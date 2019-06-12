<?php

$plugin->component = 'block_astra_setup'; // Declare the type and name of this plugin.
// YYYYMMDDHH (year, month, day, 24-hr time/version counter of the day)
$plugin->version = 2019061000;
$plugin->requires = 2018051700; // Moodle 3.5
$plugin->release = 'v1.3';
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = array(
    'mod_astra' => 2019061000,
);
