#!/usr/bin/php
<?php

$lines = file('php://stdin');
$testCases = trim($lines[0]);

$i = 1;
for($case=1;$case<=$testCases;$case++){
	sscanf(trim($lines[$i++]),'%d %d',$numbers,$studyCases);

	$numberList = explode(' ',trim($lines[$i++]));

	echo 'Test case #'.$case.PHP_EOL;
	for($j=0;$j<$studyCases;$j++){
		sscanf(trim($lines[$i++]),'%d %d',$start,$end);

		$n = $numberList;
		$studyNumbers = array_splice($n,$start-1,($end-$start)+1);

		$reordered = array();
		foreach($studyNumbers as $num){
			if(!isset($reordered[$num])){$reordered[$num] = 0;}
			$reordered[$num] += 1;
		}
		rsort($reordered);
		echo $reordered[0].PHP_EOL;
	}
}
?>