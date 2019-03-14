<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">

		table{
			width: 900px;
			margin: auto;
			text-align: center;
		}
		td{
			border: 2px solid blue;
			width: 70px;
			height: 40px;
		}
		.contentdiv{
			margin: auto;
			width: 900px;
			height: 300px;
		}

	</style>
	<script type="text/javascript">

		function getXmlObject(){//获取AJAX操作对象
			var XMLHttp=null;
			if(window.XMLHttpRequest){
				XMLHttp=new XMLHttpRequest();
			}
			else if(window.ActiveXObject){
				try{
					XMLHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e){
					XMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			return XMLHttp;
		}

		function cancelOperation(){//取消更新/添加操作
			//隐藏更新/添加输入框区域
			document.getElementById("updateInputDiv").innerHTML=null;
			document.getElementById("addInputDiv").innerHTML=null;
		}

		function saveQuery(){//存储查询条件并进行查询（将查询区域中输入框的条件存入隐藏域）

			document.getElementById('backupXH').value=document.getElementById('XH').value;
			document.getElementById('backupXM').value=document.getElementById('XM').value;
			document.getElementById('backupKCM').value=document.getElementById('KCM').value;
			queryRecord(1);
		}

		function queryRecord(queryPage){//根据查询条件与查询页面进行分页查询

			//html标签的点击属性值为JS函数
			//实现在html标签的点击事件中间接调用PHP函数：在标签响应事件调用的JS函数中使用AJAX与PHP页面交互
			var XMLHttp=getXmlObject();

			//获取存储的查询条件
			var XH=document.getElementById('backupXH').value;
			var XM=document.getElementById('backupXM').value;
			var KCM=document.getElementById('backupKCM').value;

			//设置AJAX交互的数据库分页查询页面与相关参数
			var url="queryRecord.php";
			var postStr="queryRecord="+"OK"+"&"+"XH="+XH+"&"+"XM="+XM+"&"+"KCM="+KCM+"&"+"queryPage="+queryPage;
			XMLHttp.open("POST",url,true);
			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			
			XMLHttp.onreadystatechange=function(){//查询后的响应方法

				if(XMLHttp.readyState==4&&XMLHttp.status==200){//判断处理服务器响应状态

					//将更新输入区域与添加输入区域置空
					cancelOperation();

					//JS中标签元素的下属内容：标签元素对象的innerHTML属性
					document.getElementById('content').innerHTML=XMLHttp.responseText;//内容区域显示查询结果记录
				}
			}
		}

		function fstPage(){//首页
				queryRecord(1);
		}

		function upPage(){//上一页
			//JS与PHP间的数据不能相互获取：JS为客户端代码，PHP为服务端代码
			//实现JS间接获取PHP数据：将PHP数据存入隐藏域，JS中获取隐藏域元素值
			if(document.getElementById('currentPage').value>1){//当前页>1时可执行上一页操作
				queryRecord(document.getElementById('currentPage').value-1);
			}
		}

		function downPage(){//下一页
			if(document.getElementById('currentPage').value<document.getElementById('totalPage').value){//当前页<尾页时可执行下一页操作
				queryRecord((document.getElementById('currentPage').value)*1+1);
			}
		}

		function lastPage(){//尾页
			queryRecord(document.getElementById('totalPage').value);
		}
		
		function deleteRecord(XH,KCH){//删除成绩表记录
			var queryXH=document.getElementById('backupXH').value;
			var queryXM=document.getElementById('backupXM').value;
			var queryKCM=document.getElementById('backupKCM').value;
			var currentPage=document.getElementById('currentPage').value;
			var XMLHttp=getXmlObject();
			var url="deleteRecord.php";
			var postStr="deleteRecord="+"OK"+"&"+"XH="+XH+"&"+"KCH="+KCH+"&queryXH="+queryXH+"&queryXM="+queryXM+"&queryKCM="+queryKCM+"&currentPage="+currentPage;//传递参数为记录绑定的主键数据
			XMLHttp.open("POST",url,true);
			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			XMLHttp.onreadystatechange=function(){
				if(XMLHttp.readyState==4&&XMLHttp.status==200){
					if(XMLHttp.responseText!="false"){//删除成功
						if(XMLHttp.responseText=="true-currentPageChange"){//当前页为尾页且只有一条记录时删除该记录后当前页-1
							document.getElementById('currentPage').value=document.getElementById('currentPage').value-1;
						}
						queryRecord(document.getElementById('currentPage').value);//隐藏域的总页数与尾页记录数在随后的查询中自动改变（不用手动改变）
						alert("删除成功");
					}
					else{//删除失败时
						alert("删除失败");
					}
				}
			}	
		}
		
		function updateInput(XH,XM,KCH,KCM,CJ){//显示更新输入框
			var XMLHttp=getXmlObject();
			var url="updateInput.php";
			var postStr="updateInput="+"OK"+"&"+"XH="+XH+"&"+"XM="+XM+"&"+"KCH="+KCH+"&"+"KCM="+KCM+"&"+"CJ="+CJ;//传递参数为当前记录的所有数据（用于显示）
			XMLHttp.open("POST",url,true);
			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			XMLHttp.onreadystatechange=function(){
				if(XMLHttp.readyState==4&&XMLHttp.status==200){
					document.getElementById("addInputDiv").innerHTML=null;//隐藏添加输入框区域
					document.getElementById("updateInputDiv").innerHTML=XMLHttp.responseText;//显示更新输入框区域
				}
			}
		}

		function updateRecord(XH,KCH,CJ){//更新成绩表记录
			var XMLHttp=getXmlObject();
			var url="updateRecord.php";
			var postStr="updateRecord="+"OK"+"&"+"XH="+XH+"&"+"KCH="+KCH+"&"+"CJ="+CJ;
			XMLHttp.open("POST",url,true);
			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			XMLHttp.onreadystatechange=function(){
				if(XMLHttp.readyState==4&&XMLHttp.status==200){
					document.getElementById("updateInputDiv").innerHTML=null;//隐藏更新输入框区域
					if(XMLHttp.responseText=="true"){//更新成功时
						alert("更新成功");
						queryRecord(document.getElementById('currentPage').value);//更新后重新查询当前页面
					}
					else{//更新失败时
						alert("更新失败");
					}
				}
			}
		}

		function addInput(){//显示添加输入框
			var XMLHttp=getXmlObject();
			var url="addInput.php";
			var postStr="addInput="+"OK";
			XMLHttp.open("POST",url,true);
			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			XMLHttp.onreadystatechange=function(){
				if(XMLHttp.readyState==4&&XMLHttp.status==200){
					document.getElementById("updateInputDiv").innerHTML=null;//隐藏更新输入框
					document.getElementById("addInputDiv").innerHTML=XMLHttp.responseText;//显示添加输入框区域
				}
			}
		}

		function addRecord(XH,KCH,CJ){//向成绩表添加记录
			var XMLHttp=getXmlObject();
			var url="addRecord.php";
			var postStr="addRecord="+"OK"+"&XH="+XH+"&KCH="+KCH+"&CJ="+CJ;//传递参数为添加记录的数据
			XMLHttp.open("POST",url,true);
			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			XMLHttp.onreadystatechange=function(){
				if(XMLHttp.readyState==4&&XMLHttp.status==200){
					document.getElementById("addInputDiv").innerHTML=null;//隐藏添加输入框区域
					if(XMLHttp.responseText=="true"){//当添加成功时
						queryRecord(document.getElementById('currentPage').value);//添加后重新查询当前页面
						alert("添加成功");
					}
					else{//当添加失败时
						alert("添加失败");
					}
				}
			}
		}

	</script>
</head>
<body>

	<!--顶部查询输入框区域-->
	<table cellspacing="0px">
		<caption><h1>学生成绩查询</h1></caption>
		<br>
		<tr>
			<td>学号：</td>
			<td><input type="text" name="XH" id="XH"></td>
			<td>姓名：</td>
			<td><input type="text" name="XM" id="XM"></td>
			<td>课程名：</td>
			<td>
			<select name="KCM" id="KCM">
			<?php
				include "ConnectSQL.php";
				$sql="select 课程名 from KCB";
				$smt=$pdo->query($sql);
				$kCMArr=$smt->fetchAll();
				foreach ($kCMArr as $key => $value) {
					echo "<option>".$value["课程名"]."</option>";
				}
			?>	
			</select>
			</td>
			<td>
				<input type="button" name="OK" value="查询" onclick="saveQuery();">
			</td>
			<td><input type="button" name="addInput" value="添加" onclick="addInput();"></td>
		</tr>
	</table>
	<!--查询结果区域-->
	<div class="contentdiv" name="content" id="content"></div>
	
	<!--分页查询所需参数：①查询条件参数（若存在筛选条件） ②当前页数（无则表示首次查询，默认值为首页）-->
	<!--分页查询所需参数的使用：①AJAX方式：需持续保存（可利用隐藏域保存），通过传递参数至PHP页面查询并返回结果后更新显示 ②链接/表单方式：无需持续保存，通过传递参数并跳转页面刷新显示-->
	<!--底部选项区域-->
		<input type="hidden" name="backupXH" id="backupXH">
		<input type="hidden" name="backupXM" id="backupXM">
		<input type="hidden" name="backupKCM" id="backupKCM">

	<!--更新输入框区域-->
	<div name="updateInputDiv" id="updateInputDiv"></div>
	
	<!--添加输入框区域-->
	<div name="addInputDiv" id="addInputDiv"></div>

</body>
</html>