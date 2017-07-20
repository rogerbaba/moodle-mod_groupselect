<?php

namespace mod_groupselect;

defined('MOODLE_INTERNAL') || die();

class actions {

    private $activity;
    private $error;
    private $context;
    private $isTeacher;
    private $assignRole;

    public function __construct($activty, $context) {
        $this->context = $context;
        $this->activity = $activity;

        $this->find_supervision_role();
    }

    protected function find_supervision_role() {
        global $DB;

        // fallback variant 1 with hard coded role short name
        $teacherRole = $DB->get_record( 'role', array (
            'shortname' => "teacher"
        ), '*', MUST_EXIST );

        $this->assignRole = $teacherRole->id; // Assign non-editing teachers.

        // variant 2 for system wide supervision roles
        $gs_config = get_config("groupselect");
        if (property_exists($gs_config, "supervisionrole") && $gs_config->supervisionrole > 0) {
            $this->assignRole = $gs_config->supervisionrole;
        }

        // variant 3 for activity specific supervision roles.
        if (property_exists($this->activity, "supervisionrole") && $this->activity->supervisionrole > 0) {
            $this->assignRole = $this->activity->supervisionrole;
        }
    }

    public function create_group($group, $data) {
        $message = "";
        return $message;
    }

    public function edit_group($group, $data) {
        $message = "";
        return $message;
    }

    public function join_group($group) {
        $message = "";
        return $message;
    }

    public function leave_group($group) {
        $message = "";
        return $message;
    }

    public function assign_teachers() {

    }

    public function get_group_list() {
        $list = [];
        return $list;
    }
}
