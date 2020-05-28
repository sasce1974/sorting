<?php
include "../class/Sort.php";
include "../errorHandle.php";


try {
    $test = new Sort(array('comma.txt', 'invalid.txt', 'pipe.txt')); // invalid files are ignored
}catch (Exception $e){
    handleExceptions($e);
    exit();
}
try {
    $test->sortBy(1);
    $test->createTable();

    $test->sortBy(9); // out of range is ignored by the method
    $test->createTable();

    //$test->sortBy('a'); // non number throws exception
    $test->createTable();

    print "<pre>";
    print $test->outputToBrowser();
    print "</pre>";

}catch (Exception $e){
    handleExceptions($e);
}