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

use eduvalab\msocial\msocial_plugin;
use eduvalab\msocial\msocial_view_plugin;

defined('MOODLE_INTERNAL') || die();


/**
 * library class for view the network activity as a table extending view plugin base class
 *
 * @package msocialview_timeglider
 * @copyright 2017 Juan Pablo de Castro {@email jpdecastro@tel.uva.es}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class timeglider_plugin extends msocial_view_plugin {

   
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

  

    public function get_category() {
        return msocial_plugin::CAT_VISUALIZATION;
    }

    public function get_subtype() {
        return 'timeglider';
    }

 
}
