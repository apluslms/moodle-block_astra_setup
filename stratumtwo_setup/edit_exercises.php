<?php
/** Page that lets the user edit/add/delete Stratum2 exercises in a Moodle course.
 * The user can modify settings manually or create and update exercises automatically
 * based on the configuration in the Stratum2 exercise service.
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php'); // defines MOODLE_INTERNAL for libraries
require_once(dirname(__FILE__) .'/locallib.php');
require_once(dirname(__FILE__) .'/block_stratumtwo_setup.php');
require_once(block_stratumtwo_setup::get_mod_stratumtwo_path() .'/locallib.php');

$cid = required_param('course', PARAM_INT); // Course ID
$course = get_course($cid);

require_login($course, false);
$context = context_course::instance($cid);
require_capability('moodle/course:manageactivities', $context);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $module_numbering = \mod_stratumtwo_course_config::MODULE_NUMBERING_ARABIC;
    if (isset($_POST['module_numbering'])) {
        $module_numbering = (int) $_POST['module_numbering'];
    }
    $content_numbering = \mod_stratumtwo_course_config::CONTENT_NUMBERING_ARABIC;
    if (isset($_POST['content_numbering'])) {
        $content_numbering = (int) $_POST['content_numbering'];
    }
    
    $submitted = isset($_POST['save']) || isset($_POST['renumbermodule']) || isset($_POST['renumbercourse']);
    if ($submitted) {
        \mod_stratumtwo_course_config::updateOrCreate($cid, null, null, null, $module_numbering, $content_numbering);
        
        if (isset($_POST['save'])) {
            block_stratumtwo_setup_rename_rounds_with_numbers($cid, $module_numbering);
        } else if (isset($_POST['renumbermodule'])) {
            block_stratumtwo_setup_renumber_rounds_and_exercises($cid, $module_numbering, false);
        } else if (isset($_POST['renumbercourse'])) {
            block_stratumtwo_setup_renumber_rounds_and_exercises($cid, $module_numbering, true);
        }
        // clear cache so that course main page shows the updated exercise round names (Moodle course modules)
        rebuild_course_cache($cid);
    }
}

stratumtwo_page_require($PAGE); // Bootstrap CSS etc.
// Print the page header.
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/blocks/'. block_stratumtwo_setup::PLUGINNAME .'/edit_exercises.php', array('course' => $cid));
$PAGE->set_title(format_string(get_string('editexercises', block_stratumtwo_setup::STR_PLUGINNAME)));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
$output = $PAGE->get_renderer(block_stratumtwo_setup::STR_PLUGINNAME);

echo $output->header();

$renderable = new \block_stratumtwo_setup\output\edit_exercises_page($cid);
echo $output->render($renderable);

// Finish the page.
echo $output->footer();
