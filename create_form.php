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
 * Group self selection - group creation form
 *
 * @package   mod_groupselect
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Form for creating new groups.
 *
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class create_form extends moodleform {

    /**
     * Maximum length of the group description.
     */
    const DESCRIPTION_MAXLEN = 1024;
    /**
     * Maximum lenght of a group password.
     */
    const PASSWORD_MAXLEN = 254;
    /**
     * Maximum length of a group name.
     */
    const GROUP_NAME_MAXLEN = 254;

    public function definition() {

        $mform = $this->_form;
        list($data, $this->groupselect) = $this->_customdata;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        if ($this->groupselect->studentcansetgroupname) {
            $mform->addElement('text', 'groupname', get_string('groupname', 'group'), array('size' => '100', 'maxlength' => self::GROUP_NAME_MAXLEN - 1));
        } else {
            $mform->addElement('hidden', 'groupname', '');
        }
        $mform->setType('groupname', PARAM_TEXT);

        if ($this->groupselect->studentcansetdesc) {
            $mform->addElement('textarea', 'description', get_string('description', 'mod_groupselect'),
                array('wrap' => 'virtual', 'maxlength' => self::DESCRIPTION_MAXLEN - 1, 'rows' => '3', 'cols' => '25', ''));
        } else {
            $mform->addElement('hidden', 'description', '');
        }
        $mform->setType('description', PARAM_NOTAGS);

        if ($this->groupselect->studentcansetenrolmentkey) {
            $mform->addElement('passwordunmask', 'password', get_string('password'), array('maxlength' => self::PASSWORD_MAXLEN - 1, 'size' => "24"));
        } else {
            $mform->addElement('hidden', 'password', '');
        }
        $mform->setType('password', PARAM_RAW);

        $this->add_action_buttons(true, get_string('creategroup', 'mod_groupselect'));
        $this->set_data($data);

    }

    public function validation($data, $files) {
        global $COURSE;

        $errors = parent::validation($data, $files);

        $description = $data['description'];
        if (strlen($description) > self::DESCRIPTION_MAXLEN) {
            $errors['description'] = get_string('maxcharlenreached', 'mod_groupselect');
        }
        $password = $data['password'];
        if (strlen($password) > self::PASSWORD_MAXLEN) {
            $errors['password'] = get_string('maxcharlenreached', 'mod_groupselect');
        }
        $groupname = $data['groupname'];
        if (strlen($groupname) > self::GROUP_NAME_MAXLEN) {
            $errors['groupname'] = get_string('maxcharlenreached', 'mod_groupselect');
        }
        if (groups_get_group_by_name($COURSE->id, $groupname)) {
            $errors['groupname'] = get_string('groupnameexists', 'group', $groupname);
        }
        return $errors;
    }
}
