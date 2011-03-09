<?php
namespace iconsole;

/**
 * Iconsole Prggmr Interactive Console
 *
 * @author Nickolas Whiting
 * @date February 17th, 2011
 */

/**
 * The commands object will be the workhorse of our console working
 * as the main interactive object reciving and dispatching commands from the
 * console.
 */

class Commands extends \iconsole\Console
{
    // user object
    public $_user = null;

    /**
     * Constructs the command console
     */
    public function __construct(User $user) {

        $this->_user = $user;
        $this->command();
    }

    /**
     * Command  is used to recieve input from the console as a new "command"
     * which will attempt to trigger a method within itself as the executed
     * command.
     *
     * Demonstrated with the following psuedo
     *
     * $command->command();
     * recieve input : test
     * check for test method in commands object
     * if true
     *      run test method
     * else
     *      dispatch invalid command message
     *
     * rerun command method
     *
     * @return  void
     */
    public function command()
    {
        $this->feedback('command', "Your wish is my command : \n");
    }

    /**
     * A simple example command which is triggered via the "test_command" command
     *
     * This method will execute a "feedback" asking a simple question.
     *
     * @return  void
     */
    public function test_command()
    {
        $this->feedback('test_command', 'What do you want me to do now ?  ');
    }
}