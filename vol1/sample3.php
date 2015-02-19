<!DOCTYPE html>
<html lang="ja">
<head>
<style>
.task_input,.task_input2,#subtaskPlus{
    display:none;
  }
</style>
</head>
<body>
<div id="show">
<a id="button">Button</a>
</div>
<div id="overlay">
後から表示するコンテンツ
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script>
$(document).ready(function(){
	$("#overlay").hide();
	$("#show #button").click(function () {
		$("#show").fadeTo('normal', 0.2);
			$("#overlay").toggle().animate(
			{bottom:'0'},1000
		);
	});
	$("#overlay").click(function () {
		$("#show").fadeTo('normal', 1);
		$("#overlay").toggle().css('bottom','10000px');
	});
});
</script>
</body>
</html>