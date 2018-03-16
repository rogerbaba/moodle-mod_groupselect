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
 * @package    mod_groupselect
 * @copyright  2008-2011 Petr Skoda (http://skodak.org)
 * @copyright  2014 Tampere University of Technology, P. Pyykkönen (pirkka.pyykkonen ÄT tut.fi)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['action'] = 'Toiminto';
$string['assigngroup'] = 'Jaa assistentit ryhmiin';
$string['assigngroup_confirm'] = 'Jakaa opettaja ilman muokkausoikeutta -roolin käyttäjät ryhmiin. Toiminto korvaa mahdollisen aikaisemman jaon peruuttamattomasti. Oletko varma?';
$string['assigngroup_help'] = 'Jos päällä, opettajalle näkyy päänäkymässä painike, joka mahdollistaa assistenttien (kurssilla olevien opettaja ilman muokkausoikeutta -roolin omaavien käyttäjien) jakamisen ryhmiin. Jaetut assistentit eivät ole ryhmien jäseniä, mutta näkyvät viedyssä csv-tiedostossa ja päänäkymässä (jos asetettu).';
$string['assignedteacher'] = 'Ryhmän assistentti';
$string['cannotselectclosed'] = 'Et voi enää liittyä ryhmään.';
$string['cannotselectmaxed'] = 'Et voi liittyä ryhmään {$a} - ryhmä on täynnä.';
$string['cannotselectnocap'] = 'Sinulla ei ole oikeutta valita ryhmää.';
$string['cannotselectnoenrol'] = 'Sinun täytyy olla kirjautuneena kurssille voidaksesi liittyä ryhmään.';
$string['creategroup'] = 'Luo uusi ryhmä';
$string['cannotunselectclosed'] = 'Et voi enää lähteä ryhmästä';
$string['deleteemptygroups'] = 'Poista ryhmä viimeisen jäsenen poistuessa';
$string['deleteemptygroups_help'] = 'Jos päällä, poistaa ryhmän automaattisesti viimeisen jäsenen poistuessa siitä';
$string['description'] = 'Ryhmän kuvaus';
$string['edittooltip'] = 'Klikkaa muokataksesi';
$string['export'] = 'Luo CSV-tiedoston latauslinkki';
$string['export_download'] = 'Lataa CSV-tiedosto';
$string['fromallgroups'] = 'Kaikki ryhmät';
$string['globalpassword_help'] = 'Aseta yleinen salasana ryhmään liittymiselle. Korvaa mahdollisen ryhmäkohtaisen salasanan.';
$string['groupid'] = 'Ryhmätunniste';
$string['groupselect:addinstance'] = 'Luo uusi ryhmävalinta';
$string['groupselect:assign'] = 'Salli assistenttien jakaminen ryhmiin';
$string['groupselect:create'] = 'Salli ryhmän luonti';
$string['groupselect:select'] = 'Salli ryhmään liittyminen';
$string['groupselect:unselect'] = 'Salli ryhmästä lähteminen';
$string['hidefullgroups'] = 'Piilota täynnä olevat ryhmät';
$string['hidefullgroups_help'] = 'Piilottaa täynnä olevat ryhmät listanäkymästä (poislukien käyttäjän oman ryhmän). Voi olla hyödyllinen, jos ryhmiä on paljon.';
$string['incorrectpassword'] = 'Virheellinen salasana';
$string['managegroups'] = 'Hallinnoi ryhmiä';
$string['maxcharlenreached'] = 'Merkkien enimmäismäärä saavutettu';
$string['maxlimitreached'] = 'Suurin sallittu määrä saavutettu';
$string['maxmembers'] = 'Ryhmän jäsenten enimmäismäärä';
$string['maxmembers_help'] = 'Ryhmän jäsenten enimmäismäärä, 0 jos rajoittamaton.';
$string['member'] = 'Jäsen';
$string['membercount'] = 'Lukumäärä';
$string['membershidden'] = 'Jäsenlista ei nähtävissä';
$string['memberslist'] = 'Jäsenet';
$string['minmembers'] = 'Ryhmän jäsenten vähimmäismäärä';
$string['minmembers_help'] = 'Ryhmän jäsenten vähimmäismäärä. Jos käytössä, näyttää vähimmäismäärän alittavien ryhmien jäsenille huomautuksen. Oletusarvo 0 (pois käytöstä)';
$string['minmembers_icon'] = 'Ryhmässä on liian vähän jäseniä';
$string['minmembers_notification'] = 'Ryhmässäsi on liian vähän jäseniä! Vähimmäismäärä on {$a}.';
$string['modulename'] = 'Ryhmänmuodostus';
$string['modulename_help'] = '<p>Antaa opiskelijoiden itse muodostaa ryhmät. Ominaisuudet:</p><ul><li>Opiskelija voi luoda ryhmän, antaa sille kuvauksen ja salasanan</li><li>Opiskelijat voivat liittyä ryhmiin</li><li>Assistenttien jakaminen ryhmiin</li><li>Opettaja voi ladata listan ryhmistä csv-tiedostona </li><li>Yhteensopiva Moodlen ryhmien kanssa: ryhmiä voi tarvittaessa luoda käyttäjähallinnan kautta, voi tehdä ryhmäpalautuksia jne.</li></ul>';
$string['modulenameplural'] = 'Ryhmänmuodostukset';
$string['nogroups'] = 'Ei luotuja ryhmiä.';
$string['notavailableanymore'] = 'Ryhmävalinta sulkeutunut {$a}.';
$string['notavailableyet'] = 'Ryhmävalinta aukeaa {$a}.';
$string['ok'] = 'OK';
$string['password'] = 'Vaatii salasanan';
$string['pluginadministration'] = 'Moduulinhallinta';
$string['pluginname'] = 'Ryhmänmuodostus';
$string['saving'] = 'Tallennetaan';
$string['selectconfirm'] = 'Haluatko todella liittyä ryhmään <em>{$a}</em>?';
$string['showassignedteacher'] = 'Näytä jaetut assistentit';
$string['showassignedteacher_help'] = 'Jos asetettu, ryhmälle jaettu assistentti näkyy päänäkymän jäsenlistassa. Hyödyllinen, jos opiskelijoiden on tarpeen tietää ryhmänsä assistentti.';
$string['studentcancreate'] = 'Opiskelijat voivat luoda ryhmiä';
$string['studentcancreate_help'] = 'Salli ryhmättömien opiskelijoiden (valitussa ryhmittelyssä) luoda ryhmiä';
$string['studentcansetdesc'] = 'Opiskelijat voivat asettaa ryhmälleen kuvauksen ja muokata sitä';
$string['studentcansetdesc_help'] = 'Salli opiskelijan asettaa ryhmälleen kuvaus ryhmänluonnin yhteydessä ja ryhmän jäsenten muokata sitä';
$string['unselectconfirm'] = 'Haluatko todella lähteä ryhmästä <em>{$a}</em>?';
$string['unselect'] = 'Lähde ryhmästä {$a}';
$string['select'] = 'Liity ryhmään {$a}';
$string['targetgrouping'] = 'Käytä ryhmittelyä';
$string['timeavailable'] = 'Avautuu';
$string['timedue'] = 'Sulkeutuu';
