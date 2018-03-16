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
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['assignedteacher'] = 'Zugeordnete(r) Betreuer/in';
$string['assigngroup'] = 'Betreuer/innen zu Gruppen zuordnen';
$string['assigngroup_help'] = 'Wenn gesetzt, erscheint ein Button um Betreuer/innen, sofern in diesem Kurs welche existieren, Gruppen zuzuordnen. Zugeordnete Betreuer/innen sind keine Gruppenmitglieder, erscheinen aber in exportierten Dateien und in der Hauptansicht (sofern ausgewählt). Nützlich, wenn der Kurs Assistenten benutzt, um Gruppen zu betreuen. Diese Berechtigung kann in den Rollenberechtigungen pro Rolle definiert werden.';
$string['deleteallgrouppasswords'] = 'Alle Gruppenpasswörter löschen';
$string['duedate'] = 'Fälligkeitsdatum';
$string['enablepermissions'] = 'Allgemeine Berechtigungen';
$string['eventgroupteacheradded'] = 'Betreuer/innen zugeordnet';
$string['eventexportlinkcreated'] = 'Exportlink erstellt';
$string['groupselect:assign'] = 'Das Hinzufügen eingeschriebener Betreuer/innen zu Gruppen erlauben';
$string['maxgroupmembership'] = 'Maximale Anzahl der Gruppenteilnahme pro Teilnehmenden';
$string['maxgroupmembership_error_low'] = 'Negative Anzahl an Mitgliedschaften ist nicht erlaubt!';
$string['maxgroupmembership_help'] = 'Legt die maximale Anzahl an Gruppenteilnahmen fest. Eine 0 bedeutet, dass keine Teilnahme möglich ist.';
$string['maxmembers_error_low'] = 'Negative Anzahl an Gruppenmitgliedern ist nicht erlaubt! Benutze eine 0 um eine unlimitierte Gruppengrösse zu definieren!';
$string['maxmembers_error_smaller_minmembers'] = 'Die Maximalanzahl der Teilnehmer muss grösser sein als die Mindestanzahl!';
$string['maxmembers_icon'] = 'Die Gruppe hat zu viele Mitglieder';
$string['maxmembers_notification'] = 'Ihre Gruppe hat zu viele Mitglieder! Das Maximum ist {$a}.';
$string['minmembers_error_low'] = 'Negative Anzahl an Gruppenmitgliedern ist nicht erlaubt!';
$string['minmembers_error_bigger_maxmembers'] = 'Die Mindestgruppengrösse muss kleiner sein als die maximale Anzahl an Teilnehmern!';
$string['miscellaneoussettings'] = 'Verschiedene Einstellungen';
$string['modulename_help'] = '<p>Teilnehmer/innen können Gruppen erstellen und wählen: </p><ul><li>Teilnehmer/innen können Gruppen erstellen, diesen eine Beschreibung geben und, falls gewünscht, mit einem Passwort schützen</li><li>Teilnehmer/innen können Gruppen auswählen und betreten</li><li>Betreuer/innen können Gruppen hinzugefügt werden</li><li>Trainer/in kann die Gruppenliste des Kurses als CSV-Datei exportieren</li><li>Volle Kompatibilität zu Basis Moodle-Gruppen: Gruppen können auch durch andere Plugins erzeugt werden.</li></ul>';
$string['notifyexpiredselection'] = 'Zeige Meldung, wenn das Einschreibeende vorüber ist';
$string['notifyexpiredselection_help'] = 'Wenn gesetzt, wird eine Meldung angezeigt, falls das Einschreibeende vorüber ist';
$string['removeallsupervisors'] = 'Alle Betreuerzuweisungen enfernen';
$string['selectgroupaction'] = 'Gruppe wählen';
$string['showassignedteacher'] = 'Zugeteilte Betreuer/innen anzeigen';
$string['showassignedteacher_help'] = 'Wenn gesetzt, werden zugeteilte Betreuer/innen in Gruppenmitglieder angezeigt. Dies könnte nützlich sein, wenn Teilnehmer/innen ihre zugeteilten Betreuer/innen wissen müssen.';
$string['studentcancreate'] = 'Teilnehmende können Gruppen anlegen';
$string['studentcancreate_help'] = 'Wenn gesetzt, können Teilnehmende ohne Gruppenzugehörigkeit (in der ausgewählten Gruppierung) Gruppen erstellen. Diese Berechtigung kann in den Rollenberechtigungen pro Rolle definiert werden.';
$string['studentcansetdesc'] = 'Teilnehmende können Gruppenbeschreibung erstellen und ändern';
$string['studentcansetdesc_help'] = 'Wenn die Option gesetzt ist und eine Gruppe erstellt wurde, können Gruppenmitglieder eine Beschreibung für ihre Gruppe anlegen und bearbeiten.';
$string['studentcanleave'] = 'Teilnehmende können Gruppen verlassen';
$string['studentcanleave_help'] = 'Wenn gesetzt, können Teilnehmende Gruppen verlassen. Diese Berechtigung kann in den Rollenberechtigungen pro Rolle definiert werden.';
$string['studentcanjoin'] = 'Teilnehmende können Gruppen beitreten';
$string['studentcanjoin_help'] = 'Wenn gesetzt, können Teilnehmende Gruppen beitreten. Diese Berechtigung kann in den Rollenberechtigungen pro Rolle definiert werden.';
$string['studentcansetenrolmentkey'] = 'Teilnehmende können Passwörter setzen, um Gruppen beizutreten';
$string['studentcansetenrolmentkey_help'] = 'Wenn gesetzt, kann ein/e Teilnehmer/in ein Gruppenpasswort setzen';
$string['studentcansetgroupname'] = 'Teilnehmende dürfen Gruppennamen selbst bestimmen.';
$string['studentcansetgroupname_help'] = 'Wenn gesetzt, können Teilnehmende die Gruppennamen für neue Gruppen setzen.';
$string['supervisionrole'] = 'Rolle für die Betreuung';
$string['supervisionrole_help'] = 'Supervisorenrolle festlegen für die Betreuung der Gruppen festlegen (Standard: Lehrer ohne Bearbeitungsrecht)';
$string['targetgrouping'] = 'Gruppen aus einer Gruppierung auswählen';
$string['timeavailable_error_past_timedue'] = 'Aktivität kann nicht nach dem Ende anfangen!';
$string['timedue_error_pre_timeavailable'] = 'Aktivität kann nicht vor dem Start enden!';
$string['unassigngroup'] = 'Betreuer/innen von Gruppen entfernen';
$string['unassigngroup_confirm'] = 'Diese Aktion entfernt Betreuer/innen von Gruppen. Sind Sie sicher?';
