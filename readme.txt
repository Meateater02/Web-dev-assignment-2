Davina Phan
1300285
COMP721
Assignment 2


Link to my files on AUT:
http://twt9050.cmslamp14.aut.ac.nz/assign2/


List of all the files in the system:
- booking.html
- booking.php
- booking.js
- admin.html
- admin.php
- admin.js
- admin.css
- update.php


Text files:
- mysqlcommand.txt
- readme.txt (this file)


Description of the system:
This is a booking system that allows the user to book a cab online.

Booking.html
In the booking page, the user has to fill in the form where some fields have requirements to be met before they are allowed to be submitted 
to the database. When the submit button is clicked, the javascript sends the data to the server side (php) via post method, where data 
validation is checked and the appropriate message returned back to the client side. Once the data are validated and requirements are met, 
the server side will submit to the database (sql). A message to confirm booking is sent back to the client side once the booking has been made. 

Admin.html
In the admin page, the user is allowed to search the booking reference number. When the search input is blank, the system will search for 
bookings of 2 hours from now. Search query is passed on via get method, to the server side, where the server queries from the database and 
returns the result back to the client side, through the use of ajax. When no results have been found, the appropriate message is returned to 
the client side. When results are found, a table of results will be displayed and the assign button is available for unassigned bookings to 
be assigned. Bookings that have been assigned will have the button greyed out. The assign button has a function that updates the database via
the post method using ajax. The function also calls the search function so that the table of results get updated and the button will be greyed 
out. 








 

