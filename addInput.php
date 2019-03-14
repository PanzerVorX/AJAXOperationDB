<?php
	if(isset($_POST["addInput"])){
?>
	<!--添加输入框区域内容-->
	<table style="margin-top: 100px">
		<caption><h1>添加记录<h1></caption>
		<tr><th>学号</th><th>课程号</th><th>成绩</th></tr>	
		<tr><td><input type="text" name="addXH" id="addXH"></td><td><input type="text" name="addKCH" id="addKCH"></td><td><input type="text" name="addCJ" id="addCJ"></td><td><input type="button" value="添加记录" onclick="addRecord(document.getElementById('addXH').value,document.getElementById('addKCH').value,document.getElementById('addCJ').value)"></td><td><input type="button" value="取消" onclick="cancelOperation()"></td></tr>
	</table>
<?php		
	}
?>