Cameron Graham 19084582
Files Included:
admin.html
admin.js
admin.php
updateAdmin.php
booking.html
booking.js
booking.php
xhr.js

Additional Files:
readme.txt
sqlcommands.txt
style.css


booking.html
Booking Form
User is required to enter a valid input into all required input fields.
On a submissions with invalid value the user is prompted where the error is coming from with a brief explanation of the error.
Required input fields that are invalid will have a lith red colour.
Date and Time fiedls have the current date and time as default values.

On submission of a valid bookings the users is prsrented with a booking conrifmation: the bookigns refecen  numerb adn the pickup date adn time.
All bokigsn information is sent ot egh database.
The form is then reset for a new booking


admin.html
Admin Form
User is presented with a search field.
If the user leaves this fiedl blank all unassgiedn bookigns within the next 2 hours are shown.
User can assign themselves to a booking clicking teh 'assig button', and is shown the cofniamtion message when done so.

Alternatively the user can inout a booking refernce number in the input field;
If the booking exists the user is then presentedwith the booking associated with the search
The user can assign themslves to the booking if the booking is yet to be asisgned, otherwise the assign button will not be avaible yto click

