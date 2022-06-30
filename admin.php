<?php
/* 
    Davina Phan
    1300285
    COMP721 
    Assignment 2
*/

//this admin.php search up the booking reference number and display back onto the client side from the server

//get the php file that holds password etc
// require_once('../lab05/settings.php'); // for home
require_once('../../conf/settings.php'); // for school

$search = $_GET['bsearch']; //search

$conn = mysqli_connect($host, $user, $pswd, $dbnm);

//check for connection
if ($conn) { //database connected

	//debugging
	//echo "database connected";

	$query = "";

	if (!empty($search) && isset($search)) 
	{
		//if search input is not empty, then look up the search query as booking reference ID
		$query = "SELECT * FROM cabsOnline WHERE bookingRef LIKE '%" . $search . "%'";
	} 
	else 
	{
		//if nothing in the search input
		//get the database of the bookings requests pickup time within 2hrs from current time

		//get the current date and time
		$timeNow = date('d/m/Y H:i:s');
	
		//get the time that's two hours later
		$twoHoursLater = date("d/m/Y H:i:s", strtotime("+2 hours"));

		//debugging
		//echo "<br>Time now: " . $timeNow;
		//echo "<br>Two hours later: " . $twoHoursLater;

		//sql query for pickup time within 2hrs from current time
		$query = "SELECT * FROM cabsOnline WHERE " . "CONCAT(pickupDate, ' ', pickupTime) BETWEEN '" 
			. $timeNow . "' AND '" . $twoHoursLater . "'";
	}

	//get results from the database
	$result = mysqli_query($conn, $query);

	//debugging
	//echo "<br>Query: " . $query;
    
	//check there are results found
	if(mysqli_num_rows($result) > 0) 
	{
		//display the results in a table
		echo "<div class=\"content\">";
		echo "<table class=\"data\">"; 

		//create table header
		echo "<tr>\n"
		. "<th scope=\"col\">Booking Reference Number</th>\n"
		. "<th scope=\"col\">Customer Name</th>\n"
		. "<th scope=\"col\">Phone</th>\n"
		. "<th scope=\"col\">Pickup Suburb</th>\n"
		. "<th scope=\"col\">Destination Suburb</th>\n"
 		. "<th scope=\"col\">Pickup Date and Time</th>\n"
		. "<th scope=\"col\">Status</th>\n"
		. "<th scope=\"col\">Assign</th>\n"
		. "</tr>\n";

		//display the rows
		while ($row = mysqli_fetch_assoc($result)) 
		{
			echo "<tr>";
			echo "<td>", $row["bookingRef"], "</td>";
			echo "<td>", $row["name"], "</td>";
			echo "<td>", $row["phone"], "</td>";
			echo "<td>", $row["suburb"], "</td>";
			echo "<td>", $row["destination"], "</td>";
			echo "<td>", $row["pickupDate"] . "  " . $row["pickupTime"], "</td>";
			echo "<td>", $row["status"], "</td>";

			//the assign button within the row
			if ($row["status"] == "Unassigned") 
			{
               			echo "<td>"
				. "<button value=\"Assign\" class=\"btn btn-primary\" onclick=\"assign('" . $row["bookingRef"] .  "')\" />"
				. "Assign</button>"
				. "</td>";
			} 
			else 
			{
				echo "<td><button class=\"btn btn-primary\" disabled>Assign</button></td>";
			}
            
			//end of row
			echo "</tr>";
		}

		//end of table
		echo "</table>";
		echo "</div>";
	} 
	else 
	{
		echo "<div class=\"container mt-3 bookingform\">"
		. "No data has been found. Please try another search."
		. "</div>";
	}
} 
else 
{
    die("<p>Server connection failure.</p>");
}
