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
 * Show the limit for each group involved in the activity
 *
 * @package   mod_groupselect
 * @author    Sagar Ghimire <sagarghimire@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once('../../config.php');
require_once('limit_form.php');

global $DB, $OUTPUT;

// Get URL variables.
$id = optional_param('id', 0, PARAM_INT);

$cm = get_coursemodule_from_id('groupselect', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array(
    'id' => $cm->course
), '*', MUST_EXIST);
$context = context_course::instance($course->id);
require_capability('mod/groupselect:overridegrouplimit', $context);

$url = new moodle_url('/mod/groupselect/limits.php');
if ($id) {
    $url->param('id', $id);
}
$returnurl = $CFG->wwwroot.'/mod/groupselect/view.php?id='.$id;
$groupselect = $DB->get_record('groupselect', array(
    'id' => $cm->instance
), '*', MUST_EXIST);

require_login($course);

$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->navbar->add('Limit');

$editoroptions = array(
    'instanceid' => $cm->instance,
    'targetgrouping' => $groupselect->targetgrouping
);

// Fetch groups for editing form.
$groups = groups_get_all_groups( $course->id, 0, $groupselect->targetgrouping );

$keys = array_keys($groups);
$count = count($groups);

// Fetch group limits.
$dblimit = $DB->get_records(
    'groupselect_groups_limits',
    array('instance_id' => $cm->instance),
    '',
    'id, grouplimit, groupid'
);

$limits = array();
foreach ($dblimit as $limit) {
    $limits[$limit->groupid] = $limit->grouplimit;
}

$formdata = new stdClass();
for ($i = 0; $i < $count; $i++) {
    $groupid = $groups[$keys[$i]]->id;
    $limitnum = 'limit_'.$groupid;
    $formdata->$limitnum = isset($limits[$groupid]) ? $limits[$groupid] : '';
}

// First create the form.
$editform = new limit_form($url, $editoroptions);
$editform->set_data($formdata);

if ($editform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $editform->get_data()) {
    if (!property_exists($data, 'num')) {
        redirect($returnurl);
    }

    $num = $data->num;
    $keys = get_object_vars($data);
    $keys = array_keys($keys);

    for ($i = 0; $i < $num; $i++) {

        $groupid = str_replace('limit_', '', $keys[$i]);
        $index = $keys[$i];
        $limit = $data->$index;

        // Fetch the limit.
        $recordid = $DB->get_field('groupselect_groups_limits', 'id', array('groupid' => $groupid, 'instance_id' => $cm->instance));

        // Fetch Minimum and Maximum members for the groupselect.
        $minmax = $DB->get_record('groupselect', array('id' => $cm->instance), 'maxmembers, minmembers');

        // If empty delete records.
        if ($limit === '' && $recordid) {
            $args = array(
                'id' => $recordid
            );
            // Update record.
            $DB->delete_records('groupselect_groups_limits', $args);
        }

        // Check if data exists.
        if ($recordid && $limit !== '') {
            $updateargs = new stdClass();
            $updateargs->id = $recordid;
            $updateargs->grouplimit = $limit;
            // Update record.
            $DB->update_record('groupselect_groups_limits', $updateargs, false);
        }

        // Insert new record.
        if (!$recordid && $limit !== '') {
            $dataobject = new stdClass();
            $dataobject->groupid    = $groupid;
            $dataobject->grouplimit = $limit;
            $dataobject->instance_id = $cm->instance;
            $DB->insert_record('groupselect_groups_limits', $dataobject, false, false);
        }
    }

    redirect($returnurl);
}

// Print header.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('setlimits', 'mod_groupselect'));

// Add tabs.
if (!empty($groups) && has_capability('mod/groupselect:overridegrouplimit', $context)) {
    $currenttab = 'limits';
    require('tabs.php');
}

$editform->display();
echo $OUTPUT->footer();
