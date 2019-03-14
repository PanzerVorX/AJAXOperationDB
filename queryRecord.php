<?php
	if(isset($_POST["queryRecord"])){//查询指定页

		$currentPage=$_POST["queryPage"];//查询页数
		$XH=$_POST["XH"];
		$XM=$_POST["XM"];
		$KCM=$_POST["KCM"];

		//连接查询
		include "ConnectSQL.php";
		$sql="select xsb.学号,xsb.姓名,kcb.课程号,kcb.课程名,cjb.成绩 from xsb join cjb on xsb.学号=cjb.学号 join kcb on cjb.课程号=kcb.课程号 ";

		$isFirstCondition=true;//首条件判断变量

		if($XH){
			$isFirstCondition=false;
			$sql=$sql."where xsb.学号 like '%{$XH}%' ";
		}
		if($XM){
			if($isFirstCondition){
				$isFirstCondition=false;
				$sql=$sql."where xsb.姓名 like '%{$XM}%' ";
			}
			else{
				$sql=$sql."and xsb.姓名 like '%{$XM}%' ";
			}
		}
		if($KCM){
			if ($isFirstCondition) {
				$isFirstCondition=false;
				$sql=$sql."where kcb.课程名 ='$KCM' ";
			}
			else{
				$sql=$sql."and kcb.课程名 ='$KCM' ";
			}
		}

		if(!$isFirstCondition){//存在条件
			
			//字符串替换（指定位置）substr_replace()，参数：①字符串 ②用于替换字部分 ③开始位置 [④替换长度（0为插入，负数为替换截止位距尾部长度）]
			//获取字符串中首次/最后出现指定部分的索引（首字符）：strpos()/strrpos()，参数：①字符串 ②指定部分
			$countSql=substr_replace($sql,"select count(*) ",0,strpos($sql,'from'));//查询语句替换为查询记录条数
			$smt=$pdo->query($countSql);
			$pageRow=5;//单页规定条数
			$totalCount=$smt->fetchColumn(0);//总记录行数

			//分页管理优点：通过限定查询记录行数（结果集的规模）来减少遍历结果集的时间，从而提高用户使用体验
			//分页查询过程：①通过count()查询对应条件额的总记录条数（获取总页数） ②通过limit限定查询当前页的记录
			$totalPage=ceil($totalCount/$pageRow)==0?1:ceil($totalCount/$pageRow);//总页数
			//尾页记录行数通过总记录行数模页规定行数获得，当结果为0时表示尾页行数排满
			$lastPageCount=($totalCount%$pageRow==0)?$pageRow:($totalCount%$pageRow);//尾页记录行数
			$currentFstRow=($currentPage-1)*$pageRow;//当前页首行记录索引

			//限定查询条数实现分页
			if($currentPage<$totalPage){//当前页<尾页时显示记录行数为单页规定数
				$sql=$sql."limit {$currentFstRow},{$pageRow}";
			}
			else{//当前页>尾页时显示记录行数为尾页记录行数
				$sql=$sql."limit {$currentFstRow},{$lastPageCount}";
			}
			
			$smt=$pdo->query($sql);
			$resultArr=$smt->fetchAll();//遍历结果集

			if($resultArr){
			echo "<table>";
			echo "<caption><h1>学生分数查询结果</h1></caption>";
			echo "<tr><th>学号</th><th>姓名</th><th>课程号</th><th>课程名</th><th>成绩</th></tr>";

			//查询结果区域的显示内容
			foreach ($resultArr as $key => $value) {
?>
				<!--绑定标签响应事件的操作数据：将标签调用的响应函数所传实参设置为预传参数-->
				<tr><td><?php echo $value['学号']; ?></td><td><?php echo $value['姓名']; ?></td><td><?php echo $value['课程号']; ?></td><td><?php echo $value['课程名']; ?></td><td><?php echo $value['成绩']; ?></td><td><input type="button" value="更新" onclick="updateInput('<?php echo $value['学号']; ?>','<?php echo $value['姓名']; ?>',<?php echo $value['课程号']; ?>,'<?php echo $value['课程名']; ?>','<?php echo $value['成绩']; ?>');"></td><td><input type='button' value='删除' onclick="deleteRecord('<?php echo $value['学号']; ?>',<?php echo $value['课程号']; ?>);"></td></tr>
<?php         
			}
			echo "</table>";
			echo '<div name="bottom" id="bottom" style="text-align: center; margin-top: 75px;position:absolute;left:460px;top:450px">';
			echo '<input style="margin-left: 80px" type="button" name="fstPage" value="首页" onclick="fstPage();" >';
			echo '<input style="margin-left: 80px" type="button" name="upPage" value="上一页" onclick="upPage();">';
			echo '<input style="margin-left: 80px" type="button" name="downPage" value="下一页" onclick="downPage();">';
			echo '<input style="margin-left: 80px" type="button" name="lastPage" value="尾页" onclick="lastPage();">';
			echo "<input style='margin-left: 80px' type='button' name='displayPage' id='displayPage' value='{$currentPage}/{$totalPage}'>";
			echo '<div>';

			//AJAX返回用于显示的标签内容时可通过隐藏域标签携带独立的数据
			//隐藏域存储当前页数/总页数
			echo "<input type='hidden' name='currentPage' id='currentPage' value={$currentPage}>";
			echo "<input type='hidden' name='totalPage' id='totalPage' value={$totalPage}>";
			}
		}
	}
?>