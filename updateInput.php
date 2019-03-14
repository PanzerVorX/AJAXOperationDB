<?php
	if(isset($_POST["updateInput"])){
		$XH=$_POST["XH"];
		$XM=$_POST["XM"];
		$KCH=$_POST["KCH"];
		$KCM=$_POST["KCM"];
		$CJ=$_POST["CJ"];
?>	
	<!--更新输入框区域内容-->
	<table style="margin-top: 100px">
		<caption><h1>更新记录<h1></caption>
		<tr><th>学号</th><th>姓名</th><th>课程号</th><th>课程名</th><th>成绩</th></tr>	
		<tr><td><?php echo $XH; ?></td><td><?php echo $XM; ?></td><td><?php echo $KCH; ?></td><td><?php echo $KCM; ?></td><td><input type="text" name="CJ" id="CJ" value=<?php echo $CJ; ?> ></td><td><input type="button" value="提交更新" onclick="updateRecord('<?php echo $XH; ?>',<?php echo $KCH; ?>,document.getElementById('CJ').value);"></td><td><input type="button" value="取消" onclick="cancelOperation()"></td></tr>
	</table>
<?php		
	}
?>