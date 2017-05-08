<?php
	header("Content-type: text/html; charset=utf-8"); 
	//var_dump($_POST);
	//判断表单中各字段是否都填写
	$check_fields=array('nickname','password','gender','email','qq','url','city','skill','description');
	//var_dump($check_fields);
	foreach($check_fields as $v){
		if(empty($_POST[$v])){
			die('错误：'.$v.'字段不能为空！');
		}
	}
	
	//接收需要处理的表单字段
	$nickname=$_POST['nickname'];
	$gender=$_POST['gender'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$qq=$_POST['qq'];
	$url=$_POST['url'];
	$city=$_POST['city'];
	$skill=$_POST['skill'];
	$desc=$_POST['description'];
	
	var_dump($skill);
	$skill=implode('.', $skill);//将数组转成字符串
	//连接数据库，设置字符集，选择数据库
	var_dump($skill);
	$conn=mysqli_connect('localhost', 'root','');
	if(!$conn)die('数据库连接错误'.mysql_error());
	mysqli_query($conn, 'set names utf8');
	mysqli_query($conn, 'use php2') or die('php2数据库不存在');
	
	
	//判断用户名是否存在
	$sql="select `id` from `userinfo` where `nickname`='$nickname'";
	//echo $sql;
	$result=mysqli_query($conn, $sql);
	if(mysqli_fetch_row($result)){
		die('用户名已经存在，请换个用户名。');
	}
	//使用MD5增强密码的安全性
	$password=md5($password);
	
	//拼接SQL语句
	$sql="INSERT INTO `userinfo`(`nickname`,`password`,`gender`,`email`,`qq`,`url`,`city`,`skill`,`description`) VALUES "
			."('$nickname','$password','$gender','$email','$qq','$url','$city','$skill','$desc')";
	echo $sql;
//	//执行SQL语句
	$res=mysqli_query($conn, $sql);
	if($res){
		//echo "数据添加成功";
		//echo "<script>alert('数据添加成功！');window.location.href='list.php';</script>";
		header('Location:list.php');
	}
?>