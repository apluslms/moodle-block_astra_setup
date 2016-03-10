<?php

namespace block_stratumtwo_setup\output;

defined('MOODLE_INTERNAL') || die();

class edit_exercises_page implements \renderable, \templatable {
    
    protected $courseid;
    
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }
    
    public function export_for_template(\renderer_base $output) {
        $ctx = new \stdClass();
        $ctx->autosetupurl = (new \moodle_url('/blocks/'. \block_stratumtwo_setup::PLUGINNAME .'/auto_setup.php',
                array('course' => $this->courseid)))->out();
        return $ctx;
    }
}