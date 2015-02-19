<!DOCTYPE html>
<html lang="ja">
<head>
	 <meta charset="utf-8">
	 <title>ストップウォッチ</title>
	 
</head>
<body>
	<div id="sw1">
    <button class="startBtn">start</button>
    <button class="stopBtn">stop</button>
    <button class="resetBtn">reset</button>
    <div class="timerText">0</div>
</div>
<div id="sw2">
    <button class="startBtn">start</button>
    <button class="stopBtn">stop</button>
    <button class="resetBtn">reset</button>
    <div class="timerText">0</div>
</div>
<div id="sw3">
    <button class="startBtn">start</button>
    <button class="stopBtn">stop</button>
    <button class="resetBtn">reset</button>
    <div class="timerText">0</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

//クラス作成＆コンストラクタ
var StopWatch = function(_continerId){
    var self = this;
    this.continerSelecter = '#'+_continerId;
    this.startBtnSelecter = '.startBtn';
    this.stopBtnSelecter = '.stopBtn';
    this.resetBtnSelecter = '.resetBtn';
    this.timerTextSelecter = '.timerText';
    this.defaultInterval = 1000;
    this.timerId = null;

    
    // クリックイベント登録
    $(this.continerSelecter+'>'+this.startBtnSelecter).click(function(){
        self.start();
    });
    $(this.continerSelecter+'>'+this.stopBtnSelecter).click(function(){
        self.stop();
    });
    $(this.continerSelecter+'>'+this.resetBtnSelecter).click(function(){
        self.reset();
    });
};
//各function
StopWatch.prototype.start = function(){
    this.run();
};
StopWatch.prototype.stop = function(){
    if(this.timerId !== null){
        clearInterval(this.timerId);
    }
};
StopWatch.prototype.reset = function(){
    $(this.continerSelecter+'>'+this.timerTextSelecter).text('0');
};
StopWatch.prototype.run = function(){
    var self = this;
    this.timerId = setInterval(function(){
        var num = $(self.continerSelecter+'>'+self.timerTextSelecter).text();
        $(self.continerSelecter+'>'+self.timerTextSelecter).text(parseInt(num,10)+1);
    }, this.defaultInterval);
};

// main処理
$(document).ready(function(){
    var sw1 = new StopWatch('sw1');
    var sw2 = new StopWatch('sw2');
    var sw3 = new StopWatch('sw3');
});


</script>
</body>
</html>