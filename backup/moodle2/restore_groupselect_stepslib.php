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
 * Define all the restore steps that will be used by the restore_groupselect_activity_task
 *
 * @package    mod_groupselect
 * @copyright  2018 HTW Chur Roger Barras
 * @copyright  2011 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Structure step to restore one groupselect activity.
 *
 * @copyright  2011 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_groupselect_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('groupselect', '/activity/groupselect');
        if ($userinfo) {
            $paths[] = new restore_path_element('groupselect_groups_teachers', '/activity/groupselect/groupteachers/groupteacher');
        }
        $paths[] = new restore_path_element('groupselect_passwords', '/activity/groupselect/passwords/password');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    protected function process_groupselect($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timeavailable = $this->apply_date_offset($data->timeavailable);
        $data->timedue = $this->apply_date_offset($data->timedue);
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        if (!empty($data->targetgrouping)) {
            $data->targetgrouping = $this->get_mappingid('grouping', $data->targetgrouping);
        }

        // Insert the groupselect record.
        $newitemid = $DB->insert_record('groupselect', $data);

        $this->set_mapping('groupselect', $oldid, $newitemid, true);

        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    protected function process_groupselect_groups_teachers($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->instance_id = $this->get_new_parentid('groupselect');

        $data->teacherid = $this->get_mappingid('user', $data->teacherid);
        $data->groupid = $this->get_mappingid('group', $data->groupid);

        // Insert the groupselect record.
        if ($data->groupid && $data->teacherid) {
            $newitemid = $DB->insert_record('groupselect_groups_teachers', $data);
            $this->set_mapping('groupselect_groups_teacher', $oldid, $newitemid, true);
        }
    }

    protected function process_groupselect_passwords($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->instance_id = $this->get_new_parentid('groupselect');

        $data->groupid = $this->get_mappingid('group', $data->groupid);

        // Insert the groupselect record.
        if ($data->groupid) {
            $newitemid = $DB->insert_record('groupselect_passwords', $data);
            $this->set_mapping('groupselect_password', $oldid, $newitemid, true);
        }

    }

    protected function after_execute() {
        // Add groupselect related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_groupselect', 'intro', null);
    }
}
