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
 * A form for setting group limits
 *
 * @package   mod_groupselect
 * @author    Sagar Ghimire <sagarghimire@catalyst-au.net>
 * @copyright Catalyst iT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * GroupSelect Limit Form Class
 *
 * @package   mod_groupselect
 * @author    Sagar Ghimire <sagarghimire@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class limit_form extends moodleform {
    /**
     * Definition of the form
     */
    public function definition() {
        global $COURSE;

        $rows = groups_get_all_groups($COURSE->id, 0, $this->_customdata['targetgrouping']);
        $keys = array_keys($rows);
        $mform =& $this->_form;
        $count = count($rows);

        for ($i = 0; $i < $count; $i++) {
            $limitnum = 'limit_' . $rows[$keys[$i]]->id;

            $mform->addElement('text', $limitnum, $rows[$keys[$i]]->name, 'maxlength="50" size="5"');
            $mform->addHelpButton($limitnum, 'limits', 'mod_groupselect');
            $mform->setType($limitnum, PARAM_TEXT);
            $mform->addRule($limitnum, get_string('validnum', 'mod_groupselect'), 'numeric', '');
        }

        // Hidden Params.
        $mform->addElement('hidden', 'instanceid');
        $mform->setType('instanceid', PARAM_INT);
        $mform->setDefault('instanceid', $this->_customdata['instanceid']);

        $mform->addElement('hidden', 'num');
        $mform->setDefault('num', $count);
        $mform->setType('num', PARAM_INT);

        // Buttons.
        $this->add_action_buttons();
    }

    /**
     * Form validation
     *
     * @param array $data
     * @param array $files
     * @return array $errors An array of validataion errors for the form.
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        $keys = array_keys($data);
        $num = $data['num'];

        $minmembers = $DB->get_field('groupselect', 'minmembers', ['id' => $data['instanceid']]);

        for ($i = 0; $i < $num; $i++) {
            if ($data[$keys[$i]] < $minmembers && $data[$keys[$i]] != 0) {
                $errors[$keys[$i]] = get_string('validmax', 'mod_groupselect', $minmembers);
            }
        }

        return $errors;
    }
}
