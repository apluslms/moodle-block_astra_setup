<?php

$plugin->component = 'block_astra_setup'; // Declare the type and name of this plugin.
// YYYYMMDDHH (year, month, day, 24-hr time/version counter of the day)
$plugin->version = 2018081300;
$plugin->requires = 2015111600; // Moodle 3.0
$plugin->release = 'v1.2';
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = array(
    'mod_astra' => 2017102000,
);
