<!-- Cameron Graham 19084582
server side request for booking 
Connect to databse, if not table - create and add the first booking
If table exist grab highest reference number and increase, add booking
Display Booking confirmation -->
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

$bookID;
//if table does not exist create
if (mysqli_num_rows($searchQuery) == 0) {
    $createTable = "CREATE TABLE $sql_tble (bookID varchar(8) NOT NULL UNIQUE, bookDate date NOT NULL, bookTime time NOT NULL,
        status varchar(255), cname varchar(255) NOT NULL, phone varchar(12) NOT NULL, unumber varchar(255), snumber varchar(255) NOT NULL,
        stname varchar(255) NOT NULL, sbname varchar(255), dsbname varchar(255), date date NOT NULL, time time NOT NULL)";
    $createResult = mysqli_query($conn, $createTable);
    //set fist ID
    $bookID = "BRN00001";
}else{
    //get highest ID num and increment
    $idQuery = "SELECT SUBSTRING(bookID, 4, 5) as numID FROM bookings ORDER BY numID DESC LIMIT 1;";
    $idSearch = mysqli_query($conn, $idQuery);
    $row = mysqli_fetch_assoc($idSearch);
    $id = $row['numID'];
    $id++;
    $numPad = preg_match_all("/[0-9]/", $id);
    for ($i = 0; $i < (5 - $numPad); $i++) {
        $id = "0" . $id;
    }
    //next id
    $bookID = "BRN" . $id;
}

//input field values
$cname = $_POST["cname"];
$phone = $_POST["phone"];
$unumber = $_POST["unumber"];
$snumber = $_POST["snumber"];
$stname = $_POST["stname"];
$sbname = $_POST["sbname"];
$dsbname = $_POST["dsbname"];
$date = $_POST["date"];
$time = $_POST["time"];

//booking details for database
$bookDate = date("Y-m-d");
$bookTime = date("H:i");
$status = 'Unassigned';


//(validation is done client side no need for empty checks)
$query = "INSERT INTO $sql_tble VALUES ('$bookID', '$bookDate', '$bookTime', '$status' , '$cname', '$phone', '$unumber', '$snumber', '$stname', '$sbname', '$dsbname', '$date', '$time');";
$result = mysqli_query($conn, $query);

if ($result) {
    //return booking confirmation to user
    $formDate = date_create($date);
    echo "<p>Thank you for your Booking!</br>
    Booking reference number: ", $bookID, "</br>
    Pickup time: ", $time, "</br>
    Pickup date: ", date_format($formDate, 'd/m/Y'), "</p>";

} else {
    die("<p>Database connection failure.</p> " . "<p>Error code </p>");
}
?>


