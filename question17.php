#!/usr/bin/php
<?php
$lines = file('php://stdin');

while($num = array_shift($lines)){
	// Factorial numbers? sorry, i'm the Chuck Norris of PHP
	$fact = gmp_fact($num);
	$fact = gmp_strval($fact);

	$f = str_split($fact);
	echo array_sum($f).PHP_EOL;
}
?>