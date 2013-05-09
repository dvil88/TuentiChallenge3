#!/usr/bin/php
<?php
/*
	Solution md5
	d19cf4432c5c2688c6fa21acb55f6f54
*/

$lines = file('php://stdin');
$testCases = trim($lines[0]);

$result0 = '/dev/shm/result0';
$result1 = '/dev/shm/result1';
$result2 = '/dev/shm/result2';
$result3 = '/dev/shm/result3';

$result = '/dev/shm/result';
$integersResult = '/dev/shm/integersResult';



if(!file_exists($result0) || !file_exists($result1) || !file_exists($result2) || !file_exists($result3)){
	$file = 'integers';

	$fp = fopen($file,'rb');
	$stats = fstat($fp);
	$size = $stats['size'];
	$size += ($size%16);
	$part = ceil($size/4);

	//thread 0
	$pid = pcntl_fork();
	if($pid == 0){
		$wFp = fopen($result0,'wb');
		if(fseek($fp,0,SEEK_SET) == 0){
			$readBytes = 0;
			while($readBytes < ($part+100) && ($l = fread($fp,4))){
				if(feof($fp)){break;}
				$point = unpack('I',$l);
				if(fseek($wFp,$point[1],SEEK_SET) == 0){
					fwrite($wFp,1);
				}
				$readBytes += 4;
			}
		}
		fclose($wFp);
		exit;
	}

	//hilo 1
	$pid = pcntl_fork();
	if($pid == 0){
		$wFp = fopen($result1,'wb');
		if(fseek($fp,$part,SEEK_SET) == 0){
			$readBytes = 0;
			while($readBytes < ($part+100) && ($l = fread($fp,4))){
				if(feof($fp)){break;}
				$point = unpack('I',$l);
				if(fseek($wFp,$point[1],SEEK_SET) == 0){
					fwrite($wFp,1);
				}
				$readBytes+=4;
			}
		}
		fclose($wFp);
		exit;
	}

	//hilo 2
	$pid = pcntl_fork();
	if($pid == 0){
		$wFp = fopen($result2,'wb');
		if(fseek($fp,$part*2,SEEK_SET) == 0){
			$readBytes = 0;
			while($readBytes < ($part+100) && ($l = fread($fp,4))){
				if(feof($fp)){break;}
				$point = unpack('I',$l);
				if(fseek($wFp,$point[1],SEEK_SET) == 0){
					fwrite($wFp,1);
				}
				$readBytes += 4;
			}
		}
		fclose($wFp);
		exit;
	}

	//hilo 3
	$pid = pcntl_fork();
	if($pid == 0){
		echo 'Inicio hilo 3'.PHP_EOL;
		$wFp = fopen('/dev/shm/result3','wb');
		if(fseek($fp,$part*3,SEEK_SET) == 0){
			$readBytes = 0;
			while($readBytes < ($part+100) && ($l = fread($fp,4))){
				if(feof($fp)){break;}
				$point = unpack('I',$l);
				if(fseek($wFp,$point[1],SEEK_SET) == 0){
					fwrite($wFp,1);
				}
				$readBytes += 4;
			}
		}
		fclose($wFp);
		echo 'Salir hilo 3'.PHP_EOL;
		exit;
	}

	pcntl_waitpid(0,$status);
	
	fclose($fp);
}

//Now it's time to process the files secuentaly, it's faster than using threads due to the blocks
if(!file_exists($result)){
	//Copy of the first part to the result file, it has to proccess 3 files instead of 4
	copy($result0,$result);
	$fpResult = fopen($result,'cb');

	//Second part of the file
	$fp = fopen($result1,'rb');
	while($b = fread($fp,1)){
		if($b != 1){continue;}

		$pos = ftell($fp)-1;
		fseek($fpResult,$pos,SEEK_SET);

		fwrite($fpResult,1);
	}
	fclose($fp);

	//Third part of the file
	$fp = fopen($result2,'rb');
	while($b = fread($fp,1)){
		if($b != 1){continue;}

		$pos = ftell($fp)-1;
		fseek($fpResult,$pos,SEEK_SET);

		fwrite($fpResult,1);
	}
	fclose($fp);


	//Fourth part of the file
	$fp = fopen($result3,'rb');
	while($b = fread($fp,1)){
		if($b != 1){continue;}

		$pos = ftell($fp)-1;
		fseek($fpResult,$pos,SEEK_SET);

		fwrite($fpResult,1);
	}
	fclose($fp);

	pcntl_waitpid(0,$status);
	fclose($fpResult);
}

if(!file_exists($integersResult)){
	$integers = array();
	$fp = fopen($result,'rb');
	while($n = fread($fp,1)){
		if($n == 1){continue;}
		$pos = ftell($fp)-1;
		$integers[] = $pos;
	}

	fclose($fp);
	file_put_contents($integersResult,implode('#',$integers));
}

$integers = file_get_contents($integersResult);
$integers = explode('#',$integers);

for($i=1;$i<=$testCases;$i++){
	$num = trim($lines[$i]);
	echo $integers[$num-1].PHP_EOL;
}
?>
