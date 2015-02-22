# Documentation for 'kinkaidcs.herokuapp.com' Web Application



## Utility Files
These files are low-level scripts that set-up things like the sql database, initialize important variables, and check if the user is logged in as a user or not. 

**functions.php**
- Is included by 'headers.php', which is then included in almost every page in the application. As a result, all of the functions in this file are global. 
- The main importance is the connection to the sql database by this file, which allows other php files to query the database and manipulate data.

**headers.php**
- Includes 'functions.php', and is included by many other php files in the application. This file outputs the main CSS stylesheets and javascript files needed to load each page correctly (bootstrap, jquery, etc.). 
- It checks if there are session variables for the user like a 'userid'. If they exist, the user is logged in already, and headers.php sets a variable $logged in to true. Some important utility variables are also initialized, like the user's first name and last name.
- If there are no session variables, $loggedin will be false.

**footers.php**
- Is added to the end of signup.php, and presents a link for an admin to log-in with to get into the dashboard, but also allows a $loggedin user to log out, sending them to 'logout.php'


## Client-Side
These files are what ordinary users see.

**index.php**
This is just there so that the server recognizes the webpage as a php page and loads the home page correctly. There is zero php processing going on in index.php

**signup.php**
The only php page that users will interact with.
- It includes 'headers.php', so it can access the sql databases and also use .css and .js files to load the page.
- It adds some of its own styles and scripts specific to this page
	- blockui.js 'blocks' the user input when the user is signing up for an event so that everything executes correctly (it takes a little bit of time to sign up because a confirmation email is sent to the user)
	- A handler for the 'volunteer' button is created. It runs the ajax script 'signupajax.php', and after finishing gives the an alert.
- It outputs a table of events, ordered by date from nearest to furthest. Each event gets its own table row and information is stored in <td>s

## Admin side
These pages are for an admin who correctly enters an authorization username and password after clicking the 'Part of the council? Log in here' link. 
**Every page in this category is password protected by the same security, so one MUST correctly answer the username and password to use these files.**

**dashboard.php**
The main admin window to see every event, create events, and download event data into an excel file. 
- Includes 'header.php', giving access to sql tables.
- Adds some page-specific styles, mainly the coloring of two buttons
- Has specific 'if' statements to handle post data from pages that submit to 'dashboard.php'.
	- EDITEVENT handler
		- Handles POST data from editevent.php, which edits existing details for created events.
		- updates the sql database with the new data, and displays an alert that the admin has modified the event information.
	- CLOSEEVENT handler
		- Handles POST data from eventinfo.php, when the user clicks the 'Close Event' button.
		- It adds the default amount of credits of an event to the total credit count of every student signed up for that event.
		- It sets the value 'closed' in the sql database to true (1) so that it does now show up in the 'active events' table
		- Does a redirect so that POST data cannot be submitted twice if page is refreshed.
	- DELETEEVENT handler
		- Handles POST data from eventinfo.php for events that are closed. The user has clicked the 'Delete Event' button, and wishes to completely eliminate data for that event.
		- Deletes all entries for this event from the 'signups' sql table, and deletes the event from table 'events'.
		- Does a redirect so that POST data cannot be submitted twice if page is refreshed.
	- CREATEEVENT handler
		- Handles POST data from newevent.php, which is the event creation form.
		- If there is an error, alerts the admin.
		- Otherwise, inserts a new entry into 'events' table with all of the POST data from the form.
		- Does a redirect so that POST data cannot be submitted twice if page is refreshed.
**END HANDLERS**
- Outputs a table of ACTIVE events, ordered from nearest to furthest in the future.
	- Clicking on 'view details' button sends you to eventinfo.php
- Outputs another table of CLOSED events, ordered by date in descending order.
	- Clicking on 'view details' button sends you to eventinfo.php, **but adds an extra hidden input field with name 'PAST', so that eventinfo.php knows it is a closed event.**

## eventinfo.php
This is the page the admin sees when he/she clicks on any of the 'view details' buttons on dashboard.php
- Includes 'headers.php', granting access to the sql databases.
- Generates some specific styles, like unique button colors
- Line 13 checks if it is an active event: the button from dashboard.php either sends 'eventid' or 'PAST_eventid'. Depending on whichever one is sent, some aspects are added/removed.
	-  **ACTIVE**
		- Outputs navbar
		- Outputs 3 buttons- 'Edit Event Details', 'Print Attendance', 'Email Students & Parents'
			- Each button opens another PHP page serving the title function.
		- Sets up handlers to handle ajax requests that occur on this page
			- When the 'Delete volunteer' button next to each volunteer is clicked, it runs the ajax script 'delete.php' and then adds the name of the deleted student into the drop down list to make it re-addable.
			- When the 'Add Volunteer' button is clicked below the drop down bar, it runs the script 'addvolunteer.php' and inserts it into the table of volunteers.
		- Outputs a table of current volunteers in the event - "SELECT * FROM signups WHERE eventid = currenteventid, and then iterates through each studentid and displays their information.
	- **PAST**
		- Adds a script to the 'Delete Event' button, basically it will just submit the corresponding <form> when clicked.
		- Outputs a navbar
		- Outputs a 'Delete Event' button
			- Sends post data to 'dashboard.php' telling it to completely delete the event and all of its data.
		- Outputs a table of the volunteers that signed up for this event (when you close an event the signups are still saved)

## editevent.php
This is the page the admin see when he clicks the 'Edit Event Details' button in 'eventinfo.php'
- Includes "header.php"
- Sets up javascript that will check if certain form fields are filled. If they are not, then the user will not be allowed to submit the form. These fields are the crucial ones (name, date, etc.)
- Outputs the same form as newevent.php, but fills in the current event's values so that the admin can edit them and save the changes

## printattendance.php
- Opens a new tab
- Generates a simple html page with a title for the event title and a table for all the volunteers, outputting the basic info of each volunteer.
- automatically prompts the browser to print the file
- will automatically close after leaving the taab

## emailvolunteers.php


## addvolunteer.php
Ajax script called when adding a volunteer in 'eventinfo.php'
it is sent a student ID and an event ID, and adds these values into a new row in the 'signups' database. It then returns information for the student so 'eventinfo.php' can output the student details correctly (grade, advisor, etc.)






dashboard.php



delete.php

