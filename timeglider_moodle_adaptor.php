<?php
namespace mod_msocial\view;
use mod_msocial\moodle_adaptor;
use eduvalab\msocial\filter_interactions;
require_once(__DIR__ . '/../../classes/moodle_adaptor.php');

class timeglider_moodle_adaptor extends moodle_adaptor
{
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
    public function get_icon() {
        return new \moodle_url('/mod/msocial/view/timeglider/pix/icon.svg');
    }
    
    
    /**
     *
     * {@inheritdoc}
     *
     * @see msocial_view_plugin::render_view()
     */
    public function render_view($renderer, $reqs, filter_interactions $filter) {
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
        if ($viewparam == $this->plugin->get_subtype()) {
            
            $reqs->css('/mod/msocial/view/timeglider/js/timeglider/Timeglider.css');
            $reqs->jquery_plugin('jquery.tmpl', 'msocialview_timeglider');
            $reqs->jquery_plugin('jquery.mousewheel', 'msocialview_timeglider');
            $reqs->jquery_plugin('jquery.ui.ipad', 'msocialview_timeglider');
            $reqs->jquery_plugin('jquery.global', 'msocialview_timeglider');
            $reqs->js('/mod/msocial/view/timeglider/js/underscore-min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/backbone-min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/ba-debug.min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/raphael-min.js');
            $reqs->js('/mod/msocial/view/timeglider/js/ba-tinyPubSub.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Date.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Org.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Timeline.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_TimelineView.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/TG_Mediator.js');
            $reqs->js('/mod/msocial/view/timeglider/js/timeglider/timeglider.timeline.widget.js');
        }
    }
    /** This allows a plugin to render an introductory section which is displayed
     * right below the activity's "intro" section on the main msocial page.
     *
     * @return array[string[], string[]] messages, notifications
     */
    public function render_header() {
        return [ [], [] ];
    }
    public function render_harvest_link() {
        return '';
    }
}
