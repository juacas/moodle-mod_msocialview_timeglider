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
 * *******************************************************************************
 */
namespace mod_msocial\view;

use msocial\msocial_plugin;

defined('MOODLE_INTERNAL') || die();


/**
 * library class for view the network activity as a table extending view plugin base class
 *
 * @package msocialview_timeglider
 * @copyright 2017 Juan Pablo de Castro {@email jpdecastro@tel.uva.es}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class msocial_view_timeglider extends msocial_view_plugin {

    /**
     * Get the name of the plugin
     *
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'msocialview_timeglider');
    }

    /**
     * Get the settings for the plugin
     *
     * @param MoodleQuickForm $mform The form to add elements to
     * @return void
     */
    public function get_settings(\MoodleQuickForm $mform) {
    }

    /**
     * Save the settings for table plugin
     *
     * @param \stdClass $data
     * @return bool
     */
    public function save_settings(\stdClass $data) {
        if (isset($data->msocialview_timeglider_enabled)) {
            $this->set_config('enabled', $data->msocialview_timeglider_enabled);
        }
        return true;
    }

    /**
     * The msocial has been deleted - cleanup subplugin
     *
     * @global moodle_database $DB
     * @return bool
     */
    public function delete_instance() {
        global $DB;
        $result = true;
        return $result;
    }

    public function get_category() {
        return msocial_plugin::CAT_VISUALIZATION;
    }

    public function get_subtype() {
        return 'timeglider';
    }

    public function get_icon() {
        return new \moodle_url('/mod/msocial/view/timeglider/pix/icon.svg');
    }


    /**
     *
     * {@inheritdoc}
     *
     * @see msocial_view_plugin::render_view()
     */
    public function render_view($renderer, $reqs, $filter) {
        global $PAGE;
        echo $filter->render_form($PAGE->url);

        echo "<style type='text/css'>
        /* timeline div style */
		#my-timeglider {
			width:100%;
			margin:32px auto 32px auto;
			height:600px;
		}
</style>";
        echo '<div id="my-timeglider" ></div>';
        echo $renderer->spacer(array('height' => 20));
        $reqs->js('/mod/msocial/view/timeglider/js/init_timeglider.js');
        $reqs->js_init_call("init_view", [$this->cm->id, null, $filter->get_filter_params_url()], false);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see msocial_view_plugin::view_set_requirements()
     */
    public function render_header_requirements($reqs, $viewparam) {
        if ($viewparam == $this->get_subtype()) {
            $reqs->css('/mod/msocial/view/timeglider/css/aristo/jquery-ui-1.8.5.custom.css');

            $reqs->css('/mod/msocial/view/timeglider/js/timeglider/Timeglider.css');
            $reqs->js('/mod/msocial/view/timeglider/js/jquery-1.4.4.min.js', true);
            $reqs->js('/mod/msocial/view/timeglider/js/jquery-ui-1.8.9.custom.min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/jquery.tmpl.js');
            $reqs->js('/mod/msocial/view/timeglider/js/underscore-min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/backbone-min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/ba-debug.min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/jquery.mousewheel.min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/jquery.ui.ipad.js');
            $reqs->js('/mod/msocial/view/timeglider/js/raphael-min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/jquery.global.js');
            $reqs->js('/mod/msocial/view/timeglider/js/ba-tinyPubSub.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Date.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Org.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Timeline.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_TimelineView.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Mediator.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/timeglider.timeline.widget.js');
        }
    }
}
