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
<div align="center" class="task_on2">おす</div>
<div class="task_input2">
<input type="text" id="title" name="title">		
<select name="importance" class="importance">
	<option value="0">優先度</option>
	<option value="1">小</option>
	<option value="2">中</option>
	<option value="3">高</option>
</select>
<input type="button" value="追加" id="addTask" name="button">	
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script>
var task_input2 = $('.task_input2')
$(document).on('click','.task_on2',function(){
		$('.task_on2').hide();
		$('.task_input2').show();
		$('#title').focus().val("");
		$('.task_input2').addClass('do');
		console.log('押されてる');
		$(document).on('click',function(event){
	var task_input2 = $('.task_input2')
	if(task_input2.hasClass('do')){
		if(!$.contains($(".task_input2")[0],event.target)){
			//$('task_input2').blur(function(){
			console.log('ok');
			$('.task_on2').show();
			$('.task_input2').hide();
			task_input2.removeClass('do');
			$('.importance').prop("selectedIndex",0);
			$(".importance").off("click",false);
		}
	}
});
	});
</script>
</body>
</html>