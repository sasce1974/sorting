<?php

set_exception_handler('handleExceptions');

function handleExceptions($e){
    print "There was some error: " . $e->getMessage() .
        " in file " . $e->getFile() . " on line: " . $e->getLine();
}