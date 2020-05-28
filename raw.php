<?php
require_once "class/Sort.php";

if($sort = new Sort()) {
    // First sorting - by gender then by last name, then save the output
    // into 'output' property of the Sort class
    $sort->sortBy(0);
    $sort->sortBy(2);
    $sort->setOutput("gender then lastname ascending \n");
    $sort->createTable();

    // sorting by date of birth and append to output
    $sort->sortBy(3);
    $sort->setOutput("dateofbirth ascending \n");
    $sort->createTable();

    // sorting by last name by descend order and append to output
    $sort->sortBy(0, true);
    $sort->setOutput("lastname descending \n");
    $sort->createTable();

    // save the output to text file: output.txt and print the returned message
    $message = $sort->outputToFile();
    if(key($message) === 'message'){
        print "Data successfully printed into source/output.txt \n\n";
    }elseif (key($message) === 'error'){
        print $message;
    }

    print $sort->getOutput();

    $sort = null;
}
?>

