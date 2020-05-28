<?php

require_once "class/Sort.php";
include "errorHandle.php";

try {
    $sort = new Sort();
}catch (Exception $e){
    handleExceptions($e);
    exit();
}

try {

    // FIRST TABLE
    // Sorting by gender then by last name, then save the output
    // into 'output' property of the Sort class over the setOutput method.
    // First argument (numbers) in the sortBy() method is the column to be sorted:
    // [0 - Last Name, 1 - First Name, 2 - Gender, 3 - Date of birth, 4 - Favorite color]
    // Second argument in the sortBy() method is optional, if True, sorting is Desc. Default is False
    $sort->sortBy(0);
    $sort->sortBy(2);

    // adding title on top of the table
    $sort->setOutput("gender then lastname ascending");

    // print output of the sorted array in a table like format
    $sort->createTable();


    // SECOND TABLE
    // Sorting by date of birth and append to output
    $sort->sortBy(3);
    $sort->setOutput("dateofbirth ascending");
    $sort->createTable();


    // THIRD TABLE
    // Sorting by last name by descend order and append to output
    $sort->sortBy(0, true);
    $sort->setOutput("lastname descending");
    $sort->createTable();

    // save the output to text file: output.txt and print the returned message
    $message = $sort->outputToFile();
    if (key($message) === 'message') print "<h4>" . $message['message'] . "</h4>";

    // print the output in HTML
    print "<pre>";
    print $sort->outputToBrowser();
    print "</pre>";

    $sort = null;
}catch (Exception $e){
    handleExceptions($e);
}