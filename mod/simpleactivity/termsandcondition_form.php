<?php

    //moodleform is defined in formslib.php
    require_once("$CFG->libdir/formslib.php");
    // require_once($CFG->dirroot.'/mod/simpleactivity/lib.php');
    
    class termsandcondition_form extends moodleform {
        //Add elements to form
        public function definition() {
            global $CFG;
    
            $mform = $this->_form; // Don't forget the underscore! 
    
            $mform->addElement('checkbox', 'accept', 'Accept Terms and Condition'); // Add elements to your form
            $mform->setDefault('accept', 0);        //Default value
            $this->add_action_buttons();
        }
        // Custom validation should be added here
        function validation($data, $files) {
            return array();
        }
    }