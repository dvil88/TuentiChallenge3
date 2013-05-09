<?php

// Create images from the video frames
chdir('./challenge17');
shell_exec('mplayer -vo jpeg:quality=50 video.avi ');

$data = '';
$imageFrames = glob('*.jpg');
foreach($imageFrames as $image){
	$im = imagecreatefromjpeg($image);
	$bit = imagecolorat($im,413,348);

	if($bit == 13088512){$data .= 1;}
	else{$data .= 0;}

	imagedestroy($im);
}

// Get ascii string from binary data
$httpPetition = '';
$data = str_split($data,8);
foreach($data as $char){
	$httpPetition .= chr(bindec($char));
}

// Parse the HTTP request
preg_match('/Host: ([a-z\.]+).*?Cookie: ([a-z=]+)/msi',$httpPetition,$m);

// Make the HTTP request with adminsession cookie
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$m[1]);
curl_setopt($ch,CURLOPT_COOKIE,$m[2]);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$result = curl_exec($ch);

// And here is 
preg_match('/<pre>([^<]+)<\/pre>/',$result,$m);
echo 'Challenge 17:'.PHP_EOL.'-----------------------------------------------'.PHP_EOL.$m[1].PHP_EOL;
?>