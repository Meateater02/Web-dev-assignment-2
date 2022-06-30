/* 
    Davina Phan
    1300285
    COMP721 
    Assignment 2
*/

//This JavaScript contains functions for booking.html that uses the post method to submit data to php file 

//create a xhr object
function createXHRObject() 
{
	var xHRObject = false;
	if (window.XMLHttpRequest)
	{
		xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xHRObject;
}

//this function gets data input by user, post it to book.php, and returns a response back from the server
function booking()
{
	// get data from input
	const name = document.getElementById("cname").value;	
	const phone = document.getElementById("phone").value;
	const unit = document.getElementById("unumber").value;
	const streetNum = document.getElementById("snumber").value;
	const streetName = document.getElementById("stname").value;
	const suburb = document.getElementById("sbname").value;
	const desSuburb = document.getElementById("dsbname").value;
	const date = document.getElementById("bdate").value;
	const time = document.getElementById("time").value;
 
	//for debugging purposes
	console.log(
	{
		name,
		phone,
		unit,
		streetNum,
		streetName,
		suburb,
		desSuburb,
		date,
		time
	})

	//create xhr object
	const xhr = createXHRObject();

	if(xhr) 
	{
		//build post url
		const url = "booking.php"
		const requestbody = "cname="+encodeURIComponent(name)
			+ "&phone="+encodeURIComponent(phone)
			+ "&unumber="+encodeURIComponent(unit)
			+ "&snumber="+encodeURIComponent(streetNum)
			+ "&stname="+encodeURIComponent(streetName)
			+ "&sbname="+encodeURIComponent(suburb)
			+ "&dsbname="+encodeURIComponent(desSuburb)
			+ "&date="+encodeURIComponent(date)
			+ "&time="+encodeURIComponent(time); 

		//set asynchronous 
		xhr.open("POST", url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() 
		{
			// alert(xhr.readyState); 
			if (xhr.readyState == 4 && xhr.status == 200) 
			{
				//debugging
				const response = xhr.responseText;
				console.log('response: ', response)

				//injecting response into booking.html page
				const reference = document.getElementById("reference");
				if(response) 
				{
					reference.innerHTML = response;
				}
			}
		} //end function

		//send request to server side
		xhr.send(requestbody);
	}//end if statement
}//end of booking function
