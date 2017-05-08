<?php
	header("Content-type: text/html; charset=utf-8"); 
	$conn=mysqli_connect('localhost', 'root','');
	if(!$conn)die('数据库连接错误'.mysql_error());
	mysqli_query($conn, 'set names utf8');
	mysqli_query($conn, 'use php2') or die('php2数据库不存在');
	
	//准备SQL查询语句
	//$sql='select * from userinfo';
	
	//执行SQL语句，获取结果集
//	$res=mysqli_query($conn,$sql);
//	if(!$res)die(mysql_error());
	
	//定义用户数组，用以保存用户信息
	$user_info=array();
	
	//遍历结果集，获取每位员工的详细数据
//	while($row =mysqli_fetch_assoc($res)){
//		$user_info[]=$row;
//	}
	
	//var_dump($user_info);
	//echo $user_info[0]['nickname'];
	
	//删除数据
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$sql="DELETE FROM `userinfo` WHERE  id='$id'";
		//echo $sql;
	}
	//mysqli_query($conn, $sql);
	
	//查询数据
	if(isset($_GET['search'])){
		$keywords=$_GET['search'];
		$sql="SELECT * FROM `userinfo` WHERE `nickname` like '%$keywords%'";
	}else{
		$sql="SELECT * FROM `userinfo`";
	}
	$res=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($res)){
			$user_info[]=$row;
		}
	//var_dump($user_info);
	
	//分页
	//总记录数
	$total_num = count($user_info);
	//echo $total_num;
	//每页显示的条数
	$perpage = 3;
	
	//获取当前页
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	//获取总页数
	$total_page = ceil($total_num/$perpage); 
	
	//对获取的当前页进行合理性判断
	//1、判断当前页是否小于1
	$page = max($page,1);
	//2、判断当前页码数是否大于总页数
	$page = min($page,$total_page);
	
	//获取遍历学生数组时，每页开始的数组坐标值
	$start_index = $perpage * ($page-1);
	//获取遍历学生数组时，每页最大的数组坐标值
	$end_index = $perpage * $page-1;
	//防止计算结果超过最大记录数
	$end_index = min($end_index,$total_num-1);
	
	function showPage($page,$total_page){ 	
	
		//拼接“首页”链接
		$html = '<a href="?page=1">【首页】</a>'; 
		
		//拼接“上一页”链接
		$pre_page = $page-1 <= 0 ? $page : ($page-1);
		$html .= '<a href="?page='.$pre_page.'">【上一页】</a>'; 
//		for($i=2;$i<$total_page;$i++){
//			$html .= '<a href="?page='.$i.'">'.$i.'</a>';
//		}
		 
		
		//拼接“下一页”链接
		$next_page = $page+1 > $total_page ? $page : ($page+1);
		$html .= '<a href="?page='.$next_page.'">【下一页】</a>'; 
		
		//拼接“尾页”链接
		$html .= '<a href="?page='.$total_page.'">【尾页】</a>';
		
		//返回拼接后的分页链接
		return $html; 
	}
	
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>会员信息列表</title>
<link rel="stylesheet" href="./css/common.css" />

</head>
<body>
<div class="box list">
	<div class="title"><h1>会员信息列表</h1></div>
	<div class="search">
		<form>
			快速查询：
			<input type="text" name="search"/> 
			<input type="submit" value="提交"/>
		</form>
	</div>
	<table>
		<tr>
			<th width="5%"><a href="#">ID</a></th>
			<th><a href="#">昵称</a></th>
			<th><a href="#">性别</a></th>
			<th><a href="#">邮箱</a></th>
			<th><a href="#">QQ号</a></th>
			<th><a href="#">个人主页</a></th>
			<th><a href="#">所在城市</a></th>
			<th><a href="#">语言技能</a></th>
			<th><a href="#">自我介绍</a></th>
			<th width="20%">相关操作</th>
		</tr>
		
		<?php for($i=$start_index;$i<=$end_index;++$i){ ?>	
		<tr>
			<td><?php echo  $user_info[$i]['id']?></td>
			<td><?php echo  $user_info[$i]['nickname']?></td>
			<td><?php echo  $user_info[$i]['gender']?></td>
			<td><?php echo  $user_info[$i]['email']?></td>
			<td><?php echo  $user_info[$i]['qq']?></td>
			<td><?php echo  $user_info[$i]['url']?></td>
			<td><?php echo  $user_info[$i]['city']?></td>
			<td><?php echo  $user_info[$i]['skill']?></td>
			<td><?php echo  $user_info[$i]['description']?></td>
			<td>
				<a class="icon icon-edit" href="edit.php?id=<?php echo  $user_info[$i]['id']?>">编辑</a>
				<a class="icon icon-del" href="?id=<?php echo $user_info[$i]['id']?>" onclick=" return confirm('你确定要删除吗？')">删除</a>
			</td>
		</tr>
		<?php }?>
	</table>
	<div class="page"><?php echo showPage($page,$total_page,$perpage);?></div>
	<div class="action">
		<a href="add.html">添加员工</a>
	</div>
</div>
</body>
</html>