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
 * Prints navigation tabs
 *
 * @package    mod_groupselect
 * @author     Sagar Ghimire <sagarghimire@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
    defined('MOODLE_INTERNAL') || die;

    $row = [];
    $row[] = new tabobject(
        'view',
        new moodle_url('/mod/groupselect/view.php', ['id' => $id]),
        get_string('view', 'mod_groupselect')
    );
    $row[] = new tabobject(
        'limits',
        new moodle_url('/mod/groupselect/limits.php', ['id' => $id]),
        get_string('limits', 'mod_groupselect')
    );
    echo '<div class="groupdisplay">';
    echo $OUTPUT->tabtree($row, $currenttab);
    echo '</div>';
