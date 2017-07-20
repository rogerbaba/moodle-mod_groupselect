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
 * List of all groupselection modules in course
 * This view is normally not seen by the ordinary user
 *
 * @package    mod
 * @subpackage groupselect
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 // PREAMBLE
 defined('MOODLE_INTERNAL') || die();

require('../../config.php');

// PARAMETER CHECK

$id = required_param('id', PARAM_INT); // course id

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_course_login($course, true);

// FIXME secure this script using privileges

// STATIC STRINGS

$strgroupselect  = get_string('modulename', 'mod_groupselect');
$strgroupselects = get_string('modulenameplural', 'mod_groupselect');
$strname         = get_string('name');
$strintro        = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

// DYNAMIC STRINGS
$strsectionname  = get_string('sectionname', 'format_'.$course->format);


// LOG EVENT
// add_to_log($course->id, 'groupselect', 'view all', "index.php?id=$course->id", '');
\mod_groupselect\event\course_module_instance_list_viewed::create(array('context' => context_course::instance($course->id)))->trigger();

// SETUP VIEW
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/mod/groupselect/index.php', array('id' => $id));
$PAGE->set_title($course->shortname.': '.$strgroupselects);

// SETUP VIEW CONTENT
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strgroupselects);

$output = $PAGE->get_renderer('mod_groupselect');
echo $output->header();

// DATA LOGIC

// NO ACTIVIY DEFINED
if (!$groupselects = get_all_instances_in_course('groupselect', $course)) {
    // FIXME: better show an informative message
    notice(get_string('thereareno', 'moodle', $strgroupselects), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);
if ($usesections) {
    $sections = get_fast_modinfo($course)->get_section_info_all();
}

// VIEW SETUP
$viewContext = new \stdClass;

$viewContext->use_sections = false;
$viewContext->activities  = [];
$viewContext->lang_string = new \stdClass;

$viewContext->lang_string->name = $strname;
$viewContext->lang_string->intro = $strintro;

if ($usesections) {
    $viewContext->use_sections = true;
    $viewContext->lang_string->section_name = $strsectionname;
}
else {
    $viewContext->lang_string->last_modified = $strlastmodified;
}


$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($groupselects as $groupselect) {
    $cm = $modinfo->cms[$groupselect->coursemodule];

    $activity = new \stdClass;

    $activity->id            =  $cm->id;
    $activity->link          = "view.php?id=$cm->id";
    $activity->name          = $groupselect->name;
    $activity->info          = format_module_intro('groupselect', $groupselect, $cm->id);
    $activity->time_modified = userdate($groupselect->timemodified);
    $activity->visible       = $groupselect->visible;

    if ($usesections) {
        $activity->section = new \stdClass;
        $activity->section->name    = $sections[$groupselect->section]->name;
        $activity->section->number  = $sections[$groupselect->section]->section;
        $activity->section->id      = $sections[$groupselect->section]->id;
        $activity->section->visible = $sections[$groupselect->section]->visible;

        // user visible is critical because the users MUST NOT see information
        // that they could not otherwise access.
        $activity->section->hidden  = $sections[$groupselect->section]->uservisible;
        $activity->hidden  = $sections[$groupselect->section]->uservisible;
    }

    $viewContext->activities[] = $activity;
}

// FIXME Create template
echo $output->render_from_template("mod_groupselect/activty_list", viewContext);

echo $output->footer();
