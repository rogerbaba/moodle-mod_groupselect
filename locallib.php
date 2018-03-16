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
 * @package   mod_groupselect
 * @copyright 2018 HTW Chur Roger Barras
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Event types.
define('GROUPSELECT_EVENT_TYPE_DUE', 'due');

require_once("$CFG->dirroot/group/lib.php");
require_once("$CFG->dirroot/mod/groupselect/lib.php");

/**
 * Get the group description as text
 *
 * @param object $group groups record
 * @return string group description as plain text
 */
function groupselect_get_group_info($group) {
    $group = clone($group);
    $context = context_course::instance($group->courseid);

    $group->description = file_rewrite_pluginfile_urls($group->description, 'pluginfile.php', $context->id, 'group', 'description', $group->id);
    if (!isset($group->descriptionformat)) {
        $group->descriptionformat = FORMAT_MOODLE;
    }
    $options = new stdClass;
    $options->overflowdiv = true;
    return format_text($group->description, $group->descriptionformat, array('filter' => false, 'overflowdiv' => true, 'context' => $context));
}

/**
 * Is the given group selection open for students to select their group at the moment?
 *
 * @param object $groupselect groupselect record
 * @return bool True if the group selection is open right now, false otherwise
 */
function groupselect_is_open($groupselect) {
    $now = time();
    return ($groupselect->timeavailable < $now AND ($groupselect->timedue == 0 or $groupselect->timedue > $now));
}


/**
 * Get the number of members in all groups the user can select from in this activity
 *
 * @param $cm Course module slot of the groupselect instance
 * @param $targetgrouping The id of grouping the user can select a group from
 * @return array of objects: [id] => object(->usercount ->id) where id is group id
 */
function groupselect_group_member_counts($cm, $targetgrouping=0) {
    global $DB;

    // TODO: join into enrolment table

    if (empty($targetgrouping)) {
        // all groups
        $sql = "SELECT g.id, COUNT(gm.userid) AS usercount
                  FROM {groups_members} gm
                       JOIN {groups} g ON g.id = gm.groupid
                 WHERE g.courseid = :course
              GROUP BY g.id";
        $params = array('course' => $cm->course);

    } else {
        $sql = "SELECT g.id, COUNT(gm.userid) AS usercount
                  FROM {groups_members} gm
                       JOIN {groups} g            ON g.id = gm.groupid
                       JOIN {groupings_groups} gg ON gg.groupid = g.id
                 WHERE g.courseid = :course
                       AND gg.groupingid = :grouping
              GROUP BY g.id";
        $params = array('course' => $cm->course, 'grouping' => $targetgrouping);
    }

    return $DB->get_records_sql($sql, $params);
}

/**
 * Get password protected groups
 *
 * @return array of group ids
 */
function groupselect_get_password_protected_groups($groupselect) {
    global $DB;
    $sql = "SELECT  groupid
            FROM    {groupselect_passwords} gp
            WHERE   gp.instance_id = ?";

    $result = $DB->get_records_sql($sql, array($groupselect->id));
    $ids = array();
    foreach ($result as $r) {
        array_push($ids, $r->groupid);
    }
    return $ids;
}

/**
 * Get users with given role in given context
 *
 * @return array of user ids
 */
function groupselect_get_context_members_by_role($context, $roleid) {
    global $DB;
    $sql = "SELECT r.userid
                  FROM   {role_assignments} r
                 WHERE  r.contextid = ?
                   AND    r.roleid = ?";

    return $DB->get_records_sql($sql, array($context, $roleid));
}
