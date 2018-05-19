# Changes for mod_groupselect

## Version 3.15 (2018051900)
*   Implemented interface of the privacy api for GDPR (moodle 3.5)

## Version 3.14 (2018031606)
*   New settings to allow participants to join or leave groups
*   Added navigation to manage groups for teachers
*   Supervisors could now be unassigned
*   Supporting course reset
*   Solved some issues for using groupselect in moodle 3.4
*   Placeholder "Click to edit" in group description now translatable
*   Fixed some translation strings
*   Removed the due validation, if it is not enabled
*   Supporting now the timeline in the course overview
*   Fixed issue, that the completion event was not visible in the calendar (since moodle 3.3)
*   Fixed buf of missing fields in backup: 'supervisionrole', 'maxgroupmembership'
*   Allow teachers to edit all group descriptions, if they have got the capability
*   Allow participants to create multiple groups
*   Fixed issue that the "teacher" role was nessesary in moodle
*   Removing now supervisors, if they got unenrolled from the course
*   Rearrange settings
*   update default capabilities for teachers, editingteachers and managers
    enable select, unselect group for teacher, editingteachers and managers
    enable create groups for editingteachers and managers

## Version 3.13 (2017061302)
*   Better error messages for the module administration (via @phish108)
*   Allow to configure participation in multiple groups (fixes issue #8, via @phish108)
*   Allow administrators and teachers to set supervision roles (fixes bug #14, via @phish108)
*   Omit including unnecessary libraries (fixes bug #10, via @lucaboesch)
*   Better conformity with Moodle Coding and Packaging Standards

## Older Versions
* 2017.02.01: Default values for activity settings available
* 2016.09.07: Export only current grouping if specified
* 2016.08.26: Enabled show description feature and tableview improvements for longer groupnames
* 2016.06.11: New option to disable notifications if the open until date is reached
* 2016.06.06: New option for students to define the group name; New option to avoid that students can define passwords;
Fixed validation messages for creating groups, fixed install.xml
* 2016.06.02: Fixed wrong instance_id in table groupselect_groups_teachers; Backup and restore works now;
Added new logging events for adding non-editign teachers and creating a downloadlink; Some small fixes.
* 2016.05.09: Removed general setting 'requiremodintro', not supported anymore
* 2015.03.25: Fixed: password was asked when joining group without
password (if upgraded from older versions), sql queries should now work
with oracle
* 2014.12.17: Migrated to new logging system
* 2014.12.15: Small fixes
* 2014.12.01: Fixed upgrade.php, project renamed as groupselect
* 2014.11.07: Non-editing teacher assignment, group description editing, improved csv-export, small optional features added
* 2014.09.11: Fixed mysql insertion related bug, added some notifications and small fixes
