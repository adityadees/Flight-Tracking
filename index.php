<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="your name here">
	<title>Flight Route Decoder</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#go_button').click(function() {
			$.ajax({
				url: 'query.php',
				data: {
					ident: $('#ident_text').val()
				},
				success: function(response) {
					console.log(response);
					if (response.error) {
						alert('Failed to decode route: ' + response.error);
						return;
					}

					$('#results').text('Flight ' + response.ident + ' from ' + response.origin_name + 
  								' (' + response.origin +')' + ' to ' + response.destination_name + ' (' + response.destination +')' + 
  								' on ' + response.date);

					var bounds = new google.maps.LatLngBounds();
					response.waypoints.forEach(point => {
						if (point.lat && point.lng) {
							bounds.extend(point);
						}
					});

					var map = new google.maps.Map(document.getElementById('map_div'), {
						initialZoom: true,
						mapTypeId: 'terrain'
					});

					map.fitBounds(bounds);

					var flightPath = new google.maps.Polyline({
						path: response.waypoints,
						geodesic: true,
						strokeColor: '#FF0000',
						strokeOpacity: 1.0,
						strokeWeight: 2
					});

					var originMarker = new google.maps.Marker({
						position: response.waypoints[0],
						map: map
					});

					var destinationMarker = new google.maps.Marker({
						position: response.waypoints[response.waypoints.length - 1],
						map: map
					});

					flightPath.setMap(map);
				}
			})
		});
	})
	</script>
</head>
<body>
	<form onsubmit="return false;">
		<p>Flight Indent
			<input type="text" name="ident" id="ident_text" value="UAL423" />
			<input type="submit" id="go_button" name="Go" />
		</p>
	</form>

	<div id="results"></div>
	<div id="map_div" style="width: 600px; height: 450px;"></div>
	<script async defer src="https://maps.googleapis.com/maps/api/js">
	</script>
</body>
</html>