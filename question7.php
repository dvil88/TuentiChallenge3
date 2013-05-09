#!/usr/bin/php
<?php
/*
	Solution md5
	e06355c83a1342606bf33f8eca82c030
*/

function getWordPoints($coords){
	global $board;
	global $charScore;

	$wordScore = 0;
	$wordBoost = 1;
	foreach($coords as $c=>$v){

		sscanf($c,'%d#%d',$x,$y);
		$char = $board[$x][$y]['char'];
		sscanf($board[$x][$y]['boost'],'%d#%d',$boostType,$boostValue);
		$charBoost = ($boostType == 1) ? $boostValue : 1;
		if($boostType == 2){
			$wordBoost = ($boostValue > $wordBoost) ? $boostValue : $wordBoost;
			$charBoost = 1;
		}
		$wordScore += ($charScore[$char] * $charBoost);
	}

	$wordScore = $wordScore * $wordBoost;
	$wordScore += count($coords);

	return $wordScore;
}

function getMaxPoints($wordCost,$wordPoints,$remaining,$time,&$processed) {
	if(isset($processed[$remaining][$time])){
		return $processed[$remaining][$time];
	}else{
		if($remaining == 0){
			if($wordCost[$remaining] <= $time){
				$processed[$remaining][$time] = $wordPoints[$remaining];
				return $wordPoints[$remaining];
			}else{
				$processed[$remaining][$time] = 0;
				return 0;
			}
		}	
 
		$notSelected = getMaxPoints($wordCost, $wordPoints, $remaining-1, $time,$processed);
		if($wordCost[$remaining] > $time){
			$processed[$remaining][$time] = $notSelected;
			return $notSelected;
		}else {
			$selected = $wordPoints[$remaining] + getMaxPoints($wordCost, $wordPoints, ($remaining-1), ($time - $wordCost[$remaining]),$processed);
			$res = max($selected, $notSelected); 
			$processed[$remaining][$time] = $res;
			return $res;
		}	
	}
}

function moveSmurfToNextPosition($coords,$prevWord,$visited){
	global $board;
	global $words;
	global $wordString;

	sscanf($coords,'%d#%d',$x,$y);

	if(isset($board[$x][$y]) && !isset($visited[$x.'#'.$y])){
		$nextCoords = $x.'#'.$y;
		$nextWord = $prevWord.$board[$x][$y]['char'];

		$v = $visited;
		$v[$coords] = '';
		$points = getWordPoints($v);
		if(isset($words[$nextWord]) && (empty($words[$nextWord]) || $points > $words[$nextWord])){
			$words[$nextWord] = $points;
		}
		if(strpos($wordString,'#'.$nextWord) !== false){getBiggerWord($nextCoords,$nextWord,$v);}
	}
}

function getBiggerWord($coords,$prevWord,$visited){

	sscanf($coords,'%d#%d',$x,$y);

	//x-1,y-1
	moveSmurfToNextPosition(($x-1).'#'.($y-1),$prevWord,$visited);

	//x,y-1
	moveSmurfToNextPosition($x.'#'.($y-1),$prevWord,$visited);

	//x+1,y-1
	moveSmurfToNextPosition(($x+1).'#'.($y-1),$prevWord,$visited);

	//x+1,y
	moveSmurfToNextPosition(($x+1).'#'.$y,$prevWord,$visited);

	//x+1,y+1
	moveSmurfToNextPosition(($x+1).'#'.($y+1),$prevWord,$visited);

	//x,y+1
	moveSmurfToNextPosition($x.'#'.($y+1),$prevWord,$visited);

	//x-1,y+1
	moveSmurfToNextPosition(($x-1).'#'.($y+1),$prevWord,$visited);

	//x-1,y
	moveSmurfToNextPosition(($x-1).'#'.$y,$prevWord,$visited);
}

$lines = file('php://stdin');


$testCases = trim($lines[0]);

$i = 1;
for($cases=0;$cases<$testCases;$cases++){
	$charScore = trim($lines[$i++]);
	$time = trim($lines[$i++]);
	$rows = trim($lines[$i++]);
	$cols = trim($lines[$i++]);
	$initTime = $time+1;

	$letters = array();
	$charScore = json_decode(str_replace('\'','"',$charScore),true);
	ksort($charScore,SORT_STRING);

	$words = array();

	$boardLetters = array();
	$board = array();
	for($j=0;$j<$rows;$j++){
		$colValues = explode(' ',trim($lines[$i++]));
		foreach($colValues as $k=>$v){
			$boardLetters[$v[0]] = $v[0];
			$board[$j][$k]['char'] = $v[0];
			$board[$j][$k]['boost'] = $v[1].'#'.$v[2];
		}
	}

	$wordString = '#';
	$fp = fopen(getcwd().'/boozzle-dict.txt','r');
	while($w = fgets($fp)){
		$w = trim($w);
		$len = strlen($w);
		$wordString .= $w.'#';
		if(($len+1) > $time || $len > ($rows * $cols)){
			// How can we select words with more cost than given time or bigger than the board? No way!
			break;
		}
		$words[$w] = '';
	}
	fclose($fp);

	for($x=0;$x<$cols;$x++){
		for($y=0;$y<$rows;$y++){
			$init = $x.'#'.$y;
			getBiggerWord($init,$board[$x][$y]['char'],array($x.'#'.$y=>''));
		}
	}

	$wordRatio = array();
	$words = array_diff($words,array(''));
	
	// Delete words with least punctuation, sometimes it's better to drop items we don't need when we have better ones
	$newWords = array();
	foreach($words as $w=>$p){
		$newWords[strlen($w)][$w] = $p;
	}

	$cleanWords = array();
	foreach($newWords as $len=>$w){
		$maxWords = floor($time/($len+1));
		arsort($w);
		$valid = array_splice($w,0,$maxWords);
		$cleanWords = array_merge($cleanWords,$valid);
	}
	arsort($cleanWords);
	$words = $cleanWords;

	$wordCost = array();
	$wordPoints = array();
	foreach($words as $w=>$p){
		$wordCost[] = strlen($w)+1;
		$wordPoints[] = $p;
	};

	$processed = array();
	$points = getMaxPoints($wordCost,$wordPoints,sizeof($wordPoints)-1,$time,$processed);
	echo $points.PHP_EOL;
}


?>
