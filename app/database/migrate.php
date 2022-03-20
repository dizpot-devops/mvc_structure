<?php

require __DIR__ . '/../autoload.php';

$shortopts = "";
$options = getopt($shortopts,array("noseed::"));



$seed = true;
if(array_key_exists('noseed',$options)) {
    $seed = false;
}

$migration = new Migration(
    new MySQL(),
    __DIR__ . "/migrations/",
    __DIR__ . "/seed/"
);
//$migration->resetEverything();
//exit;
$migration->migrate();
if(array_key_exists('f',$options)) {
    $file = $options['f'];
    $migration->performSeed($file);
}
else {


}

exit;



//$migration->performSeed(__DIR__ . '/testing/seed_testing.php');