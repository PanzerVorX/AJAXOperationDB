<?php
	include "ConnectSQL.php";
	if(isset($_POST["deleteRecord"])){//删除成绩表记录
		$XH=$_POST["XH"];
		$KCH=$_POST["KCH"];
		$queryXH=$_POST["queryXH"];
		$queryXM=$_POST["queryXM"];
		$queryKCM=$_POST["queryKCM"];
		$currentPage=$_POST["currentPage"];
		$sql="delete from cjb where 学号='$XH' and 课程号='$KCH'";
		$result=$pdo->exec($sql);
		if($result){
			$sql="select count(*) from xsb join cjb on xsb.学号=cjb.学号 join kcb on cjb.课程号=kcb.课程号 ";
			$isFstCondition=true;
			if($queryXH){
				$isFstCondition=false;
				$sql.="where 学号 like '%{$queryXH}%' ";
			}
			if($queryXM){
				if($isFstCondition){
					$isFstCondition=false;
					$sql.="where 姓名 like '%{$queryXM}%' ";
				}
				else{
					$sql.="and 姓名 like '%{$queryXM}%' ";
				}
			}
			if($queryKCM){
				if($isFstCondition){
					$sql.="where 课程名 like '%{$queryKCM}%' ";
				}
				else{
					$sql.="and 课程名 like '%{$queryKCM}%' ";
				}
			}
			$smt=$pdo->query($sql);
			$totalCount=$smt->fetchColumn(0);
			$pageCount=5;
			$totalPage=ceil($totalCount/$pageCount)>0?ceil($totalCount/$pageCount):1;
			if($currentPage>$totalPage){//分页管理中防止删除操作影响当前页数的判断：①判断当前页是否为尾页且只含单条记录  ②删除记录后判断当前页是否大于总页数
				echo "true-currentPageChange";
			}
			else{
				echo "true-currentPageNoChange";
			}
		}
		else{
			echo "false";
		}
	}	
?>