<?php
	// include("connection.php");
    $con = mysqli_connect("localhost","root","","pro_test") or die("Error: " . mysqli_error($con));
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $id_card = $_POST['idCard'];
    $cs_no = $_POST['csNo'];
		   
	$result = mysqli_query($con, "SELECT emp_id from employee where emp_id = '$id_card' ")
        or die("Failed db".mysqli_error());
    $row = mysqli_fetch_array($result);
    $result2 = mysqli_query($con, "SELECT emp_id from check_service JOIN check_service_detail ON check_service.cs_no = check_service_detail.cs_no JOIN check_list ON check_service_detail.csd_no = check_list.csd_no WHERE check_service.cs_no = '$cs_no' ")
        or die("Failed db".mysqli_error());
    $row2 = mysqli_fetch_array($result2);
    if($row['emp_id']&&$row2['emp_id'])  {
        echo "true";
    }
    else{
        echo "false";
    } 
?>