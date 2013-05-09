#!/usr/bin/php
<?php

function moveLinkToNextPosition($from,$to,$visited,$energy){
	global $movements;

	$visited[$from] = $energy;
	$energy += ($energy * ($movements[$from][$to]/100));
	if(isset($visited[$to])){
		if($energy > $visited[$to]){
			// Infinite energy!!
			return -1;
		}
		return 0;
	}

	if(!isset($movements[$to])){return 0;}
	foreach($movements[$to] as $points=>$v){
		$r = moveLinkToNextPosition($to,$points,$visited,$energy);
		if($r == -1){return -1;}
	}
}


$lines = file('php://stdin');

$testCases = trim($lines[0]);

$i=1;
for($case=0;$case<$testCases;$case++){

	$locations = trim($lines[$i++]);
	$moves = trim($lines[$i++]);

	$energies = array();
	$movements = array();
	for($j=0;$j<$moves;$j++){
		sscanf(trim($lines[$i++]),'%d %d %d',$start,$end,$energy);


		if(isset($movements[$start]) && $energy != 0){
			if(!isset($energies[$energy])){
				$energies[$energy] = 0;
			}
			$energies[$energy] += $energy;
		}
		$movements[$start][$end] = $energy;
	}

	if(array_sum($energies) == 0){
		// The amount of energies is complemented between them so statistically it's not possible to get a cycle
		echo 'False'.PHP_EOL;
		continue;
	}

	$bug = 'False';
	for($from=0;$from<$locations;$from++){
		$energy = 100;
		if(!isset($movements[$from])){continue;}
		foreach($movements[$from] as $to=>$v){
			if(!isset($movements[$from][$to])){continue;}
			$visited = array();
			$r = moveLinkToNextPosition($from,$to,$visited,$energy);
			if($r == '-1'){$bug = 'True';break;}
		}
		if($bug == 'True'){break;}
	}

	echo $bug.PHP_EOL;
}
?>