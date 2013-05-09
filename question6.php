<?php
/*
	Solution md5
	8e5d1eae20907eda8e94fcffa6662881
*/
function getNextMoves($coords,$prevCoords,$time,$visited){
	global $cave;
	global $minTime;
	global $startTime;
	global $speed;

	if($minTime > 0 && $time > $minTime){
		//There is already a faster solution, nothing to do here!
		return;
	}

	list($x,$y) = explode('#',$coords);
	$actualCoords = $x.'#'.$y;

	if($cave[$x][$y] == 'O'){
		//Game resolved, just put my name in the 1st position
		$minTime = round($time);
		return;
	}

	//x-1,y
	if(isset($cave[$x-1][$y]) && $cave[$x-1][$y] != '#'){
		$blocks = 0;

		$newX = $x-1;
		while($cave[$newX][$y] != '#'){
			$blocks++;
			$newX--;
			if($cave[$newX+1][$y] == 'O'){break;}
		}
		$newCoords = ($newX+1).'#'.$y;
		if($prevCoords != $newCoords && !isset($visited[$newCoords])){
			$t = $time + $startTime + ($blocks/$speed);
			$v = $visited;
			$v[$newCoords] = '';
			getNextMoves($newCoords,$actualCoords,$t,$v);
		}
	}

	//x,y-1
	if(isset($cave[$x][$y-1]) && $cave[$x][$y-1] != '#'){
		$blocks = 0;

		$newY = $y-1;
		while($cave[$x][$newY] != '#'){
			$blocks++;
			$newY--;
			if($cave[$x][$newY+1] == 'O'){break;}
		}
		$newCoords = $x.'#'.($newY+1);
		if($prevCoords != $newCoords && !isset($visited[$newCoords])){
			$t = $time + $startTime + ($blocks/$speed);
			$v = $visited;
			$v[$newCoords] = '';
			getNextMoves($newCoords,$actualCoords,$t,$v);
		}
	}

	//x+1,y
	if(isset($cave[$x+1][$y]) && $cave[$x+1][$y] != '#'){
		$blocks = 0;

		$newX = $x+1;
		while($cave[$newX][$y] != '#'){
			$blocks++;
			$newX++;
			if($cave[$newX-1][$y] == 'O'){break;}
		}
		$newCoords = ($newX-1).'#'.$y;
		if($prevCoords != $newCoords && !isset($visited[$newCoords])){
			$t = $time + $startTime + ($blocks/$speed);
			$v = $visited;
			$v[$newCoords] = '';
			getNextMoves($newCoords,$actualCoords,$t,$v);
		}
	}
	
	//x,y+1
	if(isset($cave[$x][$y+1]) && $cave[$x][$y+1] != '#'){
		$blocks = 0;

		$newY = $y+1;
		while($cave[$x][$newY] != '#'){
			$blocks++;
			$newY++;
			if($cave[$x][$newY-1] == 'O'){break;}
		}
		$newCoords = $x.'#'.($newY-1);
		if($prevCoords != $newCoords && !isset($visited[$newCoords])){
			$t = $time + $startTime + ($blocks/$speed);
			$v = $visited;
			$v[$newCoords] = '';
			getNextMoves($newCoords,$actualCoords,$t,$v);
		}
	}

}


$lines = file('php://stdin');

$testCases = trim($lines[0]);

$i=1;
for($case=0;$case<$testCases;$case++){
	$start = '';
	$end = '';
	$minTime = 0;
	$cave = array();

	$caveInfo = trim($lines[$i++]);
	sscanf($caveInfo,'%d %d %d %d',$width,$height,$speed,$startTime);

	for($j=0;$j<$height;$j++){
		$caveLine = trim($lines[$i++]);
		$caveLine = preg_split('/(?<!^)(?!$)/u',$caveLine);

		for($k=0;$k<$width;$k++){
			$cave[$k][$j] = $caveLine[$k];
			if($caveLine[$k] == 'X'){$start = $k.'#'.$j;}
			elseif($caveLine[$k] == 'O'){$end = $k.'#'.$j;}
		}
	}
	getNextMoves($start,$start,0,array($start=>''));

	echo $minTime.PHP_EOL;
}
?>