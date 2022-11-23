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
 * Version information
 *
 * @package    mod_groupselect
 * @copyright  2022 Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace mod_groupselect;

use core\activity_dates;

/**
 * Class for fetching the important dates in mod_groupselect for a given module instance and a user.
 *
 * @copyright 2021 Shamim Rezaie <shamim@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class dates extends activity_dates {

    /**
     * Returns a list of important dates in mod_groupselect
     * (code copied from /mod/assign/classes/dates.php)
     *
     * @return array
     */
    protected function get_dates(): array {

        list($course, $module) = get_course_and_cm_from_cmid($this->cm->id);

        $groupselect = groupselect_get_groupselect($module->instance);

        $timeopen = $groupselect->timeavailable ?? null;
        $timeclose = $groupselect->timedue ?? null;

        $now = time();
        $dates = [];

        if ($timeopen) {
            $openlabelid = $timeopen > $now ? 'activitydate:opens' : 'activitydate:opened';
            $date = [
                    'label'     => get_string($openlabelid, 'course'),
                    'timestamp' => (int)$timeopen,
            ];
            $dates[] = $date;
        }

        if ($timeclose) {
            $closelabelid = $timeclose > $now ? 'activitydate:closes' : 'activitydate:closed';
            $date = [
                    'label'     => get_string($closelabelid, 'course'),
                    'timestamp' => (int)$timeclose,
            ];
            $dates[] = $date;
        }

        return $dates;
    }
}
