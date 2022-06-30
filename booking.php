<?php
/* 
    Davina Phan
    1300285
    COMP721 
    Assignment 2
*/

//this booking.php checks the validity of the information on the server side, that is given by the client, and submits into the sql database once all requirements are met

//get the php file that holds password etc
//require_once('../lab05/settings.php'); //for home
require_once('../../conf/settings.php'); //for aut

//connect to the database
$conn = mysqli_connect($host, $user, $pswd, $dbnm);

//set the timezone to NZ
date_default_timezone_set('Pacific/Auckland');

//initialise variables
$name = "";
$phone = "";
$unitNum = $_POST["unumber"]; //optional, meaning can be null/empty
$streetNum = "";
$streetName = "";
$suburb = $_POST["sbname"]; //optional
$desSuburb = $_POST["dsbname"]; //optional
$pickUpDate = "";
$pickUpTime = "";
$uniqueNum = "";
$bookingRef = "";

//string to echo out if error and also used for checking all requirements are met
$nameErr = "";
$phoneErr = "";
$streetNumErr = "";
$streetNameErr = "";
$dateErr = "";
$timeErr = "";

//pattern and data to check for validity
$patternPhone = "/^\d{10,12}$/"; //pattern for phone
$patternDate = "/^\d{4}-\d{2}-\d{2}$/"; //pattern for date
$timeNow = date('d/m/Y H:i:s'); //this gets the current date and time
$patternStreetNum = "/^\d+$/"; //pattern for number only for street number

//validation
//check if phone matches and is not null
if (!empty($_POST["phone"]) && preg_match($patternPhone, $_POST["phone"])) 
{
	$phone = $_POST["phone"];
	$phoneErr = "";
} 
else if (empty($_POST["phone"])) 
{
	$phoneErr = "<br>Phone number cannot be empty!";
} 
else if (!preg_match($patternPhone, $_POST["phone"])) 
{
	$phoneErr = "<br>Incorrect phone pattern! Phone number can only consist of 10-12 digit numbers";
}

//check if date format is correct and not null and also the time
if (!empty($_POST["date"]) && preg_match($patternDate, $_POST["date"])) 
{
	//dateTemp is a temporary variable to store the date for checking for validation
	$dateTemp = $_POST["date"];
	$year = substr($dateTemp, 0, 4);
	$month = substr($dateTemp, 5, 2);
	$day = substr($dateTemp, 8, 2);

	//check date
	$valid_date = checkdate($month, $day, $year); //the built in format for checkdate function in php

	//check if date is a valid date
	if (!$valid_date) 
	{
        	$dateErr = "<br>Date entered is not a valid date!";
	} 
	else //if valid
	{ 
        	//convert dateTemp into the required format 
        	$dateTemp = $day . "/" . $month . "/" . $year;

		//get the current date to check if input date is valid
		$dayNow = substr($timeNow, 0, 2);
		$monthNow = substr($timeNow, 3, 2);
		$yearNow = substr($timeNow, 6, 4);
		$todayDate = $dayNow . "/" . $monthNow . "/" . $yearNow;

		if ($year > $yearNow || ($year == $yearNow && $month > $monthNow) || ($year == $yearNow && $month == $monthNow && $day > $dayNow)) 
		{
			//date is valid as long as it is set in the future date
			$pickUpDate = $dateTemp;
			$dateErr = "";

			//if date is future date, then for time, only need to check if a time is set
			if (!empty($_POST["time"])) 
			{
				$pickUpTime = $_POST["time"];
				$timeErr = "";
			} 
			else 
			{
				$timeErr = "<br>Time cannot be empty!";
			}
		} 
		else if ($dateTemp == $todayDate) 
		{
			//can be today's date, as long as the time has not passed yet, but have to check time 
			$pickUpDate = $dateTemp;
			$dateErr = "";

			//check if time is empty
			if (!empty($_POST["time"])) 
			{ 
				//get current hour and minutes for checking purpose 
				$currentHour = substr($timeNow, 11, 2);
				$currentMinute = substr($timeNow, 14, 2);
				$timeTempNow = $currentHour . ":" . $currentMinute;

				//get the input hour and minutes to check 
				$timeTemp = $_POST["time"];
				$hour = substr($timeTemp, 0, 2);
				$minute = substr($timeTemp, 3, 2);

				//debugging
				//echo "<br>Current time: ". $timeNow;
				//echo "<br>Current time: ". $timeTempNow;

				if ($hour > $currentHour) 
				{
					//if hour is greater, then time is valid
					$pickUpTime = $_POST["time"];
					$timeErr = "";
				} 
				else if ($hour == $currentHour) 
				{
					//if hour is equal to current hour, then check minutes
					if ($minute >= $currentMinute) 
					{
						//if minute is greater, then it's valid
						$pickUpTime = $_POST["time"];
						$timeErr = "";
					} 
					else 
					{
						$timeErr = "<br>Pick up time cannot be before the time now!";
					}
				} 
				else 
				{
					$timeErr = "<br>Pick up time cannot be before the time now!";
				}
			} 
			else 
			{
				$timeErr = "<br>Time cannot be empty!";
			}
		} 
		else 
		{
			$dateErr = "<br>Date cannot be before today's date.";

			//check for time as well
			if (!empty($_POST["time"])) 
			{
				$pickUpTime = $_POST["time"];
				$timeErr = "";
			} 
			else 
			{
				$timeErr = "<br>Time cannot be empty!";
			}
		}
	}
} 
else if (empty($_POST["date"])) //check if date is empty
{ 
	$dateErr = "<br>Date cannot be empty!";
	if (empty($_POST["time"])) 
	{
		$timeErr = "<br>Time cannot be empty!";
	}
} 
else if (!preg_match($patternDate, $_POST["date"])) //check if date pattern doesn't match
{ 
	$dateErr = "<br>Date must be in the format dd/mm/yyyy. E.g. 1/06/2022";
	if (empty($_POST["time"])) 
	{
		$timeErr = "<br>Time cannot be empty!";
	}
}

//debugging
//$date = $_POST["date"];
//echo "<br>Date: " . $date;
//echo "<br>Pick-Up Date: " . $pickUpDate;
//echo "<br>Time: " . $pickUpTime;
//echo "<br>" . $todayDate;

//debugging
//$pickUpTime = $_POST["time"];
//echo "<br>" . $pickUpTime;
//echo "<br>" . $timeNow;
//echo "<br>" . date_default_timezone_get();
//echo "<br>" . $pickUpTime;

//check for customer name not null
if (!empty($_POST["cname"])) 
{
	$name = $_POST["cname"];
} 
else 
{
	$nameErr = "<br>Name cannot be empty!";
}

//check for street number not null
if (!empty($_POST["snumber"]) && preg_match($patternStreetNum, $_POST["snumber"])) 
{
	$streetNum = $_POST["snumber"];
} 
else if (empty($_POST["snumber"])) 
{
	$streetNumErr = "<br>Street Number cannot be empty!";
}
else if (!preg_match($patternStreetNum, $_POST["snumber"])) 
{
	$streetNumErr = "<br>Street Number must contain numbers only!";
}

//check for street name not null
if (!empty($_POST["stname"])) 
{
	$streetName = $_POST["stname"];
} 
else 
{
	$streetNameErr = "<br>Street Name cannot be empty!";
}

//echo out the error messages in order
echo $nameErr;
echo $phoneErr;
echo $streetNumErr;
echo $streetNameErr;
echo $dateErr;
echo $timeErr;

//input into sql database
//check that all conditions are met by error message being empty
if ($nameErr == "" && $phoneErr == "" && $streetNumErr == "" && $streetNameErr == ""
    && $dateErr == "" && $timeErr == "") 
{
	//debugging
	//echo "Inside the if!";

	//check connection to database
	if (!$conn) 
	{
		echo "<p>Connection failure</p>";
	} 
	else 
	{
		//generate unique random number for booking reference
		//this while loop checks if the database has the existing random generated booking reference
		//if the random generated booking reference doesn't exist in the database, then exit while loop
		do 
		{
			$uniqueNum = rand(1, pow(10, 5) - 1); //generates a number between 1 to 99999 

			//debugging
			//echo "<br>Unique Number: ". $uniqueNum;

			//concat the unique booking reference to be submit onto sql
			if($uniqueNum >= 1 && $uniqueNum <= 9)
			{
				$bookingRef = "BRN0000" . $uniqueNum;
			}
			else if($uniqueNum >= 10 && $uniqueNum <= 99)
			{
				$bookingRef = "BRN000" . $uniqueNum;
			}
			else if($uniqueNum >= 100 && $uniqueNum <= 999)
			{
				$bookingRef = "BRN00" . $uniqueNum;
			}
			else if($uniqueNum >= 1000 && $uniqueNum <= 9999)
			{
				$bookingRef = "BRN0" . $uniqueNum;
			}
			else
			{
				$bookingRef = "BRN" . $uniqueNum; 
			}

			$queryBookingRefExist = "SELECT bookingRef FROM cabsOnline WHERE bookingRef='" . $bookingRef . "'"; //query to send into database

			$existResult = mysqli_query($conn, $queryBookingRefExist); //get result from database

			if (!$existResult) 
			{
				//debugging
				echo "<p>Something is wrong with " . $existResult . "</p>";
			}
		} while (mysqli_num_rows($existResult) != 0); //exit the while loop once a unique random number has been generated

		//debugging
		//echo "<p>Connection Successful</p>";

		$query = "INSERT INTO cabsOnline"
		. "(name, phone, unit_number, street_number, street_name, suburb, destination, pickupDate, pickupTime, status, bookingRef)"
		. "VALUES"
		. "('$name','$phone','$unitNum','$streetNum','$streetName','$suburb','$desSuburb','$pickUpDate','$pickUpTime','Unassigned','$bookingRef')";

		$result = mysqli_query($conn, $query);

		if (!$result) 
		{
			//debugging
			echo "<p>Something is wrong with " . $query . "</p>";
		} 
		else 
		{
			echo "<table>";
			echo "<tr>\n"
			. "<th colspan=\"2\"><h2>Thank you for your booking!</h2></th>\n"
			. "</tr>\n"
			. "<tr><td>Booking reference number:</td>"
			. "<td>" . $bookingRef . "</td></tr>"
			. "<tr><td>Pick-Up Time:</td>"
			. "<td>" . $pickUpTime . "</td></tr>"
			. "<tr><td>Pick-Up Date:</td>"
			. "<td>" . $pickUpDate . "</td></tr>"
			. "</table>";
		}

	//close connection
	mysqli_close($conn);
	}
}