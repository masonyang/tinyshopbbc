#!/usr/bin/env php
<?php
if ($argc < 2) {
    die("Usage: {$argv[0]} {Job} [--option=value ...]" . PHP_EOL);
}

define("APP_ROOT",dirname(__file__).'/../');

include(APP_ROOT."framework/tiny.php");

Tiny::registerAutoLoad();

try {

	$jobName = $argv[1];

	$className = new $jobName();

	if($className instanceof syncdataJob){
		$className->run();
		$className->outMsg();
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

// branch to head:
// /usr/local/Cellar/php54/5.4.45_3/bin/php job.php dispatchJob --synctype=branchtohead
// /usr/local/Cellar/php54/5.4.45_3/bin/php job.php syncdata_distributorInfoJob --synctype=branchtohead