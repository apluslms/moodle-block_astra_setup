<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot .'/blocks/moodleblock.class.php');

/**
 * Moodle block for accessing course-level Stratum2 exercise administration.
 * For example, automatic creation of Stratum2 exercise (round) activities in the course
 * based on the exercises configuration in the external Stratum server (exercise service).
 */
class block_stratumtwo_setup extends block_list {

    const STR_PLUGINNAME = 'block_stratumtwo_setup'; /** plugin name for get_string() */
    const PLUGINNAME = 'stratumtwo_setup'; /** plugin name without prefixes */

    /** Return the path to the mod stratumtwo directory. */
    public static function get_mod_stratumtwo_path() {
        // PHP class constants cannot be defined with a return value from a function call,
        // so we have this static method
        global $CFG;
        return $CFG->dirroot .'/mod/stratumtwo';
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

        if (has_capability('mod/stratumtwo:addinstance', $context)) {

            // Links to course-level Stratum2 exercises administration:
            // 1) edit exercises (add/edit/delete/automatic setup from exercise service config)
            // 2) Deviations (student-specific deadline/submission limit extensions)
            // 3) Export course results (points) to a JSON file that the teacher can download
            // 4) Export submitted files to a zip archive

            //$this->content->icons[] = $create_img;
            // the result looks better when the icon is combined to the link instead of separate content->icons
            $this->content->items[] = $this->render_list_item(\mod_stratumtwo\urls\urls::editCourse($cid, true),
                    'editexercises', (new moodle_url('/pix/i/settings.png'))->out());

            // deviations
            $this->content->items[] = $this->render_list_item(\mod_stratumtwo\urls\urls::deviations($cid, true),
                    'deviations', (new moodle_url('/pix/i/scheduled.png'))->out());
            
            // export results
            $this->content->items[] = $this->render_list_item(\mod_stratumtwo\urls\urls::exportResults($cid, true),
                    'exportresults', (new moodle_url('/pix/i/export.png'))->out());
            
            // export submitted files
            $this->content->items[] = $this->render_list_item(\mod_stratumtwo\urls\urls::exportSubmittedFiles($cid, true),
                    'exportsubmittedfiles', (new moodle_url('/pix/i/export.png'))->out());
        }
        // without capability, content->items and footer are empty and the block is then not displayed
        return $this->content;
    }

    protected function render_list_item(moodle_url $link_url, $link_text_id, $img_src) {
        $icon_attrs = array('height' => 16, 'width' => 16);
        $text = get_string($link_text_id, self::STR_PLUGINNAME);
        $img = html_writer::img($img_src, $text, $icon_attrs);
        return html_writer::link($link_url, $img .' '. $text);
    }
    
    /**
     * Which page types this block may appear on.
     */
    public function applicable_formats() {
        // only the course main page (and mod stratumtwo pages)
        return array(
            'course-view' => true,
            'mod-stratumtwo' => true,
        );
    }

    /** Return an array of capabilities that are used by the block plugin
     * (other than defined in db/access.php).
     */
    public static function get_extra_capabilities() {
        $caps = parent::get_extra_capabilities(); // array('moodle/block:view', 'moodle/block:edit');
        $caps[] = 'mod/stratumtwo:addinstance';
        return $caps;
    }
}
