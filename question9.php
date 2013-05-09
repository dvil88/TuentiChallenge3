#!/usr/bin/php
<?php
/*
	Solution md5
	bf8959b4f18540a66a8c088ff4322ccb
*/

$lines = file('php://stdin');
$testCases = trim($lines[0]);

function resolveGame($width,$length,$warriors,$mana,$cremPrice,$trainPrice){
	$mana -= $warriors * $trainPrice;

	$limit = $width * $length;
	$creepers = 0;
	$seconds = -1;
	while(1){
		$seconds++;
		$creepers += $width;
		if($creepers > $limit){/* KABOOM! */break;}
		$creepers -= $warriors;

		if($creepers < 0){$creepers = 0;}
		if(($creepers + $width) > ($limit) && $mana >= $cremPrice){
			// Oops I think I burned my food again :(
			$mana -= $cremPrice;
			$creepers = 0;
			continue;
		}
	}

	return $seconds;
}

for($case=1;$case<=$testCases;$case++){
	sscanf(trim($lines[$case]),'%d %d %d %d %d',$width,$length,$trainPrice,$cremPrice,$mana);

	$warriors = floor($mana/$trainPrice);
	if($warriors >= $width){
		// They shall not pass!
		echo '-1'.PHP_EOL;
		continue;
	}

	$maxSeconds = resolveGame($width,$length,$warriors,$mana,$cremPrice,$trainPrice);
	while($warriors--){
		$seconds = resolveGame($width,$length,$warriors,$mana,$cremPrice,$trainPrice);
		if($seconds > $maxSeconds){$maxSeconds = $seconds;}
	}
	echo $maxSeconds.PHP_EOL;
}
?>