@extends("app")

@section("customJS")
<script>
	function initMap() {
		var purdueUniv = {lat: 40.4237, lng: -86.9212};
		
		// Create / Zoom Map
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 3,
			center: purdueUniv,
		});
		
		// Create Markers
		var markers = [];
		$.getJSON("/map-data", function(mapData) {
			$.each(mapData, function(key, data) {
				var latLng = new google.maps.LatLng(data.loc_lat, data.loc_lng);
				var infowindow = new google.maps.InfoWindow({ content: "<h4>"+data.name+'</h4><p><a href="/location/'+data.id+'">'+data.members+" "+(data.members==1 ? "member" : "members")+"</a></p>" });
				var marker = new google.maps.Marker({
					position: latLng,
					map: map,
					title: data.name,
					label: data.name.charAt(0).toUpperCase(),
					
				});
				var isOpen = false;
				marker.addListener('mouseover', function() { isOpen = false; infowindow.open(map, marker); });
				marker.addListener('click', function() { isOpen = true; });
				marker.addListener('mouseout', function() { if(!isOpen) { infowindow.close(); } });
				markers.push(marker);
			});
			var mc = new MarkerClusterer(map, markers);
		});
	}
</script>
<!-- Google Maps -->
<script type="text/javascript" src="/js/maps_markercluster.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('KEY_GOOGLEBROWSER') }}&callback=initMap"></script>
@stop

@section("content")
<div class="section"><div class='section-container'>
	<h3>{{ env('ORG_NAME') }} Around The Globe</h3>
	<div class="panel panel-default">
		<div id="map" style="height: 500px;">
	</div>
</div></div>
@stop