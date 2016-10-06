if (document.getElementById('geo_lat') && document.getElementById('geo_lng')) {

    var startPos;
    var map;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(updateLocation, handleLocationError, {timeout: 50000});
    }

    function updateLocation(position) {
        startPos = position;
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        document.getElementById('geo_lat').value = latitude;
        document.getElementById('geo_lng').value = longitude;
    }

    function handleLocationError(error) {
        switch (error.code) {
            case 0:
                updateStatus("There was an error while retrieving your location: " + error.message);
                break;

            case 1:
                updateStatus("The user prevented this page from retrieving the location.");
                break;

            case 2:
                updateStatus("The browser was unable to determine your location: " + error.message);

                break;

            case 3:

                updateStatus("The browser timed out before retrieving the location.");

                break;
        }
    }

    function updateStatus(msg) {
        alert(msg);
    }
}

history.pushState(null, null, '#nbb');
window.addEventListener('popstate', function (event) {
    history.pushState(null, null, '#nbb');
});
