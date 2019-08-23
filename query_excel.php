<?php
/*https://flightxml.flightaware.com/json/FlightXML3/GetFlightTrack?ident=N198HF-1565953527-dlad-6664977&include_position_estimates=true*/
if (!$_GET['ident']) {
	echo json_encode(array('error' => 'Missing ident in request'));
	return;
}

$ident = $_GET['ident'];
$queryParams = [
	'ident' => $ident,
	'howMany' => 10,
	'offset' => 10
];

$result = [];
header('Content-Type: application/json');

$response = executeCurlRequest('FlightInfoStatus', $queryParams);
if ($response) {
	$flightArray = json_decode($response, true);

	foreach ($flightArray['FlightInfoStatusResult']['flights'] as $flight) {
		if ($flight['actual_departure_time']['epoch'] > 0 && $flight['route']) {
			$result['ident'] = $flight['ident'];
			$result['faFlightID'] = $flight['faFlightID'];
			$result['origin'] = $flight['origin']['code'];
			$result['origin_name'] = $flight['origin']['airport_name'];
			$result['destination'] = $flight['destination']['code'];
			$result['destination_name'] = $flight['destination']['airport_name'];
			$result['date'] = $flight['filed_departure_time']['date'];
			$result['waypoints'] = getFlightRoute($flight['faFlightID']);
			$result['trackflight'] = getTrackFlight($flight['faFlightID']);
			echo json_encode($result);
			return;
		}
	}
} else {
	echo json_encode(['error' => 'Unable to decode flight for ' . $ident]);
}

function getFlightRoute($faFlightID) {
	$result = [];
	if ($response = executeCurlRequest('DecodeFlightRoute', array('faFlightID' => $faFlightID))) {
		$flightPoints = json_decode($response, true);
		foreach ($flightPoints['DecodeFlightRouteResult']['data'] as $point) {
			array_push($result, array('lat' => $point['latitude'], 'lng' => $point['longitude']));
		}
		return $result;
	}
	return "";
}

function getTrackFlight($faFlightID) {
	$result = [];
	if ($response = executeCurlRequest('GetFlightTrack', array('ident' => $faFlightID))) {
		$flightPoints = json_decode($response, true);
/*		foreach ($flightPoints['FlightTrackResult']['data'] as $point) {
			array_push($result, [
				'altitude' => $point['altitude'], 
				'altitude_change' => $point['altitude_change'],
				'altitude_feet' => $point['altitude_feet'], 
				'altitude_status' => $point['altitude_status'],
				'groundspeed' => $point['groundspeed'], 
				'heading' => $point['heading'],
				'latitude' => $point['latitude'], 
				'longitude' => $point['longitude'],
				'timestamp' => $point['timestamp'], 
				'update_type' => $point['update_type'],
			]);
		}*/
		return $flightPoints;
	}
	return "";
}


function executeCurlRequest($endpoint, $queryParams) {

	$username = "adityads";
	$apiKey = "39f64e0f48e010df279a02139e28c89c0b48dadd";
	$fxmlUrl = "https://flightxml.flightaware.com/json/FlightXML3/";

	$url = $fxmlUrl . $endpoint . '?' . http_build_query($queryParams);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$result = curl_exec($ch);
	if ($result) {
		curl_close($ch);
		return $result;
	}
	return "";
}
