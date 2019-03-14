<?php
	if(isset($_POST["addRecord"])){////向成绩表添加记录

		$XH=$_POST["XH"];
		$KCH=$_POST["KCH"];
		$CJ=$_POST["CJ"];

		include "ConnectSQL.php";
		$sql="select count(*) from xsb where 学号={$XH}";//查询XSB是否存在存在添加的学号
		$smt=$pdo->query($sql);
		$resultXH=$smt->fetchColumn(0);
		
		$sql="select count(*) from kcb where 课程号='{$KCH}'";//查询KCB是否存在添加的课程号
		$smt=$pdo->query($sql);
		$resultKCH=$smt->fetchColumn(0);

		if($resultXH&&$resultKCH){//更表记录时应该了参照完整性
			$sql="insert into cjb (学号,课程号,成绩) values ('{$XH}','{$KCH}',{$CJ})";
			$result=$pdo->exec($sql);
			if($result){
				echo "true";
			}
			else{
				echo "false";
			}
		}
		else{
			echo "false";
		}	
	}
?>