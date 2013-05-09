#!/usr/bin/php
<?php
/*
	Solution md5
	868745d12e928cc54841093a4b9ca05c
*/
$lines = file('php://stdin');

$scripts = trim($lines[0]);
$prev = '';
$results = array();
for($i=1;$i<=$scripts;$i++){
	$detected = false;
	$results[$i] = '';

	$script = trim($lines[$i]);

	//detect valid, more than a posibility
	$s = preg_split('/([<>]+)/',$script,-1,PREG_SPLIT_DELIM_CAPTURE);

	foreach($s as $k=>$v){
		if($v == '<' || $v == '>'){continue;}
		if(preg_match_all('/[\.]+/',$v) > 1){
			if((isset( $s[$k-1]) && $s[$k-1] == '>')|| (isset($s[$k+1]) && $s[$k+1] == '<')){
				$results[$i] = 'valid';$detected = true;
			}
		}
	}

	//detect invalid
	preg_match_all('/([\.<>]{1})([^\.<>]+)/msu',$script,$m);
	$total = count($m[0]);
	foreach($m[2] as $k=>$v){
		for($j = $k+1;$j<$total;$j++){
			if($m[2][$j] == $v && ($m[1][$k] != '.' && $m[1][$j] == '.')){
				$results[$i] = 'invalid';
				$detected = true;
			}
			if($m[2][$j] == $v && ($m[1][$k] == '.' && $m[1][$j] != '.')){
				$results[$i] = 'invalid';
				$detected = true;
			}
		}
	}

	if(!$detected){
		$s = preg_split('/([\.<>]+)/',$script,-1,PREG_SPLIT_DELIM_CAPTURE);
		$s = array_diff($s,array(''));
		$total = count($s);
		$cleanScript = array();
		for($j=1;$j<$total;$j+=2){
			$cleanScript[] = $s[$j].$s[$j+1];
		}

		$temp = '';
		foreach($cleanScript as $k=>$scene){
			if($scene[0] == '<'){
				$temp = $cleanScript[$k-1];
				$cleanScript[$k-1] = $scene;
				$cleanScript[$k] = $temp;
			}
		}

		foreach($cleanScript as $k=>$scene){
			if(isset($cleanScript[$k+1]) && substr($cleanScript[$k],1) == substr($cleanScript[$k+1],1)){
				unset($cleanScript[$k]);
			}
		}
		
		$results[$i] = ltrim(str_replace(array('<','>','.'),',',implode('', $cleanScript)),',');
	}
}

foreach($results as $r){
	echo $r.PHP_EOL;
}
?>