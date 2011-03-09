<?php

/**
 * Subscription which handles the logging in of a user, this is done
 * outside of the iconsole\User object as we only prompt the user
 * for credentials from within the object and then hand the app logic
 * for producing the validated access to subscriptions if we needed
 * to ever expand this logic.
 *
 * @param  object  $event  iconsole\User
 * @param  string  $user  Username given to use as the login username
 *
 * @return  void
 */
\prggmr::listen('console_feedback_user_login', function($event, $user) {
    // We use SQLite database to store user logins
    // validate this user exists
    $query = $event->_db->prepare('SELECT pass FROM accounts WHERE name = ? LIMIT 1');
    $query->execute(array($user));
    $result = $query->fetchAll();
    // Set the username in the event object for later uses
    $event->_user = $user;
    if (count($result) == 0) {
        /**
         * user is non-existant set the event as inactive
         * and bubble the login_failure event.
         */
        $event->setState(\iconsole\User::STATE_USER_INACTIVE);
        $event->user_login_failure();
    } else {
        /**
         * User account exists we now pass the password into the
         * user event object and bubble the validate event.
         */
        $event->_pass = $result[0]['pass'];
        $event->user_login_validate();
    }
});


/**
 * Subscription which handles the authentication of a user's password.
 *
 * @param  object  $event  iconsole\User
 * @param  string  $password  Password given from the command prompt
 *
 * @return  void
 */
\prggmr::listen('console_feedback_user_login_validate', function($event, $password){
    if (sha1($password) === $event->_pass) {
        /**
         * Bubble the login success
         */
        $event->user_login_success();
    } else {
        /**
         * Bubble the login failure
         */
        $event->user_login_failure();
    }
});


/**
 * Subscription for when a user fails to authenticate themeselves,
 * this subscription will keep track of the number of times the user fails
 * and once they reach a set limit drop their console.
 *
 * @param  object  $event  iconsole\User
 *
 * @return  void
 */
\prggmr::listen('user_login_failure', function($event){
    if ($event->_attempts >= 4) {
        die();
    }
    $event->_attempts++;
    $event->user_login();
});


/**
 * Subscription for when a user successfully authenticates themeselves.
 * Here we will set the user event as active to indicate we have a active
 * account, write the users information to the persisantance file and display
 * the welcome message.
 *
 * @param  object  $event  iconsole\User
 *
 * @return  void
 */
\prggmr::listen('user_login_success', function($event){
    $event->setState(\iconsole\User::STATE_USER_ACTIVE);
    file_put_contents(\iconsole\User::PERSISTANT_FILE, $event->_user);
    $event->output(sprintf(
        "\nWelcome to Prggmr Iconsole %s\n
        ---------------------\nEnjoy your visit, and don't abuse the system!
        \n-------------------------\n\n",
        $event->_user
    ));
});