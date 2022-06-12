// Cameron Graham 19084582
// POST method requests and response for admin
var xhr = createRequest();

//use admin form search value to find bookings
//accept empty or booking ref 'BRNxxxxx'
// display table of booking/s from admin.php
// allow user to assign the booking and display the confirmation
function searchBooking(dataSource, contName, divID, bsearch) {
	//regex for admin search - accept empty or booking ref
	var refPattern = new RegExp(/^$|^BRN[0-9]{5}$/);
	if(bsearch.toString().match(refPattern)){
		document.getElementById("search").style.backgroundColor = 'white';
		if (xhr) {
			//area to display table
			var obj = document.getElementsByClassName(contName)[0];
			//message area
			var message = document.getElementById(divID);
			//pass search value to admin.php
			var requestbody =
				"bsearch=" + encodeURIComponent(bsearch);

			xhr.open("POST", dataSource, true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4 && xhr.status == 200) {
					//clear message
					message.innerHTML = "";
					//display table
					obj.innerHTML = xhr.responseText;
				} 
			}; 
			xhr.send(requestbody);
		} 
	}else{
		document.getElementById("search").style.backgroundColor = 'LightCoral';
		window.alert("Booking reference format is invalid. Please use 'BRN' followed by 5 digits or leave blank for unassigned bookings within 2 hours.")
	}
}

//updating the assignment of bookings by booking reference
//send booking ref to updateAdmin.php
function assignBooking(dataSource, pID, bID, rowCounter){
if (xhr) {
		//area to display confirmation message
		var obj = document.getElementById(pID);
		//get reference to 'status' of the booking that is being assigned
		var rStatus = document.querySelector("#r" + rowCounter + "");
		//get reference to the button of the booking tht is being assigned
		var rButton = document.querySelector("#b" + rowCounter + "");
		//pass booking ref and rowcounter to updateAdmin.php
		var requestbody =
			"bID=" + encodeURIComponent(bID) + "&rowCounter=" + encodeURIComponent(rowCounter);
			
		xhr.open("POST", dataSource, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				//give confirmation message
				obj.innerHTML = xhr.responseText;
				//change status and disable button of selected booking
				rStatus.innerText= "Assigned";
				rButton.disabled = true;
			} 
		}; 
		xhr.send(requestbody);
	} 
} 