<!-- Cameron Graham 19084582
Server side request for upading status of booking
Connect to database
Grab booking that has been clicked, update status to assinged in databse
Display confirmation of booking assignment and updated booking details -->
<?php
//connect to databse
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

// if table does not exist 
if (mysqli_num_rows($searchQuery) == 0) {
    echo "<p>Cannot find table!</p>";
}else{
    // get ref num of selected booking 
    $bID = $_POST['bID'];
    
    //select the booking that has been assigned
    $statusSearch = "SELECT * FROM bookings WHERE bookID LIKE '$bID' AND status LIKE 'Unassigned'";
    $statusResult = mysqli_query($conn, $statusSearch);

    //check it exists
    if (mysqli_num_rows($statusResult) == 0) {
        echo "<p>Cannot find booking</p>";
    } else {
        //update the booking status
        $update = "UPDATE `$sql_tble` SET `status`= 'Assigned' WHERE bookID LIKE '$bID'";
        $updateQuery = mysqli_query($conn, $update);

        //display assignment confirmation and the booking detials
        echo "<p>Congratulations! Booking request " . $bID . " has been assigned!</p>";
    }
}
?>


