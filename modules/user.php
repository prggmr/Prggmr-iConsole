<?php
namespace iconsole;

/**
 * Iconsole Prggmr Interactive Console
 *
 * @author Nickolas Whiting
 * @date February 17th, 2011
 */

/**
 * The user object represents an actual user that exists on in the database.
 * Methods are provided for logging in the user and dispatching
 * events based on that user.
 */

class User extends \iconsole\Console
{
    // User account is active
    const STATE_USER_ACTIVE = 200;

    // User account is inactive
    const STATE_USER_INACTIVE = 201;

    // File containing the user for persistant activity
    const PERSISTANT_FILE = '/tmp/upcf.tmp';

    // Timespan in which the user remains logged.
    // default : 5 Minutes
    const TIMESPAN = 300;

    // username of current user
    public $_name = null;

    // password of current user
    public $_pass = null;

    // number of failed login attempts
    public $_attempts = 0;

    // user sqlite connection
    public $_db = null;

    // Constructs the user object dispatching the user login events
    public function __construct()
    {
        $this->_db = new \PDO('sqlite:'.PRGGMR_LIBRARY_PATH.'../../../databases/users.sqlite');
        if (file_exists(self::PERSISTANT_FILE)) {
            $user = trim(file_get_contents(self::PERSISTANT_FILE));
            $this->console_feedback_user_login(array($user));
        } else {
            $this->user_login();
        }
    }

    // dispatch the user_login feedback event
    public function user_login()
    {
        $this->feedback('user_login', "Enter your username : ", array('lb' => null));
    }

    // dispatch the user_login_validate event
    public function user_login_validate()
    {
        $this->feedback('user_login_validate', "Password : ", array('lb' => null));
    }
}