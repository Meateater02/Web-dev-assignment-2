<?php
/* 
    Davina Phan
    1300285
    COMP721 
    Assignment 2
*/

//this php file updates the sql database when the user has clicked the "assign" button

//get the php file that holds password etc
//require_once('../lab05/settings.php'); //for home
require_once('../../conf/settings.php'); //for school

$conn = mysqli_connect($host, $user, $pswd, $dbnm);

//debugging
//echo "<br>In the update file now";

if ($_POST['id']) 
{
	//debugging
	//echo "<br>Inside the if statement of update";

	$bookingRow = $_POST['id'];

	//update query
	$queryUpdate = "UPDATE cabsOnline SET status='Assigned' WHERE bookingRef='" . $bookingRow . "'";

	$update = mysqli_query($conn, $queryUpdate);
	if ($update) 
	{
		echo "<h3>Congratulations! Booking request " . $bookingRow . " has been assigned!</h3><br>";
	} 
	else 
	{
		echo "<br>Oh no! Assign is unsuccessful<br>";
	}
} 
else 
{
	echo "Something is wrong with " . $queryUpdate;
}

?>