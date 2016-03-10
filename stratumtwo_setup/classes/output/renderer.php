<?php

namespace block_stratumtwo_setup\output;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base {

    protected function render_edit_exercises_page($page) {
        $data = $page->export_for_template($this);
        return $this->render_from_template(\block_stratumtwo_setup::STR_PLUGINNAME .'/edit_exercises', $data);
    }
}