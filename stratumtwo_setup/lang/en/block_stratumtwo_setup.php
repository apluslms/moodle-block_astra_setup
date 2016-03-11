<?php

$string['pluginname'] = 'Stratum2 exercises setup'; // required by Moodle core

$string['editexercises'] = 'Edit exercises';
$string['autosetup'] = 'Update and create Stratum2 exercises automatically';
$string['autosetup_help'] = 'Import configuration from the exercise service URL and override course contents (Stratum2 exercise rounds, exercises and categories).';

// edit exercises page
$string['exercisecategories'] = 'Exercise categories';
$string['editcategory'] = 'Edit category';
$string['remove'] = 'Remove';
$string['addnewcategory'] = 'Add new category';
$string['exerciserounds'] = 'Exercise rounds';
$string['editmodule'] = 'Edit exercise round';
$string['openround'] = 'Open the exercise round';
$string['editexercise'] = 'Edit exercise';
$string['openexercise'] = 'Open exercise';
$string['addnewlearningobject'] = 'Add new learning object';
$string['addnewmodule'] = 'Add new exercise round';
$string['save'] = 'Save';
$string['renumerateformodules'] = 'Renumerate learning objects for each module';
$string['renumerateignoremodules'] = 'Renumerate learning objects ignoring modules';
$string['modulenumbering'] = 'Module numbering';
$string['contentnumbering'] = 'Content numbering';
$string['nonumbering'] = 'No numbering';
$string['arabicnumbering'] = 'Arabic';
$string['romannumbering'] = 'Roman';
$string['hiddenarabicnum'] = 'Hidden arabic';

// auto setup form
$string['configurl'] = 'Configuration URL';
$string['configurl_help'] = 'Configuration data for course exercises is downloaded from this URL.';
$string['apikey'] = 'API key';
$string['apikey_help'] = 'API key to authorize access to the exercise service.';
$string['sectionnum'] = 'Moodle course section number';
$string['sectionnum_help'] = 'Number (0-N) of the Moodle course section, to which new exercise round activities should be added. Section zero is the course home page, the next section is number 1 and so on (see the navigation in the course page).';
$string['apply'] = 'Apply';
$string['createreminder'] = 'Reminder: in MyCourses, you must have the &quot;Advanced teacher&quot; role to create Stratum2 exercises.';
$string['backtocourse'] = 'Back to the course';
$string['autosetupsuccess'] = 'Configuration was downloaded and applied successfully.';
$string['autosetuperror'] = 'There were errors in the automatic setup.';

// Errors
$string['configjsonparseerror'] = 'The response from the server could not be parsed as JSON.';
$string['configcategoriesmissing'] = 'Categories are required as JSON object.';
$string['configmodulesmissing'] = 'Modules (exercise rounds) are required as JSON array.';
$string['configcatnamemissing'] = 'Category requires a name.';
$string['configbadstatus'] = 'Status has an invalid value: {$a}.';
$string['configbadint'] = 'Expected integer, but received: {$a}.';
$string['configmodkeymissing'] = 'Module (exercise round) requires a key.';
$string['configbadfloat'] = 'Expected floating-point number, but received: {$a}.';
$string['configbaddate'] = 'Unable to parse date: {$a}.';
$string['configbadduration'] = 'Unable to parse duration: {$a}.';
$string['configexercisekeymissing'] = 'Exercise requires a key.';
$string['configexercisecatmissing'] = 'Exercise requires a category.';
$string['configexerciseunknowncat'] = 'Exercise has an unknown category: {$a}.';
$string['configchapternotsupported'] = 'Moodle plugin supports only exercises, not other learning objects (chapters).';

/*
$string['nostratums'] = 'No Stratum assignment activities were found in this course.';
$string['clicktoupdate'] = 'Click here to update settings for all Stratum assignments';
$string['updatesuccess'] = 'Settings for all Stratum assignment activities were updated successfully.';
$string['updateerror'] = 'Error: the settings for the following assignment activities could not be updated.';
$string['nocmerror'] = 'The (sub)assignment has no corresponding course module.';
$string['asgnsettingsmissing'] = 'The Stratum server did not return settings for this (sub)assignment.';
$string['createreminder'] = 'Reminder: in MyCourses, you must have the &quot;Advanced teacher&quot; role to create Stratum assignments.';
*/

// capability description
$string['stratumtwo_setup:addinstance'] = 'Add a new block for the setup of Stratum2 exercises. It is used to automatically create and configure all Stratum2 exercises in the course.';
