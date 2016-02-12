<?php
// Created by sybiload

$host="mysql16.000webhost.com";
$username="a2199254_admin"; 
$password="root5384497"; 
$db_name="a2199254_test";
 
$con=mysql_connect("$host", "$username", "$password")or die("Unable to connect to the database"); 
mysql_select_db("$db_name")or die("cannot select DB");
$sql = "select * from score"; 
$result = mysql_query($sql);
$json = array();
 
if(mysql_num_rows($result)){
while($row=mysql_fetch_assoc($result)){
$json['times'][]=$row;
}
}
mysql_close($con);
echo json_encode($json); 
?> 
