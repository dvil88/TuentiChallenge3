<?php
/*
	Solution md5
	0cd328164f5ad4d7a6f38330e8a5bdc8
*/

function getNexPosibilities($coords,&$point,$visited,$time,$points){
	global $grid;
	global $gemValues;
	global $gamePoints;

	list($x,$y) = explode('#',$coords);
	if($time == 0){$gamePoints[$points] = '';return;}

	$time--;

	//x-1,y
	if(isset($grid[$x-1][$y]) && !isset($visited[($x-1).'#'.$y])){
		$p = $points;
		if(isset($gemValues[$x-1][$y])){$p = $points + $gemValues[$x-1][$y];}
		$v = $visited;
		$v[($x-1).'#'.$y] = '';
		$point[($x-1).'#'.$y] = array();
		getNexPosibilities(($x-1).'#'.$y,$point[($x-1).'#'.$y],$v,$time,$p);
	}
	//x,y+1
	if(isset($grid[$x][$y+1]) && !isset($visited[$x.'#'.($y+1)])){
		$p = $points;
		if(isset($gemValues[$x][$y+1])){$p = $points + $gemValues[$x][$y+1];}
		$v = $visited;
		$v[$x.'#'.($y+1)] = '';
		$point[$x.'#'.($y+1)] = array();
		getNexPosibilities($x.'#'.($y+1),$point[$x.'#'.($y+1)],$v,$time,$p);
	}
	//x+1,y
	if(isset($grid[$x+1][$y]) && !isset($visited[($x+1).'#'.$y])){
		$p = $points;
		if(isset($gemValues[$x+1][$y])){$p = $points + $gemValues[$x+1][$y];}
		$v = $visited;
		$v[($x+1).'#'.$y] = '';
		$point[($x+1).'#'.$y] = array();
		getNexPosibilities(($x+1).'#'.$y,$point[($x+1).'#'.$y],$v,$time,$p);
	}
	//x,y-1
	if(isset($grid[$x][$y-1]) && !isset($visited[$x.'#'.($y-1)])){
		$p = $points;
		if(isset($gemValues[$x][$y-1])){$p = $points + $gemValues[$x][$y-1];}
		$v = $visited;
		$v[$x.'#'.($y-1)] = '';
		$point[$x.'#'.($y-1)] = array();
		getNexPosibilities($x.'#'.($y-1),$point[$x.'#'.($y-1)],$v,$time,$p);
	}
}

$lines = file('php://stdin');
$testCases = trim($lines[0]);

$i=1;
for($cases=1;$cases<=$testCases;$cases++){
	$grid = array();
	$visited = array();
	$gamePoints = array();

	$gridSize = trim($lines[$i++]);
	$init = trim($lines[$i++]);
	$time = trim($lines[$i++]);
	$gems = trim($lines[$i++]);
	$values = trim($lines[$i++]);

	//Create array of gems
	$gemValues = array();
	$gems = explode('#',$values);
	foreach($gems as $val){
		list($x,$y,$v) = explode(',',$val);
		$gemValues[$x][$y] = $v;
	}

	//Create grid
	sscanf($gridSize,'%d,%d',$x,$y);
	for($j=0;$j<$x;$j++){
		for($k=0;$k<$y;$k++){
			$grid[$j][$k] = isset($gemValues[$j][$k]) ? $gemValues[$j][$k] : 0;
		}
	}

	//Set init point
	sscanf($init,'%d,%d',$x,$y);
	$array = array($x.'#'.$y=>'');
	$visited[$x.'#'.$y] = '';
	getNexPosibilities($x.'#'.$y,$array[$x.'#'.$y],$visited,$time,0);
	krsort($gamePoints);
	$max = array_keys($gamePoints);

	echo $max[0].PHP_EOL;
}
?>