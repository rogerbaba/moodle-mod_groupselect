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
 * Group self selection interface
 *
 * @package   mod_groupselect
 * @copyright 2018 HTW Chur Roger Barras
 * @copyright 2008-2011 Petr Skoda (http://skodak.org)
 * @copyright 2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * Groupselect select form.
 *
 * @copyright  2018 HTW Chur Roger Barras
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class select_form extends moodleform {
    /**
     * @var $groupselect The group select instance.
     */
    private $groupselect;

    /**
     * @var bool $haspassword Whether the selected group is password protected.
     */
    private $haspassword;

    /**
     * Definition of the form
     */
    public function definition() {
        $mform = $this->_form;

        [$data, $this->groupselect, $grpname] = $this->_customdata;
        $this->haspassword = !empty($data['group_password']);

        if ($this->haspassword) {
            $mform->addElement(
                'passwordunmask',
                'password',
                get_string('password', 'mod_groupselect'),
                'maxlength="254" size="24"'
            );
            $mform->setType('password', PARAM_RAW);
        }

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'select');
        $mform->setType('select', PARAM_INT);

        $this->add_action_buttons(true, get_string('select', 'mod_groupselect', $grpname));

        $this->set_data($data);
    }

    /**
     * Validation of the form
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {

        $errors = parent::validation($data, $files);

        // Use the server-side flag, not the submitted data.
        if ($this->haspassword) {
            global $DB;
            $password = $DB->get_field('groupselect_passwords', 'password', [
                    'groupid' => $data['select'],
                    'instance_id' => $this->groupselect->id,
            ]);

            if ($password === false || !password_verify($data['password'] ?? '', $password)) {
                $errors['password'] = get_string('incorrectpassword', 'mod_groupselect');
            }
        }
        return $errors;
    }
}
