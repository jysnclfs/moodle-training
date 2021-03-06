<?php

    require_once("../../config.php");
    require_once($CFG->dirroot."/auth/shibboleth/auth.php");

    //initialize variables
    $errormsg = '';

/// Check for timed out sessions
    if (!empty($SESSION->has_timed_out)) {
        $session_has_timed_out = true;
        $SESSION->has_timed_out = false;
    } else {
        $session_has_timed_out = false;
    }


//HTTPS is required in this page when $CFG->loginhttps enabled
$PAGE->https_required();

/// Define variables used in page
    $site = get_site();

    $loginsite = get_string("loginsite");

    $loginurl = (!empty($CFG->alternateloginurl)) ? $CFG->alternateloginurl : '';


    if (!empty($CFG->registerauth) or is_enabled_auth('none') or !empty($CFG->auth_instructions)) {
        $show_instructions = true;
    } else {
        $show_instructions = false;
    }

    // Set SAML domain cookie
    $config = get_config('auth_shibboleth');


    $IdPs = get_idp_list($config->organization_selection);
    if (isset($_POST['idp']) && isset($IdPs[$_POST['idp']])){
        $selectedIdP = $_POST['idp'];
        set_saml_cookie($selectedIdP);

        // Redirect to SessionInitiator with entityID as argument
        if (isset($IdPs[$selectedIdP][1]) && !empty($IdPs[$selectedIdP][1])) {
            // For Shibbolet 1.x Service Providers
            header('Location: '.$IdPs[$selectedIdP][1].'?providerId='. urlencode($selectedIdP) .'&target='. urlencode($CFG->httpswwwroot.'/auth/shibboleth/index.php'));

            // For Shibbolet 2.x Service Providers
            // header('Location: '.$IdPs[$selectedIdP][1].'?entityID='. urlencode($selectedIdP) .'&target='. urlencode($CFG->httpswwwroot.'/auth/shibboleth/index.php'));

        } else {
            // For Shibbolet 1.x Service Providers
            header('Location: /Shibboleth.sso?providerId='. urlencode($selectedIdP) .'&target='. urlencode($CFG->httpswwwroot.'/auth/shibboleth/index.php'));

            // For Shibboleth 2.x Service Providers
            // header('Location: /Shibboleth.sso/DS?entityID='. urlencode($selectedIdP) .'&target='. urlencode($CFG->httpswwwroot.'/auth/shibboleth/index.php'));
        }
    } elseif (isset($_POST['idp']) && !isset($IdPs[$_POST['idp']]))  {
        $errormsg = get_string('auth_shibboleth_errormsg', 'auth_shibboleth');
    }

    $loginsite = get_string("loginsite");

    $PAGE->set_url('/auth/shibboleth/login.php');
    $PAGE->set_context(context_system::instance());
    $PAGE->navbar->add($loginsite);
    $PAGE->set_title("$site->fullname: $loginsite");
    $PAGE->set_heading($site->fullname);

    echo $OUTPUT->header();

    if (isloggedin() and !isguestuser()) {
        // Prevent logging when already logged in, we do not want them to relogin by accident because sesskey would be changed.
        echo $OUTPUT->box_start();
        $params = array('sesskey' => sesskey(), 'loginpage' => 1);
        $logout = new single_button(new moodle_url('/login/logout.php', $params), get_string('logout'), 'post');
        $continue = new single_button(new moodle_url('/'), get_string('cancel'), 'get');
        echo $OUTPUT->confirm(get_string('alreadyloggedin', 'error', fullname($USER)), $logout, $continue);
        echo $OUTPUT->box_end();
    } else {
        include("index_form.html");
    }

    echo $OUTPUT->footer();


