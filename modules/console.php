<?php
namespace iconsole;

/**
 * Iconsole Prggmr Interactive Console
 *
 * @author Nickolas Whiting
 * @date February 17th, 2011
 */

/**
 * The console event allows for a event based interactive
 * console using php code, the class provides methods
 * for printing console strings and receiving feedback
 * using events, the class does not provide the actual
 * data but an interface for subscribing to the common events
 */

class Console extends \prggmr\Event
{
    public $_linkbreak = "\n";
    /**
     * Dumps a string to the console.
     *
     * @event  console_output  $string|$color
     * @param  string  $string  String to output
     *
     * @return  void
     */
    public function output($string, $options = array())
    {
        $defaults = array('lb' => "\n", 'color' => null);
        $options += $defaults;
        $this->setResultsStackable(true);
        fwrite(STDOUT, implode(LINE_BREAK, $this->console_output(array($string, $options['color']), array('object' => true))->getResults()));
        $this->clearResults();
    }

    /**
     * Provides a method to ask and receieve feedback via the console.
     *
     * The feedback generates the *console_feedback_title* event when received.
     *
     * @event  console_feedback_{title}
     * @param  string  $title  Title of feedback expected.
     * @param  string  $feedback  Question to ask.
     * @param  array  $options  Options to pass to output method
     *
     * @return  void
     */
    public function feedback($title, $feedback, $options = array())
    {
        $defaults = array('block' => false);
        $options += $defaults;
        $this->output($feedback, $options);
        if ($options['block']) {
            stream_set_blocking(STDOUT, 0);
        }
        $input = trim(fgets(STDIN));
        if ($options['block']) {
            stream_set_blocking(STDOUT, 1);
        }
        $this->setListener("console_feedback_$title");
        $this->trigger(array($input));
        return true;
    }
}