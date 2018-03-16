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
 * The group_teacher_added event.
 *
 * @package    mod_groupselect
 * @copyright  2016 HTW Chur Roger Barras
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_groupselect\event;
defined('MOODLE_INTERNAL') || die();
/**
 * The group_teacher_added event class.
 *
 * @property-read array $other {
 *      Extra information about event.
 *
 *      - int groupid: id of the group
 * }
 *
 * @since     Moodle 2.9
 * @copyright 2016 HTW Chur Roger Barras
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
class group_teacher_added extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'c'; // c(reate), r(ead), u(pdate), d(elete)
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = 'groupselect_groups_teachers';
    }

    public static function get_name() {
        return get_string('eventgroupteacheradded', 'mod_groupselect');
    }

    public function get_description() {
        return "The user with id '$this->userid' added the non editing teacher user with id '$this->relateduserid' " .
                "to the groupselect with the course module id '$this->contextinstanceid' and group id '{$this->objectid}'";
    }

    public function get_url() {
        return new \moodle_url('/mod/groupselect/view.php', array('id' => $this->contextinstanceid));
    }


    public static function get_objectid_mapping() {
        return array('db' => 'groupselect_groups_teachers', 'restore' => 'groupselect_groups_teacher');
    }

    public static function get_other_mapping() {
        $othermapped = array();
        $othermapped['groupid'] = array('db' => 'group', 'restore' => 'group');

        return $othermapped;
    }
}
