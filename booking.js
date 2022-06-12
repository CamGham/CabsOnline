// Cameron Graham 19084582
// POST method requests and response for booking
var xhr = createRequest();

//use values from booking form to create a new booking
//check required fields
//send values to booking.php to add the booking to the database and display a confirmation
function postBooking(dataSource, pID, cname, phone, unumber, snumber, stname, sbname, dsbname, date, time) {
	//check all requried fiedls are not empty
	if (cname == "" || cname == null) {
		document.getElementById("cname").style.backgroundColor = 'LightCoral';
		window.alert("Name not filled");
		return;
	}
	document.getElementById("cname").style.backgroundColor = 'white';
	if (phone == "" || phone == null) {
		document.getElementById("phone").style.backgroundColor = 'LightCoral';
		window.alert("phone not filled");
		return;
	}
	document.getElementById("phone").style.backgroundColor = 'white';
	//phone number is numeric
	if (isNaN(phone)) {
		document.getElementById("phone").style.backgroundColor = 'LightCoral';
		window.alert("Phone number not a number");
		return;
	}
	document.getElementById("phone").style.backgroundColor = 'white';
	//phone number is 10-12 digits
	if (phone.toString().length < 10 || phone.toString().length > 12) {
		document.getElementById("phone").style.backgroundColor = 'LightCoral';
		window.alert("Phone number length is insufficient");
		return;
	}
	document.getElementById("phone").style.backgroundColor = 'white';
	if (snumber == "") {
		document.getElementById("snumber").style.backgroundColor = 'LightCoral';
		window.alert("Street number not filled");
		return;
	}
	document.getElementById("snumber").style.backgroundColor = 'white';
	if (stname == "") {
		document.getElementById("stname").style.backgroundColor = 'LightCoral';
		window.alert("Street name not filled");
		return;
	}
	document.getElementById("stname").style.backgroundColor = 'white';

	//get current date
	var currentDate = new Date();
	var day = currentDate.getDate().toString().padStart(2, 0);
	var month = (currentDate.getMonth() + 1).toString().padStart(2, 0);
	var year = currentDate.getFullYear();
	//get current time
	var hour = currentDate.getHours();
	var min = currentDate.getMinutes().toString().padStart(2, 0);

	//variables for date and time
	var today = year + '-' + month + '-' + day;
	var now = hour + ":" + min;
	var currentDateTime = today + " " + now;

	//variables for comparing user input date and time
	var datepickerValue = document.getElementById("theDate").value;
	var timepickerValue = document.getElementById("theTime").value;
	var selectedDateTime = datepickerValue + " " + timepickerValue;

	//if users date and time is less than now
	if (selectedDateTime < currentDateTime) {
		//make current day and time look same as users input values
		var displayToday = day + "/" + month + '/' + year;
		var displayNow;
		var hour24 = hour;

		//display error message
		if (hour24 > 12) {
			var hour12 = hour24 % 12;
			displayNow = hour12 + ":" + min;
			document.getElementById("theDate").style.backgroundColor = 'LightCoral';
			document.getElementById("theTime").style.backgroundColor = 'LightCoral';
			window.alert("Date + Time cannot be earlier than the current time: " + displayToday + " " + displayNow + "pm");
		}
		else {
			var hour12 = hour24;
			displayNow = hour12 + ":" + min;
			document.getElementById("theDate").style.backgroundColor = 'LightCoral';
			document.getElementById("theTime").style.backgroundColor = 'LightCoral';
			window.alert("Date + Time cannot be earlier than the current time: " + displayToday + " " + displayNow + "am");
		}
		return;
	}
	document.getElementById("theDate").style.backgroundColor = 'white';
	document.getElementById("theTime").style.backgroundColor = 'white';

	//all required fields are valid
	//start request
	if (xhr) {
		//area to display confirmation
		var obj = document.getElementById(pID);
		//send all input field values to booking.php
		var requestbody =
			"cname=" + encodeURIComponent(cname) + "&phone=" + encodeURIComponent(phone) +
			"&unumber=" + encodeURIComponent(unumber) + "&snumber=" + encodeURIComponent(snumber) +
			"&stname=" + encodeURIComponent(stname) + "&sbname=" + encodeURIComponent(sbname) +
			"&dsbname=" + encodeURIComponent(dsbname) + "&date=" + encodeURIComponent(date) +
			"&time=" + encodeURIComponent(time);

		xhr.open("POST", dataSource, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				//confirmation message
				obj.innerHTML = xhr.responseText;
			}
		};
		xhr.send(requestbody);

		//reset input fields after a booking is completed
		document.getElementById("bookingform").reset();
		//reset date and time to current date and time
		getDate();
	}
}

//set date and time inputs to current date and time
function getDate() {
	var date = new Date();
	var day = date.getDate().toString().padStart(2, 0);
	var month = (date.getMonth() + 1).toString().padStart(2, 0);
	var year = date.getFullYear();

	var hour = date.getHours();
	var min = date.getMinutes().toString().padStart(2, 0);

	var today = year + '-' + month + '-' + day;
	var now = hour + ":" + min;

	var datepicker = document.getElementById("theDate");
	datepicker.value = today;
	datepicker.setAttribute("min", today);

	var timepicker = document.getElementById("theTime");
	timepicker.value = now;
}

//onload of window get the current date and time and set input fields
window.onload = function () {
	getDate();
};