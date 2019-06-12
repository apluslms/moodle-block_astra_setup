<?php

namespace block_astra_setup\privacy;

defined('MOODLE_INTERNAL') || die;


class provider implements
    // This plugin does not store any personal user data.
    \core_privacy\local\metadata\null_provider {

    public static function get_reason() : string {
        return 'privacy:metadata';
    }
}
