<?php
	if(isset($_POST["updateRecord"])){//更新成绩表
		$XH=$_POST["XH"];
		$KCH=$_POST["KCH"];
		$CJ=$_POST["CJ"];
		include "ConnectSQL.php";
		$sql="update cjb set 成绩=$CJ where 学号='$XH' and 课程号='$KCH'";
		$result=$pdo->exec($sql);
		if($result){
			echo "true";
		}
		else{
			echo "false";
		}
	}
?>