<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Automatically generated strings for Moodle installer
 *
 * Do not edit this file manually! It contains just a subset of strings
 * needed during the very first steps of installation. This file was
 * generated automatically by export-installer.php (which is part of AMOS
 * {@link https://moodledev.io/general/projects/api/amos}) using the
 * list of strings defined in /install/stringnames.txt.
 *
 * @package   installer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['cannotcreatedboninstall'] = '<p>Kan ikke oprette databasen.</p> <p>Den specificerede database eksisterer ikke og brugeren har ikke tilladelse til at oprette den.</p> <p>Administrator bør verificere databasekonfigurationen.</p>';
$string['cannotcreatelangdir'] = 'Kan ikke oprette sprogmappe';
$string['cannotcreatetempdir'] = 'Kan ikke oprette temp-mappe';
$string['cannotdownloadcomponents'] = 'Kan ikke downloade komponenter';
$string['cannotdownloadzipfile'] = 'Kan ikke downloade zip-fil';
$string['cannotfindcomponent'] = 'Kan ikke finde komponent';
$string['cannotsavemd5file'] = 'Kan ikke gemme md5-fil';
$string['cannotsavezipfile'] = 'Kan ikke gemme zip-fil';
$string['cannotunzipfile'] = 'Kan ikke pakke filen ud';
$string['componentisuptodate'] = 'Komponenten er opdateret';
$string['dmlexceptiononinstall'] = '<p>En database fejl er opstået [{$a->errorcode}].<br />{$a->debuginfo}</p>';
$string['downloadedfilecheckfailed'] = 'Downloadet fil-tjek mislykkedes';
$string['invalidmd5'] = 'Tjekvariablen var forkert - prøv igen';
$string['missingrequiredfield'] = 'Der mangler nogle obligatoriske felter';
$string['remotedownloaderror'] = '<p>Download af komponent til din server fejlede. Verificer venligst proxy-indstillilngerne; udvidelsen PHP cURL  anbefales kraftigt. </p> <p>Du må downloade filen <a href="{$a->url}">{$a->url}</a> manuelt, kopier den til "{$a->dest}" på din server og udpak den der.</p>';
$string['wrongdestpath'] = 'Forkert destinationssti';
$string['wrongsourcebase'] = 'Forkert kilde-URL';
$string['wrongzipfilename'] = 'Forkert zip-filnavn';
