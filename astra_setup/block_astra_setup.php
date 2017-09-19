<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot .'/blocks/moodleblock.class.php');

/**
 * Moodle block for accessing course-level Astra exercise administration.
 * For example, automatic creation of Astra exercise (round) activities in the course
 * based on the exercise configurations in the external exercise service server.
 */
class block_astra_setup extends block_list {

    const STR_PLUGINNAME = 'block_astra_setup'; /** plugin name for get_string() */
    const PLUGINNAME = 'astra_setup'; /** plugin name without prefixes */

    /** Return the path to the mod astra directory. */
    public static function get_mod_astra_path() {
        // PHP class constants cannot be defined with a return value from a function call,
        // so we have this static method
        global $CFG;
        return $CFG->dirroot .'/mod/astra';
    }


    public function init() {
        $this->title = get_string('pluginname', self::STR_PLUGINNAME);
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         = new stdClass();
        $this->content->items  = array();
        $this->content->icons  = array();
        $this->content->footer = '';
        $cid = $this->page->course->id; // course id
        $context = context_course::instance($cid);

        if (has_capability('mod/astra:addinstance', $context)) {

            // Links to course-level Astra exercise administration:
            // 1) edit exercises (add/edit/delete/automatic setup from exercise service config)
            // 2) Deviations (student-specific deadline/submission limit extensions)
            // 3) Export course data
            // 4) Mass regrading (upload many submissions at once to the exercise service for regrading)

            //$this->content->icons[] = $create_img;
            // the result looks better when the icon is combined to the link instead of separate content->icons
            $this->content->items[] = $this->render_list_item(\mod_astra\urls\urls::editCourse($cid, true),
                    'editexercises', 'i/settings');

            // deviations
            $this->content->items[] = $this->render_list_item(\mod_astra\urls\urls::deviations($cid, true),
                    'deviations', 'i/scheduled');
            
            // export results
            $this->content->items[] = $this->render_list_item(\mod_astra\urls\urls::exportIndex($cid, true),
                    'exportdata', 'i/export');
            
            // mass regrading
            $this->content->items[] = $this->render_list_item(
                    \mod_astra\urls\urls::massRegrading($cid, true),
                    'massregrading', 'i/grades');
        }
        // without capability, content->items and footer are empty and the block is then not displayed
        return $this->content;
    }

    protected function render_list_item(moodle_url $link_url, $link_text_id, $icon_name) {
        global $OUTPUT;
        
        $text = get_string($link_text_id, self::STR_PLUGINNAME);
        $icon = $OUTPUT->pix_icon($icon_name, $text);
        // pix_icon produces the HTML for the icon <img> element, taking into account that
        // themes may override core icons. Modern themes may output Font Awesome icons
        // in Moodle versions 3.3+
        return html_writer::link($link_url, $icon .' '. $text);
    }
    
    /**
     * Which page types this block may appear on.
     */
    public function applicable_formats() {
        // only the course main page (and mod astra pages)
        return array(
            'course-view' => true,
            'mod-astra' => true,
        );
    }

    /** Return an array of capabilities that are used by the block plugin
     * (other than defined in db/access.php).
     */
    public static function get_extra_capabilities() {
        $caps = parent::get_extra_capabilities(); // array('moodle/block:view', 'moodle/block:edit');
        $caps[] = 'mod/astra:addinstance';
        return $caps;
    }
}
