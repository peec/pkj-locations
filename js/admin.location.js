(function (Pkj) {
	var $ = jQuery;
	
	Pkj.Map = function (el) {
		this.markersArray = [];
		var mapOptions = {
				center: new google.maps.LatLng(-34.397, 150.644),
				zoom: 8,
				mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(el, mapOptions);
		this.map = map;
		
		
		

		var self = this;
		
		google.maps.event.addListener(map, 'click', function(event) {
			var mapZoom = map.getZoom();
			var latLng = event.latLng;
			
			
			self.addMarker(latLng);
			
		});
		
	};

	Pkj.Map.prototype.addMarker = function (loc) {
		this.clearOverlays();
		var marker = new google.maps.Marker({
			position: loc,
			map: this.map,
			draggable:true
		});
		this.markersArray.push(marker);	
		var longitude_f = $('#longitude_f');
		var latitude_f = $('#latitude_f');

		google.maps.event.addListener(
				marker,
				'drag',
				function() {
					longitude_f.val(marker.position.lng());
					latitude_f.val(marker.position.lat());
				}
		);
		
		longitude_f.val(marker.position.lng());
		latitude_f.val(marker.position.lat());
	};
	
	
	Pkj.Map.prototype.clearOverlays = function () {
		for (var i = 0; i < this.markersArray.length; i++ ) {
			this.markersArray[i].setMap(null);
		}
		this.markersArray = [];
	}
	
	


	Pkj.Map.prototype.setPos = function (lat, lon) {
		if (lat && lon) {
			var point = new google.maps.LatLng(lat, lon);
			this.map.setCenter(point);
			this.map.setZoom(12);
			this.addMarker(point);
		}
	};

	$(function () {
		var el = document.getElementById("admin_location_map");
		
		if (el) {
			var mapWrap = new Pkj.Map(el);
		

			
			var searchMap = function () {
				var s = $('#admin_location_map_search').val();
				
				console.log("Search for " + s );
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': s}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						mapWrap.map.setCenter();
						var loc = results[0].geometry.location;
						
						mapWrap.map.setCenter(loc);
						mapWrap.map.setZoom(12);
						mapWrap.addMarker(loc);
						
					} else {
						
					}
				});
				return false;
			};
			
			$('#admin_location_map_search_btn').click(searchMap);		
			
			$("#admin_location_map_search").keypress(function(e){
				if(e.which === 13){
					e.preventDefault();
					e.stopPropagation();
					searchMap();
				}
			});

			Pkj.mapWrap = mapWrap;
		}
	});
		
		
})(window.Pkj = window.Pkj ||  {});
