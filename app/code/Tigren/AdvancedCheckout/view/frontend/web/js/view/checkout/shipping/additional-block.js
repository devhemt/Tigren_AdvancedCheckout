define(["ko", "jquery", "uiComponent", "mage/url"], function (
    ko,
    $,
    Component,
    url
) {
    "use strict";

    return Component.extend({
        defaults: {
            template:
                "Tigren_AdvancedCheckout/checkout/shipping/additional-block",
        },

        initialize: function () {
            this._super();
            var date = "";
            var time = "";
            var dayOffs = [],
                dateOffs = [],
                deliveryTimes = [];

            var dayOffLink = url.build("rest/V1/checktime/dayoff");
            $.ajax({
                showLoader: true,
                url: dayOffLink,
                data: { checkVal: date },
                type: "GET",
                dataType: "json",
            }).done(function (data) {
                dayOffs = data.split(",").map(Number);
            });
            var dateOffLink = url.build("rest/V1/checktime/dateoff");
            $.ajax({
                showLoader: true,
                url: dateOffLink,
                data: { checkVal: date },
                type: "GET",
                dataType: "json",
            }).done(function (data) {
                const jsonArray = JSON.parse(data);
                for (const key in jsonArray) {
                    var jsonDate = new Date(jsonArray[key].date);
                    var formattedJsonDate = jsonDate
                        .toISOString()
                        .substring(0, 10);

                    dateOffs.push(formattedJsonDate);
                }
            });
            var deliveryTimeLink = url.build("rest/V1/checktime/deliverytime");
            $.ajax({
                showLoader: true,
                url: deliveryTimeLink,
                data: { checkVal: date },
                type: "GET",
                dataType: "json",
            }).done(function (data) {
                const jsonArray = JSON.parse(data);

                for (const key in jsonArray) {
                    const value = jsonArray[key];
                    const timeStart = value.time_start;
                    const timeEnd = value.time_end;
                    deliveryTimes.push(`From ${timeStart} to ${timeEnd}`);
                }
            });

            this.weekdays = deliveryTimes;

            this.test = ko.observable();
            this.invalidDate = ko.observable("");

            this.test.subscribe(function (value) {
                date = value;

                if (value != null) {
                    const selectedDate = new Date(value);
                    const selectedDay = selectedDate.getDay();
                    const isDayIncluded =
                        ko.utils.arrayFilter(dayOffs, function (day) {
                            return day === selectedDay;
                        }).length > 0;
                    var isDateInArray = $.inArray(value, dateOffs) !== -1;
                    if (isDayIncluded || isDateInArray) {
                        date = "";
                        $("#erorr").text(
                            "Sorry, the store is closed on the selected date!"
                        );
                    } else {
                        $("#erorr").text("");
                    }
                }

                var linkUrls = url.build(
                    "AdvancedCheckout/checkout/saveInQuote"
                );
                $.ajax({
                    showLoader: true,
                    url: linkUrls,
                    data: { checkVal: date },
                    type: "POST",
                    dataType: "json",
                }).done(function (data) {
                    console.log("success");
                });
            });

            this.selectedWeekday = ko.observable();
            this.selectedWeekday.subscribe(function (newValue) {
                time = newValue;
                var linkUrls = url.build(
                    "AdvancedCheckout/checkout/saveInQuote"
                );
                $.ajax({
                    showLoader: true,
                    url: linkUrls,
                    data: { checkVal: date + time },
                    type: "POST",
                    dataType: "json",
                }).done(function (data) {
                    console.log("success");
                });
            });
        },
    });
});
