#!/usr/bin/php
<?php
/*
	Solution md5
	1b80a4872fa5e46c68bfac7a6b56492f
*/

$lines = file('php://stdin');

$words = array();
$comments = 0;
foreach($lines as $l){
	if($l[0] == '#'){$comments++;continue;}
	if($comments == 1){$dictionary = trim($l);continue;}
	if($comments == 3){
		$words[] = trim($l);
	}
}

$indexed = array();
$dict = fopen(getcwd().'/dictionaries/'.$dictionary,'r');
while($line = fgets($dict)){
	$l = str_split(trim($line));sort($l);$l = implode('',$l);

	$indexed[md5($l)][] = trim($line);
}
fclose($dict);


$result = '';
foreach($words as $w){
	$s = str_split($w);sort($s);$s = implode('',$s);

	$result .= $w.' -> ';
	if(isset($indexed[md5($s)])){
		foreach($indexed[md5($s)] as $res){
			if($res == $w){continue;}
			$result .= $res.' '; 
		}
	}
	$result = trim($result).PHP_EOL;
}

echo $result;

?>