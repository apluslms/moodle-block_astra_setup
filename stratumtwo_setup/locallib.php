<?php
defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) .'/block_stratumtwo_setup.php');

/* Notes about Moodle database
tables (default prefix to table names is mdl_)
course_sections  visible topic sections in the course page
course_modules  activity added to the course
modules  activity module name mapping to ids (e.g. stratumtwo -> 27)

Tables starting with stratumtwo are defined in mod/stratumtwo/db/install.xml
*/

/** Sort Stratum2 exercise round activities in the given Moodle course section.
 * The exercise rounds are sorted according to Stratum2 settings
 * (smaller ordernum comes first).
 * Non-Stratum2 activities are kept before all Stratum2 activities.
 * @param int $courseid Moodle course ID
 * @param int $course_section section number (0-N) in the course,
 * sorting affects only the activities in this section
 * @return void
 */
function block_stratumtwo_setup_sort_activities_in_section($courseid, $course_section) {
    global $DB;
    $section_row = $DB->get_record('course_sections', array(
        'course'  => $courseid,
        'section' => $course_section,
    ), 'id, sequence', IGNORE_MISSING);
    if ($section_row === false) {
        return;
    }
    // course module records
    $course_modinfo = get_fast_modinfo($courseid)->cms; // indexes are course module ids
    // Stratum2 exercise round records in the course
    $stratumtwos = $DB->get_records(\mod_stratumtwo_exercise_round::TABLE, array('course' => $courseid));

    // sorting callback function for sorting an array of course module ids
    // (only Stratum modules allowed in the array)
    // Order: assignment 1 is less than asgn 2, asgn 1 subassignments follow asgn 1
    // immediately before asgn 2, in alphabetical order of subasgn names
    $sortfunc = function($cmid1, $cmid2) use ($course_modinfo, $stratumtwos) {
        $cm1 = $course_modinfo[$cmid1];
        $cm2 = $course_modinfo[$cmid2];
        // figure out Stratum2 round order numbers
        $order1 = $stratumtwos[$cm1->instance]->ordernum;
        $order2 = $stratumtwos[$cm2->instance]->ordernum;

        // must return an integer less than, equal to, or greater than zero if the
        // first argument is considered to be respectively less than, equal to, or
        // greater than the second
        if ($order1 < $order2) {
            return -1;
        } else if ($order2 < $order1) {
            return 1;
        } else { // same order number
            if ($cm1->instance < $cm2->instance) // compare IDs
                return -1;
            else if ($cm1->instance > $cm2->instance)
                return 1;
            else
                return 0;
        }
    };

    $non_stratum_modules = array(); // cm ids
    $stratum_modules = array();
    // cm ids in the section
    $course_module_ids = explode(',', trim($section_row->sequence));
    foreach ($course_module_ids as $cm_id) {
        $cm = $course_modinfo[$cm_id];
        if ($cm->modname == \mod_stratumtwo_exercise_round::TABLE) {
            $stratum_modules[] = $cm_id;
        } else {
            $non_stratum_modules[] = $cm_id;
        }
    }
    usort($stratum_modules, $sortfunc); // sort Stratum2 exercise round activities
    // add non-stratum modules to the beginning
    $section_cm_ids = array_merge($non_stratum_modules, $stratum_modules);
    // write the new section ordering (sequence) to DB
    $new_section_sequence = implode(',', $section_cm_ids);
    $DB->set_field('course_sections', 'sequence', $new_section_sequence,
        array('id' => $section_row->id));
}
