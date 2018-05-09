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
require_once('../../classes/msocialconnectorplugin.php');
require_once('../../classes/socialinteraction.php');

header('Content-Type: application/json; charset=utf-8');
$id = required_param('id', PARAM_INT);
$redirecturl = optional_param('redirect', null, PARAM_RAW);

$cm = get_coursemodule_from_id('msocial', $id, null, null, MUST_EXIST);
$msocial = $DB->get_record('msocial', array('id' => $cm->instance), '*', MUST_EXIST);
require_login($cm->course, false, $cm);
$plugins = mod_msocial\plugininfo\msocialconnector::get_enabled_connector_plugins($msocial);
$contextcourse = context_course::instance($msocial->course);
$contextmodule = context_module::instance($cm->id);
$canviewothers = has_capability('mod/msocial:viewothers', $contextmodule);

$events = [];
$lastitemdate = null;
$firstitemdate = null;

$filter = new filter_interactions($_GET, $msocial);
$usersstruct = msocial_get_users_by_type($contextcourse);
$userrecords = $usersstruct->userrecords;

$filter->set_users($usersstruct);
// Process interactions.
$interactions = social_interaction::load_interactions_filter($filter);
if (count($interactions) > 0) {
    foreach ($interactions as $interaction) {
        $subtype = $interaction->source;
        if (!isset($plugins[$subtype])) {
            continue;
        }
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

        list($namefrom, $userlinkfrom) = msocial_create_userlink($interaction, 'from', $userrecords, $msocial, $cm, $redirecturl, $canviewothers);
        $thispageurl = $plugin->get_interaction_url($interaction);

        $event = ['id' => $interaction->uid, 'startdate' => $date, 'title' => '<a href="' . $userlinkfrom . '">' . $namefrom . '</a>',//$userinfo->socialname,
                        'description' => $plugin->get_interaction_description($interaction), 'icon' => $plugin->get_icon()->out(), 'link' => $thispageurl,
                        'importance' => 10, 'date_limit' => 'mo'];
        $events[] = $event;
    }
    $timespan = $lastitemdate->getTimestamp() - $firstitemdate->getTimestamp();
    $focusdate = $firstitemdate->add(new DateInterval("PT" . (int)($timespan / 2) . "S"))->format('Y-m-d H:i:s');
} else {
    $focusdate = new DateTime();
    $timespan = 60 * 24 * 3600;
}
$legend = [];
foreach ($plugins as $plugin) {
    $legend[] = (object) ['title' => $plugin->get_name(), 'icon' => $plugin->get_icon()->out()];
}
// Seems that zoom 6 is about 1 day and 29 1 year.
$initialzoom = 1 + 6 + (int) (log10( $timespan / (3600 * 24)) / log10(1.29));
$jsondata = [
                (object) ['id' => 'e', 'description' => 'Twitter count timeglide', 'title' => 'Timeglider',
                                'focus_date' => $focusdate, 'initial_zoom' => min([32, $initialzoom]),
                                'events' => $events, 'legend' => $legend,
                                'size_importance' => false]
                ];
$jsonencoded = json_encode($jsondata);
echo $jsonencoded;