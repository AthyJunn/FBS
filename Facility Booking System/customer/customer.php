<?php

function getCustomerInformation($Cust_no)
{
//create connection
$con=mysqli_connect("localhost","web2025","web2025","facilitydb");
if(!$con)
	{
	echo  mysqli_connect_error(); 
	exit;
	}
$sql = "select * from customers where customerID = '".$customerID."'";
$qry = mysqli_query($con,$sql);//run query
if(mysqli_num_rows($qry) == 1)
	{
	$row=mysqli_fetch_assoc($qry);
	return $row;
	}
else
	return false;
}

?>