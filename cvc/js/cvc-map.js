var map;

function initialize() {

  var cvcLatLng = new google.maps.LatLng(25.802085, -80.203986);
  
  var mapOptions = {
    zoom: 16,
    center: cvcLatLng
  };
  
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
    position: cvcLatLng,
    map: map,
    title: 'CVC'
  });

  var contentString = '<div id="content" style="width:250px;">'+
  '<hr><div id="bodyContent">'+
  '<p><address>'+
  '541 NW 27th Street<br/>Miami, FL 33127<br/>305-571-1415' +
  '</address></p>'+
  '</div><hr>'+
  '</div>';

  var infowindow = new google.maps.InfoWindow({
    content: contentString
  });

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });

}

google.maps.event.addDomListener(window, 'load', initialize);