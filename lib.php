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
 * Library of functions and constants of Group selection module
 *
 * @package    mod_groupselect
 * @copyright  2018 HTW Chur Roger Barras
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * List of features supported in groupselect module
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function groupselect_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_OTHER;
        case FEATURE_GROUPS:
            return true;  // Only separate mode makes sense - you hide members of other groups here.
        case FEATURE_GROUPINGS:
            return false; // Should be true. Separate setting in groupselect.
        case FEATURE_GROUPMEMBERSONLY:
            return false; // This could be very confusing.
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;

        default:
            return null;
    }
}

/**
 * Returns all other caps used in module
 * @return array
 */
function groupselect_get_extra_capabilities() {
    return array('moodle/site:accessallgroups', 'moodle/site:viewfullnames');
}

/**
 * Given an object containing all the necessary data, (defined by the form in mod.html)
 * this function will create a new instance and return the id number of the new instance.
 *
 * @param object $groupselect Object containing all the necessary data defined by the form in mod_form.php
 * $return int The id of the newly created instance
 */
function groupselect_add_instance($groupselect) {
    global $DB;

    $groupselect->timecreated = time();
    $groupselect->timemodified = time();

    $groupselect->id = $DB->insert_record('groupselect', $groupselect);

    // Add calendar events if necessary.
    groupselect_set_events($groupselect);
    if (!empty($groupselect->completionexpected)) {
        \core_completion\api::update_completion_date_event($groupselect->coursemodule, 'groupselect', $groupselect->id,
                $groupselect->completionexpected);
    }

    return $groupselect->id;
}


/**
 * Update an existing instance with new data.
 *
 * @param object $groupselect An object containing all the necessary data defined by the mod_form.php
 * @return bool
 */
function groupselect_update_instance($groupselect) {
    global $DB;

    $groupselect->timemodified = time();
    $groupselect->id = $groupselect->instance;

    $DB->update_record('groupselect', $groupselect);

    // Add calendar events if necessary.
    groupselect_set_events($groupselect);
    if (!empty($groupselect->completionexpected)) {
        \core_completion\api::update_completion_date_event($groupselect->coursemodule, 'groupselect', $groupselect->id,
                $groupselect->completionexpected);
    }

    return true;
}


/**
 * Permanently delete the instance of the module and any data that depends on it.
 *
 * @param int $id Instance id
 * @return bool
 */
function groupselect_delete_instance($id) {
    global $DB;
    // Delete group password rows related to this instance (but not the groups).
    $DB->delete_records('groupselect_passwords', array('instance_id' => $id));

    $DB->delete_records('groupselect_groups_teachers', array('instance_id' => $id));

    $DB->delete_records('groupselect', array('id' => $id));

    return true;
}

/**
 * This standard function will check all instances of this module
 * and make sure there are up-to-date events created for each of them.
 * If courseid = 0, then every chat event in the site is checked, else
 * only chat events belonging to the course specified are checked.
 * This function is used, in its new format, by restore_refresh_events()
 *
 * @param int $courseid
 * @return bool
 */
function groupselect_refresh_events($courseid = 0) {
    global $DB;

    $params = $courseid ? ['course' => $courseid] : [];
    $modules = $DB->get_records('groupselect', $params);

    foreach ($modules as $module) {
        groupselect_set_events($module);
    }
    return true;
}

/**
 * This creates new events given as timeopen and closeopen by $groupselect.
 *
 * @param stdClass $groupselect
 * @return void
 */
function groupselect_set_events($groupselect) {
    global $DB, $CFG;

    // Include calendar/lib.php.
    require_once($CFG->dirroot.'/calendar/lib.php');
    require_once($CFG->dirroot . '/mod/groupselect/locallib.php');

    // Get CMID if not sent as part of $groupselect.
    if (!isset($groupselect->coursemodule)) {
        $cm = get_coursemodule_from_instance('groupselect',
                $groupselect->id, $groupselect->course);
        $groupselect->coursemodule = $cm->id;
    }

    // Get old event.
    $oldevent = null;
    $oldevent = $DB->get_record('event',
    array('modulename' => 'groupselect',
        'instance' => $groupselect->id, 'eventtype' => GROUPSELECT_EVENT_TYPE_DUE));

    if ($groupselect->timedue) {
        // Create calendar event.
        $event = new stdClass();
        $event->type = CALENDAR_EVENT_TYPE_ACTION;
        $event->name = $groupselect->name .' ('.get_string('duedate', 'groupselect').')';
        $event->description = format_module_intro('groupselect', $groupselect, $groupselect->coursemodule);
        $event->courseid = $groupselect->course;
        $event->groupid = 0;
        $event->userid = 0;
        $event->modulename = 'groupselect';
        $event->instance = $groupselect->id;
        $event->eventtype = GROUPSELECT_EVENT_TYPE_DUE;
        $event->visible   = instance_is_visible('groupselect', $groupselect);
        $event->timestart = $groupselect->timedue;
        $event->timeduration = 0;
        $event->timesort = $event->timestart + $event->timeduration;

        if ($oldevent) {
            $event->id = $oldevent->id;
        } else {
            unset($event->id);
        }
        // Create also updates an existing event.
        calendar_event::create($event);
    } else {
        // Delete calendar event.
        if ($oldevent) {
            $calendarevent = calendar_event::load($oldevent);
            $calendarevent->delete();
        }
    }
}


/**
 * Returns the users with data in this module
 *
 * We have no data/users here but this must exists in every module
 *
 * @param int $groupselectid
 * @return bool
 */
function groupselect_get_participants($groupselectid) {
    // No participants here - all data is stored in the group tables.
    return false;
}


/**
 * groupselect_get_view_actions
 *
 * @return array
 */
function groupselect_get_view_actions() {
    return array('view', 'export');
}


/**
 * groupselect_get_post_actions
 *
 * @return array
 */
function groupselect_get_post_actions() {
    return array('select', 'unselect', 'create', 'assign');
}


/**
 * Used to create exportable csv-file in view.php
 *
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function groupselect_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'export') { // && $filearea !== 'anotherexpectedfilearea') {
        return false;
    }

    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    require_login($course, true, $cm);

    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    if (!has_capability('mod/groupselect:export', $context)) {
        return false;
    }

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // The $args is empty => the path is '/'.
    } else {
        $filepath = '/'.implode('/', $args).'/'; // The $args contains elements of the filepath.
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'mod_groupselect', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    // From Moodle 2.3, use send_stored_file instead.
    send_stored_file($file, 86400, 0, 'true', $options);
}

/**
 * Callback function that determines whether an action event should be showing its item count
 * based on the event type and the item count.
 *
 * @param calendar_event $event The calendar event.
 * @param int $itemcount The item count associated with the action event.
 * @return bool
 */
function groupselect_core_calendar_event_action_shows_item_count(calendar_event $event, $itemcount = 0) {
    return $itemcount > 1;
}

function groupselect_core_calendar_provide_event_action(calendar_event $event,
                                                        \core_calendar\action_factory $factory) {
    $cm = get_fast_modinfo($event->courseid)->instances['groupselect'][$event->instance];
    $context = context_module::instance($cm->id);
    $itmecount = 1;
    $actionable = true;

    $completion = new \completion_info($cm->get_course());
    $completiondata = $completion->get_data($cm, false);

    if ($completiondata->completionstate != COMPLETION_INCOMPLETE) {
        return null;
    }

    if (!has_capability('mod/groupselect:select', $context)) {
        $actionable = false;
    }

    return $factory->create_instance(
        get_string('selectgroupaction', 'groupselect'),
        new \moodle_url('/mod/groupselect/view.php', array('id' => $cm->id)),
        $itmecount,
        $actionable
    );
}

/**
 * Extends the settings navigation
 *
 * @param settings_navigation $settingsnav complete settings navigation tree
 * @param navigation_node $groupselectnode Groupselect administration node
 */
function groupselect_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $groupselectnode) {
    global $PAGE;

    $cm = $PAGE->cm;
    if (!$cm) {
        return;
    }

    $context = $cm->context;
    $course = $PAGE->course;

    if (!$course) {
        return;
    }

    // We want to add these new nodes after the Edit settings node, and before the
    // Locally assigned roles node. Of course, both of those are controlled by capabilities.
    $keys = $groupselectnode->get_children_key_list();
    $beforekey = null;
    $i = array_search('modedit', $keys);
    if ($i === false and array_key_exists(0, $keys)) {
        $beforekey = $keys[0];
    } else if (array_key_exists($i + 1, $keys)) {
        $beforekey = $keys[$i + 1];
    }

    // Add the navigation items.
    if (has_capability('moodle/course:managegroups', $context)) {
        $groupselectnode->add_node(navigation_node::create(get_string('groups'),
            new moodle_url('/group/index.php', array('id' => $course->id)),
            navigation_node::TYPE_SETTING, null, 'mod_groupselect_groups',
            new pix_icon('i/group', '')), $beforekey);
    }
}


/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the groupselect.
 * @param moodleform $mform form passed by reference
 */
function groupselect_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'groupselectheader', get_string('modulenameplural', 'mod_groupselect'));
    $mform->addElement('advcheckbox', 'reset_groupselect_passwords',
        get_string('deleteallgrouppasswords', 'mod_groupselect'));
    $mform->addElement('advcheckbox', 'reset_groupselect_supervisors',
        get_string('removeallsupervisors', 'mod_groupselect'));
}

/**
 * Course reset form defaults.
 * @param  object $course
 * @return array
 */
function groupselect_reset_course_form_defaults($course) {
    return array('reset_groupselect_passwords' => 1,
            'reset_groupselect_supervisors' => 0);
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all group supervisors and delete all group passwords
 *
 * @param stdClass $data the data submitted from the reset course.
 * @return array
 */
function groupselect_reset_userdata($data) {
    global $DB;

    $status = array();
    $params = array();

    $componentstr = get_string('modulenameplural', 'mod_groupselect');

    if (!empty($data->reset_groupselect_passwords)) {
        if ($groupselections = $DB->get_records('groupselect', array('course' => $data->courseid), '', 'id')) {
            list($groupselect, $params) = $DB->get_in_or_equal(array_keys($groupselections), SQL_PARAMS_NAMED);
            $DB->delete_records_select('groupselect_passwords', 'instance_id '.$groupselect, $params);

            $status[] = array('component' => $componentstr,
            'item' => get_string('deleteallgrouppasswords', 'mod_groupselect'),
            'error' => false);
        }
    }
    if (!empty($data->reset_groupselect_supervisors)) {
        if ($groupselections = $DB->get_records('groupselect', array('course' => $data->courseid), '', 'id')) {
            list($groupselect, $params) = $DB->get_in_or_equal(array_keys($groupselections), SQL_PARAMS_NAMED);
            $DB->delete_records_select('groupselect_groups_teachers', 'instance_id '.$groupselect, $params);

            $status[] = array('component' => $componentstr,
            'item' => get_string('removeallsupervisors', 'mod_groupselect'),
            'error' => false);
        }
    }

    return $status;
}