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
 * Steps definitions related with the group self-selection activity.
 *
 * @package    mod_groupselect
 * @category   test
 * @copyright  2022 Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.
require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Group self-selection steps definitions.
 *
 * @package mod_groupselect
 * @category test
 * @copyright  2022 Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_mod_groupselect extends behat_base {
    /**
     * Convert page names to URLs for steps like 'When I am on the "[page name]" page'.
     *
     * Recognised page names are:
     * | None so far!      |                                                              |
     *
     * @param string $page name of the page, with the component name removed e.g. 'Admin notification'.
     * @return moodle_url the corresponding URL.
     * @throws Exception with a meaningful error message if the specified page cannot be found.
     */
    protected function resolve_page_url(string $page): moodle_url {
        switch (strtolower($page)) {
            default:
                throw new Exception('Unrecognised quiz page type "' . $page . '."');
        }
    }

    /**
     * Convert page names to URLs for steps like 'When I am on the "[identifier]" "[page type]" page'.
     *
     * Recognised page names are:
     * | pagetype          | name meaning                              | description                                   |
     * | view              | Groupselect name                          | The groupselect info page (view.php)          |
     * | report            | Groupselect name                          | The groupselect report page (report.php)      |
     *
     * @param string $type identifies which type of page this is, e.g. 'report'.
     * @param string $identifier identifies the particular page, e.g. 'Test groupselect > report > Attempt 1'.
     * @return moodle_url the corresponding URL.
     * @throws Exception with a meaningful error message if the specified page cannot be found.
     */
    protected function resolve_page_instance_url(string $type, string $identifier): moodle_url {
        switch (strtolower($type)) {
            case 'view':
                return new moodle_url(
                    '/mod/groupselect/view.php',
                    ['id' => $this->get_cm_by_groupselect_name($identifier)->id]
                );

            case 'report':
                return new moodle_url(
                    '/mod/groupselect/report.php',
                    ['id' => $this->get_cm_by_groupselect_name($identifier)->id]
                );

            default:
                throw new Exception('Unrecognised groupselect page type "' . $type . '."');
        }
    }

    /**
     * Get a groupselect by name.
     *
     * @param string $name groupselect name.
     * @return stdClass the corresponding DB row.
     */
    protected function get_groupselect_by_name(string $name): stdClass {
        global $DB;
        return $DB->get_record('groupselect', ['name' => $name], '*', MUST_EXIST);
    }

    /**
     * Get a groupselect cmid from the groupselect name.
     *
     * @param string $name groupselect name.
     * @return stdClass cm from get_coursemodule_from_instance.
     */
    protected function get_cm_by_groupselect_name(string $name): stdClass {
        $groupselect = $this->get_groupselect_by_name($name);
        return get_coursemodule_from_instance('groupselect', $groupselect->id, $groupselect->course);
    }
}
