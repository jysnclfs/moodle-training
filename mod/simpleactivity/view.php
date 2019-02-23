<?php


require('../../config.php');
require_once('lib.php');
require_once('termsandcondition_form.php');

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('simpleactivity', $cmid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$simpleactivity = $DB->get_record('simpleactivity', array('id'=> $cm->instance), '*', MUST_EXIST);

require_login($course, true, $cm);

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_url('/mod/simpleactivity/view.php', array('id' => $cm->id));
$title = $course->shortname . ': ' . format_string($simpleactivity->name);
$PAGE->set_title($title);
$PAGE->set_heading($course->fullname);

$actionurl = new moodle_url('/mod/simpleactivity/view.php', array('id' => $cm->id));
$nexturl = new moodle_url('/course/view.php', array('id' => $course->id));

$mform = new termsandcondition_form($actionurl);

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($nexturl);
} else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.

    if(!empty($fromform->accept)){
        // Try to store it in the database.
        $acceptterms = new stdClass();
        $acceptterms->simpleactivity_id = $simpleactivity->id;
        $acceptterms->userid = $USER->id;
        $acceptterms->timecreated = time();
        $acceptterms->timemodified = time();
        $DB->insert_record('simpleactivity_acceptterms', $acceptterms);
    
        $completion = new completion_info($course);
        if($completion->is_enabled($cm) && $simpleactivity->completionaccept) {
            $completion->update_state($cm,COMPLETION_COMPLETE);
        }
    }

    redirect($nexturl);
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
 
  //Set default data (if any)
  //$mform->set_data($toform);
  //displays the form

  echo $OUTPUT->header();
  echo '<h2>'.$simpleactivity->name.'</h2>';
  $mform->display();
  echo $OUTPUT->footer();
}

