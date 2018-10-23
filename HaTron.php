<?PHP
# particle-map.php
// GPS UNIT


$deviceID = "YOUR_PARTICLE_DEVIC_ID";
$access_token = "YOUR_PARTICLE_ACCESS_TOKEN";

$url = "https://api.particle.io/v1/devices/$deviceID/";
$formed_url ='?access_token='.$access_token;
$variable_name = "STU";

$headers = array( 
  	"GET /v1/devices/".$variable_name.$formed_url." HTTP/1.1",
  	"Host: api.particle.io");

  	// setup and make HTTP GET REQUEST
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL,$url.$variable_name.$formed_url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return output
	$retrievedhtml = curl_exec ($ch); 
	curl_close($ch); 
	$json = json_decode($retrievedhtml,true);

	// see if there was an error connecting with electron
	if ($json['error'] != "") {
	    echo ("ERROR = " . $json['error'] . "<br>");
	} else {
	   // read the data into a variable
	   $DATA = $json['result'];
	   // output to screen
	   echo ("<b>result: </b>" . $DATA . "<br>");
	   // split data into array  is comma delimited
	   $pieces = explode(",", $DATA);
	   // A = valid, V = not valid
	   $status = $pieces[2];
	   if ($status == "V") {
			echo ("Data not valid<br>Can the GPS unit see the sky?");
	   } else {
			// put data in variables
			$LAT = $pieces[3];
			$LON = $pieces[5];
			$EW = $pieces[6];
			// Convert LAT
			$deg = substr($LAT, 0, 2);
			$min = substr($LAT, 2, 8);
			$sec = '';
			$resultLAT = $deg+((($min*60)+($sec))/3600);
			print ("Latitude " . $resultLAT . "<br>");

			// Convert Longitude
			$deg = substr($LON, 0, 3);
			$min = substr($LON, 3, 8);
			$sec='';
			$resultLON = $deg+((($min*60)+($sec))/3600);

			// Is it East or West
			if ($EW == "W") {
			   $resultLON = $resultLON * -1;
			}
			
			print ("Longitude " . $resultLON . "<br>");
		}
	}
?>

<iframe
width="400"
height="400"
frameborder="0" style="border:0"
src="https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAP_API_KEY
&q=<?=$resultLAT?>,<?=$resultLON?>">
</iframe>

