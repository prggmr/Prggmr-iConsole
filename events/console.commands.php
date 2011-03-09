<?php

/**
 * Iconsole Prggmr Interactive Console
 *
 * @author Nickolas Whiting
 * @date February 17th, 2011
 */

/**
 * User Login Prompt Event
 */
\prggmr::listen('console_feedback_command', function($event, $command) {

    if (!method_exists($event, $command)) {
        $event->output("Invalid command recieved!\n");
    } else {
        $event->{$command}();
    }

    $event->command();
});

\prggmr::listen('console_feedback_command_test_command', function($event, $command) {
    $event->output("You have just executed me with the input of : $command");
});
