// Geolocations
const getLocation = ({ idLat, idLong }) => {
    const btnGeolocation = $("#btn_geoLocation");
    btnGeolocation.html('<i class="fa fa-spinner fa-spin""></i>');
    let location;

    const tryAPIGeolocation = function () {
        jQuery
            .post(
                "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyAKv-benZnSAvT0C8TjaG80pQbybyaz0Q8",
                function (success) {
                    apiGeolocationSuccess({
                        coords: {
                            latitude: success.location.lat,
                            longitude: success.location.lng,
                        },
                    });
                }
            )
            .fail(function (err) {
                console.log("API Geolocation error! \n\n" + err);
            });
    };

    const apiGeolocationSuccess= (pos) => {
        const crd = pos.coords;
        console.log(crd);

        if (
            $(`#${idLat}`).val() === crd.latitude.toString() &&
            $(`#${idLong}`).val() === crd.longitude.toString()
        ) {
            navigator.geolocation.clearWatch(location);
            btnGeolocation.html('<i class="ti-location-pin"></i>');
        }
        $(`#${idLat}`).val(crd.latitude).keyup();
        $(`#${idLong}`).val(crd.longitude).keyup();
    };

    const error = (error) => {
        switch (error.code) {
            case error.TIMEOUT:
                console.log("Browser geolocation error !\n\nTimeout.");
                break;
            case error.PERMISSION_DENIED:
                if (
                    error.message.indexOf("Only secure origins are allowed") ==
                    0
                ) {
                    tryAPIGeolocation();
                }
                break;
            case error.POSITION_UNAVAILABLE:
                console.log(
                    "Browser geolocation error !\n\nPosition unavailable."
                );
                break;
        }
    };

    const options = {
        enableHighAccuracy: true,
        maximumAge: 0,
    };

    location = navigator.geolocation.watchPosition(apiGeolocationSuccess, error, options);
};

$(document).ready(() => {
    if (!navigator.geolocation) $("#btn_geoLocation").remove();
});
