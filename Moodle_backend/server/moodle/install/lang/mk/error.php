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

$string['cannotcreatedboninstall'] = '<p>Не може да се креира базата со податоци.</p>
<p>Посочената база со податоци не постои и корисникот нема овластувања да ја креира базата со податоци.</p>
<p>Администраторот на сајтот треба да ја верификува конфигурацијата на базата со податоци.</p>';
$string['cannotcreatelangdir'] = 'Lang именик не може да се креира';
$string['cannotcreatetempdir'] = 'Не може да се креира привремена папка';
$string['cannotdownloadcomponents'] = 'Не можете да ги симнете компонентите';
$string['cannotdownloadzipfile'] = 'Не можете да спуштите ZIP фајл';
$string['cannotfindcomponent'] = 'Компонентата не е пронајдена';
$string['cannotsavemd5file'] = 'Не може да се сними md5 фајлот';
$string['cannotsavezipfile'] = 'Не може да се сними ZIP фајл';
$string['cannotunzipfile'] = 'Не можеше да се одзипува фајлот';
$string['componentisuptodate'] = 'Компонентата е ажурирана.';
$string['dmlexceptiononinstall'] = '<p>Настана грешка во базата на податоци [{$a->errorcode}].<br />{$a->debuginfo}</p>';
$string['downloadedfilecheckfailed'] = 'Проверката на преземениот фајл не успеа';
$string['invalidmd5'] = 'Променливата за проверка беше погрешна - обидете се повторно';
$string['missingrequiredfield'] = 'Некое задолжително поле недостасува';
$string['remotedownloaderror'] = '<p>Преземањето на компонентата на вашиот сервер не успеа. Проверете ги поставките за прокси; се препорачува екстензијата PHP cURL.</p>
<p>Мора да го рачно да го симнете фајлот <a href="{$a->url}">{$a->url}</a>, копирајте го "{$a->dest}" во вашиот сервер и таму отпакувајте го.</p>';
$string['wrongdestpath'] = 'Погрешна дестинациска патека';
$string['wrongsourcebase'] = 'Погрешна URL на извор';
$string['wrongzipfilename'] = 'Погрешно име на ZIP фајл';
