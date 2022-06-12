<!-- Cameron Graham 19084582
server side request for admin 
Connect to database, search for bookings that follow query parameters
Display table of the booking/s with 'Assign' button -->
<?php
//connect to database
require_once('../../conf/sqlinfo.php');
$conn = @mysqli_connect($sql_host, $sql_user, $sql_pass);
if (!$conn) {
    die("<p>Database connection failure.</p> " . "<p>Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error() . "</p>");
}
$dbSelect = @mysqli_select_db($conn, $sql_db);
if (!$dbSelect) {
    die("<p>Connection to " . $sql_db . " was a failure.</p> " . "<p>Error code " . mysqli_errno($conn) . ": " . mysqli_error($conn) . "</p>");
}

//check for table
$searchTable = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'bookings'";
$searchQuery = mysqli_query($conn, $searchTable);

//if table does not exist 
if (mysqli_num_rows($searchQuery) == 0) {
    echo "<p>There are no bookings yet!</p>";
} else {
    //get search result from html input
    $bsearch = $_POST['bsearch'];
    //set a counter for identying rows used in admin.js
    $rowCounter = 0;

    //if empty search result
    if ($bsearch == "") {
        //time range for next 2 hours
        $currentDateTime = date("Y-m-d H:i:s");
        $futureDateTime = date("Y-m-d H:i:s", strtotime('+2 hours'));

        //find unassigned bookings within the next 2 hours
        $query = "SELECT * FROM bookings WHERE status LIKE 'Unassigned' AND CONCAT(date, ' ', time) > '$currentDateTime' AND CONCAT(date, ' ', time) < '$futureDateTime'";
        $searchResult = mysqli_query($conn, $query);

        //no bookings
        if (mysqli_num_rows($searchResult) == 0) {
            echo "<p>No unassigned bookings within 2 hours</p>";
        } else {
            //show table
            echo "<table width='100%' border='1'>";
            echo "<tr><th>Booking Reference Number</th><th>Customer Name</th><th>Phone</th><th>Pickup Suburb</th>
            <th>Destination Suburb</th><th>Pickup and Time</th><th>Status</th><th>Assign</th></tr>";
            $row = mysqli_fetch_assoc($searchResult);
            while ($row) {
                echo "<tr><td>", $row['bookID'], "</td>";
                echo "<td>", $row['cname'], "</td>";
                echo "<td>", $row['phone'], "</td>";
                echo "<td>", $row['sbname'], "</td>";
                echo "<td>", $row['dsbname'], "</td>";
                //concat date and time
                $date = date_create($row['date']);
                $time = date_create($row['time']);
                echo "<td>", date_format($date, 'd/m/Y'), " ",  date_format($time, 'H:i'), "</td>";
                //use rowcounter to identify the position in the table
                echo "<td id='r$rowCounter'>", $row['status'], "</td>";

                //passable variable of booking ref num
                $bID = $row['bookID'];
                //give an id to the button to identify positon in table
                echo '<td><input id="b' . $rowCounter . '" name="abutton" type="button" onClick="assignBooking(\'' . 'updateAdmin.php' . '\', \'' . 'confirm' . '\', \'' . $bID . '\', \'' . $rowCounter . '\')" value="Assign" /></td></tr>';

                // get next row
                $row = mysqli_fetch_assoc($searchResult);
                // increment counter
                $rowCounter++;
            }
            echo "</table>";
            mysqli_close($conn);
        }
    } else {
        //exact match of search
        //seach table for booking
        $query = "SELECT * FROM $sql_tble WHERE bookID LIKE '$bsearch'";
        $searchResult = mysqli_query($conn, $query);

        //no booking
        if (mysqli_num_rows($searchResult) == 0) {
            echo "<p>Booking not found! Please try a different refernce number.</p>";
        } else {
            //display table
            echo "<table width='100%' border='1'>";
            echo "<tr><th>Booking Reference Number</th><th>Customer Name</th><th>Phone</th><th>Pickup Suburb</th>
                <th>Destination Suburb</th><th>Pickup and Time</th><th>Status</th><th>Assign</th></tr>";
            $row = mysqli_fetch_assoc($searchResult);
            while ($row) {
                echo "<tr><td>", $row['bookID'], "</td>";
                echo "<td>", $row['cname'], "</td>";
                echo "<td>", $row['phone'], "</td>";
                echo "<td>", $row['sbname'], "</td>";
                echo "<td>", $row['dsbname'], "</td>";
                //concat the dat and time
                $date = date_create($row['date']);
                $time = date_create($row['time']);
                echo "<td>", date_format($date, 'd/m/Y'), " ",  date_format($time, 'H:i'), "</td>";
                //use rowcounter to identify the position in the table
                echo "<td id='r$rowCounter'>", $row['status'], "</td>";

                //passable variable of booking ref num
                $bID = $row['bookID'];
                //variable for checking if the button should be clickable
                $statusResult = $row['status'];
                //show a clickable button if booking is unassigned
                if ($statusResult == 'Unassigned') {
                    //give an id to the button to identify positon in table
                    echo '<td><input id="b' . $rowCounter . '" name="abutton" type="button" onClick="assignBooking(\'' . 'updateAdmin.php' . '\', \'' . 'confirm' . '\', \'' . $bID . '\', \'' . $rowCounter . '\')" value="Assign" /></td>';
                } else {
                    echo '<td><input id="b' . $rowCounter . '" name="abutton" type="button" onClick="assignBooking(\'' . 'updateAdmin.php' . '\', \'' . 'confirm' . '\', \'' . $bID . '\', \'' . $rowCounter . '\')" value="Assign" disabled="true" /></td>';
                }
                echo '</tr>';
                $row = mysqli_fetch_assoc($searchResult);
            }
            echo "</table>";
            mysqli_close($conn);
        }
    }
}
?>