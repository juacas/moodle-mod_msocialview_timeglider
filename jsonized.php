<?php
// This file is part of MSocial activity for Moodle http://moodle.org/
//
// MSocial for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// MSocial for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/* ***************************
 * Module developed at the University of Valladolid
 * Designed and directed by Juan Pablo de Castro at telecommunication engineering school
 * Copyright 2017 onwards EdUVaLab http://www.eduvalab.uva.es
 * @author Juan Pablo de Castro
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package msocial
 * @copyright 2017 Juan Pablo de Castro <jpdecastro@tel.uva.es>
 * *******************************************************************************/
use mod_msocial\connector\social_interaction;
require_once('../../../../config.php');
require_once('../../locallib.php');
require_once('../../msocialconnectorplugin.php');
require_once('../../socialinteraction.php');

header('Content-Type: application/json; charset=utf-8');
$id = required_param('id', PARAM_INT);
$fromdate = optional_param('startdate', null, PARAM_ALPHANUMEXT);
$todate = optional_param('enddate', null, PARAM_ALPHANUMEXT);
$subtype = optional_param('subtype', null, PARAM_ALPHA);
$cm = get_coursemodule_from_id('msocial', $id, null, null, MUST_EXIST);
$msocial = $DB->get_record('msocial', array('id' => $cm->instance), '*', MUST_EXIST);
require_login($cm->course, false, $cm);
$plugins = mod_msocial\plugininfo\msocialconnector::get_enabled_connector_plugins($msocial);

if ($subtype) {
    $subtypefilter = "source ='$subtype'";
} else {
    $subtypefilter = '';
}
$events = [];
$lastitemdate = null;
$firstitemdate = null;
// Process interactions.
$interactions = social_interaction::load_interactions((int) $msocial->id, $subtypefilter, $fromdate, $todate);
foreach ($interactions as $interaction) {
    if ($interaction->timestamp == null) {
        continue;
    }
    if ($lastitemdate == null || $lastitemdate < $interaction->timestamp) {
        $lastitemdate = $interaction->timestamp;
    }
    if ($firstitemdate == null || $firstitemdate > $interaction->timestamp) {
        $firstitemdate = $interaction->timestamp;
    }
    $date = $interaction->timestamp->format('Y-m-d H:i:s');
    $subtype = $interaction->source;
    $plugin = $plugins[$subtype];
    $userinfo = $plugin->get_social_userid($interaction->fromid);
    if (!$userinfo) {
        $userinfo = (object) ['socialname' => $interaction->nativefromname];
    }
    $url = $plugin->get_interaction_url($interaction);
    $event = ['id' => $interaction->uid, 'startdate' => $date, 'title' => $userinfo->socialname,
                    'description' => $interaction->description, 'icon' => $plugin->get_icon()->out(), 'link' => $url,
                    'importance' => 10, 'date_limit' => 'mo'];
    $events[] = $event;
}
$legend = [];
foreach ($plugins as $plugin) {
    $legend[] = (object) ['title' => $plugin->get_name(), 'icon' => $plugin->get_icon()->out()];
}
$timespan = $lastitemdate->getTimestamp() - $firstitemdate->getTimestamp();
$focusdate = $firstitemdate->add(new DateInterval("PT" . (int)($timespan / 2) . "S"))->format('Y-m-d H:i:s');
// Seems that zoom 6 is about 1 day and 29 1 year.
$initialzoom = 6 + (int) log( $timespan / (3600 * 24), 29);
$jsondata = [
                (object) ['id' => 'e', 'description' => 'Twitter count timeglide', 'title' => 'Timeglider',
                                'focus_date' => $focusdate, 'initial_zoom' => $initialzoom, 'events' => $events, 'legend' => $legend,
                                'size_importance' => false]];
$jsonencoded = json_encode($jsondata);
echo $jsonencoded;