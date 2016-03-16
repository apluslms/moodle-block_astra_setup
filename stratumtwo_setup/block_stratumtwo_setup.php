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
        $icon_attrs = array('height' => 16, 'width' => 16);
        $cid = $this->page->course->id; // course id
        $context = context_course::instance($cid);

        if (has_capability('mod/stratumtwo:addinstance', $context)) {

            // Links to course-level Stratum2 exercises administration:
            // 1) edit exercises (add/edit/delete/automatic setup from exercise service config)
            // 2) Deviations (student-specific deadline/submission limit extensions)

            $edit_img_url = new moodle_url('/pix/a/setting.png');
            $edit_img = html_writer::img($edit_img_url->out(),
                    get_string('editexercises', self::STR_PLUGINNAME), $icon_attrs);
            //$this->content->icons[] = $create_img;
            // the result looks better when the icon is combined to the link
            $this->content->items[] = html_writer::link(
                    \mod_stratumtwo\urls\urls::editCourse($cid, true),
                    $edit_img .' '. get_string('editexercises', self::STR_PLUGINNAME));

            // TODO deviations
        }
        // without capability, content->items and footer are empty and the block is then not displayed
        return $this->content;
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
