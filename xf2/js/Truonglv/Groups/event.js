!(function ($, window, document, _undefined) {
    XF.Groups_EventMap = XF.Element.newHandler({
        options: {
            zoom: null,
            place: null,
            acfield: null,
            error: null,
        },

        $address: null,
        $map: null,
        $latitude: null,
        $longitude: null,

        map: null,
        markers: [],

        init: function () {
            this.$address = $(this.options.acfield);
            this.$latitude = $('input[name="latitude"]');
            this.$longitude = $('input[name="longitude"]');

            this.$map = this.$target.find('>.map');
            this.createMap();

            var location = new google.maps.LatLng(this.$latitude.val() || 0, this.$longitude.val() || 0);
            this.map.setCenter(location);
            this.addMarkerToMap(location);

            var options = { type: ['geocode'] },
                _this = this;
            this.geocoder = new google.maps.Geocoder();

            var autoComplete = new google.maps.places.Autocomplete(this.$address[0], options);

            autoComplete.addListener('place_changed', function () {
                var place = autoComplete.getPlace();

                _this.removeMarkers();
                _this.map.setCenter(place.geometry.location);
                _this.addMarkerToMap(place.geometry.location);
                _this.updateLatLng(place.geometry.location);
            });
        },

        createMap: function () {
            this.map = new google.maps.Map(this.$map[0], {
                zoom: this.options.zoom,
            });

            google.maps.event.addListener(this.map, 'click', $.proxy(this, 'addMarker'));
        },

        addMarker: function (event) {
            this.removeMarkers();
            var location = event.latLng;

            this.geocoder.geocode(
                {
                    latLng: location,
                },
                $.proxy(this, 'geocoderReponse')
            );

            this.updateLatLng(location);
            this.addMarkerToMap(location);
        },

        geocoderReponse: function (results) {
            if (!results.length) {
                XF.alert(this.options.error);
                return false;
            }

            var result = results[0];
            this.$address.val(result.formatted_address);
        },

        addMarkerToMap: function (location) {
            var marker = new google.maps.Marker({
                position: location,
                map: this.map,
                draggable: false,
                animation: google.maps.Animation.DROP,
            });

            this.markers.push(marker);
        },

        removeMarkers: function () {
            for (var i = 0; i < this.markers.length; i++) {
                this.markers[i].setMap(null);
            }
        },

        updateLatLng: function (location) {
            this.$latitude.val(location.lat());
            this.$longitude.val(location.lng());
        },
    });

    XF.Groups_CountDown = XF.Element.newHandler({
        options: {
            time: null,
            classPrefix: null,
        },

        $days: null,
        $hours: null,
        $minutes: null,
        $seconds: null,

        cached: {},

        init: function () {
            if (!this.options.time) {
                return;
            }

            var prefix = this.options.classPrefix;

            this.$days = this.$target.find(prefix + 'days');
            this.$hours = this.$target.find(prefix + 'hours');
            this.$minutes = this.$target.find(prefix + 'minutes');
            this.$seconds = this.$target.find(prefix + 'seconds');

            this.onTick();

            var _this = this;
            function regInterval() {
                _this.interval = setInterval(XF.proxy(_this, 'onTick'), 1000);
            }

            regInterval();
            $(window).on('blur', function () {
                clearInterval(_this.interval);
            });
            $(window).on('focus', regInterval);
        },

        onTick: function () {
            var time = this.options.time * 1000,
                target = new Date(time),
                days,
                hours,
                minutes,
                seconds,
                diff;

            diff = Math.ceil((target.getTime() - Date.now()) / 1000);
            if (diff <= 0) {
                clearInterval(this.interval);
            }

            days = Math.floor(diff / 86400);
            diff -= days * 86400;

            hours = Math.floor(diff / 3600);
            diff -= hours * 3600;

            minutes = Math.floor(diff / 60);
            diff -= minutes * 60;

            seconds = diff;

            this.update(this.$days, 'days', days);
            this.update(this.$hours, 'hours', hours);
            this.update(this.$minutes, 'minutes', minutes);
            this.update(this.$seconds, 'seconds', seconds);
        },

        update: function ($node, name, value) {
            if (this.cached[name] !== value) {
                $node.find(this.options.classPrefix + 'number').text(value);

                this.cached[name] = value;
            }
        },
    });

    XF.Groups_EventFulCalendar = XF.Element.newHandler({
        options: {
            source: null,
            buttonToday: null,
            buttonMonth: null,
            buttonWeek: null,
            buttonDay: null,
            buttonList: null,
        },

        calendar: null,

        init: function () {
            this.setupCalendar();
        },

        setupCalendar: function () {
            var calendar = new FullCalendar.Calendar(this.$target[0], {
                initialView: 'dayGridWeek',
                headerToolbar: { center: 'dayGridMonth,dayGridWeek,listWeek' },
                events: {
                    url: this.options.source,
                    method: 'GET',
                    extraParams: {
                        _xfWithData: 1,
                        _xfResponseType: 'json',
                        _xfToken: XF.config.csrf,
                    },
                    failure: function (e) {
                        XF.defaultAjaxSuccessError(JSON.parse(e.xhr.response), e.xhr.status, e.xhr);
                    },
                },
                height: 650,
                buttonText: {
                    today: this.options.buttonToday,
                    month: this.options.buttonMonth,
                    week: this.options.buttonWeek,
                    day: this.options.buttonDay,
                    list: this.options.buttonList,
                },
                navLinks: true,
            });

            calendar.render();
            calendar.on('eventDrop', XF.proxy(this, 'onEventDrop'));
            this.calendar = calendar;
        },

        onEventDrop: function (e) {
            var event = e.event,
                delta = e.delta;
            if (!event.extendedProps.hasOwnProperty('editLink') || !event.extendedProps.editLink) {
                return;
            }

            XF.ajax(
                'POST',
                event.extendedProps.editLink,
                delta,
                function (data, status, xhr) {
                    if (!data.hasOwnProperty('is_saved') || !data.is_saved) {
                        e.revert();
                    }

                    XF.defaultAjaxSuccessError(data, status, xhr);
                },
                { skipDefault: true }
            );
        },
    });

    XF.Element.register('tlg-event-map', 'XF.Groups_EventMap');
    XF.Element.register('tlg-event-countdown', 'XF.Groups_CountDown');
    XF.Element.register('tlg-event--fullcalendar', 'XF.Groups_EventFulCalendar');
})(jQuery, this, document);
