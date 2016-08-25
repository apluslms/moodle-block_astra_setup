<?php

defined('MOODLE_INTERNAL') || die();
 
$capabilities = array(
    /* Add a new Astra setup block to a Moodle course. */
    'block/astra_setup:addinstance' => array(

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
