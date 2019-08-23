<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="your name here">
	<title>Flight</title>
	<!-- 

		<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" crossorigin="anonymous"> -->
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" >
		<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" >
	</head>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Flight Indent</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<form onsubmit="return false;" class="form-inline my-2 my-lg-0">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"  name="ident" id="ident_text" value="GLF4">
				<button class="btn btn-outline-success my-2 my-sm-0" id="go_button" name="go" type="submit">Search</button>
			</form>
		</div>
	</nav>

	<body>
		<div class="content">
			<div class="jumbotron p-2 p-md-5">
				<div class="row">

					<div class="col-md-6 px-0">
						<div id="results"></div>
						<div id="map_div" style="height: 400px;width: auto;border: 1px solid"></div>
					</div>

					<div class="col-md-6">
						<div class="card flex-md-row mb-4 shadow-sm h-md-250">
							<div class="card-body flex-column align-items-start">
								<strong class="d-inline-block mb-2 text-primary">Info Status</strong>

								<div class="row">
									<div class="col-md-12">
										<div id="hasiljson">
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<table class="table table-striped table-bordered dtabel">
											<thead>
												<tr>
													<th>No</th>
													<th>Latitude</th>
													<th>Longitude</th>
												</tr>
											</thead>
											<tbody id="tway">
											</tbody>
										</table>

									</div>
								</div>


							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mb-2">
				<div class="col-md-12">
					<div class="card flex-md-row mb-4 shadow-sm h-md-250">
						<div class="card-body flex-column align-items-start">
							<strong class="d-inline-block mb-2 text-success">Info Track Flight</strong>

							<div class="row">
								<div class="col-md-12">
									<div id="hasiljson">
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hovered dtabel">
										<thead>
											<tr>
												<th>No</th>
												<th>Altitude</th>
												<th>Altitude Change</th>
												<th>Altitude Feet</th>
												<th>Altitude Status</th>
												<th>Ground Speed</th>
												<th>Heading</th>
												<th>Latitude</th>
												<th>Longitude</th>
												<th>Timestamp</th>
												<th>Update Type</th>
											</tr>
										</thead>
										<tbody id="ttrack">
										</tbody>
									</table>

								</div>
							</div>


						</div>
					</div>
				</div>

			</div>
		</div>

	</body>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.3.1.js"></script> 
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 


<!-- https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js
https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js -->


<script type="text/javascript">
	$(document).ready(function() {
		$('.dtabel').DataTable(
		{
			dom: 'Bfrtip',
			buttons: [
			'copyHtml5',
			'excelHtml5',
			]
		} 
		);

		$('#go_button').click(function() {
			$.ajax({
				url: 'query.php',
				data: {
					ident: $('#ident_text').val()
				},
				success: function(response) {
					if (response.error) {
						alert('Failed to decode route: ' + response.error);
						return;
					}
					var trackres = response.trackflight.GetFlightTrackResult;

					$('#hasiljson').html(

						`<div class="row">
						<div class="col-md-5">
						Tanggal
						</div>
						<div class="col-md-7">
						`+response.date+`
						</div>
						</div>
						<div class="row">
						<div class="col-md-5">
						Destinasi / Nama Destinasi
						</div>
						<div class="col-md-7">
						`+response.destination+` / `+response.destination_name+`
						</div>
						</div>
						<div class="row">
						<div class="col-md-5">
						FlightID
						</div>
						<div class="col-md-7">
						`+response.faFlightID+`
						</div>
						</div>
						<div class="row">
						<div class="col-md-5">
						Indent
						</div>
						<div class="col-md-7">
						`+response.ident+`
						</div>
						</div>
						<div class="row">
						<div class="col-md-5">
						Origin / Nama Asal
						</div>
						<div class="col-md-7">
						`+response.origin+` / `+response.origin_name+`
						</div>
						</div>`

						);

					$("#tway").html("");
					var no = 0;
					$.each(response.waypoints, function (i,cb) {
						no++;
						var xhtml = '';
						xhtml+=`
						<tr>
						<td>`+no+`</td>
						<td>`+cb.lat+`</td>
						<td>`+cb.lng+`</td>
						</tr>`;
						$('#tway').append(xhtml);
					});



					$("#ttrack").html("");
					var nid = 0;
					$.each(trackres.tracks, function (i,cb) {
						nid++;
						var xhtml = '';
						xhtml+=`
						<tr>
						<td>`+nid+`</td>
						<td>`+cb.altitude+`</td>
						<td>`+cb.altitude_change+`</td>
						<td>`+cb.altitude_feet+`</td>
						<td>`+cb.altitude_status+`</td>
						<td>`+cb.groundspeed+`</td>
						<td>`+cb.heading+`</td>
						<td>`+cb.latitude+`</td>
						<td>`+cb.longitude+`</td>
						<td>`+cb.timestamp+`</td>
						<td>`+cb.update_type+`</td>
						</tr>`;
						$('#ttrack').append(xhtml);
					});




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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyAogXD-AHrsmnWinZIyhRORJ84bgLwDPpg"></script>

</html>