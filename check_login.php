<?php
	session_start();
	include('connection.php');
	$uname = mysqli_real_escape_string($con,$_POST['username']);
	$upass = mysqli_real_escape_string($con,$_POST['password']);
	
	$result=mysqli_query($con,"SELECT * from user where user_name ='$uname' and user_pass = '$upass'")
		or die("Failed db".mysqli_error());
	$row=mysqli_fetch_array($result);

	if($row['user_name']==$uname && $row['user_pass']==$upass 
		&& $uname!='' &&$upass!='' )
	{
		if ($row['user_status']=='admin') {
			$_SESSION['user_name']=$row['user_name'];
			$_SESSION['login']= '1';
			$_SESSION['status']= '1';
			$_SESSION['home']='1';
			// $_SESSION['alert']='loginSuccess';
			//echo "<script language=\"JavaScript\">";
			//echo "alert('you are ".$row['user_status']."');";
			//echo "window.location='home.php';";
			//echo "</script>";
			// header("location: home.php");
			$_SESSION['alert']='loginSuccess';
			// echo "<script language=\"JavaScript\">";
			// echo "alert('you are ".$row['user_status']."');";
			// echo "window.location='home.php';";
			// echo "</script>";
			header('Location: edit_check-service.php');
			exit;
		}
		else{
			$_SESSION['user_name']=$row['user_name'];
			$_SESSION['login']= '1';
			$_SESSION['status']= '2';
			//echo "<script language=\"JavaScript\">";
			//echo "alert('you are ".$row['user_status']."');";
			//echo "window.location='home.php';";
			//echo "</script>";
			header('Location: edit_check-service.php');
		}
		
	}
	else //wrong user or pass
	{	
		echo "<script language=\"JavaScript\">";
		//echo "alert('Invalid username or password!!!');";
		//echo "window.location='index.php';";
		echo "</script>";
		header('Location: index.php');
	}
?>
  