(function ($, Pkj) {
	Pkj.GoogleMapMarkers = function ($el, markers, mapOptions) {
		this.markersArray = [];
		mapOptions = mapOptions || {};
		
		var map = new google.maps.Map($el, mapOptions);
		this.map = map;
		
		
		var bounds = new google.maps.LatLngBounds();
		
		$.each(markers, function (index, item) {
			var point = new google.maps.LatLng(item.lat, item.lon);
			
			var marker = new google.maps.Marker({
				position: point,
				map: map,
				title: item.title
			});
			google.maps.event.addListener(marker, 'click', function() {
				window.location = item.link;
			});
			bounds.extend(point);
		});
		
		map.fitBounds(bounds);
		
	};
	
})(jQuery, window.Pkj = window.Pkj || {});