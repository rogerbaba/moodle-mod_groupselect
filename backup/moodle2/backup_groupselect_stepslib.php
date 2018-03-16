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
 * Define all the backup steps that will be used by the backup_groupselect_activity_task
 *
 * @package    mod_groupselect
 * @copyright  2018 HTW Chur Roger Barras
 * @copyright  2011 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete groupselect structure for backup, with file and id annotations.
 *
 * @copyright  2018 HTW Chur Roger Barras
 * @copyright  2011 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_groupselect_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $groupselect = new backup_nested_element('groupselect', array('id'), array(
            'name', 'intro', 'introformat', 'targetgrouping', 'maxmembers', 'timeavailable', 'timedue',
            'timecreated', 'timemodified', 'hidefullgroups', 'deleteemptygroups',
            'studentcancreate', 'minmembers', 'assignteachers', 'studentcansetdesc',
            'showassignedteacher', 'studentcansetenrolmentkey', 'studentcansetgroupname',
            'notifyexpiredselection', 'supervisionrole', 'maxgroupmembership', 'studentcanjoin', 'studentcanleave'
        ));

        $passwords = new backup_nested_element('passwords');

        $password = new backup_nested_element('password', array('id'), array(
                'groupid', 'password'));

        $groupteachers = new backup_nested_element('groupteachers');

        $groupteacher = new backup_nested_element('groupteacher', array('id'), array(
                'groupid', 'teacherid'));

        // Build the tree.
        $groupselect->add_child($passwords);
        $passwords->add_child($password);
        $groupselect->add_child($groupteachers);
        $groupteachers->add_child($groupteacher);

        // Define sources.
        $groupselect->set_source_table('groupselect', array('id' => backup::VAR_ACTIVITYID));
        $password->set_source_table('groupselect_passwords', array('instance_id' => backup::VAR_ACTIVITYID));
        if ($userinfo) {
            $groupteacher->set_source_table('groupselect_groups_teachers', array('instance_id' => backup::VAR_ACTIVITYID));
        }

        // Define id annotations.
        $groupselect->annotate_ids('grouping', 'targetgrouping');
        $password->annotate_ids('group', 'groupid');
        $groupteacher->annotate_ids('group', 'groupid');
        $groupteacher->annotate_ids('user', 'teacherid');

        // Define file annotations.
        $groupselect->annotate_files('mod_groupselect', 'intro', null); // This file areas haven't itemid.

        // Return the root element (groupselect), wrapped into standard activity structure.
        return $this->prepare_activity_structure($groupselect);
    }
}
