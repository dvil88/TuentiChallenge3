#!/usr/bin/php
<?php
function processWord($word,$s = false){
	$breaks = array();
	$nextWord = array(array_shift($word));
	$breaks[] = $nextWord;

	while($word){
		$u = implode('',$nextWord);
		$size = substr_count($u,'p');
		$nextWord = array_splice($word,0,$size);
		$breaks[] = $nextWord;
	}
	$breaks = array_reverse($breaks);
	$rest = false;
	foreach($breaks as $curr){
		$u = implode('',$curr);
		$fragment = array();
		foreach($curr as $key){
			if(!substr_count($key,'p')){
				$fragment[] = str_split($key);
				continue;
			}
			$key = str_split($key);
			foreach($key as $k=>$z){
				if($z == 'p'){
					$key[$k] = array_shift($rest);
				}
			}
			$fragment[] = $key;
		}
		$rest = $fragment;
	}
	return array_shift($rest);
}

function getHiddenWord(&$hiddenWord,$word1,$word2){
	foreach($word1 as $k => $v){
		if(is_array($v) && is_array($word2[$k])){getHiddenWord($hiddenWord[$k],$word1[$k],$word2[$k]);}
		if((is_array($word1) && $word2[$k] == 'w') || (is_array($word2) && $word1[$k] == 'b')){$hiddenWord[$k] = $word1[$k];}
		if((is_array($word1) && $word2[$k] == 'b') || (is_array($word2) && $word1[$k] == 'w')){$hiddenWord[$k] = $word2[$k];}
	}
}

function tree2string($array = array()){
	$str = '';
	foreach($array as $k=>$v){if(!is_array($v)){$str .= $v;continue;}$str .= 'p';}
	foreach($array as $k=>$v){if(is_array($v)){$str .= tree2string($v);}}
	return $str;
}

function generateAlienMessage(&$im,$arr,$size = 80,$x = 0,$y = 0){
	global $blackColor;

	foreach($arr as $k=>$a){
		$top = $y;
		$left = $x;

		if($k > 1){$top += $size/2;}
		if($k == 0 || $k == 3){$left += $size/2;}

		if($a == 'b'){imagefilledrectangle($im,$left,$top,$left+($size/2),$top+($size/2),$blackColor);}
		if(is_array($a)){generateAlienMessage($im,$a,$size/2,$left,$top);}
	}
}

$lines = file('php://stdin');

$testCases = $lines[0];
for($case=1;$case<=$testCases;$case++){
	$secrets = explode(' ',trim($lines[$case]));

	$squares = str_split(substr($secrets[0],1),4);
	$word1 = processWord($squares);

	$squares = str_split(substr($secrets[1],1),4);
	$word2 = processWord($squares);

	$hidden = array();
	getHiddenWord($hidden,$word1,$word2);
	$secretWord = tree2string($hidden);

	$size = 700;
	$im = imagecreate($size,$size);
	$whiteColor = imagecolorallocate($im,255,255,255);
	$blackColor = imagecolorallocate($im,0, 0, 0);
	generateAlienMessage($im,$hidden,$size);
	imagepng($im,getcwd().'/test'.$case.'.png');
	imagedestroy($im);


	$r = shell_exec('zbarimg test'.$case.'.png 2>&1');
	$qr = preg_match('/QR-Code:(?<qr>[^\n]+)/',$r,$m);

	echo $m[1].PHP_EOL;
}
?>