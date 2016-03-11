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
        $ctx->categories = array();
        foreach (\mod_stratumtwo_category::getCategoriesInCourse($this->courseid) as $cat) {
            $ctx->categories[] = $cat->getTemplateContext();
        }
        $ctx->create_category_url = \mod_stratumtwo\urls\urls::createCategory();
        $ctx->course_modules = array();
        foreach (\mod_stratumtwo_exercise_round::getExerciseRoundsInCourse($this->courseid) as $exround) {
            $ctx->course_modules[] = $exround->getTemplateContextWithExercises(true);
        }
        $ctx->create_module_url = \mod_stratumtwo\urls\urls::createExerciseRound();
        $ctx->renumber_action_url = 'edit_exercises.php?course='. $this->courseid; // the page itself
        //TODO select selected attr in numerate form
        return $ctx;
    }
}