<?php

defined('MOODLE_INTERNAL') || die();
 
$capabilities = array(
    /* Add a new Stratum2 setup block to a Moodle course. */
    'block/stratumtwo_setup:addinstance' => array(

        'captype' => 'write',
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks',
    ),

);
