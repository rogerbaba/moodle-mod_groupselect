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
 * Event observers supported by this module
 *
 * @package    mod_groupselect
 * @copyright  2018 HTW Chur Roger Barras
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_groupselect;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupselect/locallib.php');

/**
 * Group observers class.
 *
 * @copyright 2018 HTW Chur Roger Barras
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class groupselect_observer {

    /**
     * A user has been unenrolled.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public static function user_unenrolled($event) {
        global $DB;

        // NOTE: this has to be as fast as possible.
        // Get user enrolment info from event.
        $cp = (object)$event->other['userenrolment'];
        if ($cp->lastenrol) {
            if (!$groupselections = $DB->get_records('groupselect', array('course' => $cp->courseid), '', 'id')) {
                return;
            }
            list($groupselect, $params) = $DB->get_in_or_equal(array_keys($groupselections), SQL_PARAMS_NAMED);
            $params['userid'] = $cp->userid;

            $DB->delete_records_select('groupselect_groups_teachers', 'teacherid = :userid AND instance_id '.$groupselect, $params);
        }
    }

    /**
     * A group has been deleted.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public static function group_deleted($event) {
        global $DB;

        // NOTE: this has to be as fast as possible.
        $groupid = $event->objectid;
        if (isset($groupid)) {
            $params['groupid'] = $groupid;
            $DB->delete_records_select('groupselect_groups_teachers', 'groupid = :groupid', $params);
            $DB->delete_records_select('groupselect_passwords', 'groupid = :groupid', $params);
        }
    }
}