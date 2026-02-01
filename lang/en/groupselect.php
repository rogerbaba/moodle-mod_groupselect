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
 * Group self selection
 *
 * @package   mod_groupselect
 * @copyright 2018 HTW Chur Roger Barras
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['action'] = 'Action';
$string['assignedteacher'] = 'Supervisor';
$string['assigngroup'] = 'Assign supervisors to groups';
$string['assigngroup_help'] = 'If set, enables a button which assigns supervisors to groups (if course has supervisors). Assigned supervisors are not group members, but they show up in export file and in the main view (if set). Useful if course uses assistants to handle groups. This permission can be set further in the role capabilities.';
$string['cannotselectclosed'] = 'You can not become group member any more.';
$string['cannotselectmaxed'] = 'You can not join group {$a} - maximum number of members reached.';
$string['cannotselectnocap'] = 'You are not allowed to select group.';
$string['cannotselectnoenrol'] = 'You need to be enrolled into course in order to become a group member.';
$string['cannotunselectclosed'] = 'You can not leave group any more';
$string['completiondetail:submit'] = 'Choose a group';
$string['completionsubmit'] = 'Show as complete when user makes a choice';
$string['creategroup'] = 'Create a new group';
$string['deleteallgrouppasswords'] = 'Delete all group passwords';
$string['deleteemptygroups'] = 'Delete group when last participant leaves';
$string['deleteemptygroups_help'] = 'If set, automatically deletes group when last participant leaves from it';
$string['description'] = 'Group description';
$string['duedate'] = 'Due date';
$string['edittooltip'] = 'Click to edit';
$string['enablepermissions'] = 'General permissions';
$string['event:answered_desc'] = 'The user with id \'{$a->userid}\' joined a group in the group self-selection activity with the course module id \'{$a->contextinstanceid}\'.';
$string['event:answered'] = 'Group joined';
$string['eventexportlinkcreated'] = 'Export link created';
$string['eventgroupteacheradded'] = 'Supervisor teacher added';
$string['export'] = 'Create a download link for group data file (CSV)';
$string['export_download'] = 'Download CSV-file';
$string['fromallgroups'] = 'All groups';
$string['globalpassword_help'] = 'Set a global password for group joining. Overrides participant set passwords.';
$string['groupid'] = 'Group ID';
$string['groupselect:addinstance'] = 'Add a new group self-selection';
$string['groupselect:assign'] = 'Allow to assign supervisors to groups';
$string['groupselect:create'] = 'Allow creating of group';
$string['groupselect:export'] = 'Allow export of group members';
$string['groupselect:overridegrouplimit'] = 'Allow to assign permission to set limit per group';
$string['groupselect:select'] = 'Allow becoming of group member';
$string['groupselect:unselect'] = 'Allow leaving of group';
$string['hidefullgroups'] = 'Hide full groups from the main view';
$string['hidefullgroups_help'] = 'If set, hides all groups, which have reached max member count, from the main list view (excluding user\'s own group). May be useful if the activity has lots of groups.';
$string['hidegroupmembers'] = 'Hide group members for students';
$string['hidegroupmembers_help'] = 'If set, all group members will be hidden for students. If the students have the capabilities to manage groups (moodle/course:managegroups) or if they can access all groups (moodle/site:accessallgroups), the members will be always shown.';
$string['hidesuspendedstudents'] = 'Hide suspended students';
$string['hidesuspendedstudents_help'] = 'If checked, suspended students will be removed from user count and group lists.';
$string['incorrectpassword'] = 'Incorrect password';
$string['limits'] = 'Limits';
$string['limits_help'] = 'Maximum number of members for this group. Use 0 for unlimited and leave blank for default';
$string['managegroups'] = 'Manage groups';
$string['maxcharlenreached'] = 'Maximum character number reached';
$string['maxgroupmembership'] = 'Maximum number of groups to participate in';
$string['maxgroupmembership_error_low'] = 'Negative numbers are not allowed!';
$string['maxgroupmembership_help'] = 'Maximum number of groups to participate in. A 0 means that no participation is possible.';
$string['maxlimitreached'] = 'Maximum number reached';
$string['maxmembers'] = 'Max members per group';
$string['maxmembers_error_low'] = "Negative numbers are not allowed for! Use 0 for unlimited!";
$string['maxmembers_error_smaller_minmembers'] = "Needs to be greater than the minimum participants per group!";
$string['maxmembers_help'] = 'Maximum number of members per group. Use 0 for unlimited.';
$string['maxmembers_icon'] = 'Group has too many members';
$string['maxmembers_notification'] = 'Your group has too many members! Maximum is {$a}.';
$string['member'] = 'Member';
$string['membercount'] = 'Count';
$string['membershidden'] = 'Member list not available';
$string['memberslist'] = 'Members';
$string['minmembers'] = 'Min members per group';
$string['minmembers_error_bigger_maxmembers'] = 'The minimum group size needs to be smaller than the maximum participants per group!';
$string['minmembers_error_low'] = 'Negative numbers are not allowed for the minimum group size! Use 0 for disabling!';
$string['minmembers_help'] = 'Minimum number of members per group. Adds notifications for members of groups which are under this limit. Default is 0 (disabled).';
$string['minmembers_icon'] = 'Group has less members than required';
$string['minmembers_notification'] = 'Your group has less members than required! Minimum is {$a}.';
$string['miscellaneoussettings'] = 'Miscellaneous settings';
$string['modulename'] = 'Group self-selection';
$string['modulename_help'] = '###### Key features
- Participants can create groups with descriptions and optional password protection
- Students can select and join existing groups
- Supervisors can be assigned to groups for guidance
- Teachers can export group lists as CSV files
- Fully compatible with Moodle’s native group features and group-based activities

###### Ways to use it
- Allow students to form their own project or study groups
- Enable flexible group creation for collaborative assignments
- Assign supervisors to specific groups for mentoring
- Export group data for administrative or reporting purposes
- Combine with Moodle group activities for seamless integration';
$string['modulename_link'] = 'mod/groupselect/view';
$string['modulename_summary'] = 'Lets participants create and join groups themselves, with options for descriptions, passwords, supervisor assignment, and full Moodle group compatibility.';
$string['modulenameplural'] = 'Group self-selections';
$string['nogroups'] = 'No groups available to select from, sorry.';
$string['notavailableanymore'] = 'Group selection is not available anymore, sorry (since {$a}).';
$string['notavailableyet'] = 'Group selection will be available on {$a}.';
$string['notifyexpiredselection'] = 'Show message, if the open until date is reached';
$string['notifyexpiredselection_help'] = 'If set, a message will appear if the open until date is reached';
$string['ok'] = 'OK';
$string['password'] = 'Requires password';
$string['pluginadministration'] = 'Module administration';
$string['pluginname'] = 'Group self-selection';
$string['privacy:metadata'] = 'The plugin Group self-selection does not store any personal data.';
$string['removeallsupervisors'] = 'Remove group supervisors';
$string['saving'] = 'Saving...';
$string['select'] = 'Become member of {$a}';
$string['selectconfirm'] = 'Do you really want to become member of the group <em>{$a}</em>?';
$string['selectgroupaction'] = 'Select group';
$string['setlimits'] = 'Set limits';
$string['showassignedteacher'] = 'Show assigned supervisors';
$string['showassignedteacher_help'] = 'If set, assigned supervisors will show up in group members. Useful if participants need to know their assigned teacher';
$string['studentcancreate'] = 'Participants can create groups';
$string['studentcancreate_help'] = 'If set, participants without group (in selected grouping) can create groups. This permission can be set further in the role capabilities.';
$string['studentcanjoin'] = 'Participants can join groups';
$string['studentcanjoin_help'] = 'If set, participants can join groups. This permission can be set further in the role capabilities.';
$string['studentcanleave'] = 'Participants can leave groups';
$string['studentcanleave_help'] = 'If set, participants can leave groups. This permission can be set further in the role capabilities.';
$string['studentcansetdesc'] = 'Participants can set and edit group description';
$string['studentcansetdesc_help'] = 'If set, participants can set a a group description when creating one and group members can edit it.';
$string['studentcansetenrolmentkey'] = 'Participants can set passwords for joining groups';
$string['studentcansetenrolmentkey_help'] = 'If set, participants can set an enrolment key for joining groups.';
$string['studentcansetgroupname'] = 'Participants can set the name of new groups';
$string['studentcansetgroupname_help'] = 'If set, participants can set the group name for new groups.';
$string['supervisionrole'] = 'Supervisor role';
$string['supervisionrole_help'] = 'Define the role for supervisors (formally non-editing teachers).';
$string['targetgrouping'] = 'Select groups from grouping';
$string['timeavailable'] = 'Open from';
$string['timeavailable_error_past_timedue'] = 'Cannot start after due date!';
$string['timedue'] = 'Open until';
$string['timedue_error_pre_timeavailable'] = 'Cannot end before start date!';
$string['unassigngroup'] = 'Unassign supervisors from groups';
$string['unassigngroup_confirm'] = 'This will unassign supervisors from groups. Are you sure?';
$string['unselect'] = 'Leave group {$a}';
$string['unselectconfirm'] = 'Do you really want to leave the group <em>{$a}</em>?';
$string['validmax'] = 'Please enter a value greater than the current default minimum (\'{$a}\')';
$string['validnum'] = 'Please enter valid number';
$string['view'] = 'View';
