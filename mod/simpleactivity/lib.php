<?php

defined('MOODLE_INTERNAL') || die();


function simpleactivity_add_instance($simpleactivity) {
    global $DB;
    $cmid = $simpleactivity->coursemodule;

    // Process the options from the form.
    $simpleactivity->timecreated = time();


    // Try to store it in the database.
    $simpleactivity->id = $DB->insert_record('simpleactivity', $simpleactivity);


    return $simpleactivity->id;
}

// function simpleactivity_update_instance($simpleactivity) {
//     global $DB;
//     // require_once($CFG->dirroot . '/mod/simpleactivity/locallib.php');

//     // Get the current value, so we can see what changed.
//     // $simpleactivity = $DB->get_record('simpleactivity', array('id' => $simpleactivity->instance));

//     // Update the database.
//     $simpleactivity->timemodified = time();
//     $simpleactivity->id = $simpleactivity->instance;
//     $DB->update_record('simpleactivity', $simpleactivity);

//     return true;
// }

function simpleactivity_update_instance($simpleactivity, $mform) {
    global $CFG, $DB;
    // require_once($CFG->dirroot . '/mod/simpleactivity/locallib.php');

    // Get the current value, so we can see what changed.
    $oldsimpleactivity = $DB->get_record('simpleactivity', array('id' => $simpleactivity->instance));

    // We need two values from the existing DB record that are not in the form,
    // in some of the function calls below.
    $simpleactivity->timecreated = $oldsimpleactivity->timecreated;
    $simpleactivity->introformat = $oldsimpleactivity->introformat;
    $simpleactivity->intro = $oldsimpleactivity->intro;
    $simpleactivity->timemodified = time();

    // Update the database.
    $simpleactivity->id = $simpleactivity->instance;
    $DB->update_record('simpleactivity', $simpleactivity);

    return true;
}

function simpleactivity_supports($feature) {
    switch($feature) {
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_COMPLETION_HAS_RULES: return true;
        default: return null;
    }
}

 /**
  * Obtains the automatic completion state for this simpleactivity based on any conditions
  * in forum settings.
  *
  * @param object $course Course
  * @param object $cm Course-module
  * @param int $userid User ID
  * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
  * @return bool True if completed, false if not, $type if conditions not set.
  */
function simpleactivity_get_completion_state($course,$cm,$userid,$type) {
    global $CFG,$DB;

    // Get forum details
    $simpleactivity = $DB->get_record('simpleactivity', array('id' => $cm->instance), '*', MUST_EXIST);

    // If completion option is enabled, evaluate it and return true/false 
    if($simpleactivity->completionaccept) {
        return $simpleactivity->completionaccept <= $DB->get_field_sql("
        SELECT 
            COUNT(1) 
        FROM 
            {simpleactivity_acceptterms} fp 
        WHERE
            fp.userid=:userid AND fp.simpleactivity_id=:simpleactivity_id",
                    array('userid'=>$userid,'simpleactivity_id'=>$simpleactivity->id));
    } else {
        // Completion option is not enabled so just return $type
        return $type;
    }
}