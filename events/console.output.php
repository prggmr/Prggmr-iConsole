<?php
/**
 * Iconsole Prggmr Interactive Console
 *
 * @author Nickolas Whiting
 * @date February 17th, 2011
 */

/**
 * Subscription that is bubbled when data is being requested for output
 * this acts as a filter allowing the data to be tampered with, if so choosen,
 * before dumping it to the console.
 *
 * @param  object  $event  iconsole\Commands
 * @param  string  $string  String that will be printed to the console.
 *
 * @return  string  String to be outputted to the console
 */
\prggmr::listen('console_output', function($event, $string){
    return $string;
});