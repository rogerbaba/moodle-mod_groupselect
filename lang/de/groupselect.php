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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['hidegroupmembers'] = 'Verberge Gruppenmitglieder für Teilnehmende';
$string['hidegroupmembers_help'] = 'Wenn gesetzt, werden Gruppenmitglieder für Teilnehmende verborgen. Falls Teilnehmende die Berechtigung besitzen Gruppen zu verwalten (moodle/course:managegroups) oder auf alle Gruppen zugreifen können (moodle/site:accessallgroups), werden die Gruppenmitgleider immer angezeigt.';
$string['hidesuspendedstudents'] = 'Verberge inaktive Teilnehmende';
$string['hidesuspendedstudents_help'] = 'Wenn gesetzt, werden inaktive Teilnehmende aus der Ansicht verborgen und bei der Anzahl Gruppenteilnehmer nicht berücksichtig.';
$string['privacy:metadata'] = 'Das Plugin Freie Gruppeneinteilung speichert keine persönlichen Daten.';
