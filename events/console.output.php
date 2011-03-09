<?php

// Console Output
\prggmr::listen('console_output', function($event, $string){
    return $string;
});