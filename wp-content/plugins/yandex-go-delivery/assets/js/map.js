jQuery(function () {
    const $ = jQuery;

    let alertWasShown = false;

    $(document).on('yandex-taxi-delivery:initMapSuggestion', function (event, address) {
        initSuggestion(address);
    });

    ymaps.ready(function () {
        $('.js_yandex-taxi-delivery_form__param_address').each(function () {
            initSuggestion($(this));
        });
    });

    function initSuggestion(input) {
        let suggestView = new ymaps.SuggestView(input.get(0)),
            mapContainer = input.closest('.yandex-taxi-delivery_form__route_point').find('.yandex-taxi-delivery_map:first'),
            notice = input.closest('.js_yandex-taxi-delivery_param_container').find('.error-message:first'),
            coordinate = input.next('.js_yandex-taxi-delivery_form__param_coordinate'),
            addressDetails = input.closest('.yandex-taxi-delivery_form__route_point').find('.yandex-taxi-delivery_address-details'),
            placemark,
            map;

        geocode();
        // Add address changing handler
        input.on('change', function () {
            geocode();
        });
        // Add address selection from suggestion handler
        suggestView.events.add('select', function () {
            input.trigger('change'); // need for trigger price recalculation
            geocode();
        });

        function geocode() {
            var request = input.val().trim();

            if (request === "") {
                return;
            }
            ymaps.geocode(request).then(function (res) {
                var obj = res.geoObjects.get(0),
                    error, hint;

                if (obj) {
                    switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        case 'exact':
                            break;
                        case 'number':
                        case 'near':
                        case 'range':
                            error = translations.bad_address_house;
                            break;
                        case 'street':
                            error = translations.bad_address_house;
                            break;
                        case 'other':
                        default:
                            error = translations.bad_address;
                    }
                } else {
                    error = translations.address_not_found;
                }

                if (error) {
                    resetCoordinate();
                    addError(error);
                } else {
                    handleResult(obj);
                }
            }, function (e) {
                console.log(e);

                if (alertWasShown) {
                    return;
                }

                alertWasShown = true;

                alert(translations.token_error);

                const settingUrl = $('[name="setting_url"]');
                if(settingUrl.val()) {
                    window.location = settingUrl.val();
                }
            })
        }

        function handleResult(obj) {
            removeError();

            var bounds = obj.properties.get('boundedBy'),
                mapState = ymaps.util.bounds.getCenterAndZoom(
                    bounds,
                    [mapContainer.width(), mapContainer.height()]
                ),
                shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
            mapState.controls = [];

            setCoordinate(mapState.center[0], mapState.center[1]);
            createMap(mapState, shortAddress);
            sendAddressDetails(obj.properties.get('metaDataProperty.GeocoderMetaData.Address.Components'));
        }

        function sendAddressDetails(components) {
            let html = '';
            components.forEach((component) => {
                let label = false;
                switch (component.kind) {
                    case 'country':
                        label = translations.country;
                        break;
                    case 'locality':
                        label = translations.locality;
                        break;
                    case 'street':
                        label = translations.street;
                        break;
                    case 'house':
                        label = translations.house;
                        break;
                }

                if (label) {
                    html += `<div><span class="label">${label}: </span><span>${component.name}</span></div>`;
                }
            });

            addressDetails.html(html);
        }

        function createMap(state, caption) {
            if (!map) {
                map = new ymaps.Map(mapContainer.get(0), state);
                placemark = new ymaps.Placemark(
                    map.getCenter(), {
                        iconCaption: caption,
                        balloonContent: caption
                    }, {
                        preset: 'islands#redDotIconWithCaption'
                    });
                map.geoObjects.add(placemark);
                mapContainer.show();
            } else {
                map.setCenter(state.center, state.zoom);
                placemark.geometry.setCoordinates(state.center);
                placemark.properties.set({iconCaption: caption, balloonContent: caption});
            }
        }

        function resetCoordinate() {
            coordinate.val('');
            coordinate.trigger('change');
        }

        function setCoordinate(lat, lon) {
            coordinate.val(lat + ',' + lon);
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
