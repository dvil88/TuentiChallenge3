<?php

$states = array(
	'start,#:%,R,state0',
	'state0,0:0,R,state0',
	'state0,1:1,R,state0',
	'state0,#:#,S,state1',
	'state1,#:#,L,state1',
	'state1,$:$,L,state1',
	'state1,1:0,R,state2',
	'state1,0:1,L,state1',
	'state2,1:1,R,state2',
	'state2,0:0,R,state2',
	'state2,#:#,L,state3',
	'state2,$:$,L,state3',
	'state3,0:0,L,state3',
	'state3,1:1,R,state4',
	'state3,%:%,R,state5',
	'state4,0:0,R,state4',
	'state4,1:1,R,state4',
	'state5,1:1,R,state5',
	'state5,0:0,R,state5',
	'state5,#:#,S,state6',
	'state5,$:$,S,state6',
	'state4,#:#,S,state7',
	'state4,$:$,S,state7',
	'state7,#:$,R,state8',
	'state7,$:$,R,state9',
	'state8,1:1,R,state8',
	'state8,0:0,R,state8',
	'state8,#:#,R,state8',
	'state8,_:_,L,state10',
	'state10,#:$,L,state11',
	'state11,1:1,L,state11',
	'state11,0:0,L,state11',
	'state11,#:#,L,state11',
	'state11,$:$,R,state9',
	'state9,1:_,R,state12',
	'state9,0:_,R,state13',
	'state9,#:_,R,state14',
	'state9,$:$,S,state15',
	'state12,1:1,R,state12',
	'state12,0:0,R,state12',
	'state12,#:#,R,state12',
	'state12,$:$,R,state12',
	'state12,_:1,L,state16',
	'state16,1:1,L,state16',
	'state16,0:0,L,state16',
	'state16,#:#,L,state16',
	'state16,$:$,L,state16',
	'state16,_:1,R,state9',
	'state13,1:1,R,state13',
	'state13,0:0,R,state13',
	'state13,#:#,R,state13',
	'state13,$:$,R,state13',
	'state13,_:0,L,state17',
	'state17,1:1,L,state17',
	'state17,0:0,L,state17',
	'state17,#:#,L,state17',
	'state17,$:$,L,state17',
	'state17,_:0,R,state9',
	'state14,1:1,R,state14',
	'state14,0:0,R,state14',
	'state14,#:#,R,state14',
	'state14,$:$,R,state14',
	'state14,_:#,L,state18',
	'state18,1:1,L,state18',
	'state18,0:0,L,state18',
	'state18,#:#,L,state18',
	'state18,$:$,L,state18',
	'state18,_:#,R,state9',
	'state15,1:1,R,state15',
	'state15,0:0,R,state15',
	'state15,#:#,R,state15',
	'state15,$:$,R,state15',
	'state15,_:#,L,state19',
	'state19,1:1,L,state19',
	'state19,0:0,L,state19',
	'state19,#:#,L,state19',
	'state19,$:$,L,state20',
	'state20,1:1,L,state20',
	'state20,0:0,L,state20',
	'state20,#:#,L,state20',
	'state20,$:$,S,state1',
	'state6,1:1,R,state6',
	'state6,0:0,R,state6',
	'state6,#:#,R,state6',
	'state6,$:#,R,state6',
	'state6,_:_,L,state21',
	'state21,#:#,L,state22',
	'state22,1:1,L,state22',
	'state22,0:0,L,state22',
	'state22,#:#,L,state23',
	'state22,%:#,S,end',
	'state23,1:1,R,state23',
	'state23,0:0,R,state23',
	'state23,#:#,R,state23',
	'state23,_:_,L,state24',
	'state24,#:#,L,state25',
	'state25,0:0,L,state25',
	'state25,1:1,R,state26',
	'state25,#:#,R,state27',
	'state26,0:0,R,state26',
	'state26,1:1,R,state26',
	'state27,1:1,R,state27',
	'state27,0:0,R,state27',
	'state27,#:_,L,state28',
	'state26,#:#,L,state29',
	'state29,1:0,L,state30',
	'state29,0:1,L,state29',
	'state30,1:1,L,state30',
	'state30,0:0,L,state30',
	'state30,#:#,L,state31',
	'state31,1:0,L,state31',
	'state31,0:1,R,state32',
	'state32,1:1,R,state32',
	'state32,0:0,R,state32',
	'state32,#:#,R,state32',
	'state32,_:_,L,state21',
	'state28,1:_,L,state28',
	'state28,0:_,L,state28',
	'state28,#:#,S,state21',
);

$turing = array();
foreach($states as $s){
	// Turing machine is an automaton, so why not automate the creation of the machine? :)
	$s = str_replace(array(',',':'),' ',$s);
	sscanf($s,'%s %s %s %s %s',$init,$char,$replace,$move,$next);

	$turing[$init][$char] = array('replace'=>$replace,'move'=>$move,'next'=>$next);
}

$lines = file('php://stdin');
while($l = array_shift($lines)){
	$str = trim($l);

	$next = 'start';
	$pos = 0;
	while($next != 'end'){
		// We have to set up the spaces, the tape is infinite but not the string
		$c = '_';
		if(isset($str[$pos])){$c = $str[$pos];}
		$s = $turing[$next][$c];
		$str[$pos] = $s['replace'];

		if($s['move'] == 'R'){$pos++;}
		if($s['move'] == 'L'){$pos--;}
		$next = $s['next'];

	}
	$str = str_replace('_','',$str);
	echo $str.PHP_EOL;
}

?>