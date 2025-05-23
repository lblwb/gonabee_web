jQuery(document).ready(function () {
    const $ = jQuery;

    $(document).on('yandex-taxi-delivery:initMapSuggestion', function (event, address) {
        initSuggestion(address);
    });

    $('.js_yandex-taxi-delivery_form__param_address').each(function () {
        initSuggestion($(this));
    });

    function initSuggestion(input) {
        let mapContainer = input.closest('.yandex-taxi-delivery_form__route_point').find('.yandex-taxi-delivery_map:first');
        let notice = input.closest('.js_yandex-taxi-delivery_param_container').find('.error-message:first'),
            coordinate = input.next('.js_yandex-taxi-delivery_form__param_coordinate'),
            coordinate_obj,
            addressDetails = input.closest('.yandex-taxi-delivery_form__route_point').find('.yandex-taxi-delivery_address-details'),
            map,
            suggestView;

        geocode();

        coordinate.change(function(e){
            suggestView = new google.maps.Map(mapContainer[0], {
                center: coordinate_obj,
                zoom: 15
            });

            // Add address selection from suggestion handler
            suggestView.addListener("place_changed", () => {
                input.trigger('change'); // need for trigger price recalculation
            });
        });

        // Add address changing handler
        input.change(function () {
            geocode();
        });

        function geocode() {
            var request = input.val().trim();
            var geocoder = new google.maps.Geocoder()

            if (request === "") {
                return;
            }
            geocoder.geocode({
                address: request
            }, function (res, status) {
                console.log(res);
                var obj = res[0],
                    error, hint;

                if (!obj.geometry) {
                    error = translations.address_not_found;
                }

                if (error) {
                    resetCoordinate();
                    addError(error);
                } else {
                    handleResult(obj);
                }
            })
        }

        function handleResult(obj) {
            removeError();

            var shortAddress = obj.formatted_address;

            setCoordinate(obj.geometry.location.lat(), obj.geometry.location.lng());
            createMap(shortAddress);
            sendAddressDetails(obj.address_components);
        }

        function sendAddressDetails(components) {
            let html = '';
            components.forEach((component) => {
                let label = false;
                switch (component.types[0]) {
                    case 'country':
                        label = translations.country;
                        break;
                    case 'locality':
                        label = translations.locality;
                        break;
                    case 'route':
                        label = translations.street;
                        break;
                    case 'street_number':
                        label = translations.house;
                        break;
                }

                if (label) {
                    html += `<div><span class="label">${label}: </span><span>${component.long_name}</span></div>`;
                }
            });

            addressDetails.html(html);
        }

        function createMap(caption) {
            marker = new google.maps.Marker({
                position: coordinate_obj,
                map: suggestView,
                title: caption,
            });
            mapContainer.show();
            /*if (!map) {
            } else {
                map.setCenter(state.center, state.zoom);
                placemark.geometry.setCoordinates(state.center);
                placemark.properties.set({iconCaption: caption, balloonContent: caption});
            }*/
        }

        function resetCoordinate() {
            coordinate.val('');
            coordinate.trigger('change');
        }

        function setCoordinate(lat, lon) {
            coordinate.val(lat + ',' + lon);
            coordinate_obj = {lat: lat, lng: lon};
            coordinate.trigger('change');
        }

        function addError(message) {
            input.addClass('error');
            notice.html(message);

            if (map) {
                mapContainer.hide();
                map.destroy();
                map = null;
            }
        }

        function removeError() {
            input.removeClass('error');
            notice.html('');
        }
    }
});
