<?php

/**
 * User Login Prompt Event
 */
\prggmr::listen('console_feedback_user_login', function($event, $user) {
    $query = $event->_db->prepare('SELECT pass FROM accounts WHERE name = ? LIMIT 1');
    $query->execute(array($user));
    $result = $query->fetchAll();
    $event->_user = $user;
    if (count($result) == 0) {
        $event->setState(\iconsole\User::STATE_USER_INACTIVE);
        $event->user_login_failure();
    } else {
        $event->_pass = $result[0]['pass'];
        $event->user_login_validate();
    }
});


/**
 * Prompts for password once successful name is found
 */
\prggmr::listen('console_feedback_user_login_validate', function($event, $password){
    if (sha1($password) === $event->_pass) {
        $event->user_login_success();
    } else {
        $event->user_login_failure();
    }
});


/**
 * A user failed a login attempt
 */
\prggmr::listen('user_login_failure', function($event){
    if ($event->_attempts >= 4) {
        die();
    }
    $event->_attempts++;
    $event->user_login();
});


/**
 * A Successful login
 */
\prggmr::listen('user_login_success', function($event){
    $event->setState(\iconsole\User::STATE_USER_ACTIVE);
    file_put_contents(\iconsole\User::PERSISTANT_FILE, $event->_user);
    $event->output(sprintf("\nWelcome to Prggmr Iconsole %s\n---------------------\nEnjoy your visit, and don't abuse the system!\n-------------------------\n\n", $event->_user));
});