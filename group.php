<?php
// Create connection
$con=mysqli_connect("sql5c40a.carrierzone.com","highrevpro100550","woodhouse","phpmy1_highrevproductions_com");
$codeCase = $_GET["tCode"];
$queryVar = $_GET["qCode"];
 
// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

switch ($codeCase) {
    case "1": 
    	$sql = "SELECT g.groupID, u.id, u.firstName, u.lastName, u.fbID, g.split, g.locked, g.message 
FROM Groups g INNER JOIN User u ON (g.userID = u.id) WHERE g.groupID IN (SELECT gr.groupID FROM Groups gr LEFT JOIN User us ON (gr.userID = us.id)  WHERE us.fbID = $queryVar)";
        break;
    case "2":
        $sql = "SELECT g.*, u.fbID, u.picURL
FROM Groups g INNER JOIN User u ON (g.userID = u.id) INNER JOIN Bids b ON (b.initiator = g.groupID || b.extraGroup1 = g.groupID || b.extraGroup2 = g.groupID || b.extraGroup3 = g.groupID)
WHERE b.`id` = $queryVar";
        break;
    case "3":
        $sql = "SELECT o.name as 'offerName', l.name as 'locationName','0' as hitCount FROM Offer o LEFT JOIN Bids b ON (o.id = b.offer) LEFT JOIN Location l ON (o.locationID = l.id) WHERE b.initiator = $queryVar";
        break;
    case "4":
        $sql = "SELECT u.id FROM User u WHERE u.fbID = $queryVar";
        break;
    default:
        $sql = "SELECT g.groupID, u.id, u.firstName, u.lastName, u.fbID, g.splitType, g.split, g.locked 
FROM Groups g INNER JOIN User u ON (g.userID = u.id) WHERE g.groupID IN (SELECT gr.groupID FROM Groups gr WHERE gr.userID = $queryVar)";
}

//$sql = "SELECT g.groupID, u.id, u.firstName, u.lastName, u.fbID, g.splitType, g.split, g.locked 
//FROM Groups g INNER JOIN User u ON (g.userID = u.id)";
//WHERE g.groupID IN (SELECT groupID FROM Groups WHERE userID = (SELECT id FROM User WHERE fbID = '".$queryVar."'))";

// This SQL statement selects ALL from the table 'Locations'
//$sql = "SELECT * FROM Groups WHERE userID = '7'"

// Check if there are results

if ($result = mysqli_query($con, $sql))
{
	// If so, then create a results array and a temporary one
	// to hold the data
	$resultArray = array();
	$tempArray = array();
 
	// Loop through each row in the result set
	while($row = $result->fetch_object())
	{
		// Add each row into our results array
		$tempArray = $row;
	    array_push($resultArray, $tempArray);
	}
 
	// Finally, encode the array to JSON and output the results
	echo json_encode($resultArray);
}
 
// Close connections
mysqli_close($con);
?>