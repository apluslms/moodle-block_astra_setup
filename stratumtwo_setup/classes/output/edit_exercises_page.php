<?php

namespace block_stratumtwo_setup\output;

defined('MOODLE_INTERNAL') || die();

class edit_exercises_page implements \renderable, \templatable {
    
    protected $courseid;
    protected $moduleNumbering;
    protected $contentNumbering;
    
    public function __construct($courseid) {
        global $DB;
        
        $this->courseid = $courseid;
        
        $course_settings = $DB->get_record(\mod_stratumtwo_course_config::TABLE, array('course' => $courseid));
        if ($course_settings === false) {
            $this->moduleNumbering = \mod_stratumtwo_course_config::MODULE_NUMBERING_ARABIC;
            $this->contentNumbering = \mod_stratumtwo_course_config::CONTENT_NUMBERING_ARABIC;
        } else {
            $conf = new \mod_stratumtwo_course_config($course_settings);
            $this->moduleNumbering = $conf->getModuleNumbering();
            $this->contentNumbering = $conf->getContentNumbering();
        }
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
        
        $ctx->module_numbering_options = function($mustacheHelper) {
            $options = array(
                \mod_stratumtwo_course_config::MODULE_NUMBERING_NONE => 
                    \get_string('nonumbering', \block_stratumtwo_setup::STR_PLUGINNAME),
                \mod_stratumtwo_course_config::MODULE_NUMBERING_ARABIC => 
                    \get_string('arabicnumbering', \block_stratumtwo_setup::STR_PLUGINNAME),
                \mod_stratumtwo_course_config::MODULE_NUMBERING_ROMAN => 
                    \get_string('romannumbering', \block_stratumtwo_setup::STR_PLUGINNAME),
                \mod_stratumtwo_course_config::MODULE_NUMBERING_HIDDEN_ARABIC => 
                    \get_string('hiddenarabicnum', \block_stratumtwo_setup::STR_PLUGINNAME),
            );
            $result = '';
            foreach ($options as $val => $text) {
                $selected = '';
                if ($val === $this->moduleNumbering) {
                    $selected = ' selected="selected"';
                }
                $result .= "<option value=\"$val\"$selected>$text</option>";
            }
            return $result;
        };
        $ctx->content_numbering_options = function($mustacheHelper) {
            $options = array(
                \mod_stratumtwo_course_config::CONTENT_NUMBERING_NONE => 
                    \get_string('nonumbering', \block_stratumtwo_setup::STR_PLUGINNAME),
                \mod_stratumtwo_course_config::CONTENT_NUMBERING_ARABIC => 
                    \get_string('arabicnumbering', \block_stratumtwo_setup::STR_PLUGINNAME),
                \mod_stratumtwo_course_config::CONTENT_NUMBERING_ROMAN => 
                    \get_string('romannumbering', \block_stratumtwo_setup::STR_PLUGINNAME),
            );
            $result = '';
            foreach ($options as $val => $text) {
                $selected = '';
                if ($val === $this->contentNumbering) {
                    $selected = ' selected="selected"';
                }
                $result .= "<option value=\"$val\"$selected>$text</option>";
            }
            return $result;
        };
        return $ctx;
    }
}