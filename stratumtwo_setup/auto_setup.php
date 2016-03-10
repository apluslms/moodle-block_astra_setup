<?php
/** Page for automatic setup of Stratum2 exercises.
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php'); // defines MOODLE_INTERNAL for libraries
require_once(dirname(__FILE__) .'/block_stratumtwo_setup.php');

$cid = required_param('course', PARAM_INT); // Course ID
$course = get_course($cid);

require_login($course, false);
$context = context_course::instance($cid);
require_capability('moodle/course:manageactivities', $context);

// Print the page header.
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/blocks/'. block_stratumtwo_setup::PLUGINNAME .'/auto_setup.php', array('course' => $cid));
$PAGE->set_title(format_string(get_string('autosetup', block_stratumtwo_setup::STR_PLUGINNAME)));
$PAGE->set_heading(format_string($course->fullname));

$default_values = $DB->get_record(\mod_stratumtwo_course_config::TABLE, array('course' => $cid));

// Output starts here.
// gotcha: moodle forms should be initialized before $OUTPUT->header
$form = new \block_stratumtwo_setup\form\autosetup_form($default_values, 'auto_setup.php?course='. $cid);
$output = $PAGE->get_renderer(block_stratumtwo_setup::STR_PLUGINNAME);

echo $output->header();
echo $output->heading_with_help(get_string('autosetup', block_stratumtwo_setup::STR_PLUGINNAME),
        'autosetup', block_stratumtwo_setup::STR_PLUGINNAME);
echo '<p>'. get_string('createreminder', block_stratumtwo_setup::STR_PLUGINNAME) .'</p>';

// Form processing and displaying is done here
if ($form->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url('/course/view.php', array('id' => $cid)));
} else if ($fromform = $form->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    $errors = \block_stratumtwo_setup_auto_setup::configure_content_from_url($cid,
            $fromform->sectionnum, $fromform->configurl, $fromform->apikey);
    if (empty($errors)) {
        echo '<p>'. get_string('autosetupsuccess', block_stratumtwo_setup::STR_PLUGINNAME) .'</p>';
    } else {
        // errors in creating/updating some course content
        echo '<p>'. get_string('autosetuperror', block_stratumtwo_setup::STR_PLUGINNAME) .'</p>';
        echo html_writer::alist($errors);
    }
    
    echo '<p>'.
            html_writer::link(new moodle_url('/course/view.php', array('id' => $cid)),
                    get_string('backtocourse', block_stratumtwo_setup::STR_PLUGINNAME)) .
                    '</p>';
} else {
    // this branch is executed if the form is submitted but the data doesn't validate
    // and the form should be redisplayed, or on the first display of the form.
    $form->display();
}

// Finish the page.
echo $output->footer();
