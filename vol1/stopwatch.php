<!DOCTYPE html>
<html lang="ja">
<head>
	 <meta charset="utf-8">
	 <title>ストップウォッチ</title>
	 <style>
	 	#stop,#reset{
	 		display:none;
	 	}
	 </style>
</head>
<body>
	<h1>ストップウォッチ</h1>
	<div id="sec" style="font-size:128px">0.00</div>
	<input type="button" value="start!" id="run">
	<input type="button" value="stop!" id="stop">
	<input type="button" value="reset!" id="reset">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

$(function(){
var startTime,
	timerId,
	running = false,
	stopTime;

$('#run').click(function(){
	run();
	clickRun();
});

$('#stop').click(function(){
	stop();
	clickstop();
});

$('#reset').click(function(){
	reset();
	clickReset();
});

function run(){

	if(running) return;

	running = true;

	if (stopTime){
		startTime = startTime + (new Date()).getTime() - stopTime;
	}

	if(!startTime){
		startTime = (new Date()).getTime();
	}
	timer();
}

function timer(){
	document.getElementById('sec').innerHTML = (((new Date()).getTime() - startTime) / 1000).toFixed(2);
	timerId = setTimeout(function(){
		timer();
	},100);
}

function stop(){
	if(!running) return false;
	running = false;
	clearTimeout(timerId);
	stopTime = (new Date()).getTime();
}

function reset(){
	if(running) return;
	startTime = undefined;
	document.getElementById('sec').innerHTML = '0.00';
}

function clickRun(){
	$('#run').css('display','none');
	$('#stop').show();
	$('#reset').show();
}
function clickstop(){
	$('#run').show();
	$('#stop').css('display','none');
}
function clickReset(){
	$('#run').show();
	$('#stop').css('display','none');
	$('#reset').css('display','none');
}


});


</script>
</body>
</html>