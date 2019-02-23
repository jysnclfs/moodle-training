<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
 
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/simpleactivity/lib.php');
 
class mod_simpleactivity_mod_form extends moodleform_mod {
 
    function definition() {
        global $CFG, $DB, $OUTPUT;
 
        $mform =& $this->_form;
 
        $mform->addElement('text', 'name', get_string('simpleactivityname', 'simpleactivity'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
 
        // $ynoptions = array(0 => get_string('no'),
        //                    1 => get_string('yes'));
        // $mform->addElement('select', 'usecode', get_string('usecode', 'simpleactivity'), $ynoptions);
        // $mform->setDefault('usecode', 0);
        // $mform->addHelpButton('usecode', 'usecode', 'simpleactivity');
 
        $this->standard_coursemodule_elements();
 
        $this->add_action_buttons();
    }

    public function add_completion_rules() {

        $mform = $this->_form;
    
        $group = [
            $mform->createElement('checkbox', 'completionacceptenabled', ' ', get_string('completionaccept', 'simpleactivity')),
            $mform->createElement('hidden', 'completionaccept', ' ', ['size' => 3]),
        ];
        $mform->setType('completionaccept', PARAM_INT);
        $mform->addGroup($group, 'completionacceptgroup', get_string('completionacceptgroup','simpleactivity'), [' '], false);
        $mform->addHelpButton('completionacceptgroup', 'completionaccept', 'simpleactivity');
        $mform->disabledIf('completionaccept', 'completionacceptenabled', 'notchecked');
    
        return ['completionacceptgroup'];
    }

    public function completion_rule_enabled($data) {
        return (!empty($data['completionacceptenabled']) && $data['completionaccept'] != 0);
    }

    function get_data() {
        $data = parent::get_data();
        if (!$data) {
            return $data;
        }
        if (!empty($data->completionunlocked)) {
            // Turn off completion settings if the checkboxes aren't ticked
            $autocompletion = !empty($data->completion) && $data->completion==COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionacceptenabled) || !$autocompletion) {
               $data->completionaccept = 0;
            }
        }
        return $data;
    }

    function data_preprocessing(&$default_values){
        // [Existing code, not shown]
    
        // Set up the completion checkboxes which aren't part of standard data.
        // We also make the default value (if you turn on the checkbox) for those
        // numbers to be 1, this will not apply unless checkbox is ticked.
        $default_values['completionacceptenabled']=
            !empty($default_values['completionaccept']) ? 1 : 0;
        if(empty($default_values['completionaccept'])) {
            $default_values['completionaccept']=1;
        }
    }
}