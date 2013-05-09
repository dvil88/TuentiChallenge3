#!/usr/bin/php
<?php
/* 
	Solution md5
	58ed8c3413e0c78535379150a11e58e6
*/
$lines = file('php://stdin');
$i = $cases = 0;
$testCases = $lines[$i++];

$finalValues = array();
while($cases < $testCases){
	$finalValues[$cases] = 0;

	$initialBudget = (int)trim($lines[$i++]);
	$amount = explode(' ',trim($lines[$i++]));

	$initialBudget = $initialBudget/$amount[0];
	foreach($amount as $k=>$a){
		$relativeAmount = $initialBudget * $a;
		if(isset($amount[$k+1]) && $a > $amount[$k+1]){
			$relativeAmount = $initialBudget * $a;
			$initialBudget = isset($amount[$k+1]) ? $relativeAmount/$amount[$k+1] : $relativeAmount;
		}
		if($relativeAmount > $finalValues[$cases]){$finalValues[$cases] = $relativeAmount;}
	}
	$cases++;
}
foreach($finalValues as $val){echo $val.PHP_EOL;}
?>