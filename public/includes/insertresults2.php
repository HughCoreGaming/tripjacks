<?php
include 'gen.php';
if (!is_authed() || !browser()) {
    header("location:login.php");
}


        $toll = 0;
			
$ids = $_POST["ids"];
$date = get_date($ids);
$season = get_season_from_id($ids);
$vid = $_POST["vid"];

include 'dat.php';

$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
} else {
	$sql = "INSERT INTO games (season, date, venueid, venuetotal) 
		VALUES ('".$season."', '".$date."', '".$vid."', '".$toll."')";
	

	$res = mysqli_query($mysqli, $sql);

	if ($res === TRUE) {
	   	echo "";
	} else {
		printf("Could not insert record: %s\n", mysqli_error($mysqli));
	}

	mysqli_close($mysqli);
}


$count = $_POST["count"];
$counter = 1;
$dec = 1;

$stack = array();
$stack = $_POST["playerid"];

$many = $_POST["count"];


    $total = $many * 200;


    $percent[1] = "5";
    $percent[2] = "6";
    $percent[3] = "7";
    $percent[4] = "9";
    $percent[5] = "10";
    $percent[6] = "13";
    $percent[7] = "20";
    $percent[8] = "30";


    $counter = 1;



    while ($counter <= $many) {

        $sort = array_pop($percent);


        if ($counter >= 9) {

            $score = 50;
        } else {

            $score = $total * $sort / 100;
        }

        $playerid = array_pop($stack);
        $attendanceid = get_attendanceid($playerid,$vid);


include 'dat.php';

$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
} else {
	
	$amount_of = "SELECT Count(*) AS ThisID
	FROM venuetotals WHERE season ='".$season."'";


	$result = mysqli_query($mysqli, $amount_of);

	if ($result) {
		while ($newArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			
			$display8 = $newArray['ThisID'];
			
			
			
	   	}
	} else {
		printf("Could not retrieve records: %s\n", mysqli_error($mysqli));
	}

	mysqli_free_result($result);
	mysqli_close($mysqli);
}





$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
} else {
	$q="SELECT * FROM games WHERE date = '".$date."' ORDER BY gameid DESC LIMIT 1";
	
	$result = mysqli_query($mysqli, $q);

	if ($result) {



		while ($newArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			
			$gameid = $newArray['gameid'];
										
		
			
	   	}


	} else {
		printf("Could not retrieve records: %s\n", mysqli_error($mysqli));
	}

	mysqli_free_result($result);
	mysqli_close($mysqli);
}




$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
} else {
	$sql = "INSERT INTO results (score, gameid, attendanceid, playerid) 
		VALUES ('".$score."', '".$gameid."', '".$attendanceid."', '".$playerid."')";
	

	$res = mysqli_query($mysqli, $sql);

	if ($res === TRUE) {
	   	echo "";
	} else {
		printf("Could not insert record: %s\n", mysqli_error($mysqli));
	}

	mysqli_close($mysqli);
}




$counter++;

    }




header('Location:admin.php');





?>

