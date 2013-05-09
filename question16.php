#!/usr/bin/php
<?php

$lines = file('php://stdin');
while($l = array_shift($lines)){
	$str = trim($l);

	$str = explode('#',$str);
	$str = array_values(array_diff($str, array('')));
	$len = strlen($str[0]);

	foreach($str as $k => $n){
		$str[$k] = bindec($n);
	}

	$mult = array_shift($str);
	$sum = array_sum($str);

	$result = $mult * $sum;
	$result = decbin($result);
	$resLen = strlen($result);

	echo '#';
	if($resLen<$len){
		for($i=0;$i<($len-$resLen);$i++){echo '0';}
	}
	echo $result.'#'.PHP_EOL;
}
?>