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
 * Page module capability definition
 *
 * @package   mod_groupselect
 * @copyright 2018 HTW Chur Roger Barras
 * @copyright 2011 Petr Skoda (http://skodak.org)
 * @copyright 2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$capabilities = [
        'mod/groupselect:addinstance' => [
                'riskbitmask' => RISK_XSS,
                'captype' => 'write',
                'contextlevel' => CONTEXT_COURSE,
                'archetypes' => [
                        'editingteacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                ],
                'clonepermissionsfrom' => 'moodle/course:manageactivities',
        ],

        'mod/groupselect:overridegrouplimit' => [
                'riskbitmask' => RISK_XSS,
                'captype' => 'write',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'editingteacher' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                ],
                'clonepermissionsfrom' => 'moodle/course:manageactivities',
        ],

        'mod/groupselect:create' => [
                'captype' => 'write',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'student' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                ],
        ],

        'mod/groupselect:select' => [
                'captype' => 'write',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'student' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                ],
        ],

        'mod/groupselect:unselect' => [
                'captype' => 'write',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'student' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                ],
        ],

        'mod/groupselect:export' => [
                'riskbitmask' => RISK_PERSONAL,
                    'captype' => 'read',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'manager' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                ],
        ],
        'mod/groupselect:assign' => [
                'captype' => 'write',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => [
                        'manager' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                ],
        ],
];
