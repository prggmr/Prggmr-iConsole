<?php
/**
 * Iconsole Prggmr Interactive Console
 *
 * @author Nickolas Whiting
 * @date February 17th, 2011
 */

/**
 * Subscription that is bubbled and acts as the command interrupter
 * suplement to the iconsole\Commands object.
 *
 * This subscription will check for the given command in the commands
 * object and run if it exists, once finished ( we dont konw when )
 * it will trigger the command action asking for another command to
 * be executed.
 *
 * @param  object  $event  iconsole\Commands
 * @param  string  $command  Command received from the command prompt
 *
 * @return  void
 */
\prggmr::listen('console_feedback_command', function($event, $command) {

    if (!method_exists($event, $command)) {
        $event->output("Invalid command recieved!\n");
    } else {
        $event->{$command}();
    }

    $event->command();
});


/**
 * This subscription is the response subscriber that will await for the
 * response from the "test_command" feedback publisher.
 *
 * This is not to be confused as the subscription that is bubbled when
 * the "test_command" is executed rather it is the subscription to the
 * "feedback" publisher that is bubbled once the "test_command" is
 * bubbled via the iconsole\Commands::test_command method.
 *
 * @param  object  $event  iconsole\Commands
 * @param  string  $command  String recieved from the command prompt
 *
 * @return  void
 */
\prggmr::listen('console_feedback_command_test_command', function($event, $command) {
    $event->output("You have just executed me with the input of : $command");
});
