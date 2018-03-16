<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main group self selection interface
 *
 * @package    mod_groupselect
 * @copyright  2018 HTW Chur Roger Barras
 * @copyright 2008-2011 Petr Skoda (http://skodak.org)
 * @copyright 2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');
require_once('locallib.php');
require_once('select_form.php');
require_once('create_form.php');

$PAGE->requires->jquery_plugin('groupselect-jeditable', 'mod_groupselect');

$id = optional_param( 'id', 0, PARAM_INT ); // Course Module ID, or
$g = optional_param( 'g', 0, PARAM_INT ); // Page instance ID
$select = optional_param( 'select', 0, PARAM_INT );
$unselect = optional_param( 'unselect', 0, PARAM_INT );
$confirm = optional_param( 'confirm', 0, PARAM_BOOL );
$create = optional_param( 'create', 0, PARAM_BOOL );
$password = optional_param( 'group_password', 0, PARAM_BOOL );
$export = optional_param( 'export', 0, PARAM_BOOL );
$assign = optional_param( 'assign', 0, PARAM_BOOL );
$unassign = optional_param( 'unassign', 0, PARAM_BOOL );
$groupid = optional_param( 'groupid', 0, PARAM_INT );
$newdescription = optional_param( 'newdescription', 0, PARAM_TEXT );

if ($g) {
    $groupselect = $DB->get_record( 'groupselect', array (
            'id' => $g
    ), '*', MUST_EXIST );
    $cm = get_coursemodule_from_instance( 'groupselect', $groupselect->id, $groupselect->course, false, MUST_EXIST );
} else {
    $cm = get_coursemodule_from_id( 'groupselect', $id, 0, false, MUST_EXIST );
    $groupselect = $DB->get_record( 'groupselect', array (
            'id' => $cm->instance
    ), '*', MUST_EXIST );
}

$course = $DB->get_record( 'course', array (
        'id' => $cm->course
), '*', MUST_EXIST );

require_login( $course, true, $cm );
$context = context_module::instance( $cm->id );

$PAGE->set_url( '/mod/groupselect/view.php', array (
        'id' => $cm->id
) );
$PAGE->add_body_class( 'mod_groupselect' );
$PAGE->set_title( $course->shortname . ': ' . $groupselect->name );
$PAGE->set_heading( $course->fullname );
$PAGE->set_activity_record( $groupselect );

$event = \mod_groupselect\event\course_module_viewed::create(array(
    'objectid' => $groupselect->id,
    'context' => $context,
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('groupselect', $groupselect);
$event->trigger();

$mygroups = groups_get_all_groups( $course->id, $USER->id, $groupselect->targetgrouping, 'g.*' );
$isopen = groupselect_is_open( $groupselect );
$groupmode = groups_get_activity_groupmode( $cm, $course );
$counts = groupselect_group_member_counts( $cm, $groupselect->targetgrouping );
$groups = groups_get_all_groups( $course->id, 0, $groupselect->targetgrouping );
$passwordgroups = groupselect_get_password_protected_groups( $groupselect );
$hidefullgroups = $groupselect->hidefullgroups;
$exporturl = '';


// Course specific supervision roles.
if (property_exists($groupselect, "supervisionrole") && $groupselect->supervisionrole > 0) {
    $assignrole = $groupselect->supervisionrole;
} else {
    $teacherrole = $DB->get_record( 'role', array (
        'shortname' => "teacher"
    ), '*', IGNORE_MISSING);
    // Assign non-editing teachers.
    if (empty($teacherrole)) {
        $assignrole = 4; // 4 is the moodle default value for the non-editing teachers.
    } else {
        $assignrole = $teacherrole->id;
    }
}

// Permissions.
$accessall = has_capability( 'moodle/site:accessallgroups', $context );
$viewfullnames = has_capability( 'moodle/site:viewfullnames', $context );

// multi group selection prerequisite

$alreadyassigned = count ( $DB->get_records( 'groupselect_groups_teachers', array (
                            'instance_id' => $groupselect->id
                            ) ) ) > 0 ? true : false;

$canselect = (has_capability( 'mod/groupselect:select', $context ) and is_enrolled( $context ) and (empty( $mygroups )
                or count( $mygroups ) < $groupselect->maxgroupmembership) and $groupselect->studentcanjoin);
$canunselect = (has_capability('mod/groupselect:unselect', $context) and is_enrolled($context) and !empty($mygroups) and
                $groupselect->studentcanleave);
$cancreate = ($groupselect->studentcancreate and has_capability( 'mod/groupselect:create', $context ) and is_enrolled( $context )
                and (count($mygroups) < $groupselect->maxgroupmembership));
$canexport = (has_capability( 'mod/groupselect:export', $context ) and count( $groups ) > 0);
$canassign = (has_capability( 'mod/groupselect:assign', $context ) and $groupselect->assignteachers
            and (count(groupselect_get_context_members_by_role( context_course::instance( $course->id )->id, $assignrole )) > 0));
$canunassign = (has_capability( 'mod/groupselect:assign', $context ) and $alreadyassigned);
$canedit = ($groupselect->studentcansetdesc and $isopen);
$canmanagegroups = has_capability('moodle/course:managegroups', $context);
$cansetgroupname = ($groupselect->studentcansetgroupname);

if ($course->id == SITEID) {
    $viewothers = has_capability( 'moodle/site:viewparticipants', $context );
} else {
    $viewothers = has_capability( 'moodle/course:viewparticipants', $context );
}

$strgroup = get_string( 'group' );
$strgroupdesc = get_string( 'groupdescription', 'group' );
$strmembers = get_string( 'memberslist', 'mod_groupselect' );
$straction = get_string( 'action', 'mod_groupselect' );
$strcount = get_string( 'membercount', 'mod_groupselect' );

// Problem notification.
$problems = array ();

if (! is_enrolled( $context )) {
    $problems[] = get_string( 'cannotselectnoenrol', 'mod_groupselect' );
} else {
    if (! has_capability( 'mod/groupselect:select', $context )) {
        $problems[] = get_string( 'cannotselectnocap', 'mod_groupselect' );
    } else if ($groupselect->timedue != 0 and $groupselect->timedue < time() and ($groupselect->notifyexpiredselection)) {
        $problems[] = get_string( 'notavailableanymore', 'mod_groupselect', userdate( $groupselect->timedue ) );
    }
}

// Group description edit.
if ($groupid and (($canedit and isset($mygroups[$groupid])) or ($canmanagegroups)) and data_submitted()) {
    $egroup = $DB->get_record_sql("SELECT *
                                 FROM {groups} g
                                WHERE g.id = ?", array($groupid));
    if (strlen($newdescription) > create_form::DESCRIPTION_MAXLEN) {
        $newdescription = substr($newdescription, 0, create_form::DESCRIPTION_MAXLEN);
    }
    $egroup->description = $newdescription;
    groups_update_group($egroup);

    echo strip_tags(groupselect_get_group_info($egroup));
    die;
}

// Student group self-creation.
if ($cancreate and $isopen) {
    $data = array (
            'id' => $id,
            'description' => ''
    );
    $mform = new create_form( null, array (
            $data,
            $groupselect
    ) );
    if ($mform->is_cancelled()) {
        redirect( $PAGE->url );
    }
    if ($formdata = $mform->get_data ()) {
        /* Create a new group and add the creator as a member of it */
        $params = array (
            $course->id
        );

        if (!$formdata->groupname) {
            $names = $DB->get_records_sql( "SELECT g.name
                       FROM {groups} g
                      WHERE g.courseid = ?", $params );

            $max = 0;
            foreach ($names as $n) {
                if (intval( $n->name ) >= $max) {
                    $max = intval( $n->name );
                }
            }

            $groupname = strval( $max + 1 );
        } else {
            $groupname = $formdata->groupname;
        }

        $data = ( object ) array (
                'name' => $groupname,
                'description' => $formdata->description,
                'courseid' => $course->id
        );
        $id = groups_create_group( $data, false );
        if ($groupselect->targetgrouping != 0) {
            groups_assign_grouping( $groupselect->targetgrouping, $id );
        }

        groups_add_member( $id, $USER->id );

        if ($formdata->password !== '') {
            $passworddata = ( object ) array (
                    'groupid' => $id,
                    'password' => password_hash( $formdata->password, PASSWORD_DEFAULT ),
                    'instance_id' => $groupselect->id
            );
            $DB->insert_record( 'groupselect_passwords', $passworddata, false );
        }
        redirect ( $PAGE->url );
    } else if ($create or $mform->is_submitted()) {
        /* If create button was clicked, show the form
         * or show validation errors
         */
        echo $OUTPUT->header();
        echo $OUTPUT->heading( get_string( 'creategroup', 'mod_groupselect' ) );
        $mform->display();
        echo $OUTPUT->footer();
        die();
    }
}

// Student group self-selection.
if ($select and $canselect and isset( $groups[$select] ) and $isopen) {

    $grpname = format_string( $groups[$select]->name, true, array (
            'context' => $context
    ) );
    $usercount = isset( $counts[$select] ) ? $counts[$select]->usercount : 0;

    $data = array (
            'id' => $id,
            'select' => $select,
            'group_password' => $password
    );
    $mform = new select_form( null, array (
            $data,
            $groupselect,
            $grpname
    ) );

    if ($mform->is_cancelled()) {
        redirect ( $PAGE->url );
    }

    if (! $isopen) {
        $problems[] = get_string( 'cannotselectclosed', 'mod_groupselect' );
    } else if ($groupselect->maxmembers and $groupselect->maxmembers <= $usercount) {
        $problems[] = get_string( 'cannotselectmaxed', 'mod_groupselect', $grpname );
    } else if ($return = $mform->get_data()) {
        groups_add_member( $select, $USER->id );

        redirect ( $PAGE->url );
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading( get_string( 'select', 'mod_groupselect', $grpname ) );
        echo $OUTPUT->box_start( 'generalbox', 'notice' );
        echo '<p>' . get_string( 'selectconfirm', 'mod_groupselect', $grpname ) . '</p>';
        $mform->display();
        echo $OUTPUT->box_end();
        echo $OUTPUT->footer();
        die();
    }
} else if ($unselect and $canunselect and isset( $mygroups[$unselect] )) {
    // User unselected group.

    if (! $isopen) {
        $problems[] = get_string( 'cannotunselectclosed', 'mod_groupselect' );
    } else if ($confirm and data_submitted() and confirm_sesskey()) {
        groups_remove_member( $unselect, $USER->id );
        if ($groupselect->deleteemptygroups and ! groups_get_members( $unselect )) {
            groups_delete_group( $unselect );
            $DB->delete_records( 'groupselect_passwords', array (
                    'groupid' => $unselect
            ) );
            $DB->delete_records( 'groupselect_groups_teachers', array (
                    'groupid' => $unselect
            ) );
        }

        redirect ( $PAGE->url );
    } else {
        $grpname = format_string( $mygroups[$unselect]->name, true, array (
                'context' => $context
        ) );
        echo $OUTPUT->header();
        echo $OUTPUT->heading( get_string( 'unselect', 'mod_groupselect', $grpname ) );
        $yesurl = new moodle_url( '/mod/groupselect/view.php', array (
                'id' => $cm->id,
                'unselect' => $unselect,
                'confirm' => 1,
                'sesskey' => sesskey()
        ) );
        $message = get_string( 'unselectconfirm', 'mod_groupselect', $grpname );
        echo $OUTPUT->confirm( $message, $yesurl, $PAGE->url );
        echo $OUTPUT->footer();
        die();
    }
}

// Group user data export.
if ($export and $canexport) {
    // Fetch groups & assigned teachers.
    $params = ['cmid' => $id, 'courseid' => $course->id, 'instanceid' => $groupselect->id];
    $groupingsql = '';
    if ($groupselect->targetgrouping) {
        $groupingsql = "JOIN {groupings_groups} gg ON gg.groupid = g.id AND gg.groupingid = :grouping";
        $params['grouping'] = $groupselect->targetgrouping;
    }
    $sql = "SELECT g.id AS groupid, g.name, g.description, u.username, u.firstname, u.lastname, u.email
              FROM {groups} g
                 $groupingsql
         LEFT JOIN {groupselect_groups_teachers} gt
                ON g.id = gt.groupid AND gt.instance_id = :instanceid
         LEFT JOIN {user} u
                ON u.id = gt.teacherid
             WHERE g.courseid = :courseid
          ORDER BY g.id ASC";
    $grouplist = $DB->get_records_sql( $sql, $params );

    // Fetch students & groups.
    $sql = "SELECT m.id, u.username, u.idnumber, u.firstname, u.lastname, u.email, g.id AS groupid
            FROM   {groups} g
            $groupingsql
            JOIN {groups_members} m ON g.id = m.groupid
            JOIN {user} u ON u.id = m.userid
            WHERE  g.courseid = :courseid
            ORDER BY groupid ASC";

    $students = $DB->get_records_sql( $sql, $params );

    // Fetch max number of students in a group (may differ from setting, because teacher may add members w/o limits).

    $sql = "SELECT MAX(t.memberscount) AS max
            FROM (
                SELECT g.id, COUNT(m.userid) AS memberscount
                FROM {groups} g
                $groupingsql
                JOIN {groups_members} m on m.groupid = g.id
                WHERE g.courseid = :courseid
                GROUP BY g.id
            ) t
            ";

    $maxgroupsize = $DB->get_records_sql( $sql, $params );
    $maxgroupsize = array_pop($maxgroupsize)->max;

    foreach ($students as $student) {
        $gid = $student->groupid;
        foreach ($grouplist as $group) {
            if ($gid === $group->groupid) {
                for ($i = 1; $i < intval($maxgroupsize) + 1; $i++) {
                    if (!isset($group->$i)) {
                        $group->$i = $student;
                        break;
                    }
                }
            }
        }
    }

    // Format data to csv.
    $quote = '"';
    $charstoescape = array(
                        $quote => $quote.$quote
                        );
    $assignedteacher = 'Assigned teacher ';
    $groupmember = 'Member ';
    $header = array(
    // get_string ( 'groupid', 'mod_groupselect' ),
    // get_string ( 'groupname', 'group' ),
    // get_string ( 'groupdescription', 'group' ),
    // get_string ( 'assignedteacher', 'mod_groupselect' ) . ' ' . get_string ( 'username' ),
    // get_string ( 'assignedteacher', 'mod_groupselect' ) . ' ' . get_string ( 'firstname' ),
    // get_string ( 'assignedteacher', 'mod_groupselect' ) . ' ' . get_string ( 'lastname' ),
    // get_string ( 'assignedteacher', 'mod_groupselect' ) . ' ' . get_string ( 'email' )

        'Group ID',
        'Group Name',
        'Group Size',
        'Group Description',
    $assignedteacher . 'Username',
    $assignedteacher . 'Firstname',
    $assignedteacher . 'Lastname',
    $assignedteacher . 'Email',
            );

    for ($i = 0; $i < $maxgroupsize; $i++) {
        // $header[] = get_string('member', 'mod_groupselect').' '.strval($i+1).' '. get_string ( 'username' );
        // $header[] = get_string('member', 'mod_groupselect').' '.strval($i+1).' '. get_string ( 'idnumber' );
        // $header[] = get_string('member', 'mod_groupselect').' '.strval($i+1).' '. get_string ( 'firstname' );
        // $header[] = get_string('member', 'mod_groupselect').' '.strval($i+1).' '. get_string ( 'lastname' );
        // $header[] = get_string('member', 'mod_groupselect').' '.strval($i+1).' '. get_string ( 'email' );

        $header[] = $groupmember.strval($i + 1).' '.'Username';
        $header[] = $groupmember.strval($i + 1).' '.'ID Number';
        $header[] = $groupmember.strval($i + 1).' '.'Firstname';
        $header[] = $groupmember.strval($i + 1).' '.'Lastname';
        $header[] = $groupmember.strval($i + 1).' '.'Email';
    }
    $content = implode( (','), $header ) . "\n";

    // TODO: add better export options
    // Quick workaround for Excel
    $content = 'sep=,' . "\n" . $content;

    foreach ($grouplist as $r) {
        $row = array (
                $quote.strtr($r->groupid, $charstoescape).$quote,
                $quote.strtr($r->name, $charstoescape).$quote,
                $quote.strtr($r->description, $charstoescape).$quote,
                $quote.strtr($r->username, $charstoescape).$quote,
                $quote.strtr($r->firstname, $charstoescape).$quote,
                $quote.strtr($r->lastname, $charstoescape).$quote,
                $quote.strtr($r->email, $charstoescape).$quote
        );
        $groupsize = 0;
        for ($i = 1; $i < $maxgroupsize + 1; $i++) {
            if (isset($r->$i)) {
                // First element contains group-member relation id which is not needed, so skip it
                $first = true;
                foreach ($r->$i as $memberfield) {
                    if ($first) {
                        $first = false;
                        continue;
                    }
                    $row[] = $quote.strtr($memberfield, $charstoescape).$quote;
                }
                array_pop($row);
                $groupsize++;
            }
        }
        array_splice($row, 2, 0, $quote.strval($groupsize).$quote);
        $content = $content . implode( (','), $row ) . "\n";
    }

    // File info
    $separator = '_';
    $filename = get_string( 'modulename', 'mod_groupselect' ) . $separator .
            clean_param(format_string($course->shortname), PARAM_FILE) . $separator .
                date( 'Y-m-d' ) . '.csv';
    $filename = str_replace( ' ', '', $filename );
    $fs = get_file_storage();
    $fileinfo = array (
            'contextid' => $context->id, // ID of context
            'component' => 'mod_groupselect', // usually = table name
            'filearea' => 'export', // usually = table name
            'itemid' => $groupselect->id, // usually = ID of row in table
            'filepath' => '/', // any path beginning and ending in /
            'filename' => $filename
    ); // any filename

    // See if same file exists
    $file = $fs->get_file( $fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename'] );

    // Delete already existing file
    if ($file) {
        $file->delete();
    }

    $file = $fs->create_file_from_string( $fileinfo, $content );
    // Store file url to show later
    $exporturl = moodle_url::make_pluginfile_url( $file->get_contextid(), $file->get_component(), $file->get_filearea(),
                                                  $file->get_itemid(), $file->get_filepath(), $file->get_filename());

    // event logging
    $event = \mod_groupselect\event\export_link_created::create(array(
            'context' => $context,
    ));
    $event->trigger();
}

// User wants to assign supervisors via supervisionrole
if ($assign and $canassign) {

    $coursecontext = context_course::instance( $course->id )->id;
    $teachers = groupselect_get_context_members_by_role( $coursecontext, $assignrole );
    shuffle( $teachers );

    $agroups = $groups;
    $teachercount = count($teachers);

    foreach ($teachers as $teacher) {
        $i = 0;
        $iterations = ceil( count( $agroups ) / $teachercount );
        while ( $i < $iterations ) {
            $group = array_rand( $agroups );

            unset ( $agroups[$group] );
            $newgroupteacherrelation = ( object ) array (
                    'groupid' => $group,
                    'teacherid' => $teacher->userid,
                    'instance_id' => $groupselect->id
            );

            $gsgteacherid = $DB->insert_record( 'groupselect_groups_teachers', $newgroupteacherrelation );
            $newgroupteacherrelation->id = $gsgteacherid;

            $alreadyassigned = true;
            $canunassign = true;

            // event logging
            $event = \mod_groupselect\event\group_teacher_added::create(array(
                    'context' => $context,
                    'objectid' => $gsgteacherid,
                    'relateduserid' => $teacher->userid,
                    'other' => array(
                    'groupid' => $group)
                    ));
            $event->add_record_snapshot('groupselect', $groupselect);
            $event->add_record_snapshot('groupselect_groups_teachers', $newgroupteacherrelation);
            $event->trigger();

            $i ++;
        }
        $teachercount --;
    }
} else if ($unassign and $canunassign) {
    if ($alreadyassigned) {
        $DB->delete_records('groupselect_groups_teachers', array (
                'instance_id' => $groupselect->id));
    }
    $alreadyassigned = false;
    $canunassign = false;
}

// *** PAGE OUTPUT ***
echo $OUTPUT->header();
echo $OUTPUT->heading( format_string( $groupselect->name, true, array (
        'context' => $context
) ) );

if (trim( strip_tags( $groupselect->intro ) )) {
    echo $OUTPUT->box_start( 'mod_introbox', 'groupselectintro' );
    echo format_module_intro( 'groupselect', $groupselect, $cm->id );
    echo $OUTPUT->box_end();
}

// Too few members in my group-notification.
if ($groupselect->minmembers > 0 and ! empty( $mygroups )) {
    $mygroup = array_keys( $mygroups );
    foreach ($mygroup as $group) {
        $usercount = isset( $counts[$group] ) ? $counts[$group]->usercount : 0;
        if ($groupselect->minmembers > $usercount) {
            echo $OUTPUT->notification( get_string( 'minmembers_notification', 'mod_groupselect', $groupselect->minmembers ) );
            break;
        }
    }
}

// Too many members in my group-notification.
if ($groupselect->maxmembers > 0 and ! empty( $mygroups )) {
    $mygroup = array_keys( $mygroups );
    foreach ($mygroup as $group) {
        $usercount = isset( $counts[$group] ) ? $counts[$group]->usercount : 0;
        if ($groupselect->maxmembers < $usercount) {
            echo $OUTPUT->notification( get_string( 'maxmembers_notification', 'mod_groupselect', $groupselect->maxmembers ) );
            break;
        }
    }
}

// Activity opening/closing related notificatins.
if ($groupselect->timeavailable !== 0 and $groupselect->timeavailable > time()) {
    echo $OUTPUT->notification( get_string( 'timeavailable', 'mod_groupselect' ) . ' ' . strval( userdate( $groupselect->timeavailable ) ) );
}
if ($groupselect->timedue !== 0 and $groupselect->timedue > time()) {
    echo $OUTPUT->notification( get_string( 'timedue', 'mod_groupselect' ) . ' ' . strval( userdate( $groupselect->timedue ) ) );
}

// Create group button.
if ($cancreate and $isopen and ! $create) {
    echo $OUTPUT->single_button( new moodle_url( '/mod/groupselect/view.php', array (
            'id' => $cm->id,
            'create' => true
    ) ), get_string( 'creategroup', 'mod_groupselect' ) );
}

// Export button.
if ($canexport) {
    if ($exporturl === '') {
        echo $OUTPUT->single_button( new moodle_url( '/mod/groupselect/view.php', array (
                    'id' => $cm->id,
                    'export' => true
            ) ), get_string( 'export', 'mod_groupselect' ) );
    } else {
        echo '<div class="export_url" >';
        echo $OUTPUT->action_link( $exporturl, get_string( 'export_download', 'mod_groupselect' ) );
        echo '</div> <br>';
    }
}

// Assign or unassign button.
if ($canunassign) {
    $action = new confirm_action(get_string('unassigngroup_confirm', 'mod_groupselect'));
    $button = new single_button(new moodle_url( '/mod/groupselect/view.php', array (
            'id' => $cm->id,
                        'unassign' => true
    ) ), get_string( 'unassigngroup', 'mod_groupselect' ) );
    $button->add_action($action);
    echo $OUTPUT->render($button);
} else if ($canassign and count($groups) > 0 ) {
    $button = new single_button(new moodle_url( '/mod/groupselect/view.php', array (
            'id' => $cm->id,
                        'assign' => true
    ) ), get_string( 'assigngroup', 'mod_groupselect' ) );
    echo $OUTPUT->render($button);
}

if (empty ( $groups )) {
    echo $OUTPUT->notification( get_string( 'nogroups', 'mod_groupselect' ) );
} else {
    if ($problems) {
        foreach ($problems as $problem) {
            echo $OUTPUT->notification( $problem, 'notifyproblem' );
        }
    }

    $data = array ();
    $actionpresent = false;

    $assignedrelation = $DB->get_records_sql( "SELECT g.id AS rid, g.teacherid AS id, g.groupid
                                                FROM  {groupselect_groups_teachers} g
                                                WHERE g.instance_id = ?", array (
                                                                                    'instance_id' => $groupselect->id
    ) );
    $assignedteacherids = array ();
    foreach ($assignedrelation as $r) {
        array_push( $assignedteacherids, $r->id );
    }
    $assignedteacherids = array_unique( $assignedteacherids );

    if (count ( $assignedteacherids ) > 0) {
        $sql = "SELECT   *
                      FROM   {user} u
                     WHERE ";
        foreach ($assignedteacherids as $i) {
            $sql = $sql . "u.id = ? OR ";
        }
        $sql = substr ( $sql, 0, - 3 );

        $assignedteachers = $DB->get_records_sql($sql, $assignedteacherids);
    }

    // Group list.
    foreach ($groups as $group) {
        $ismember = isset( $mygroups[$group->id] );
        $usercount = isset( $counts[$group->id] ) ? $counts[$group->id]->usercount : 0;
        $grpname = format_string( $group->name, true, array (
                'context' => $context
        ) );

        // Skips listing full groups if set.
        if (! $ismember and $hidefullgroups and $groupselect->maxmembers === $usercount) {
            continue;
        }

        if (in_array( $group->id, $passwordgroups )) {
            $group->password = true;
        } else {
            $group->password = false;
        }

        // Groupname.
        $line = array ();
        if ($ismember) {
            $line[0] = '<div class="mygroup">' . $grpname . '</div>';
        } else {
            $line[0] = $grpname;
        }

        // Group description.
        if (($ismember and $canedit) or ($canmanagegroups)) {
            $line[1] = '<div id="' . $group->id . '" class="editable_textarea" role="button" tabindex="1">' .
                strip_tags(groupselect_get_group_info( $group ))
                . '</div>';
        } else {
            $line[1] = strip_tags(groupselect_get_group_info( $group ));
        }

        // Member count.
        if ($groupselect->maxmembers) {
            $line[2] = $usercount . '/' . $groupselect->maxmembers;
        } else {
            $line[2] = $usercount;
        }

        if ($accessall) {
            $canseemembers = true;
        } else {
            if ($groupmode == SEPARATEGROUPS and !$ismember) {
                $canseemembers = false;
            } else {
                $canseemembers = $viewothers;
            }
        }

        // Group members.
        if ($canseemembers) {
            if ($members = groups_get_members( $group->id )) {
                $membernames = array ();
                foreach ($members as $member) {
                    $pic = $OUTPUT->user_picture( $member, array (
                            'courseid' => $course->id
                    ) );
                    if ($member->id == $USER->id) {
                        $membernames[] = '<span class="me">' . $pic . '&nbsp;' . fullname( $member, $viewfullnames ) . '</span>';
                    } else {
                        $membernames[] = $pic . '&nbsp;<a href="' . $CFG->wwwroot . '/user/view.php?id=' . $member->id .
                                          '&amp;course=' . $course->id . '">' . fullname( $member, $viewfullnames ) . '</a>';
                    }
                }
                // Show assigned teacher, if exists, when enabled or when user is non-assigned teacher
                if ($groupselect->showassignedteacher or user_has_role_assignment($USER->id, $assignrole, context_course::instance( $course->id )->id)) {
                    $teacherid = null;
                    foreach ($assignedrelation as $r) {
                        if ($r->groupid === $group->id) {
                            $teacherid = $r->id;
                            break;
                        }
                    }
                    if ($teacherid) {
                        $teacher = null;
                        foreach ($assignedteachers as $a) {
                            if ($a->id === $teacherid) {
                                $teacher = $a;
                                $break;
                            }
                        }
                        $pic = $OUTPUT->user_picture( $teacher, array (
                                'courseid' => $course->id
                        ) );
                        if ($teacher->id == $USER->id) {
                            $membernames[] = '<span class="me">' . $pic . '&nbsp;' . fullname( $teacher, $viewfullnames ) .
                                              ' (' . get_string( 'assignedteacher', 'mod_groupselect' ) . ')'.'</span>';
                        } else {
                            $membernames[] = $pic . '&nbsp;<a href="' . $CFG->wwwroot . '/user/view.php?id=' . $teacher->id .
                                              '&amp;course=' . $course->id . '">' . fullname( $teacher, $viewfullnames ) .
                                              ' (' . get_string( 'assignedteacher', 'mod_groupselect' ) . ')</a>';
                        }
                    }
                }
                $line[3] = implode( ', ', $membernames );
            } else {
                $line[3] = '';
            }
        } else {
            $line[3] = '<div class="membershidden">' . get_string( 'membershidden', 'mod_groupselect' ) . '</div>';
        }

        // Icons.
        $line[4] = '<div class="icons">';
        if ($groupselect->minmembers > $usercount) {
            $line[4] = $line[4] . $OUTPUT->pix_icon( 'i/risk_xss', get_string( 'minmembers_icon', 'mod_groupselect' ), null,
                array (
                    'align' => 'left'
                )
            );
        }
        if ($groupselect->maxmembers > 0 && $groupselect->maxmembers < $usercount ) {
            $line[4] = $line[4] . $OUTPUT->pix_icon( 'i/risk_xss', get_string( 'maxmembers_icon', 'mod_groupselect' ), null,
                array (
                    'align' => 'left'
                )
            );
        }
        if ($group->password) {
            $line[4] = $line[4] . $OUTPUT->pix_icon( 't/locked', get_string( 'password', 'mod_groupselect' ), null, array (
                    'align' => 'right'
            ) );
        }
        $line[4] = $line[4] . '</div>';

        // Action buttons.
        if ($isopen) {
            if (! $ismember and $canselect and $groupselect->maxmembers and $groupselect->maxmembers <= $usercount) {
                $line[5] = '<div class="maxlimitreached">' . get_string( 'maxlimitreached', 'mod_groupselect' ) . '</div>'; // full - no more members
                $actionpresent = true;
            } else if ($ismember and $canunselect) {
                $line[5] = $OUTPUT->single_button( new moodle_url( '/mod/groupselect/view.php', array (
                        'id' => $cm->id,
                        'unselect' => $group->id
                ) ), get_string( 'unselect', 'mod_groupselect', "") );
                $actionpresent = true;
            } else if (! $ismember and $canselect) {
                $line[5] = $OUTPUT->single_button( new moodle_url( '/mod/groupselect/view.php', array (
                        'id' => $cm->id,
                        'select' => $group->id,
                        'group_password' => $group->password
                ) ), get_string ( 'select', 'mod_groupselect', "") );
                $actionpresent = true;
            } else {
                $line[5] = '';
            }
        }
        if (!$ismember) {
            $data[] = $line;
        } else {
            array_unshift($data, $line);
        }
    }

    $sortscript = file_get_contents( './lib/sorttable/sorttable.js' );
    echo html_writer::script( $sortscript );
    $table = new html_table();
    $table->attributes = array (
            'class' => 'generaltable sortable groupselect-table',
    );
    $table->head = array (
            $strgroup,
            $strgroupdesc,
            $strcount,
            $strmembers,
            ''
    );
    if ($actionpresent) {
        array_push($table->head, $straction);
    }

    $table->data = $data;
    echo html_writer::table( $table );
}

echo $OUTPUT->footer();
$url = $PAGE->url;
// Group description edit JS.
if ($canedit or $canmanagegroups) {
    echo '<script type="text/javascript">$(document).ready(function() {
        $(".editable_textarea").editable("' . $url .'", {
            id        : "groupid",
            name      : "newdescription",
            type      : "textarea",
            height    : "90%",
            width     : "90%",
            submit    : "'.get_string('ok', 'mod_groupselect').'",
            indicator : "'.get_string('saving', 'mod_groupselect').'",
            tooltip   : "'.get_string('edittooltip', 'mod_groupselect').'",
            placeholder: "'.get_string('edittooltip', 'mod_groupselect').'"
        });
    });</script>';
}
