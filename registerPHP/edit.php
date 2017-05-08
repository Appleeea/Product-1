<?php
	header("Content-type: text/html; charset=utf-8"); 
	//连接数据库，设置字符集，选择数据库
	$conn=mysqli_connect('localhost','root','') or die('数据库连接失败！');
	mysqli_query($conn,'set names utf8');
	mysqli_query($conn,'use `php2`') or die('php2数据库不存在！');
	
	//定义数组$city保存预设的城市下拉列表
	$city = array('北京','上海','广州','其他');
	
	//定义数组$skill保存预设的语言技能复选框
	$skill = array('HTML','JavaScript','PHP','C++');
	
	$id=$_GET['id'];
	//$id=1;
	
	$sql="select * from userinfo where id='$id'";
	//echo $sql;
	
	$res=mysqli_query($conn, $sql);
	$data=mysqli_fetch_assoc($res);
	$data['skill']=explode(',', $data['skill']);
	
	//修改
	//var_dump($_POST);
	
	//判断是否有表单提交
if(!empty($_POST)){
	
	//当有表单提交时，收集表单数据，保存到数据库中

	//显示接收到的表单数据
	//var_dump($_POST);

	//指定需要接收的表单字段
	$fields = array('nickname','password','gender','email','qq','url','city','skill','description');

	//根据程序中定义好的表单字段收集$_POST数据
	foreach($fields as $v){
		//$save_data保存收集到的表单数据，不存在的字段填充空字符串
		$save_data[$v] = isset($_POST[$v]) ? $_POST[$v] : '';
	}

	var_dump($save_data);
	
	//单选框处理
	if($save_data['gender']!='男' && $save_data['gender']!='女'){
		die('保存失败：未选择性别。');
	}

	//下拉菜单处理
	if($save_data['city']!='未选择' && !in_array($save_data['city'],$city)){  //判断是否是$city数组中定义的合法值
		die('保存失败：您填写的城市不在允许的城市列表中。');
	}

	//复选框处理
	if(is_array($save_data['skill'])){
		$save_data['skill'] = array_intersect($skill,$save_data['skill']);	//只取出合法的数组元素
		$save_data['skill'] = implode(',',$save_data['skill']);  //将数组转换为用逗号分隔的字符串	
	}else{
		$save_data['skill'] = '';
	}

	//通过循环数组，自动拼接SQL语句，保存到数据库中
	$sql = 'update `userinfo` set ';
	foreach($save_data as $k=>$v){
		$sql .= "`$k`='".mysqli_real_escape_string($conn,$v)."',"; //拼接每个字段的SQL语句，并对值进行SQL安全转义
	}
	$sql = rtrim($sql,',')." where id=$id"; //rtrim($sql,',')用于去除$sql中的最后一个逗号
	//echo $sql;
	$rst = mysqli_query($conn,$sql);
	//输出执行结果和调试信息
	//echo $rst ? "保存成功：$sql" : "保存失败：$sql<br>".mysql_error();
	if($rst){
		echo "<script>alert('数据保存成功');window.location.href='list.php'</script>";
		//header('Location:list.php');
	} 
}
	
	require 'edit.html';
?>