<?php

$plugin->component = 'block_stratumtwo_setup'; // Declare the type and name of this plugin.
// YYYYMMDDHH (year, month, day, 24-hr time/version counter of the day)
$plugin->version = 2016031600;
$plugin->requires = 2015111600; // Moodle 3.0
$plugin->release = 'v0.1';
$plugin->maturity = MATURITY_ALPHA;
$plugin->dependencies = array(
    'mod_stratumtwo' => 2016021900,
);
