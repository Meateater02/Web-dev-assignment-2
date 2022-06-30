/* 
    Davina Phan
    1300285
    COMP721 
    Assignment 2
*/

//This JavaScript contains functions for admin.html
//It sends requests to php files and brings the response back to admin.html
//Functions here include getting the results back from the search input and for the assign button

//function to create a xhr object for get data
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

//function to request data from server to display onto the html via get method
function getData()  
{
	var xhr = createXHRObject();
	if(xhr) 
	{
		var bsearch = document.getElementById('bsearch');
		var targetTable = document.getElementById('targetTable');
		var url = "admin.php?bsearch=" + bsearch?.value || "''";
		console.log('bsearch', 
		{
			bsearch,
			targetTable,
			url
		})
		
		xhr.open("GET", url, true);

		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState == 4 && xhr.status == 200) 
			{
				console.log('response ', xhr.responseText)
				targetTable.innerHTML = xhr.responseText;
			}
		}
		
		xhr.send(null);
	}
}

// function to assign cab via post method
function assign(bookingRef)
{ 
	//debugging
	//console.log('bookingRef: ', bookingRef) //logs

	//create xhr object
	const xhr = createXHRObject();

	if(xhr) 
	{
		// build post url
		const url = "update.php"
		const requestbody = "id="+encodeURIComponent(bookingRef)

		xhr.open("POST", url, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() 
		{
			if (xhr.readyState == 4 && xhr.status == 200) 
			{
				const response = xhr.responseText;

				console.log('assign response: ', response)

				//injecting response into booking.html page
				const reference = document.getElementById("updateMessage");
			
				if(response) 
				{
					reference.innerHTML = response;
				}
			
				getData();
			}
		}

		xhr.send(requestbody);
	}
}
