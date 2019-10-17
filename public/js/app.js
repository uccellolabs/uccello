(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/app"],{

/***/ "./node_modules/daterangepicker/daterangepicker.js":
/*!*********************************************************!*\
  !*** ./node_modules/daterangepicker/daterangepicker.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
* @version: 3.0.5
* @author: Dan Grossman http://www.dangrossman.info/
* @copyright: Copyright (c) 2012-2019 Dan Grossman. All rights reserved.
* @license: Licensed under the MIT license. See http://www.opensource.org/licenses/mit-license.php
* @website: http://www.daterangepicker.com/
*/
// Following the UMD template https://github.com/umdjs/umd/blob/master/templates/returnExportsGlobal.js
(function (root, factory) {
    if (true) {
        // AMD. Make globaly available as well
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! moment */ "./node_modules/moment/moment.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")], __WEBPACK_AMD_DEFINE_RESULT__ = (function (moment, jquery) {
            if (!jquery.fn) jquery.fn = {}; // webpack server rendering
            if (typeof moment !== 'function' && moment.default) moment = moment.default
            return factory(moment, jquery);
        }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else { var moment, jQuery; }
}(this, function(moment, $) {
    var DateRangePicker = function(element, options, cb) {

        //default settings for options
        this.parentEl = 'body';
        this.element = $(element);
        this.startDate = moment().startOf('day');
        this.endDate = moment().endOf('day');
        this.minDate = false;
        this.maxDate = false;
        this.maxSpan = false;
        this.autoApply = false;
        this.singleDatePicker = false;
        this.showDropdowns = false;
        this.minYear = moment().subtract(100, 'year').format('YYYY');
        this.maxYear = moment().add(100, 'year').format('YYYY');
        this.showWeekNumbers = false;
        this.showISOWeekNumbers = false;
        this.showCustomRangeLabel = true;
        this.timePicker = false;
        this.timePicker24Hour = false;
        this.timePickerIncrement = 1;
        this.timePickerSeconds = false;
        this.linkedCalendars = true;
        this.autoUpdateInput = true;
        this.alwaysShowCalendars = false;
        this.ranges = {};

        this.opens = 'right';
        if (this.element.hasClass('pull-right'))
            this.opens = 'left';

        this.drops = 'down';
        if (this.element.hasClass('dropup'))
            this.drops = 'up';

        this.buttonClasses = 'btn btn-sm';
        this.applyButtonClasses = 'btn-primary';
        this.cancelButtonClasses = 'btn-default';

        this.locale = {
            direction: 'ltr',
            format: moment.localeData().longDateFormat('L'),
            separator: ' - ',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
            weekLabel: 'W',
            customRangeLabel: 'Custom Range',
            daysOfWeek: moment.weekdaysMin(),
            monthNames: moment.monthsShort(),
            firstDay: moment.localeData().firstDayOfWeek()
        };

        this.callback = function() { };

        //some state information
        this.isShowing = false;
        this.leftCalendar = {};
        this.rightCalendar = {};

        //custom options from user
        if (typeof options !== 'object' || options === null)
            options = {};

        //allow setting options with data attributes
        //data-api options will be overwritten with custom javascript options
        options = $.extend(this.element.data(), options);

        //html template for the picker UI
        if (typeof options.template !== 'string' && !(options.template instanceof $))
            options.template =
            '<div class="daterangepicker">' +
                '<div class="ranges"></div>' +
                '<div class="drp-calendar left">' +
                    '<div class="calendar-table"></div>' +
                    '<div class="calendar-time"></div>' +
                '</div>' +
                '<div class="drp-calendar right">' +
                    '<div class="calendar-table"></div>' +
                    '<div class="calendar-time"></div>' +
                '</div>' +
                '<div class="drp-buttons">' +
                    '<span class="drp-selected"></span>' +
                    '<button class="cancelBtn" type="button"></button>' +
                    '<button class="applyBtn" disabled="disabled" type="button"></button> ' +
                '</div>' +
            '</div>';

        this.parentEl = (options.parentEl && $(options.parentEl).length) ? $(options.parentEl) : $(this.parentEl);
        this.container = $(options.template).appendTo(this.parentEl);

        //
        // handle all the possible options overriding defaults
        //

        if (typeof options.locale === 'object') {

            if (typeof options.locale.direction === 'string')
                this.locale.direction = options.locale.direction;

            if (typeof options.locale.format === 'string')
                this.locale.format = options.locale.format;

            if (typeof options.locale.separator === 'string')
                this.locale.separator = options.locale.separator;

            if (typeof options.locale.daysOfWeek === 'object')
                this.locale.daysOfWeek = options.locale.daysOfWeek.slice();

            if (typeof options.locale.monthNames === 'object')
              this.locale.monthNames = options.locale.monthNames.slice();

            if (typeof options.locale.firstDay === 'number')
              this.locale.firstDay = options.locale.firstDay;

            if (typeof options.locale.applyLabel === 'string')
              this.locale.applyLabel = options.locale.applyLabel;

            if (typeof options.locale.cancelLabel === 'string')
              this.locale.cancelLabel = options.locale.cancelLabel;

            if (typeof options.locale.weekLabel === 'string')
              this.locale.weekLabel = options.locale.weekLabel;

            if (typeof options.locale.customRangeLabel === 'string'){
                //Support unicode chars in the custom range name.
                var elem = document.createElement('textarea');
                elem.innerHTML = options.locale.customRangeLabel;
                var rangeHtml = elem.value;
                this.locale.customRangeLabel = rangeHtml;
            }
        }
        this.container.addClass(this.locale.direction);

        if (typeof options.startDate === 'string')
            this.startDate = moment(options.startDate, this.locale.format);

        if (typeof options.endDate === 'string')
            this.endDate = moment(options.endDate, this.locale.format);

        if (typeof options.minDate === 'string')
            this.minDate = moment(options.minDate, this.locale.format);

        if (typeof options.maxDate === 'string')
            this.maxDate = moment(options.maxDate, this.locale.format);

        if (typeof options.startDate === 'object')
            this.startDate = moment(options.startDate);

        if (typeof options.endDate === 'object')
            this.endDate = moment(options.endDate);

        if (typeof options.minDate === 'object')
            this.minDate = moment(options.minDate);

        if (typeof options.maxDate === 'object')
            this.maxDate = moment(options.maxDate);

        // sanity check for bad options
        if (this.minDate && this.startDate.isBefore(this.minDate))
            this.startDate = this.minDate.clone();

        // sanity check for bad options
        if (this.maxDate && this.endDate.isAfter(this.maxDate))
            this.endDate = this.maxDate.clone();

        if (typeof options.applyButtonClasses === 'string')
            this.applyButtonClasses = options.applyButtonClasses;

        if (typeof options.applyClass === 'string') //backwards compat
            this.applyButtonClasses = options.applyClass;

        if (typeof options.cancelButtonClasses === 'string')
            this.cancelButtonClasses = options.cancelButtonClasses;

        if (typeof options.cancelClass === 'string') //backwards compat
            this.cancelButtonClasses = options.cancelClass;

        if (typeof options.maxSpan === 'object')
            this.maxSpan = options.maxSpan;

        if (typeof options.dateLimit === 'object') //backwards compat
            this.maxSpan = options.dateLimit;

        if (typeof options.opens === 'string')
            this.opens = options.opens;

        if (typeof options.drops === 'string')
            this.drops = options.drops;

        if (typeof options.showWeekNumbers === 'boolean')
            this.showWeekNumbers = options.showWeekNumbers;

        if (typeof options.showISOWeekNumbers === 'boolean')
            this.showISOWeekNumbers = options.showISOWeekNumbers;

        if (typeof options.buttonClasses === 'string')
            this.buttonClasses = options.buttonClasses;

        if (typeof options.buttonClasses === 'object')
            this.buttonClasses = options.buttonClasses.join(' ');

        if (typeof options.showDropdowns === 'boolean')
            this.showDropdowns = options.showDropdowns;

        if (typeof options.minYear === 'number')
            this.minYear = options.minYear;

        if (typeof options.maxYear === 'number')
            this.maxYear = options.maxYear;

        if (typeof options.showCustomRangeLabel === 'boolean')
            this.showCustomRangeLabel = options.showCustomRangeLabel;

        if (typeof options.singleDatePicker === 'boolean') {
            this.singleDatePicker = options.singleDatePicker;
            if (this.singleDatePicker)
                this.endDate = this.startDate.clone();
        }

        if (typeof options.timePicker === 'boolean')
            this.timePicker = options.timePicker;

        if (typeof options.timePickerSeconds === 'boolean')
            this.timePickerSeconds = options.timePickerSeconds;

        if (typeof options.timePickerIncrement === 'number')
            this.timePickerIncrement = options.timePickerIncrement;

        if (typeof options.timePicker24Hour === 'boolean')
            this.timePicker24Hour = options.timePicker24Hour;

        if (typeof options.autoApply === 'boolean')
            this.autoApply = options.autoApply;

        if (typeof options.autoUpdateInput === 'boolean')
            this.autoUpdateInput = options.autoUpdateInput;

        if (typeof options.linkedCalendars === 'boolean')
            this.linkedCalendars = options.linkedCalendars;

        if (typeof options.isInvalidDate === 'function')
            this.isInvalidDate = options.isInvalidDate;

        if (typeof options.isCustomDate === 'function')
            this.isCustomDate = options.isCustomDate;

        if (typeof options.alwaysShowCalendars === 'boolean')
            this.alwaysShowCalendars = options.alwaysShowCalendars;

        // update day names order to firstDay
        if (this.locale.firstDay != 0) {
            var iterator = this.locale.firstDay;
            while (iterator > 0) {
                this.locale.daysOfWeek.push(this.locale.daysOfWeek.shift());
                iterator--;
            }
        }

        var start, end, range;

        //if no start/end dates set, check if an input element contains initial values
        if (typeof options.startDate === 'undefined' && typeof options.endDate === 'undefined') {
            if ($(this.element).is(':text')) {
                var val = $(this.element).val(),
                    split = val.split(this.locale.separator);

                start = end = null;

                if (split.length == 2) {
                    start = moment(split[0], this.locale.format);
                    end = moment(split[1], this.locale.format);
                } else if (this.singleDatePicker && val !== "") {
                    start = moment(val, this.locale.format);
                    end = moment(val, this.locale.format);
                }
                if (start !== null && end !== null) {
                    this.setStartDate(start);
                    this.setEndDate(end);
                }
            }
        }

        if (typeof options.ranges === 'object') {
            for (range in options.ranges) {

                if (typeof options.ranges[range][0] === 'string')
                    start = moment(options.ranges[range][0], this.locale.format);
                else
                    start = moment(options.ranges[range][0]);

                if (typeof options.ranges[range][1] === 'string')
                    end = moment(options.ranges[range][1], this.locale.format);
                else
                    end = moment(options.ranges[range][1]);

                // If the start or end date exceed those allowed by the minDate or maxSpan
                // options, shorten the range to the allowable period.
                if (this.minDate && start.isBefore(this.minDate))
                    start = this.minDate.clone();

                var maxDate = this.maxDate;
                if (this.maxSpan && maxDate && start.clone().add(this.maxSpan).isAfter(maxDate))
                    maxDate = start.clone().add(this.maxSpan);
                if (maxDate && end.isAfter(maxDate))
                    end = maxDate.clone();

                // If the end of the range is before the minimum or the start of the range is
                // after the maximum, don't display this range option at all.
                if ((this.minDate && end.isBefore(this.minDate, this.timepicker ? 'minute' : 'day'))
                  || (maxDate && start.isAfter(maxDate, this.timepicker ? 'minute' : 'day')))
                    continue;

                //Support unicode chars in the range names.
                var elem = document.createElement('textarea');
                elem.innerHTML = range;
                var rangeHtml = elem.value;

                this.ranges[rangeHtml] = [start, end];
            }

            var list = '<ul>';
            for (range in this.ranges) {
                list += '<li data-range-key="' + range + '">' + range + '</li>';
            }
            if (this.showCustomRangeLabel) {
                list += '<li data-range-key="' + this.locale.customRangeLabel + '">' + this.locale.customRangeLabel + '</li>';
            }
            list += '</ul>';
            this.container.find('.ranges').prepend(list);
        }

        if (typeof cb === 'function') {
            this.callback = cb;
        }

        if (!this.timePicker) {
            this.startDate = this.startDate.startOf('day');
            this.endDate = this.endDate.endOf('day');
            this.container.find('.calendar-time').hide();
        }

        //can't be used together for now
        if (this.timePicker && this.autoApply)
            this.autoApply = false;

        if (this.autoApply) {
            this.container.addClass('auto-apply');
        }

        if (typeof options.ranges === 'object')
            this.container.addClass('show-ranges');

        if (this.singleDatePicker) {
            this.container.addClass('single');
            this.container.find('.drp-calendar.left').addClass('single');
            this.container.find('.drp-calendar.left').show();
            this.container.find('.drp-calendar.right').hide();
            if (!this.timePicker) {
                this.container.addClass('auto-apply');
            }
        }

        if ((typeof options.ranges === 'undefined' && !this.singleDatePicker) || this.alwaysShowCalendars) {
            this.container.addClass('show-calendar');
        }

        this.container.addClass('opens' + this.opens);

        //apply CSS classes and labels to buttons
        this.container.find('.applyBtn, .cancelBtn').addClass(this.buttonClasses);
        if (this.applyButtonClasses.length)
            this.container.find('.applyBtn').addClass(this.applyButtonClasses);
        if (this.cancelButtonClasses.length)
            this.container.find('.cancelBtn').addClass(this.cancelButtonClasses);
        this.container.find('.applyBtn').html(this.locale.applyLabel);
        this.container.find('.cancelBtn').html(this.locale.cancelLabel);

        //
        // event listeners
        //

        this.container.find('.drp-calendar')
            .on('click.daterangepicker', '.prev', $.proxy(this.clickPrev, this))
            .on('click.daterangepicker', '.next', $.proxy(this.clickNext, this))
            .on('mousedown.daterangepicker', 'td.available', $.proxy(this.clickDate, this))
            .on('mouseenter.daterangepicker', 'td.available', $.proxy(this.hoverDate, this))
            .on('change.daterangepicker', 'select.yearselect', $.proxy(this.monthOrYearChanged, this))
            .on('change.daterangepicker', 'select.monthselect', $.proxy(this.monthOrYearChanged, this))
            .on('change.daterangepicker', 'select.hourselect,select.minuteselect,select.secondselect,select.ampmselect', $.proxy(this.timeChanged, this))

        this.container.find('.ranges')
            .on('click.daterangepicker', 'li', $.proxy(this.clickRange, this))

        this.container.find('.drp-buttons')
            .on('click.daterangepicker', 'button.applyBtn', $.proxy(this.clickApply, this))
            .on('click.daterangepicker', 'button.cancelBtn', $.proxy(this.clickCancel, this))

        if (this.element.is('input') || this.element.is('button')) {
            this.element.on({
                'click.daterangepicker': $.proxy(this.show, this),
                'focus.daterangepicker': $.proxy(this.show, this),
                'keyup.daterangepicker': $.proxy(this.elementChanged, this),
                'keydown.daterangepicker': $.proxy(this.keydown, this) //IE 11 compatibility
            });
        } else {
            this.element.on('click.daterangepicker', $.proxy(this.toggle, this));
            this.element.on('keydown.daterangepicker', $.proxy(this.toggle, this));
        }

        //
        // if attached to a text input, set the initial value
        //

        this.updateElement();

    };

    DateRangePicker.prototype = {

        constructor: DateRangePicker,

        setStartDate: function(startDate) {
            if (typeof startDate === 'string')
                this.startDate = moment(startDate, this.locale.format);

            if (typeof startDate === 'object')
                this.startDate = moment(startDate);

            if (!this.timePicker)
                this.startDate = this.startDate.startOf('day');

            if (this.timePicker && this.timePickerIncrement)
                this.startDate.minute(Math.round(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement);

            if (this.minDate && this.startDate.isBefore(this.minDate)) {
                this.startDate = this.minDate.clone();
                if (this.timePicker && this.timePickerIncrement)
                    this.startDate.minute(Math.round(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement);
            }

            if (this.maxDate && this.startDate.isAfter(this.maxDate)) {
                this.startDate = this.maxDate.clone();
                if (this.timePicker && this.timePickerIncrement)
                    this.startDate.minute(Math.floor(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement);
            }

            if (!this.isShowing)
                this.updateElement();

            this.updateMonthsInView();
        },

        setEndDate: function(endDate) {
            if (typeof endDate === 'string')
                this.endDate = moment(endDate, this.locale.format);

            if (typeof endDate === 'object')
                this.endDate = moment(endDate);

            if (!this.timePicker)
                this.endDate = this.endDate.endOf('day');

            if (this.timePicker && this.timePickerIncrement)
                this.endDate.minute(Math.round(this.endDate.minute() / this.timePickerIncrement) * this.timePickerIncrement);

            if (this.endDate.isBefore(this.startDate))
                this.endDate = this.startDate.clone();

            if (this.maxDate && this.endDate.isAfter(this.maxDate))
                this.endDate = this.maxDate.clone();

            if (this.maxSpan && this.startDate.clone().add(this.maxSpan).isBefore(this.endDate))
                this.endDate = this.startDate.clone().add(this.maxSpan);

            this.previousRightTime = this.endDate.clone();

            this.container.find('.drp-selected').html(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));

            if (!this.isShowing)
                this.updateElement();

            this.updateMonthsInView();
        },

        isInvalidDate: function() {
            return false;
        },

        isCustomDate: function() {
            return false;
        },

        updateView: function() {
            if (this.timePicker) {
                this.renderTimePicker('left');
                this.renderTimePicker('right');
                if (!this.endDate) {
                    this.container.find('.right .calendar-time select').attr('disabled', 'disabled').addClass('disabled');
                } else {
                    this.container.find('.right .calendar-time select').removeAttr('disabled').removeClass('disabled');
                }
            }
            if (this.endDate)
                this.container.find('.drp-selected').html(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));
            this.updateMonthsInView();
            this.updateCalendars();
            this.updateFormInputs();
        },

        updateMonthsInView: function() {
            if (this.endDate) {

                //if both dates are visible already, do nothing
                if (!this.singleDatePicker && this.leftCalendar.month && this.rightCalendar.month &&
                    (this.startDate.format('YYYY-MM') == this.leftCalendar.month.format('YYYY-MM') || this.startDate.format('YYYY-MM') == this.rightCalendar.month.format('YYYY-MM'))
                    &&
                    (this.endDate.format('YYYY-MM') == this.leftCalendar.month.format('YYYY-MM') || this.endDate.format('YYYY-MM') == this.rightCalendar.month.format('YYYY-MM'))
                    ) {
                    return;
                }

                this.leftCalendar.month = this.startDate.clone().date(2);
                if (!this.linkedCalendars && (this.endDate.month() != this.startDate.month() || this.endDate.year() != this.startDate.year())) {
                    this.rightCalendar.month = this.endDate.clone().date(2);
                } else {
                    this.rightCalendar.month = this.startDate.clone().date(2).add(1, 'month');
                }

            } else {
                if (this.leftCalendar.month.format('YYYY-MM') != this.startDate.format('YYYY-MM') && this.rightCalendar.month.format('YYYY-MM') != this.startDate.format('YYYY-MM')) {
                    this.leftCalendar.month = this.startDate.clone().date(2);
                    this.rightCalendar.month = this.startDate.clone().date(2).add(1, 'month');
                }
            }
            if (this.maxDate && this.linkedCalendars && !this.singleDatePicker && this.rightCalendar.month > this.maxDate) {
              this.rightCalendar.month = this.maxDate.clone().date(2);
              this.leftCalendar.month = this.maxDate.clone().date(2).subtract(1, 'month');
            }
        },

        updateCalendars: function() {

            if (this.timePicker) {
                var hour, minute, second;
                if (this.endDate) {
                    hour = parseInt(this.container.find('.left .hourselect').val(), 10);
                    minute = parseInt(this.container.find('.left .minuteselect').val(), 10);
                    if (isNaN(minute)) {
                        minute = parseInt(this.container.find('.left .minuteselect option:last').val(), 10);
                    }
                    second = this.timePickerSeconds ? parseInt(this.container.find('.left .secondselect').val(), 10) : 0;
                    if (!this.timePicker24Hour) {
                        var ampm = this.container.find('.left .ampmselect').val();
                        if (ampm === 'PM' && hour < 12)
                            hour += 12;
                        if (ampm === 'AM' && hour === 12)
                            hour = 0;
                    }
                } else {
                    hour = parseInt(this.container.find('.right .hourselect').val(), 10);
                    minute = parseInt(this.container.find('.right .minuteselect').val(), 10);
                    if (isNaN(minute)) {
                        minute = parseInt(this.container.find('.right .minuteselect option:last').val(), 10);
                    }
                    second = this.timePickerSeconds ? parseInt(this.container.find('.right .secondselect').val(), 10) : 0;
                    if (!this.timePicker24Hour) {
                        var ampm = this.container.find('.right .ampmselect').val();
                        if (ampm === 'PM' && hour < 12)
                            hour += 12;
                        if (ampm === 'AM' && hour === 12)
                            hour = 0;
                    }
                }
                this.leftCalendar.month.hour(hour).minute(minute).second(second);
                this.rightCalendar.month.hour(hour).minute(minute).second(second);
            }

            this.renderCalendar('left');
            this.renderCalendar('right');

            //highlight any predefined range matching the current start and end dates
            this.container.find('.ranges li').removeClass('active');
            if (this.endDate == null) return;

            this.calculateChosenLabel();
        },

        renderCalendar: function(side) {

            //
            // Build the matrix of dates that will populate the calendar
            //

            var calendar = side == 'left' ? this.leftCalendar : this.rightCalendar;
            var month = calendar.month.month();
            var year = calendar.month.year();
            var hour = calendar.month.hour();
            var minute = calendar.month.minute();
            var second = calendar.month.second();
            var daysInMonth = moment([year, month]).daysInMonth();
            var firstDay = moment([year, month, 1]);
            var lastDay = moment([year, month, daysInMonth]);
            var lastMonth = moment(firstDay).subtract(1, 'month').month();
            var lastYear = moment(firstDay).subtract(1, 'month').year();
            var daysInLastMonth = moment([lastYear, lastMonth]).daysInMonth();
            var dayOfWeek = firstDay.day();

            //initialize a 6 rows x 7 columns array for the calendar
            var calendar = [];
            calendar.firstDay = firstDay;
            calendar.lastDay = lastDay;

            for (var i = 0; i < 6; i++) {
                calendar[i] = [];
            }

            //populate the calendar with date objects
            var startDay = daysInLastMonth - dayOfWeek + this.locale.firstDay + 1;
            if (startDay > daysInLastMonth)
                startDay -= 7;

            if (dayOfWeek == this.locale.firstDay)
                startDay = daysInLastMonth - 6;

            var curDate = moment([lastYear, lastMonth, startDay, 12, minute, second]);

            var col, row;
            for (var i = 0, col = 0, row = 0; i < 42; i++, col++, curDate = moment(curDate).add(24, 'hour')) {
                if (i > 0 && col % 7 === 0) {
                    col = 0;
                    row++;
                }
                calendar[row][col] = curDate.clone().hour(hour).minute(minute).second(second);
                curDate.hour(12);

                if (this.minDate && calendar[row][col].format('YYYY-MM-DD') == this.minDate.format('YYYY-MM-DD') && calendar[row][col].isBefore(this.minDate) && side == 'left') {
                    calendar[row][col] = this.minDate.clone();
                }

                if (this.maxDate && calendar[row][col].format('YYYY-MM-DD') == this.maxDate.format('YYYY-MM-DD') && calendar[row][col].isAfter(this.maxDate) && side == 'right') {
                    calendar[row][col] = this.maxDate.clone();
                }

            }

            //make the calendar object available to hoverDate/clickDate
            if (side == 'left') {
                this.leftCalendar.calendar = calendar;
            } else {
                this.rightCalendar.calendar = calendar;
            }

            //
            // Display the calendar
            //

            var minDate = side == 'left' ? this.minDate : this.startDate;
            var maxDate = this.maxDate;
            var selected = side == 'left' ? this.startDate : this.endDate;
            var arrow = this.locale.direction == 'ltr' ? {left: 'chevron-left', right: 'chevron-right'} : {left: 'chevron-right', right: 'chevron-left'};

            var html = '<table class="table-condensed">';
            html += '<thead>';
            html += '<tr>';

            // add empty cell for week number
            if (this.showWeekNumbers || this.showISOWeekNumbers)
                html += '<th></th>';

            if ((!minDate || minDate.isBefore(calendar.firstDay)) && (!this.linkedCalendars || side == 'left')) {
                html += '<th class="prev available"><span></span></th>';
            } else {
                html += '<th></th>';
            }

            var dateHtml = this.locale.monthNames[calendar[1][1].month()] + calendar[1][1].format(" YYYY");

            if (this.showDropdowns) {
                var currentMonth = calendar[1][1].month();
                var currentYear = calendar[1][1].year();
                var maxYear = (maxDate && maxDate.year()) || (this.maxYear);
                var minYear = (minDate && minDate.year()) || (this.minYear);
                var inMinYear = currentYear == minYear;
                var inMaxYear = currentYear == maxYear;

                var monthHtml = '<select class="monthselect">';
                for (var m = 0; m < 12; m++) {
                    if ((!inMinYear || (minDate && m >= minDate.month())) && (!inMaxYear || (maxDate && m <= maxDate.month()))) {
                        monthHtml += "<option value='" + m + "'" +
                            (m === currentMonth ? " selected='selected'" : "") +
                            ">" + this.locale.monthNames[m] + "</option>";
                    } else {
                        monthHtml += "<option value='" + m + "'" +
                            (m === currentMonth ? " selected='selected'" : "") +
                            " disabled='disabled'>" + this.locale.monthNames[m] + "</option>";
                    }
                }
                monthHtml += "</select>";

                var yearHtml = '<select class="yearselect">';
                for (var y = minYear; y <= maxYear; y++) {
                    yearHtml += '<option value="' + y + '"' +
                        (y === currentYear ? ' selected="selected"' : '') +
                        '>' + y + '</option>';
                }
                yearHtml += '</select>';

                dateHtml = monthHtml + yearHtml;
            }

            html += '<th colspan="5" class="month">' + dateHtml + '</th>';
            if ((!maxDate || maxDate.isAfter(calendar.lastDay)) && (!this.linkedCalendars || side == 'right' || this.singleDatePicker)) {
                html += '<th class="next available"><span></span></th>';
            } else {
                html += '<th></th>';
            }

            html += '</tr>';
            html += '<tr>';

            // add week number label
            if (this.showWeekNumbers || this.showISOWeekNumbers)
                html += '<th class="week">' + this.locale.weekLabel + '</th>';

            $.each(this.locale.daysOfWeek, function(index, dayOfWeek) {
                html += '<th>' + dayOfWeek + '</th>';
            });

            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            //adjust maxDate to reflect the maxSpan setting in order to
            //grey out end dates beyond the maxSpan
            if (this.endDate == null && this.maxSpan) {
                var maxLimit = this.startDate.clone().add(this.maxSpan).endOf('day');
                if (!maxDate || maxLimit.isBefore(maxDate)) {
                    maxDate = maxLimit;
                }
            }

            for (var row = 0; row < 6; row++) {
                html += '<tr>';

                // add week number
                if (this.showWeekNumbers)
                    html += '<td class="week">' + calendar[row][0].week() + '</td>';
                else if (this.showISOWeekNumbers)
                    html += '<td class="week">' + calendar[row][0].isoWeek() + '</td>';

                for (var col = 0; col < 7; col++) {

                    var classes = [];

                    //highlight today's date
                    if (calendar[row][col].isSame(new Date(), "day"))
                        classes.push('today');

                    //highlight weekends
                    if (calendar[row][col].isoWeekday() > 5)
                        classes.push('weekend');

                    //grey out the dates in other months displayed at beginning and end of this calendar
                    if (calendar[row][col].month() != calendar[1][1].month())
                        classes.push('off', 'ends');

                    //don't allow selection of dates before the minimum date
                    if (this.minDate && calendar[row][col].isBefore(this.minDate, 'day'))
                        classes.push('off', 'disabled');

                    //don't allow selection of dates after the maximum date
                    if (maxDate && calendar[row][col].isAfter(maxDate, 'day'))
                        classes.push('off', 'disabled');

                    //don't allow selection of date if a custom function decides it's invalid
                    if (this.isInvalidDate(calendar[row][col]))
                        classes.push('off', 'disabled');

                    //highlight the currently selected start date
                    if (calendar[row][col].format('YYYY-MM-DD') == this.startDate.format('YYYY-MM-DD'))
                        classes.push('active', 'start-date');

                    //highlight the currently selected end date
                    if (this.endDate != null && calendar[row][col].format('YYYY-MM-DD') == this.endDate.format('YYYY-MM-DD'))
                        classes.push('active', 'end-date');

                    //highlight dates in-between the selected dates
                    if (this.endDate != null && calendar[row][col] > this.startDate && calendar[row][col] < this.endDate)
                        classes.push('in-range');

                    //apply custom classes for this date
                    var isCustom = this.isCustomDate(calendar[row][col]);
                    if (isCustom !== false) {
                        if (typeof isCustom === 'string')
                            classes.push(isCustom);
                        else
                            Array.prototype.push.apply(classes, isCustom);
                    }

                    var cname = '', disabled = false;
                    for (var i = 0; i < classes.length; i++) {
                        cname += classes[i] + ' ';
                        if (classes[i] == 'disabled')
                            disabled = true;
                    }
                    if (!disabled)
                        cname += 'available';

                    html += '<td class="' + cname.replace(/^\s+|\s+$/g, '') + '" data-title="' + 'r' + row + 'c' + col + '">' + calendar[row][col].date() + '</td>';

                }
                html += '</tr>';
            }

            html += '</tbody>';
            html += '</table>';

            this.container.find('.drp-calendar.' + side + ' .calendar-table').html(html);

        },

        renderTimePicker: function(side) {

            // Don't bother updating the time picker if it's currently disabled
            // because an end date hasn't been clicked yet
            if (side == 'right' && !this.endDate) return;

            var html, selected, minDate, maxDate = this.maxDate;

            if (this.maxSpan && (!this.maxDate || this.startDate.clone().add(this.maxSpan).isBefore(this.maxDate)))
                maxDate = this.startDate.clone().add(this.maxSpan);

            if (side == 'left') {
                selected = this.startDate.clone();
                minDate = this.minDate;
            } else if (side == 'right') {
                selected = this.endDate.clone();
                minDate = this.startDate;

                //Preserve the time already selected
                var timeSelector = this.container.find('.drp-calendar.right .calendar-time');
                if (timeSelector.html() != '') {

                    selected.hour(!isNaN(selected.hour()) ? selected.hour() : timeSelector.find('.hourselect option:selected').val());
                    selected.minute(!isNaN(selected.minute()) ? selected.minute() : timeSelector.find('.minuteselect option:selected').val());
                    selected.second(!isNaN(selected.second()) ? selected.second() : timeSelector.find('.secondselect option:selected').val());

                    if (!this.timePicker24Hour) {
                        var ampm = timeSelector.find('.ampmselect option:selected').val();
                        if (ampm === 'PM' && selected.hour() < 12)
                            selected.hour(selected.hour() + 12);
                        if (ampm === 'AM' && selected.hour() === 12)
                            selected.hour(0);
                    }

                }

                if (selected.isBefore(this.startDate))
                    selected = this.startDate.clone();

                if (maxDate && selected.isAfter(maxDate))
                    selected = maxDate.clone();

            }

            //
            // hours
            //

            html = '<select class="hourselect">';

            var start = this.timePicker24Hour ? 0 : 1;
            var end = this.timePicker24Hour ? 23 : 12;

            for (var i = start; i <= end; i++) {
                var i_in_24 = i;
                if (!this.timePicker24Hour)
                    i_in_24 = selected.hour() >= 12 ? (i == 12 ? 12 : i + 12) : (i == 12 ? 0 : i);

                var time = selected.clone().hour(i_in_24);
                var disabled = false;
                if (minDate && time.minute(59).isBefore(minDate))
                    disabled = true;
                if (maxDate && time.minute(0).isAfter(maxDate))
                    disabled = true;

                if (i_in_24 == selected.hour() && !disabled) {
                    html += '<option value="' + i + '" selected="selected">' + i + '</option>';
                } else if (disabled) {
                    html += '<option value="' + i + '" disabled="disabled" class="disabled">' + i + '</option>';
                } else {
                    html += '<option value="' + i + '">' + i + '</option>';
                }
            }

            html += '</select> ';

            //
            // minutes
            //

            html += ': <select class="minuteselect">';

            for (var i = 0; i < 60; i += this.timePickerIncrement) {
                var padded = i < 10 ? '0' + i : i;
                var time = selected.clone().minute(i);

                var disabled = false;
                if (minDate && time.second(59).isBefore(minDate))
                    disabled = true;
                if (maxDate && time.second(0).isAfter(maxDate))
                    disabled = true;

                if (selected.minute() == i && !disabled) {
                    html += '<option value="' + i + '" selected="selected">' + padded + '</option>';
                } else if (disabled) {
                    html += '<option value="' + i + '" disabled="disabled" class="disabled">' + padded + '</option>';
                } else {
                    html += '<option value="' + i + '">' + padded + '</option>';
                }
            }

            html += '</select> ';

            //
            // seconds
            //

            if (this.timePickerSeconds) {
                html += ': <select class="secondselect">';

                for (var i = 0; i < 60; i++) {
                    var padded = i < 10 ? '0' + i : i;
                    var time = selected.clone().second(i);

                    var disabled = false;
                    if (minDate && time.isBefore(minDate))
                        disabled = true;
                    if (maxDate && time.isAfter(maxDate))
                        disabled = true;

                    if (selected.second() == i && !disabled) {
                        html += '<option value="' + i + '" selected="selected">' + padded + '</option>';
                    } else if (disabled) {
                        html += '<option value="' + i + '" disabled="disabled" class="disabled">' + padded + '</option>';
                    } else {
                        html += '<option value="' + i + '">' + padded + '</option>';
                    }
                }

                html += '</select> ';
            }

            //
            // AM/PM
            //

            if (!this.timePicker24Hour) {
                html += '<select class="ampmselect">';

                var am_html = '';
                var pm_html = '';

                if (minDate && selected.clone().hour(12).minute(0).second(0).isBefore(minDate))
                    am_html = ' disabled="disabled" class="disabled"';

                if (maxDate && selected.clone().hour(0).minute(0).second(0).isAfter(maxDate))
                    pm_html = ' disabled="disabled" class="disabled"';

                if (selected.hour() >= 12) {
                    html += '<option value="AM"' + am_html + '>AM</option><option value="PM" selected="selected"' + pm_html + '>PM</option>';
                } else {
                    html += '<option value="AM" selected="selected"' + am_html + '>AM</option><option value="PM"' + pm_html + '>PM</option>';
                }

                html += '</select>';
            }

            this.container.find('.drp-calendar.' + side + ' .calendar-time').html(html);

        },

        updateFormInputs: function() {

            if (this.singleDatePicker || (this.endDate && (this.startDate.isBefore(this.endDate) || this.startDate.isSame(this.endDate)))) {
                this.container.find('button.applyBtn').removeAttr('disabled');
            } else {
                this.container.find('button.applyBtn').attr('disabled', 'disabled');
            }

        },

        move: function() {
            var parentOffset = { top: 0, left: 0 },
                containerTop;
            var parentRightEdge = $(window).width();
            if (!this.parentEl.is('body')) {
                parentOffset = {
                    top: this.parentEl.offset().top - this.parentEl.scrollTop(),
                    left: this.parentEl.offset().left - this.parentEl.scrollLeft()
                };
                parentRightEdge = this.parentEl[0].clientWidth + this.parentEl.offset().left;
            }

            if (this.drops == 'up')
                containerTop = this.element.offset().top - this.container.outerHeight() - parentOffset.top;
            else
                containerTop = this.element.offset().top + this.element.outerHeight() - parentOffset.top;

            // Force the container to it's actual width
            this.container.css({
              top: 0,
              left: 0,
              right: 'auto'
            });
            var containerWidth = this.container.outerWidth();

            this.container[this.drops == 'up' ? 'addClass' : 'removeClass']('drop-up');

            if (this.opens == 'left') {
                var containerRight = parentRightEdge - this.element.offset().left - this.element.outerWidth();
                if (containerWidth + containerRight > $(window).width()) {
                    this.container.css({
                        top: containerTop,
                        right: 'auto',
                        left: 9
                    });
                } else {
                    this.container.css({
                        top: containerTop,
                        right: containerRight,
                        left: 'auto'
                    });
                }
            } else if (this.opens == 'center') {
                var containerLeft = this.element.offset().left - parentOffset.left + this.element.outerWidth() / 2
                                        - containerWidth / 2;
                if (containerLeft < 0) {
                    this.container.css({
                        top: containerTop,
                        right: 'auto',
                        left: 9
                    });
                } else if (containerLeft + containerWidth > $(window).width()) {
                    this.container.css({
                        top: containerTop,
                        left: 'auto',
                        right: 0
                    });
                } else {
                    this.container.css({
                        top: containerTop,
                        left: containerLeft,
                        right: 'auto'
                    });
                }
            } else {
                var containerLeft = this.element.offset().left - parentOffset.left;
                if (containerLeft + containerWidth > $(window).width()) {
                    this.container.css({
                        top: containerTop,
                        left: 'auto',
                        right: 0
                    });
                } else {
                    this.container.css({
                        top: containerTop,
                        left: containerLeft,
                        right: 'auto'
                    });
                }
            }
        },

        show: function(e) {
            if (this.isShowing) return;

            // Create a click proxy that is private to this instance of datepicker, for unbinding
            this._outsideClickProxy = $.proxy(function(e) { this.outsideClick(e); }, this);

            // Bind global datepicker mousedown for hiding and
            $(document)
              .on('mousedown.daterangepicker', this._outsideClickProxy)
              // also support mobile devices
              .on('touchend.daterangepicker', this._outsideClickProxy)
              // also explicitly play nice with Bootstrap dropdowns, which stopPropagation when clicking them
              .on('click.daterangepicker', '[data-toggle=dropdown]', this._outsideClickProxy)
              // and also close when focus changes to outside the picker (eg. tabbing between controls)
              .on('focusin.daterangepicker', this._outsideClickProxy);

            // Reposition the picker if the window is resized while it's open
            $(window).on('resize.daterangepicker', $.proxy(function(e) { this.move(e); }, this));

            this.oldStartDate = this.startDate.clone();
            this.oldEndDate = this.endDate.clone();
            this.previousRightTime = this.endDate.clone();

            this.updateView();
            this.container.show();
            this.move();
            this.element.trigger('show.daterangepicker', this);
            this.isShowing = true;
        },

        hide: function(e) {
            if (!this.isShowing) return;

            //incomplete date selection, revert to last values
            if (!this.endDate) {
                this.startDate = this.oldStartDate.clone();
                this.endDate = this.oldEndDate.clone();
            }

            //if a new date range was selected, invoke the user callback function
            if (!this.startDate.isSame(this.oldStartDate) || !this.endDate.isSame(this.oldEndDate))
                this.callback(this.startDate.clone(), this.endDate.clone(), this.chosenLabel);

            //if picker is attached to a text input, update it
            this.updateElement();

            $(document).off('.daterangepicker');
            $(window).off('.daterangepicker');
            this.container.hide();
            this.element.trigger('hide.daterangepicker', this);
            this.isShowing = false;
        },

        toggle: function(e) {
            if (this.isShowing) {
                this.hide();
            } else {
                this.show();
            }
        },

        outsideClick: function(e) {
            var target = $(e.target);
            // if the page is clicked anywhere except within the daterangerpicker/button
            // itself then call this.hide()
            if (
                // ie modal dialog fix
                e.type == "focusin" ||
                target.closest(this.element).length ||
                target.closest(this.container).length ||
                target.closest('.calendar-table').length
                ) return;
            this.hide();
            this.element.trigger('outsideClick.daterangepicker', this);
        },

        showCalendars: function() {
            this.container.addClass('show-calendar');
            this.move();
            this.element.trigger('showCalendar.daterangepicker', this);
        },

        hideCalendars: function() {
            this.container.removeClass('show-calendar');
            this.element.trigger('hideCalendar.daterangepicker', this);
        },

        clickRange: function(e) {
            var label = e.target.getAttribute('data-range-key');
            this.chosenLabel = label;
            if (label == this.locale.customRangeLabel) {
                this.showCalendars();
            } else {
                var dates = this.ranges[label];
                this.startDate = dates[0];
                this.endDate = dates[1];

                if (!this.timePicker) {
                    this.startDate.startOf('day');
                    this.endDate.endOf('day');
                }

                if (!this.alwaysShowCalendars)
                    this.hideCalendars();
                this.clickApply();
            }
        },

        clickPrev: function(e) {
            var cal = $(e.target).parents('.drp-calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.subtract(1, 'month');
                if (this.linkedCalendars)
                    this.rightCalendar.month.subtract(1, 'month');
            } else {
                this.rightCalendar.month.subtract(1, 'month');
            }
            this.updateCalendars();
        },

        clickNext: function(e) {
            var cal = $(e.target).parents('.drp-calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.add(1, 'month');
            } else {
                this.rightCalendar.month.add(1, 'month');
                if (this.linkedCalendars)
                    this.leftCalendar.month.add(1, 'month');
            }
            this.updateCalendars();
        },

        hoverDate: function(e) {

            //ignore dates that can't be selected
            if (!$(e.target).hasClass('available')) return;

            var title = $(e.target).attr('data-title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.drp-calendar');
            var date = cal.hasClass('left') ? this.leftCalendar.calendar[row][col] : this.rightCalendar.calendar[row][col];

            //highlight the dates between the start date and the date being hovered as a potential end date
            var leftCalendar = this.leftCalendar;
            var rightCalendar = this.rightCalendar;
            var startDate = this.startDate;
            if (!this.endDate) {
                this.container.find('.drp-calendar tbody td').each(function(index, el) {

                    //skip week numbers, only look at dates
                    if ($(el).hasClass('week')) return;

                    var title = $(el).attr('data-title');
                    var row = title.substr(1, 1);
                    var col = title.substr(3, 1);
                    var cal = $(el).parents('.drp-calendar');
                    var dt = cal.hasClass('left') ? leftCalendar.calendar[row][col] : rightCalendar.calendar[row][col];

                    if ((dt.isAfter(startDate) && dt.isBefore(date)) || dt.isSame(date, 'day')) {
                        $(el).addClass('in-range');
                    } else {
                        $(el).removeClass('in-range');
                    }

                });
            }

        },

        clickDate: function(e) {

            if (!$(e.target).hasClass('available')) return;

            var title = $(e.target).attr('data-title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.drp-calendar');
            var date = cal.hasClass('left') ? this.leftCalendar.calendar[row][col] : this.rightCalendar.calendar[row][col];

            //
            // this function needs to do a few things:
            // * alternate between selecting a start and end date for the range,
            // * if the time picker is enabled, apply the hour/minute/second from the select boxes to the clicked date
            // * if autoapply is enabled, and an end date was chosen, apply the selection
            // * if single date picker mode, and time picker isn't enabled, apply the selection immediately
            // * if one of the inputs above the calendars was focused, cancel that manual input
            //

            if (this.endDate || date.isBefore(this.startDate, 'day')) { //picking start
                if (this.timePicker) {
                    var hour = parseInt(this.container.find('.left .hourselect').val(), 10);
                    if (!this.timePicker24Hour) {
                        var ampm = this.container.find('.left .ampmselect').val();
                        if (ampm === 'PM' && hour < 12)
                            hour += 12;
                        if (ampm === 'AM' && hour === 12)
                            hour = 0;
                    }
                    var minute = parseInt(this.container.find('.left .minuteselect').val(), 10);
                    if (isNaN(minute)) {
                        minute = parseInt(this.container.find('.left .minuteselect option:last').val(), 10);
                    }
                    var second = this.timePickerSeconds ? parseInt(this.container.find('.left .secondselect').val(), 10) : 0;
                    date = date.clone().hour(hour).minute(minute).second(second);
                }
                this.endDate = null;
                this.setStartDate(date.clone());
            } else if (!this.endDate && date.isBefore(this.startDate)) {
                //special case: clicking the same date for start/end,
                //but the time of the end date is before the start date
                this.setEndDate(this.startDate.clone());
            } else { // picking end
                if (this.timePicker) {
                    var hour = parseInt(this.container.find('.right .hourselect').val(), 10);
                    if (!this.timePicker24Hour) {
                        var ampm = this.container.find('.right .ampmselect').val();
                        if (ampm === 'PM' && hour < 12)
                            hour += 12;
                        if (ampm === 'AM' && hour === 12)
                            hour = 0;
                    }
                    var minute = parseInt(this.container.find('.right .minuteselect').val(), 10);
                    if (isNaN(minute)) {
                        minute = parseInt(this.container.find('.right .minuteselect option:last').val(), 10);
                    }
                    var second = this.timePickerSeconds ? parseInt(this.container.find('.right .secondselect').val(), 10) : 0;
                    date = date.clone().hour(hour).minute(minute).second(second);
                }
                this.setEndDate(date.clone());
                if (this.autoApply) {
                  this.calculateChosenLabel();
                  this.clickApply();
                }
            }

            if (this.singleDatePicker) {
                this.setEndDate(this.startDate);
                if (!this.timePicker)
                    this.clickApply();
            }

            this.updateView();

            //This is to cancel the blur event handler if the mouse was in one of the inputs
            e.stopPropagation();

        },

        calculateChosenLabel: function () {
            var customRange = true;
            var i = 0;
            for (var range in this.ranges) {
              if (this.timePicker) {
                    var format = this.timePickerSeconds ? "YYYY-MM-DD HH:mm:ss" : "YYYY-MM-DD HH:mm";
                    //ignore times when comparing dates if time picker seconds is not enabled
                    if (this.startDate.format(format) == this.ranges[range][0].format(format) && this.endDate.format(format) == this.ranges[range][1].format(format)) {
                        customRange = false;
                        this.chosenLabel = this.container.find('.ranges li:eq(' + i + ')').addClass('active').attr('data-range-key');
                        break;
                    }
                } else {
                    //ignore times when comparing dates if time picker is not enabled
                    if (this.startDate.format('YYYY-MM-DD') == this.ranges[range][0].format('YYYY-MM-DD') && this.endDate.format('YYYY-MM-DD') == this.ranges[range][1].format('YYYY-MM-DD')) {
                        customRange = false;
                        this.chosenLabel = this.container.find('.ranges li:eq(' + i + ')').addClass('active').attr('data-range-key');
                        break;
                    }
                }
                i++;
            }
            if (customRange) {
                if (this.showCustomRangeLabel) {
                    this.chosenLabel = this.container.find('.ranges li:last').addClass('active').attr('data-range-key');
                } else {
                    this.chosenLabel = null;
                }
                this.showCalendars();
            }
        },

        clickApply: function(e) {
            this.hide();
            this.element.trigger('apply.daterangepicker', this);
        },

        clickCancel: function(e) {
            this.startDate = this.oldStartDate;
            this.endDate = this.oldEndDate;
            this.hide();
            this.element.trigger('cancel.daterangepicker', this);
        },

        monthOrYearChanged: function(e) {
            var isLeft = $(e.target).closest('.drp-calendar').hasClass('left'),
                leftOrRight = isLeft ? 'left' : 'right',
                cal = this.container.find('.drp-calendar.'+leftOrRight);

            // Month must be Number for new moment versions
            var month = parseInt(cal.find('.monthselect').val(), 10);
            var year = cal.find('.yearselect').val();

            if (!isLeft) {
                if (year < this.startDate.year() || (year == this.startDate.year() && month < this.startDate.month())) {
                    month = this.startDate.month();
                    year = this.startDate.year();
                }
            }

            if (this.minDate) {
                if (year < this.minDate.year() || (year == this.minDate.year() && month < this.minDate.month())) {
                    month = this.minDate.month();
                    year = this.minDate.year();
                }
            }

            if (this.maxDate) {
                if (year > this.maxDate.year() || (year == this.maxDate.year() && month > this.maxDate.month())) {
                    month = this.maxDate.month();
                    year = this.maxDate.year();
                }
            }

            if (isLeft) {
                this.leftCalendar.month.month(month).year(year);
                if (this.linkedCalendars)
                    this.rightCalendar.month = this.leftCalendar.month.clone().add(1, 'month');
            } else {
                this.rightCalendar.month.month(month).year(year);
                if (this.linkedCalendars)
                    this.leftCalendar.month = this.rightCalendar.month.clone().subtract(1, 'month');
            }
            this.updateCalendars();
        },

        timeChanged: function(e) {

            var cal = $(e.target).closest('.drp-calendar'),
                isLeft = cal.hasClass('left');

            var hour = parseInt(cal.find('.hourselect').val(), 10);
            var minute = parseInt(cal.find('.minuteselect').val(), 10);
            if (isNaN(minute)) {
                minute = parseInt(cal.find('.minuteselect option:last').val(), 10);
            }
            var second = this.timePickerSeconds ? parseInt(cal.find('.secondselect').val(), 10) : 0;

            if (!this.timePicker24Hour) {
                var ampm = cal.find('.ampmselect').val();
                if (ampm === 'PM' && hour < 12)
                    hour += 12;
                if (ampm === 'AM' && hour === 12)
                    hour = 0;
            }

            if (isLeft) {
                var start = this.startDate.clone();
                start.hour(hour);
                start.minute(minute);
                start.second(second);
                this.setStartDate(start);
                if (this.singleDatePicker) {
                    this.endDate = this.startDate.clone();
                } else if (this.endDate && this.endDate.format('YYYY-MM-DD') == start.format('YYYY-MM-DD') && this.endDate.isBefore(start)) {
                    this.setEndDate(start.clone());
                }
            } else if (this.endDate) {
                var end = this.endDate.clone();
                end.hour(hour);
                end.minute(minute);
                end.second(second);
                this.setEndDate(end);
            }

            //update the calendars so all clickable dates reflect the new time component
            this.updateCalendars();

            //update the form inputs above the calendars with the new time
            this.updateFormInputs();

            //re-render the time pickers because changing one selection can affect what's enabled in another
            this.renderTimePicker('left');
            this.renderTimePicker('right');

        },

        elementChanged: function() {
            if (!this.element.is('input')) return;
            if (!this.element.val().length) return;

            var dateString = this.element.val().split(this.locale.separator),
                start = null,
                end = null;

            if (dateString.length === 2) {
                start = moment(dateString[0], this.locale.format);
                end = moment(dateString[1], this.locale.format);
            }

            if (this.singleDatePicker || start === null || end === null) {
                start = moment(this.element.val(), this.locale.format);
                end = start;
            }

            if (!start.isValid() || !end.isValid()) return;

            this.setStartDate(start);
            this.setEndDate(end);
            this.updateView();
        },

        keydown: function(e) {
            //hide on tab or enter
            if ((e.keyCode === 9) || (e.keyCode === 13)) {
                this.hide();
            }

            //hide on esc and prevent propagation
            if (e.keyCode === 27) {
                e.preventDefault();
                e.stopPropagation();

                this.hide();
            }
        },

        updateElement: function() {
            if (this.element.is('input') && this.autoUpdateInput) {
                var newValue = this.startDate.format(this.locale.format);
                if (!this.singleDatePicker) {
                    newValue += this.locale.separator + this.endDate.format(this.locale.format);
                }
                if (newValue !== this.element.val()) {
                    this.element.val(newValue).trigger('change');
                }
            }
        },

        remove: function() {
            this.container.remove();
            this.element.off('.daterangepicker');
            this.element.removeData();
        }

    };

    $.fn.daterangepicker = function(options, callback) {
        var implementOptions = $.extend(true, {}, $.fn.daterangepicker.defaultOptions, options);
        this.each(function() {
            var el = $(this);
            if (el.data('daterangepicker'))
                el.data('daterangepicker').remove();
            el.data('daterangepicker', new DateRangePicker(el, implementOptions, callback));
        });
        return this;
    };

    return DateRangePicker;

}));


/***/ }),

/***/ "./node_modules/devbridge-autocomplete/dist/jquery.autocomplete.js":
/*!*************************************************************************!*\
  !*** ./node_modules/devbridge-autocomplete/dist/jquery.autocomplete.js ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
*  Ajax Autocomplete for jQuery, version 1.4.10
*  (c) 2017 Tomas Kirda
*
*  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
*  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
*/

/*jslint  browser: true, white: true, single: true, this: true, multivar: true */
/*global define, window, document, jQuery, exports, require */

// Expose plugin as an AMD module if AMD loader is present:
(function (factory) {
    "use strict";
    if (true) {
        // AMD. Register as an anonymous module.
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else {}
}(function ($) {
    'use strict';

    var
        utils = (function () {
            return {
                escapeRegExChars: function (value) {
                    return value.replace(/[|\\{}()[\]^$+*?.]/g, "\\$&");
                },
                createNode: function (containerClass) {
                    var div = document.createElement('div');
                    div.className = containerClass;
                    div.style.position = 'absolute';
                    div.style.display = 'none';
                    return div;
                }
            };
        }()),

        keys = {
            ESC: 27,
            TAB: 9,
            RETURN: 13,
            LEFT: 37,
            UP: 38,
            RIGHT: 39,
            DOWN: 40
        },

        noop = $.noop;

    function Autocomplete(el, options) {
        var that = this;

        // Shared variables:
        that.element = el;
        that.el = $(el);
        that.suggestions = [];
        that.badQueries = [];
        that.selectedIndex = -1;
        that.currentValue = that.element.value;
        that.timeoutId = null;
        that.cachedResponse = {};
        that.onChangeTimeout = null;
        that.onChange = null;
        that.isLocal = false;
        that.suggestionsContainer = null;
        that.noSuggestionsContainer = null;
        that.options = $.extend(true, {}, Autocomplete.defaults, options);
        that.classes = {
            selected: 'autocomplete-selected',
            suggestion: 'autocomplete-suggestion'
        };
        that.hint = null;
        that.hintValue = '';
        that.selection = null;

        // Initialize and set options:
        that.initialize();
        that.setOptions(options);
    }

    Autocomplete.utils = utils;

    $.Autocomplete = Autocomplete;

    Autocomplete.defaults = {
            ajaxSettings: {},
            autoSelectFirst: false,
            appendTo: 'body',
            serviceUrl: null,
            lookup: null,
            onSelect: null,
            width: 'auto',
            minChars: 1,
            maxHeight: 300,
            deferRequestBy: 0,
            params: {},
            formatResult: _formatResult,
            formatGroup: _formatGroup,
            delimiter: null,
            zIndex: 9999,
            type: 'GET',
            noCache: false,
            onSearchStart: noop,
            onSearchComplete: noop,
            onSearchError: noop,
            preserveInput: false,
            containerClass: 'autocomplete-suggestions',
            tabDisabled: false,
            dataType: 'text',
            currentRequest: null,
            triggerSelectOnValidInput: true,
            preventBadQueries: true,
            lookupFilter: _lookupFilter,
            paramName: 'query',
            transformResult: _transformResult,
            showNoSuggestionNotice: false,
            noSuggestionNotice: 'No results',
            orientation: 'bottom',
            forceFixPosition: false
    };

    function _lookupFilter(suggestion, originalQuery, queryLowerCase) {
        return suggestion.value.toLowerCase().indexOf(queryLowerCase) !== -1;
    };

    function _transformResult(response) {
        return typeof response === 'string' ? $.parseJSON(response) : response;
    };

    function _formatResult(suggestion, currentValue) {
        // Do not replace anything if the current value is empty
        if (!currentValue) {
            return suggestion.value;
        }

        var pattern = '(' + utils.escapeRegExChars(currentValue) + ')';

        return suggestion.value
            .replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/&lt;(\/?strong)&gt;/g, '<$1>');
    };

    function _formatGroup(suggestion, category) {
        return '<div class="autocomplete-group">' + category + '</div>';
    };

    Autocomplete.prototype = {

        initialize: function () {
            var that = this,
                suggestionSelector = '.' + that.classes.suggestion,
                selected = that.classes.selected,
                options = that.options,
                container;

            that.element.setAttribute('autocomplete', 'off');

            // html() deals with many types: htmlString or Element or Array or jQuery
            that.noSuggestionsContainer = $('<div class="autocomplete-no-suggestion"></div>')
                                          .html(this.options.noSuggestionNotice).get(0);

            that.suggestionsContainer = Autocomplete.utils.createNode(options.containerClass);

            container = $(that.suggestionsContainer);

            container.appendTo(options.appendTo || 'body');

            // Only set width if it was provided:
            if (options.width !== 'auto') {
                container.css('width', options.width);
            }

            // Listen for mouse over event on suggestions list:
            container.on('mouseover.autocomplete', suggestionSelector, function () {
                that.activate($(this).data('index'));
            });

            // Deselect active element when mouse leaves suggestions container:
            container.on('mouseout.autocomplete', function () {
                that.selectedIndex = -1;
                container.children('.' + selected).removeClass(selected);
            });

            // Listen for click event on suggestions list:
            container.on('click.autocomplete', suggestionSelector, function () {
                that.select($(this).data('index'));
            });

            container.on('click.autocomplete', function () {
                clearTimeout(that.blurTimeoutId);
            })

            that.fixPositionCapture = function () {
                if (that.visible) {
                    that.fixPosition();
                }
            };

            $(window).on('resize.autocomplete', that.fixPositionCapture);

            that.el.on('keydown.autocomplete', function (e) { that.onKeyPress(e); });
            that.el.on('keyup.autocomplete', function (e) { that.onKeyUp(e); });
            that.el.on('blur.autocomplete', function () { that.onBlur(); });
            that.el.on('focus.autocomplete', function () { that.onFocus(); });
            that.el.on('change.autocomplete', function (e) { that.onKeyUp(e); });
            that.el.on('input.autocomplete', function (e) { that.onKeyUp(e); });
        },

        onFocus: function () {
            var that = this;

            that.fixPosition();

            if (that.el.val().length >= that.options.minChars) {
                that.onValueChange();
            }
        },

        onBlur: function () {
            var that = this,
                options = that.options,
                value = that.el.val(),
                query = that.getQuery(value);

            // If user clicked on a suggestion, hide() will
            // be canceled, otherwise close suggestions
            that.blurTimeoutId = setTimeout(function () {
                that.hide();

                if (that.selection && that.currentValue !== query) {
                    (options.onInvalidateSelection || $.noop).call(that.element);
                }
            }, 200);
        },

        abortAjax: function () {
            var that = this;
            if (that.currentRequest) {
                that.currentRequest.abort();
                that.currentRequest = null;
            }
        },

        setOptions: function (suppliedOptions) {
            var that = this,
                options = $.extend({}, that.options, suppliedOptions);

            that.isLocal = Array.isArray(options.lookup);

            if (that.isLocal) {
                options.lookup = that.verifySuggestionsFormat(options.lookup);
            }

            options.orientation = that.validateOrientation(options.orientation, 'bottom');

            // Adjust height, width and z-index:
            $(that.suggestionsContainer).css({
                'max-height': options.maxHeight + 'px',
                'width': options.width + 'px',
                'z-index': options.zIndex
            });

            this.options = options;            
        },


        clearCache: function () {
            this.cachedResponse = {};
            this.badQueries = [];
        },

        clear: function () {
            this.clearCache();
            this.currentValue = '';
            this.suggestions = [];
        },

        disable: function () {
            var that = this;
            that.disabled = true;
            clearTimeout(that.onChangeTimeout);
            that.abortAjax();
        },

        enable: function () {
            this.disabled = false;
        },

        fixPosition: function () {
            // Use only when container has already its content

            var that = this,
                $container = $(that.suggestionsContainer),
                containerParent = $container.parent().get(0);
            // Fix position automatically when appended to body.
            // In other cases force parameter must be given.
            if (containerParent !== document.body && !that.options.forceFixPosition) {
                return;
            }

            // Choose orientation
            var orientation = that.options.orientation,
                containerHeight = $container.outerHeight(),
                height = that.el.outerHeight(),
                offset = that.el.offset(),
                styles = { 'top': offset.top, 'left': offset.left };

            if (orientation === 'auto') {
                var viewPortHeight = $(window).height(),
                    scrollTop = $(window).scrollTop(),
                    topOverflow = -scrollTop + offset.top - containerHeight,
                    bottomOverflow = scrollTop + viewPortHeight - (offset.top + height + containerHeight);

                orientation = (Math.max(topOverflow, bottomOverflow) === topOverflow) ? 'top' : 'bottom';
            }

            if (orientation === 'top') {
                styles.top += -containerHeight;
            } else {
                styles.top += height;
            }

            // If container is not positioned to body,
            // correct its position using offset parent offset
            if(containerParent !== document.body) {
                var opacity = $container.css('opacity'),
                    parentOffsetDiff;

                    if (!that.visible){
                        $container.css('opacity', 0).show();
                    }

                parentOffsetDiff = $container.offsetParent().offset();
                styles.top -= parentOffsetDiff.top;
                styles.top += containerParent.scrollTop;
                styles.left -= parentOffsetDiff.left;

                if (!that.visible){
                    $container.css('opacity', opacity).hide();
                }
            }

            if (that.options.width === 'auto') {
                styles.width = that.el.outerWidth() + 'px';
            }

            $container.css(styles);
        },

        isCursorAtEnd: function () {
            var that = this,
                valLength = that.el.val().length,
                selectionStart = that.element.selectionStart,
                range;

            if (typeof selectionStart === 'number') {
                return selectionStart === valLength;
            }
            if (document.selection) {
                range = document.selection.createRange();
                range.moveStart('character', -valLength);
                return valLength === range.text.length;
            }
            return true;
        },

        onKeyPress: function (e) {
            var that = this;

            // If suggestions are hidden and user presses arrow down, display suggestions:
            if (!that.disabled && !that.visible && e.which === keys.DOWN && that.currentValue) {
                that.suggest();
                return;
            }

            if (that.disabled || !that.visible) {
                return;
            }

            switch (e.which) {
                case keys.ESC:
                    that.el.val(that.currentValue);
                    that.hide();
                    break;
                case keys.RIGHT:
                    if (that.hint && that.options.onHint && that.isCursorAtEnd()) {
                        that.selectHint();
                        break;
                    }
                    return;
                case keys.TAB:
                    if (that.hint && that.options.onHint) {
                        that.selectHint();
                        return;
                    }
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    if (that.options.tabDisabled === false) {
                        return;
                    }
                    break;
                case keys.RETURN:
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    break;
                case keys.UP:
                    that.moveUp();
                    break;
                case keys.DOWN:
                    that.moveDown();
                    break;
                default:
                    return;
            }

            // Cancel event if function did not return:
            e.stopImmediatePropagation();
            e.preventDefault();
        },

        onKeyUp: function (e) {
            var that = this;

            if (that.disabled) {
                return;
            }

            switch (e.which) {
                case keys.UP:
                case keys.DOWN:
                    return;
            }

            clearTimeout(that.onChangeTimeout);

            if (that.currentValue !== that.el.val()) {
                that.findBestHint();
                if (that.options.deferRequestBy > 0) {
                    // Defer lookup in case when value changes very quickly:
                    that.onChangeTimeout = setTimeout(function () {
                        that.onValueChange();
                    }, that.options.deferRequestBy);
                } else {
                    that.onValueChange();
                }
            }
        },

        onValueChange: function () {
            if (this.ignoreValueChange) {
                this.ignoreValueChange = false;
                return;
            }

            var that = this,
                options = that.options,
                value = that.el.val(),
                query = that.getQuery(value);

            if (that.selection && that.currentValue !== query) {
                that.selection = null;
                (options.onInvalidateSelection || $.noop).call(that.element);
            }

            clearTimeout(that.onChangeTimeout);
            that.currentValue = value;
            that.selectedIndex = -1;

            // Check existing suggestion for the match before proceeding:
            if (options.triggerSelectOnValidInput && that.isExactMatch(query)) {
                that.select(0);
                return;
            }

            if (query.length < options.minChars) {
                that.hide();
            } else {
                that.getSuggestions(query);
            }
        },

        isExactMatch: function (query) {
            var suggestions = this.suggestions;

            return (suggestions.length === 1 && suggestions[0].value.toLowerCase() === query.toLowerCase());
        },

        getQuery: function (value) {
            var delimiter = this.options.delimiter,
                parts;

            if (!delimiter) {
                return value;
            }
            parts = value.split(delimiter);
            return $.trim(parts[parts.length - 1]);
        },

        getSuggestionsLocal: function (query) {
            var that = this,
                options = that.options,
                queryLowerCase = query.toLowerCase(),
                filter = options.lookupFilter,
                limit = parseInt(options.lookupLimit, 10),
                data;

            data = {
                suggestions: $.grep(options.lookup, function (suggestion) {
                    return filter(suggestion, query, queryLowerCase);
                })
            };

            if (limit && data.suggestions.length > limit) {
                data.suggestions = data.suggestions.slice(0, limit);
            }

            return data;
        },

        getSuggestions: function (q) {
            var response,
                that = this,
                options = that.options,
                serviceUrl = options.serviceUrl,
                params,
                cacheKey,
                ajaxSettings;

            options.params[options.paramName] = q;

            if (options.onSearchStart.call(that.element, options.params) === false) {
                return;
            }

            params = options.ignoreParams ? null : options.params;

            if ($.isFunction(options.lookup)){
                options.lookup(q, function (data) {
                    that.suggestions = data.suggestions;
                    that.suggest();
                    options.onSearchComplete.call(that.element, q, data.suggestions);
                });
                return;
            }

            if (that.isLocal) {
                response = that.getSuggestionsLocal(q);
            } else {
                if ($.isFunction(serviceUrl)) {
                    serviceUrl = serviceUrl.call(that.element, q);
                }
                cacheKey = serviceUrl + '?' + $.param(params || {});
                response = that.cachedResponse[cacheKey];
            }

            if (response && Array.isArray(response.suggestions)) {
                that.suggestions = response.suggestions;
                that.suggest();
                options.onSearchComplete.call(that.element, q, response.suggestions);
            } else if (!that.isBadQuery(q)) {
                that.abortAjax();

                ajaxSettings = {
                    url: serviceUrl,
                    data: params,
                    type: options.type,
                    dataType: options.dataType
                };

                $.extend(ajaxSettings, options.ajaxSettings);

                that.currentRequest = $.ajax(ajaxSettings).done(function (data) {
                    var result;
                    that.currentRequest = null;
                    result = options.transformResult(data, q);
                    that.processResponse(result, q, cacheKey);
                    options.onSearchComplete.call(that.element, q, result.suggestions);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    options.onSearchError.call(that.element, q, jqXHR, textStatus, errorThrown);
                });
            } else {
                options.onSearchComplete.call(that.element, q, []);
            }
        },

        isBadQuery: function (q) {
            if (!this.options.preventBadQueries){
                return false;
            }

            var badQueries = this.badQueries,
                i = badQueries.length;

            while (i--) {
                if (q.indexOf(badQueries[i]) === 0) {
                    return true;
                }
            }

            return false;
        },

        hide: function () {
            var that = this,
                container = $(that.suggestionsContainer);

            if ($.isFunction(that.options.onHide) && that.visible) {
                that.options.onHide.call(that.element, container);
            }

            that.visible = false;
            that.selectedIndex = -1;
            clearTimeout(that.onChangeTimeout);
            $(that.suggestionsContainer).hide();
            that.signalHint(null);
        },

        suggest: function () {
            if (!this.suggestions.length) {
                if (this.options.showNoSuggestionNotice) {
                    this.noSuggestions();
                } else {
                    this.hide();
                }
                return;
            }

            var that = this,
                options = that.options,
                groupBy = options.groupBy,
                formatResult = options.formatResult,
                value = that.getQuery(that.currentValue),
                className = that.classes.suggestion,
                classSelected = that.classes.selected,
                container = $(that.suggestionsContainer),
                noSuggestionsContainer = $(that.noSuggestionsContainer),
                beforeRender = options.beforeRender,
                html = '',
                category,
                formatGroup = function (suggestion, index) {
                        var currentCategory = suggestion.data[groupBy];

                        if (category === currentCategory){
                            return '';
                        }

                        category = currentCategory;

                        return options.formatGroup(suggestion, category);
                    };

            if (options.triggerSelectOnValidInput && that.isExactMatch(value)) {
                that.select(0);
                return;
            }

            // Build suggestions inner HTML:
            $.each(that.suggestions, function (i, suggestion) {
                if (groupBy){
                    html += formatGroup(suggestion, value, i);
                }

                html += '<div class="' + className + '" data-index="' + i + '">' + formatResult(suggestion, value, i) + '</div>';
            });

            this.adjustContainerWidth();

            noSuggestionsContainer.detach();
            container.html(html);

            if ($.isFunction(beforeRender)) {
                beforeRender.call(that.element, container, that.suggestions);
            }

            that.fixPosition();
            container.show();

            // Select first value by default:
            if (options.autoSelectFirst) {
                that.selectedIndex = 0;
                container.scrollTop(0);
                container.children('.' + className).first().addClass(classSelected);
            }

            that.visible = true;
            that.findBestHint();
        },

        noSuggestions: function() {
             var that = this,
                 beforeRender = that.options.beforeRender,
                 container = $(that.suggestionsContainer),
                 noSuggestionsContainer = $(that.noSuggestionsContainer);

            this.adjustContainerWidth();

            // Some explicit steps. Be careful here as it easy to get
            // noSuggestionsContainer removed from DOM if not detached properly.
            noSuggestionsContainer.detach();

            // clean suggestions if any
            container.empty();
            container.append(noSuggestionsContainer);

            if ($.isFunction(beforeRender)) {
                beforeRender.call(that.element, container, that.suggestions);
            }

            that.fixPosition();

            container.show();
            that.visible = true;
        },

        adjustContainerWidth: function() {
            var that = this,
                options = that.options,
                width,
                container = $(that.suggestionsContainer);

            // If width is auto, adjust width before displaying suggestions,
            // because if instance was created before input had width, it will be zero.
            // Also it adjusts if input width has changed.
            if (options.width === 'auto') {
                width = that.el.outerWidth();
                container.css('width', width > 0 ? width : 300);
            } else if(options.width === 'flex') {
                // Trust the source! Unset the width property so it will be the max length
                // the containing elements.
                container.css('width', '');
            }
        },

        findBestHint: function () {
            var that = this,
                value = that.el.val().toLowerCase(),
                bestMatch = null;

            if (!value) {
                return;
            }

            $.each(that.suggestions, function (i, suggestion) {
                var foundMatch = suggestion.value.toLowerCase().indexOf(value) === 0;
                if (foundMatch) {
                    bestMatch = suggestion;
                }
                return !foundMatch;
            });

            that.signalHint(bestMatch);
        },

        signalHint: function (suggestion) {
            var hintValue = '',
                that = this;
            if (suggestion) {
                hintValue = that.currentValue + suggestion.value.substr(that.currentValue.length);
            }
            if (that.hintValue !== hintValue) {
                that.hintValue = hintValue;
                that.hint = suggestion;
                (this.options.onHint || $.noop)(hintValue);
            }
        },

        verifySuggestionsFormat: function (suggestions) {
            // If suggestions is string array, convert them to supported format:
            if (suggestions.length && typeof suggestions[0] === 'string') {
                return $.map(suggestions, function (value) {
                    return { value: value, data: null };
                });
            }

            return suggestions;
        },

        validateOrientation: function(orientation, fallback) {
            orientation = $.trim(orientation || '').toLowerCase();

            if($.inArray(orientation, ['auto', 'bottom', 'top']) === -1){
                orientation = fallback;
            }

            return orientation;
        },

        processResponse: function (result, originalQuery, cacheKey) {
            var that = this,
                options = that.options;

            result.suggestions = that.verifySuggestionsFormat(result.suggestions);

            // Cache results if cache is not disabled:
            if (!options.noCache) {
                that.cachedResponse[cacheKey] = result;
                if (options.preventBadQueries && !result.suggestions.length) {
                    that.badQueries.push(originalQuery);
                }
            }

            // Return if originalQuery is not matching current query:
            if (originalQuery !== that.getQuery(that.currentValue)) {
                return;
            }

            that.suggestions = result.suggestions;
            that.suggest();
        },

        activate: function (index) {
            var that = this,
                activeItem,
                selected = that.classes.selected,
                container = $(that.suggestionsContainer),
                children = container.find('.' + that.classes.suggestion);

            container.find('.' + selected).removeClass(selected);

            that.selectedIndex = index;

            if (that.selectedIndex !== -1 && children.length > that.selectedIndex) {
                activeItem = children.get(that.selectedIndex);
                $(activeItem).addClass(selected);
                return activeItem;
            }

            return null;
        },

        selectHint: function () {
            var that = this,
                i = $.inArray(that.hint, that.suggestions);

            that.select(i);
        },

        select: function (i) {
            var that = this;
            that.hide();
            that.onSelect(i);
        },

        moveUp: function () {
            var that = this;

            if (that.selectedIndex === -1) {
                return;
            }

            if (that.selectedIndex === 0) {
                $(that.suggestionsContainer).children('.' + that.classes.suggestion).first().removeClass(that.classes.selected);
                that.selectedIndex = -1;
                that.ignoreValueChange = false;
                that.el.val(that.currentValue);
                that.findBestHint();
                return;
            }

            that.adjustScroll(that.selectedIndex - 1);
        },

        moveDown: function () {
            var that = this;

            if (that.selectedIndex === (that.suggestions.length - 1)) {
                return;
            }

            that.adjustScroll(that.selectedIndex + 1);
        },

        adjustScroll: function (index) {
            var that = this,
                activeItem = that.activate(index);

            if (!activeItem) {
                return;
            }

            var offsetTop,
                upperBound,
                lowerBound,
                heightDelta = $(activeItem).outerHeight();

            offsetTop = activeItem.offsetTop;
            upperBound = $(that.suggestionsContainer).scrollTop();
            lowerBound = upperBound + that.options.maxHeight - heightDelta;

            if (offsetTop < upperBound) {
                $(that.suggestionsContainer).scrollTop(offsetTop);
            } else if (offsetTop > lowerBound) {
                $(that.suggestionsContainer).scrollTop(offsetTop - that.options.maxHeight + heightDelta);
            }

            if (!that.options.preserveInput) {
                // During onBlur event, browser will trigger "change" event,
                // because value has changed, to avoid side effect ignore,
                // that event, so that correct suggestion can be selected
                // when clicking on suggestion with a mouse
                that.ignoreValueChange = true;
                that.el.val(that.getValue(that.suggestions[index].value));
            }

            that.signalHint(null);
        },

        onSelect: function (index) {
            var that = this,
                onSelectCallback = that.options.onSelect,
                suggestion = that.suggestions[index];

            that.currentValue = that.getValue(suggestion.value);

            if (that.currentValue !== that.el.val() && !that.options.preserveInput) {
                that.el.val(that.currentValue);
            }

            that.signalHint(null);
            that.suggestions = [];
            that.selection = suggestion;

            if ($.isFunction(onSelectCallback)) {
                onSelectCallback.call(that.element, suggestion);
            }
        },

        getValue: function (value) {
            var that = this,
                delimiter = that.options.delimiter,
                currentValue,
                parts;

            if (!delimiter) {
                return value;
            }

            currentValue = that.currentValue;
            parts = currentValue.split(delimiter);

            if (parts.length === 1) {
                return value;
            }

            return currentValue.substr(0, currentValue.length - parts[parts.length - 1].length) + value;
        },

        dispose: function () {
            var that = this;
            that.el.off('.autocomplete').removeData('autocomplete');
            $(window).off('resize.autocomplete', that.fixPositionCapture);
            $(that.suggestionsContainer).remove();
        }
    };

    // Create chainable jQuery plugin:
    $.fn.devbridgeAutocomplete = function (options, args) {
        var dataKey = 'autocomplete';
        // If function invoked without argument return
        // instance of the first matched element:
        if (!arguments.length) {
            return this.first().data(dataKey);
        }

        return this.each(function () {
            var inputElement = $(this),
                instance = inputElement.data(dataKey);

            if (typeof options === 'string') {
                if (instance && typeof instance[options] === 'function') {
                    instance[options](args);
                }
            } else {
                // If instance already exists, destroy it:
                if (instance && instance.dispose) {
                    instance.dispose();
                }
                instance = new Autocomplete(this, options);
                inputElement.data(dataKey, instance);
            }
        });
    };

    // Don't overwrite if it already exists
    if (!$.fn.autocomplete) {
        $.fn.autocomplete = $.fn.devbridgeAutocomplete;
    }
}));


/***/ }),

/***/ "./node_modules/jquery-countto/jquery.countTo.js":
/*!*******************************************************!*\
  !*** ./node_modules/jquery-countto/jquery.countTo.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (factory) {
    if (true) {
        // AMD
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else {}
}(function ($) {
  var CountTo = function (element, options) {
    this.$element = $(element);
    this.options  = $.extend({}, CountTo.DEFAULTS, this.dataOptions(), options);
    this.init();
  };

  CountTo.DEFAULTS = {
    from: 0,               // the number the element should start at
    to: 0,                 // the number the element should end at
    speed: 1000,           // how long it should take to count between the target numbers
    refreshInterval: 100,  // how often the element should be updated
    decimals: 0,           // the number of decimal places to show
    formatter: formatter,  // handler for formatting the value before rendering
    onUpdate: null,        // callback method for every time the element is updated
    onComplete: null       // callback method for when the element finishes updating
  };

  CountTo.prototype.init = function () {
    this.value     = this.options.from;
    this.loops     = Math.ceil(this.options.speed / this.options.refreshInterval);
    this.loopCount = 0;
    this.increment = (this.options.to - this.options.from) / this.loops;
  };

  CountTo.prototype.dataOptions = function () {
    var options = {
      from:            this.$element.data('from'),
      to:              this.$element.data('to'),
      speed:           this.$element.data('speed'),
      refreshInterval: this.$element.data('refresh-interval'),
      decimals:        this.$element.data('decimals')
    };

    var keys = Object.keys(options);

    for (var i in keys) {
      var key = keys[i];

      if (typeof(options[key]) === 'undefined') {
        delete options[key];
      }
    }

    return options;
  };

  CountTo.prototype.update = function () {
    this.value += this.increment;
    this.loopCount++;

    this.render();

    if (typeof(this.options.onUpdate) == 'function') {
      this.options.onUpdate.call(this.$element, this.value);
    }

    if (this.loopCount >= this.loops) {
      clearInterval(this.interval);
      this.value = this.options.to;

      if (typeof(this.options.onComplete) == 'function') {
        this.options.onComplete.call(this.$element, this.value);
      }
    }
  };

  CountTo.prototype.render = function () {
    var formattedValue = this.options.formatter.call(this.$element, this.value, this.options);
    this.$element.text(formattedValue);
  };

  CountTo.prototype.restart = function () {
    this.stop();
    this.init();
    this.start();
  };

  CountTo.prototype.start = function () {
    this.stop();
    this.render();
    this.interval = setInterval(this.update.bind(this), this.options.refreshInterval);
  };

  CountTo.prototype.stop = function () {
    if (this.interval) {
      clearInterval(this.interval);
    }
  };

  CountTo.prototype.toggle = function () {
    if (this.interval) {
      this.stop();
    } else {
      this.start();
    }
  };

  function formatter(value, options) {
    return value.toFixed(options.decimals);
  }

  $.fn.countTo = function (option) {
    return this.each(function () {
      var $this   = $(this);
      var data    = $this.data('countTo');
      var init    = !data || typeof(option) === 'object';
      var options = typeof(option) === 'object' ? option : {};
      var method  = typeof(option) === 'string' ? option : 'start';

      if (init) {
        if (data) data.stop();
        $this.data('countTo', data = new CountTo(this, options));
      }

      data[method].call(data);
    });
  };
}));


/***/ }),

/***/ "./node_modules/jstree/dist/jstree.js":
/*!********************************************!*\
  !*** ./node_modules/jstree/dist/jstree.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*globals jQuery, define, module, exports, require, window, document, postMessage */
(function (factory) {
	"use strict";
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	}
	else {}
}(function ($, undefined) {
	"use strict";
/*!
 * jsTree 3.3.8
 * http://jstree.com/
 *
 * Copyright (c) 2014 Ivan Bozhanov (http://vakata.com)
 *
 * Licensed same as jquery - under the terms of the MIT License
 *   http://www.opensource.org/licenses/mit-license.php
 */
/*!
 * if using jslint please allow for the jQuery global and use following options:
 * jslint: loopfunc: true, browser: true, ass: true, bitwise: true, continue: true, nomen: true, plusplus: true, regexp: true, unparam: true, todo: true, white: true
 */
/*jshint -W083 */

	// prevent another load? maybe there is a better way?
	if($.jstree) {
		return;
	}

	/**
	 * ### jsTree core functionality
	 */

	// internal variables
	var instance_counter = 0,
		ccp_node = false,
		ccp_mode = false,
		ccp_inst = false,
		themes_loaded = [],
		src = $('script:last').attr('src'),
		document = window.document; // local variable is always faster to access then a global

	/**
	 * holds all jstree related functions and variables, including the actual class and methods to create, access and manipulate instances.
	 * @name $.jstree
	 */
	$.jstree = {
		/**
		 * specifies the jstree version in use
		 * @name $.jstree.version
		 */
		version : '3.3.8',
		/**
		 * holds all the default options used when creating new instances
		 * @name $.jstree.defaults
		 */
		defaults : {
			/**
			 * configure which plugins will be active on an instance. Should be an array of strings, where each element is a plugin name. The default is `[]`
			 * @name $.jstree.defaults.plugins
			 */
			plugins : []
		},
		/**
		 * stores all loaded jstree plugins (used internally)
		 * @name $.jstree.plugins
		 */
		plugins : {},
		path : src && src.indexOf('/') !== -1 ? src.replace(/\/[^\/]+$/,'') : '',
		idregex : /[\\:&!^|()\[\]<>@*'+~#";.,=\- \/${}%?`]/g,
		root : '#'
	};
	
	/**
	 * creates a jstree instance
	 * @name $.jstree.create(el [, options])
	 * @param {DOMElement|jQuery|String} el the element to create the instance on, can be jQuery extended or a selector
	 * @param {Object} options options for this instance (extends `$.jstree.defaults`)
	 * @return {jsTree} the new instance
	 */
	$.jstree.create = function (el, options) {
		var tmp = new $.jstree.core(++instance_counter),
			opt = options;
		options = $.extend(true, {}, $.jstree.defaults, options);
		if(opt && opt.plugins) {
			options.plugins = opt.plugins;
		}
		$.each(options.plugins, function (i, k) {
			if(i !== 'core') {
				tmp = tmp.plugin(k, options[k]);
			}
		});
		$(el).data('jstree', tmp);
		tmp.init(el, options);
		return tmp;
	};
	/**
	 * remove all traces of jstree from the DOM and destroy all instances
	 * @name $.jstree.destroy()
	 */
	$.jstree.destroy = function () {
		$('.jstree:jstree').jstree('destroy');
		$(document).off('.jstree');
	};
	/**
	 * the jstree class constructor, used only internally
	 * @private
	 * @name $.jstree.core(id)
	 * @param {Number} id this instance's index
	 */
	$.jstree.core = function (id) {
		this._id = id;
		this._cnt = 0;
		this._wrk = null;
		this._data = {
			core : {
				themes : {
					name : false,
					dots : false,
					icons : false,
					ellipsis : false
				},
				selected : [],
				last_error : {},
				working : false,
				worker_queue : [],
				focused : null
			}
		};
	};
	/**
	 * get a reference to an existing instance
	 *
	 * __Examples__
	 *
	 *	// provided a container with an ID of "tree", and a nested node with an ID of "branch"
	 *	// all of there will return the same instance
	 *	$.jstree.reference('tree');
	 *	$.jstree.reference('#tree');
	 *	$.jstree.reference($('#tree'));
	 *	$.jstree.reference(document.getElementByID('tree'));
	 *	$.jstree.reference('branch');
	 *	$.jstree.reference('#branch');
	 *	$.jstree.reference($('#branch'));
	 *	$.jstree.reference(document.getElementByID('branch'));
	 *
	 * @name $.jstree.reference(needle)
	 * @param {DOMElement|jQuery|String} needle
	 * @return {jsTree|null} the instance or `null` if not found
	 */
	$.jstree.reference = function (needle) {
		var tmp = null,
			obj = null;
		if(needle && needle.id && (!needle.tagName || !needle.nodeType)) { needle = needle.id; }

		if(!obj || !obj.length) {
			try { obj = $(needle); } catch (ignore) { }
		}
		if(!obj || !obj.length) {
			try { obj = $('#' + needle.replace($.jstree.idregex,'\\$&')); } catch (ignore) { }
		}
		if(obj && obj.length && (obj = obj.closest('.jstree')).length && (obj = obj.data('jstree'))) {
			tmp = obj;
		}
		else {
			$('.jstree').each(function () {
				var inst = $(this).data('jstree');
				if(inst && inst._model.data[needle]) {
					tmp = inst;
					return false;
				}
			});
		}
		return tmp;
	};
	/**
	 * Create an instance, get an instance or invoke a command on a instance.
	 *
	 * If there is no instance associated with the current node a new one is created and `arg` is used to extend `$.jstree.defaults` for this new instance. There would be no return value (chaining is not broken).
	 *
	 * If there is an existing instance and `arg` is a string the command specified by `arg` is executed on the instance, with any additional arguments passed to the function. If the function returns a value it will be returned (chaining could break depending on function).
	 *
	 * If there is an existing instance and `arg` is not a string the instance itself is returned (similar to `$.jstree.reference`).
	 *
	 * In any other case - nothing is returned and chaining is not broken.
	 *
	 * __Examples__
	 *
	 *	$('#tree1').jstree(); // creates an instance
	 *	$('#tree2').jstree({ plugins : [] }); // create an instance with some options
	 *	$('#tree1').jstree('open_node', '#branch_1'); // call a method on an existing instance, passing additional arguments
	 *	$('#tree2').jstree(); // get an existing instance (or create an instance)
	 *	$('#tree2').jstree(true); // get an existing instance (will not create new instance)
	 *	$('#branch_1').jstree().select_node('#branch_1'); // get an instance (using a nested element and call a method)
	 *
	 * @name $().jstree([arg])
	 * @param {String|Object} arg
	 * @return {Mixed}
	 */
	$.fn.jstree = function (arg) {
		// check for string argument
		var is_method	= (typeof arg === 'string'),
			args		= Array.prototype.slice.call(arguments, 1),
			result		= null;
		if(arg === true && !this.length) { return false; }
		this.each(function () {
			// get the instance (if there is one) and method (if it exists)
			var instance = $.jstree.reference(this),
				method = is_method && instance ? instance[arg] : null;
			// if calling a method, and method is available - execute on the instance
			result = is_method && method ?
				method.apply(instance, args) :
				null;
			// if there is no instance and no method is being called - create one
			if(!instance && !is_method && (arg === undefined || $.isPlainObject(arg))) {
				$.jstree.create(this, arg);
			}
			// if there is an instance and no method is called - return the instance
			if( (instance && !is_method) || arg === true ) {
				result = instance || false;
			}
			// if there was a method call which returned a result - break and return the value
			if(result !== null && result !== undefined) {
				return false;
			}
		});
		// if there was a method call with a valid return value - return that, otherwise continue the chain
		return result !== null && result !== undefined ?
			result : this;
	};
	/**
	 * used to find elements containing an instance
	 *
	 * __Examples__
	 *
	 *	$('div:jstree').each(function () {
	 *		$(this).jstree('destroy');
	 *	});
	 *
	 * @name $(':jstree')
	 * @return {jQuery}
	 */
	$.expr.pseudos.jstree = $.expr.createPseudo(function(search) {
		return function(a) {
			return $(a).hasClass('jstree') &&
				$(a).data('jstree') !== undefined;
		};
	});

	/**
	 * stores all defaults for the core
	 * @name $.jstree.defaults.core
	 */
	$.jstree.defaults.core = {
		/**
		 * data configuration
		 *
		 * If left as `false` the HTML inside the jstree container element is used to populate the tree (that should be an unordered list with list items).
		 *
		 * You can also pass in a HTML string or a JSON array here.
		 *
		 * It is possible to pass in a standard jQuery-like AJAX config and jstree will automatically determine if the response is JSON or HTML and use that to populate the tree.
		 * In addition to the standard jQuery ajax options here you can suppy functions for `data` and `url`, the functions will be run in the current instance's scope and a param will be passed indicating which node is being loaded, the return value of those functions will be used.
		 *
		 * The last option is to specify a function, that function will receive the node being loaded as argument and a second param which is a function which should be called with the result.
		 *
		 * __Examples__
		 *
		 *	// AJAX
		 *	$('#tree').jstree({
		 *		'core' : {
		 *			'data' : {
		 *				'url' : '/get/children/',
		 *				'data' : function (node) {
		 *					return { 'id' : node.id };
		 *				}
		 *			}
		 *		});
		 *
		 *	// direct data
		 *	$('#tree').jstree({
		 *		'core' : {
		 *			'data' : [
		 *				'Simple root node',
		 *				{
		 *					'id' : 'node_2',
		 *					'text' : 'Root node with options',
		 *					'state' : { 'opened' : true, 'selected' : true },
		 *					'children' : [ { 'text' : 'Child 1' }, 'Child 2']
		 *				}
		 *			]
		 *		}
		 *	});
		 *
		 *	// function
		 *	$('#tree').jstree({
		 *		'core' : {
		 *			'data' : function (obj, callback) {
		 *				callback.call(this, ['Root 1', 'Root 2']);
		 *			}
		 *		});
		 *
		 * @name $.jstree.defaults.core.data
		 */
		data			: false,
		/**
		 * configure the various strings used throughout the tree
		 *
		 * You can use an object where the key is the string you need to replace and the value is your replacement.
		 * Another option is to specify a function which will be called with an argument of the needed string and should return the replacement.
		 * If left as `false` no replacement is made.
		 *
		 * __Examples__
		 *
		 *	$('#tree').jstree({
		 *		'core' : {
		 *			'strings' : {
		 *				'Loading ...' : 'Please wait ...'
		 *			}
		 *		}
		 *	});
		 *
		 * @name $.jstree.defaults.core.strings
		 */
		strings			: false,
		/**
		 * determines what happens when a user tries to modify the structure of the tree
		 * If left as `false` all operations like create, rename, delete, move or copy are prevented.
		 * You can set this to `true` to allow all interactions or use a function to have better control.
		 *
		 * __Examples__
		 *
		 *	$('#tree').jstree({
		 *		'core' : {
		 *			'check_callback' : function (operation, node, node_parent, node_position, more) {
		 *				// operation can be 'create_node', 'rename_node', 'delete_node', 'move_node', 'copy_node' or 'edit'
		 *				// in case of 'rename_node' node_position is filled with the new node name
		 *				return operation === 'rename_node' ? true : false;
		 *			}
		 *		}
		 *	});
		 *
		 * @name $.jstree.defaults.core.check_callback
		 */
		check_callback	: false,
		/**
		 * a callback called with a single object parameter in the instance's scope when something goes wrong (operation prevented, ajax failed, etc)
		 * @name $.jstree.defaults.core.error
		 */
		error			: $.noop,
		/**
		 * the open / close animation duration in milliseconds - set this to `false` to disable the animation (default is `200`)
		 * @name $.jstree.defaults.core.animation
		 */
		animation		: 200,
		/**
		 * a boolean indicating if multiple nodes can be selected
		 * @name $.jstree.defaults.core.multiple
		 */
		multiple		: true,
		/**
		 * theme configuration object
		 * @name $.jstree.defaults.core.themes
		 */
		themes			: {
			/**
			 * the name of the theme to use (if left as `false` the default theme is used)
			 * @name $.jstree.defaults.core.themes.name
			 */
			name			: false,
			/**
			 * the URL of the theme's CSS file, leave this as `false` if you have manually included the theme CSS (recommended). You can set this to `true` too which will try to autoload the theme.
			 * @name $.jstree.defaults.core.themes.url
			 */
			url				: false,
			/**
			 * the location of all jstree themes - only used if `url` is set to `true`
			 * @name $.jstree.defaults.core.themes.dir
			 */
			dir				: false,
			/**
			 * a boolean indicating if connecting dots are shown
			 * @name $.jstree.defaults.core.themes.dots
			 */
			dots			: true,
			/**
			 * a boolean indicating if node icons are shown
			 * @name $.jstree.defaults.core.themes.icons
			 */
			icons			: true,
			/**
			 * a boolean indicating if node ellipsis should be shown - this only works with a fixed with on the container
			 * @name $.jstree.defaults.core.themes.ellipsis
			 */
			ellipsis		: false,
			/**
			 * a boolean indicating if the tree background is striped
			 * @name $.jstree.defaults.core.themes.stripes
			 */
			stripes			: false,
			/**
			 * a string (or boolean `false`) specifying the theme variant to use (if the theme supports variants)
			 * @name $.jstree.defaults.core.themes.variant
			 */
			variant			: false,
			/**
			 * a boolean specifying if a reponsive version of the theme should kick in on smaller screens (if the theme supports it). Defaults to `false`.
			 * @name $.jstree.defaults.core.themes.responsive
			 */
			responsive		: false
		},
		/**
		 * if left as `true` all parents of all selected nodes will be opened once the tree loads (so that all selected nodes are visible to the user)
		 * @name $.jstree.defaults.core.expand_selected_onload
		 */
		expand_selected_onload : true,
		/**
		 * if left as `true` web workers will be used to parse incoming JSON data where possible, so that the UI will not be blocked by large requests. Workers are however about 30% slower. Defaults to `true`
		 * @name $.jstree.defaults.core.worker
		 */
		worker : true,
		/**
		 * Force node text to plain text (and escape HTML). Defaults to `false`
		 * @name $.jstree.defaults.core.force_text
		 */
		force_text : false,
		/**
		 * Should the node be toggled if the text is double clicked. Defaults to `true`
		 * @name $.jstree.defaults.core.dblclick_toggle
		 */
		dblclick_toggle : true,
		/**
		 * Should the loaded nodes be part of the state. Defaults to `false`
		 * @name $.jstree.defaults.core.loaded_state
		 */
		loaded_state : false,
		/**
		 * Should the last active node be focused when the tree container is blurred and the focused again. This helps working with screen readers. Defaults to `true`
		 * @name $.jstree.defaults.core.restore_focus
		 */
		restore_focus : true,
		/**
		 * Default keyboard shortcuts (an object where each key is the button name or combo - like 'enter', 'ctrl-space', 'p', etc and the value is the function to execute in the instance's scope)
		 * @name $.jstree.defaults.core.keyboard
		 */
		keyboard : {
			'ctrl-space': function (e) {
				// aria defines space only with Ctrl
				e.type = "click";
				$(e.currentTarget).trigger(e);
			},
			'enter': function (e) {
				// enter
				e.type = "click";
				$(e.currentTarget).trigger(e);
			},
			'left': function (e) {
				// left
				e.preventDefault();
				if(this.is_open(e.currentTarget)) {
					this.close_node(e.currentTarget);
				}
				else {
					var o = this.get_parent(e.currentTarget);
					if(o && o.id !== $.jstree.root) { this.get_node(o, true).children('.jstree-anchor').focus(); }
				}
			},
			'up': function (e) {
				// up
				e.preventDefault();
				var o = this.get_prev_dom(e.currentTarget);
				if(o && o.length) { o.children('.jstree-anchor').focus(); }
			},
			'right': function (e) {
				// right
				e.preventDefault();
				if(this.is_closed(e.currentTarget)) {
					this.open_node(e.currentTarget, function (o) { this.get_node(o, true).children('.jstree-anchor').focus(); });
				}
				else if (this.is_open(e.currentTarget)) {
					var o = this.get_node(e.currentTarget, true).children('.jstree-children')[0];
					if(o) { $(this._firstChild(o)).children('.jstree-anchor').focus(); }
				}
			},
			'down': function (e) {
				// down
				e.preventDefault();
				var o = this.get_next_dom(e.currentTarget);
				if(o && o.length) { o.children('.jstree-anchor').focus(); }
			},
			'*': function (e) {
				// aria defines * on numpad as open_all - not very common
				this.open_all();
			},
			'home': function (e) {
				// home
				e.preventDefault();
				var o = this._firstChild(this.get_container_ul()[0]);
				if(o) { $(o).children('.jstree-anchor').filter(':visible').focus(); }
			},
			'end': function (e) {
				// end
				e.preventDefault();
				this.element.find('.jstree-anchor').filter(':visible').last().focus();
			},
			'f2': function (e) {
				// f2 - safe to include - if check_callback is false it will fail
				e.preventDefault();
				this.edit(e.currentTarget);
			}
		}
	};
	$.jstree.core.prototype = {
		/**
		 * used to decorate an instance with a plugin. Used internally.
		 * @private
		 * @name plugin(deco [, opts])
		 * @param  {String} deco the plugin to decorate with
		 * @param  {Object} opts options for the plugin
		 * @return {jsTree}
		 */
		plugin : function (deco, opts) {
			var Child = $.jstree.plugins[deco];
			if(Child) {
				this._data[deco] = {};
				Child.prototype = this;
				return new Child(opts, this);
			}
			return this;
		},
		/**
		 * initialize the instance. Used internally.
		 * @private
		 * @name init(el, optons)
		 * @param {DOMElement|jQuery|String} el the element we are transforming
		 * @param {Object} options options for this instance
		 * @trigger init.jstree, loading.jstree, loaded.jstree, ready.jstree, changed.jstree
		 */
		init : function (el, options) {
			this._model = {
				data : {},
				changed : [],
				force_full_redraw : false,
				redraw_timeout : false,
				default_state : {
					loaded : true,
					opened : false,
					selected : false,
					disabled : false
				}
			};
			this._model.data[$.jstree.root] = {
				id : $.jstree.root,
				parent : null,
				parents : [],
				children : [],
				children_d : [],
				state : { loaded : false }
			};

			this.element = $(el).addClass('jstree jstree-' + this._id);
			this.settings = options;

			this._data.core.ready = false;
			this._data.core.loaded = false;
			this._data.core.rtl = (this.element.css("direction") === "rtl");
			this.element[this._data.core.rtl ? 'addClass' : 'removeClass']("jstree-rtl");
			this.element.attr('role','tree');
			if(this.settings.core.multiple) {
				this.element.attr('aria-multiselectable', true);
			}
			if(!this.element.attr('tabindex')) {
				this.element.attr('tabindex','0');
			}

			this.bind();
			/**
			 * triggered after all events are bound
			 * @event
			 * @name init.jstree
			 */
			this.trigger("init");

			this._data.core.original_container_html = this.element.find(" > ul > li").clone(true);
			this._data.core.original_container_html
				.find("li").addBack()
				.contents().filter(function() {
					return this.nodeType === 3 && (!this.nodeValue || /^\s+$/.test(this.nodeValue));
				})
				.remove();
			this.element.html("<"+"ul class='jstree-container-ul jstree-children' role='group'><"+"li id='j"+this._id+"_loading' class='jstree-initial-node jstree-loading jstree-leaf jstree-last' role='tree-item'><i class='jstree-icon jstree-ocl'></i><"+"a class='jstree-anchor' href='#'><i class='jstree-icon jstree-themeicon-hidden'></i>" + this.get_string("Loading ...") + "</a></li></ul>");
			this.element.attr('aria-activedescendant','j' + this._id + '_loading');
			this._data.core.li_height = this.get_container_ul().children("li").first().outerHeight() || 24;
			this._data.core.node = this._create_prototype_node();
			/**
			 * triggered after the loading text is shown and before loading starts
			 * @event
			 * @name loading.jstree
			 */
			this.trigger("loading");
			this.load_node($.jstree.root);
		},
		/**
		 * destroy an instance
		 * @name destroy()
		 * @param  {Boolean} keep_html if not set to `true` the container will be emptied, otherwise the current DOM elements will be kept intact
		 */
		destroy : function (keep_html) {
			/**
			 * triggered before the tree is destroyed
			 * @event
			 * @name destroy.jstree
			 */
			this.trigger("destroy");
			if(this._wrk) {
				try {
					window.URL.revokeObjectURL(this._wrk);
					this._wrk = null;
				}
				catch (ignore) { }
			}
			if(!keep_html) { this.element.empty(); }
			this.teardown();
		},
		/**
		 * Create a prototype node
		 * @name _create_prototype_node()
		 * @return {DOMElement}
		 */
		_create_prototype_node : function () {
			var _node = document.createElement('LI'), _temp1, _temp2;
			_node.setAttribute('role', 'treeitem');
			_temp1 = document.createElement('I');
			_temp1.className = 'jstree-icon jstree-ocl';
			_temp1.setAttribute('role', 'presentation');
			_node.appendChild(_temp1);
			_temp1 = document.createElement('A');
			_temp1.className = 'jstree-anchor';
			_temp1.setAttribute('href','#');
			_temp1.setAttribute('tabindex','-1');
			_temp2 = document.createElement('I');
			_temp2.className = 'jstree-icon jstree-themeicon';
			_temp2.setAttribute('role', 'presentation');
			_temp1.appendChild(_temp2);
			_node.appendChild(_temp1);
			_temp1 = _temp2 = null;

			return _node;
		},
		_kbevent_to_func : function (e) {
			var keys = {
				8: "Backspace", 9: "Tab", 13: "Enter", 19: "Pause", 27: "Esc",
				32: "Space", 33: "PageUp", 34: "PageDown", 35: "End", 36: "Home",
				37: "Left", 38: "Up", 39: "Right", 40: "Down", 44: "Print", 45: "Insert",
				46: "Delete", 96: "Numpad0", 97: "Numpad1", 98: "Numpad2", 99 : "Numpad3",
				100: "Numpad4", 101: "Numpad5", 102: "Numpad6", 103: "Numpad7",
				104: "Numpad8", 105: "Numpad9", '-13': "NumpadEnter", 112: "F1",
				113: "F2", 114: "F3", 115: "F4", 116: "F5", 117: "F6", 118: "F7",
				119: "F8", 120: "F9", 121: "F10", 122: "F11", 123: "F12", 144: "Numlock",
				145: "Scrolllock", 16: 'Shift', 17: 'Ctrl', 18: 'Alt',
				48: '0',  49: '1',  50: '2',  51: '3',  52: '4', 53:  '5',
				54: '6',  55: '7',  56: '8',  57: '9',  59: ';',  61: '=', 65:  'a',
				66: 'b',  67: 'c',  68: 'd',  69: 'e',  70: 'f',  71: 'g', 72:  'h',
				73: 'i',  74: 'j',  75: 'k',  76: 'l',  77: 'm',  78: 'n', 79:  'o',
				80: 'p',  81: 'q',  82: 'r',  83: 's',  84: 't',  85: 'u', 86:  'v',
				87: 'w',  88: 'x',  89: 'y',  90: 'z', 107: '+', 109: '-', 110: '.',
				186: ';', 187: '=', 188: ',', 189: '-', 190: '.', 191: '/', 192: '`',
				219: '[', 220: '\\',221: ']', 222: "'", 111: '/', 106: '*', 173: '-'
			};
			var parts = [];
			if (e.ctrlKey) { parts.push('ctrl'); }
			if (e.altKey) { parts.push('alt'); }
			if (e.shiftKey) { parts.push('shift'); }
			parts.push(keys[e.which] || e.which);
			parts = parts.sort().join('-').toLowerCase();

			var kb = this.settings.core.keyboard, i, tmp;
			for (i in kb) {
				if (kb.hasOwnProperty(i)) {
					tmp = i;
					if (tmp !== '-' && tmp !== '+') {
						tmp = tmp.replace('--', '-MINUS').replace('+-', '-MINUS').replace('++', '-PLUS').replace('-+', '-PLUS');
						tmp = tmp.split(/-|\+/).sort().join('-').replace('MINUS', '-').replace('PLUS', '+').toLowerCase();
					}
					if (tmp === parts) {
						return kb[i];
					}
				}
			}
			return null;
		},
		/**
		 * part of the destroying of an instance. Used internally.
		 * @private
		 * @name teardown()
		 */
		teardown : function () {
			this.unbind();
			this.element
				.removeClass('jstree')
				.removeData('jstree')
				.find("[class^='jstree']")
					.addBack()
					.attr("class", function () { return this.className.replace(/jstree[^ ]*|$/ig,''); });
			this.element = null;
		},
		/**
		 * bind all events. Used internally.
		 * @private
		 * @name bind()
		 */
		bind : function () {
			var word = '',
				tout = null,
				was_click = 0;
			this.element
				.on("dblclick.jstree", function (e) {
						if(e.target.tagName && e.target.tagName.toLowerCase() === "input") { return true; }
						if(document.selection && document.selection.empty) {
							document.selection.empty();
						}
						else {
							if(window.getSelection) {
								var sel = window.getSelection();
								try {
									sel.removeAllRanges();
									sel.collapse();
								} catch (ignore) { }
							}
						}
					})
				.on("mousedown.jstree", $.proxy(function (e) {
						if(e.target === this.element[0]) {
							e.preventDefault(); // prevent losing focus when clicking scroll arrows (FF, Chrome)
							was_click = +(new Date()); // ie does not allow to prevent losing focus
						}
					}, this))
				.on("mousedown.jstree", ".jstree-ocl", function (e) {
						e.preventDefault(); // prevent any node inside from losing focus when clicking the open/close icon
					})
				.on("click.jstree", ".jstree-ocl", $.proxy(function (e) {
						this.toggle_node(e.target);
					}, this))
				.on("dblclick.jstree", ".jstree-anchor", $.proxy(function (e) {
						if(e.target.tagName && e.target.tagName.toLowerCase() === "input") { return true; }
						if(this.settings.core.dblclick_toggle) {
							this.toggle_node(e.target);
						}
					}, this))
				.on("click.jstree", ".jstree-anchor", $.proxy(function (e) {
						e.preventDefault();
						if(e.currentTarget !== document.activeElement) { $(e.currentTarget).focus(); }
						this.activate_node(e.currentTarget, e);
					}, this))
				.on('keydown.jstree', '.jstree-anchor', $.proxy(function (e) {
						if(e.target.tagName && e.target.tagName.toLowerCase() === "input") { return true; }
						if(this._data.core.rtl) {
							if(e.which === 37) { e.which = 39; }
							else if(e.which === 39) { e.which = 37; }
						}
						var f = this._kbevent_to_func(e);
						if (f) {
							var r = f.call(this, e);
							if (r === false || r === true) {
								return r;
							}
						}
					}, this))
				.on("load_node.jstree", $.proxy(function (e, data) {
						if(data.status) {
							if(data.node.id === $.jstree.root && !this._data.core.loaded) {
								this._data.core.loaded = true;
								if(this._firstChild(this.get_container_ul()[0])) {
									this.element.attr('aria-activedescendant',this._firstChild(this.get_container_ul()[0]).id);
								}
								/**
								 * triggered after the root node is loaded for the first time
								 * @event
								 * @name loaded.jstree
								 */
								this.trigger("loaded");
							}
							if(!this._data.core.ready) {
								setTimeout($.proxy(function() {
									if(this.element && !this.get_container_ul().find('.jstree-loading').length) {
										this._data.core.ready = true;
										if(this._data.core.selected.length) {
											if(this.settings.core.expand_selected_onload) {
												var tmp = [], i, j;
												for(i = 0, j = this._data.core.selected.length; i < j; i++) {
													tmp = tmp.concat(this._model.data[this._data.core.selected[i]].parents);
												}
												tmp = $.vakata.array_unique(tmp);
												for(i = 0, j = tmp.length; i < j; i++) {
													this.open_node(tmp[i], false, 0);
												}
											}
											this.trigger('changed', { 'action' : 'ready', 'selected' : this._data.core.selected });
										}
										/**
										 * triggered after all nodes are finished loading
										 * @event
										 * @name ready.jstree
										 */
										this.trigger("ready");
									}
								}, this), 0);
							}
						}
					}, this))
				// quick searching when the tree is focused
				.on('keypress.jstree', $.proxy(function (e) {
						if(e.target.tagName && e.target.tagName.toLowerCase() === "input") { return true; }
						if(tout) { clearTimeout(tout); }
						tout = setTimeout(function () {
							word = '';
						}, 500);

						var chr = String.fromCharCode(e.which).toLowerCase(),
							col = this.element.find('.jstree-anchor').filter(':visible'),
							ind = col.index(document.activeElement) || 0,
							end = false;
						word += chr;

						// match for whole word from current node down (including the current node)
						if(word.length > 1) {
							col.slice(ind).each($.proxy(function (i, v) {
								if($(v).text().toLowerCase().indexOf(word) === 0) {
									$(v).focus();
									end = true;
									return false;
								}
							}, this));
							if(end) { return; }

							// match for whole word from the beginning of the tree
							col.slice(0, ind).each($.proxy(function (i, v) {
								if($(v).text().toLowerCase().indexOf(word) === 0) {
									$(v).focus();
									end = true;
									return false;
								}
							}, this));
							if(end) { return; }
						}
						// list nodes that start with that letter (only if word consists of a single char)
						if(new RegExp('^' + chr.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&') + '+$').test(word)) {
							// search for the next node starting with that letter
							col.slice(ind + 1).each($.proxy(function (i, v) {
								if($(v).text().toLowerCase().charAt(0) === chr) {
									$(v).focus();
									end = true;
									return false;
								}
							}, this));
							if(end) { return; }

							// search from the beginning
							col.slice(0, ind + 1).each($.proxy(function (i, v) {
								if($(v).text().toLowerCase().charAt(0) === chr) {
									$(v).focus();
									end = true;
									return false;
								}
							}, this));
							if(end) { return; }
						}
					}, this))
				// THEME RELATED
				.on("init.jstree", $.proxy(function () {
						var s = this.settings.core.themes;
						this._data.core.themes.dots			= s.dots;
						this._data.core.themes.stripes		= s.stripes;
						this._data.core.themes.icons		= s.icons;
						this._data.core.themes.ellipsis		= s.ellipsis;
						this.set_theme(s.name || "default", s.url);
						this.set_theme_variant(s.variant);
					}, this))
				.on("loading.jstree", $.proxy(function () {
						this[ this._data.core.themes.dots ? "show_dots" : "hide_dots" ]();
						this[ this._data.core.themes.icons ? "show_icons" : "hide_icons" ]();
						this[ this._data.core.themes.stripes ? "show_stripes" : "hide_stripes" ]();
						this[ this._data.core.themes.ellipsis ? "show_ellipsis" : "hide_ellipsis" ]();
					}, this))
				.on('blur.jstree', '.jstree-anchor', $.proxy(function (e) {
						this._data.core.focused = null;
						$(e.currentTarget).filter('.jstree-hovered').trigger('mouseleave');
						this.element.attr('tabindex', '0');
					}, this))
				.on('focus.jstree', '.jstree-anchor', $.proxy(function (e) {
						var tmp = this.get_node(e.currentTarget);
						if(tmp && tmp.id) {
							this._data.core.focused = tmp.id;
						}
						this.element.find('.jstree-hovered').not(e.currentTarget).trigger('mouseleave');
						$(e.currentTarget).trigger('mouseenter');
						this.element.attr('tabindex', '-1');
					}, this))
				.on('focus.jstree', $.proxy(function () {
						if(+(new Date()) - was_click > 500 && !this._data.core.focused && this.settings.core.restore_focus) {
							was_click = 0;
							var act = this.get_node(this.element.attr('aria-activedescendant'), true);
							if(act) {
								act.find('> .jstree-anchor').focus();
							}
						}
					}, this))
				.on('mouseenter.jstree', '.jstree-anchor', $.proxy(function (e) {
						this.hover_node(e.currentTarget);
					}, this))
				.on('mouseleave.jstree', '.jstree-anchor', $.proxy(function (e) {
						this.dehover_node(e.currentTarget);
					}, this));
		},
		/**
		 * part of the destroying of an instance. Used internally.
		 * @private
		 * @name unbind()
		 */
		unbind : function () {
			this.element.off('.jstree');
			$(document).off('.jstree-' + this._id);
		},
		/**
		 * trigger an event. Used internally.
		 * @private
		 * @name trigger(ev [, data])
		 * @param  {String} ev the name of the event to trigger
		 * @param  {Object} data additional data to pass with the event
		 */
		trigger : function (ev, data) {
			if(!data) {
				data = {};
			}
			data.instance = this;
			this.element.triggerHandler(ev.replace('.jstree','') + '.jstree', data);
		},
		/**
		 * returns the jQuery extended instance container
		 * @name get_container()
		 * @return {jQuery}
		 */
		get_container : function () {
			return this.element;
		},
		/**
		 * returns the jQuery extended main UL node inside the instance container. Used internally.
		 * @private
		 * @name get_container_ul()
		 * @return {jQuery}
		 */
		get_container_ul : function () {
			return this.element.children(".jstree-children").first();
		},
		/**
		 * gets string replacements (localization). Used internally.
		 * @private
		 * @name get_string(key)
		 * @param  {String} key
		 * @return {String}
		 */
		get_string : function (key) {
			var a = this.settings.core.strings;
			if($.isFunction(a)) { return a.call(this, key); }
			if(a && a[key]) { return a[key]; }
			return key;
		},
		/**
		 * gets the first child of a DOM node. Used internally.
		 * @private
		 * @name _firstChild(dom)
		 * @param  {DOMElement} dom
		 * @return {DOMElement}
		 */
		_firstChild : function (dom) {
			dom = dom ? dom.firstChild : null;
			while(dom !== null && dom.nodeType !== 1) {
				dom = dom.nextSibling;
			}
			return dom;
		},
		/**
		 * gets the next sibling of a DOM node. Used internally.
		 * @private
		 * @name _nextSibling(dom)
		 * @param  {DOMElement} dom
		 * @return {DOMElement}
		 */
		_nextSibling : function (dom) {
			dom = dom ? dom.nextSibling : null;
			while(dom !== null && dom.nodeType !== 1) {
				dom = dom.nextSibling;
			}
			return dom;
		},
		/**
		 * gets the previous sibling of a DOM node. Used internally.
		 * @private
		 * @name _previousSibling(dom)
		 * @param  {DOMElement} dom
		 * @return {DOMElement}
		 */
		_previousSibling : function (dom) {
			dom = dom ? dom.previousSibling : null;
			while(dom !== null && dom.nodeType !== 1) {
				dom = dom.previousSibling;
			}
			return dom;
		},
		/**
		 * get the JSON representation of a node (or the actual jQuery extended DOM node) by using any input (child DOM element, ID string, selector, etc)
		 * @name get_node(obj [, as_dom])
		 * @param  {mixed} obj
		 * @param  {Boolean} as_dom
		 * @return {Object|jQuery}
		 */
		get_node : function (obj, as_dom) {
			if(obj && obj.id) {
				obj = obj.id;
			}
			if (obj instanceof $ && obj.length && obj[0].id) {
				obj = obj[0].id;
			}
			var dom;
			try {
				if(this._model.data[obj]) {
					obj = this._model.data[obj];
				}
				else if(typeof obj === "string" && this._model.data[obj.replace(/^#/, '')]) {
					obj = this._model.data[obj.replace(/^#/, '')];
				}
				else if(typeof obj === "string" && (dom = $('#' + obj.replace($.jstree.idregex,'\\$&'), this.element)).length && this._model.data[dom.closest('.jstree-node').attr('id')]) {
					obj = this._model.data[dom.closest('.jstree-node').attr('id')];
				}
				else if((dom = this.element.find(obj)).length && this._model.data[dom.closest('.jstree-node').attr('id')]) {
					obj = this._model.data[dom.closest('.jstree-node').attr('id')];
				}
				else if((dom = this.element.find(obj)).length && dom.hasClass('jstree')) {
					obj = this._model.data[$.jstree.root];
				}
				else {
					return false;
				}

				if(as_dom) {
					obj = obj.id === $.jstree.root ? this.element : $('#' + obj.id.replace($.jstree.idregex,'\\$&'), this.element);
				}
				return obj;
			} catch (ex) { return false; }
		},
		/**
		 * get the path to a node, either consisting of node texts, or of node IDs, optionally glued together (otherwise an array)
		 * @name get_path(obj [, glue, ids])
		 * @param  {mixed} obj the node
		 * @param  {String} glue if you want the path as a string - pass the glue here (for example '/'), if a falsy value is supplied here, an array is returned
		 * @param  {Boolean} ids if set to true build the path using ID, otherwise node text is used
		 * @return {mixed}
		 */
		get_path : function (obj, glue, ids) {
			obj = obj.parents ? obj : this.get_node(obj);
			if(!obj || obj.id === $.jstree.root || !obj.parents) {
				return false;
			}
			var i, j, p = [];
			p.push(ids ? obj.id : obj.text);
			for(i = 0, j = obj.parents.length; i < j; i++) {
				p.push(ids ? obj.parents[i] : this.get_text(obj.parents[i]));
			}
			p = p.reverse().slice(1);
			return glue ? p.join(glue) : p;
		},
		/**
		 * get the next visible node that is below the `obj` node. If `strict` is set to `true` only sibling nodes are returned.
		 * @name get_next_dom(obj [, strict])
		 * @param  {mixed} obj
		 * @param  {Boolean} strict
		 * @return {jQuery}
		 */
		get_next_dom : function (obj, strict) {
			var tmp;
			obj = this.get_node(obj, true);
			if(obj[0] === this.element[0]) {
				tmp = this._firstChild(this.get_container_ul()[0]);
				while (tmp && tmp.offsetHeight === 0) {
					tmp = this._nextSibling(tmp);
				}
				return tmp ? $(tmp) : false;
			}
			if(!obj || !obj.length) {
				return false;
			}
			if(strict) {
				tmp = obj[0];
				do {
					tmp = this._nextSibling(tmp);
				} while (tmp && tmp.offsetHeight === 0);
				return tmp ? $(tmp) : false;
			}
			if(obj.hasClass("jstree-open")) {
				tmp = this._firstChild(obj.children('.jstree-children')[0]);
				while (tmp && tmp.offsetHeight === 0) {
					tmp = this._nextSibling(tmp);
				}
				if(tmp !== null) {
					return $(tmp);
				}
			}
			tmp = obj[0];
			do {
				tmp = this._nextSibling(tmp);
			} while (tmp && tmp.offsetHeight === 0);
			if(tmp !== null) {
				return $(tmp);
			}
			return obj.parentsUntil(".jstree",".jstree-node").nextAll(".jstree-node:visible").first();
		},
		/**
		 * get the previous visible node that is above the `obj` node. If `strict` is set to `true` only sibling nodes are returned.
		 * @name get_prev_dom(obj [, strict])
		 * @param  {mixed} obj
		 * @param  {Boolean} strict
		 * @return {jQuery}
		 */
		get_prev_dom : function (obj, strict) {
			var tmp;
			obj = this.get_node(obj, true);
			if(obj[0] === this.element[0]) {
				tmp = this.get_container_ul()[0].lastChild;
				while (tmp && tmp.offsetHeight === 0) {
					tmp = this._previousSibling(tmp);
				}
				return tmp ? $(tmp) : false;
			}
			if(!obj || !obj.length) {
				return false;
			}
			if(strict) {
				tmp = obj[0];
				do {
					tmp = this._previousSibling(tmp);
				} while (tmp && tmp.offsetHeight === 0);
				return tmp ? $(tmp) : false;
			}
			tmp = obj[0];
			do {
				tmp = this._previousSibling(tmp);
			} while (tmp && tmp.offsetHeight === 0);
			if(tmp !== null) {
				obj = $(tmp);
				while(obj.hasClass("jstree-open")) {
					obj = obj.children(".jstree-children").first().children(".jstree-node:visible:last");
				}
				return obj;
			}
			tmp = obj[0].parentNode.parentNode;
			return tmp && tmp.className && tmp.className.indexOf('jstree-node') !== -1 ? $(tmp) : false;
		},
		/**
		 * get the parent ID of a node
		 * @name get_parent(obj)
		 * @param  {mixed} obj
		 * @return {String}
		 */
		get_parent : function (obj) {
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			return obj.parent;
		},
		/**
		 * get a jQuery collection of all the children of a node (node must be rendered), returns false on error
		 * @name get_children_dom(obj)
		 * @param  {mixed} obj
		 * @return {jQuery}
		 */
		get_children_dom : function (obj) {
			obj = this.get_node(obj, true);
			if(obj[0] === this.element[0]) {
				return this.get_container_ul().children(".jstree-node");
			}
			if(!obj || !obj.length) {
				return false;
			}
			return obj.children(".jstree-children").children(".jstree-node");
		},
		/**
		 * checks if a node has children
		 * @name is_parent(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_parent : function (obj) {
			obj = this.get_node(obj);
			return obj && (obj.state.loaded === false || obj.children.length > 0);
		},
		/**
		 * checks if a node is loaded (its children are available)
		 * @name is_loaded(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_loaded : function (obj) {
			obj = this.get_node(obj);
			return obj && obj.state.loaded;
		},
		/**
		 * check if a node is currently loading (fetching children)
		 * @name is_loading(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_loading : function (obj) {
			obj = this.get_node(obj);
			return obj && obj.state && obj.state.loading;
		},
		/**
		 * check if a node is opened
		 * @name is_open(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_open : function (obj) {
			obj = this.get_node(obj);
			return obj && obj.state.opened;
		},
		/**
		 * check if a node is in a closed state
		 * @name is_closed(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_closed : function (obj) {
			obj = this.get_node(obj);
			return obj && this.is_parent(obj) && !obj.state.opened;
		},
		/**
		 * check if a node has no children
		 * @name is_leaf(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_leaf : function (obj) {
			return !this.is_parent(obj);
		},
		/**
		 * loads a node (fetches its children using the `core.data` setting). Multiple nodes can be passed to by using an array.
		 * @name load_node(obj [, callback])
		 * @param  {mixed} obj
		 * @param  {function} callback a function to be executed once loading is complete, the function is executed in the instance's scope and receives two arguments - the node and a boolean status
		 * @return {Boolean}
		 * @trigger load_node.jstree
		 */
		load_node : function (obj, callback) {
			var k, l, i, j, c;
			if($.isArray(obj)) {
				this._load_nodes(obj.slice(), callback);
				return true;
			}
			obj = this.get_node(obj);
			if(!obj) {
				if(callback) { callback.call(this, obj, false); }
				return false;
			}
			// if(obj.state.loading) { } // the node is already loading - just wait for it to load and invoke callback? but if called implicitly it should be loaded again?
			if(obj.state.loaded) {
				obj.state.loaded = false;
				for(i = 0, j = obj.parents.length; i < j; i++) {
					this._model.data[obj.parents[i]].children_d = $.vakata.array_filter(this._model.data[obj.parents[i]].children_d, function (v) {
						return $.inArray(v, obj.children_d) === -1;
					});
				}
				for(k = 0, l = obj.children_d.length; k < l; k++) {
					if(this._model.data[obj.children_d[k]].state.selected) {
						c = true;
					}
					delete this._model.data[obj.children_d[k]];
				}
				if (c) {
					this._data.core.selected = $.vakata.array_filter(this._data.core.selected, function (v) {
						return $.inArray(v, obj.children_d) === -1;
					});
				}
				obj.children = [];
				obj.children_d = [];
				if(c) {
					this.trigger('changed', { 'action' : 'load_node', 'node' : obj, 'selected' : this._data.core.selected });
				}
			}
			obj.state.failed = false;
			obj.state.loading = true;
			this.get_node(obj, true).addClass("jstree-loading").attr('aria-busy',true);
			this._load_node(obj, $.proxy(function (status) {
				obj = this._model.data[obj.id];
				obj.state.loading = false;
				obj.state.loaded = status;
				obj.state.failed = !obj.state.loaded;
				var dom = this.get_node(obj, true), i = 0, j = 0, m = this._model.data, has_children = false;
				for(i = 0, j = obj.children.length; i < j; i++) {
					if(m[obj.children[i]] && !m[obj.children[i]].state.hidden) {
						has_children = true;
						break;
					}
				}
				if(obj.state.loaded && dom && dom.length) {
					dom.removeClass('jstree-closed jstree-open jstree-leaf');
					if (!has_children) {
						dom.addClass('jstree-leaf');
					}
					else {
						if (obj.id !== '#') {
							dom.addClass(obj.state.opened ? 'jstree-open' : 'jstree-closed');
						}
					}
				}
				dom.removeClass("jstree-loading").attr('aria-busy',false);
				/**
				 * triggered after a node is loaded
				 * @event
				 * @name load_node.jstree
				 * @param {Object} node the node that was loading
				 * @param {Boolean} status was the node loaded successfully
				 */
				this.trigger('load_node', { "node" : obj, "status" : status });
				if(callback) {
					callback.call(this, obj, status);
				}
			}, this));
			return true;
		},
		/**
		 * load an array of nodes (will also load unavailable nodes as soon as they appear in the structure). Used internally.
		 * @private
		 * @name _load_nodes(nodes [, callback])
		 * @param  {array} nodes
		 * @param  {function} callback a function to be executed once loading is complete, the function is executed in the instance's scope and receives one argument - the array passed to _load_nodes
		 */
		_load_nodes : function (nodes, callback, is_callback, force_reload) {
			var r = true,
				c = function () { this._load_nodes(nodes, callback, true); },
				m = this._model.data, i, j, tmp = [];
			for(i = 0, j = nodes.length; i < j; i++) {
				if(m[nodes[i]] && ( (!m[nodes[i]].state.loaded && !m[nodes[i]].state.failed) || (!is_callback && force_reload) )) {
					if(!this.is_loading(nodes[i])) {
						this.load_node(nodes[i], c);
					}
					r = false;
				}
			}
			if(r) {
				for(i = 0, j = nodes.length; i < j; i++) {
					if(m[nodes[i]] && m[nodes[i]].state.loaded) {
						tmp.push(nodes[i]);
					}
				}
				if(callback && !callback.done) {
					callback.call(this, tmp);
					callback.done = true;
				}
			}
		},
		/**
		 * loads all unloaded nodes
		 * @name load_all([obj, callback])
		 * @param {mixed} obj the node to load recursively, omit to load all nodes in the tree
		 * @param {function} callback a function to be executed once loading all the nodes is complete,
		 * @trigger load_all.jstree
		 */
		load_all : function (obj, callback) {
			if(!obj) { obj = $.jstree.root; }
			obj = this.get_node(obj);
			if(!obj) { return false; }
			var to_load = [],
				m = this._model.data,
				c = m[obj.id].children_d,
				i, j;
			if(obj.state && !obj.state.loaded) {
				to_load.push(obj.id);
			}
			for(i = 0, j = c.length; i < j; i++) {
				if(m[c[i]] && m[c[i]].state && !m[c[i]].state.loaded) {
					to_load.push(c[i]);
				}
			}
			if(to_load.length) {
				this._load_nodes(to_load, function () {
					this.load_all(obj, callback);
				});
			}
			else {
				/**
				 * triggered after a load_all call completes
				 * @event
				 * @name load_all.jstree
				 * @param {Object} node the recursively loaded node
				 */
				if(callback) { callback.call(this, obj); }
				this.trigger('load_all', { "node" : obj });
			}
		},
		/**
		 * handles the actual loading of a node. Used only internally.
		 * @private
		 * @name _load_node(obj [, callback])
		 * @param  {mixed} obj
		 * @param  {function} callback a function to be executed once loading is complete, the function is executed in the instance's scope and receives one argument - a boolean status
		 * @return {Boolean}
		 */
		_load_node : function (obj, callback) {
			var s = this.settings.core.data, t;
			var notTextOrCommentNode = function notTextOrCommentNode () {
				return this.nodeType !== 3 && this.nodeType !== 8;
			};
			// use original HTML
			if(!s) {
				if(obj.id === $.jstree.root) {
					return this._append_html_data(obj, this._data.core.original_container_html.clone(true), function (status) {
						callback.call(this, status);
					});
				}
				else {
					return callback.call(this, false);
				}
				// return callback.call(this, obj.id === $.jstree.root ? this._append_html_data(obj, this._data.core.original_container_html.clone(true)) : false);
			}
			if($.isFunction(s)) {
				return s.call(this, obj, $.proxy(function (d) {
					if(d === false) {
						callback.call(this, false);
					}
					else {
						this[typeof d === 'string' ? '_append_html_data' : '_append_json_data'](obj, typeof d === 'string' ? $($.parseHTML(d)).filter(notTextOrCommentNode) : d, function (status) {
							callback.call(this, status);
						});
					}
					// return d === false ? callback.call(this, false) : callback.call(this, this[typeof d === 'string' ? '_append_html_data' : '_append_json_data'](obj, typeof d === 'string' ? $(d) : d));
				}, this));
			}
			if(typeof s === 'object') {
				if(s.url) {
					s = $.extend(true, {}, s);
					if($.isFunction(s.url)) {
						s.url = s.url.call(this, obj);
					}
					if($.isFunction(s.data)) {
						s.data = s.data.call(this, obj);
					}
					return $.ajax(s)
						.done($.proxy(function (d,t,x) {
								var type = x.getResponseHeader('Content-Type');
								if((type && type.indexOf('json') !== -1) || typeof d === "object") {
									return this._append_json_data(obj, d, function (status) { callback.call(this, status); });
									//return callback.call(this, this._append_json_data(obj, d));
								}
								if((type && type.indexOf('html') !== -1) || typeof d === "string") {
									return this._append_html_data(obj, $($.parseHTML(d)).filter(notTextOrCommentNode), function (status) { callback.call(this, status); });
									// return callback.call(this, this._append_html_data(obj, $(d)));
								}
								this._data.core.last_error = { 'error' : 'ajax', 'plugin' : 'core', 'id' : 'core_04', 'reason' : 'Could not load node', 'data' : JSON.stringify({ 'id' : obj.id, 'xhr' : x }) };
								this.settings.core.error.call(this, this._data.core.last_error);
								return callback.call(this, false);
							}, this))
						.fail($.proxy(function (f) {
								this._data.core.last_error = { 'error' : 'ajax', 'plugin' : 'core', 'id' : 'core_04', 'reason' : 'Could not load node', 'data' : JSON.stringify({ 'id' : obj.id, 'xhr' : f }) };
								callback.call(this, false);
								this.settings.core.error.call(this, this._data.core.last_error);
							}, this));
				}
				if ($.isArray(s)) {
					t = $.extend(true, [], s);
				} else if ($.isPlainObject(s)) {
					t = $.extend(true, {}, s);
				} else {
					t = s;
				}
				if(obj.id === $.jstree.root) {
					return this._append_json_data(obj, t, function (status) {
						callback.call(this, status);
					});
				}
				else {
					this._data.core.last_error = { 'error' : 'nodata', 'plugin' : 'core', 'id' : 'core_05', 'reason' : 'Could not load node', 'data' : JSON.stringify({ 'id' : obj.id }) };
					this.settings.core.error.call(this, this._data.core.last_error);
					return callback.call(this, false);
				}
				//return callback.call(this, (obj.id === $.jstree.root ? this._append_json_data(obj, t) : false) );
			}
			if(typeof s === 'string') {
				if(obj.id === $.jstree.root) {
					return this._append_html_data(obj, $($.parseHTML(s)).filter(notTextOrCommentNode), function (status) {
						callback.call(this, status);
					});
				}
				else {
					this._data.core.last_error = { 'error' : 'nodata', 'plugin' : 'core', 'id' : 'core_06', 'reason' : 'Could not load node', 'data' : JSON.stringify({ 'id' : obj.id }) };
					this.settings.core.error.call(this, this._data.core.last_error);
					return callback.call(this, false);
				}
				//return callback.call(this, (obj.id === $.jstree.root ? this._append_html_data(obj, $(s)) : false) );
			}
			return callback.call(this, false);
		},
		/**
		 * adds a node to the list of nodes to redraw. Used only internally.
		 * @private
		 * @name _node_changed(obj [, callback])
		 * @param  {mixed} obj
		 */
		_node_changed : function (obj) {
			obj = this.get_node(obj);
      if (obj && $.inArray(obj.id, this._model.changed) === -1) {
				this._model.changed.push(obj.id);
			}
		},
		/**
		 * appends HTML content to the tree. Used internally.
		 * @private
		 * @name _append_html_data(obj, data)
		 * @param  {mixed} obj the node to append to
		 * @param  {String} data the HTML string to parse and append
		 * @trigger model.jstree, changed.jstree
		 */
		_append_html_data : function (dom, data, cb) {
			dom = this.get_node(dom);
			dom.children = [];
			dom.children_d = [];
			var dat = data.is('ul') ? data.children() : data,
				par = dom.id,
				chd = [],
				dpc = [],
				m = this._model.data,
				p = m[par],
				s = this._data.core.selected.length,
				tmp, i, j;
			dat.each($.proxy(function (i, v) {
				tmp = this._parse_model_from_html($(v), par, p.parents.concat());
				if(tmp) {
					chd.push(tmp);
					dpc.push(tmp);
					if(m[tmp].children_d.length) {
						dpc = dpc.concat(m[tmp].children_d);
					}
				}
			}, this));
			p.children = chd;
			p.children_d = dpc;
			for(i = 0, j = p.parents.length; i < j; i++) {
				m[p.parents[i]].children_d = m[p.parents[i]].children_d.concat(dpc);
			}
			/**
			 * triggered when new data is inserted to the tree model
			 * @event
			 * @name model.jstree
			 * @param {Array} nodes an array of node IDs
			 * @param {String} parent the parent ID of the nodes
			 */
			this.trigger('model', { "nodes" : dpc, 'parent' : par });
			if(par !== $.jstree.root) {
				this._node_changed(par);
				this.redraw();
			}
			else {
				this.get_container_ul().children('.jstree-initial-node').remove();
				this.redraw(true);
			}
			if(this._data.core.selected.length !== s) {
				this.trigger('changed', { 'action' : 'model', 'selected' : this._data.core.selected });
			}
			cb.call(this, true);
		},
		/**
		 * appends JSON content to the tree. Used internally.
		 * @private
		 * @name _append_json_data(obj, data)
		 * @param  {mixed} obj the node to append to
		 * @param  {String} data the JSON object to parse and append
		 * @param  {Boolean} force_processing internal param - do not set
		 * @trigger model.jstree, changed.jstree
		 */
		_append_json_data : function (dom, data, cb, force_processing) {
			if(this.element === null) { return; }
			dom = this.get_node(dom);
			dom.children = [];
			dom.children_d = [];
			// *%$@!!!
			if(data.d) {
				data = data.d;
				if(typeof data === "string") {
					data = JSON.parse(data);
				}
			}
			if(!$.isArray(data)) { data = [data]; }
			var w = null,
				args = {
					'df'	: this._model.default_state,
					'dat'	: data,
					'par'	: dom.id,
					'm'		: this._model.data,
					't_id'	: this._id,
					't_cnt'	: this._cnt,
					'sel'	: this._data.core.selected
				},
				inst = this,
				func = function (data, undefined) {
					if(data.data) { data = data.data; }
					var dat = data.dat,
						par = data.par,
						chd = [],
						dpc = [],
						add = [],
						df = data.df,
						t_id = data.t_id,
						t_cnt = data.t_cnt,
						m = data.m,
						p = m[par],
						sel = data.sel,
						tmp, i, j, rslt,
						parse_flat = function (d, p, ps) {
							if(!ps) { ps = []; }
							else { ps = ps.concat(); }
							if(p) { ps.unshift(p); }
							var tid = d.id.toString(),
								i, j, c, e,
								tmp = {
									id			: tid,
									text		: d.text || '',
									icon		: d.icon !== undefined ? d.icon : true,
									parent		: p,
									parents		: ps,
									children	: d.children || [],
									children_d	: d.children_d || [],
									data		: d.data,
									state		: { },
									li_attr		: { id : false },
									a_attr		: { href : '#' },
									original	: false
								};
							for(i in df) {
								if(df.hasOwnProperty(i)) {
									tmp.state[i] = df[i];
								}
							}
							if(d && d.data && d.data.jstree && d.data.jstree.icon) {
								tmp.icon = d.data.jstree.icon;
							}
							if(tmp.icon === undefined || tmp.icon === null || tmp.icon === "") {
								tmp.icon = true;
							}
							if(d && d.data) {
								tmp.data = d.data;
								if(d.data.jstree) {
									for(i in d.data.jstree) {
										if(d.data.jstree.hasOwnProperty(i)) {
											tmp.state[i] = d.data.jstree[i];
										}
									}
								}
							}
							if(d && typeof d.state === 'object') {
								for (i in d.state) {
									if(d.state.hasOwnProperty(i)) {
										tmp.state[i] = d.state[i];
									}
								}
							}
							if(d && typeof d.li_attr === 'object') {
								for (i in d.li_attr) {
									if(d.li_attr.hasOwnProperty(i)) {
										tmp.li_attr[i] = d.li_attr[i];
									}
								}
							}
							if(!tmp.li_attr.id) {
								tmp.li_attr.id = tid;
							}
							if(d && typeof d.a_attr === 'object') {
								for (i in d.a_attr) {
									if(d.a_attr.hasOwnProperty(i)) {
										tmp.a_attr[i] = d.a_attr[i];
									}
								}
							}
							if(d && d.children && d.children === true) {
								tmp.state.loaded = false;
								tmp.children = [];
								tmp.children_d = [];
							}
							m[tmp.id] = tmp;
							for(i = 0, j = tmp.children.length; i < j; i++) {
								c = parse_flat(m[tmp.children[i]], tmp.id, ps);
								e = m[c];
								tmp.children_d.push(c);
								if(e.children_d.length) {
									tmp.children_d = tmp.children_d.concat(e.children_d);
								}
							}
							delete d.data;
							delete d.children;
							m[tmp.id].original = d;
							if(tmp.state.selected) {
								add.push(tmp.id);
							}
							return tmp.id;
						},
						parse_nest = function (d, p, ps) {
							if(!ps) { ps = []; }
							else { ps = ps.concat(); }
							if(p) { ps.unshift(p); }
							var tid = false, i, j, c, e, tmp;
							do {
								tid = 'j' + t_id + '_' + (++t_cnt);
							} while(m[tid]);

							tmp = {
								id			: false,
								text		: typeof d === 'string' ? d : '',
								icon		: typeof d === 'object' && d.icon !== undefined ? d.icon : true,
								parent		: p,
								parents		: ps,
								children	: [],
								children_d	: [],
								data		: null,
								state		: { },
								li_attr		: { id : false },
								a_attr		: { href : '#' },
								original	: false
							};
							for(i in df) {
								if(df.hasOwnProperty(i)) {
									tmp.state[i] = df[i];
								}
							}
							if(d && d.id) { tmp.id = d.id.toString(); }
							if(d && d.text) { tmp.text = d.text; }
							if(d && d.data && d.data.jstree && d.data.jstree.icon) {
								tmp.icon = d.data.jstree.icon;
							}
							if(tmp.icon === undefined || tmp.icon === null || tmp.icon === "") {
								tmp.icon = true;
							}
							if(d && d.data) {
								tmp.data = d.data;
								if(d.data.jstree) {
									for(i in d.data.jstree) {
										if(d.data.jstree.hasOwnProperty(i)) {
											tmp.state[i] = d.data.jstree[i];
										}
									}
								}
							}
							if(d && typeof d.state === 'object') {
								for (i in d.state) {
									if(d.state.hasOwnProperty(i)) {
										tmp.state[i] = d.state[i];
									}
								}
							}
							if(d && typeof d.li_attr === 'object') {
								for (i in d.li_attr) {
									if(d.li_attr.hasOwnProperty(i)) {
										tmp.li_attr[i] = d.li_attr[i];
									}
								}
							}
							if(tmp.li_attr.id && !tmp.id) {
								tmp.id = tmp.li_attr.id.toString();
							}
							if(!tmp.id) {
								tmp.id = tid;
							}
							if(!tmp.li_attr.id) {
								tmp.li_attr.id = tmp.id;
							}
							if(d && typeof d.a_attr === 'object') {
								for (i in d.a_attr) {
									if(d.a_attr.hasOwnProperty(i)) {
										tmp.a_attr[i] = d.a_attr[i];
									}
								}
							}
							if(d && d.children && d.children.length) {
								for(i = 0, j = d.children.length; i < j; i++) {
									c = parse_nest(d.children[i], tmp.id, ps);
									e = m[c];
									tmp.children.push(c);
									if(e.children_d.length) {
										tmp.children_d = tmp.children_d.concat(e.children_d);
									}
								}
								tmp.children_d = tmp.children_d.concat(tmp.children);
							}
							if(d && d.children && d.children === true) {
								tmp.state.loaded = false;
								tmp.children = [];
								tmp.children_d = [];
							}
							delete d.data;
							delete d.children;
							tmp.original = d;
							m[tmp.id] = tmp;
							if(tmp.state.selected) {
								add.push(tmp.id);
							}
							return tmp.id;
						};

					if(dat.length && dat[0].id !== undefined && dat[0].parent !== undefined) {
						// Flat JSON support (for easy import from DB):
						// 1) convert to object (foreach)
						for(i = 0, j = dat.length; i < j; i++) {
							if(!dat[i].children) {
								dat[i].children = [];
							}
							if(!dat[i].state) {
								dat[i].state = {};
							}
							m[dat[i].id.toString()] = dat[i];
						}
						// 2) populate children (foreach)
						for(i = 0, j = dat.length; i < j; i++) {
							if (!m[dat[i].parent.toString()]) {
								if (typeof inst !== "undefined") {
									inst._data.core.last_error = { 'error' : 'parse', 'plugin' : 'core', 'id' : 'core_07', 'reason' : 'Node with invalid parent', 'data' : JSON.stringify({ 'id' : dat[i].id.toString(), 'parent' : dat[i].parent.toString() }) };
									inst.settings.core.error.call(inst, inst._data.core.last_error);
								}
								continue;
							}

							m[dat[i].parent.toString()].children.push(dat[i].id.toString());
							// populate parent.children_d
							p.children_d.push(dat[i].id.toString());
						}
						// 3) normalize && populate parents and children_d with recursion
						for(i = 0, j = p.children.length; i < j; i++) {
							tmp = parse_flat(m[p.children[i]], par, p.parents.concat());
							dpc.push(tmp);
							if(m[tmp].children_d.length) {
								dpc = dpc.concat(m[tmp].children_d);
							}
						}
						for(i = 0, j = p.parents.length; i < j; i++) {
							m[p.parents[i]].children_d = m[p.parents[i]].children_d.concat(dpc);
						}
						// ?) three_state selection - p.state.selected && t - (if three_state foreach(dat => ch) -> foreach(parents) if(parent.selected) child.selected = true;
						rslt = {
							'cnt' : t_cnt,
							'mod' : m,
							'sel' : sel,
							'par' : par,
							'dpc' : dpc,
							'add' : add
						};
					}
					else {
						for(i = 0, j = dat.length; i < j; i++) {
							tmp = parse_nest(dat[i], par, p.parents.concat());
							if(tmp) {
								chd.push(tmp);
								dpc.push(tmp);
								if(m[tmp].children_d.length) {
									dpc = dpc.concat(m[tmp].children_d);
								}
							}
						}
						p.children = chd;
						p.children_d = dpc;
						for(i = 0, j = p.parents.length; i < j; i++) {
							m[p.parents[i]].children_d = m[p.parents[i]].children_d.concat(dpc);
						}
						rslt = {
							'cnt' : t_cnt,
							'mod' : m,
							'sel' : sel,
							'par' : par,
							'dpc' : dpc,
							'add' : add
						};
					}
					if(typeof window === 'undefined' || typeof window.document === 'undefined') {
						postMessage(rslt);
					}
					else {
						return rslt;
					}
				},
				rslt = function (rslt, worker) {
					if(this.element === null) { return; }
					this._cnt = rslt.cnt;
					var i, m = this._model.data;
					for (i in m) {
						if (m.hasOwnProperty(i) && m[i].state && m[i].state.loading && rslt.mod[i]) {
							rslt.mod[i].state.loading = true;
						}
					}
					this._model.data = rslt.mod; // breaks the reference in load_node - careful

					if(worker) {
						var j, a = rslt.add, r = rslt.sel, s = this._data.core.selected.slice();
						m = this._model.data;
						// if selection was changed while calculating in worker
						if(r.length !== s.length || $.vakata.array_unique(r.concat(s)).length !== r.length) {
							// deselect nodes that are no longer selected
							for(i = 0, j = r.length; i < j; i++) {
								if($.inArray(r[i], a) === -1 && $.inArray(r[i], s) === -1) {
									m[r[i]].state.selected = false;
								}
							}
							// select nodes that were selected in the mean time
							for(i = 0, j = s.length; i < j; i++) {
								if($.inArray(s[i], r) === -1) {
									m[s[i]].state.selected = true;
								}
							}
						}
					}
					if(rslt.add.length) {
						this._data.core.selected = this._data.core.selected.concat(rslt.add);
					}

					this.trigger('model', { "nodes" : rslt.dpc, 'parent' : rslt.par });

					if(rslt.par !== $.jstree.root) {
						this._node_changed(rslt.par);
						this.redraw();
					}
					else {
						// this.get_container_ul().children('.jstree-initial-node').remove();
						this.redraw(true);
					}
					if(rslt.add.length) {
						this.trigger('changed', { 'action' : 'model', 'selected' : this._data.core.selected });
					}
					cb.call(this, true);
				};
			if(this.settings.core.worker && window.Blob && window.URL && window.Worker) {
				try {
					if(this._wrk === null) {
						this._wrk = window.URL.createObjectURL(
							new window.Blob(
								['self.onmessage = ' + func.toString()],
								{type:"text/javascript"}
							)
						);
					}
					if(!this._data.core.working || force_processing) {
						this._data.core.working = true;
						w = new window.Worker(this._wrk);
						w.onmessage = $.proxy(function (e) {
							rslt.call(this, e.data, true);
							try { w.terminate(); w = null; } catch(ignore) { }
							if(this._data.core.worker_queue.length) {
								this._append_json_data.apply(this, this._data.core.worker_queue.shift());
							}
							else {
								this._data.core.working = false;
							}
						}, this);
						if(!args.par) {
							if(this._data.core.worker_queue.length) {
								this._append_json_data.apply(this, this._data.core.worker_queue.shift());
							}
							else {
								this._data.core.working = false;
							}
						}
						else {
							w.postMessage(args);
						}
					}
					else {
						this._data.core.worker_queue.push([dom, data, cb, true]);
					}
				}
				catch(e) {
					rslt.call(this, func(args), false);
					if(this._data.core.worker_queue.length) {
						this._append_json_data.apply(this, this._data.core.worker_queue.shift());
					}
					else {
						this._data.core.working = false;
					}
				}
			}
			else {
				rslt.call(this, func(args), false);
			}
		},
		/**
		 * parses a node from a jQuery object and appends them to the in memory tree model. Used internally.
		 * @private
		 * @name _parse_model_from_html(d [, p, ps])
		 * @param  {jQuery} d the jQuery object to parse
		 * @param  {String} p the parent ID
		 * @param  {Array} ps list of all parents
		 * @return {String} the ID of the object added to the model
		 */
		_parse_model_from_html : function (d, p, ps) {
			if(!ps) { ps = []; }
			else { ps = [].concat(ps); }
			if(p) { ps.unshift(p); }
			var c, e, m = this._model.data,
				data = {
					id			: false,
					text		: false,
					icon		: true,
					parent		: p,
					parents		: ps,
					children	: [],
					children_d	: [],
					data		: null,
					state		: { },
					li_attr		: { id : false },
					a_attr		: { href : '#' },
					original	: false
				}, i, tmp, tid;
			for(i in this._model.default_state) {
				if(this._model.default_state.hasOwnProperty(i)) {
					data.state[i] = this._model.default_state[i];
				}
			}
			tmp = $.vakata.attributes(d, true);
			$.each(tmp, function (i, v) {
				v = $.trim(v);
				if(!v.length) { return true; }
				data.li_attr[i] = v;
				if(i === 'id') {
					data.id = v.toString();
				}
			});
			tmp = d.children('a').first();
			if(tmp.length) {
				tmp = $.vakata.attributes(tmp, true);
				$.each(tmp, function (i, v) {
					v = $.trim(v);
					if(v.length) {
						data.a_attr[i] = v;
					}
				});
			}
			tmp = d.children("a").first().length ? d.children("a").first().clone() : d.clone();
			tmp.children("ins, i, ul").remove();
			tmp = tmp.html();
			tmp = $('<div />').html(tmp);
			data.text = this.settings.core.force_text ? tmp.text() : tmp.html();
			tmp = d.data();
			data.data = tmp ? $.extend(true, {}, tmp) : null;
			data.state.opened = d.hasClass('jstree-open');
			data.state.selected = d.children('a').hasClass('jstree-clicked');
			data.state.disabled = d.children('a').hasClass('jstree-disabled');
			if(data.data && data.data.jstree) {
				for(i in data.data.jstree) {
					if(data.data.jstree.hasOwnProperty(i)) {
						data.state[i] = data.data.jstree[i];
					}
				}
			}
			tmp = d.children("a").children(".jstree-themeicon");
			if(tmp.length) {
				data.icon = tmp.hasClass('jstree-themeicon-hidden') ? false : tmp.attr('rel');
			}
			if(data.state.icon !== undefined) {
				data.icon = data.state.icon;
			}
			if(data.icon === undefined || data.icon === null || data.icon === "") {
				data.icon = true;
			}
			tmp = d.children("ul").children("li");
			do {
				tid = 'j' + this._id + '_' + (++this._cnt);
			} while(m[tid]);
			data.id = data.li_attr.id ? data.li_attr.id.toString() : tid;
			if(tmp.length) {
				tmp.each($.proxy(function (i, v) {
					c = this._parse_model_from_html($(v), data.id, ps);
					e = this._model.data[c];
					data.children.push(c);
					if(e.children_d.length) {
						data.children_d = data.children_d.concat(e.children_d);
					}
				}, this));
				data.children_d = data.children_d.concat(data.children);
			}
			else {
				if(d.hasClass('jstree-closed')) {
					data.state.loaded = false;
				}
			}
			if(data.li_attr['class']) {
				data.li_attr['class'] = data.li_attr['class'].replace('jstree-closed','').replace('jstree-open','');
			}
			if(data.a_attr['class']) {
				data.a_attr['class'] = data.a_attr['class'].replace('jstree-clicked','').replace('jstree-disabled','');
			}
			m[data.id] = data;
			if(data.state.selected) {
				this._data.core.selected.push(data.id);
			}
			return data.id;
		},
		/**
		 * parses a node from a JSON object (used when dealing with flat data, which has no nesting of children, but has id and parent properties) and appends it to the in memory tree model. Used internally.
		 * @private
		 * @name _parse_model_from_flat_json(d [, p, ps])
		 * @param  {Object} d the JSON object to parse
		 * @param  {String} p the parent ID
		 * @param  {Array} ps list of all parents
		 * @return {String} the ID of the object added to the model
		 */
		_parse_model_from_flat_json : function (d, p, ps) {
			if(!ps) { ps = []; }
			else { ps = ps.concat(); }
			if(p) { ps.unshift(p); }
			var tid = d.id.toString(),
				m = this._model.data,
				df = this._model.default_state,
				i, j, c, e,
				tmp = {
					id			: tid,
					text		: d.text || '',
					icon		: d.icon !== undefined ? d.icon : true,
					parent		: p,
					parents		: ps,
					children	: d.children || [],
					children_d	: d.children_d || [],
					data		: d.data,
					state		: { },
					li_attr		: { id : false },
					a_attr		: { href : '#' },
					original	: false
				};
			for(i in df) {
				if(df.hasOwnProperty(i)) {
					tmp.state[i] = df[i];
				}
			}
			if(d && d.data && d.data.jstree && d.data.jstree.icon) {
				tmp.icon = d.data.jstree.icon;
			}
			if(tmp.icon === undefined || tmp.icon === null || tmp.icon === "") {
				tmp.icon = true;
			}
			if(d && d.data) {
				tmp.data = d.data;
				if(d.data.jstree) {
					for(i in d.data.jstree) {
						if(d.data.jstree.hasOwnProperty(i)) {
							tmp.state[i] = d.data.jstree[i];
						}
					}
				}
			}
			if(d && typeof d.state === 'object') {
				for (i in d.state) {
					if(d.state.hasOwnProperty(i)) {
						tmp.state[i] = d.state[i];
					}
				}
			}
			if(d && typeof d.li_attr === 'object') {
				for (i in d.li_attr) {
					if(d.li_attr.hasOwnProperty(i)) {
						tmp.li_attr[i] = d.li_attr[i];
					}
				}
			}
			if(!tmp.li_attr.id) {
				tmp.li_attr.id = tid;
			}
			if(d && typeof d.a_attr === 'object') {
				for (i in d.a_attr) {
					if(d.a_attr.hasOwnProperty(i)) {
						tmp.a_attr[i] = d.a_attr[i];
					}
				}
			}
			if(d && d.children && d.children === true) {
				tmp.state.loaded = false;
				tmp.children = [];
				tmp.children_d = [];
			}
			m[tmp.id] = tmp;
			for(i = 0, j = tmp.children.length; i < j; i++) {
				c = this._parse_model_from_flat_json(m[tmp.children[i]], tmp.id, ps);
				e = m[c];
				tmp.children_d.push(c);
				if(e.children_d.length) {
					tmp.children_d = tmp.children_d.concat(e.children_d);
				}
			}
			delete d.data;
			delete d.children;
			m[tmp.id].original = d;
			if(tmp.state.selected) {
				this._data.core.selected.push(tmp.id);
			}
			return tmp.id;
		},
		/**
		 * parses a node from a JSON object and appends it to the in memory tree model. Used internally.
		 * @private
		 * @name _parse_model_from_json(d [, p, ps])
		 * @param  {Object} d the JSON object to parse
		 * @param  {String} p the parent ID
		 * @param  {Array} ps list of all parents
		 * @return {String} the ID of the object added to the model
		 */
		_parse_model_from_json : function (d, p, ps) {
			if(!ps) { ps = []; }
			else { ps = ps.concat(); }
			if(p) { ps.unshift(p); }
			var tid = false, i, j, c, e, m = this._model.data, df = this._model.default_state, tmp;
			do {
				tid = 'j' + this._id + '_' + (++this._cnt);
			} while(m[tid]);

			tmp = {
				id			: false,
				text		: typeof d === 'string' ? d : '',
				icon		: typeof d === 'object' && d.icon !== undefined ? d.icon : true,
				parent		: p,
				parents		: ps,
				children	: [],
				children_d	: [],
				data		: null,
				state		: { },
				li_attr		: { id : false },
				a_attr		: { href : '#' },
				original	: false
			};
			for(i in df) {
				if(df.hasOwnProperty(i)) {
					tmp.state[i] = df[i];
				}
			}
			if(d && d.id) { tmp.id = d.id.toString(); }
			if(d && d.text) { tmp.text = d.text; }
			if(d && d.data && d.data.jstree && d.data.jstree.icon) {
				tmp.icon = d.data.jstree.icon;
			}
			if(tmp.icon === undefined || tmp.icon === null || tmp.icon === "") {
				tmp.icon = true;
			}
			if(d && d.data) {
				tmp.data = d.data;
				if(d.data.jstree) {
					for(i in d.data.jstree) {
						if(d.data.jstree.hasOwnProperty(i)) {
							tmp.state[i] = d.data.jstree[i];
						}
					}
				}
			}
			if(d && typeof d.state === 'object') {
				for (i in d.state) {
					if(d.state.hasOwnProperty(i)) {
						tmp.state[i] = d.state[i];
					}
				}
			}
			if(d && typeof d.li_attr === 'object') {
				for (i in d.li_attr) {
					if(d.li_attr.hasOwnProperty(i)) {
						tmp.li_attr[i] = d.li_attr[i];
					}
				}
			}
			if(tmp.li_attr.id && !tmp.id) {
				tmp.id = tmp.li_attr.id.toString();
			}
			if(!tmp.id) {
				tmp.id = tid;
			}
			if(!tmp.li_attr.id) {
				tmp.li_attr.id = tmp.id;
			}
			if(d && typeof d.a_attr === 'object') {
				for (i in d.a_attr) {
					if(d.a_attr.hasOwnProperty(i)) {
						tmp.a_attr[i] = d.a_attr[i];
					}
				}
			}
			if(d && d.children && d.children.length) {
				for(i = 0, j = d.children.length; i < j; i++) {
					c = this._parse_model_from_json(d.children[i], tmp.id, ps);
					e = m[c];
					tmp.children.push(c);
					if(e.children_d.length) {
						tmp.children_d = tmp.children_d.concat(e.children_d);
					}
				}
				tmp.children_d = tmp.children.concat(tmp.children_d);
			}
			if(d && d.children && d.children === true) {
				tmp.state.loaded = false;
				tmp.children = [];
				tmp.children_d = [];
			}
			delete d.data;
			delete d.children;
			tmp.original = d;
			m[tmp.id] = tmp;
			if(tmp.state.selected) {
				this._data.core.selected.push(tmp.id);
			}
			return tmp.id;
		},
		/**
		 * redraws all nodes that need to be redrawn. Used internally.
		 * @private
		 * @name _redraw()
		 * @trigger redraw.jstree
		 */
		_redraw : function () {
			var nodes = this._model.force_full_redraw ? this._model.data[$.jstree.root].children.concat([]) : this._model.changed.concat([]),
				f = document.createElement('UL'), tmp, i, j, fe = this._data.core.focused;
			for(i = 0, j = nodes.length; i < j; i++) {
				tmp = this.redraw_node(nodes[i], true, this._model.force_full_redraw);
				if(tmp && this._model.force_full_redraw) {
					f.appendChild(tmp);
				}
			}
			if(this._model.force_full_redraw) {
				f.className = this.get_container_ul()[0].className;
				f.setAttribute('role','group');
				this.element.empty().append(f);
				//this.get_container_ul()[0].appendChild(f);
			}
			if(fe !== null && this.settings.core.restore_focus) {
				tmp = this.get_node(fe, true);
				if(tmp && tmp.length && tmp.children('.jstree-anchor')[0] !== document.activeElement) {
					tmp.children('.jstree-anchor').focus();
				}
				else {
					this._data.core.focused = null;
				}
			}
			this._model.force_full_redraw = false;
			this._model.changed = [];
			/**
			 * triggered after nodes are redrawn
			 * @event
			 * @name redraw.jstree
			 * @param {array} nodes the redrawn nodes
			 */
			this.trigger('redraw', { "nodes" : nodes });
		},
		/**
		 * redraws all nodes that need to be redrawn or optionally - the whole tree
		 * @name redraw([full])
		 * @param {Boolean} full if set to `true` all nodes are redrawn.
		 */
		redraw : function (full) {
			if(full) {
				this._model.force_full_redraw = true;
			}
			//if(this._model.redraw_timeout) {
			//	clearTimeout(this._model.redraw_timeout);
			//}
			//this._model.redraw_timeout = setTimeout($.proxy(this._redraw, this),0);
			this._redraw();
		},
		/**
		 * redraws a single node's children. Used internally.
		 * @private
		 * @name draw_children(node)
		 * @param {mixed} node the node whose children will be redrawn
		 */
		draw_children : function (node) {
			var obj = this.get_node(node),
				i = false,
				j = false,
				k = false,
				d = document;
			if(!obj) { return false; }
			if(obj.id === $.jstree.root) { return this.redraw(true); }
			node = this.get_node(node, true);
			if(!node || !node.length) { return false; } // TODO: quick toggle

			node.children('.jstree-children').remove();
			node = node[0];
			if(obj.children.length && obj.state.loaded) {
				k = d.createElement('UL');
				k.setAttribute('role', 'group');
				k.className = 'jstree-children';
				for(i = 0, j = obj.children.length; i < j; i++) {
					k.appendChild(this.redraw_node(obj.children[i], true, true));
				}
				node.appendChild(k);
			}
		},
		/**
		 * redraws a single node. Used internally.
		 * @private
		 * @name redraw_node(node, deep, is_callback, force_render)
		 * @param {mixed} node the node to redraw
		 * @param {Boolean} deep should child nodes be redrawn too
		 * @param {Boolean} is_callback is this a recursion call
		 * @param {Boolean} force_render should children of closed parents be drawn anyway
		 */
		redraw_node : function (node, deep, is_callback, force_render) {
			var obj = this.get_node(node),
				par = false,
				ind = false,
				old = false,
				i = false,
				j = false,
				k = false,
				c = '',
				d = document,
				m = this._model.data,
				f = false,
				s = false,
				tmp = null,
				t = 0,
				l = 0,
				has_children = false,
				last_sibling = false;
			if(!obj) { return false; }
			if(obj.id === $.jstree.root) {  return this.redraw(true); }
			deep = deep || obj.children.length === 0;
			node = !document.querySelector ? document.getElementById(obj.id) : this.element[0].querySelector('#' + ("0123456789".indexOf(obj.id[0]) !== -1 ? '\\3' + obj.id[0] + ' ' + obj.id.substr(1).replace($.jstree.idregex,'\\$&') : obj.id.replace($.jstree.idregex,'\\$&')) ); //, this.element);
			if(!node) {
				deep = true;
				//node = d.createElement('LI');
				if(!is_callback) {
					par = obj.parent !== $.jstree.root ? $('#' + obj.parent.replace($.jstree.idregex,'\\$&'), this.element)[0] : null;
					if(par !== null && (!par || !m[obj.parent].state.opened)) {
						return false;
					}
					ind = $.inArray(obj.id, par === null ? m[$.jstree.root].children : m[obj.parent].children);
				}
			}
			else {
				node = $(node);
				if(!is_callback) {
					par = node.parent().parent()[0];
					if(par === this.element[0]) {
						par = null;
					}
					ind = node.index();
				}
				// m[obj.id].data = node.data(); // use only node's data, no need to touch jquery storage
				if(!deep && obj.children.length && !node.children('.jstree-children').length) {
					deep = true;
				}
				if(!deep) {
					old = node.children('.jstree-children')[0];
				}
				f = node.children('.jstree-anchor')[0] === document.activeElement;
				node.remove();
				//node = d.createElement('LI');
				//node = node[0];
			}
			node = this._data.core.node.cloneNode(true);
			// node is DOM, deep is boolean

			c = 'jstree-node ';
			for(i in obj.li_attr) {
				if(obj.li_attr.hasOwnProperty(i)) {
					if(i === 'id') { continue; }
					if(i !== 'class') {
						node.setAttribute(i, obj.li_attr[i]);
					}
					else {
						c += obj.li_attr[i];
					}
				}
			}
			if(!obj.a_attr.id) {
				obj.a_attr.id = obj.id + '_anchor';
			}
			node.setAttribute('aria-selected', !!obj.state.selected);
			node.setAttribute('aria-level', obj.parents.length);
			node.setAttribute('aria-labelledby', obj.a_attr.id);
			if(obj.state.disabled) {
				node.setAttribute('aria-disabled', true);
			}

			for(i = 0, j = obj.children.length; i < j; i++) {
				if(!m[obj.children[i]].state.hidden) {
					has_children = true;
					break;
				}
			}
			if(obj.parent !== null && m[obj.parent] && !obj.state.hidden) {
				i = $.inArray(obj.id, m[obj.parent].children);
				last_sibling = obj.id;
				if(i !== -1) {
					i++;
					for(j = m[obj.parent].children.length; i < j; i++) {
						if(!m[m[obj.parent].children[i]].state.hidden) {
							last_sibling = m[obj.parent].children[i];
						}
						if(last_sibling !== obj.id) {
							break;
						}
					}
				}
			}

			if(obj.state.hidden) {
				c += ' jstree-hidden';
			}
			if (obj.state.loading) {
				c += ' jstree-loading';
			}
			if(obj.state.loaded && !has_children) {
				c += ' jstree-leaf';
			}
			else {
				c += obj.state.opened && obj.state.loaded ? ' jstree-open' : ' jstree-closed';
				node.setAttribute('aria-expanded', (obj.state.opened && obj.state.loaded) );
			}
			if(last_sibling === obj.id) {
				c += ' jstree-last';
			}
			node.id = obj.id;
			node.className = c;
			c = ( obj.state.selected ? ' jstree-clicked' : '') + ( obj.state.disabled ? ' jstree-disabled' : '');
			for(j in obj.a_attr) {
				if(obj.a_attr.hasOwnProperty(j)) {
					if(j === 'href' && obj.a_attr[j] === '#') { continue; }
					if(j !== 'class') {
						node.childNodes[1].setAttribute(j, obj.a_attr[j]);
					}
					else {
						c += ' ' + obj.a_attr[j];
					}
				}
			}
			if(c.length) {
				node.childNodes[1].className = 'jstree-anchor ' + c;
			}
			if((obj.icon && obj.icon !== true) || obj.icon === false) {
				if(obj.icon === false) {
					node.childNodes[1].childNodes[0].className += ' jstree-themeicon-hidden';
				}
				else if(obj.icon.indexOf('/') === -1 && obj.icon.indexOf('.') === -1) {
					node.childNodes[1].childNodes[0].className += ' ' + obj.icon + ' jstree-themeicon-custom';
				}
				else {
					node.childNodes[1].childNodes[0].style.backgroundImage = 'url("'+obj.icon+'")';
					node.childNodes[1].childNodes[0].style.backgroundPosition = 'center center';
					node.childNodes[1].childNodes[0].style.backgroundSize = 'auto';
					node.childNodes[1].childNodes[0].className += ' jstree-themeicon-custom';
				}
			}

			if(this.settings.core.force_text) {
				node.childNodes[1].appendChild(d.createTextNode(obj.text));
			}
			else {
				node.childNodes[1].innerHTML += obj.text;
			}


			if(deep && obj.children.length && (obj.state.opened || force_render) && obj.state.loaded) {
				k = d.createElement('UL');
				k.setAttribute('role', 'group');
				k.className = 'jstree-children';
				for(i = 0, j = obj.children.length; i < j; i++) {
					k.appendChild(this.redraw_node(obj.children[i], deep, true));
				}
				node.appendChild(k);
			}
			if(old) {
				node.appendChild(old);
			}
			if(!is_callback) {
				// append back using par / ind
				if(!par) {
					par = this.element[0];
				}
				for(i = 0, j = par.childNodes.length; i < j; i++) {
					if(par.childNodes[i] && par.childNodes[i].className && par.childNodes[i].className.indexOf('jstree-children') !== -1) {
						tmp = par.childNodes[i];
						break;
					}
				}
				if(!tmp) {
					tmp = d.createElement('UL');
					tmp.setAttribute('role', 'group');
					tmp.className = 'jstree-children';
					par.appendChild(tmp);
				}
				par = tmp;

				if(ind < par.childNodes.length) {
					par.insertBefore(node, par.childNodes[ind]);
				}
				else {
					par.appendChild(node);
				}
				if(f) {
					t = this.element[0].scrollTop;
					l = this.element[0].scrollLeft;
					node.childNodes[1].focus();
					this.element[0].scrollTop = t;
					this.element[0].scrollLeft = l;
				}
			}
			if(obj.state.opened && !obj.state.loaded) {
				obj.state.opened = false;
				setTimeout($.proxy(function () {
					this.open_node(obj.id, false, 0);
				}, this), 0);
			}
			return node;
		},
		/**
		 * opens a node, revealing its children. If the node is not loaded it will be loaded and opened once ready.
		 * @name open_node(obj [, callback, animation])
		 * @param {mixed} obj the node to open
		 * @param {Function} callback a function to execute once the node is opened
		 * @param {Number} animation the animation duration in milliseconds when opening the node (overrides the `core.animation` setting). Use `false` for no animation.
		 * @trigger open_node.jstree, after_open.jstree, before_open.jstree
		 */
		open_node : function (obj, callback, animation) {
			var t1, t2, d, t;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.open_node(obj[t1], callback, animation);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			animation = animation === undefined ? this.settings.core.animation : animation;
			if(!this.is_closed(obj)) {
				if(callback) {
					callback.call(this, obj, false);
				}
				return false;
			}
			if(!this.is_loaded(obj)) {
				if(this.is_loading(obj)) {
					return setTimeout($.proxy(function () {
						this.open_node(obj, callback, animation);
					}, this), 500);
				}
				this.load_node(obj, function (o, ok) {
					return ok ? this.open_node(o, callback, animation) : (callback ? callback.call(this, o, false) : false);
				});
			}
			else {
				d = this.get_node(obj, true);
				t = this;
				if(d.length) {
					if(animation && d.children(".jstree-children").length) {
						d.children(".jstree-children").stop(true, true);
					}
					if(obj.children.length && !this._firstChild(d.children('.jstree-children')[0])) {
						this.draw_children(obj);
						//d = this.get_node(obj, true);
					}
					if(!animation) {
						this.trigger('before_open', { "node" : obj });
						d[0].className = d[0].className.replace('jstree-closed', 'jstree-open');
						d[0].setAttribute("aria-expanded", true);
					}
					else {
						this.trigger('before_open', { "node" : obj });
						d
							.children(".jstree-children").css("display","none").end()
							.removeClass("jstree-closed").addClass("jstree-open").attr("aria-expanded", true)
							.children(".jstree-children").stop(true, true)
								.slideDown(animation, function () {
									this.style.display = "";
									if (t.element) {
										t.trigger("after_open", { "node" : obj });
									}
								});
					}
				}
				obj.state.opened = true;
				if(callback) {
					callback.call(this, obj, true);
				}
				if(!d.length) {
					/**
					 * triggered when a node is about to be opened (if the node is supposed to be in the DOM, it will be, but it won't be visible yet)
					 * @event
					 * @name before_open.jstree
					 * @param {Object} node the opened node
					 */
					this.trigger('before_open', { "node" : obj });
				}
				/**
				 * triggered when a node is opened (if there is an animation it will not be completed yet)
				 * @event
				 * @name open_node.jstree
				 * @param {Object} node the opened node
				 */
				this.trigger('open_node', { "node" : obj });
				if(!animation || !d.length) {
					/**
					 * triggered when a node is opened and the animation is complete
					 * @event
					 * @name after_open.jstree
					 * @param {Object} node the opened node
					 */
					this.trigger("after_open", { "node" : obj });
				}
				return true;
			}
		},
		/**
		 * opens every parent of a node (node should be loaded)
		 * @name _open_to(obj)
		 * @param {mixed} obj the node to reveal
		 * @private
		 */
		_open_to : function (obj) {
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			var i, j, p = obj.parents;
			for(i = 0, j = p.length; i < j; i+=1) {
				if(i !== $.jstree.root) {
					this.open_node(p[i], false, 0);
				}
			}
			return $('#' + obj.id.replace($.jstree.idregex,'\\$&'), this.element);
		},
		/**
		 * closes a node, hiding its children
		 * @name close_node(obj [, animation])
		 * @param {mixed} obj the node to close
		 * @param {Number} animation the animation duration in milliseconds when closing the node (overrides the `core.animation` setting). Use `false` for no animation.
		 * @trigger close_node.jstree, after_close.jstree
		 */
		close_node : function (obj, animation) {
			var t1, t2, t, d;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.close_node(obj[t1], animation);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			if(this.is_closed(obj)) {
				return false;
			}
			animation = animation === undefined ? this.settings.core.animation : animation;
			t = this;
			d = this.get_node(obj, true);

			obj.state.opened = false;
			/**
			 * triggered when a node is closed (if there is an animation it will not be complete yet)
			 * @event
			 * @name close_node.jstree
			 * @param {Object} node the closed node
			 */
			this.trigger('close_node',{ "node" : obj });
			if(!d.length) {
				/**
				 * triggered when a node is closed and the animation is complete
				 * @event
				 * @name after_close.jstree
				 * @param {Object} node the closed node
				 */
				this.trigger("after_close", { "node" : obj });
			}
			else {
				if(!animation) {
					d[0].className = d[0].className.replace('jstree-open', 'jstree-closed');
					d.attr("aria-expanded", false).children('.jstree-children').remove();
					this.trigger("after_close", { "node" : obj });
				}
				else {
					d
						.children(".jstree-children").attr("style","display:block !important").end()
						.removeClass("jstree-open").addClass("jstree-closed").attr("aria-expanded", false)
						.children(".jstree-children").stop(true, true).slideUp(animation, function () {
							this.style.display = "";
							d.children('.jstree-children').remove();
							if (t.element) {
								t.trigger("after_close", { "node" : obj });
							}
						});
				}
			}
		},
		/**
		 * toggles a node - closing it if it is open, opening it if it is closed
		 * @name toggle_node(obj)
		 * @param {mixed} obj the node to toggle
		 */
		toggle_node : function (obj) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.toggle_node(obj[t1]);
				}
				return true;
			}
			if(this.is_closed(obj)) {
				return this.open_node(obj);
			}
			if(this.is_open(obj)) {
				return this.close_node(obj);
			}
		},
		/**
		 * opens all nodes within a node (or the tree), revealing their children. If the node is not loaded it will be loaded and opened once ready.
		 * @name open_all([obj, animation, original_obj])
		 * @param {mixed} obj the node to open recursively, omit to open all nodes in the tree
		 * @param {Number} animation the animation duration in milliseconds when opening the nodes, the default is no animation
		 * @param {jQuery} reference to the node that started the process (internal use)
		 * @trigger open_all.jstree
		 */
		open_all : function (obj, animation, original_obj) {
			if(!obj) { obj = $.jstree.root; }
			obj = this.get_node(obj);
			if(!obj) { return false; }
			var dom = obj.id === $.jstree.root ? this.get_container_ul() : this.get_node(obj, true), i, j, _this;
			if(!dom.length) {
				for(i = 0, j = obj.children_d.length; i < j; i++) {
					if(this.is_closed(this._model.data[obj.children_d[i]])) {
						this._model.data[obj.children_d[i]].state.opened = true;
					}
				}
				return this.trigger('open_all', { "node" : obj });
			}
			original_obj = original_obj || dom;
			_this = this;
			dom = this.is_closed(obj) ? dom.find('.jstree-closed').addBack() : dom.find('.jstree-closed');
			dom.each(function () {
				_this.open_node(
					this,
					function(node, status) { if(status && this.is_parent(node)) { this.open_all(node, animation, original_obj); } },
					animation || 0
				);
			});
			if(original_obj.find('.jstree-closed').length === 0) {
				/**
				 * triggered when an `open_all` call completes
				 * @event
				 * @name open_all.jstree
				 * @param {Object} node the opened node
				 */
				this.trigger('open_all', { "node" : this.get_node(original_obj) });
			}
		},
		/**
		 * closes all nodes within a node (or the tree), revealing their children
		 * @name close_all([obj, animation])
		 * @param {mixed} obj the node to close recursively, omit to close all nodes in the tree
		 * @param {Number} animation the animation duration in milliseconds when closing the nodes, the default is no animation
		 * @trigger close_all.jstree
		 */
		close_all : function (obj, animation) {
			if(!obj) { obj = $.jstree.root; }
			obj = this.get_node(obj);
			if(!obj) { return false; }
			var dom = obj.id === $.jstree.root ? this.get_container_ul() : this.get_node(obj, true),
				_this = this, i, j;
			if(dom.length) {
				dom = this.is_open(obj) ? dom.find('.jstree-open').addBack() : dom.find('.jstree-open');
				$(dom.get().reverse()).each(function () { _this.close_node(this, animation || 0); });
			}
			for(i = 0, j = obj.children_d.length; i < j; i++) {
				this._model.data[obj.children_d[i]].state.opened = false;
			}
			/**
			 * triggered when an `close_all` call completes
			 * @event
			 * @name close_all.jstree
			 * @param {Object} node the closed node
			 */
			this.trigger('close_all', { "node" : obj });
		},
		/**
		 * checks if a node is disabled (not selectable)
		 * @name is_disabled(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		is_disabled : function (obj) {
			obj = this.get_node(obj);
			return obj && obj.state && obj.state.disabled;
		},
		/**
		 * enables a node - so that it can be selected
		 * @name enable_node(obj)
		 * @param {mixed} obj the node to enable
		 * @trigger enable_node.jstree
		 */
		enable_node : function (obj) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.enable_node(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			obj.state.disabled = false;
			this.get_node(obj,true).children('.jstree-anchor').removeClass('jstree-disabled').attr('aria-disabled', false);
			/**
			 * triggered when an node is enabled
			 * @event
			 * @name enable_node.jstree
			 * @param {Object} node the enabled node
			 */
			this.trigger('enable_node', { 'node' : obj });
		},
		/**
		 * disables a node - so that it can not be selected
		 * @name disable_node(obj)
		 * @param {mixed} obj the node to disable
		 * @trigger disable_node.jstree
		 */
		disable_node : function (obj) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.disable_node(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			obj.state.disabled = true;
			this.get_node(obj,true).children('.jstree-anchor').addClass('jstree-disabled').attr('aria-disabled', true);
			/**
			 * triggered when an node is disabled
			 * @event
			 * @name disable_node.jstree
			 * @param {Object} node the disabled node
			 */
			this.trigger('disable_node', { 'node' : obj });
		},
		/**
		 * determines if a node is hidden
		 * @name is_hidden(obj)
		 * @param {mixed} obj the node
		 */
		is_hidden : function (obj) {
			obj = this.get_node(obj);
			return obj.state.hidden === true;
		},
		/**
		 * hides a node - it is still in the structure but will not be visible
		 * @name hide_node(obj)
		 * @param {mixed} obj the node to hide
		 * @param {Boolean} skip_redraw internal parameter controlling if redraw is called
		 * @trigger hide_node.jstree
		 */
		hide_node : function (obj, skip_redraw) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.hide_node(obj[t1], true);
				}
				if (!skip_redraw) {
					this.redraw();
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			if(!obj.state.hidden) {
				obj.state.hidden = true;
				this._node_changed(obj.parent);
				if(!skip_redraw) {
					this.redraw();
				}
				/**
				 * triggered when an node is hidden
				 * @event
				 * @name hide_node.jstree
				 * @param {Object} node the hidden node
				 */
				this.trigger('hide_node', { 'node' : obj });
			}
		},
		/**
		 * shows a node
		 * @name show_node(obj)
		 * @param {mixed} obj the node to show
		 * @param {Boolean} skip_redraw internal parameter controlling if redraw is called
		 * @trigger show_node.jstree
		 */
		show_node : function (obj, skip_redraw) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.show_node(obj[t1], true);
				}
				if (!skip_redraw) {
					this.redraw();
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			if(obj.state.hidden) {
				obj.state.hidden = false;
				this._node_changed(obj.parent);
				if(!skip_redraw) {
					this.redraw();
				}
				/**
				 * triggered when an node is shown
				 * @event
				 * @name show_node.jstree
				 * @param {Object} node the shown node
				 */
				this.trigger('show_node', { 'node' : obj });
			}
		},
		/**
		 * hides all nodes
		 * @name hide_all()
		 * @trigger hide_all.jstree
		 */
		hide_all : function (skip_redraw) {
			var i, m = this._model.data, ids = [];
			for(i in m) {
				if(m.hasOwnProperty(i) && i !== $.jstree.root && !m[i].state.hidden) {
					m[i].state.hidden = true;
					ids.push(i);
				}
			}
			this._model.force_full_redraw = true;
			if(!skip_redraw) {
				this.redraw();
			}
			/**
			 * triggered when all nodes are hidden
			 * @event
			 * @name hide_all.jstree
			 * @param {Array} nodes the IDs of all hidden nodes
			 */
			this.trigger('hide_all', { 'nodes' : ids });
			return ids;
		},
		/**
		 * shows all nodes
		 * @name show_all()
		 * @trigger show_all.jstree
		 */
		show_all : function (skip_redraw) {
			var i, m = this._model.data, ids = [];
			for(i in m) {
				if(m.hasOwnProperty(i) && i !== $.jstree.root && m[i].state.hidden) {
					m[i].state.hidden = false;
					ids.push(i);
				}
			}
			this._model.force_full_redraw = true;
			if(!skip_redraw) {
				this.redraw();
			}
			/**
			 * triggered when all nodes are shown
			 * @event
			 * @name show_all.jstree
			 * @param {Array} nodes the IDs of all shown nodes
			 */
			this.trigger('show_all', { 'nodes' : ids });
			return ids;
		},
		/**
		 * called when a node is selected by the user. Used internally.
		 * @private
		 * @name activate_node(obj, e)
		 * @param {mixed} obj the node
		 * @param {Object} e the related event
		 * @trigger activate_node.jstree, changed.jstree
		 */
		activate_node : function (obj, e) {
			if(this.is_disabled(obj)) {
				return false;
			}
			if(!e || typeof e !== 'object') {
				e = {};
			}

			// ensure last_clicked is still in the DOM, make it fresh (maybe it was moved?) and make sure it is still selected, if not - make last_clicked the last selected node
			this._data.core.last_clicked = this._data.core.last_clicked && this._data.core.last_clicked.id !== undefined ? this.get_node(this._data.core.last_clicked.id) : null;
			if(this._data.core.last_clicked && !this._data.core.last_clicked.state.selected) { this._data.core.last_clicked = null; }
			if(!this._data.core.last_clicked && this._data.core.selected.length) { this._data.core.last_clicked = this.get_node(this._data.core.selected[this._data.core.selected.length - 1]); }

			if(!this.settings.core.multiple || (!e.metaKey && !e.ctrlKey && !e.shiftKey) || (e.shiftKey && (!this._data.core.last_clicked || !this.get_parent(obj) || this.get_parent(obj) !== this._data.core.last_clicked.parent ) )) {
				if(!this.settings.core.multiple && (e.metaKey || e.ctrlKey || e.shiftKey) && this.is_selected(obj)) {
					this.deselect_node(obj, false, e);
				}
				else {
					this.deselect_all(true);
					this.select_node(obj, false, false, e);
					this._data.core.last_clicked = this.get_node(obj);
				}
			}
			else {
				if(e.shiftKey) {
					var o = this.get_node(obj).id,
						l = this._data.core.last_clicked.id,
						p = this.get_node(this._data.core.last_clicked.parent).children,
						c = false,
						i, j;
					for(i = 0, j = p.length; i < j; i += 1) {
						// separate IFs work whem o and l are the same
						if(p[i] === o) {
							c = !c;
						}
						if(p[i] === l) {
							c = !c;
						}
						if(!this.is_disabled(p[i]) && (c || p[i] === o || p[i] === l)) {
							if (!this.is_hidden(p[i])) {
								this.select_node(p[i], true, false, e);
							}
						}
						else {
							this.deselect_node(p[i], true, e);
						}
					}
					this.trigger('changed', { 'action' : 'select_node', 'node' : this.get_node(obj), 'selected' : this._data.core.selected, 'event' : e });
				}
				else {
					if(!this.is_selected(obj)) {
						this.select_node(obj, false, false, e);
					}
					else {
						this.deselect_node(obj, false, e);
					}
				}
			}
			/**
			 * triggered when an node is clicked or intercated with by the user
			 * @event
			 * @name activate_node.jstree
			 * @param {Object} node
			 * @param {Object} event the ooriginal event (if any) which triggered the call (may be an empty object)
			 */
			this.trigger('activate_node', { 'node' : this.get_node(obj), 'event' : e });
		},
		/**
		 * applies the hover state on a node, called when a node is hovered by the user. Used internally.
		 * @private
		 * @name hover_node(obj)
		 * @param {mixed} obj
		 * @trigger hover_node.jstree
		 */
		hover_node : function (obj) {
			obj = this.get_node(obj, true);
			if(!obj || !obj.length || obj.children('.jstree-hovered').length) {
				return false;
			}
			var o = this.element.find('.jstree-hovered'), t = this.element;
			if(o && o.length) { this.dehover_node(o); }

			obj.children('.jstree-anchor').addClass('jstree-hovered');
			/**
			 * triggered when an node is hovered
			 * @event
			 * @name hover_node.jstree
			 * @param {Object} node
			 */
			this.trigger('hover_node', { 'node' : this.get_node(obj) });
			setTimeout(function () { t.attr('aria-activedescendant', obj[0].id); }, 0);
		},
		/**
		 * removes the hover state from a nodecalled when a node is no longer hovered by the user. Used internally.
		 * @private
		 * @name dehover_node(obj)
		 * @param {mixed} obj
		 * @trigger dehover_node.jstree
		 */
		dehover_node : function (obj) {
			obj = this.get_node(obj, true);
			if(!obj || !obj.length || !obj.children('.jstree-hovered').length) {
				return false;
			}
			obj.children('.jstree-anchor').removeClass('jstree-hovered');
			/**
			 * triggered when an node is no longer hovered
			 * @event
			 * @name dehover_node.jstree
			 * @param {Object} node
			 */
			this.trigger('dehover_node', { 'node' : this.get_node(obj) });
		},
		/**
		 * select a node
		 * @name select_node(obj [, supress_event, prevent_open])
		 * @param {mixed} obj an array can be used to select multiple nodes
		 * @param {Boolean} supress_event if set to `true` the `changed.jstree` event won't be triggered
		 * @param {Boolean} prevent_open if set to `true` parents of the selected node won't be opened
		 * @trigger select_node.jstree, changed.jstree
		 */
		select_node : function (obj, supress_event, prevent_open, e) {
			var dom, t1, t2, th;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.select_node(obj[t1], supress_event, prevent_open, e);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			dom = this.get_node(obj, true);
			if(!obj.state.selected) {
				obj.state.selected = true;
				this._data.core.selected.push(obj.id);
				if(!prevent_open) {
					dom = this._open_to(obj);
				}
				if(dom && dom.length) {
					dom.attr('aria-selected', true).children('.jstree-anchor').addClass('jstree-clicked');
				}
				/**
				 * triggered when an node is selected
				 * @event
				 * @name select_node.jstree
				 * @param {Object} node
				 * @param {Array} selected the current selection
				 * @param {Object} event the event (if any) that triggered this select_node
				 */
				this.trigger('select_node', { 'node' : obj, 'selected' : this._data.core.selected, 'event' : e });
				if(!supress_event) {
					/**
					 * triggered when selection changes
					 * @event
					 * @name changed.jstree
					 * @param {Object} node
					 * @param {Object} action the action that caused the selection to change
					 * @param {Array} selected the current selection
					 * @param {Object} event the event (if any) that triggered this changed event
					 */
					this.trigger('changed', { 'action' : 'select_node', 'node' : obj, 'selected' : this._data.core.selected, 'event' : e });
				}
			}
		},
		/**
		 * deselect a node
		 * @name deselect_node(obj [, supress_event])
		 * @param {mixed} obj an array can be used to deselect multiple nodes
		 * @param {Boolean} supress_event if set to `true` the `changed.jstree` event won't be triggered
		 * @trigger deselect_node.jstree, changed.jstree
		 */
		deselect_node : function (obj, supress_event, e) {
			var t1, t2, dom;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.deselect_node(obj[t1], supress_event, e);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			dom = this.get_node(obj, true);
			if(obj.state.selected) {
				obj.state.selected = false;
				this._data.core.selected = $.vakata.array_remove_item(this._data.core.selected, obj.id);
				if(dom.length) {
					dom.attr('aria-selected', false).children('.jstree-anchor').removeClass('jstree-clicked');
				}
				/**
				 * triggered when an node is deselected
				 * @event
				 * @name deselect_node.jstree
				 * @param {Object} node
				 * @param {Array} selected the current selection
				 * @param {Object} event the event (if any) that triggered this deselect_node
				 */
				this.trigger('deselect_node', { 'node' : obj, 'selected' : this._data.core.selected, 'event' : e });
				if(!supress_event) {
					this.trigger('changed', { 'action' : 'deselect_node', 'node' : obj, 'selected' : this._data.core.selected, 'event' : e });
				}
			}
		},
		/**
		 * select all nodes in the tree
		 * @name select_all([supress_event])
		 * @param {Boolean} supress_event if set to `true` the `changed.jstree` event won't be triggered
		 * @trigger select_all.jstree, changed.jstree
		 */
		select_all : function (supress_event) {
			var tmp = this._data.core.selected.concat([]), i, j;
			this._data.core.selected = this._model.data[$.jstree.root].children_d.concat();
			for(i = 0, j = this._data.core.selected.length; i < j; i++) {
				if(this._model.data[this._data.core.selected[i]]) {
					this._model.data[this._data.core.selected[i]].state.selected = true;
				}
			}
			this.redraw(true);
			/**
			 * triggered when all nodes are selected
			 * @event
			 * @name select_all.jstree
			 * @param {Array} selected the current selection
			 */
			this.trigger('select_all', { 'selected' : this._data.core.selected });
			if(!supress_event) {
				this.trigger('changed', { 'action' : 'select_all', 'selected' : this._data.core.selected, 'old_selection' : tmp });
			}
		},
		/**
		 * deselect all selected nodes
		 * @name deselect_all([supress_event])
		 * @param {Boolean} supress_event if set to `true` the `changed.jstree` event won't be triggered
		 * @trigger deselect_all.jstree, changed.jstree
		 */
		deselect_all : function (supress_event) {
			var tmp = this._data.core.selected.concat([]), i, j;
			for(i = 0, j = this._data.core.selected.length; i < j; i++) {
				if(this._model.data[this._data.core.selected[i]]) {
					this._model.data[this._data.core.selected[i]].state.selected = false;
				}
			}
			this._data.core.selected = [];
			this.element.find('.jstree-clicked').removeClass('jstree-clicked').parent().attr('aria-selected', false);
			/**
			 * triggered when all nodes are deselected
			 * @event
			 * @name deselect_all.jstree
			 * @param {Object} node the previous selection
			 * @param {Array} selected the current selection
			 */
			this.trigger('deselect_all', { 'selected' : this._data.core.selected, 'node' : tmp });
			if(!supress_event) {
				this.trigger('changed', { 'action' : 'deselect_all', 'selected' : this._data.core.selected, 'old_selection' : tmp });
			}
		},
		/**
		 * checks if a node is selected
		 * @name is_selected(obj)
		 * @param  {mixed}  obj
		 * @return {Boolean}
		 */
		is_selected : function (obj) {
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			return obj.state.selected;
		},
		/**
		 * get an array of all selected nodes
		 * @name get_selected([full])
		 * @param  {mixed}  full if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 */
		get_selected : function (full) {
			return full ? $.map(this._data.core.selected, $.proxy(function (i) { return this.get_node(i); }, this)) : this._data.core.selected.slice();
		},
		/**
		 * get an array of all top level selected nodes (ignoring children of selected nodes)
		 * @name get_top_selected([full])
		 * @param  {mixed}  full if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 */
		get_top_selected : function (full) {
			var tmp = this.get_selected(true),
				obj = {}, i, j, k, l;
			for(i = 0, j = tmp.length; i < j; i++) {
				obj[tmp[i].id] = tmp[i];
			}
			for(i = 0, j = tmp.length; i < j; i++) {
				for(k = 0, l = tmp[i].children_d.length; k < l; k++) {
					if(obj[tmp[i].children_d[k]]) {
						delete obj[tmp[i].children_d[k]];
					}
				}
			}
			tmp = [];
			for(i in obj) {
				if(obj.hasOwnProperty(i)) {
					tmp.push(i);
				}
			}
			return full ? $.map(tmp, $.proxy(function (i) { return this.get_node(i); }, this)) : tmp;
		},
		/**
		 * get an array of all bottom level selected nodes (ignoring selected parents)
		 * @name get_bottom_selected([full])
		 * @param  {mixed}  full if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 */
		get_bottom_selected : function (full) {
			var tmp = this.get_selected(true),
				obj = [], i, j;
			for(i = 0, j = tmp.length; i < j; i++) {
				if(!tmp[i].children.length) {
					obj.push(tmp[i].id);
				}
			}
			return full ? $.map(obj, $.proxy(function (i) { return this.get_node(i); }, this)) : obj;
		},
		/**
		 * gets the current state of the tree so that it can be restored later with `set_state(state)`. Used internally.
		 * @name get_state()
		 * @private
		 * @return {Object}
		 */
		get_state : function () {
			var state	= {
				'core' : {
					'open' : [],
					'loaded' : [],
					'scroll' : {
						'left' : this.element.scrollLeft(),
						'top' : this.element.scrollTop()
					},
					/*!
					'themes' : {
						'name' : this.get_theme(),
						'icons' : this._data.core.themes.icons,
						'dots' : this._data.core.themes.dots
					},
					*/
					'selected' : []
				}
			}, i;
			for(i in this._model.data) {
				if(this._model.data.hasOwnProperty(i)) {
					if(i !== $.jstree.root) {
						if(this._model.data[i].state.loaded && this.settings.core.loaded_state) {
							state.core.loaded.push(i);
						}
						if(this._model.data[i].state.opened) {
							state.core.open.push(i);
						}
						if(this._model.data[i].state.selected) {
							state.core.selected.push(i);
						}
					}
				}
			}
			return state;
		},
		/**
		 * sets the state of the tree. Used internally.
		 * @name set_state(state [, callback])
		 * @private
		 * @param {Object} state the state to restore. Keep in mind this object is passed by reference and jstree will modify it.
		 * @param {Function} callback an optional function to execute once the state is restored.
		 * @trigger set_state.jstree
		 */
		set_state : function (state, callback) {
			if(state) {
				if(state.core && state.core.selected && state.core.initial_selection === undefined) {
					state.core.initial_selection = this._data.core.selected.concat([]).sort().join(',');
				}
				if(state.core) {
					var res, n, t, _this, i;
					if(state.core.loaded) {
						if(!this.settings.core.loaded_state || !$.isArray(state.core.loaded) || !state.core.loaded.length) {
							delete state.core.loaded;
							this.set_state(state, callback);
						}
						else {
							this._load_nodes(state.core.loaded, function (nodes) {
								delete state.core.loaded;
								this.set_state(state, callback);
							});
						}
						return false;
					}
					if(state.core.open) {
						if(!$.isArray(state.core.open) || !state.core.open.length) {
							delete state.core.open;
							this.set_state(state, callback);
						}
						else {
							this._load_nodes(state.core.open, function (nodes) {
								this.open_node(nodes, false, 0);
								delete state.core.open;
								this.set_state(state, callback);
							});
						}
						return false;
					}
					if(state.core.scroll) {
						if(state.core.scroll && state.core.scroll.left !== undefined) {
							this.element.scrollLeft(state.core.scroll.left);
						}
						if(state.core.scroll && state.core.scroll.top !== undefined) {
							this.element.scrollTop(state.core.scroll.top);
						}
						delete state.core.scroll;
						this.set_state(state, callback);
						return false;
					}
					if(state.core.selected) {
						_this = this;
						if (state.core.initial_selection === undefined ||
							state.core.initial_selection === this._data.core.selected.concat([]).sort().join(',')
						) {
							this.deselect_all();
							$.each(state.core.selected, function (i, v) {
								_this.select_node(v, false, true);
							});
						}
						delete state.core.initial_selection;
						delete state.core.selected;
						this.set_state(state, callback);
						return false;
					}
					for(i in state) {
						if(state.hasOwnProperty(i) && i !== "core" && $.inArray(i, this.settings.plugins) === -1) {
							delete state[i];
						}
					}
					if($.isEmptyObject(state.core)) {
						delete state.core;
						this.set_state(state, callback);
						return false;
					}
				}
				if($.isEmptyObject(state)) {
					state = null;
					if(callback) { callback.call(this); }
					/**
					 * triggered when a `set_state` call completes
					 * @event
					 * @name set_state.jstree
					 */
					this.trigger('set_state');
					return false;
				}
				return true;
			}
			return false;
		},
		/**
		 * refreshes the tree - all nodes are reloaded with calls to `load_node`.
		 * @name refresh()
		 * @param {Boolean} skip_loading an option to skip showing the loading indicator
		 * @param {Mixed} forget_state if set to `true` state will not be reapplied, if set to a function (receiving the current state as argument) the result of that function will be used as state
		 * @trigger refresh.jstree
		 */
		refresh : function (skip_loading, forget_state) {
			this._data.core.state = forget_state === true ? {} : this.get_state();
			if(forget_state && $.isFunction(forget_state)) { this._data.core.state = forget_state.call(this, this._data.core.state); }
			this._cnt = 0;
			this._model.data = {};
			this._model.data[$.jstree.root] = {
				id : $.jstree.root,
				parent : null,
				parents : [],
				children : [],
				children_d : [],
				state : { loaded : false }
			};
			this._data.core.selected = [];
			this._data.core.last_clicked = null;
			this._data.core.focused = null;

			var c = this.get_container_ul()[0].className;
			if(!skip_loading) {
				this.element.html("<"+"ul class='"+c+"' role='group'><"+"li class='jstree-initial-node jstree-loading jstree-leaf jstree-last' role='treeitem' id='j"+this._id+"_loading'><i class='jstree-icon jstree-ocl'></i><"+"a class='jstree-anchor' href='#'><i class='jstree-icon jstree-themeicon-hidden'></i>" + this.get_string("Loading ...") + "</a></li></ul>");
				this.element.attr('aria-activedescendant','j'+this._id+'_loading');
			}
			this.load_node($.jstree.root, function (o, s) {
				if(s) {
					this.get_container_ul()[0].className = c;
					if(this._firstChild(this.get_container_ul()[0])) {
						this.element.attr('aria-activedescendant',this._firstChild(this.get_container_ul()[0]).id);
					}
					this.set_state($.extend(true, {}, this._data.core.state), function () {
						/**
						 * triggered when a `refresh` call completes
						 * @event
						 * @name refresh.jstree
						 */
						this.trigger('refresh');
					});
				}
				this._data.core.state = null;
			});
		},
		/**
		 * refreshes a node in the tree (reload its children) all opened nodes inside that node are reloaded with calls to `load_node`.
		 * @name refresh_node(obj)
		 * @param  {mixed} obj the node
		 * @trigger refresh_node.jstree
		 */
		refresh_node : function (obj) {
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			var opened = [], to_load = [], s = this._data.core.selected.concat([]);
			to_load.push(obj.id);
			if(obj.state.opened === true) { opened.push(obj.id); }
			this.get_node(obj, true).find('.jstree-open').each(function() { to_load.push(this.id); opened.push(this.id); });
			this._load_nodes(to_load, $.proxy(function (nodes) {
				this.open_node(opened, false, 0);
				this.select_node(s);
				/**
				 * triggered when a node is refreshed
				 * @event
				 * @name refresh_node.jstree
				 * @param {Object} node - the refreshed node
				 * @param {Array} nodes - an array of the IDs of the nodes that were reloaded
				 */
				this.trigger('refresh_node', { 'node' : obj, 'nodes' : nodes });
			}, this), false, true);
		},
		/**
		 * set (change) the ID of a node
		 * @name set_id(obj, id)
		 * @param  {mixed} obj the node
		 * @param  {String} id the new ID
		 * @return {Boolean}
		 * @trigger set_id.jstree
		 */
		set_id : function (obj, id) {
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			var i, j, m = this._model.data, old = obj.id;
			id = id.toString();
			// update parents (replace current ID with new one in children and children_d)
			m[obj.parent].children[$.inArray(obj.id, m[obj.parent].children)] = id;
			for(i = 0, j = obj.parents.length; i < j; i++) {
				m[obj.parents[i]].children_d[$.inArray(obj.id, m[obj.parents[i]].children_d)] = id;
			}
			// update children (replace current ID with new one in parent and parents)
			for(i = 0, j = obj.children.length; i < j; i++) {
				m[obj.children[i]].parent = id;
			}
			for(i = 0, j = obj.children_d.length; i < j; i++) {
				m[obj.children_d[i]].parents[$.inArray(obj.id, m[obj.children_d[i]].parents)] = id;
			}
			i = $.inArray(obj.id, this._data.core.selected);
			if(i !== -1) { this._data.core.selected[i] = id; }
			// update model and obj itself (obj.id, this._model.data[KEY])
			i = this.get_node(obj.id, true);
			if(i) {
				i.attr('id', id); //.children('.jstree-anchor').attr('id', id + '_anchor').end().attr('aria-labelledby', id + '_anchor');
				if(this.element.attr('aria-activedescendant') === obj.id) {
					this.element.attr('aria-activedescendant', id);
				}
			}
			delete m[obj.id];
			obj.id = id;
			obj.li_attr.id = id;
			m[id] = obj;
			/**
			 * triggered when a node id value is changed
			 * @event
			 * @name set_id.jstree
			 * @param {Object} node
			 * @param {String} old the old id
			 */
			this.trigger('set_id',{ "node" : obj, "new" : obj.id, "old" : old });
			return true;
		},
		/**
		 * get the text value of a node
		 * @name get_text(obj)
		 * @param  {mixed} obj the node
		 * @return {String}
		 */
		get_text : function (obj) {
			obj = this.get_node(obj);
			return (!obj || obj.id === $.jstree.root) ? false : obj.text;
		},
		/**
		 * set the text value of a node. Used internally, please use `rename_node(obj, val)`.
		 * @private
		 * @name set_text(obj, val)
		 * @param  {mixed} obj the node, you can pass an array to set the text on multiple nodes
		 * @param  {String} val the new text value
		 * @return {Boolean}
		 * @trigger set_text.jstree
		 */
		set_text : function (obj, val) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.set_text(obj[t1], val);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			obj.text = val;
			if(this.get_node(obj, true).length) {
				this.redraw_node(obj.id);
			}
			/**
			 * triggered when a node text value is changed
			 * @event
			 * @name set_text.jstree
			 * @param {Object} obj
			 * @param {String} text the new value
			 */
			this.trigger('set_text',{ "obj" : obj, "text" : val });
			return true;
		},
		/**
		 * gets a JSON representation of a node (or the whole tree)
		 * @name get_json([obj, options])
		 * @param  {mixed} obj
		 * @param  {Object} options
		 * @param  {Boolean} options.no_state do not return state information
		 * @param  {Boolean} options.no_id do not return ID
		 * @param  {Boolean} options.no_children do not include children
		 * @param  {Boolean} options.no_data do not include node data
		 * @param  {Boolean} options.no_li_attr do not include LI attributes
		 * @param  {Boolean} options.no_a_attr do not include A attributes
		 * @param  {Boolean} options.flat return flat JSON instead of nested
		 * @return {Object}
		 */
		get_json : function (obj, options, flat) {
			obj = this.get_node(obj || $.jstree.root);
			if(!obj) { return false; }
			if(options && options.flat && !flat) { flat = []; }
			var tmp = {
				'id' : obj.id,
				'text' : obj.text,
				'icon' : this.get_icon(obj),
				'li_attr' : $.extend(true, {}, obj.li_attr),
				'a_attr' : $.extend(true, {}, obj.a_attr),
				'state' : {},
				'data' : options && options.no_data ? false : $.extend(true, $.isArray(obj.data)?[]:{}, obj.data)
				//( this.get_node(obj, true).length ? this.get_node(obj, true).data() : obj.data ),
			}, i, j;
			if(options && options.flat) {
				tmp.parent = obj.parent;
			}
			else {
				tmp.children = [];
			}
			if(!options || !options.no_state) {
				for(i in obj.state) {
					if(obj.state.hasOwnProperty(i)) {
						tmp.state[i] = obj.state[i];
					}
				}
			} else {
				delete tmp.state;
			}
			if(options && options.no_li_attr) {
				delete tmp.li_attr;
			}
			if(options && options.no_a_attr) {
				delete tmp.a_attr;
			}
			if(options && options.no_id) {
				delete tmp.id;
				if(tmp.li_attr && tmp.li_attr.id) {
					delete tmp.li_attr.id;
				}
				if(tmp.a_attr && tmp.a_attr.id) {
					delete tmp.a_attr.id;
				}
			}
			if(options && options.flat && obj.id !== $.jstree.root) {
				flat.push(tmp);
			}
			if(!options || !options.no_children) {
				for(i = 0, j = obj.children.length; i < j; i++) {
					if(options && options.flat) {
						this.get_json(obj.children[i], options, flat);
					}
					else {
						tmp.children.push(this.get_json(obj.children[i], options));
					}
				}
			}
			return options && options.flat ? flat : (obj.id === $.jstree.root ? tmp.children : tmp);
		},
		/**
		 * create a new node (do not confuse with load_node)
		 * @name create_node([par, node, pos, callback, is_loaded])
		 * @param  {mixed}   par       the parent node (to create a root node use either "#" (string) or `null`)
		 * @param  {mixed}   node      the data for the new node (a valid JSON object, or a simple string with the name)
		 * @param  {mixed}   pos       the index at which to insert the node, "first" and "last" are also supported, default is "last"
		 * @param  {Function} callback a function to be called once the node is created
		 * @param  {Boolean} is_loaded internal argument indicating if the parent node was succesfully loaded
		 * @return {String}            the ID of the newly create node
		 * @trigger model.jstree, create_node.jstree
		 */
		create_node : function (par, node, pos, callback, is_loaded) {
			if(par === null) { par = $.jstree.root; }
			par = this.get_node(par);
			if(!par) { return false; }
			pos = pos === undefined ? "last" : pos;
			if(!pos.toString().match(/^(before|after)$/) && !is_loaded && !this.is_loaded(par)) {
				return this.load_node(par, function () { this.create_node(par, node, pos, callback, true); });
			}
			if(!node) { node = { "text" : this.get_string('New node') }; }
			if(typeof node === "string") {
				node = { "text" : node };
			} else {
				node = $.extend(true, {}, node);
			}
			if(node.text === undefined) { node.text = this.get_string('New node'); }
			var tmp, dpc, i, j;

			if(par.id === $.jstree.root) {
				if(pos === "before") { pos = "first"; }
				if(pos === "after") { pos = "last"; }
			}
			switch(pos) {
				case "before":
					tmp = this.get_node(par.parent);
					pos = $.inArray(par.id, tmp.children);
					par = tmp;
					break;
				case "after" :
					tmp = this.get_node(par.parent);
					pos = $.inArray(par.id, tmp.children) + 1;
					par = tmp;
					break;
				case "inside":
				case "first":
					pos = 0;
					break;
				case "last":
					pos = par.children.length;
					break;
				default:
					if(!pos) { pos = 0; }
					break;
			}
			if(pos > par.children.length) { pos = par.children.length; }
			if(!node.id) { node.id = true; }
			if(!this.check("create_node", node, par, pos)) {
				this.settings.core.error.call(this, this._data.core.last_error);
				return false;
			}
			if(node.id === true) { delete node.id; }
			node = this._parse_model_from_json(node, par.id, par.parents.concat());
			if(!node) { return false; }
			tmp = this.get_node(node);
			dpc = [];
			dpc.push(node);
			dpc = dpc.concat(tmp.children_d);
			this.trigger('model', { "nodes" : dpc, "parent" : par.id });

			par.children_d = par.children_d.concat(dpc);
			for(i = 0, j = par.parents.length; i < j; i++) {
				this._model.data[par.parents[i]].children_d = this._model.data[par.parents[i]].children_d.concat(dpc);
			}
			node = tmp;
			tmp = [];
			for(i = 0, j = par.children.length; i < j; i++) {
				tmp[i >= pos ? i+1 : i] = par.children[i];
			}
			tmp[pos] = node.id;
			par.children = tmp;

			this.redraw_node(par, true);
			/**
			 * triggered when a node is created
			 * @event
			 * @name create_node.jstree
			 * @param {Object} node
			 * @param {String} parent the parent's ID
			 * @param {Number} position the position of the new node among the parent's children
			 */
			this.trigger('create_node', { "node" : this.get_node(node), "parent" : par.id, "position" : pos });
			if(callback) { callback.call(this, this.get_node(node)); }
			return node.id;
		},
		/**
		 * set the text value of a node
		 * @name rename_node(obj, val)
		 * @param  {mixed} obj the node, you can pass an array to rename multiple nodes to the same name
		 * @param  {String} val the new text value
		 * @return {Boolean}
		 * @trigger rename_node.jstree
		 */
		rename_node : function (obj, val) {
			var t1, t2, old;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.rename_node(obj[t1], val);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			old = obj.text;
			if(!this.check("rename_node", obj, this.get_parent(obj), val)) {
				this.settings.core.error.call(this, this._data.core.last_error);
				return false;
			}
			this.set_text(obj, val); // .apply(this, Array.prototype.slice.call(arguments))
			/**
			 * triggered when a node is renamed
			 * @event
			 * @name rename_node.jstree
			 * @param {Object} node
			 * @param {String} text the new value
			 * @param {String} old the old value
			 */
			this.trigger('rename_node', { "node" : obj, "text" : val, "old" : old });
			return true;
		},
		/**
		 * remove a node
		 * @name delete_node(obj)
		 * @param  {mixed} obj the node, you can pass an array to delete multiple nodes
		 * @return {Boolean}
		 * @trigger delete_node.jstree, changed.jstree
		 */
		delete_node : function (obj) {
			var t1, t2, par, pos, tmp, i, j, k, l, c, top, lft;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.delete_node(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			par = this.get_node(obj.parent);
			pos = $.inArray(obj.id, par.children);
			c = false;
			if(!this.check("delete_node", obj, par, pos)) {
				this.settings.core.error.call(this, this._data.core.last_error);
				return false;
			}
			if(pos !== -1) {
				par.children = $.vakata.array_remove(par.children, pos);
			}
			tmp = obj.children_d.concat([]);
			tmp.push(obj.id);
			for(i = 0, j = obj.parents.length; i < j; i++) {
				this._model.data[obj.parents[i]].children_d = $.vakata.array_filter(this._model.data[obj.parents[i]].children_d, function (v) {
					return $.inArray(v, tmp) === -1;
				});
			}
			for(k = 0, l = tmp.length; k < l; k++) {
				if(this._model.data[tmp[k]].state.selected) {
					c = true;
					break;
				}
			}
			if (c) {
				this._data.core.selected = $.vakata.array_filter(this._data.core.selected, function (v) {
					return $.inArray(v, tmp) === -1;
				});
			}
			/**
			 * triggered when a node is deleted
			 * @event
			 * @name delete_node.jstree
			 * @param {Object} node
			 * @param {String} parent the parent's ID
			 */
			this.trigger('delete_node', { "node" : obj, "parent" : par.id });
			if(c) {
				this.trigger('changed', { 'action' : 'delete_node', 'node' : obj, 'selected' : this._data.core.selected, 'parent' : par.id });
			}
			for(k = 0, l = tmp.length; k < l; k++) {
				delete this._model.data[tmp[k]];
			}
			if($.inArray(this._data.core.focused, tmp) !== -1) {
				this._data.core.focused = null;
				top = this.element[0].scrollTop;
				lft = this.element[0].scrollLeft;
				if(par.id === $.jstree.root) {
					if (this._model.data[$.jstree.root].children[0]) {
						this.get_node(this._model.data[$.jstree.root].children[0], true).children('.jstree-anchor').focus();
					}
				}
				else {
					this.get_node(par, true).children('.jstree-anchor').focus();
				}
				this.element[0].scrollTop  = top;
				this.element[0].scrollLeft = lft;
			}
			this.redraw_node(par, true);
			return true;
		},
		/**
		 * check if an operation is premitted on the tree. Used internally.
		 * @private
		 * @name check(chk, obj, par, pos)
		 * @param  {String} chk the operation to check, can be "create_node", "rename_node", "delete_node", "copy_node" or "move_node"
		 * @param  {mixed} obj the node
		 * @param  {mixed} par the parent
		 * @param  {mixed} pos the position to insert at, or if "rename_node" - the new name
		 * @param  {mixed} more some various additional information, for example if a "move_node" operations is triggered by DND this will be the hovered node
		 * @return {Boolean}
		 */
		check : function (chk, obj, par, pos, more) {
			obj = obj && obj.id ? obj : this.get_node(obj);
			par = par && par.id ? par : this.get_node(par);
			var tmp = chk.match(/^move_node|copy_node|create_node$/i) ? par : obj,
				chc = this.settings.core.check_callback;
			if(chk === "move_node" || chk === "copy_node") {
				if((!more || !more.is_multi) && (chk === "move_node" && $.inArray(obj.id, par.children) === pos)) {
					this._data.core.last_error = { 'error' : 'check', 'plugin' : 'core', 'id' : 'core_08', 'reason' : 'Moving node to its current position', 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
					return false;
				}
				if((!more || !more.is_multi) && (obj.id === par.id || (chk === "move_node" && $.inArray(obj.id, par.children) === pos) || $.inArray(par.id, obj.children_d) !== -1)) {
					this._data.core.last_error = { 'error' : 'check', 'plugin' : 'core', 'id' : 'core_01', 'reason' : 'Moving parent inside child', 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
					return false;
				}
			}
			if(tmp && tmp.data) { tmp = tmp.data; }
			if(tmp && tmp.functions && (tmp.functions[chk] === false || tmp.functions[chk] === true)) {
				if(tmp.functions[chk] === false) {
					this._data.core.last_error = { 'error' : 'check', 'plugin' : 'core', 'id' : 'core_02', 'reason' : 'Node data prevents function: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
				}
				return tmp.functions[chk];
			}
			if(chc === false || ($.isFunction(chc) && chc.call(this, chk, obj, par, pos, more) === false) || (chc && chc[chk] === false)) {
				this._data.core.last_error = { 'error' : 'check', 'plugin' : 'core', 'id' : 'core_03', 'reason' : 'User config for core.check_callback prevents function: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
				return false;
			}
			return true;
		},
		/**
		 * get the last error
		 * @name last_error()
		 * @return {Object}
		 */
		last_error : function () {
			return this._data.core.last_error;
		},
		/**
		 * move a node to a new parent
		 * @name move_node(obj, par [, pos, callback, is_loaded])
		 * @param  {mixed} obj the node to move, pass an array to move multiple nodes
		 * @param  {mixed} par the new parent
		 * @param  {mixed} pos the position to insert at (besides integer values, "first" and "last" are supported, as well as "before" and "after"), defaults to integer `0`
		 * @param  {function} callback a function to call once the move is completed, receives 3 arguments - the node, the new parent and the position
		 * @param  {Boolean} is_loaded internal parameter indicating if the parent node has been loaded
		 * @param  {Boolean} skip_redraw internal parameter indicating if the tree should be redrawn
		 * @param  {Boolean} instance internal parameter indicating if the node comes from another instance
		 * @trigger move_node.jstree
		 */
		move_node : function (obj, par, pos, callback, is_loaded, skip_redraw, origin) {
			var t1, t2, old_par, old_pos, new_par, old_ins, is_multi, dpc, tmp, i, j, k, l, p;

			par = this.get_node(par);
			pos = pos === undefined ? 0 : pos;
			if(!par) { return false; }
			if(!pos.toString().match(/^(before|after)$/) && !is_loaded && !this.is_loaded(par)) {
				return this.load_node(par, function () { this.move_node(obj, par, pos, callback, true, false, origin); });
			}

			if($.isArray(obj)) {
				if(obj.length === 1) {
					obj = obj[0];
				}
				else {
					//obj = obj.slice();
					for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
						if((tmp = this.move_node(obj[t1], par, pos, callback, is_loaded, false, origin))) {
							par = tmp;
							pos = "after";
						}
					}
					this.redraw();
					return true;
				}
			}
			obj = obj && obj.id ? obj : this.get_node(obj);

			if(!obj || obj.id === $.jstree.root) { return false; }

			old_par = (obj.parent || $.jstree.root).toString();
			new_par = (!pos.toString().match(/^(before|after)$/) || par.id === $.jstree.root) ? par : this.get_node(par.parent);
			old_ins = origin ? origin : (this._model.data[obj.id] ? this : $.jstree.reference(obj.id));
			is_multi = !old_ins || !old_ins._id || (this._id !== old_ins._id);
			old_pos = old_ins && old_ins._id && old_par && old_ins._model.data[old_par] && old_ins._model.data[old_par].children ? $.inArray(obj.id, old_ins._model.data[old_par].children) : -1;
			if(old_ins && old_ins._id) {
				obj = old_ins._model.data[obj.id];
			}

			if(is_multi) {
				if((tmp = this.copy_node(obj, par, pos, callback, is_loaded, false, origin))) {
					if(old_ins) { old_ins.delete_node(obj); }
					return tmp;
				}
				return false;
			}
			//var m = this._model.data;
			if(par.id === $.jstree.root) {
				if(pos === "before") { pos = "first"; }
				if(pos === "after") { pos = "last"; }
			}
			switch(pos) {
				case "before":
					pos = $.inArray(par.id, new_par.children);
					break;
				case "after" :
					pos = $.inArray(par.id, new_par.children) + 1;
					break;
				case "inside":
				case "first":
					pos = 0;
					break;
				case "last":
					pos = new_par.children.length;
					break;
				default:
					if(!pos) { pos = 0; }
					break;
			}
			if(pos > new_par.children.length) { pos = new_par.children.length; }
			if(!this.check("move_node", obj, new_par, pos, { 'core' : true, 'origin' : origin, 'is_multi' : (old_ins && old_ins._id && old_ins._id !== this._id), 'is_foreign' : (!old_ins || !old_ins._id) })) {
				this.settings.core.error.call(this, this._data.core.last_error);
				return false;
			}
			if(obj.parent === new_par.id) {
				dpc = new_par.children.concat();
				tmp = $.inArray(obj.id, dpc);
				if(tmp !== -1) {
					dpc = $.vakata.array_remove(dpc, tmp);
					if(pos > tmp) { pos--; }
				}
				tmp = [];
				for(i = 0, j = dpc.length; i < j; i++) {
					tmp[i >= pos ? i+1 : i] = dpc[i];
				}
				tmp[pos] = obj.id;
				new_par.children = tmp;
				this._node_changed(new_par.id);
				this.redraw(new_par.id === $.jstree.root);
			}
			else {
				// clean old parent and up
				tmp = obj.children_d.concat();
				tmp.push(obj.id);
				for(i = 0, j = obj.parents.length; i < j; i++) {
					dpc = [];
					p = old_ins._model.data[obj.parents[i]].children_d;
					for(k = 0, l = p.length; k < l; k++) {
						if($.inArray(p[k], tmp) === -1) {
							dpc.push(p[k]);
						}
					}
					old_ins._model.data[obj.parents[i]].children_d = dpc;
				}
				old_ins._model.data[old_par].children = $.vakata.array_remove_item(old_ins._model.data[old_par].children, obj.id);

				// insert into new parent and up
				for(i = 0, j = new_par.parents.length; i < j; i++) {
					this._model.data[new_par.parents[i]].children_d = this._model.data[new_par.parents[i]].children_d.concat(tmp);
				}
				dpc = [];
				for(i = 0, j = new_par.children.length; i < j; i++) {
					dpc[i >= pos ? i+1 : i] = new_par.children[i];
				}
				dpc[pos] = obj.id;
				new_par.children = dpc;
				new_par.children_d.push(obj.id);
				new_par.children_d = new_par.children_d.concat(obj.children_d);

				// update object
				obj.parent = new_par.id;
				tmp = new_par.parents.concat();
				tmp.unshift(new_par.id);
				p = obj.parents.length;
				obj.parents = tmp;

				// update object children
				tmp = tmp.concat();
				for(i = 0, j = obj.children_d.length; i < j; i++) {
					this._model.data[obj.children_d[i]].parents = this._model.data[obj.children_d[i]].parents.slice(0,p*-1);
					Array.prototype.push.apply(this._model.data[obj.children_d[i]].parents, tmp);
				}

				if(old_par === $.jstree.root || new_par.id === $.jstree.root) {
					this._model.force_full_redraw = true;
				}
				if(!this._model.force_full_redraw) {
					this._node_changed(old_par);
					this._node_changed(new_par.id);
				}
				if(!skip_redraw) {
					this.redraw();
				}
			}
			if(callback) { callback.call(this, obj, new_par, pos); }
			/**
			 * triggered when a node is moved
			 * @event
			 * @name move_node.jstree
			 * @param {Object} node
			 * @param {String} parent the parent's ID
			 * @param {Number} position the position of the node among the parent's children
			 * @param {String} old_parent the old parent of the node
			 * @param {Number} old_position the old position of the node
			 * @param {Boolean} is_multi do the node and new parent belong to different instances
			 * @param {jsTree} old_instance the instance the node came from
			 * @param {jsTree} new_instance the instance of the new parent
			 */
			this.trigger('move_node', { "node" : obj, "parent" : new_par.id, "position" : pos, "old_parent" : old_par, "old_position" : old_pos, 'is_multi' : (old_ins && old_ins._id && old_ins._id !== this._id), 'is_foreign' : (!old_ins || !old_ins._id), 'old_instance' : old_ins, 'new_instance' : this });
			return obj.id;
		},
		/**
		 * copy a node to a new parent
		 * @name copy_node(obj, par [, pos, callback, is_loaded])
		 * @param  {mixed} obj the node to copy, pass an array to copy multiple nodes
		 * @param  {mixed} par the new parent
		 * @param  {mixed} pos the position to insert at (besides integer values, "first" and "last" are supported, as well as "before" and "after"), defaults to integer `0`
		 * @param  {function} callback a function to call once the move is completed, receives 3 arguments - the node, the new parent and the position
		 * @param  {Boolean} is_loaded internal parameter indicating if the parent node has been loaded
		 * @param  {Boolean} skip_redraw internal parameter indicating if the tree should be redrawn
		 * @param  {Boolean} instance internal parameter indicating if the node comes from another instance
		 * @trigger model.jstree copy_node.jstree
		 */
		copy_node : function (obj, par, pos, callback, is_loaded, skip_redraw, origin) {
			var t1, t2, dpc, tmp, i, j, node, old_par, new_par, old_ins, is_multi;

			par = this.get_node(par);
			pos = pos === undefined ? 0 : pos;
			if(!par) { return false; }
			if(!pos.toString().match(/^(before|after)$/) && !is_loaded && !this.is_loaded(par)) {
				return this.load_node(par, function () { this.copy_node(obj, par, pos, callback, true, false, origin); });
			}

			if($.isArray(obj)) {
				if(obj.length === 1) {
					obj = obj[0];
				}
				else {
					//obj = obj.slice();
					for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
						if((tmp = this.copy_node(obj[t1], par, pos, callback, is_loaded, true, origin))) {
							par = tmp;
							pos = "after";
						}
					}
					this.redraw();
					return true;
				}
			}
			obj = obj && obj.id ? obj : this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }

			old_par = (obj.parent || $.jstree.root).toString();
			new_par = (!pos.toString().match(/^(before|after)$/) || par.id === $.jstree.root) ? par : this.get_node(par.parent);
			old_ins = origin ? origin : (this._model.data[obj.id] ? this : $.jstree.reference(obj.id));
			is_multi = !old_ins || !old_ins._id || (this._id !== old_ins._id);

			if(old_ins && old_ins._id) {
				obj = old_ins._model.data[obj.id];
			}

			if(par.id === $.jstree.root) {
				if(pos === "before") { pos = "first"; }
				if(pos === "after") { pos = "last"; }
			}
			switch(pos) {
				case "before":
					pos = $.inArray(par.id, new_par.children);
					break;
				case "after" :
					pos = $.inArray(par.id, new_par.children) + 1;
					break;
				case "inside":
				case "first":
					pos = 0;
					break;
				case "last":
					pos = new_par.children.length;
					break;
				default:
					if(!pos) { pos = 0; }
					break;
			}
			if(pos > new_par.children.length) { pos = new_par.children.length; }
			if(!this.check("copy_node", obj, new_par, pos, { 'core' : true, 'origin' : origin, 'is_multi' : (old_ins && old_ins._id && old_ins._id !== this._id), 'is_foreign' : (!old_ins || !old_ins._id) })) {
				this.settings.core.error.call(this, this._data.core.last_error);
				return false;
			}
			node = old_ins ? old_ins.get_json(obj, { no_id : true, no_data : true, no_state : true }) : obj;
			if(!node) { return false; }
			if(node.id === true) { delete node.id; }
			node = this._parse_model_from_json(node, new_par.id, new_par.parents.concat());
			if(!node) { return false; }
			tmp = this.get_node(node);
			if(obj && obj.state && obj.state.loaded === false) { tmp.state.loaded = false; }
			dpc = [];
			dpc.push(node);
			dpc = dpc.concat(tmp.children_d);
			this.trigger('model', { "nodes" : dpc, "parent" : new_par.id });

			// insert into new parent and up
			for(i = 0, j = new_par.parents.length; i < j; i++) {
				this._model.data[new_par.parents[i]].children_d = this._model.data[new_par.parents[i]].children_d.concat(dpc);
			}
			dpc = [];
			for(i = 0, j = new_par.children.length; i < j; i++) {
				dpc[i >= pos ? i+1 : i] = new_par.children[i];
			}
			dpc[pos] = tmp.id;
			new_par.children = dpc;
			new_par.children_d.push(tmp.id);
			new_par.children_d = new_par.children_d.concat(tmp.children_d);

			if(new_par.id === $.jstree.root) {
				this._model.force_full_redraw = true;
			}
			if(!this._model.force_full_redraw) {
				this._node_changed(new_par.id);
			}
			if(!skip_redraw) {
				this.redraw(new_par.id === $.jstree.root);
			}
			if(callback) { callback.call(this, tmp, new_par, pos); }
			/**
			 * triggered when a node is copied
			 * @event
			 * @name copy_node.jstree
			 * @param {Object} node the copied node
			 * @param {Object} original the original node
			 * @param {String} parent the parent's ID
			 * @param {Number} position the position of the node among the parent's children
			 * @param {String} old_parent the old parent of the node
			 * @param {Number} old_position the position of the original node
			 * @param {Boolean} is_multi do the node and new parent belong to different instances
			 * @param {jsTree} old_instance the instance the node came from
			 * @param {jsTree} new_instance the instance of the new parent
			 */
			this.trigger('copy_node', { "node" : tmp, "original" : obj, "parent" : new_par.id, "position" : pos, "old_parent" : old_par, "old_position" : old_ins && old_ins._id && old_par && old_ins._model.data[old_par] && old_ins._model.data[old_par].children ? $.inArray(obj.id, old_ins._model.data[old_par].children) : -1,'is_multi' : (old_ins && old_ins._id && old_ins._id !== this._id), 'is_foreign' : (!old_ins || !old_ins._id), 'old_instance' : old_ins, 'new_instance' : this });
			return tmp.id;
		},
		/**
		 * cut a node (a later call to `paste(obj)` would move the node)
		 * @name cut(obj)
		 * @param  {mixed} obj multiple objects can be passed using an array
		 * @trigger cut.jstree
		 */
		cut : function (obj) {
			if(!obj) { obj = this._data.core.selected.concat(); }
			if(!$.isArray(obj)) { obj = [obj]; }
			if(!obj.length) { return false; }
			var tmp = [], o, t1, t2;
			for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
				o = this.get_node(obj[t1]);
				if(o && o.id && o.id !== $.jstree.root) { tmp.push(o); }
			}
			if(!tmp.length) { return false; }
			ccp_node = tmp;
			ccp_inst = this;
			ccp_mode = 'move_node';
			/**
			 * triggered when nodes are added to the buffer for moving
			 * @event
			 * @name cut.jstree
			 * @param {Array} node
			 */
			this.trigger('cut', { "node" : obj });
		},
		/**
		 * copy a node (a later call to `paste(obj)` would copy the node)
		 * @name copy(obj)
		 * @param  {mixed} obj multiple objects can be passed using an array
		 * @trigger copy.jstree
		 */
		copy : function (obj) {
			if(!obj) { obj = this._data.core.selected.concat(); }
			if(!$.isArray(obj)) { obj = [obj]; }
			if(!obj.length) { return false; }
			var tmp = [], o, t1, t2;
			for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
				o = this.get_node(obj[t1]);
				if(o && o.id && o.id !== $.jstree.root) { tmp.push(o); }
			}
			if(!tmp.length) { return false; }
			ccp_node = tmp;
			ccp_inst = this;
			ccp_mode = 'copy_node';
			/**
			 * triggered when nodes are added to the buffer for copying
			 * @event
			 * @name copy.jstree
			 * @param {Array} node
			 */
			this.trigger('copy', { "node" : obj });
		},
		/**
		 * get the current buffer (any nodes that are waiting for a paste operation)
		 * @name get_buffer()
		 * @return {Object} an object consisting of `mode` ("copy_node" or "move_node"), `node` (an array of objects) and `inst` (the instance)
		 */
		get_buffer : function () {
			return { 'mode' : ccp_mode, 'node' : ccp_node, 'inst' : ccp_inst };
		},
		/**
		 * check if there is something in the buffer to paste
		 * @name can_paste()
		 * @return {Boolean}
		 */
		can_paste : function () {
			return ccp_mode !== false && ccp_node !== false; // && ccp_inst._model.data[ccp_node];
		},
		/**
		 * copy or move the previously cut or copied nodes to a new parent
		 * @name paste(obj [, pos])
		 * @param  {mixed} obj the new parent
		 * @param  {mixed} pos the position to insert at (besides integer, "first" and "last" are supported), defaults to integer `0`
		 * @trigger paste.jstree
		 */
		paste : function (obj, pos) {
			obj = this.get_node(obj);
			if(!obj || !ccp_mode || !ccp_mode.match(/^(copy_node|move_node)$/) || !ccp_node) { return false; }
			if(this[ccp_mode](ccp_node, obj, pos, false, false, false, ccp_inst)) {
				/**
				 * triggered when paste is invoked
				 * @event
				 * @name paste.jstree
				 * @param {String} parent the ID of the receiving node
				 * @param {Array} node the nodes in the buffer
				 * @param {String} mode the performed operation - "copy_node" or "move_node"
				 */
				this.trigger('paste', { "parent" : obj.id, "node" : ccp_node, "mode" : ccp_mode });
			}
			ccp_node = false;
			ccp_mode = false;
			ccp_inst = false;
		},
		/**
		 * clear the buffer of previously copied or cut nodes
		 * @name clear_buffer()
		 * @trigger clear_buffer.jstree
		 */
		clear_buffer : function () {
			ccp_node = false;
			ccp_mode = false;
			ccp_inst = false;
			/**
			 * triggered when the copy / cut buffer is cleared
			 * @event
			 * @name clear_buffer.jstree
			 */
			this.trigger('clear_buffer');
		},
		/**
		 * put a node in edit mode (input field to rename the node)
		 * @name edit(obj [, default_text, callback])
		 * @param  {mixed} obj
		 * @param  {String} default_text the text to populate the input with (if omitted or set to a non-string value the node's text value is used)
		 * @param  {Function} callback a function to be called once the text box is blurred, it is called in the instance's scope and receives the node, a status parameter (true if the rename is successful, false otherwise) and a boolean indicating if the user cancelled the edit. You can access the node's title using .text
		 */
		edit : function (obj, default_text, callback) {
			var rtl, w, a, s, t, h1, h2, fn, tmp, cancel = false;
			obj = this.get_node(obj);
			if(!obj) { return false; }
			if(!this.check("edit", obj, this.get_parent(obj))) {
				this.settings.core.error.call(this, this._data.core.last_error);
				return false;
			}
			tmp = obj;
			default_text = typeof default_text === 'string' ? default_text : obj.text;
			this.set_text(obj, "");
			obj = this._open_to(obj);
			tmp.text = default_text;

			rtl = this._data.core.rtl;
			w  = this.element.width();
			this._data.core.focused = tmp.id;
			a  = obj.children('.jstree-anchor').focus();
			s  = $('<span>');
			/*!
			oi = obj.children("i:visible"),
			ai = a.children("i:visible"),
			w1 = oi.width() * oi.length,
			w2 = ai.width() * ai.length,
			*/
			t  = default_text;
			h1 = $("<"+"div />", { css : { "position" : "absolute", "top" : "-200px", "left" : (rtl ? "0px" : "-1000px"), "visibility" : "hidden" } }).appendTo(document.body);
			h2 = $("<"+"input />", {
						"value" : t,
						"class" : "jstree-rename-input",
						// "size" : t.length,
						"css" : {
							"padding" : "0",
							"border" : "1px solid silver",
							"box-sizing" : "border-box",
							"display" : "inline-block",
							"height" : (this._data.core.li_height) + "px",
							"lineHeight" : (this._data.core.li_height) + "px",
							"width" : "150px" // will be set a bit further down
						},
						"blur" : $.proxy(function (e) {
							e.stopImmediatePropagation();
							e.preventDefault();
							var i = s.children(".jstree-rename-input"),
								v = i.val(),
								f = this.settings.core.force_text,
								nv;
							if(v === "") { v = t; }
							h1.remove();
							s.replaceWith(a);
							s.remove();
							t = f ? t : $('<div></div>').append($.parseHTML(t)).html();
							obj = this.get_node(obj);
							this.set_text(obj, t);
							nv = !!this.rename_node(obj, f ? $('<div></div>').text(v).text() : $('<div></div>').append($.parseHTML(v)).html());
							if(!nv) {
								this.set_text(obj, t); // move this up? and fix #483
							}
							this._data.core.focused = tmp.id;
							setTimeout($.proxy(function () {
								var node = this.get_node(tmp.id, true);
								if(node.length) {
									this._data.core.focused = tmp.id;
									node.children('.jstree-anchor').focus();
								}
							}, this), 0);
							if(callback) {
								callback.call(this, tmp, nv, cancel);
							}
							h2 = null;
						}, this),
						"keydown" : function (e) {
							var key = e.which;
							if(key === 27) {
								cancel = true;
								this.value = t;
							}
							if(key === 27 || key === 13 || key === 37 || key === 38 || key === 39 || key === 40 || key === 32) {
								e.stopImmediatePropagation();
							}
							if(key === 27 || key === 13) {
								e.preventDefault();
								this.blur();
							}
						},
						"click" : function (e) { e.stopImmediatePropagation(); },
						"mousedown" : function (e) { e.stopImmediatePropagation(); },
						"keyup" : function (e) {
							h2.width(Math.min(h1.text("pW" + this.value).width(),w));
						},
						"keypress" : function(e) {
							if(e.which === 13) { return false; }
						}
					});
				fn = {
						fontFamily		: a.css('fontFamily')		|| '',
						fontSize		: a.css('fontSize')			|| '',
						fontWeight		: a.css('fontWeight')		|| '',
						fontStyle		: a.css('fontStyle')		|| '',
						fontStretch		: a.css('fontStretch')		|| '',
						fontVariant		: a.css('fontVariant')		|| '',
						letterSpacing	: a.css('letterSpacing')	|| '',
						wordSpacing		: a.css('wordSpacing')		|| ''
				};
			s.attr('class', a.attr('class')).append(a.contents().clone()).append(h2);
			a.replaceWith(s);
			h1.css(fn);
			h2.css(fn).width(Math.min(h1.text("pW" + h2[0].value).width(),w))[0].select();
			$(document).one('mousedown.jstree touchstart.jstree dnd_start.vakata', function (e) {
				if (h2 && e.target !== h2) {
					$(h2).blur();
				}
			});
		},


		/**
		 * changes the theme
		 * @name set_theme(theme_name [, theme_url])
		 * @param {String} theme_name the name of the new theme to apply
		 * @param {mixed} theme_url  the location of the CSS file for this theme. Omit or set to `false` if you manually included the file. Set to `true` to autoload from the `core.themes.dir` directory.
		 * @trigger set_theme.jstree
		 */
		set_theme : function (theme_name, theme_url) {
			if(!theme_name) { return false; }
			if(theme_url === true) {
				var dir = this.settings.core.themes.dir;
				if(!dir) { dir = $.jstree.path + '/themes'; }
				theme_url = dir + '/' + theme_name + '/style.css';
			}
			if(theme_url && $.inArray(theme_url, themes_loaded) === -1) {
				$('head').append('<'+'link rel="stylesheet" href="' + theme_url + '" type="text/css" />');
				themes_loaded.push(theme_url);
			}
			if(this._data.core.themes.name) {
				this.element.removeClass('jstree-' + this._data.core.themes.name);
			}
			this._data.core.themes.name = theme_name;
			this.element.addClass('jstree-' + theme_name);
			this.element[this.settings.core.themes.responsive ? 'addClass' : 'removeClass' ]('jstree-' + theme_name + '-responsive');
			/**
			 * triggered when a theme is set
			 * @event
			 * @name set_theme.jstree
			 * @param {String} theme the new theme
			 */
			this.trigger('set_theme', { 'theme' : theme_name });
		},
		/**
		 * gets the name of the currently applied theme name
		 * @name get_theme()
		 * @return {String}
		 */
		get_theme : function () { return this._data.core.themes.name; },
		/**
		 * changes the theme variant (if the theme has variants)
		 * @name set_theme_variant(variant_name)
		 * @param {String|Boolean} variant_name the variant to apply (if `false` is used the current variant is removed)
		 */
		set_theme_variant : function (variant_name) {
			if(this._data.core.themes.variant) {
				this.element.removeClass('jstree-' + this._data.core.themes.name + '-' + this._data.core.themes.variant);
			}
			this._data.core.themes.variant = variant_name;
			if(variant_name) {
				this.element.addClass('jstree-' + this._data.core.themes.name + '-' + this._data.core.themes.variant);
			}
		},
		/**
		 * gets the name of the currently applied theme variant
		 * @name get_theme()
		 * @return {String}
		 */
		get_theme_variant : function () { return this._data.core.themes.variant; },
		/**
		 * shows a striped background on the container (if the theme supports it)
		 * @name show_stripes()
		 */
		show_stripes : function () {
			this._data.core.themes.stripes = true;
			this.get_container_ul().addClass("jstree-striped");
			/**
			 * triggered when stripes are shown
			 * @event
			 * @name show_stripes.jstree
			 */
			this.trigger('show_stripes');
		},
		/**
		 * hides the striped background on the container
		 * @name hide_stripes()
		 */
		hide_stripes : function () {
			this._data.core.themes.stripes = false;
			this.get_container_ul().removeClass("jstree-striped");
			/**
			 * triggered when stripes are hidden
			 * @event
			 * @name hide_stripes.jstree
			 */
			this.trigger('hide_stripes');
		},
		/**
		 * toggles the striped background on the container
		 * @name toggle_stripes()
		 */
		toggle_stripes : function () { if(this._data.core.themes.stripes) { this.hide_stripes(); } else { this.show_stripes(); } },
		/**
		 * shows the connecting dots (if the theme supports it)
		 * @name show_dots()
		 */
		show_dots : function () {
			this._data.core.themes.dots = true;
			this.get_container_ul().removeClass("jstree-no-dots");
			/**
			 * triggered when dots are shown
			 * @event
			 * @name show_dots.jstree
			 */
			this.trigger('show_dots');
		},
		/**
		 * hides the connecting dots
		 * @name hide_dots()
		 */
		hide_dots : function () {
			this._data.core.themes.dots = false;
			this.get_container_ul().addClass("jstree-no-dots");
			/**
			 * triggered when dots are hidden
			 * @event
			 * @name hide_dots.jstree
			 */
			this.trigger('hide_dots');
		},
		/**
		 * toggles the connecting dots
		 * @name toggle_dots()
		 */
		toggle_dots : function () { if(this._data.core.themes.dots) { this.hide_dots(); } else { this.show_dots(); } },
		/**
		 * show the node icons
		 * @name show_icons()
		 */
		show_icons : function () {
			this._data.core.themes.icons = true;
			this.get_container_ul().removeClass("jstree-no-icons");
			/**
			 * triggered when icons are shown
			 * @event
			 * @name show_icons.jstree
			 */
			this.trigger('show_icons');
		},
		/**
		 * hide the node icons
		 * @name hide_icons()
		 */
		hide_icons : function () {
			this._data.core.themes.icons = false;
			this.get_container_ul().addClass("jstree-no-icons");
			/**
			 * triggered when icons are hidden
			 * @event
			 * @name hide_icons.jstree
			 */
			this.trigger('hide_icons');
		},
		/**
		 * toggle the node icons
		 * @name toggle_icons()
		 */
		toggle_icons : function () { if(this._data.core.themes.icons) { this.hide_icons(); } else { this.show_icons(); } },
		/**
		 * show the node ellipsis
		 * @name show_icons()
		 */
		show_ellipsis : function () {
			this._data.core.themes.ellipsis = true;
			this.get_container_ul().addClass("jstree-ellipsis");
			/**
			 * triggered when ellisis is shown
			 * @event
			 * @name show_ellipsis.jstree
			 */
			this.trigger('show_ellipsis');
		},
		/**
		 * hide the node ellipsis
		 * @name hide_ellipsis()
		 */
		hide_ellipsis : function () {
			this._data.core.themes.ellipsis = false;
			this.get_container_ul().removeClass("jstree-ellipsis");
			/**
			 * triggered when ellisis is hidden
			 * @event
			 * @name hide_ellipsis.jstree
			 */
			this.trigger('hide_ellipsis');
		},
		/**
		 * toggle the node ellipsis
		 * @name toggle_icons()
		 */
		toggle_ellipsis : function () { if(this._data.core.themes.ellipsis) { this.hide_ellipsis(); } else { this.show_ellipsis(); } },
		/**
		 * set the node icon for a node
		 * @name set_icon(obj, icon)
		 * @param {mixed} obj
		 * @param {String} icon the new icon - can be a path to an icon or a className, if using an image that is in the current directory use a `./` prefix, otherwise it will be detected as a class
		 */
		set_icon : function (obj, icon) {
			var t1, t2, dom, old;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.set_icon(obj[t1], icon);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			old = obj.icon;
			obj.icon = icon === true || icon === null || icon === undefined || icon === '' ? true : icon;
			dom = this.get_node(obj, true).children(".jstree-anchor").children(".jstree-themeicon");
			if(icon === false) {
				dom.removeClass('jstree-themeicon-custom ' + old).css("background","").removeAttr("rel");
				this.hide_icon(obj);
			}
			else if(icon === true || icon === null || icon === undefined || icon === '') {
				dom.removeClass('jstree-themeicon-custom ' + old).css("background","").removeAttr("rel");
				if(old === false) { this.show_icon(obj); }
			}
			else if(icon.indexOf("/") === -1 && icon.indexOf(".") === -1) {
				dom.removeClass(old).css("background","");
				dom.addClass(icon + ' jstree-themeicon-custom').attr("rel",icon);
				if(old === false) { this.show_icon(obj); }
			}
			else {
				dom.removeClass(old).css("background","");
				dom.addClass('jstree-themeicon-custom').css("background", "url('" + icon + "') center center no-repeat").attr("rel",icon);
				if(old === false) { this.show_icon(obj); }
			}
			return true;
		},
		/**
		 * get the node icon for a node
		 * @name get_icon(obj)
		 * @param {mixed} obj
		 * @return {String}
		 */
		get_icon : function (obj) {
			obj = this.get_node(obj);
			return (!obj || obj.id === $.jstree.root) ? false : obj.icon;
		},
		/**
		 * hide the icon on an individual node
		 * @name hide_icon(obj)
		 * @param {mixed} obj
		 */
		hide_icon : function (obj) {
			var t1, t2;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.hide_icon(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj === $.jstree.root) { return false; }
			obj.icon = false;
			this.get_node(obj, true).children(".jstree-anchor").children(".jstree-themeicon").addClass('jstree-themeicon-hidden');
			return true;
		},
		/**
		 * show the icon on an individual node
		 * @name show_icon(obj)
		 * @param {mixed} obj
		 */
		show_icon : function (obj) {
			var t1, t2, dom;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.show_icon(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj === $.jstree.root) { return false; }
			dom = this.get_node(obj, true);
			obj.icon = dom.length ? dom.children(".jstree-anchor").children(".jstree-themeicon").attr('rel') : true;
			if(!obj.icon) { obj.icon = true; }
			dom.children(".jstree-anchor").children(".jstree-themeicon").removeClass('jstree-themeicon-hidden');
			return true;
		}
	};

	// helpers
	$.vakata = {};
	// collect attributes
	$.vakata.attributes = function(node, with_values) {
		node = $(node)[0];
		var attr = with_values ? {} : [];
		if(node && node.attributes) {
			$.each(node.attributes, function (i, v) {
				if($.inArray(v.name.toLowerCase(),['style','contenteditable','hasfocus','tabindex']) !== -1) { return; }
				if(v.value !== null && $.trim(v.value) !== '') {
					if(with_values) { attr[v.name] = v.value; }
					else { attr.push(v.name); }
				}
			});
		}
		return attr;
	};
	$.vakata.array_unique = function(array) {
		var a = [], i, j, l, o = {};
		for(i = 0, l = array.length; i < l; i++) {
			if(o[array[i]] === undefined) {
				a.push(array[i]);
				o[array[i]] = true;
			}
		}
		return a;
	};
	// remove item from array
	$.vakata.array_remove = function(array, from) {
		array.splice(from, 1);
		return array;
		//var rest = array.slice((to || from) + 1 || array.length);
		//array.length = from < 0 ? array.length + from : from;
		//array.push.apply(array, rest);
		//return array;
	};
	// remove item from array
	$.vakata.array_remove_item = function(array, item) {
		var tmp = $.inArray(item, array);
		return tmp !== -1 ? $.vakata.array_remove(array, tmp) : array;
	};
	$.vakata.array_filter = function(c,a,b,d,e) {
		if (c.filter) {
			return c.filter(a, b);
		}
		d=[];
		for (e in c) {
			if (~~e+''===e+'' && e>=0 && a.call(b,c[e],+e,c)) {
				d.push(c[e]);
			}
		}
		return d;
	};


/**
 * ### Changed plugin
 *
 * This plugin adds more information to the `changed.jstree` event. The new data is contained in the `changed` event data property, and contains a lists of `selected` and `deselected` nodes.
 */

	$.jstree.plugins.changed = function (options, parent) {
		var last = [];
		this.trigger = function (ev, data) {
			var i, j;
			if(!data) {
				data = {};
			}
			if(ev.replace('.jstree','') === 'changed') {
				data.changed = { selected : [], deselected : [] };
				var tmp = {};
				for(i = 0, j = last.length; i < j; i++) {
					tmp[last[i]] = 1;
				}
				for(i = 0, j = data.selected.length; i < j; i++) {
					if(!tmp[data.selected[i]]) {
						data.changed.selected.push(data.selected[i]);
					}
					else {
						tmp[data.selected[i]] = 2;
					}
				}
				for(i = 0, j = last.length; i < j; i++) {
					if(tmp[last[i]] === 1) {
						data.changed.deselected.push(last[i]);
					}
				}
				last = data.selected.slice();
			}
			/**
			 * triggered when selection changes (the "changed" plugin enhances the original event with more data)
			 * @event
			 * @name changed.jstree
			 * @param {Object} node
			 * @param {Object} action the action that caused the selection to change
			 * @param {Array} selected the current selection
			 * @param {Object} changed an object containing two properties `selected` and `deselected` - both arrays of node IDs, which were selected or deselected since the last changed event
			 * @param {Object} event the event (if any) that triggered this changed event
			 * @plugin changed
			 */
			parent.trigger.call(this, ev, data);
		};
		this.refresh = function (skip_loading, forget_state) {
			last = [];
			return parent.refresh.apply(this, arguments);
		};
	};

/**
 * ### Checkbox plugin
 *
 * This plugin renders checkbox icons in front of each node, making multiple selection much easier.
 * It also supports tri-state behavior, meaning that if a node has a few of its children checked it will be rendered as undetermined, and state will be propagated up.
 */

	var _i = document.createElement('I');
	_i.className = 'jstree-icon jstree-checkbox';
	_i.setAttribute('role', 'presentation');
	/**
	 * stores all defaults for the checkbox plugin
	 * @name $.jstree.defaults.checkbox
	 * @plugin checkbox
	 */
	$.jstree.defaults.checkbox = {
		/**
		 * a boolean indicating if checkboxes should be visible (can be changed at a later time using `show_checkboxes()` and `hide_checkboxes`). Defaults to `true`.
		 * @name $.jstree.defaults.checkbox.visible
		 * @plugin checkbox
		 */
		visible				: true,
		/**
		 * a boolean indicating if checkboxes should cascade down and have an undetermined state. Defaults to `true`.
		 * @name $.jstree.defaults.checkbox.three_state
		 * @plugin checkbox
		 */
		three_state			: true,
		/**
		 * a boolean indicating if clicking anywhere on the node should act as clicking on the checkbox. Defaults to `true`.
		 * @name $.jstree.defaults.checkbox.whole_node
		 * @plugin checkbox
		 */
		whole_node			: true,
		/**
		 * a boolean indicating if the selected style of a node should be kept, or removed. Defaults to `true`.
		 * @name $.jstree.defaults.checkbox.keep_selected_style
		 * @plugin checkbox
		 */
		keep_selected_style	: true,
		/**
		 * This setting controls how cascading and undetermined nodes are applied.
		 * If 'up' is in the string - cascading up is enabled, if 'down' is in the string - cascading down is enabled, if 'undetermined' is in the string - undetermined nodes will be used.
		 * If `three_state` is set to `true` this setting is automatically set to 'up+down+undetermined'. Defaults to ''.
		 * @name $.jstree.defaults.checkbox.cascade
		 * @plugin checkbox
		 */
		cascade				: '',
		/**
		 * This setting controls if checkbox are bound to the general tree selection or to an internal array maintained by the checkbox plugin. Defaults to `true`, only set to `false` if you know exactly what you are doing.
		 * @name $.jstree.defaults.checkbox.tie_selection
		 * @plugin checkbox
		 */
		tie_selection		: true,

		/**
		 * This setting controls if cascading down affects disabled checkboxes
		 * @name $.jstree.defaults.checkbox.cascade_to_disabled
		 * @plugin checkbox
		 */
		cascade_to_disabled : true,

		/**
		 * This setting controls if cascading down affects hidden checkboxes
		 * @name $.jstree.defaults.checkbox.cascade_to_hidden
		 * @plugin checkbox
		 */
		cascade_to_hidden : true
	};
	$.jstree.plugins.checkbox = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);
			this._data.checkbox.uto = false;
			this._data.checkbox.selected = [];
			if(this.settings.checkbox.three_state) {
				this.settings.checkbox.cascade = 'up+down+undetermined';
			}
			this.element
				.on("init.jstree", $.proxy(function () {
						this._data.checkbox.visible = this.settings.checkbox.visible;
						if(!this.settings.checkbox.keep_selected_style) {
							this.element.addClass('jstree-checkbox-no-clicked');
						}
						if(this.settings.checkbox.tie_selection) {
							this.element.addClass('jstree-checkbox-selection');
						}
					}, this))
				.on("loading.jstree", $.proxy(function () {
						this[ this._data.checkbox.visible ? 'show_checkboxes' : 'hide_checkboxes' ]();
					}, this));
			if(this.settings.checkbox.cascade.indexOf('undetermined') !== -1) {
				this.element
					.on('changed.jstree uncheck_node.jstree check_node.jstree uncheck_all.jstree check_all.jstree move_node.jstree copy_node.jstree redraw.jstree open_node.jstree', $.proxy(function () {
							// only if undetermined is in setting
							if(this._data.checkbox.uto) { clearTimeout(this._data.checkbox.uto); }
							this._data.checkbox.uto = setTimeout($.proxy(this._undetermined, this), 50);
						}, this));
			}
			if(!this.settings.checkbox.tie_selection) {
				this.element
					.on('model.jstree', $.proxy(function (e, data) {
						var m = this._model.data,
							p = m[data.parent],
							dpc = data.nodes,
							i, j;
						for(i = 0, j = dpc.length; i < j; i++) {
							m[dpc[i]].state.checked = m[dpc[i]].state.checked || (m[dpc[i]].original && m[dpc[i]].original.state && m[dpc[i]].original.state.checked);
							if(m[dpc[i]].state.checked) {
								this._data.checkbox.selected.push(dpc[i]);
							}
						}
					}, this));
			}
			if(this.settings.checkbox.cascade.indexOf('up') !== -1 || this.settings.checkbox.cascade.indexOf('down') !== -1) {
				this.element
					.on('model.jstree', $.proxy(function (e, data) {
							var m = this._model.data,
								p = m[data.parent],
								dpc = data.nodes,
								chd = [],
								c, i, j, k, l, tmp, s = this.settings.checkbox.cascade, t = this.settings.checkbox.tie_selection;

							if(s.indexOf('down') !== -1) {
								// apply down
								if(p.state[ t ? 'selected' : 'checked' ]) {
									for(i = 0, j = dpc.length; i < j; i++) {
										m[dpc[i]].state[ t ? 'selected' : 'checked' ] = true;
									}

									this._data[ t ? 'core' : 'checkbox' ].selected = this._data[ t ? 'core' : 'checkbox' ].selected.concat(dpc);
								}
								else {
									for(i = 0, j = dpc.length; i < j; i++) {
										if(m[dpc[i]].state[ t ? 'selected' : 'checked' ]) {
											for(k = 0, l = m[dpc[i]].children_d.length; k < l; k++) {
												m[m[dpc[i]].children_d[k]].state[ t ? 'selected' : 'checked' ] = true;
											}
											this._data[ t ? 'core' : 'checkbox' ].selected = this._data[ t ? 'core' : 'checkbox' ].selected.concat(m[dpc[i]].children_d);
										}
									}
								}
							}

							if(s.indexOf('up') !== -1) {
								// apply up
								for(i = 0, j = p.children_d.length; i < j; i++) {
									if(!m[p.children_d[i]].children.length) {
										chd.push(m[p.children_d[i]].parent);
									}
								}
								chd = $.vakata.array_unique(chd);
								for(k = 0, l = chd.length; k < l; k++) {
									p = m[chd[k]];
									while(p && p.id !== $.jstree.root) {
										c = 0;
										for(i = 0, j = p.children.length; i < j; i++) {
											c += m[p.children[i]].state[ t ? 'selected' : 'checked' ];
										}
										if(c === j) {
											p.state[ t ? 'selected' : 'checked' ] = true;
											this._data[ t ? 'core' : 'checkbox' ].selected.push(p.id);
											tmp = this.get_node(p, true);
											if(tmp && tmp.length) {
												tmp.attr('aria-selected', true).children('.jstree-anchor').addClass( t ? 'jstree-clicked' : 'jstree-checked');
											}
										}
										else {
											break;
										}
										p = this.get_node(p.parent);
									}
								}
							}

							this._data[ t ? 'core' : 'checkbox' ].selected = $.vakata.array_unique(this._data[ t ? 'core' : 'checkbox' ].selected);
						}, this))
					.on(this.settings.checkbox.tie_selection ? 'select_node.jstree' : 'check_node.jstree', $.proxy(function (e, data) {
							var self = this,
								obj = data.node,
								m = this._model.data,
								par = this.get_node(obj.parent),
								i, j, c, tmp, s = this.settings.checkbox.cascade, t = this.settings.checkbox.tie_selection,
								sel = {}, cur = this._data[ t ? 'core' : 'checkbox' ].selected;

							for (i = 0, j = cur.length; i < j; i++) {
								sel[cur[i]] = true;
							}

							// apply down
							if(s.indexOf('down') !== -1) {
								//this._data[ t ? 'core' : 'checkbox' ].selected = $.vakata.array_unique(this._data[ t ? 'core' : 'checkbox' ].selected.concat(obj.children_d));
								var selectedIds = this._cascade_new_checked_state(obj.id, true);
								var temp = obj.children_d.concat(obj.id);
								for (i = 0, j = temp.length; i < j; i++) {
									if (selectedIds.indexOf(temp[i]) > -1) {
										sel[temp[i]] = true;
									}
									else {
										delete sel[temp[i]];
									}
								}
							}

							// apply up
							if(s.indexOf('up') !== -1) {
								while(par && par.id !== $.jstree.root) {
									c = 0;
									for(i = 0, j = par.children.length; i < j; i++) {
										c += m[par.children[i]].state[ t ? 'selected' : 'checked' ];
									}
									if(c === j) {
										par.state[ t ? 'selected' : 'checked' ] = true;
										sel[par.id] = true;
										//this._data[ t ? 'core' : 'checkbox' ].selected.push(par.id);
										tmp = this.get_node(par, true);
										if(tmp && tmp.length) {
											tmp.attr('aria-selected', true).children('.jstree-anchor').addClass(t ? 'jstree-clicked' : 'jstree-checked');
										}
									}
									else {
										break;
									}
									par = this.get_node(par.parent);
								}
							}

							cur = [];
							for (i in sel) {
								if (sel.hasOwnProperty(i)) {
									cur.push(i);
								}
							}
							this._data[ t ? 'core' : 'checkbox' ].selected = cur;
						}, this))
					.on(this.settings.checkbox.tie_selection ? 'deselect_all.jstree' : 'uncheck_all.jstree', $.proxy(function (e, data) {
							var obj = this.get_node($.jstree.root),
								m = this._model.data,
								i, j, tmp;
							for(i = 0, j = obj.children_d.length; i < j; i++) {
								tmp = m[obj.children_d[i]];
								if(tmp && tmp.original && tmp.original.state && tmp.original.state.undetermined) {
									tmp.original.state.undetermined = false;
								}
							}
						}, this))
					.on(this.settings.checkbox.tie_selection ? 'deselect_node.jstree' : 'uncheck_node.jstree', $.proxy(function (e, data) {
							var self = this,
								obj = data.node,
								dom = this.get_node(obj, true),
								i, j, tmp, s = this.settings.checkbox.cascade, t = this.settings.checkbox.tie_selection,
								cur = this._data[ t ? 'core' : 'checkbox' ].selected, sel = {},
								stillSelectedIds = [],
								allIds = obj.children_d.concat(obj.id);

							// apply down
							if(s.indexOf('down') !== -1) {
								var selectedIds = this._cascade_new_checked_state(obj.id, false);

								cur = $.vakata.array_filter(cur, function(id) {
									return allIds.indexOf(id) === -1 || selectedIds.indexOf(id) > -1;
								});
							}

							// only apply up if cascade up is enabled and if this node is not selected
							// (if all child nodes are disabled and cascade_to_disabled === false then this node will till be selected).
							if(s.indexOf('up') !== -1 && cur.indexOf(obj.id) === -1) {
								for(i = 0, j = obj.parents.length; i < j; i++) {
									tmp = this._model.data[obj.parents[i]];
									tmp.state[ t ? 'selected' : 'checked' ] = false;
									if(tmp && tmp.original && tmp.original.state && tmp.original.state.undetermined) {
										tmp.original.state.undetermined = false;
									}
									tmp = this.get_node(obj.parents[i], true);
									if(tmp && tmp.length) {
										tmp.attr('aria-selected', false).children('.jstree-anchor').removeClass(t ? 'jstree-clicked' : 'jstree-checked');
									}
								}

								cur = $.vakata.array_filter(cur, function(id) {
									return obj.parents.indexOf(id) === -1;
								});
							}

							this._data[ t ? 'core' : 'checkbox' ].selected = cur;
						}, this));
			}
			if(this.settings.checkbox.cascade.indexOf('up') !== -1) {
				this.element
					.on('delete_node.jstree', $.proxy(function (e, data) {
							// apply up (whole handler)
							var p = this.get_node(data.parent),
								m = this._model.data,
								i, j, c, tmp, t = this.settings.checkbox.tie_selection;
							while(p && p.id !== $.jstree.root && !p.state[ t ? 'selected' : 'checked' ]) {
								c = 0;
								for(i = 0, j = p.children.length; i < j; i++) {
									c += m[p.children[i]].state[ t ? 'selected' : 'checked' ];
								}
								if(j > 0 && c === j) {
									p.state[ t ? 'selected' : 'checked' ] = true;
									this._data[ t ? 'core' : 'checkbox' ].selected.push(p.id);
									tmp = this.get_node(p, true);
									if(tmp && tmp.length) {
										tmp.attr('aria-selected', true).children('.jstree-anchor').addClass(t ? 'jstree-clicked' : 'jstree-checked');
									}
								}
								else {
									break;
								}
								p = this.get_node(p.parent);
							}
						}, this))
					.on('move_node.jstree', $.proxy(function (e, data) {
							// apply up (whole handler)
							var is_multi = data.is_multi,
								old_par = data.old_parent,
								new_par = this.get_node(data.parent),
								m = this._model.data,
								p, c, i, j, tmp, t = this.settings.checkbox.tie_selection;
							if(!is_multi) {
								p = this.get_node(old_par);
								while(p && p.id !== $.jstree.root && !p.state[ t ? 'selected' : 'checked' ]) {
									c = 0;
									for(i = 0, j = p.children.length; i < j; i++) {
										c += m[p.children[i]].state[ t ? 'selected' : 'checked' ];
									}
									if(j > 0 && c === j) {
										p.state[ t ? 'selected' : 'checked' ] = true;
										this._data[ t ? 'core' : 'checkbox' ].selected.push(p.id);
										tmp = this.get_node(p, true);
										if(tmp && tmp.length) {
											tmp.attr('aria-selected', true).children('.jstree-anchor').addClass(t ? 'jstree-clicked' : 'jstree-checked');
										}
									}
									else {
										break;
									}
									p = this.get_node(p.parent);
								}
							}
							p = new_par;
							while(p && p.id !== $.jstree.root) {
								c = 0;
								for(i = 0, j = p.children.length; i < j; i++) {
									c += m[p.children[i]].state[ t ? 'selected' : 'checked' ];
								}
								if(c === j) {
									if(!p.state[ t ? 'selected' : 'checked' ]) {
										p.state[ t ? 'selected' : 'checked' ] = true;
										this._data[ t ? 'core' : 'checkbox' ].selected.push(p.id);
										tmp = this.get_node(p, true);
										if(tmp && tmp.length) {
											tmp.attr('aria-selected', true).children('.jstree-anchor').addClass(t ? 'jstree-clicked' : 'jstree-checked');
										}
									}
								}
								else {
									if(p.state[ t ? 'selected' : 'checked' ]) {
										p.state[ t ? 'selected' : 'checked' ] = false;
										this._data[ t ? 'core' : 'checkbox' ].selected = $.vakata.array_remove_item(this._data[ t ? 'core' : 'checkbox' ].selected, p.id);
										tmp = this.get_node(p, true);
										if(tmp && tmp.length) {
											tmp.attr('aria-selected', false).children('.jstree-anchor').removeClass(t ? 'jstree-clicked' : 'jstree-checked');
										}
									}
									else {
										break;
									}
								}
								p = this.get_node(p.parent);
							}
						}, this));
			}
		};
		/**
		 * get an array of all nodes whose state is "undetermined"
		 * @name get_undetermined([full])
		 * @param  {boolean} full: if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 * @plugin checkbox
		 */
		this.get_undetermined = function (full) {
			if (this.settings.checkbox.cascade.indexOf('undetermined') === -1) {
				return [];
			}
			var i, j, k, l, o = {}, m = this._model.data, t = this.settings.checkbox.tie_selection, s = this._data[ t ? 'core' : 'checkbox' ].selected, p = [], tt = this, r = [];
			for(i = 0, j = s.length; i < j; i++) {
				if(m[s[i]] && m[s[i]].parents) {
					for(k = 0, l = m[s[i]].parents.length; k < l; k++) {
						if(o[m[s[i]].parents[k]] !== undefined) {
							break;
						}
						if(m[s[i]].parents[k] !== $.jstree.root) {
							o[m[s[i]].parents[k]] = true;
							p.push(m[s[i]].parents[k]);
						}
					}
				}
			}
			// attempt for server side undetermined state
			this.element.find('.jstree-closed').not(':has(.jstree-children)')
				.each(function () {
					var tmp = tt.get_node(this), tmp2;
					
					if(!tmp) { return; }
					
					if(!tmp.state.loaded) {
						if(tmp.original && tmp.original.state && tmp.original.state.undetermined && tmp.original.state.undetermined === true) {
							if(o[tmp.id] === undefined && tmp.id !== $.jstree.root) {
								o[tmp.id] = true;
								p.push(tmp.id);
							}
							for(k = 0, l = tmp.parents.length; k < l; k++) {
								if(o[tmp.parents[k]] === undefined && tmp.parents[k] !== $.jstree.root) {
									o[tmp.parents[k]] = true;
									p.push(tmp.parents[k]);
								}
							}
						}
					}
					else {
						for(i = 0, j = tmp.children_d.length; i < j; i++) {
							tmp2 = m[tmp.children_d[i]];
							if(!tmp2.state.loaded && tmp2.original && tmp2.original.state && tmp2.original.state.undetermined && tmp2.original.state.undetermined === true) {
								if(o[tmp2.id] === undefined && tmp2.id !== $.jstree.root) {
									o[tmp2.id] = true;
									p.push(tmp2.id);
								}
								for(k = 0, l = tmp2.parents.length; k < l; k++) {
									if(o[tmp2.parents[k]] === undefined && tmp2.parents[k] !== $.jstree.root) {
										o[tmp2.parents[k]] = true;
										p.push(tmp2.parents[k]);
									}
								}
							}
						}
					}
				});
			for (i = 0, j = p.length; i < j; i++) {
				if(!m[p[i]].state[ t ? 'selected' : 'checked' ]) {
					r.push(full ? m[p[i]] : p[i]);
				}
			}
			return r;
		};
		/**
		 * set the undetermined state where and if necessary. Used internally.
		 * @private
		 * @name _undetermined()
		 * @plugin checkbox
		 */
		this._undetermined = function () {
			if(this.element === null) { return; }
			var p = this.get_undetermined(false), i, j, s;

			this.element.find('.jstree-undetermined').removeClass('jstree-undetermined');
			for (i = 0, j = p.length; i < j; i++) {
				s = this.get_node(p[i], true);
				if(s && s.length) {
					s.children('.jstree-anchor').children('.jstree-checkbox').addClass('jstree-undetermined');
				}
			}
		};
		this.redraw_node = function(obj, deep, is_callback, force_render) {
			obj = parent.redraw_node.apply(this, arguments);
			if(obj) {
				var i, j, tmp = null, icon = null;
				for(i = 0, j = obj.childNodes.length; i < j; i++) {
					if(obj.childNodes[i] && obj.childNodes[i].className && obj.childNodes[i].className.indexOf("jstree-anchor") !== -1) {
						tmp = obj.childNodes[i];
						break;
					}
				}
				if(tmp) {
					if(!this.settings.checkbox.tie_selection && this._model.data[obj.id].state.checked) { tmp.className += ' jstree-checked'; }
					icon = _i.cloneNode(false);
					if(this._model.data[obj.id].state.checkbox_disabled) { icon.className += ' jstree-checkbox-disabled'; }
					tmp.insertBefore(icon, tmp.childNodes[0]);
				}
			}
			if(!is_callback && this.settings.checkbox.cascade.indexOf('undetermined') !== -1) {
				if(this._data.checkbox.uto) { clearTimeout(this._data.checkbox.uto); }
				this._data.checkbox.uto = setTimeout($.proxy(this._undetermined, this), 50);
			}
			return obj;
		};
		/**
		 * show the node checkbox icons
		 * @name show_checkboxes()
		 * @plugin checkbox
		 */
		this.show_checkboxes = function () { this._data.core.themes.checkboxes = true; this.get_container_ul().removeClass("jstree-no-checkboxes"); };
		/**
		 * hide the node checkbox icons
		 * @name hide_checkboxes()
		 * @plugin checkbox
		 */
		this.hide_checkboxes = function () { this._data.core.themes.checkboxes = false; this.get_container_ul().addClass("jstree-no-checkboxes"); };
		/**
		 * toggle the node icons
		 * @name toggle_checkboxes()
		 * @plugin checkbox
		 */
		this.toggle_checkboxes = function () { if(this._data.core.themes.checkboxes) { this.hide_checkboxes(); } else { this.show_checkboxes(); } };
		/**
		 * checks if a node is in an undetermined state
		 * @name is_undetermined(obj)
		 * @param  {mixed} obj
		 * @return {Boolean}
		 */
		this.is_undetermined = function (obj) {
			obj = this.get_node(obj);
			var s = this.settings.checkbox.cascade, i, j, t = this.settings.checkbox.tie_selection, d = this._data[ t ? 'core' : 'checkbox' ].selected, m = this._model.data;
			if(!obj || obj.state[ t ? 'selected' : 'checked' ] === true || s.indexOf('undetermined') === -1 || (s.indexOf('down') === -1 && s.indexOf('up') === -1)) {
				return false;
			}
			if(!obj.state.loaded && obj.original.state.undetermined === true) {
				return true;
			}
			for(i = 0, j = obj.children_d.length; i < j; i++) {
				if($.inArray(obj.children_d[i], d) !== -1 || (!m[obj.children_d[i]].state.loaded && m[obj.children_d[i]].original.state.undetermined)) {
					return true;
				}
			}
			return false;
		};
		/**
		 * disable a node's checkbox
		 * @name disable_checkbox(obj)
		 * @param {mixed} obj an array can be used too
		 * @trigger disable_checkbox.jstree
		 * @plugin checkbox
		 */
		this.disable_checkbox = function (obj) {
			var t1, t2, dom;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.disable_checkbox(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			dom = this.get_node(obj, true);
			if(!obj.state.checkbox_disabled) {
				obj.state.checkbox_disabled = true;
				if(dom && dom.length) {
					dom.children('.jstree-anchor').children('.jstree-checkbox').addClass('jstree-checkbox-disabled');
				}
				/**
				 * triggered when an node's checkbox is disabled
				 * @event
				 * @name disable_checkbox.jstree
				 * @param {Object} node
				 * @plugin checkbox
				 */
				this.trigger('disable_checkbox', { 'node' : obj });
			}
		};
		/**
		 * enable a node's checkbox
		 * @name enable_checkbox(obj)
		 * @param {mixed} obj an array can be used too
		 * @trigger enable_checkbox.jstree
		 * @plugin checkbox
		 */
		this.enable_checkbox = function (obj) {
			var t1, t2, dom;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.enable_checkbox(obj[t1]);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			dom = this.get_node(obj, true);
			if(obj.state.checkbox_disabled) {
				obj.state.checkbox_disabled = false;
				if(dom && dom.length) {
					dom.children('.jstree-anchor').children('.jstree-checkbox').removeClass('jstree-checkbox-disabled');
				}
				/**
				 * triggered when an node's checkbox is enabled
				 * @event
				 * @name enable_checkbox.jstree
				 * @param {Object} node
				 * @plugin checkbox
				 */
				this.trigger('enable_checkbox', { 'node' : obj });
			}
		};

		this.activate_node = function (obj, e) {
			if($(e.target).hasClass('jstree-checkbox-disabled')) {
				return false;
			}
			if(this.settings.checkbox.tie_selection && (this.settings.checkbox.whole_node || $(e.target).hasClass('jstree-checkbox'))) {
				e.ctrlKey = true;
			}
			if(this.settings.checkbox.tie_selection || (!this.settings.checkbox.whole_node && !$(e.target).hasClass('jstree-checkbox'))) {
				return parent.activate_node.call(this, obj, e);
			}
			if(this.is_disabled(obj)) {
				return false;
			}
			if(this.is_checked(obj)) {
				this.uncheck_node(obj, e);
			}
			else {
				this.check_node(obj, e);
			}
			this.trigger('activate_node', { 'node' : this.get_node(obj) });
		};

		/**
		 * Cascades checked state to a node and all its descendants. This function does NOT affect hidden and disabled nodes (or their descendants).
		 * However if these unaffected nodes are already selected their ids will be included in the returned array.
		 * @private
		 * @param {string} id the node ID
		 * @param {bool} checkedState should the nodes be checked or not
		 * @returns {Array} Array of all node id's (in this tree branch) that are checked.
		 */
		this._cascade_new_checked_state = function (id, checkedState) {
			var self = this;
			var t = this.settings.checkbox.tie_selection;
			var node = this._model.data[id];
			var selectedNodeIds = [];
			var selectedChildrenIds = [], i, j, selectedChildIds;

			if (
				(this.settings.checkbox.cascade_to_disabled || !node.state.disabled) &&
				(this.settings.checkbox.cascade_to_hidden || !node.state.hidden)
			) {
				//First try and check/uncheck the children
				if (node.children) {
					for (i = 0, j = node.children.length; i < j; i++) {
						var childId = node.children[i];
						selectedChildIds = self._cascade_new_checked_state(childId, checkedState);
						selectedNodeIds = selectedNodeIds.concat(selectedChildIds);
						if (selectedChildIds.indexOf(childId) > -1) {
							selectedChildrenIds.push(childId);
						}
					}
				}

				var dom = self.get_node(node, true);

				//A node's state is undetermined if some but not all of it's children are checked/selected .
				var undetermined = selectedChildrenIds.length > 0 && selectedChildrenIds.length < node.children.length;

				if(node.original && node.original.state && node.original.state.undetermined) {
					node.original.state.undetermined = undetermined;
				}

				//If a node is undetermined then remove selected class
				if (undetermined) {
					node.state[ t ? 'selected' : 'checked' ] = false;
					dom.attr('aria-selected', false).children('.jstree-anchor').removeClass(t ? 'jstree-clicked' : 'jstree-checked');
				}
				//Otherwise, if the checkedState === true (i.e. the node is being checked now) and all of the node's children are checked (if it has any children),
				//check the node and style it correctly.
				else if (checkedState && selectedChildrenIds.length === node.children.length) {
					node.state[ t ? 'selected' : 'checked' ] = checkedState;
					selectedNodeIds.push(node.id);

					dom.attr('aria-selected', true).children('.jstree-anchor').addClass(t ? 'jstree-clicked' : 'jstree-checked');
				}
				else {
					node.state[ t ? 'selected' : 'checked' ] = false;
					dom.attr('aria-selected', false).children('.jstree-anchor').removeClass(t ? 'jstree-clicked' : 'jstree-checked');
				}
			}
			else {
				selectedChildIds = this.get_checked_descendants(id);

				if (node.state[ t ? 'selected' : 'checked' ]) {
					selectedChildIds.push(node.id);
				}

				selectedNodeIds = selectedNodeIds.concat(selectedChildIds);
			}

			return selectedNodeIds;
		};

		/**
		 * Gets ids of nodes selected in branch (of tree) specified by id (does not include the node specified by id)
		 * @name get_checked_descendants(obj)
		 * @param {string} id the node ID
		 * @return {Array} array of IDs
		 * @plugin checkbox
		 */
		this.get_checked_descendants = function (id) {
			var self = this;
			var t = self.settings.checkbox.tie_selection;
			var node = self._model.data[id];

			return $.vakata.array_filter(node.children_d, function(_id) {
				return self._model.data[_id].state[ t ? 'selected' : 'checked' ];
			});
		};

		/**
		 * check a node (only if tie_selection in checkbox settings is false, otherwise select_node will be called internally)
		 * @name check_node(obj)
		 * @param {mixed} obj an array can be used to check multiple nodes
		 * @trigger check_node.jstree
		 * @plugin checkbox
		 */
		this.check_node = function (obj, e) {
			if(this.settings.checkbox.tie_selection) { return this.select_node(obj, false, true, e); }
			var dom, t1, t2, th;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.check_node(obj[t1], e);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			dom = this.get_node(obj, true);
			if(!obj.state.checked) {
				obj.state.checked = true;
				this._data.checkbox.selected.push(obj.id);
				if(dom && dom.length) {
					dom.children('.jstree-anchor').addClass('jstree-checked');
				}
				/**
				 * triggered when an node is checked (only if tie_selection in checkbox settings is false)
				 * @event
				 * @name check_node.jstree
				 * @param {Object} node
				 * @param {Array} selected the current selection
				 * @param {Object} event the event (if any) that triggered this check_node
				 * @plugin checkbox
				 */
				this.trigger('check_node', { 'node' : obj, 'selected' : this._data.checkbox.selected, 'event' : e });
			}
		};
		/**
		 * uncheck a node (only if tie_selection in checkbox settings is false, otherwise deselect_node will be called internally)
		 * @name uncheck_node(obj)
		 * @param {mixed} obj an array can be used to uncheck multiple nodes
		 * @trigger uncheck_node.jstree
		 * @plugin checkbox
		 */
		this.uncheck_node = function (obj, e) {
			if(this.settings.checkbox.tie_selection) { return this.deselect_node(obj, false, e); }
			var t1, t2, dom;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.uncheck_node(obj[t1], e);
				}
				return true;
			}
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) {
				return false;
			}
			dom = this.get_node(obj, true);
			if(obj.state.checked) {
				obj.state.checked = false;
				this._data.checkbox.selected = $.vakata.array_remove_item(this._data.checkbox.selected, obj.id);
				if(dom.length) {
					dom.children('.jstree-anchor').removeClass('jstree-checked');
				}
				/**
				 * triggered when an node is unchecked (only if tie_selection in checkbox settings is false)
				 * @event
				 * @name uncheck_node.jstree
				 * @param {Object} node
				 * @param {Array} selected the current selection
				 * @param {Object} event the event (if any) that triggered this uncheck_node
				 * @plugin checkbox
				 */
				this.trigger('uncheck_node', { 'node' : obj, 'selected' : this._data.checkbox.selected, 'event' : e });
			}
		};
		
		/**
		 * checks all nodes in the tree (only if tie_selection in checkbox settings is false, otherwise select_all will be called internally)
		 * @name check_all()
		 * @trigger check_all.jstree, changed.jstree
		 * @plugin checkbox
		 */
		this.check_all = function () {
			if(this.settings.checkbox.tie_selection) { return this.select_all(); }
			var tmp = this._data.checkbox.selected.concat([]), i, j;
			this._data.checkbox.selected = this._model.data[$.jstree.root].children_d.concat();
			for(i = 0, j = this._data.checkbox.selected.length; i < j; i++) {
				if(this._model.data[this._data.checkbox.selected[i]]) {
					this._model.data[this._data.checkbox.selected[i]].state.checked = true;
				}
			}
			this.redraw(true);
			/**
			 * triggered when all nodes are checked (only if tie_selection in checkbox settings is false)
			 * @event
			 * @name check_all.jstree
			 * @param {Array} selected the current selection
			 * @plugin checkbox
			 */
			this.trigger('check_all', { 'selected' : this._data.checkbox.selected });
		};
		/**
		 * uncheck all checked nodes (only if tie_selection in checkbox settings is false, otherwise deselect_all will be called internally)
		 * @name uncheck_all()
		 * @trigger uncheck_all.jstree
		 * @plugin checkbox
		 */
		this.uncheck_all = function () {
			if(this.settings.checkbox.tie_selection) { return this.deselect_all(); }
			var tmp = this._data.checkbox.selected.concat([]), i, j;
			for(i = 0, j = this._data.checkbox.selected.length; i < j; i++) {
				if(this._model.data[this._data.checkbox.selected[i]]) {
					this._model.data[this._data.checkbox.selected[i]].state.checked = false;
				}
			}
			this._data.checkbox.selected = [];
			this.element.find('.jstree-checked').removeClass('jstree-checked');
			/**
			 * triggered when all nodes are unchecked (only if tie_selection in checkbox settings is false)
			 * @event
			 * @name uncheck_all.jstree
			 * @param {Object} node the previous selection
			 * @param {Array} selected the current selection
			 * @plugin checkbox
			 */
			this.trigger('uncheck_all', { 'selected' : this._data.checkbox.selected, 'node' : tmp });
		};
		/**
		 * checks if a node is checked (if tie_selection is on in the settings this function will return the same as is_selected)
		 * @name is_checked(obj)
		 * @param  {mixed}  obj
		 * @return {Boolean}
		 * @plugin checkbox
		 */
		this.is_checked = function (obj) {
			if(this.settings.checkbox.tie_selection) { return this.is_selected(obj); }
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			return obj.state.checked;
		};
		/**
		 * get an array of all checked nodes (if tie_selection is on in the settings this function will return the same as get_selected)
		 * @name get_checked([full])
		 * @param  {mixed}  full if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 * @plugin checkbox
		 */
		this.get_checked = function (full) {
			if(this.settings.checkbox.tie_selection) { return this.get_selected(full); }
			return full ? $.map(this._data.checkbox.selected, $.proxy(function (i) { return this.get_node(i); }, this)) : this._data.checkbox.selected.slice();
		};
		/**
		 * get an array of all top level checked nodes (ignoring children of checked nodes) (if tie_selection is on in the settings this function will return the same as get_top_selected)
		 * @name get_top_checked([full])
		 * @param  {mixed}  full if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 * @plugin checkbox
		 */
		this.get_top_checked = function (full) {
			if(this.settings.checkbox.tie_selection) { return this.get_top_selected(full); }
			var tmp = this.get_checked(true),
				obj = {}, i, j, k, l;
			for(i = 0, j = tmp.length; i < j; i++) {
				obj[tmp[i].id] = tmp[i];
			}
			for(i = 0, j = tmp.length; i < j; i++) {
				for(k = 0, l = tmp[i].children_d.length; k < l; k++) {
					if(obj[tmp[i].children_d[k]]) {
						delete obj[tmp[i].children_d[k]];
					}
				}
			}
			tmp = [];
			for(i in obj) {
				if(obj.hasOwnProperty(i)) {
					tmp.push(i);
				}
			}
			return full ? $.map(tmp, $.proxy(function (i) { return this.get_node(i); }, this)) : tmp;
		};
		/**
		 * get an array of all bottom level checked nodes (ignoring selected parents) (if tie_selection is on in the settings this function will return the same as get_bottom_selected)
		 * @name get_bottom_checked([full])
		 * @param  {mixed}  full if set to `true` the returned array will consist of the full node objects, otherwise - only IDs will be returned
		 * @return {Array}
		 * @plugin checkbox
		 */
		this.get_bottom_checked = function (full) {
			if(this.settings.checkbox.tie_selection) { return this.get_bottom_selected(full); }
			var tmp = this.get_checked(true),
				obj = [], i, j;
			for(i = 0, j = tmp.length; i < j; i++) {
				if(!tmp[i].children.length) {
					obj.push(tmp[i].id);
				}
			}
			return full ? $.map(obj, $.proxy(function (i) { return this.get_node(i); }, this)) : obj;
		};
		this.load_node = function (obj, callback) {
			var k, l, i, j, c, tmp;
			if(!$.isArray(obj) && !this.settings.checkbox.tie_selection) {
				tmp = this.get_node(obj);
				if(tmp && tmp.state.loaded) {
					for(k = 0, l = tmp.children_d.length; k < l; k++) {
						if(this._model.data[tmp.children_d[k]].state.checked) {
							c = true;
							this._data.checkbox.selected = $.vakata.array_remove_item(this._data.checkbox.selected, tmp.children_d[k]);
						}
					}
				}
			}
			return parent.load_node.apply(this, arguments);
		};
		this.get_state = function () {
			var state = parent.get_state.apply(this, arguments);
			if(this.settings.checkbox.tie_selection) { return state; }
			state.checkbox = this._data.checkbox.selected.slice();
			return state;
		};
		this.set_state = function (state, callback) {
			var res = parent.set_state.apply(this, arguments);
			if(res && state.checkbox) {
				if(!this.settings.checkbox.tie_selection) {
					this.uncheck_all();
					var _this = this;
					$.each(state.checkbox, function (i, v) {
						_this.check_node(v);
					});
				}
				delete state.checkbox;
				this.set_state(state, callback);
				return false;
			}
			return res;
		};
		this.refresh = function (skip_loading, forget_state) {
			if(this.settings.checkbox.tie_selection) {
				this._data.checkbox.selected = [];
			}
			return parent.refresh.apply(this, arguments);
		};
	};

	// include the checkbox plugin by default
	// $.jstree.defaults.plugins.push("checkbox");


/**
 * ### Conditionalselect plugin
 *
 * This plugin allows defining a callback to allow or deny node selection by user input (activate node method).
 */

	/**
	 * a callback (function) which is invoked in the instance's scope and receives two arguments - the node and the event that triggered the `activate_node` call. Returning false prevents working with the node, returning true allows invoking activate_node. Defaults to returning `true`.
	 * @name $.jstree.defaults.checkbox.visible
	 * @plugin checkbox
	 */
	$.jstree.defaults.conditionalselect = function () { return true; };
	$.jstree.plugins.conditionalselect = function (options, parent) {
		// own function
		this.activate_node = function (obj, e) {
			if(this.settings.conditionalselect.call(this, this.get_node(obj), e)) {
				return parent.activate_node.call(this, obj, e);
			}
		};
	};


/**
 * ### Contextmenu plugin
 *
 * Shows a context menu when a node is right-clicked.
 */

	/**
	 * stores all defaults for the contextmenu plugin
	 * @name $.jstree.defaults.contextmenu
	 * @plugin contextmenu
	 */
	$.jstree.defaults.contextmenu = {
		/**
		 * a boolean indicating if the node should be selected when the context menu is invoked on it. Defaults to `true`.
		 * @name $.jstree.defaults.contextmenu.select_node
		 * @plugin contextmenu
		 */
		select_node : true,
		/**
		 * a boolean indicating if the menu should be shown aligned with the node. Defaults to `true`, otherwise the mouse coordinates are used.
		 * @name $.jstree.defaults.contextmenu.show_at_node
		 * @plugin contextmenu
		 */
		show_at_node : true,
		/**
		 * an object of actions, or a function that accepts a node and a callback function and calls the callback function with an object of actions available for that node (you can also return the items too).
		 *
		 * Each action consists of a key (a unique name) and a value which is an object with the following properties (only label and action are required). Once a menu item is activated the `action` function will be invoked with an object containing the following keys: item - the contextmenu item definition as seen below, reference - the DOM node that was used (the tree node), element - the contextmenu DOM element, position - an object with x/y properties indicating the position of the menu.
		 *
		 * * `separator_before` - a boolean indicating if there should be a separator before this item
		 * * `separator_after` - a boolean indicating if there should be a separator after this item
		 * * `_disabled` - a boolean indicating if this action should be disabled
		 * * `label` - a string - the name of the action (could be a function returning a string)
		 * * `title` - a string - an optional tooltip for the item
		 * * `action` - a function to be executed if this item is chosen, the function will receive 
		 * * `icon` - a string, can be a path to an icon or a className, if using an image that is in the current directory use a `./` prefix, otherwise it will be detected as a class
		 * * `shortcut` - keyCode which will trigger the action if the menu is open (for example `113` for rename, which equals F2)
		 * * `shortcut_label` - shortcut label (like for example `F2` for rename)
		 * * `submenu` - an object with the same structure as $.jstree.defaults.contextmenu.items which can be used to create a submenu - each key will be rendered as a separate option in a submenu that will appear once the current item is hovered
		 *
		 * @name $.jstree.defaults.contextmenu.items
		 * @plugin contextmenu
		 */
		items : function (o, cb) { // Could be an object directly
			return {
				"create" : {
					"separator_before"	: false,
					"separator_after"	: true,
					"_disabled"			: false, //(this.check("create_node", data.reference, {}, "last")),
					"label"				: "Create",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						inst.create_node(obj, {}, "last", function (new_node) {
							try {
								inst.edit(new_node);
							} catch (ex) {
								setTimeout(function () { inst.edit(new_node); },0);
							}
						});
					}
				},
				"rename" : {
					"separator_before"	: false,
					"separator_after"	: false,
					"_disabled"			: false, //(this.check("rename_node", data.reference, this.get_parent(data.reference), "")),
					"label"				: "Rename",
					/*!
					"shortcut"			: 113,
					"shortcut_label"	: 'F2',
					"icon"				: "glyphicon glyphicon-leaf",
					*/
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						inst.edit(obj);
					}
				},
				"remove" : {
					"separator_before"	: false,
					"icon"				: false,
					"separator_after"	: false,
					"_disabled"			: false, //(this.check("delete_node", data.reference, this.get_parent(data.reference), "")),
					"label"				: "Delete",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						if(inst.is_selected(obj)) {
							inst.delete_node(inst.get_selected());
						}
						else {
							inst.delete_node(obj);
						}
					}
				},
				"ccp" : {
					"separator_before"	: true,
					"icon"				: false,
					"separator_after"	: false,
					"label"				: "Edit",
					"action"			: false,
					"submenu" : {
						"cut" : {
							"separator_before"	: false,
							"separator_after"	: false,
							"label"				: "Cut",
							"action"			: function (data) {
								var inst = $.jstree.reference(data.reference),
									obj = inst.get_node(data.reference);
								if(inst.is_selected(obj)) {
									inst.cut(inst.get_top_selected());
								}
								else {
									inst.cut(obj);
								}
							}
						},
						"copy" : {
							"separator_before"	: false,
							"icon"				: false,
							"separator_after"	: false,
							"label"				: "Copy",
							"action"			: function (data) {
								var inst = $.jstree.reference(data.reference),
									obj = inst.get_node(data.reference);
								if(inst.is_selected(obj)) {
									inst.copy(inst.get_top_selected());
								}
								else {
									inst.copy(obj);
								}
							}
						},
						"paste" : {
							"separator_before"	: false,
							"icon"				: false,
							"_disabled"			: function (data) {
								return !$.jstree.reference(data.reference).can_paste();
							},
							"separator_after"	: false,
							"label"				: "Paste",
							"action"			: function (data) {
								var inst = $.jstree.reference(data.reference),
									obj = inst.get_node(data.reference);
								inst.paste(obj);
							}
						}
					}
				}
			};
		}
	};

	$.jstree.plugins.contextmenu = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);

			var last_ts = 0, cto = null, ex, ey;
			this.element
				.on("init.jstree loading.jstree ready.jstree", $.proxy(function () {
						this.get_container_ul().addClass('jstree-contextmenu');
					}, this))
				.on("contextmenu.jstree", ".jstree-anchor", $.proxy(function (e, data) {
						if (e.target.tagName.toLowerCase() === 'input') {
							return;
						}
						e.preventDefault();
						last_ts = e.ctrlKey ? +new Date() : 0;
						if(data || cto) {
							last_ts = (+new Date()) + 10000;
						}
						if(cto) {
							clearTimeout(cto);
						}
						if(!this.is_loading(e.currentTarget)) {
							this.show_contextmenu(e.currentTarget, e.pageX, e.pageY, e);
						}
					}, this))
				.on("click.jstree", ".jstree-anchor", $.proxy(function (e) {
						if(this._data.contextmenu.visible && (!last_ts || (+new Date()) - last_ts > 250)) { // work around safari & macOS ctrl+click
							$.vakata.context.hide();
						}
						last_ts = 0;
					}, this))
				.on("touchstart.jstree", ".jstree-anchor", function (e) {
						if(!e.originalEvent || !e.originalEvent.changedTouches || !e.originalEvent.changedTouches[0]) {
							return;
						}
						ex = e.originalEvent.changedTouches[0].clientX;
						ey = e.originalEvent.changedTouches[0].clientY;
						cto = setTimeout(function () {
							$(e.currentTarget).trigger('contextmenu', true);
						}, 750);
					})
				.on('touchmove.vakata.jstree', function (e) {
						if(cto && e.originalEvent && e.originalEvent.changedTouches && e.originalEvent.changedTouches[0] && (Math.abs(ex - e.originalEvent.changedTouches[0].clientX) > 10 || Math.abs(ey - e.originalEvent.changedTouches[0].clientY) > 10)) {
							clearTimeout(cto);
							$.vakata.context.hide();
						}
					})
				.on('touchend.vakata.jstree', function (e) {
						if(cto) {
							clearTimeout(cto);
						}
					});

			/*!
			if(!('oncontextmenu' in document.body) && ('ontouchstart' in document.body)) {
				var el = null, tm = null;
				this.element
					.on("touchstart", ".jstree-anchor", function (e) {
						el = e.currentTarget;
						tm = +new Date();
						$(document).one("touchend", function (e) {
							e.target = document.elementFromPoint(e.originalEvent.targetTouches[0].pageX - window.pageXOffset, e.originalEvent.targetTouches[0].pageY - window.pageYOffset);
							e.currentTarget = e.target;
							tm = ((+(new Date())) - tm);
							if(e.target === el && tm > 600 && tm < 1000) {
								e.preventDefault();
								$(el).trigger('contextmenu', e);
							}
							el = null;
							tm = null;
						});
					});
			}
			*/
			$(document).on("context_hide.vakata.jstree", $.proxy(function (e, data) {
				this._data.contextmenu.visible = false;
				$(data.reference).removeClass('jstree-context');
			}, this));
		};
		this.teardown = function () {
			if(this._data.contextmenu.visible) {
				$.vakata.context.hide();
			}
			parent.teardown.call(this);
		};

		/**
		 * prepare and show the context menu for a node
		 * @name show_contextmenu(obj [, x, y])
		 * @param {mixed} obj the node
		 * @param {Number} x the x-coordinate relative to the document to show the menu at
		 * @param {Number} y the y-coordinate relative to the document to show the menu at
		 * @param {Object} e the event if available that triggered the contextmenu
		 * @plugin contextmenu
		 * @trigger show_contextmenu.jstree
		 */
		this.show_contextmenu = function (obj, x, y, e) {
			obj = this.get_node(obj);
			if(!obj || obj.id === $.jstree.root) { return false; }
			var s = this.settings.contextmenu,
				d = this.get_node(obj, true),
				a = d.children(".jstree-anchor"),
				o = false,
				i = false;
			if(s.show_at_node || x === undefined || y === undefined) {
				o = a.offset();
				x = o.left;
				y = o.top + this._data.core.li_height;
			}
			if(this.settings.contextmenu.select_node && !this.is_selected(obj)) {
				this.activate_node(obj, e);
			}

			i = s.items;
			if($.isFunction(i)) {
				i = i.call(this, obj, $.proxy(function (i) {
					this._show_contextmenu(obj, x, y, i);
				}, this));
			}
			if($.isPlainObject(i)) {
				this._show_contextmenu(obj, x, y, i);
			}
		};
		/**
		 * show the prepared context menu for a node
		 * @name _show_contextmenu(obj, x, y, i)
		 * @param {mixed} obj the node
		 * @param {Number} x the x-coordinate relative to the document to show the menu at
		 * @param {Number} y the y-coordinate relative to the document to show the menu at
		 * @param {Number} i the object of items to show
		 * @plugin contextmenu
		 * @trigger show_contextmenu.jstree
		 * @private
		 */
		this._show_contextmenu = function (obj, x, y, i) {
			var d = this.get_node(obj, true),
				a = d.children(".jstree-anchor");
			$(document).one("context_show.vakata.jstree", $.proxy(function (e, data) {
				var cls = 'jstree-contextmenu jstree-' + this.get_theme() + '-contextmenu';
				$(data.element).addClass(cls);
				a.addClass('jstree-context');
			}, this));
			this._data.contextmenu.visible = true;
			$.vakata.context.show(a, { 'x' : x, 'y' : y }, i);
			/**
			 * triggered when the contextmenu is shown for a node
			 * @event
			 * @name show_contextmenu.jstree
			 * @param {Object} node the node
			 * @param {Number} x the x-coordinate of the menu relative to the document
			 * @param {Number} y the y-coordinate of the menu relative to the document
			 * @plugin contextmenu
			 */
			this.trigger('show_contextmenu', { "node" : obj, "x" : x, "y" : y });
		};
	};

	// contextmenu helper
	(function ($) {
		var right_to_left = false,
			vakata_context = {
				element		: false,
				reference	: false,
				position_x	: 0,
				position_y	: 0,
				items		: [],
				html		: "",
				is_visible	: false
			};

		$.vakata.context = {
			settings : {
				hide_onmouseleave	: 0,
				icons				: true
			},
			_trigger : function (event_name) {
				$(document).triggerHandler("context_" + event_name + ".vakata", {
					"reference"	: vakata_context.reference,
					"element"	: vakata_context.element,
					"position"	: {
						"x" : vakata_context.position_x,
						"y" : vakata_context.position_y
					}
				});
			},
			_execute : function (i) {
				i = vakata_context.items[i];
				return i && (!i._disabled || ($.isFunction(i._disabled) && !i._disabled({ "item" : i, "reference" : vakata_context.reference, "element" : vakata_context.element }))) && i.action ? i.action.call(null, {
							"item"		: i,
							"reference"	: vakata_context.reference,
							"element"	: vakata_context.element,
							"position"	: {
								"x" : vakata_context.position_x,
								"y" : vakata_context.position_y
							}
						}) : false;
			},
			_parse : function (o, is_callback) {
				if(!o) { return false; }
				if(!is_callback) {
					vakata_context.html		= "";
					vakata_context.items	= [];
				}
				var str = "",
					sep = false,
					tmp;

				if(is_callback) { str += "<"+"ul>"; }
				$.each(o, function (i, val) {
					if(!val) { return true; }
					vakata_context.items.push(val);
					if(!sep && val.separator_before) {
						str += "<"+"li class='vakata-context-separator'><"+"a href='#' " + ($.vakata.context.settings.icons ? '' : 'style="margin-left:0px;"') + ">&#160;<"+"/a><"+"/li>";
					}
					sep = false;
					str += "<"+"li class='" + (val._class || "") + (val._disabled === true || ($.isFunction(val._disabled) && val._disabled({ "item" : val, "reference" : vakata_context.reference, "element" : vakata_context.element })) ? " vakata-contextmenu-disabled " : "") + "' "+(val.shortcut?" data-shortcut='"+val.shortcut+"' ":'')+">";
					str += "<"+"a href='#' rel='" + (vakata_context.items.length - 1) + "' " + (val.title ? "title='" + val.title + "'" : "") + ">";
					if($.vakata.context.settings.icons) {
						str += "<"+"i ";
						if(val.icon) {
							if(val.icon.indexOf("/") !== -1 || val.icon.indexOf(".") !== -1) { str += " style='background:url(\"" + val.icon + "\") center center no-repeat' "; }
							else { str += " class='" + val.icon + "' "; }
						}
						str += "><"+"/i><"+"span class='vakata-contextmenu-sep'>&#160;<"+"/span>";
					}
					str += ($.isFunction(val.label) ? val.label({ "item" : i, "reference" : vakata_context.reference, "element" : vakata_context.element }) : val.label) + (val.shortcut?' <span class="vakata-contextmenu-shortcut vakata-contextmenu-shortcut-'+val.shortcut+'">'+ (val.shortcut_label || '') +'</span>':'') + "<"+"/a>";
					if(val.submenu) {
						tmp = $.vakata.context._parse(val.submenu, true);
						if(tmp) { str += tmp; }
					}
					str += "<"+"/li>";
					if(val.separator_after) {
						str += "<"+"li class='vakata-context-separator'><"+"a href='#' " + ($.vakata.context.settings.icons ? '' : 'style="margin-left:0px;"') + ">&#160;<"+"/a><"+"/li>";
						sep = true;
					}
				});
				str  = str.replace(/<li class\='vakata-context-separator'\><\/li\>$/,"");
				if(is_callback) { str += "</ul>"; }
				/**
				 * triggered on the document when the contextmenu is parsed (HTML is built)
				 * @event
				 * @plugin contextmenu
				 * @name context_parse.vakata
				 * @param {jQuery} reference the element that was right clicked
				 * @param {jQuery} element the DOM element of the menu itself
				 * @param {Object} position the x & y coordinates of the menu
				 */
				if(!is_callback) { vakata_context.html = str; $.vakata.context._trigger("parse"); }
				return str.length > 10 ? str : false;
			},
			_show_submenu : function (o) {
				o = $(o);
				if(!o.length || !o.children("ul").length) { return; }
				var e = o.children("ul"),
					xl = o.offset().left,
					x = xl + o.outerWidth(),
					y = o.offset().top,
					w = e.width(),
					h = e.height(),
					dw = $(window).width() + $(window).scrollLeft(),
					dh = $(window).height() + $(window).scrollTop();
				// може да се спести е една проверка - дали няма някой от класовете вече нагоре
				if(right_to_left) {
					o[x - (w + 10 + o.outerWidth()) < 0 ? "addClass" : "removeClass"]("vakata-context-left");
				}
				else {
					o[x + w > dw  && xl > dw - x ? "addClass" : "removeClass"]("vakata-context-right");
				}
				if(y + h + 10 > dh) {
					e.css("bottom","-1px");
				}

				//if does not fit - stick it to the side
				if (o.hasClass('vakata-context-right')) {
					if (xl < w) {
						e.css("margin-right", xl - w);
					}
				} else {
					if (dw - x < w) {
						e.css("margin-left", dw - x - w);
					}
				}

				e.show();
			},
			show : function (reference, position, data) {
				var o, e, x, y, w, h, dw, dh, cond = true;
				if(vakata_context.element && vakata_context.element.length) {
					vakata_context.element.width('');
				}
				switch(cond) {
					case (!position && !reference):
						return false;
					case (!!position && !!reference):
						vakata_context.reference	= reference;
						vakata_context.position_x	= position.x;
						vakata_context.position_y	= position.y;
						break;
					case (!position && !!reference):
						vakata_context.reference	= reference;
						o = reference.offset();
						vakata_context.position_x	= o.left + reference.outerHeight();
						vakata_context.position_y	= o.top;
						break;
					case (!!position && !reference):
						vakata_context.position_x	= position.x;
						vakata_context.position_y	= position.y;
						break;
				}
				if(!!reference && !data && $(reference).data('vakata_contextmenu')) {
					data = $(reference).data('vakata_contextmenu');
				}
				if($.vakata.context._parse(data)) {
					vakata_context.element.html(vakata_context.html);
				}
				if(vakata_context.items.length) {
					vakata_context.element.appendTo(document.body);
					e = vakata_context.element;
					x = vakata_context.position_x;
					y = vakata_context.position_y;
					w = e.width();
					h = e.height();
					dw = $(window).width() + $(window).scrollLeft();
					dh = $(window).height() + $(window).scrollTop();
					if(right_to_left) {
						x -= (e.outerWidth() - $(reference).outerWidth());
						if(x < $(window).scrollLeft() + 20) {
							x = $(window).scrollLeft() + 20;
						}
					}
					if(x + w + 20 > dw) {
						x = dw - (w + 20);
					}
					if(y + h + 20 > dh) {
						y = dh - (h + 20);
					}

					vakata_context.element
						.css({ "left" : x, "top" : y })
						.show()
						.find('a').first().focus().parent().addClass("vakata-context-hover");
					vakata_context.is_visible = true;
					/**
					 * triggered on the document when the contextmenu is shown
					 * @event
					 * @plugin contextmenu
					 * @name context_show.vakata
					 * @param {jQuery} reference the element that was right clicked
					 * @param {jQuery} element the DOM element of the menu itself
					 * @param {Object} position the x & y coordinates of the menu
					 */
					$.vakata.context._trigger("show");
				}
			},
			hide : function () {
				if(vakata_context.is_visible) {
					vakata_context.element.hide().find("ul").hide().end().find(':focus').blur().end().detach();
					vakata_context.is_visible = false;
					/**
					 * triggered on the document when the contextmenu is hidden
					 * @event
					 * @plugin contextmenu
					 * @name context_hide.vakata
					 * @param {jQuery} reference the element that was right clicked
					 * @param {jQuery} element the DOM element of the menu itself
					 * @param {Object} position the x & y coordinates of the menu
					 */
					$.vakata.context._trigger("hide");
				}
			}
		};
		$(function () {
			right_to_left = $(document.body).css("direction") === "rtl";
			var to = false;

			vakata_context.element = $("<ul class='vakata-context'></ul>");
			vakata_context.element
				.on("mouseenter", "li", function (e) {
					e.stopImmediatePropagation();

					if($.contains(this, e.relatedTarget)) {
						// премахнато заради delegate mouseleave по-долу
						// $(this).find(".vakata-context-hover").removeClass("vakata-context-hover");
						return;
					}

					if(to) { clearTimeout(to); }
					vakata_context.element.find(".vakata-context-hover").removeClass("vakata-context-hover").end();

					$(this)
						.siblings().find("ul").hide().end().end()
						.parentsUntil(".vakata-context", "li").addBack().addClass("vakata-context-hover");
					$.vakata.context._show_submenu(this);
				})
				// тестово - дали не натоварва?
				.on("mouseleave", "li", function (e) {
					if($.contains(this, e.relatedTarget)) { return; }
					$(this).find(".vakata-context-hover").addBack().removeClass("vakata-context-hover");
				})
				.on("mouseleave", function (e) {
					$(this).find(".vakata-context-hover").removeClass("vakata-context-hover");
					if($.vakata.context.settings.hide_onmouseleave) {
						to = setTimeout(
							(function (t) {
								return function () { $.vakata.context.hide(); };
							}(this)), $.vakata.context.settings.hide_onmouseleave);
					}
				})
				.on("click", "a", function (e) {
					e.preventDefault();
				//})
				//.on("mouseup", "a", function (e) {
					if(!$(this).blur().parent().hasClass("vakata-context-disabled") && $.vakata.context._execute($(this).attr("rel")) !== false) {
						$.vakata.context.hide();
					}
				})
				.on('keydown', 'a', function (e) {
						var o = null;
						switch(e.which) {
							case 13:
							case 32:
								e.type = "click";
								e.preventDefault();
								$(e.currentTarget).trigger(e);
								break;
							case 37:
								if(vakata_context.is_visible) {
									vakata_context.element.find(".vakata-context-hover").last().closest("li").first().find("ul").hide().find(".vakata-context-hover").removeClass("vakata-context-hover").end().end().children('a').focus();
									e.stopImmediatePropagation();
									e.preventDefault();
								}
								break;
							case 38:
								if(vakata_context.is_visible) {
									o = vakata_context.element.find("ul:visible").addBack().last().children(".vakata-context-hover").removeClass("vakata-context-hover").prevAll("li:not(.vakata-context-separator)").first();
									if(!o.length) { o = vakata_context.element.find("ul:visible").addBack().last().children("li:not(.vakata-context-separator)").last(); }
									o.addClass("vakata-context-hover").children('a').focus();
									e.stopImmediatePropagation();
									e.preventDefault();
								}
								break;
							case 39:
								if(vakata_context.is_visible) {
									vakata_context.element.find(".vakata-context-hover").last().children("ul").show().children("li:not(.vakata-context-separator)").removeClass("vakata-context-hover").first().addClass("vakata-context-hover").children('a').focus();
									e.stopImmediatePropagation();
									e.preventDefault();
								}
								break;
							case 40:
								if(vakata_context.is_visible) {
									o = vakata_context.element.find("ul:visible").addBack().last().children(".vakata-context-hover").removeClass("vakata-context-hover").nextAll("li:not(.vakata-context-separator)").first();
									if(!o.length) { o = vakata_context.element.find("ul:visible").addBack().last().children("li:not(.vakata-context-separator)").first(); }
									o.addClass("vakata-context-hover").children('a').focus();
									e.stopImmediatePropagation();
									e.preventDefault();
								}
								break;
							case 27:
								$.vakata.context.hide();
								e.preventDefault();
								break;
							default:
								//console.log(e.which);
								break;
						}
					})
				.on('keydown', function (e) {
					e.preventDefault();
					var a = vakata_context.element.find('.vakata-contextmenu-shortcut-' + e.which).parent();
					if(a.parent().not('.vakata-context-disabled')) {
						a.click();
					}
				});

			$(document)
				.on("mousedown.vakata.jstree", function (e) {
					if(vakata_context.is_visible && vakata_context.element[0] !== e.target  && !$.contains(vakata_context.element[0], e.target)) {
						$.vakata.context.hide();
					}
				})
				.on("context_show.vakata.jstree", function (e, data) {
					vakata_context.element.find("li:has(ul)").children("a").addClass("vakata-context-parent");
					if(right_to_left) {
						vakata_context.element.addClass("vakata-context-rtl").css("direction", "rtl");
					}
					// also apply a RTL class?
					vakata_context.element.find("ul").hide().end();
				});
		});
	}($));
	// $.jstree.defaults.plugins.push("contextmenu");


/**
 * ### Drag'n'drop plugin
 *
 * Enables dragging and dropping of nodes in the tree, resulting in a move or copy operations.
 */

	/**
	 * stores all defaults for the drag'n'drop plugin
	 * @name $.jstree.defaults.dnd
	 * @plugin dnd
	 */
	$.jstree.defaults.dnd = {
		/**
		 * a boolean indicating if a copy should be possible while dragging (by pressint the meta key or Ctrl). Defaults to `true`.
		 * @name $.jstree.defaults.dnd.copy
		 * @plugin dnd
		 */
		copy : true,
		/**
		 * a number indicating how long a node should remain hovered while dragging to be opened. Defaults to `500`.
		 * @name $.jstree.defaults.dnd.open_timeout
		 * @plugin dnd
		 */
		open_timeout : 500,
		/**
		 * a function invoked each time a node is about to be dragged, invoked in the tree's scope and receives the nodes about to be dragged as an argument (array) and the event that started the drag - return `false` to prevent dragging
		 * @name $.jstree.defaults.dnd.is_draggable
		 * @plugin dnd
		 */
		is_draggable : true,
		/**
		 * a boolean indicating if checks should constantly be made while the user is dragging the node (as opposed to checking only on drop), default is `true`
		 * @name $.jstree.defaults.dnd.check_while_dragging
		 * @plugin dnd
		 */
		check_while_dragging : true,
		/**
		 * a boolean indicating if nodes from this tree should only be copied with dnd (as opposed to moved), default is `false`
		 * @name $.jstree.defaults.dnd.always_copy
		 * @plugin dnd
		 */
		always_copy : false,
		/**
		 * when dropping a node "inside", this setting indicates the position the node should go to - it can be an integer or a string: "first" (same as 0) or "last", default is `0`
		 * @name $.jstree.defaults.dnd.inside_pos
		 * @plugin dnd
		 */
		inside_pos : 0,
		/**
		 * when starting the drag on a node that is selected this setting controls if all selected nodes are dragged or only the single node, default is `true`, which means all selected nodes are dragged when the drag is started on a selected node
		 * @name $.jstree.defaults.dnd.drag_selection
		 * @plugin dnd
		 */
		drag_selection : true,
		/**
		 * controls whether dnd works on touch devices. If left as boolean true dnd will work the same as in desktop browsers, which in some cases may impair scrolling. If set to boolean false dnd will not work on touch devices. There is a special third option - string "selected" which means only selected nodes can be dragged on touch devices.
		 * @name $.jstree.defaults.dnd.touch
		 * @plugin dnd
		 */
		touch : true,
		/**
		 * controls whether items can be dropped anywhere on the node, not just on the anchor, by default only the node anchor is a valid drop target. Works best with the wholerow plugin. If enabled on mobile depending on the interface it might be hard for the user to cancel the drop, since the whole tree container will be a valid drop target.
		 * @name $.jstree.defaults.dnd.large_drop_target
		 * @plugin dnd
		 */
		large_drop_target : false,
		/**
		 * controls whether a drag can be initiated from any part of the node and not just the text/icon part, works best with the wholerow plugin. Keep in mind it can cause problems with tree scrolling on mobile depending on the interface - in that case set the touch option to "selected".
		 * @name $.jstree.defaults.dnd.large_drag_target
		 * @plugin dnd
		 */
		large_drag_target : false,
		/**
		 * controls whether use HTML5 dnd api instead of classical. That will allow better integration of dnd events with other HTML5 controls.
		 * @reference http://caniuse.com/#feat=dragndrop
		 * @name $.jstree.defaults.dnd.use_html5
		 * @plugin dnd
		 */
		use_html5: false
	};
	var drg, elm;
	// TODO: now check works by checking for each node individually, how about max_children, unique, etc?
	$.jstree.plugins.dnd = function (options, parent) {
		this.init = function (el, options) {
			parent.init.call(this, el, options);
			this.settings.dnd.use_html5 = this.settings.dnd.use_html5 && ('draggable' in document.createElement('span'));
		};
		this.bind = function () {
			parent.bind.call(this);

			this.element
				.on(this.settings.dnd.use_html5 ? 'dragstart.jstree' : 'mousedown.jstree touchstart.jstree', this.settings.dnd.large_drag_target ? '.jstree-node' : '.jstree-anchor', $.proxy(function (e) {
						if(this.settings.dnd.large_drag_target && $(e.target).closest('.jstree-node')[0] !== e.currentTarget) {
							return true;
						}
						if(e.type === "touchstart" && (!this.settings.dnd.touch || (this.settings.dnd.touch === 'selected' && !$(e.currentTarget).closest('.jstree-node').children('.jstree-anchor').hasClass('jstree-clicked')))) {
							return true;
						}
						var obj = this.get_node(e.target),
							mlt = this.is_selected(obj) && this.settings.dnd.drag_selection ? this.get_top_selected().length : 1,
							txt = (mlt > 1 ? mlt + ' ' + this.get_string('nodes') : this.get_text(e.currentTarget));
						if(this.settings.core.force_text) {
							txt = $.vakata.html.escape(txt);
						}
						if(obj && obj.id && obj.id !== $.jstree.root && (e.which === 1 || e.type === "touchstart" || e.type === "dragstart") &&
							(this.settings.dnd.is_draggable === true || ($.isFunction(this.settings.dnd.is_draggable) && this.settings.dnd.is_draggable.call(this, (mlt > 1 ? this.get_top_selected(true) : [obj]), e)))
						) {
							drg = { 'jstree' : true, 'origin' : this, 'obj' : this.get_node(obj,true), 'nodes' : mlt > 1 ? this.get_top_selected() : [obj.id] };
							elm = e.currentTarget;
							if (this.settings.dnd.use_html5) {
								$.vakata.dnd._trigger('start', e, { 'helper': $(), 'element': elm, 'data': drg });
							} else {
								this.element.trigger('mousedown.jstree');
								return $.vakata.dnd.start(e, drg, '<div id="jstree-dnd" class="jstree-' + this.get_theme() + ' jstree-' + this.get_theme() + '-' + this.get_theme_variant() + ' ' + ( this.settings.core.themes.responsive ? ' jstree-dnd-responsive' : '' ) + '"><i class="jstree-icon jstree-er"></i>' + txt + '<ins class="jstree-copy" style="display:none;">+</ins></div>');
							}
						}
					}, this));
			if (this.settings.dnd.use_html5) {
				this.element
					.on('dragover.jstree', function (e) {
							e.preventDefault();
							$.vakata.dnd._trigger('move', e, { 'helper': $(), 'element': elm, 'data': drg });
							return false;
						})
					//.on('dragenter.jstree', this.settings.dnd.large_drop_target ? '.jstree-node' : '.jstree-anchor', $.proxy(function (e) {
					//		e.preventDefault();
					//		$.vakata.dnd._trigger('move', e, { 'helper': $(), 'element': elm, 'data': drg });
					//		return false;
					//	}, this))
					.on('drop.jstree', $.proxy(function (e) {
							e.preventDefault();
							$.vakata.dnd._trigger('stop', e, { 'helper': $(), 'element': elm, 'data': drg });
							return false;
						}, this));
			}
		};
		this.redraw_node = function(obj, deep, callback, force_render) {
			obj = parent.redraw_node.apply(this, arguments);
			if (obj && this.settings.dnd.use_html5) {
				if (this.settings.dnd.large_drag_target) {
					obj.setAttribute('draggable', true);
				} else {
					var i, j, tmp = null;
					for(i = 0, j = obj.childNodes.length; i < j; i++) {
						if(obj.childNodes[i] && obj.childNodes[i].className && obj.childNodes[i].className.indexOf("jstree-anchor") !== -1) {
							tmp = obj.childNodes[i];
							break;
						}
					}
					if(tmp) {
						tmp.setAttribute('draggable', true);
					}
				}
			}
			return obj;
		};
	};

	$(function() {
		// bind only once for all instances
		var lastmv = false,
			laster = false,
			lastev = false,
			opento = false,
			marker = $('<div id="jstree-marker">&#160;</div>').hide(); //.appendTo('body');

		$(document)
			.on('dragover.vakata.jstree', function (e) {
				if (elm) {
					$.vakata.dnd._trigger('move', e, { 'helper': $(), 'element': elm, 'data': drg });
				}
			})
			.on('drop.vakata.jstree', function (e) {
				if (elm) {
					$.vakata.dnd._trigger('stop', e, { 'helper': $(), 'element': elm, 'data': drg });
					elm = null;
					drg = null;
				}
			})
			.on('dnd_start.vakata.jstree', function (e, data) {
				lastmv = false;
				lastev = false;
				if(!data || !data.data || !data.data.jstree) { return; }
				marker.appendTo(document.body); //.show();
			})
			.on('dnd_move.vakata.jstree', function (e, data) {
				var isDifferentNode = data.event.target !== lastev.target;
				if(opento) {
					if (!data.event || data.event.type !== 'dragover' || isDifferentNode) {
						clearTimeout(opento);
					}
				}
				if(!data || !data.data || !data.data.jstree) { return; }

				// if we are hovering the marker image do nothing (can happen on "inside" drags)
				if(data.event.target.id && data.event.target.id === 'jstree-marker') {
					return;
				}
				lastev = data.event;

				var ins = $.jstree.reference(data.event.target),
					ref = false,
					off = false,
					rel = false,
					tmp, l, t, h, p, i, o, ok, t1, t2, op, ps, pr, ip, tm, is_copy, pn;
				// if we are over an instance
				if(ins && ins._data && ins._data.dnd) {
					marker.attr('class', 'jstree-' + ins.get_theme() + ( ins.settings.core.themes.responsive ? ' jstree-dnd-responsive' : '' ));
					is_copy = data.data.origin && (data.data.origin.settings.dnd.always_copy || (data.data.origin.settings.dnd.copy && (data.event.metaKey || data.event.ctrlKey)));
					data.helper
						.children().attr('class', 'jstree-' + ins.get_theme() + ' jstree-' + ins.get_theme() + '-' + ins.get_theme_variant() + ' ' + ( ins.settings.core.themes.responsive ? ' jstree-dnd-responsive' : '' ))
						.find('.jstree-copy').first()[ is_copy ? 'show' : 'hide' ]();

					// if are hovering the container itself add a new root node
					//console.log(data.event);
					if( (data.event.target === ins.element[0] || data.event.target === ins.get_container_ul()[0]) && ins.get_container_ul().children().length === 0) {
						ok = true;
						for(t1 = 0, t2 = data.data.nodes.length; t1 < t2; t1++) {
							ok = ok && ins.check( (data.data.origin && (data.data.origin.settings.dnd.always_copy || (data.data.origin.settings.dnd.copy && (data.event.metaKey || data.event.ctrlKey)) ) ? "copy_node" : "move_node"), (data.data.origin && data.data.origin !== ins ? data.data.origin.get_node(data.data.nodes[t1]) : data.data.nodes[t1]), $.jstree.root, 'last', { 'dnd' : true, 'ref' : ins.get_node($.jstree.root), 'pos' : 'i', 'origin' : data.data.origin, 'is_multi' : (data.data.origin && data.data.origin !== ins), 'is_foreign' : (!data.data.origin) });
							if(!ok) { break; }
						}
						if(ok) {
							lastmv = { 'ins' : ins, 'par' : $.jstree.root, 'pos' : 'last' };
							marker.hide();
							data.helper.find('.jstree-icon').first().removeClass('jstree-er').addClass('jstree-ok');
							if (data.event.originalEvent && data.event.originalEvent.dataTransfer) {
								data.event.originalEvent.dataTransfer.dropEffect = is_copy ? 'copy' : 'move';
							}
							return;
						}
					}
					else {
						// if we are hovering a tree node
						ref = ins.settings.dnd.large_drop_target ? $(data.event.target).closest('.jstree-node').children('.jstree-anchor') : $(data.event.target).closest('.jstree-anchor');
						if(ref && ref.length && ref.parent().is('.jstree-closed, .jstree-open, .jstree-leaf')) {
							off = ref.offset();
							rel = (data.event.pageY !== undefined ? data.event.pageY : data.event.originalEvent.pageY) - off.top;
							h = ref.outerHeight();
							if(rel < h / 3) {
								o = ['b', 'i', 'a'];
							}
							else if(rel > h - h / 3) {
								o = ['a', 'i', 'b'];
							}
							else {
								o = rel > h / 2 ? ['i', 'a', 'b'] : ['i', 'b', 'a'];
							}
							$.each(o, function (j, v) {
								switch(v) {
									case 'b':
										l = off.left - 6;
										t = off.top;
										p = ins.get_parent(ref);
										i = ref.parent().index();
										break;
									case 'i':
										ip = ins.settings.dnd.inside_pos;
										tm = ins.get_node(ref.parent());
										l = off.left - 2;
										t = off.top + h / 2 + 1;
										p = tm.id;
										i = ip === 'first' ? 0 : (ip === 'last' ? tm.children.length : Math.min(ip, tm.children.length));
										break;
									case 'a':
										l = off.left - 6;
										t = off.top + h;
										p = ins.get_parent(ref);
										i = ref.parent().index() + 1;
										break;
								}
								ok = true;
								for(t1 = 0, t2 = data.data.nodes.length; t1 < t2; t1++) {
									op = data.data.origin && (data.data.origin.settings.dnd.always_copy || (data.data.origin.settings.dnd.copy && (data.event.metaKey || data.event.ctrlKey))) ? "copy_node" : "move_node";
									ps = i;
									if(op === "move_node" && v === 'a' && (data.data.origin && data.data.origin === ins) && p === ins.get_parent(data.data.nodes[t1])) {
										pr = ins.get_node(p);
										if(ps > $.inArray(data.data.nodes[t1], pr.children)) {
											ps -= 1;
										}
									}
									ok = ok && ( (ins && ins.settings && ins.settings.dnd && ins.settings.dnd.check_while_dragging === false) || ins.check(op, (data.data.origin && data.data.origin !== ins ? data.data.origin.get_node(data.data.nodes[t1]) : data.data.nodes[t1]), p, ps, { 'dnd' : true, 'ref' : ins.get_node(ref.parent()), 'pos' : v, 'origin' : data.data.origin, 'is_multi' : (data.data.origin && data.data.origin !== ins), 'is_foreign' : (!data.data.origin) }) );
									if(!ok) {
										if(ins && ins.last_error) { laster = ins.last_error(); }
										break;
									}
								}
								if(v === 'i' && ref.parent().is('.jstree-closed') && ins.settings.dnd.open_timeout) {
									if (!data.event || data.event.type !== 'dragover' || isDifferentNode) {
										if (opento) { clearTimeout(opento); }
										opento = setTimeout((function (x, z) { return function () { x.open_node(z); }; }(ins, ref)), ins.settings.dnd.open_timeout);
									}
								}
								if(ok) {
									pn = ins.get_node(p, true);
									if (!pn.hasClass('.jstree-dnd-parent')) {
										$('.jstree-dnd-parent').removeClass('jstree-dnd-parent');
										pn.addClass('jstree-dnd-parent');
									}
									lastmv = { 'ins' : ins, 'par' : p, 'pos' : v === 'i' && ip === 'last' && i === 0 && !ins.is_loaded(tm) ? 'last' : i };
									marker.css({ 'left' : l + 'px', 'top' : t + 'px' }).show();
									data.helper.find('.jstree-icon').first().removeClass('jstree-er').addClass('jstree-ok');
									if (data.event.originalEvent && data.event.originalEvent.dataTransfer) {
										data.event.originalEvent.dataTransfer.dropEffect = is_copy ? 'copy' : 'move';
									}
									laster = {};
									o = true;
									return false;
								}
							});
							if(o === true) { return; }
						}
					}
				}
				$('.jstree-dnd-parent').removeClass('jstree-dnd-parent');
				lastmv = false;
				data.helper.find('.jstree-icon').removeClass('jstree-ok').addClass('jstree-er');
				if (data.event.originalEvent && data.event.originalEvent.dataTransfer) {
					//data.event.originalEvent.dataTransfer.dropEffect = 'none';
				}
				marker.hide();
			})
			.on('dnd_scroll.vakata.jstree', function (e, data) {
				if(!data || !data.data || !data.data.jstree) { return; }
				marker.hide();
				lastmv = false;
				lastev = false;
				data.helper.find('.jstree-icon').first().removeClass('jstree-ok').addClass('jstree-er');
			})
			.on('dnd_stop.vakata.jstree', function (e, data) {
				$('.jstree-dnd-parent').removeClass('jstree-dnd-parent');
				if(opento) { clearTimeout(opento); }
				if(!data || !data.data || !data.data.jstree) { return; }
				marker.hide().detach();
				var i, j, nodes = [];
				if(lastmv) {
					for(i = 0, j = data.data.nodes.length; i < j; i++) {
						nodes[i] = data.data.origin ? data.data.origin.get_node(data.data.nodes[i]) : data.data.nodes[i];
					}
					lastmv.ins[ data.data.origin && (data.data.origin.settings.dnd.always_copy || (data.data.origin.settings.dnd.copy && (data.event.metaKey || data.event.ctrlKey))) ? 'copy_node' : 'move_node' ](nodes, lastmv.par, lastmv.pos, false, false, false, data.data.origin);
				}
				else {
					i = $(data.event.target).closest('.jstree');
					if(i.length && laster && laster.error && laster.error === 'check') {
						i = i.jstree(true);
						if(i) {
							i.settings.core.error.call(this, laster);
						}
					}
				}
				lastev = false;
				lastmv = false;
			})
			.on('keyup.jstree keydown.jstree', function (e, data) {
				data = $.vakata.dnd._get();
				if(data && data.data && data.data.jstree) {
					if (e.type === "keyup" && e.which === 27) {
						if (opento) { clearTimeout(opento); }
						lastmv = false;
						laster = false;
						lastev = false;
						opento = false;
						marker.hide().detach();
						$.vakata.dnd._clean();
					} else {
						data.helper.find('.jstree-copy').first()[ data.data.origin && (data.data.origin.settings.dnd.always_copy || (data.data.origin.settings.dnd.copy && (e.metaKey || e.ctrlKey))) ? 'show' : 'hide' ]();
						if(lastev) {
							lastev.metaKey = e.metaKey;
							lastev.ctrlKey = e.ctrlKey;
							$.vakata.dnd._trigger('move', lastev);
						}
					}
				}
			});
	});

	// helpers
	(function ($) {
		$.vakata.html = {
			div : $('<div />'),
			escape : function (str) {
				return $.vakata.html.div.text(str).html();
			},
			strip : function (str) {
				return $.vakata.html.div.empty().append($.parseHTML(str)).text();
			}
		};
		// private variable
		var vakata_dnd = {
			element	: false,
			target	: false,
			is_down	: false,
			is_drag	: false,
			helper	: false,
			helper_w: 0,
			data	: false,
			init_x	: 0,
			init_y	: 0,
			scroll_l: 0,
			scroll_t: 0,
			scroll_e: false,
			scroll_i: false,
			is_touch: false
		};
		$.vakata.dnd = {
			settings : {
				scroll_speed		: 10,
				scroll_proximity	: 20,
				helper_left			: 5,
				helper_top			: 10,
				threshold			: 5,
				threshold_touch		: 10
			},
			_trigger : function (event_name, e, data) {
				if (data === undefined) {
					data = $.vakata.dnd._get();
				}
				data.event = e;
				$(document).triggerHandler("dnd_" + event_name + ".vakata", data);
			},
			_get : function () {
				return {
					"data"		: vakata_dnd.data,
					"element"	: vakata_dnd.element,
					"helper"	: vakata_dnd.helper
				};
			},
			_clean : function () {
				if(vakata_dnd.helper) { vakata_dnd.helper.remove(); }
				if(vakata_dnd.scroll_i) { clearInterval(vakata_dnd.scroll_i); vakata_dnd.scroll_i = false; }
				vakata_dnd = {
					element	: false,
					target	: false,
					is_down	: false,
					is_drag	: false,
					helper	: false,
					helper_w: 0,
					data	: false,
					init_x	: 0,
					init_y	: 0,
					scroll_l: 0,
					scroll_t: 0,
					scroll_e: false,
					scroll_i: false,
					is_touch: false
				};
				$(document).off("mousemove.vakata.jstree touchmove.vakata.jstree", $.vakata.dnd.drag);
				$(document).off("mouseup.vakata.jstree touchend.vakata.jstree", $.vakata.dnd.stop);
			},
			_scroll : function (init_only) {
				if(!vakata_dnd.scroll_e || (!vakata_dnd.scroll_l && !vakata_dnd.scroll_t)) {
					if(vakata_dnd.scroll_i) { clearInterval(vakata_dnd.scroll_i); vakata_dnd.scroll_i = false; }
					return false;
				}
				if(!vakata_dnd.scroll_i) {
					vakata_dnd.scroll_i = setInterval($.vakata.dnd._scroll, 100);
					return false;
				}
				if(init_only === true) { return false; }

				var i = vakata_dnd.scroll_e.scrollTop(),
					j = vakata_dnd.scroll_e.scrollLeft();
				vakata_dnd.scroll_e.scrollTop(i + vakata_dnd.scroll_t * $.vakata.dnd.settings.scroll_speed);
				vakata_dnd.scroll_e.scrollLeft(j + vakata_dnd.scroll_l * $.vakata.dnd.settings.scroll_speed);
				if(i !== vakata_dnd.scroll_e.scrollTop() || j !== vakata_dnd.scroll_e.scrollLeft()) {
					/**
					 * triggered on the document when a drag causes an element to scroll
					 * @event
					 * @plugin dnd
					 * @name dnd_scroll.vakata
					 * @param {Mixed} data any data supplied with the call to $.vakata.dnd.start
					 * @param {DOM} element the DOM element being dragged
					 * @param {jQuery} helper the helper shown next to the mouse
					 * @param {jQuery} event the element that is scrolling
					 */
					$.vakata.dnd._trigger("scroll", vakata_dnd.scroll_e);
				}
			},
			start : function (e, data, html) {
				if(e.type === "touchstart" && e.originalEvent && e.originalEvent.changedTouches && e.originalEvent.changedTouches[0]) {
					e.pageX = e.originalEvent.changedTouches[0].pageX;
					e.pageY = e.originalEvent.changedTouches[0].pageY;
					e.target = document.elementFromPoint(e.originalEvent.changedTouches[0].pageX - window.pageXOffset, e.originalEvent.changedTouches[0].pageY - window.pageYOffset);
				}
				if(vakata_dnd.is_drag) { $.vakata.dnd.stop({}); }
				try {
					e.currentTarget.unselectable = "on";
					e.currentTarget.onselectstart = function() { return false; };
					if(e.currentTarget.style) {
						e.currentTarget.style.touchAction = "none";
						e.currentTarget.style.msTouchAction = "none";
						e.currentTarget.style.MozUserSelect = "none";
					}
				} catch(ignore) { }
				vakata_dnd.init_x	= e.pageX;
				vakata_dnd.init_y	= e.pageY;
				vakata_dnd.data		= data;
				vakata_dnd.is_down	= true;
				vakata_dnd.element	= e.currentTarget;
				vakata_dnd.target	= e.target;
				vakata_dnd.is_touch	= e.type === "touchstart";
				if(html !== false) {
					vakata_dnd.helper = $("<div id='vakata-dnd'></div>").html(html).css({
						"display"		: "block",
						"margin"		: "0",
						"padding"		: "0",
						"position"		: "absolute",
						"top"			: "-2000px",
						"lineHeight"	: "16px",
						"zIndex"		: "10000"
					});
				}
				$(document).on("mousemove.vakata.jstree touchmove.vakata.jstree", $.vakata.dnd.drag);
				$(document).on("mouseup.vakata.jstree touchend.vakata.jstree", $.vakata.dnd.stop);
				return false;
			},
			drag : function (e) {
				if(e.type === "touchmove" && e.originalEvent && e.originalEvent.changedTouches && e.originalEvent.changedTouches[0]) {
					e.pageX = e.originalEvent.changedTouches[0].pageX;
					e.pageY = e.originalEvent.changedTouches[0].pageY;
					e.target = document.elementFromPoint(e.originalEvent.changedTouches[0].pageX - window.pageXOffset, e.originalEvent.changedTouches[0].pageY - window.pageYOffset);
				}
				if(!vakata_dnd.is_down) { return; }
				if(!vakata_dnd.is_drag) {
					if(
						Math.abs(e.pageX - vakata_dnd.init_x) > (vakata_dnd.is_touch ? $.vakata.dnd.settings.threshold_touch : $.vakata.dnd.settings.threshold) ||
						Math.abs(e.pageY - vakata_dnd.init_y) > (vakata_dnd.is_touch ? $.vakata.dnd.settings.threshold_touch : $.vakata.dnd.settings.threshold)
					) {
						if(vakata_dnd.helper) {
							vakata_dnd.helper.appendTo(document.body);
							vakata_dnd.helper_w = vakata_dnd.helper.outerWidth();
						}
						vakata_dnd.is_drag = true;
						$(vakata_dnd.target).one('click.vakata', false);
						/**
						 * triggered on the document when a drag starts
						 * @event
						 * @plugin dnd
						 * @name dnd_start.vakata
						 * @param {Mixed} data any data supplied with the call to $.vakata.dnd.start
						 * @param {DOM} element the DOM element being dragged
						 * @param {jQuery} helper the helper shown next to the mouse
						 * @param {Object} event the event that caused the start (probably mousemove)
						 */
						$.vakata.dnd._trigger("start", e);
					}
					else { return; }
				}

				var d  = false, w  = false,
					dh = false, wh = false,
					dw = false, ww = false,
					dt = false, dl = false,
					ht = false, hl = false;

				vakata_dnd.scroll_t = 0;
				vakata_dnd.scroll_l = 0;
				vakata_dnd.scroll_e = false;
				$($(e.target).parentsUntil("body").addBack().get().reverse())
					.filter(function () {
						return	(/^auto|scroll$/).test($(this).css("overflow")) &&
								(this.scrollHeight > this.offsetHeight || this.scrollWidth > this.offsetWidth);
					})
					.each(function () {
						var t = $(this), o = t.offset();
						if(this.scrollHeight > this.offsetHeight) {
							if(o.top + t.height() - e.pageY < $.vakata.dnd.settings.scroll_proximity)	{ vakata_dnd.scroll_t = 1; }
							if(e.pageY - o.top < $.vakata.dnd.settings.scroll_proximity)				{ vakata_dnd.scroll_t = -1; }
						}
						if(this.scrollWidth > this.offsetWidth) {
							if(o.left + t.width() - e.pageX < $.vakata.dnd.settings.scroll_proximity)	{ vakata_dnd.scroll_l = 1; }
							if(e.pageX - o.left < $.vakata.dnd.settings.scroll_proximity)				{ vakata_dnd.scroll_l = -1; }
						}
						if(vakata_dnd.scroll_t || vakata_dnd.scroll_l) {
							vakata_dnd.scroll_e = $(this);
							return false;
						}
					});

				if(!vakata_dnd.scroll_e) {
					d  = $(document); w = $(window);
					dh = d.height(); wh = w.height();
					dw = d.width(); ww = w.width();
					dt = d.scrollTop(); dl = d.scrollLeft();
					if(dh > wh && e.pageY - dt < $.vakata.dnd.settings.scroll_proximity)		{ vakata_dnd.scroll_t = -1;  }
					if(dh > wh && wh - (e.pageY - dt) < $.vakata.dnd.settings.scroll_proximity)	{ vakata_dnd.scroll_t = 1; }
					if(dw > ww && e.pageX - dl < $.vakata.dnd.settings.scroll_proximity)		{ vakata_dnd.scroll_l = -1; }
					if(dw > ww && ww - (e.pageX - dl) < $.vakata.dnd.settings.scroll_proximity)	{ vakata_dnd.scroll_l = 1; }
					if(vakata_dnd.scroll_t || vakata_dnd.scroll_l) {
						vakata_dnd.scroll_e = d;
					}
				}
				if(vakata_dnd.scroll_e) { $.vakata.dnd._scroll(true); }

				if(vakata_dnd.helper) {
					ht = parseInt(e.pageY + $.vakata.dnd.settings.helper_top, 10);
					hl = parseInt(e.pageX + $.vakata.dnd.settings.helper_left, 10);
					if(dh && ht + 25 > dh) { ht = dh - 50; }
					if(dw && hl + vakata_dnd.helper_w > dw) { hl = dw - (vakata_dnd.helper_w + 2); }
					vakata_dnd.helper.css({
						left	: hl + "px",
						top		: ht + "px"
					});
				}
				/**
				 * triggered on the document when a drag is in progress
				 * @event
				 * @plugin dnd
				 * @name dnd_move.vakata
				 * @param {Mixed} data any data supplied with the call to $.vakata.dnd.start
				 * @param {DOM} element the DOM element being dragged
				 * @param {jQuery} helper the helper shown next to the mouse
				 * @param {Object} event the event that caused this to trigger (most likely mousemove)
				 */
				$.vakata.dnd._trigger("move", e);
				return false;
			},
			stop : function (e) {
				if(e.type === "touchend" && e.originalEvent && e.originalEvent.changedTouches && e.originalEvent.changedTouches[0]) {
					e.pageX = e.originalEvent.changedTouches[0].pageX;
					e.pageY = e.originalEvent.changedTouches[0].pageY;
					e.target = document.elementFromPoint(e.originalEvent.changedTouches[0].pageX - window.pageXOffset, e.originalEvent.changedTouches[0].pageY - window.pageYOffset);
				}
				if(vakata_dnd.is_drag) {
					/**
					 * triggered on the document when a drag stops (the dragged element is dropped)
					 * @event
					 * @plugin dnd
					 * @name dnd_stop.vakata
					 * @param {Mixed} data any data supplied with the call to $.vakata.dnd.start
					 * @param {DOM} element the DOM element being dragged
					 * @param {jQuery} helper the helper shown next to the mouse
					 * @param {Object} event the event that caused the stop
					 */
					if (e.target !== vakata_dnd.target) {
						$(vakata_dnd.target).off('click.vakata');
					}
					$.vakata.dnd._trigger("stop", e);
				}
				else {
					if(e.type === "touchend" && e.target === vakata_dnd.target) {
						var to = setTimeout(function () { $(e.target).click(); }, 100);
						$(e.target).one('click', function() { if(to) { clearTimeout(to); } });
					}
				}
				$.vakata.dnd._clean();
				return false;
			}
		};
	}($));

	// include the dnd plugin by default
	// $.jstree.defaults.plugins.push("dnd");


/**
 * ### Massload plugin
 *
 * Adds massload functionality to jsTree, so that multiple nodes can be loaded in a single request (only useful with lazy loading).
 */

	/**
	 * massload configuration
	 *
	 * It is possible to set this to a standard jQuery-like AJAX config.
	 * In addition to the standard jQuery ajax options here you can supply functions for `data` and `url`, the functions will be run in the current instance's scope and a param will be passed indicating which node IDs need to be loaded, the return value of those functions will be used.
	 *
	 * You can also set this to a function, that function will receive the node IDs being loaded as argument and a second param which is a function (callback) which should be called with the result.
	 *
	 * Both the AJAX and the function approach rely on the same return value - an object where the keys are the node IDs, and the value is the children of that node as an array.
	 *
	 *	{
	 *		"id1" : [{ "text" : "Child of ID1", "id" : "c1" }, { "text" : "Another child of ID1", "id" : "c2" }],
	 *		"id2" : [{ "text" : "Child of ID2", "id" : "c3" }]
	 *	}
	 * 
	 * @name $.jstree.defaults.massload
	 * @plugin massload
	 */
	$.jstree.defaults.massload = null;
	$.jstree.plugins.massload = function (options, parent) {
		this.init = function (el, options) {
			this._data.massload = {};
			parent.init.call(this, el, options);
		};
		this._load_nodes = function (nodes, callback, is_callback, force_reload) {
			var s = this.settings.massload,
				nodesString = JSON.stringify(nodes),
				toLoad = [],
				m = this._model.data,
				i, j, dom;
			if (!is_callback) {
				for(i = 0, j = nodes.length; i < j; i++) {
					if(!m[nodes[i]] || ( (!m[nodes[i]].state.loaded && !m[nodes[i]].state.failed) || force_reload) ) {
						toLoad.push(nodes[i]);
						dom = this.get_node(nodes[i], true);
						if (dom && dom.length) {
							dom.addClass("jstree-loading").attr('aria-busy',true);
						}
					}
				}
				this._data.massload = {};
				if (toLoad.length) {
					if($.isFunction(s)) {
						return s.call(this, toLoad, $.proxy(function (data) {
							var i, j;
							if(data) {
								for(i in data) {
									if(data.hasOwnProperty(i)) {
										this._data.massload[i] = data[i];
									}
								}
							}
							for(i = 0, j = nodes.length; i < j; i++) {
								dom = this.get_node(nodes[i], true);
								if (dom && dom.length) {
									dom.removeClass("jstree-loading").attr('aria-busy',false);
								}
							}
							parent._load_nodes.call(this, nodes, callback, is_callback, force_reload);
						}, this));
					}
					if(typeof s === 'object' && s && s.url) {
						s = $.extend(true, {}, s);
						if($.isFunction(s.url)) {
							s.url = s.url.call(this, toLoad);
						}
						if($.isFunction(s.data)) {
							s.data = s.data.call(this, toLoad);
						}
						return $.ajax(s)
							.done($.proxy(function (data,t,x) {
									var i, j;
									if(data) {
										for(i in data) {
											if(data.hasOwnProperty(i)) {
												this._data.massload[i] = data[i];
											}
										}
									}
									for(i = 0, j = nodes.length; i < j; i++) {
										dom = this.get_node(nodes[i], true);
										if (dom && dom.length) {
											dom.removeClass("jstree-loading").attr('aria-busy',false);
										}
									}
									parent._load_nodes.call(this, nodes, callback, is_callback, force_reload);
								}, this))
							.fail($.proxy(function (f) {
									parent._load_nodes.call(this, nodes, callback, is_callback, force_reload);
								}, this));
					}
				}
			}
			return parent._load_nodes.call(this, nodes, callback, is_callback, force_reload);
		};
		this._load_node = function (obj, callback) {
			var data = this._data.massload[obj.id],
				rslt = null, dom;
			if(data) {
				rslt = this[typeof data === 'string' ? '_append_html_data' : '_append_json_data'](
					obj,
					typeof data === 'string' ? $($.parseHTML(data)).filter(function () { return this.nodeType !== 3; }) : data,
					function (status) { callback.call(this, status); }
				);
				dom = this.get_node(obj.id, true);
				if (dom && dom.length) {
					dom.removeClass("jstree-loading").attr('aria-busy',false);
				}
				delete this._data.massload[obj.id];
				return rslt;
			}
			return parent._load_node.call(this, obj, callback);
		};
	};

/**
 * ### Search plugin
 *
 * Adds search functionality to jsTree.
 */

	/**
	 * stores all defaults for the search plugin
	 * @name $.jstree.defaults.search
	 * @plugin search
	 */
	$.jstree.defaults.search = {
		/**
		 * a jQuery-like AJAX config, which jstree uses if a server should be queried for results.
		 *
		 * A `str` (which is the search string) parameter will be added with the request, an optional `inside` parameter will be added if the search is limited to a node id. The expected result is a JSON array with nodes that need to be opened so that matching nodes will be revealed.
		 * Leave this setting as `false` to not query the server. You can also set this to a function, which will be invoked in the instance's scope and receive 3 parameters - the search string, the callback to call with the array of nodes to load, and the optional node ID to limit the search to
		 * @name $.jstree.defaults.search.ajax
		 * @plugin search
		 */
		ajax : false,
		/**
		 * Indicates if the search should be fuzzy or not (should `chnd3` match `child node 3`). Default is `false`.
		 * @name $.jstree.defaults.search.fuzzy
		 * @plugin search
		 */
		fuzzy : false,
		/**
		 * Indicates if the search should be case sensitive. Default is `false`.
		 * @name $.jstree.defaults.search.case_sensitive
		 * @plugin search
		 */
		case_sensitive : false,
		/**
		 * Indicates if the tree should be filtered (by default) to show only matching nodes (keep in mind this can be a heavy on large trees in old browsers).
		 * This setting can be changed at runtime when calling the search method. Default is `false`.
		 * @name $.jstree.defaults.search.show_only_matches
		 * @plugin search
		 */
		show_only_matches : false,
		/**
		 * Indicates if the children of matched element are shown (when show_only_matches is true)
		 * This setting can be changed at runtime when calling the search method. Default is `false`.
		 * @name $.jstree.defaults.search.show_only_matches_children
		 * @plugin search
		 */
		show_only_matches_children : false,
		/**
		 * Indicates if all nodes opened to reveal the search result, should be closed when the search is cleared or a new search is performed. Default is `true`.
		 * @name $.jstree.defaults.search.close_opened_onclear
		 * @plugin search
		 */
		close_opened_onclear : true,
		/**
		 * Indicates if only leaf nodes should be included in search results. Default is `false`.
		 * @name $.jstree.defaults.search.search_leaves_only
		 * @plugin search
		 */
		search_leaves_only : false,
		/**
		 * If set to a function it wil be called in the instance's scope with two arguments - search string and node (where node will be every node in the structure, so use with caution).
		 * If the function returns a truthy value the node will be considered a match (it might not be displayed if search_only_leaves is set to true and the node is not a leaf). Default is `false`.
		 * @name $.jstree.defaults.search.search_callback
		 * @plugin search
		 */
		search_callback : false
	};

	$.jstree.plugins.search = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);

			this._data.search.str = "";
			this._data.search.dom = $();
			this._data.search.res = [];
			this._data.search.opn = [];
			this._data.search.som = false;
			this._data.search.smc = false;
			this._data.search.hdn = [];

			this.element
				.on("search.jstree", $.proxy(function (e, data) {
						if(this._data.search.som && data.res.length) {
							var m = this._model.data, i, j, p = [], k, l;
							for(i = 0, j = data.res.length; i < j; i++) {
								if(m[data.res[i]] && !m[data.res[i]].state.hidden) {
									p.push(data.res[i]);
									p = p.concat(m[data.res[i]].parents);
									if(this._data.search.smc) {
										for (k = 0, l = m[data.res[i]].children_d.length; k < l; k++) {
											if (m[m[data.res[i]].children_d[k]] && !m[m[data.res[i]].children_d[k]].state.hidden) {
												p.push(m[data.res[i]].children_d[k]);
											}
										}
									}
								}
							}
							p = $.vakata.array_remove_item($.vakata.array_unique(p), $.jstree.root);
							this._data.search.hdn = this.hide_all(true);
							this.show_node(p, true);
							this.redraw(true);
						}
					}, this))
				.on("clear_search.jstree", $.proxy(function (e, data) {
						if(this._data.search.som && data.res.length) {
							this.show_node(this._data.search.hdn, true);
							this.redraw(true);
						}
					}, this));
		};
		/**
		 * used to search the tree nodes for a given string
		 * @name search(str [, skip_async])
		 * @param {String} str the search string
		 * @param {Boolean} skip_async if set to true server will not be queried even if configured
		 * @param {Boolean} show_only_matches if set to true only matching nodes will be shown (keep in mind this can be very slow on large trees or old browsers)
		 * @param {mixed} inside an optional node to whose children to limit the search
		 * @param {Boolean} append if set to true the results of this search are appended to the previous search
		 * @plugin search
		 * @trigger search.jstree
		 */
		this.search = function (str, skip_async, show_only_matches, inside, append, show_only_matches_children) {
			if(str === false || $.trim(str.toString()) === "") {
				return this.clear_search();
			}
			inside = this.get_node(inside);
			inside = inside && inside.id ? inside.id : null;
			str = str.toString();
			var s = this.settings.search,
				a = s.ajax ? s.ajax : false,
				m = this._model.data,
				f = null,
				r = [],
				p = [], i, j;
			if(this._data.search.res.length && !append) {
				this.clear_search();
			}
			if(show_only_matches === undefined) {
				show_only_matches = s.show_only_matches;
			}
			if(show_only_matches_children === undefined) {
				show_only_matches_children = s.show_only_matches_children;
			}
			if(!skip_async && a !== false) {
				if($.isFunction(a)) {
					return a.call(this, str, $.proxy(function (d) {
							if(d && d.d) { d = d.d; }
							this._load_nodes(!$.isArray(d) ? [] : $.vakata.array_unique(d), function () {
								this.search(str, true, show_only_matches, inside, append, show_only_matches_children);
							});
						}, this), inside);
				}
				else {
					a = $.extend({}, a);
					if(!a.data) { a.data = {}; }
					a.data.str = str;
					if(inside) {
						a.data.inside = inside;
					}
					if (this._data.search.lastRequest) {
						this._data.search.lastRequest.abort();
					}
					this._data.search.lastRequest = $.ajax(a)
						.fail($.proxy(function () {
							this._data.core.last_error = { 'error' : 'ajax', 'plugin' : 'search', 'id' : 'search_01', 'reason' : 'Could not load search parents', 'data' : JSON.stringify(a) };
							this.settings.core.error.call(this, this._data.core.last_error);
						}, this))
						.done($.proxy(function (d) {
							if(d && d.d) { d = d.d; }
							this._load_nodes(!$.isArray(d) ? [] : $.vakata.array_unique(d), function () {
								this.search(str, true, show_only_matches, inside, append, show_only_matches_children);
							});
						}, this));
					return this._data.search.lastRequest;
				}
			}
			if(!append) {
				this._data.search.str = str;
				this._data.search.dom = $();
				this._data.search.res = [];
				this._data.search.opn = [];
				this._data.search.som = show_only_matches;
				this._data.search.smc = show_only_matches_children;
			}

			f = new $.vakata.search(str, true, { caseSensitive : s.case_sensitive, fuzzy : s.fuzzy });
			$.each(m[inside ? inside : $.jstree.root].children_d, function (ii, i) {
				var v = m[i];
				if(v.text && !v.state.hidden && (!s.search_leaves_only || (v.state.loaded && v.children.length === 0)) && ( (s.search_callback && s.search_callback.call(this, str, v)) || (!s.search_callback && f.search(v.text).isMatch) ) ) {
					r.push(i);
					p = p.concat(v.parents);
				}
			});
			if(r.length) {
				p = $.vakata.array_unique(p);
				for(i = 0, j = p.length; i < j; i++) {
					if(p[i] !== $.jstree.root && m[p[i]] && this.open_node(p[i], null, 0) === true) {
						this._data.search.opn.push(p[i]);
					}
				}
				if(!append) {
					this._data.search.dom = $(this.element[0].querySelectorAll('#' + $.map(r, function (v) { return "0123456789".indexOf(v[0]) !== -1 ? '\\3' + v[0] + ' ' + v.substr(1).replace($.jstree.idregex,'\\$&') : v.replace($.jstree.idregex,'\\$&'); }).join(', #')));
					this._data.search.res = r;
				}
				else {
					this._data.search.dom = this._data.search.dom.add($(this.element[0].querySelectorAll('#' + $.map(r, function (v) { return "0123456789".indexOf(v[0]) !== -1 ? '\\3' + v[0] + ' ' + v.substr(1).replace($.jstree.idregex,'\\$&') : v.replace($.jstree.idregex,'\\$&'); }).join(', #'))));
					this._data.search.res = $.vakata.array_unique(this._data.search.res.concat(r));
				}
				this._data.search.dom.children(".jstree-anchor").addClass('jstree-search');
			}
			/**
			 * triggered after search is complete
			 * @event
			 * @name search.jstree
			 * @param {jQuery} nodes a jQuery collection of matching nodes
			 * @param {String} str the search string
			 * @param {Array} res a collection of objects represeing the matching nodes
			 * @plugin search
			 */
			this.trigger('search', { nodes : this._data.search.dom, str : str, res : this._data.search.res, show_only_matches : show_only_matches });
		};
		/**
		 * used to clear the last search (removes classes and shows all nodes if filtering is on)
		 * @name clear_search()
		 * @plugin search
		 * @trigger clear_search.jstree
		 */
		this.clear_search = function () {
			if(this.settings.search.close_opened_onclear) {
				this.close_node(this._data.search.opn, 0);
			}
			/**
			 * triggered after search is complete
			 * @event
			 * @name clear_search.jstree
			 * @param {jQuery} nodes a jQuery collection of matching nodes (the result from the last search)
			 * @param {String} str the search string (the last search string)
			 * @param {Array} res a collection of objects represeing the matching nodes (the result from the last search)
			 * @plugin search
			 */
			this.trigger('clear_search', { 'nodes' : this._data.search.dom, str : this._data.search.str, res : this._data.search.res });
			if(this._data.search.res.length) {
				this._data.search.dom = $(this.element[0].querySelectorAll('#' + $.map(this._data.search.res, function (v) {
					return "0123456789".indexOf(v[0]) !== -1 ? '\\3' + v[0] + ' ' + v.substr(1).replace($.jstree.idregex,'\\$&') : v.replace($.jstree.idregex,'\\$&');
				}).join(', #')));
				this._data.search.dom.children(".jstree-anchor").removeClass("jstree-search");
			}
			this._data.search.str = "";
			this._data.search.res = [];
			this._data.search.opn = [];
			this._data.search.dom = $();
		};

		this.redraw_node = function(obj, deep, callback, force_render) {
			obj = parent.redraw_node.apply(this, arguments);
			if(obj) {
				if($.inArray(obj.id, this._data.search.res) !== -1) {
					var i, j, tmp = null;
					for(i = 0, j = obj.childNodes.length; i < j; i++) {
						if(obj.childNodes[i] && obj.childNodes[i].className && obj.childNodes[i].className.indexOf("jstree-anchor") !== -1) {
							tmp = obj.childNodes[i];
							break;
						}
					}
					if(tmp) {
						tmp.className += ' jstree-search';
					}
				}
			}
			return obj;
		};
	};

	// helpers
	(function ($) {
		// from http://kiro.me/projects/fuse.html
		$.vakata.search = function(pattern, txt, options) {
			options = options || {};
			options = $.extend({}, $.vakata.search.defaults, options);
			if(options.fuzzy !== false) {
				options.fuzzy = true;
			}
			pattern = options.caseSensitive ? pattern : pattern.toLowerCase();
			var MATCH_LOCATION	= options.location,
				MATCH_DISTANCE	= options.distance,
				MATCH_THRESHOLD	= options.threshold,
				patternLen = pattern.length,
				matchmask, pattern_alphabet, match_bitapScore, search;
			if(patternLen > 32) {
				options.fuzzy = false;
			}
			if(options.fuzzy) {
				matchmask = 1 << (patternLen - 1);
				pattern_alphabet = (function () {
					var mask = {},
						i = 0;
					for (i = 0; i < patternLen; i++) {
						mask[pattern.charAt(i)] = 0;
					}
					for (i = 0; i < patternLen; i++) {
						mask[pattern.charAt(i)] |= 1 << (patternLen - i - 1);
					}
					return mask;
				}());
				match_bitapScore = function (e, x) {
					var accuracy = e / patternLen,
						proximity = Math.abs(MATCH_LOCATION - x);
					if(!MATCH_DISTANCE) {
						return proximity ? 1.0 : accuracy;
					}
					return accuracy + (proximity / MATCH_DISTANCE);
				};
			}
			search = function (text) {
				text = options.caseSensitive ? text : text.toLowerCase();
				if(pattern === text || text.indexOf(pattern) !== -1) {
					return {
						isMatch: true,
						score: 0
					};
				}
				if(!options.fuzzy) {
					return {
						isMatch: false,
						score: 1
					};
				}
				var i, j,
					textLen = text.length,
					scoreThreshold = MATCH_THRESHOLD,
					bestLoc = text.indexOf(pattern, MATCH_LOCATION),
					binMin, binMid,
					binMax = patternLen + textLen,
					lastRd, start, finish, rd, charMatch,
					score = 1,
					locations = [];
				if (bestLoc !== -1) {
					scoreThreshold = Math.min(match_bitapScore(0, bestLoc), scoreThreshold);
					bestLoc = text.lastIndexOf(pattern, MATCH_LOCATION + patternLen);
					if (bestLoc !== -1) {
						scoreThreshold = Math.min(match_bitapScore(0, bestLoc), scoreThreshold);
					}
				}
				bestLoc = -1;
				for (i = 0; i < patternLen; i++) {
					binMin = 0;
					binMid = binMax;
					while (binMin < binMid) {
						if (match_bitapScore(i, MATCH_LOCATION + binMid) <= scoreThreshold) {
							binMin = binMid;
						} else {
							binMax = binMid;
						}
						binMid = Math.floor((binMax - binMin) / 2 + binMin);
					}
					binMax = binMid;
					start = Math.max(1, MATCH_LOCATION - binMid + 1);
					finish = Math.min(MATCH_LOCATION + binMid, textLen) + patternLen;
					rd = new Array(finish + 2);
					rd[finish + 1] = (1 << i) - 1;
					for (j = finish; j >= start; j--) {
						charMatch = pattern_alphabet[text.charAt(j - 1)];
						if (i === 0) {
							rd[j] = ((rd[j + 1] << 1) | 1) & charMatch;
						} else {
							rd[j] = ((rd[j + 1] << 1) | 1) & charMatch | (((lastRd[j + 1] | lastRd[j]) << 1) | 1) | lastRd[j + 1];
						}
						if (rd[j] & matchmask) {
							score = match_bitapScore(i, j - 1);
							if (score <= scoreThreshold) {
								scoreThreshold = score;
								bestLoc = j - 1;
								locations.push(bestLoc);
								if (bestLoc > MATCH_LOCATION) {
									start = Math.max(1, 2 * MATCH_LOCATION - bestLoc);
								} else {
									break;
								}
							}
						}
					}
					if (match_bitapScore(i + 1, MATCH_LOCATION) > scoreThreshold) {
						break;
					}
					lastRd = rd;
				}
				return {
					isMatch: bestLoc >= 0,
					score: score
				};
			};
			return txt === true ? { 'search' : search } : search(txt);
		};
		$.vakata.search.defaults = {
			location : 0,
			distance : 100,
			threshold : 0.6,
			fuzzy : false,
			caseSensitive : false
		};
	}($));

	// include the search plugin by default
	// $.jstree.defaults.plugins.push("search");


/**
 * ### Sort plugin
 *
 * Automatically sorts all siblings in the tree according to a sorting function.
 */

	/**
	 * the settings function used to sort the nodes.
	 * It is executed in the tree's context, accepts two nodes as arguments and should return `1` or `-1`.
	 * @name $.jstree.defaults.sort
	 * @plugin sort
	 */
	$.jstree.defaults.sort = function (a, b) {
		//return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : this.get_type(a) >= this.get_type(b);
		return this.get_text(a) > this.get_text(b) ? 1 : -1;
	};
	$.jstree.plugins.sort = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);
			this.element
				.on("model.jstree", $.proxy(function (e, data) {
						this.sort(data.parent, true);
					}, this))
				.on("rename_node.jstree create_node.jstree", $.proxy(function (e, data) {
						this.sort(data.parent || data.node.parent, false);
						this.redraw_node(data.parent || data.node.parent, true);
					}, this))
				.on("move_node.jstree copy_node.jstree", $.proxy(function (e, data) {
						this.sort(data.parent, false);
						this.redraw_node(data.parent, true);
					}, this));
		};
		/**
		 * used to sort a node's children
		 * @private
		 * @name sort(obj [, deep])
		 * @param  {mixed} obj the node
		 * @param {Boolean} deep if set to `true` nodes are sorted recursively.
		 * @plugin sort
		 * @trigger search.jstree
		 */
		this.sort = function (obj, deep) {
			var i, j;
			obj = this.get_node(obj);
			if(obj && obj.children && obj.children.length) {
				obj.children.sort($.proxy(this.settings.sort, this));
				if(deep) {
					for(i = 0, j = obj.children_d.length; i < j; i++) {
						this.sort(obj.children_d[i], false);
					}
				}
			}
		};
	};

	// include the sort plugin by default
	// $.jstree.defaults.plugins.push("sort");

/**
 * ### State plugin
 *
 * Saves the state of the tree (selected nodes, opened nodes) on the user's computer using available options (localStorage, cookies, etc)
 */

	var to = false;
	/**
	 * stores all defaults for the state plugin
	 * @name $.jstree.defaults.state
	 * @plugin state
	 */
	$.jstree.defaults.state = {
		/**
		 * A string for the key to use when saving the current tree (change if using multiple trees in your project). Defaults to `jstree`.
		 * @name $.jstree.defaults.state.key
		 * @plugin state
		 */
		key		: 'jstree',
		/**
		 * A space separated list of events that trigger a state save. Defaults to `changed.jstree open_node.jstree close_node.jstree`.
		 * @name $.jstree.defaults.state.events
		 * @plugin state
		 */
		events	: 'changed.jstree open_node.jstree close_node.jstree check_node.jstree uncheck_node.jstree',
		/**
		 * Time in milliseconds after which the state will expire. Defaults to 'false' meaning - no expire.
		 * @name $.jstree.defaults.state.ttl
		 * @plugin state
		 */
		ttl		: false,
		/**
		 * A function that will be executed prior to restoring state with one argument - the state object. Can be used to clear unwanted parts of the state.
		 * @name $.jstree.defaults.state.filter
		 * @plugin state
		 */
		filter	: false,
		/**
		 * Should loaded nodes be restored (setting this to true means that it is possible that the whole tree will be loaded for some users - use with caution). Defaults to `false`
		 * @name $.jstree.defaults.state.preserve_loaded
		 * @plugin state
		 */
		preserve_loaded : false
	};
	$.jstree.plugins.state = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);
			var bind = $.proxy(function () {
				this.element.on(this.settings.state.events, $.proxy(function () {
					if(to) { clearTimeout(to); }
					to = setTimeout($.proxy(function () { this.save_state(); }, this), 100);
				}, this));
				/**
				 * triggered when the state plugin is finished restoring the state (and immediately after ready if there is no state to restore).
				 * @event
				 * @name state_ready.jstree
				 * @plugin state
				 */
				this.trigger('state_ready');
			}, this);
			this.element
				.on("ready.jstree", $.proxy(function (e, data) {
						this.element.one("restore_state.jstree", bind);
						if(!this.restore_state()) { bind(); }
					}, this));
		};
		/**
		 * save the state
		 * @name save_state()
		 * @plugin state
		 */
		this.save_state = function () {
			var tm = this.get_state();
			if (!this.settings.state.preserve_loaded) {
				delete tm.core.loaded;
			}
			var st = { 'state' : tm, 'ttl' : this.settings.state.ttl, 'sec' : +(new Date()) };
			$.vakata.storage.set(this.settings.state.key, JSON.stringify(st));
		};
		/**
		 * restore the state from the user's computer
		 * @name restore_state()
		 * @plugin state
		 */
		this.restore_state = function () {
			var k = $.vakata.storage.get(this.settings.state.key);
			if(!!k) { try { k = JSON.parse(k); } catch(ex) { return false; } }
			if(!!k && k.ttl && k.sec && +(new Date()) - k.sec > k.ttl) { return false; }
			if(!!k && k.state) { k = k.state; }
			if(!!k && $.isFunction(this.settings.state.filter)) { k = this.settings.state.filter.call(this, k); }
			if(!!k) {
				if (!this.settings.state.preserve_loaded) {
					delete k.core.loaded;
				}
				this.element.one("set_state.jstree", function (e, data) { data.instance.trigger('restore_state', { 'state' : $.extend(true, {}, k) }); });
				this.set_state(k);
				return true;
			}
			return false;
		};
		/**
		 * clear the state on the user's computer
		 * @name clear_state()
		 * @plugin state
		 */
		this.clear_state = function () {
			return $.vakata.storage.del(this.settings.state.key);
		};
	};

	(function ($, undefined) {
		$.vakata.storage = {
			// simply specifying the functions in FF throws an error
			set : function (key, val) { return window.localStorage.setItem(key, val); },
			get : function (key) { return window.localStorage.getItem(key); },
			del : function (key) { return window.localStorage.removeItem(key); }
		};
	}($));

	// include the state plugin by default
	// $.jstree.defaults.plugins.push("state");

/**
 * ### Types plugin
 *
 * Makes it possible to add predefined types for groups of nodes, which make it possible to easily control nesting rules and icon for each group.
 */

	/**
	 * An object storing all types as key value pairs, where the key is the type name and the value is an object that could contain following keys (all optional).
	 *
	 * * `max_children` the maximum number of immediate children this node type can have. Do not specify or set to `-1` for unlimited.
	 * * `max_depth` the maximum number of nesting this node type can have. A value of `1` would mean that the node can have children, but no grandchildren. Do not specify or set to `-1` for unlimited.
	 * * `valid_children` an array of node type strings, that nodes of this type can have as children. Do not specify or set to `-1` for no limits.
	 * * `icon` a string - can be a path to an icon or a className, if using an image that is in the current directory use a `./` prefix, otherwise it will be detected as a class. Omit to use the default icon from your theme.
	 * * `li_attr` an object of values which will be used to add HTML attributes on the resulting LI DOM node (merged with the node's own data)
	 * * `a_attr` an object of values which will be used to add HTML attributes on the resulting A DOM node (merged with the node's own data)
	 *
	 * There are two predefined types:
	 *
	 * * `#` represents the root of the tree, for example `max_children` would control the maximum number of root nodes.
	 * * `default` represents the default node - any settings here will be applied to all nodes that do not have a type specified.
	 *
	 * @name $.jstree.defaults.types
	 * @plugin types
	 */
	$.jstree.defaults.types = {
		'default' : {}
	};
	$.jstree.defaults.types[$.jstree.root] = {};

	$.jstree.plugins.types = function (options, parent) {
		this.init = function (el, options) {
			var i, j;
			if(options && options.types && options.types['default']) {
				for(i in options.types) {
					if(i !== "default" && i !== $.jstree.root && options.types.hasOwnProperty(i)) {
						for(j in options.types['default']) {
							if(options.types['default'].hasOwnProperty(j) && options.types[i][j] === undefined) {
								options.types[i][j] = options.types['default'][j];
							}
						}
					}
				}
			}
			parent.init.call(this, el, options);
			this._model.data[$.jstree.root].type = $.jstree.root;
		};
		this.refresh = function (skip_loading, forget_state) {
			parent.refresh.call(this, skip_loading, forget_state);
			this._model.data[$.jstree.root].type = $.jstree.root;
		};
		this.bind = function () {
			this.element
				.on('model.jstree', $.proxy(function (e, data) {
						var m = this._model.data,
							dpc = data.nodes,
							t = this.settings.types,
							i, j, c = 'default', k;
						for(i = 0, j = dpc.length; i < j; i++) {
							c = 'default';
							if(m[dpc[i]].original && m[dpc[i]].original.type && t[m[dpc[i]].original.type]) {
								c = m[dpc[i]].original.type;
							}
							if(m[dpc[i]].data && m[dpc[i]].data.jstree && m[dpc[i]].data.jstree.type && t[m[dpc[i]].data.jstree.type]) {
								c = m[dpc[i]].data.jstree.type;
							}
							m[dpc[i]].type = c;
							if(m[dpc[i]].icon === true && t[c].icon !== undefined) {
								m[dpc[i]].icon = t[c].icon;
							}
							if(t[c].li_attr !== undefined && typeof t[c].li_attr === 'object') {
								for (k in t[c].li_attr) {
									if (t[c].li_attr.hasOwnProperty(k)) {
										if (k === 'id') {
											continue;
										}
										else if (m[dpc[i]].li_attr[k] === undefined) {
											m[dpc[i]].li_attr[k] = t[c].li_attr[k];
										}
										else if (k === 'class') {
											m[dpc[i]].li_attr['class'] = t[c].li_attr['class'] + ' ' + m[dpc[i]].li_attr['class'];
										}
									}
								}
							}
							if(t[c].a_attr !== undefined && typeof t[c].a_attr === 'object') {
								for (k in t[c].a_attr) {
									if (t[c].a_attr.hasOwnProperty(k)) {
										if (k === 'id') {
											continue;
										}
										else if (m[dpc[i]].a_attr[k] === undefined) {
											m[dpc[i]].a_attr[k] = t[c].a_attr[k];
										}
										else if (k === 'href' && m[dpc[i]].a_attr[k] === '#') {
											m[dpc[i]].a_attr['href'] = t[c].a_attr['href'];
										}
										else if (k === 'class') {
											m[dpc[i]].a_attr['class'] = t[c].a_attr['class'] + ' ' + m[dpc[i]].a_attr['class'];
										}
									}
								}
							}
						}
						m[$.jstree.root].type = $.jstree.root;
					}, this));
			parent.bind.call(this);
		};
		this.get_json = function (obj, options, flat) {
			var i, j,
				m = this._model.data,
				opt = options ? $.extend(true, {}, options, {no_id:false}) : {},
				tmp = parent.get_json.call(this, obj, opt, flat);
			if(tmp === false) { return false; }
			if($.isArray(tmp)) {
				for(i = 0, j = tmp.length; i < j; i++) {
					tmp[i].type = tmp[i].id && m[tmp[i].id] && m[tmp[i].id].type ? m[tmp[i].id].type : "default";
					if(options && options.no_id) {
						delete tmp[i].id;
						if(tmp[i].li_attr && tmp[i].li_attr.id) {
							delete tmp[i].li_attr.id;
						}
						if(tmp[i].a_attr && tmp[i].a_attr.id) {
							delete tmp[i].a_attr.id;
						}
					}
				}
			}
			else {
				tmp.type = tmp.id && m[tmp.id] && m[tmp.id].type ? m[tmp.id].type : "default";
				if(options && options.no_id) {
					tmp = this._delete_ids(tmp);
				}
			}
			return tmp;
		};
		this._delete_ids = function (tmp) {
			if($.isArray(tmp)) {
				for(var i = 0, j = tmp.length; i < j; i++) {
					tmp[i] = this._delete_ids(tmp[i]);
				}
				return tmp;
			}
			delete tmp.id;
			if(tmp.li_attr && tmp.li_attr.id) {
				delete tmp.li_attr.id;
			}
			if(tmp.a_attr && tmp.a_attr.id) {
				delete tmp.a_attr.id;
			}
			if(tmp.children && $.isArray(tmp.children)) {
				tmp.children = this._delete_ids(tmp.children);
			}
			return tmp;
		};
		this.check = function (chk, obj, par, pos, more) {
			if(parent.check.call(this, chk, obj, par, pos, more) === false) { return false; }
			obj = obj && obj.id ? obj : this.get_node(obj);
			par = par && par.id ? par : this.get_node(par);
			var m = obj && obj.id ? (more && more.origin ? more.origin : $.jstree.reference(obj.id)) : null, tmp, d, i, j;
			m = m && m._model && m._model.data ? m._model.data : null;
			switch(chk) {
				case "create_node":
				case "move_node":
				case "copy_node":
					if(chk !== 'move_node' || $.inArray(obj.id, par.children) === -1) {
						tmp = this.get_rules(par);
						if(tmp.max_children !== undefined && tmp.max_children !== -1 && tmp.max_children === par.children.length) {
							this._data.core.last_error = { 'error' : 'check', 'plugin' : 'types', 'id' : 'types_01', 'reason' : 'max_children prevents function: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
							return false;
						}
						if(tmp.valid_children !== undefined && tmp.valid_children !== -1 && $.inArray((obj.type || 'default'), tmp.valid_children) === -1) {
							this._data.core.last_error = { 'error' : 'check', 'plugin' : 'types', 'id' : 'types_02', 'reason' : 'valid_children prevents function: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
							return false;
						}
						if(m && obj.children_d && obj.parents) {
							d = 0;
							for(i = 0, j = obj.children_d.length; i < j; i++) {
								d = Math.max(d, m[obj.children_d[i]].parents.length);
							}
							d = d - obj.parents.length + 1;
						}
						if(d <= 0 || d === undefined) { d = 1; }
						do {
							if(tmp.max_depth !== undefined && tmp.max_depth !== -1 && tmp.max_depth < d) {
								this._data.core.last_error = { 'error' : 'check', 'plugin' : 'types', 'id' : 'types_03', 'reason' : 'max_depth prevents function: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
								return false;
							}
							par = this.get_node(par.parent);
							tmp = this.get_rules(par);
							d++;
						} while(par);
					}
					break;
			}
			return true;
		};
		/**
		 * used to retrieve the type settings object for a node
		 * @name get_rules(obj)
		 * @param {mixed} obj the node to find the rules for
		 * @return {Object}
		 * @plugin types
		 */
		this.get_rules = function (obj) {
			obj = this.get_node(obj);
			if(!obj) { return false; }
			var tmp = this.get_type(obj, true);
			if(tmp.max_depth === undefined) { tmp.max_depth = -1; }
			if(tmp.max_children === undefined) { tmp.max_children = -1; }
			if(tmp.valid_children === undefined) { tmp.valid_children = -1; }
			return tmp;
		};
		/**
		 * used to retrieve the type string or settings object for a node
		 * @name get_type(obj [, rules])
		 * @param {mixed} obj the node to find the rules for
		 * @param {Boolean} rules if set to `true` instead of a string the settings object will be returned
		 * @return {String|Object}
		 * @plugin types
		 */
		this.get_type = function (obj, rules) {
			obj = this.get_node(obj);
			return (!obj) ? false : ( rules ? $.extend({ 'type' : obj.type }, this.settings.types[obj.type]) : obj.type);
		};
		/**
		 * used to change a node's type
		 * @name set_type(obj, type)
		 * @param {mixed} obj the node to change
		 * @param {String} type the new type
		 * @plugin types
		 */
		this.set_type = function (obj, type) {
			var m = this._model.data, t, t1, t2, old_type, old_icon, k, d, a;
			if($.isArray(obj)) {
				obj = obj.slice();
				for(t1 = 0, t2 = obj.length; t1 < t2; t1++) {
					this.set_type(obj[t1], type);
				}
				return true;
			}
			t = this.settings.types;
			obj = this.get_node(obj);
			if(!t[type] || !obj) { return false; }
			d = this.get_node(obj, true);
			if (d && d.length) {
				a = d.children('.jstree-anchor');
			}
			old_type = obj.type;
			old_icon = this.get_icon(obj);
			obj.type = type;
			if(old_icon === true || !t[old_type] || (t[old_type].icon !== undefined && old_icon === t[old_type].icon)) {
				this.set_icon(obj, t[type].icon !== undefined ? t[type].icon : true);
			}

			// remove old type props
			if(t[old_type] && t[old_type].li_attr !== undefined && typeof t[old_type].li_attr === 'object') {
				for (k in t[old_type].li_attr) {
					if (t[old_type].li_attr.hasOwnProperty(k)) {
						if (k === 'id') {
							continue;
						}
						else if (k === 'class') {
							m[obj.id].li_attr['class'] = (m[obj.id].li_attr['class'] || '').replace(t[old_type].li_attr[k], '');
							if (d) { d.removeClass(t[old_type].li_attr[k]); }
						}
						else if (m[obj.id].li_attr[k] === t[old_type].li_attr[k]) {
							m[obj.id].li_attr[k] = null;
							if (d) { d.removeAttr(k); }
						}
					}
				}
			}
			if(t[old_type] && t[old_type].a_attr !== undefined && typeof t[old_type].a_attr === 'object') {
				for (k in t[old_type].a_attr) {
					if (t[old_type].a_attr.hasOwnProperty(k)) {
						if (k === 'id') {
							continue;
						}
						else if (k === 'class') {
							m[obj.id].a_attr['class'] = (m[obj.id].a_attr['class'] || '').replace(t[old_type].a_attr[k], '');
							if (a) { a.removeClass(t[old_type].a_attr[k]); }
						}
						else if (m[obj.id].a_attr[k] === t[old_type].a_attr[k]) {
							if (k === 'href') {
								m[obj.id].a_attr[k] = '#';
								if (a) { a.attr('href', '#'); }
							}
							else {
								delete m[obj.id].a_attr[k];
								if (a) { a.removeAttr(k); }
							}
						}
					}
				}
			}

			// add new props
			if(t[type].li_attr !== undefined && typeof t[type].li_attr === 'object') {
				for (k in t[type].li_attr) {
					if (t[type].li_attr.hasOwnProperty(k)) {
						if (k === 'id') {
							continue;
						}
						else if (m[obj.id].li_attr[k] === undefined) {
							m[obj.id].li_attr[k] = t[type].li_attr[k];
							if (d) {
								if (k === 'class') {
									d.addClass(t[type].li_attr[k]);
								}
								else {
									d.attr(k, t[type].li_attr[k]);
								}
							}
						}
						else if (k === 'class') {
							m[obj.id].li_attr['class'] = t[type].li_attr[k] + ' ' + m[obj.id].li_attr['class'];
							if (d) { d.addClass(t[type].li_attr[k]); }
						}
					}
				}
			}
			if(t[type].a_attr !== undefined && typeof t[type].a_attr === 'object') {
				for (k in t[type].a_attr) {
					if (t[type].a_attr.hasOwnProperty(k)) {
						if (k === 'id') {
							continue;
						}
						else if (m[obj.id].a_attr[k] === undefined) {
							m[obj.id].a_attr[k] = t[type].a_attr[k];
							if (a) {
								if (k === 'class') {
									a.addClass(t[type].a_attr[k]);
								}
								else {
									a.attr(k, t[type].a_attr[k]);
								}
							}
						}
						else if (k === 'href' && m[obj.id].a_attr[k] === '#') {
							m[obj.id].a_attr['href'] = t[type].a_attr['href'];
							if (a) { a.attr('href', t[type].a_attr['href']); }
						}
						else if (k === 'class') {
							m[obj.id].a_attr['class'] = t[type].a_attr['class'] + ' ' + m[obj.id].a_attr['class'];
							if (a) { a.addClass(t[type].a_attr[k]); }
						}
					}
				}
			}

			return true;
		};
	};
	// include the types plugin by default
	// $.jstree.defaults.plugins.push("types");


/**
 * ### Unique plugin
 *
 * Enforces that no nodes with the same name can coexist as siblings.
 */

	/**
	 * stores all defaults for the unique plugin
	 * @name $.jstree.defaults.unique
	 * @plugin unique
	 */
	$.jstree.defaults.unique = {
		/**
		 * Indicates if the comparison should be case sensitive. Default is `false`.
		 * @name $.jstree.defaults.unique.case_sensitive
		 * @plugin unique
		 */
		case_sensitive : false,
		/**
		 * Indicates if white space should be trimmed before the comparison. Default is `false`.
		 * @name $.jstree.defaults.unique.trim_whitespace
		 * @plugin unique
		 */
		trim_whitespace : false,
		/**
		 * A callback executed in the instance's scope when a new node is created and the name is already taken, the two arguments are the conflicting name and the counter. The default will produce results like `New node (2)`.
		 * @name $.jstree.defaults.unique.duplicate
		 * @plugin unique
		 */
		duplicate : function (name, counter) {
			return name + ' (' + counter + ')';
		}
	};

	$.jstree.plugins.unique = function (options, parent) {
		this.check = function (chk, obj, par, pos, more) {
			if(parent.check.call(this, chk, obj, par, pos, more) === false) { return false; }
			obj = obj && obj.id ? obj : this.get_node(obj);
			par = par && par.id ? par : this.get_node(par);
			if(!par || !par.children) { return true; }
			var n = chk === "rename_node" ? pos : obj.text,
				c = [],
				s = this.settings.unique.case_sensitive,
				w = this.settings.unique.trim_whitespace,
				m = this._model.data, i, j, t;
			for(i = 0, j = par.children.length; i < j; i++) {
				t = m[par.children[i]].text;
				if (!s) {
					t = t.toLowerCase();
				}
				if (w) {
					t = t.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
				}
				c.push(t);
			}
			if(!s) { n = n.toLowerCase(); }
			if (w) { n = n.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, ''); }
			switch(chk) {
				case "delete_node":
					return true;
				case "rename_node":
					t = obj.text || '';
					if (!s) {
						t = t.toLowerCase();
					}
					if (w) {
						t = t.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
					}
					i = ($.inArray(n, c) === -1 || (obj.text && t === n));
					if(!i) {
						this._data.core.last_error = { 'error' : 'check', 'plugin' : 'unique', 'id' : 'unique_01', 'reason' : 'Child with name ' + n + ' already exists. Preventing: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
					}
					return i;
				case "create_node":
					i = ($.inArray(n, c) === -1);
					if(!i) {
						this._data.core.last_error = { 'error' : 'check', 'plugin' : 'unique', 'id' : 'unique_04', 'reason' : 'Child with name ' + n + ' already exists. Preventing: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
					}
					return i;
				case "copy_node":
					i = ($.inArray(n, c) === -1);
					if(!i) {
						this._data.core.last_error = { 'error' : 'check', 'plugin' : 'unique', 'id' : 'unique_02', 'reason' : 'Child with name ' + n + ' already exists. Preventing: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
					}
					return i;
				case "move_node":
					i = ( (obj.parent === par.id && (!more || !more.is_multi)) || $.inArray(n, c) === -1);
					if(!i) {
						this._data.core.last_error = { 'error' : 'check', 'plugin' : 'unique', 'id' : 'unique_03', 'reason' : 'Child with name ' + n + ' already exists. Preventing: ' + chk, 'data' : JSON.stringify({ 'chk' : chk, 'pos' : pos, 'obj' : obj && obj.id ? obj.id : false, 'par' : par && par.id ? par.id : false }) };
					}
					return i;
			}
			return true;
		};
		this.create_node = function (par, node, pos, callback, is_loaded) {
			if(!node || node.text === undefined) {
				if(par === null) {
					par = $.jstree.root;
				}
				par = this.get_node(par);
				if(!par) {
					return parent.create_node.call(this, par, node, pos, callback, is_loaded);
				}
				pos = pos === undefined ? "last" : pos;
				if(!pos.toString().match(/^(before|after)$/) && !is_loaded && !this.is_loaded(par)) {
					return parent.create_node.call(this, par, node, pos, callback, is_loaded);
				}
				if(!node) { node = {}; }
				var tmp, n, dpc, i, j, m = this._model.data, s = this.settings.unique.case_sensitive, w = this.settings.unique.trim_whitespace, cb = this.settings.unique.duplicate, t;
				n = tmp = this.get_string('New node');
				dpc = [];
				for(i = 0, j = par.children.length; i < j; i++) {
					t = m[par.children[i]].text;
					if (!s) {
						t = t.toLowerCase();
					}
					if (w) {
						t = t.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
					}
					dpc.push(t);
				}
				i = 1;
				t = n;
				if (!s) {
					t = t.toLowerCase();
				}
				if (w) {
					t = t.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
				}
				while($.inArray(t, dpc) !== -1) {
					n = cb.call(this, tmp, (++i)).toString();
					t = n;
					if (!s) {
						t = t.toLowerCase();
					}
					if (w) {
						t = t.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
					}
				}
				node.text = n;
			}
			return parent.create_node.call(this, par, node, pos, callback, is_loaded);
		};
	};

	// include the unique plugin by default
	// $.jstree.defaults.plugins.push("unique");


/**
 * ### Wholerow plugin
 *
 * Makes each node appear block level. Making selection easier. May cause slow down for large trees in old browsers.
 */

	var div = document.createElement('DIV');
	div.setAttribute('unselectable','on');
	div.setAttribute('role','presentation');
	div.className = 'jstree-wholerow';
	div.innerHTML = '&#160;';
	$.jstree.plugins.wholerow = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);

			this.element
				.on('ready.jstree set_state.jstree', $.proxy(function () {
						this.hide_dots();
					}, this))
				.on("init.jstree loading.jstree ready.jstree", $.proxy(function () {
						//div.style.height = this._data.core.li_height + 'px';
						this.get_container_ul().addClass('jstree-wholerow-ul');
					}, this))
				.on("deselect_all.jstree", $.proxy(function (e, data) {
						this.element.find('.jstree-wholerow-clicked').removeClass('jstree-wholerow-clicked');
					}, this))
				.on("changed.jstree", $.proxy(function (e, data) {
						this.element.find('.jstree-wholerow-clicked').removeClass('jstree-wholerow-clicked');
						var tmp = false, i, j;
						for(i = 0, j = data.selected.length; i < j; i++) {
							tmp = this.get_node(data.selected[i], true);
							if(tmp && tmp.length) {
								tmp.children('.jstree-wholerow').addClass('jstree-wholerow-clicked');
							}
						}
					}, this))
				.on("open_node.jstree", $.proxy(function (e, data) {
						this.get_node(data.node, true).find('.jstree-clicked').parent().children('.jstree-wholerow').addClass('jstree-wholerow-clicked');
					}, this))
				.on("hover_node.jstree dehover_node.jstree", $.proxy(function (e, data) {
						if(e.type === "hover_node" && this.is_disabled(data.node)) { return; }
						this.get_node(data.node, true).children('.jstree-wholerow')[e.type === "hover_node"?"addClass":"removeClass"]('jstree-wholerow-hovered');
					}, this))
				.on("contextmenu.jstree", ".jstree-wholerow", $.proxy(function (e) {
						if (this._data.contextmenu) {
							e.preventDefault();
							var tmp = $.Event('contextmenu', { metaKey : e.metaKey, ctrlKey : e.ctrlKey, altKey : e.altKey, shiftKey : e.shiftKey, pageX : e.pageX, pageY : e.pageY });
							$(e.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(tmp);
						}
					}, this))
				/*!
				.on("mousedown.jstree touchstart.jstree", ".jstree-wholerow", function (e) {
						if(e.target === e.currentTarget) {
							var a = $(e.currentTarget).closest(".jstree-node").children(".jstree-anchor");
							e.target = a[0];
							a.trigger(e);
						}
					})
				*/
				.on("click.jstree", ".jstree-wholerow", function (e) {
						e.stopImmediatePropagation();
						var tmp = $.Event('click', { metaKey : e.metaKey, ctrlKey : e.ctrlKey, altKey : e.altKey, shiftKey : e.shiftKey });
						$(e.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(tmp).focus();
					})
				.on("dblclick.jstree", ".jstree-wholerow", function (e) {
						e.stopImmediatePropagation();
						var tmp = $.Event('dblclick', { metaKey : e.metaKey, ctrlKey : e.ctrlKey, altKey : e.altKey, shiftKey : e.shiftKey });
						$(e.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(tmp).focus();
					})
				.on("click.jstree", ".jstree-leaf > .jstree-ocl", $.proxy(function (e) {
						e.stopImmediatePropagation();
						var tmp = $.Event('click', { metaKey : e.metaKey, ctrlKey : e.ctrlKey, altKey : e.altKey, shiftKey : e.shiftKey });
						$(e.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(tmp).focus();
					}, this))
				.on("mouseover.jstree", ".jstree-wholerow, .jstree-icon", $.proxy(function (e) {
						e.stopImmediatePropagation();
						if(!this.is_disabled(e.currentTarget)) {
							this.hover_node(e.currentTarget);
						}
						return false;
					}, this))
				.on("mouseleave.jstree", ".jstree-node", $.proxy(function (e) {
						this.dehover_node(e.currentTarget);
					}, this));
		};
		this.teardown = function () {
			if(this.settings.wholerow) {
				this.element.find(".jstree-wholerow").remove();
			}
			parent.teardown.call(this);
		};
		this.redraw_node = function(obj, deep, callback, force_render) {
			obj = parent.redraw_node.apply(this, arguments);
			if(obj) {
				var tmp = div.cloneNode(true);
				//tmp.style.height = this._data.core.li_height + 'px';
				if($.inArray(obj.id, this._data.core.selected) !== -1) { tmp.className += ' jstree-wholerow-clicked'; }
				if(this._data.core.focused && this._data.core.focused === obj.id) { tmp.className += ' jstree-wholerow-hovered'; }
				obj.insertBefore(tmp, obj.childNodes[0]);
			}
			return obj;
		};
	};
	// include the wholerow plugin by default
	// $.jstree.defaults.plugins.push("wholerow");
	if(window.customElements && Object && Object.create) {
		var proto = Object.create(HTMLElement.prototype);
		proto.createdCallback = function () {
			var c = { core : {}, plugins : [] }, i;
			for(i in $.jstree.plugins) {
				if($.jstree.plugins.hasOwnProperty(i) && this.attributes[i]) {
					c.plugins.push(i);
					if(this.getAttribute(i) && JSON.parse(this.getAttribute(i))) {
						c[i] = JSON.parse(this.getAttribute(i));
					}
				}
			}
			for(i in $.jstree.defaults.core) {
				if($.jstree.defaults.core.hasOwnProperty(i) && this.attributes[i]) {
					c.core[i] = JSON.parse(this.getAttribute(i)) || this.getAttribute(i);
				}
			}
			$(this).jstree(c);
		};
		// proto.attributeChangedCallback = function (name, previous, value) { };
		try {
			window.customElements.define("vakata-jstree", function() {}, { prototype: proto });
		} catch (ignore) { }
	}

}));

/***/ }),

/***/ "./node_modules/jstree/src/jstree.search.js":
/*!**************************************************!*\
  !*** ./node_modules/jstree/src/jstree.search.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * ### Search plugin
 *
 * Adds search functionality to jsTree.
 */
/*globals jQuery, define, exports, require, document */
(function (factory) {
	"use strict";
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"),__webpack_require__(/*! jstree */ "./node_modules/jstree/dist/jstree.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	}
	else {}
}(function ($, jstree, undefined) {
	"use strict";

	if($.jstree.plugins.search) { return; }

	/**
	 * stores all defaults for the search plugin
	 * @name $.jstree.defaults.search
	 * @plugin search
	 */
	$.jstree.defaults.search = {
		/**
		 * a jQuery-like AJAX config, which jstree uses if a server should be queried for results.
		 *
		 * A `str` (which is the search string) parameter will be added with the request, an optional `inside` parameter will be added if the search is limited to a node id. The expected result is a JSON array with nodes that need to be opened so that matching nodes will be revealed.
		 * Leave this setting as `false` to not query the server. You can also set this to a function, which will be invoked in the instance's scope and receive 3 parameters - the search string, the callback to call with the array of nodes to load, and the optional node ID to limit the search to
		 * @name $.jstree.defaults.search.ajax
		 * @plugin search
		 */
		ajax : false,
		/**
		 * Indicates if the search should be fuzzy or not (should `chnd3` match `child node 3`). Default is `false`.
		 * @name $.jstree.defaults.search.fuzzy
		 * @plugin search
		 */
		fuzzy : false,
		/**
		 * Indicates if the search should be case sensitive. Default is `false`.
		 * @name $.jstree.defaults.search.case_sensitive
		 * @plugin search
		 */
		case_sensitive : false,
		/**
		 * Indicates if the tree should be filtered (by default) to show only matching nodes (keep in mind this can be a heavy on large trees in old browsers).
		 * This setting can be changed at runtime when calling the search method. Default is `false`.
		 * @name $.jstree.defaults.search.show_only_matches
		 * @plugin search
		 */
		show_only_matches : false,
		/**
		 * Indicates if the children of matched element are shown (when show_only_matches is true)
		 * This setting can be changed at runtime when calling the search method. Default is `false`.
		 * @name $.jstree.defaults.search.show_only_matches_children
		 * @plugin search
		 */
		show_only_matches_children : false,
		/**
		 * Indicates if all nodes opened to reveal the search result, should be closed when the search is cleared or a new search is performed. Default is `true`.
		 * @name $.jstree.defaults.search.close_opened_onclear
		 * @plugin search
		 */
		close_opened_onclear : true,
		/**
		 * Indicates if only leaf nodes should be included in search results. Default is `false`.
		 * @name $.jstree.defaults.search.search_leaves_only
		 * @plugin search
		 */
		search_leaves_only : false,
		/**
		 * If set to a function it wil be called in the instance's scope with two arguments - search string and node (where node will be every node in the structure, so use with caution).
		 * If the function returns a truthy value the node will be considered a match (it might not be displayed if search_only_leaves is set to true and the node is not a leaf). Default is `false`.
		 * @name $.jstree.defaults.search.search_callback
		 * @plugin search
		 */
		search_callback : false
	};

	$.jstree.plugins.search = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);

			this._data.search.str = "";
			this._data.search.dom = $();
			this._data.search.res = [];
			this._data.search.opn = [];
			this._data.search.som = false;
			this._data.search.smc = false;
			this._data.search.hdn = [];

			this.element
				.on("search.jstree", $.proxy(function (e, data) {
						if(this._data.search.som && data.res.length) {
							var m = this._model.data, i, j, p = [], k, l;
							for(i = 0, j = data.res.length; i < j; i++) {
								if(m[data.res[i]] && !m[data.res[i]].state.hidden) {
									p.push(data.res[i]);
									p = p.concat(m[data.res[i]].parents);
									if(this._data.search.smc) {
										for (k = 0, l = m[data.res[i]].children_d.length; k < l; k++) {
											if (m[m[data.res[i]].children_d[k]] && !m[m[data.res[i]].children_d[k]].state.hidden) {
												p.push(m[data.res[i]].children_d[k]);
											}
										}
									}
								}
							}
							p = $.vakata.array_remove_item($.vakata.array_unique(p), $.jstree.root);
							this._data.search.hdn = this.hide_all(true);
							this.show_node(p, true);
							this.redraw(true);
						}
					}, this))
				.on("clear_search.jstree", $.proxy(function (e, data) {
						if(this._data.search.som && data.res.length) {
							this.show_node(this._data.search.hdn, true);
							this.redraw(true);
						}
					}, this));
		};
		/**
		 * used to search the tree nodes for a given string
		 * @name search(str [, skip_async])
		 * @param {String} str the search string
		 * @param {Boolean} skip_async if set to true server will not be queried even if configured
		 * @param {Boolean} show_only_matches if set to true only matching nodes will be shown (keep in mind this can be very slow on large trees or old browsers)
		 * @param {mixed} inside an optional node to whose children to limit the search
		 * @param {Boolean} append if set to true the results of this search are appended to the previous search
		 * @plugin search
		 * @trigger search.jstree
		 */
		this.search = function (str, skip_async, show_only_matches, inside, append, show_only_matches_children) {
			if(str === false || $.trim(str.toString()) === "") {
				return this.clear_search();
			}
			inside = this.get_node(inside);
			inside = inside && inside.id ? inside.id : null;
			str = str.toString();
			var s = this.settings.search,
				a = s.ajax ? s.ajax : false,
				m = this._model.data,
				f = null,
				r = [],
				p = [], i, j;
			if(this._data.search.res.length && !append) {
				this.clear_search();
			}
			if(show_only_matches === undefined) {
				show_only_matches = s.show_only_matches;
			}
			if(show_only_matches_children === undefined) {
				show_only_matches_children = s.show_only_matches_children;
			}
			if(!skip_async && a !== false) {
				if($.isFunction(a)) {
					return a.call(this, str, $.proxy(function (d) {
							if(d && d.d) { d = d.d; }
							this._load_nodes(!$.isArray(d) ? [] : $.vakata.array_unique(d), function () {
								this.search(str, true, show_only_matches, inside, append, show_only_matches_children);
							});
						}, this), inside);
				}
				else {
					a = $.extend({}, a);
					if(!a.data) { a.data = {}; }
					a.data.str = str;
					if(inside) {
						a.data.inside = inside;
					}
					if (this._data.search.lastRequest) {
						this._data.search.lastRequest.abort();
					}
					this._data.search.lastRequest = $.ajax(a)
						.fail($.proxy(function () {
							this._data.core.last_error = { 'error' : 'ajax', 'plugin' : 'search', 'id' : 'search_01', 'reason' : 'Could not load search parents', 'data' : JSON.stringify(a) };
							this.settings.core.error.call(this, this._data.core.last_error);
						}, this))
						.done($.proxy(function (d) {
							if(d && d.d) { d = d.d; }
							this._load_nodes(!$.isArray(d) ? [] : $.vakata.array_unique(d), function () {
								this.search(str, true, show_only_matches, inside, append, show_only_matches_children);
							});
						}, this));
					return this._data.search.lastRequest;
				}
			}
			if(!append) {
				this._data.search.str = str;
				this._data.search.dom = $();
				this._data.search.res = [];
				this._data.search.opn = [];
				this._data.search.som = show_only_matches;
				this._data.search.smc = show_only_matches_children;
			}

			f = new $.vakata.search(str, true, { caseSensitive : s.case_sensitive, fuzzy : s.fuzzy });
			$.each(m[inside ? inside : $.jstree.root].children_d, function (ii, i) {
				var v = m[i];
				if(v.text && !v.state.hidden && (!s.search_leaves_only || (v.state.loaded && v.children.length === 0)) && ( (s.search_callback && s.search_callback.call(this, str, v)) || (!s.search_callback && f.search(v.text).isMatch) ) ) {
					r.push(i);
					p = p.concat(v.parents);
				}
			});
			if(r.length) {
				p = $.vakata.array_unique(p);
				for(i = 0, j = p.length; i < j; i++) {
					if(p[i] !== $.jstree.root && m[p[i]] && this.open_node(p[i], null, 0) === true) {
						this._data.search.opn.push(p[i]);
					}
				}
				if(!append) {
					this._data.search.dom = $(this.element[0].querySelectorAll('#' + $.map(r, function (v) { return "0123456789".indexOf(v[0]) !== -1 ? '\\3' + v[0] + ' ' + v.substr(1).replace($.jstree.idregex,'\\$&') : v.replace($.jstree.idregex,'\\$&'); }).join(', #')));
					this._data.search.res = r;
				}
				else {
					this._data.search.dom = this._data.search.dom.add($(this.element[0].querySelectorAll('#' + $.map(r, function (v) { return "0123456789".indexOf(v[0]) !== -1 ? '\\3' + v[0] + ' ' + v.substr(1).replace($.jstree.idregex,'\\$&') : v.replace($.jstree.idregex,'\\$&'); }).join(', #'))));
					this._data.search.res = $.vakata.array_unique(this._data.search.res.concat(r));
				}
				this._data.search.dom.children(".jstree-anchor").addClass('jstree-search');
			}
			/**
			 * triggered after search is complete
			 * @event
			 * @name search.jstree
			 * @param {jQuery} nodes a jQuery collection of matching nodes
			 * @param {String} str the search string
			 * @param {Array} res a collection of objects represeing the matching nodes
			 * @plugin search
			 */
			this.trigger('search', { nodes : this._data.search.dom, str : str, res : this._data.search.res, show_only_matches : show_only_matches });
		};
		/**
		 * used to clear the last search (removes classes and shows all nodes if filtering is on)
		 * @name clear_search()
		 * @plugin search
		 * @trigger clear_search.jstree
		 */
		this.clear_search = function () {
			if(this.settings.search.close_opened_onclear) {
				this.close_node(this._data.search.opn, 0);
			}
			/**
			 * triggered after search is complete
			 * @event
			 * @name clear_search.jstree
			 * @param {jQuery} nodes a jQuery collection of matching nodes (the result from the last search)
			 * @param {String} str the search string (the last search string)
			 * @param {Array} res a collection of objects represeing the matching nodes (the result from the last search)
			 * @plugin search
			 */
			this.trigger('clear_search', { 'nodes' : this._data.search.dom, str : this._data.search.str, res : this._data.search.res });
			if(this._data.search.res.length) {
				this._data.search.dom = $(this.element[0].querySelectorAll('#' + $.map(this._data.search.res, function (v) {
					return "0123456789".indexOf(v[0]) !== -1 ? '\\3' + v[0] + ' ' + v.substr(1).replace($.jstree.idregex,'\\$&') : v.replace($.jstree.idregex,'\\$&');
				}).join(', #')));
				this._data.search.dom.children(".jstree-anchor").removeClass("jstree-search");
			}
			this._data.search.str = "";
			this._data.search.res = [];
			this._data.search.opn = [];
			this._data.search.dom = $();
		};

		this.redraw_node = function(obj, deep, callback, force_render) {
			obj = parent.redraw_node.apply(this, arguments);
			if(obj) {
				if($.inArray(obj.id, this._data.search.res) !== -1) {
					var i, j, tmp = null;
					for(i = 0, j = obj.childNodes.length; i < j; i++) {
						if(obj.childNodes[i] && obj.childNodes[i].className && obj.childNodes[i].className.indexOf("jstree-anchor") !== -1) {
							tmp = obj.childNodes[i];
							break;
						}
					}
					if(tmp) {
						tmp.className += ' jstree-search';
					}
				}
			}
			return obj;
		};
	};

	// helpers
	(function ($) {
		// from http://kiro.me/projects/fuse.html
		$.vakata.search = function(pattern, txt, options) {
			options = options || {};
			options = $.extend({}, $.vakata.search.defaults, options);
			if(options.fuzzy !== false) {
				options.fuzzy = true;
			}
			pattern = options.caseSensitive ? pattern : pattern.toLowerCase();
			var MATCH_LOCATION	= options.location,
				MATCH_DISTANCE	= options.distance,
				MATCH_THRESHOLD	= options.threshold,
				patternLen = pattern.length,
				matchmask, pattern_alphabet, match_bitapScore, search;
			if(patternLen > 32) {
				options.fuzzy = false;
			}
			if(options.fuzzy) {
				matchmask = 1 << (patternLen - 1);
				pattern_alphabet = (function () {
					var mask = {},
						i = 0;
					for (i = 0; i < patternLen; i++) {
						mask[pattern.charAt(i)] = 0;
					}
					for (i = 0; i < patternLen; i++) {
						mask[pattern.charAt(i)] |= 1 << (patternLen - i - 1);
					}
					return mask;
				}());
				match_bitapScore = function (e, x) {
					var accuracy = e / patternLen,
						proximity = Math.abs(MATCH_LOCATION - x);
					if(!MATCH_DISTANCE) {
						return proximity ? 1.0 : accuracy;
					}
					return accuracy + (proximity / MATCH_DISTANCE);
				};
			}
			search = function (text) {
				text = options.caseSensitive ? text : text.toLowerCase();
				if(pattern === text || text.indexOf(pattern) !== -1) {
					return {
						isMatch: true,
						score: 0
					};
				}
				if(!options.fuzzy) {
					return {
						isMatch: false,
						score: 1
					};
				}
				var i, j,
					textLen = text.length,
					scoreThreshold = MATCH_THRESHOLD,
					bestLoc = text.indexOf(pattern, MATCH_LOCATION),
					binMin, binMid,
					binMax = patternLen + textLen,
					lastRd, start, finish, rd, charMatch,
					score = 1,
					locations = [];
				if (bestLoc !== -1) {
					scoreThreshold = Math.min(match_bitapScore(0, bestLoc), scoreThreshold);
					bestLoc = text.lastIndexOf(pattern, MATCH_LOCATION + patternLen);
					if (bestLoc !== -1) {
						scoreThreshold = Math.min(match_bitapScore(0, bestLoc), scoreThreshold);
					}
				}
				bestLoc = -1;
				for (i = 0; i < patternLen; i++) {
					binMin = 0;
					binMid = binMax;
					while (binMin < binMid) {
						if (match_bitapScore(i, MATCH_LOCATION + binMid) <= scoreThreshold) {
							binMin = binMid;
						} else {
							binMax = binMid;
						}
						binMid = Math.floor((binMax - binMin) / 2 + binMin);
					}
					binMax = binMid;
					start = Math.max(1, MATCH_LOCATION - binMid + 1);
					finish = Math.min(MATCH_LOCATION + binMid, textLen) + patternLen;
					rd = new Array(finish + 2);
					rd[finish + 1] = (1 << i) - 1;
					for (j = finish; j >= start; j--) {
						charMatch = pattern_alphabet[text.charAt(j - 1)];
						if (i === 0) {
							rd[j] = ((rd[j + 1] << 1) | 1) & charMatch;
						} else {
							rd[j] = ((rd[j + 1] << 1) | 1) & charMatch | (((lastRd[j + 1] | lastRd[j]) << 1) | 1) | lastRd[j + 1];
						}
						if (rd[j] & matchmask) {
							score = match_bitapScore(i, j - 1);
							if (score <= scoreThreshold) {
								scoreThreshold = score;
								bestLoc = j - 1;
								locations.push(bestLoc);
								if (bestLoc > MATCH_LOCATION) {
									start = Math.max(1, 2 * MATCH_LOCATION - bestLoc);
								} else {
									break;
								}
							}
						}
					}
					if (match_bitapScore(i + 1, MATCH_LOCATION) > scoreThreshold) {
						break;
					}
					lastRd = rd;
				}
				return {
					isMatch: bestLoc >= 0,
					score: score
				};
			};
			return txt === true ? { 'search' : search } : search(txt);
		};
		$.vakata.search.defaults = {
			location : 0,
			distance : 100,
			threshold : 0.6,
			fuzzy : false,
			caseSensitive : false
		};
	}($));

	// include the search plugin by default
	// $.jstree.defaults.plugins.push("search");
}));


/***/ }),

/***/ "./node_modules/jstree/src/jstree.sort.js":
/*!************************************************!*\
  !*** ./node_modules/jstree/src/jstree.sort.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * ### Sort plugin
 *
 * Automatically sorts all siblings in the tree according to a sorting function.
 */
/*globals jQuery, define, exports, require */
(function (factory) {
	"use strict";
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"),__webpack_require__(/*! jstree */ "./node_modules/jstree/dist/jstree.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	}
	else {}
}(function ($, jstree, undefined) {
	"use strict";

	if($.jstree.plugins.sort) { return; }

	/**
	 * the settings function used to sort the nodes.
	 * It is executed in the tree's context, accepts two nodes as arguments and should return `1` or `-1`.
	 * @name $.jstree.defaults.sort
	 * @plugin sort
	 */
	$.jstree.defaults.sort = function (a, b) {
		//return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : this.get_type(a) >= this.get_type(b);
		return this.get_text(a) > this.get_text(b) ? 1 : -1;
	};
	$.jstree.plugins.sort = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);
			this.element
				.on("model.jstree", $.proxy(function (e, data) {
						this.sort(data.parent, true);
					}, this))
				.on("rename_node.jstree create_node.jstree", $.proxy(function (e, data) {
						this.sort(data.parent || data.node.parent, false);
						this.redraw_node(data.parent || data.node.parent, true);
					}, this))
				.on("move_node.jstree copy_node.jstree", $.proxy(function (e, data) {
						this.sort(data.parent, false);
						this.redraw_node(data.parent, true);
					}, this));
		};
		/**
		 * used to sort a node's children
		 * @private
		 * @name sort(obj [, deep])
		 * @param  {mixed} obj the node
		 * @param {Boolean} deep if set to `true` nodes are sorted recursively.
		 * @plugin sort
		 * @trigger search.jstree
		 */
		this.sort = function (obj, deep) {
			var i, j;
			obj = this.get_node(obj);
			if(obj && obj.children && obj.children.length) {
				obj.children.sort($.proxy(this.settings.sort, this));
				if(deep) {
					for(i = 0, j = obj.children_d.length; i < j; i++) {
						this.sort(obj.children_d[i], false);
					}
				}
			}
		};
	};

	// include the sort plugin by default
	// $.jstree.defaults.plugins.push("sort");
}));

/***/ }),

/***/ "./node_modules/moment/locale sync recursive ^\\.\\/.*$":
/*!**************************************************!*\
  !*** ./node_modules/moment/locale sync ^\.\/.*$ ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./af": "./node_modules/moment/locale/af.js",
	"./af.js": "./node_modules/moment/locale/af.js",
	"./ar": "./node_modules/moment/locale/ar.js",
	"./ar-dz": "./node_modules/moment/locale/ar-dz.js",
	"./ar-dz.js": "./node_modules/moment/locale/ar-dz.js",
	"./ar-kw": "./node_modules/moment/locale/ar-kw.js",
	"./ar-kw.js": "./node_modules/moment/locale/ar-kw.js",
	"./ar-ly": "./node_modules/moment/locale/ar-ly.js",
	"./ar-ly.js": "./node_modules/moment/locale/ar-ly.js",
	"./ar-ma": "./node_modules/moment/locale/ar-ma.js",
	"./ar-ma.js": "./node_modules/moment/locale/ar-ma.js",
	"./ar-sa": "./node_modules/moment/locale/ar-sa.js",
	"./ar-sa.js": "./node_modules/moment/locale/ar-sa.js",
	"./ar-tn": "./node_modules/moment/locale/ar-tn.js",
	"./ar-tn.js": "./node_modules/moment/locale/ar-tn.js",
	"./ar.js": "./node_modules/moment/locale/ar.js",
	"./az": "./node_modules/moment/locale/az.js",
	"./az.js": "./node_modules/moment/locale/az.js",
	"./be": "./node_modules/moment/locale/be.js",
	"./be.js": "./node_modules/moment/locale/be.js",
	"./bg": "./node_modules/moment/locale/bg.js",
	"./bg.js": "./node_modules/moment/locale/bg.js",
	"./bm": "./node_modules/moment/locale/bm.js",
	"./bm.js": "./node_modules/moment/locale/bm.js",
	"./bn": "./node_modules/moment/locale/bn.js",
	"./bn.js": "./node_modules/moment/locale/bn.js",
	"./bo": "./node_modules/moment/locale/bo.js",
	"./bo.js": "./node_modules/moment/locale/bo.js",
	"./br": "./node_modules/moment/locale/br.js",
	"./br.js": "./node_modules/moment/locale/br.js",
	"./bs": "./node_modules/moment/locale/bs.js",
	"./bs.js": "./node_modules/moment/locale/bs.js",
	"./ca": "./node_modules/moment/locale/ca.js",
	"./ca.js": "./node_modules/moment/locale/ca.js",
	"./cs": "./node_modules/moment/locale/cs.js",
	"./cs.js": "./node_modules/moment/locale/cs.js",
	"./cv": "./node_modules/moment/locale/cv.js",
	"./cv.js": "./node_modules/moment/locale/cv.js",
	"./cy": "./node_modules/moment/locale/cy.js",
	"./cy.js": "./node_modules/moment/locale/cy.js",
	"./da": "./node_modules/moment/locale/da.js",
	"./da.js": "./node_modules/moment/locale/da.js",
	"./de": "./node_modules/moment/locale/de.js",
	"./de-at": "./node_modules/moment/locale/de-at.js",
	"./de-at.js": "./node_modules/moment/locale/de-at.js",
	"./de-ch": "./node_modules/moment/locale/de-ch.js",
	"./de-ch.js": "./node_modules/moment/locale/de-ch.js",
	"./de.js": "./node_modules/moment/locale/de.js",
	"./dv": "./node_modules/moment/locale/dv.js",
	"./dv.js": "./node_modules/moment/locale/dv.js",
	"./el": "./node_modules/moment/locale/el.js",
	"./el.js": "./node_modules/moment/locale/el.js",
	"./en-SG": "./node_modules/moment/locale/en-SG.js",
	"./en-SG.js": "./node_modules/moment/locale/en-SG.js",
	"./en-au": "./node_modules/moment/locale/en-au.js",
	"./en-au.js": "./node_modules/moment/locale/en-au.js",
	"./en-ca": "./node_modules/moment/locale/en-ca.js",
	"./en-ca.js": "./node_modules/moment/locale/en-ca.js",
	"./en-gb": "./node_modules/moment/locale/en-gb.js",
	"./en-gb.js": "./node_modules/moment/locale/en-gb.js",
	"./en-ie": "./node_modules/moment/locale/en-ie.js",
	"./en-ie.js": "./node_modules/moment/locale/en-ie.js",
	"./en-il": "./node_modules/moment/locale/en-il.js",
	"./en-il.js": "./node_modules/moment/locale/en-il.js",
	"./en-nz": "./node_modules/moment/locale/en-nz.js",
	"./en-nz.js": "./node_modules/moment/locale/en-nz.js",
	"./eo": "./node_modules/moment/locale/eo.js",
	"./eo.js": "./node_modules/moment/locale/eo.js",
	"./es": "./node_modules/moment/locale/es.js",
	"./es-do": "./node_modules/moment/locale/es-do.js",
	"./es-do.js": "./node_modules/moment/locale/es-do.js",
	"./es-us": "./node_modules/moment/locale/es-us.js",
	"./es-us.js": "./node_modules/moment/locale/es-us.js",
	"./es.js": "./node_modules/moment/locale/es.js",
	"./et": "./node_modules/moment/locale/et.js",
	"./et.js": "./node_modules/moment/locale/et.js",
	"./eu": "./node_modules/moment/locale/eu.js",
	"./eu.js": "./node_modules/moment/locale/eu.js",
	"./fa": "./node_modules/moment/locale/fa.js",
	"./fa.js": "./node_modules/moment/locale/fa.js",
	"./fi": "./node_modules/moment/locale/fi.js",
	"./fi.js": "./node_modules/moment/locale/fi.js",
	"./fo": "./node_modules/moment/locale/fo.js",
	"./fo.js": "./node_modules/moment/locale/fo.js",
	"./fr": "./node_modules/moment/locale/fr.js",
	"./fr-ca": "./node_modules/moment/locale/fr-ca.js",
	"./fr-ca.js": "./node_modules/moment/locale/fr-ca.js",
	"./fr-ch": "./node_modules/moment/locale/fr-ch.js",
	"./fr-ch.js": "./node_modules/moment/locale/fr-ch.js",
	"./fr.js": "./node_modules/moment/locale/fr.js",
	"./fy": "./node_modules/moment/locale/fy.js",
	"./fy.js": "./node_modules/moment/locale/fy.js",
	"./ga": "./node_modules/moment/locale/ga.js",
	"./ga.js": "./node_modules/moment/locale/ga.js",
	"./gd": "./node_modules/moment/locale/gd.js",
	"./gd.js": "./node_modules/moment/locale/gd.js",
	"./gl": "./node_modules/moment/locale/gl.js",
	"./gl.js": "./node_modules/moment/locale/gl.js",
	"./gom-latn": "./node_modules/moment/locale/gom-latn.js",
	"./gom-latn.js": "./node_modules/moment/locale/gom-latn.js",
	"./gu": "./node_modules/moment/locale/gu.js",
	"./gu.js": "./node_modules/moment/locale/gu.js",
	"./he": "./node_modules/moment/locale/he.js",
	"./he.js": "./node_modules/moment/locale/he.js",
	"./hi": "./node_modules/moment/locale/hi.js",
	"./hi.js": "./node_modules/moment/locale/hi.js",
	"./hr": "./node_modules/moment/locale/hr.js",
	"./hr.js": "./node_modules/moment/locale/hr.js",
	"./hu": "./node_modules/moment/locale/hu.js",
	"./hu.js": "./node_modules/moment/locale/hu.js",
	"./hy-am": "./node_modules/moment/locale/hy-am.js",
	"./hy-am.js": "./node_modules/moment/locale/hy-am.js",
	"./id": "./node_modules/moment/locale/id.js",
	"./id.js": "./node_modules/moment/locale/id.js",
	"./is": "./node_modules/moment/locale/is.js",
	"./is.js": "./node_modules/moment/locale/is.js",
	"./it": "./node_modules/moment/locale/it.js",
	"./it-ch": "./node_modules/moment/locale/it-ch.js",
	"./it-ch.js": "./node_modules/moment/locale/it-ch.js",
	"./it.js": "./node_modules/moment/locale/it.js",
	"./ja": "./node_modules/moment/locale/ja.js",
	"./ja.js": "./node_modules/moment/locale/ja.js",
	"./jv": "./node_modules/moment/locale/jv.js",
	"./jv.js": "./node_modules/moment/locale/jv.js",
	"./ka": "./node_modules/moment/locale/ka.js",
	"./ka.js": "./node_modules/moment/locale/ka.js",
	"./kk": "./node_modules/moment/locale/kk.js",
	"./kk.js": "./node_modules/moment/locale/kk.js",
	"./km": "./node_modules/moment/locale/km.js",
	"./km.js": "./node_modules/moment/locale/km.js",
	"./kn": "./node_modules/moment/locale/kn.js",
	"./kn.js": "./node_modules/moment/locale/kn.js",
	"./ko": "./node_modules/moment/locale/ko.js",
	"./ko.js": "./node_modules/moment/locale/ko.js",
	"./ku": "./node_modules/moment/locale/ku.js",
	"./ku.js": "./node_modules/moment/locale/ku.js",
	"./ky": "./node_modules/moment/locale/ky.js",
	"./ky.js": "./node_modules/moment/locale/ky.js",
	"./lb": "./node_modules/moment/locale/lb.js",
	"./lb.js": "./node_modules/moment/locale/lb.js",
	"./lo": "./node_modules/moment/locale/lo.js",
	"./lo.js": "./node_modules/moment/locale/lo.js",
	"./lt": "./node_modules/moment/locale/lt.js",
	"./lt.js": "./node_modules/moment/locale/lt.js",
	"./lv": "./node_modules/moment/locale/lv.js",
	"./lv.js": "./node_modules/moment/locale/lv.js",
	"./me": "./node_modules/moment/locale/me.js",
	"./me.js": "./node_modules/moment/locale/me.js",
	"./mi": "./node_modules/moment/locale/mi.js",
	"./mi.js": "./node_modules/moment/locale/mi.js",
	"./mk": "./node_modules/moment/locale/mk.js",
	"./mk.js": "./node_modules/moment/locale/mk.js",
	"./ml": "./node_modules/moment/locale/ml.js",
	"./ml.js": "./node_modules/moment/locale/ml.js",
	"./mn": "./node_modules/moment/locale/mn.js",
	"./mn.js": "./node_modules/moment/locale/mn.js",
	"./mr": "./node_modules/moment/locale/mr.js",
	"./mr.js": "./node_modules/moment/locale/mr.js",
	"./ms": "./node_modules/moment/locale/ms.js",
	"./ms-my": "./node_modules/moment/locale/ms-my.js",
	"./ms-my.js": "./node_modules/moment/locale/ms-my.js",
	"./ms.js": "./node_modules/moment/locale/ms.js",
	"./mt": "./node_modules/moment/locale/mt.js",
	"./mt.js": "./node_modules/moment/locale/mt.js",
	"./my": "./node_modules/moment/locale/my.js",
	"./my.js": "./node_modules/moment/locale/my.js",
	"./nb": "./node_modules/moment/locale/nb.js",
	"./nb.js": "./node_modules/moment/locale/nb.js",
	"./ne": "./node_modules/moment/locale/ne.js",
	"./ne.js": "./node_modules/moment/locale/ne.js",
	"./nl": "./node_modules/moment/locale/nl.js",
	"./nl-be": "./node_modules/moment/locale/nl-be.js",
	"./nl-be.js": "./node_modules/moment/locale/nl-be.js",
	"./nl.js": "./node_modules/moment/locale/nl.js",
	"./nn": "./node_modules/moment/locale/nn.js",
	"./nn.js": "./node_modules/moment/locale/nn.js",
	"./pa-in": "./node_modules/moment/locale/pa-in.js",
	"./pa-in.js": "./node_modules/moment/locale/pa-in.js",
	"./pl": "./node_modules/moment/locale/pl.js",
	"./pl.js": "./node_modules/moment/locale/pl.js",
	"./pt": "./node_modules/moment/locale/pt.js",
	"./pt-br": "./node_modules/moment/locale/pt-br.js",
	"./pt-br.js": "./node_modules/moment/locale/pt-br.js",
	"./pt.js": "./node_modules/moment/locale/pt.js",
	"./ro": "./node_modules/moment/locale/ro.js",
	"./ro.js": "./node_modules/moment/locale/ro.js",
	"./ru": "./node_modules/moment/locale/ru.js",
	"./ru.js": "./node_modules/moment/locale/ru.js",
	"./sd": "./node_modules/moment/locale/sd.js",
	"./sd.js": "./node_modules/moment/locale/sd.js",
	"./se": "./node_modules/moment/locale/se.js",
	"./se.js": "./node_modules/moment/locale/se.js",
	"./si": "./node_modules/moment/locale/si.js",
	"./si.js": "./node_modules/moment/locale/si.js",
	"./sk": "./node_modules/moment/locale/sk.js",
	"./sk.js": "./node_modules/moment/locale/sk.js",
	"./sl": "./node_modules/moment/locale/sl.js",
	"./sl.js": "./node_modules/moment/locale/sl.js",
	"./sq": "./node_modules/moment/locale/sq.js",
	"./sq.js": "./node_modules/moment/locale/sq.js",
	"./sr": "./node_modules/moment/locale/sr.js",
	"./sr-cyrl": "./node_modules/moment/locale/sr-cyrl.js",
	"./sr-cyrl.js": "./node_modules/moment/locale/sr-cyrl.js",
	"./sr.js": "./node_modules/moment/locale/sr.js",
	"./ss": "./node_modules/moment/locale/ss.js",
	"./ss.js": "./node_modules/moment/locale/ss.js",
	"./sv": "./node_modules/moment/locale/sv.js",
	"./sv.js": "./node_modules/moment/locale/sv.js",
	"./sw": "./node_modules/moment/locale/sw.js",
	"./sw.js": "./node_modules/moment/locale/sw.js",
	"./ta": "./node_modules/moment/locale/ta.js",
	"./ta.js": "./node_modules/moment/locale/ta.js",
	"./te": "./node_modules/moment/locale/te.js",
	"./te.js": "./node_modules/moment/locale/te.js",
	"./tet": "./node_modules/moment/locale/tet.js",
	"./tet.js": "./node_modules/moment/locale/tet.js",
	"./tg": "./node_modules/moment/locale/tg.js",
	"./tg.js": "./node_modules/moment/locale/tg.js",
	"./th": "./node_modules/moment/locale/th.js",
	"./th.js": "./node_modules/moment/locale/th.js",
	"./tl-ph": "./node_modules/moment/locale/tl-ph.js",
	"./tl-ph.js": "./node_modules/moment/locale/tl-ph.js",
	"./tlh": "./node_modules/moment/locale/tlh.js",
	"./tlh.js": "./node_modules/moment/locale/tlh.js",
	"./tr": "./node_modules/moment/locale/tr.js",
	"./tr.js": "./node_modules/moment/locale/tr.js",
	"./tzl": "./node_modules/moment/locale/tzl.js",
	"./tzl.js": "./node_modules/moment/locale/tzl.js",
	"./tzm": "./node_modules/moment/locale/tzm.js",
	"./tzm-latn": "./node_modules/moment/locale/tzm-latn.js",
	"./tzm-latn.js": "./node_modules/moment/locale/tzm-latn.js",
	"./tzm.js": "./node_modules/moment/locale/tzm.js",
	"./ug-cn": "./node_modules/moment/locale/ug-cn.js",
	"./ug-cn.js": "./node_modules/moment/locale/ug-cn.js",
	"./uk": "./node_modules/moment/locale/uk.js",
	"./uk.js": "./node_modules/moment/locale/uk.js",
	"./ur": "./node_modules/moment/locale/ur.js",
	"./ur.js": "./node_modules/moment/locale/ur.js",
	"./uz": "./node_modules/moment/locale/uz.js",
	"./uz-latn": "./node_modules/moment/locale/uz-latn.js",
	"./uz-latn.js": "./node_modules/moment/locale/uz-latn.js",
	"./uz.js": "./node_modules/moment/locale/uz.js",
	"./vi": "./node_modules/moment/locale/vi.js",
	"./vi.js": "./node_modules/moment/locale/vi.js",
	"./x-pseudo": "./node_modules/moment/locale/x-pseudo.js",
	"./x-pseudo.js": "./node_modules/moment/locale/x-pseudo.js",
	"./yo": "./node_modules/moment/locale/yo.js",
	"./yo.js": "./node_modules/moment/locale/yo.js",
	"./zh-cn": "./node_modules/moment/locale/zh-cn.js",
	"./zh-cn.js": "./node_modules/moment/locale/zh-cn.js",
	"./zh-hk": "./node_modules/moment/locale/zh-hk.js",
	"./zh-hk.js": "./node_modules/moment/locale/zh-hk.js",
	"./zh-tw": "./node_modules/moment/locale/zh-tw.js",
	"./zh-tw.js": "./node_modules/moment/locale/zh-tw.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./node_modules/moment/locale sync recursive ^\\.\\/.*$";

/***/ }),

/***/ "./node_modules/nestable2/jquery.nestable.js":
/*!***************************************************!*\
  !*** ./node_modules/nestable2/jquery.nestable.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*!
 * Nestable jQuery Plugin - Copyright (c) 2014 Ramon Smit - https://github.com/RamonSmit/Nestable
 */

(function($, window, document, undefined) {
    var hasTouch = 'ontouchstart' in document;

    /**
     * Detect CSS pointer-events property
     * events are normally disabled on the dragging element to avoid conflicts
     * https://github.com/ausi/Feature-detection-technique-for-pointer-events/blob/master/modernizr-pointerevents.js
     */
    var hasPointerEvents = (function() {
        var el = document.createElement('div'),
            docEl = document.documentElement;
        if (!('pointerEvents' in el.style)) {
            return false;
        }
        el.style.pointerEvents = 'auto';
        el.style.pointerEvents = 'x';
        docEl.appendChild(el);
        var supports = window.getComputedStyle && window.getComputedStyle(el, '').pointerEvents === 'auto';
        docEl.removeChild(el);
        return !!supports;
    })();

    var defaults = {
        contentCallback: function(item) {return item.content || '' ? item.content : item.id;},
        listNodeName: 'ol',
        itemNodeName: 'li',
        handleNodeName: 'div',
        contentNodeName: 'span',
        rootClass: 'dd',
        listClass: 'dd-list',
        itemClass: 'dd-item',
        dragClass: 'dd-dragel',
        handleClass: 'dd-handle',
        contentClass: 'dd-content',
        collapsedClass: 'dd-collapsed',
        placeClass: 'dd-placeholder',
        noDragClass: 'dd-nodrag',
        noChildrenClass: 'dd-nochildren',
        emptyClass: 'dd-empty',
        expandBtnHTML: '<button class="dd-expand" data-action="expand" type="button">Expand</button>',
        collapseBtnHTML: '<button class="dd-collapse" data-action="collapse" type="button">Collapse</button>',
        group: 0,
        maxDepth: 5,
        threshold: 20,
        fixedDepth: false, //fixed item's depth
        fixed: false,
        includeContent: false,
        scroll: false,
        scrollSensitivity: 1,
        scrollSpeed: 5,
        scrollTriggers: {
            top: 40,
            left: 40,
            right: -40,
            bottom: -40
        },
        effect: {
            animation: 'none',
            time: 'slow'
        },
        callback: function(l, e, p) {},
        onDragStart: function(l, e, p) {},
        beforeDragStop: function(l, e, p) {},
        listRenderer: function(children, options) {
            var html = '<' + options.listNodeName + ' class="' + options.listClass + '">';
            html += children;
            html += '</' + options.listNodeName + '>';

            return html;
        },
        itemRenderer: function(item_attrs, content, children, options, item) {
            var item_attrs_string = $.map(item_attrs, function(value, key) {
                return ' ' + key + '="' + value + '"';
            }).join(' ');

            var html = '<' + options.itemNodeName + item_attrs_string + '>';
            html += '<' + options.handleNodeName + ' class="' + options.handleClass + '">';
            html += '<' + options.contentNodeName + ' class="' + options.contentClass + '">';
            html += content;
            html += '</' + options.contentNodeName + '>';
            html += '</' + options.handleNodeName + '>';
            html += children;
            html += '</' + options.itemNodeName + '>';

            return html;
        }
    };

    function Plugin(element, options) {
        this.w  = $(document);
        this.el = $(element);
        options = options || defaults;

        if (options.rootClass !== undefined && options.rootClass !== 'dd') {
            options.listClass       = options.listClass ? options.listClass : options.rootClass + '-list';
            options.itemClass       = options.itemClass ? options.itemClass : options.rootClass + '-item';
            options.dragClass       = options.dragClass ? options.dragClass : options.rootClass + '-dragel';
            options.handleClass     = options.handleClass ? options.handleClass : options.rootClass + '-handle';
            options.collapsedClass  = options.collapsedClass ? options.collapsedClass : options.rootClass + '-collapsed';
            options.placeClass      = options.placeClass ? options.placeClass : options.rootClass + '-placeholder';
            options.noDragClass     = options.noDragClass ? options.noDragClass : options.rootClass + '-nodrag';
            options.noChildrenClass = options.noChildrenClass ? options.noChildrenClass : options.rootClass + '-nochildren';
            options.emptyClass      = options.emptyClass ? options.emptyClass : options.rootClass + '-empty';
        }

        this.options = $.extend({}, defaults, options);

        // build HTML from serialized JSON if passed
        if (this.options.json !== undefined) {
            this._build();
        }

        this.init();
    }

    Plugin.prototype = {

        init: function() {
            var list = this;

            list.reset();
            list.el.data('nestable-group', this.options.group);
            list.placeEl = $('<div class="' + list.options.placeClass + '"/>');

            var items = this.el.find(list.options.itemNodeName);
            $.each(items, function(k, el) {
                var item = $(el),
                    parent = item.parent();
                list.setParent(item);
                if (parent.hasClass(list.options.collapsedClass)) {
                    list.collapseItem(parent.parent());
                }
            });

            // Append the .dd-empty div if the list don't have any items on init
            if (!items.length) {
                this.appendEmptyElement(this.el);
            }

            list.el.on('click', 'button', function(e) {
                if (list.dragEl) {
                    return;
                }
                var target = $(e.currentTarget),
                    action = target.data('action'),
                    item = target.parents(list.options.itemNodeName).eq(0);
                if (action === 'collapse') {
                    list.collapseItem(item);
                }
                if (action === 'expand') {
                    list.expandItem(item);
                }
            });

            var onStartEvent = function(e) {
                var handle = $(e.target);
                if (!handle.hasClass(list.options.handleClass)) {
                    if (handle.closest('.' + list.options.noDragClass).length) {
                        return;
                    }
                    handle = handle.closest('.' + list.options.handleClass);
                }
                if (!handle.length || list.dragEl) {
                    return;
                }

                list.isTouch = /^touch/.test(e.type);
                if (list.isTouch && e.touches.length !== 1) {
                    return;
                }

                e.preventDefault();
                list.dragStart(e.touches ? e.touches[0] : e);
            };

            var onMoveEvent = function(e) {
                if (list.dragEl) {
                    e.preventDefault();
                    list.dragMove(e.touches ? e.touches[0] : e);
                }
            };

            var onEndEvent = function(e) {
                if (list.dragEl) {
                    e.preventDefault();
                    list.dragStop(e.touches ? e.changedTouches[0] : e);
                }
            };

            if (hasTouch) {
                list.el[0].addEventListener('touchstart', onStartEvent, false);
                window.addEventListener('touchmove', onMoveEvent, false);
                window.addEventListener('touchend', onEndEvent, false);
                window.addEventListener('touchcancel', onEndEvent, false);
            }

            list.el.on('mousedown', onStartEvent);
            list.w.on('mousemove', onMoveEvent);
            list.w.on('mouseup', onEndEvent);

            var destroyNestable = function()
            {
                if (hasTouch) {
                    list.el[0].removeEventListener('touchstart', onStartEvent, false);
                    window.removeEventListener('touchmove', onMoveEvent, false);
                    window.removeEventListener('touchend', onEndEvent, false);
                    window.removeEventListener('touchcancel', onEndEvent, false);
                }

                list.el.off('mousedown', onStartEvent);
                list.w.off('mousemove', onMoveEvent);
                list.w.off('mouseup', onEndEvent);

                list.el.off('click');
                list.el.unbind('destroy-nestable');

                list.el.data("nestable", null);
            };

            list.el.bind('destroy-nestable', destroyNestable);

        },

        destroy: function ()
        {
            this.el.trigger('destroy-nestable');
        },

        add: function (item)
        {
            var listClassSelector = '.' + this.options.listClass;
            var tree = $(this.el).children(listClassSelector);

            if (item.parent_id !== undefined) {
                tree = tree.find('[data-id="' + item.parent_id + '"]');
                delete item.parent_id;

                if (tree.children(listClassSelector).length === 0) {
                    tree = tree.append(this.options.listRenderer('', this.options));
                }

                tree = tree.find(listClassSelector + ':first');
                this.setParent(tree.parent());
            }

            tree.append(this._buildItem(item, this.options));
        },

        replace: function (item)
        {
            var html = this._buildItem(item, this.options);

            this._getItemById(item.id)
                .replaceWith(html);
        },

        //removes item and additional elements from list
        removeItem: function (item){
            var opts = this.options,
                el   = this.el;

            // remove item
            item = item || this;
            item.remove();

            // remove empty children lists
            var emptyListsSelector = '.' + opts.listClass
                + ' .' + opts.listClass + ':not(:has(*))';
            $(el).find(emptyListsSelector).remove();

            // remove buttons if parents do not have children
            var buttonsSelector = '[data-action="expand"], [data-action="collapse"]';
            $(el).find(buttonsSelector).each(function() {
                var siblings = $(this).siblings('.' + opts.listClass);
                if (siblings.length === 0) {
                    $(this).remove();
                }
            });
        },

        //removes item by itemId and run callback at the end
        remove: function (itemId, callback)
        {
            var opts = this.options;
            var list = this;
            var item = this._getItemById(itemId);

            //animation style
            var animation = opts.effect.animation || 'fade';

            //animation time
            var time = opts.effect.time || 'slow';

            //add fadeOut effect when removing
            if (animation === 'fade'){
                item.fadeOut(time, function(){
                    list.removeItem(item);
                });
            }
            else {
                this.removeItem(item);
            }

            if (callback) callback();
        },

        //removes all items from the list and run callback at the end
        removeAll: function(callback){

            var list  = this,
                opts  = this.options,
                node  = list.el.find(opts.listNodeName).first(),
                items = node.children(opts.itemNodeName);

            //animation style
            var animation = opts.effect.animation || 'fade';

            //animation time
            var time = opts.effect.time || 'slow';

            function remove(){
                //Removes each item and its children.
                items.each(function() {
                    list.removeItem($(this));
                });
                //Now we can again show our node element
                node.show();
                if (callback) callback();
            }

            //add fadeOut effect when removing
            if (animation === 'fade'){
                node.fadeOut(time, remove);
            }
            else {
                remove();
            }
        },

        _getItemById: function(itemId) {
            return $(this.el).children('.' + this.options.listClass)
                .find('[data-id="' + itemId + '"]');
        },

        _build: function() {
            var json = this.options.json;

            if (typeof json === 'string') {
                json = JSON.parse(json);
            }

            $(this.el).html(this._buildList(json, this.options));
        },

        _buildList: function(items, options) {
            if (!items) {
                return '';
            }

            var children = '';
            var that = this;

            $.each(items, function(index, sub) {
                children += that._buildItem(sub, options);
            });

            return options.listRenderer(children, options);
        },

        _buildItem: function(item, options) {
            function escapeHtml(text) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };

                return text + "".replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            function filterClasses(classes) {
                var new_classes = {};

                for (var k in classes) {
                    // Remove duplicates
                    new_classes[classes[k]] = classes[k];
                }

                return new_classes;
            }

            function createClassesString(item, options) {
                var classes = item.classes || {};

                if (typeof classes === 'string') {
                    classes = [classes];
                }

                var item_classes = filterClasses(classes);
                item_classes[options.itemClass] = options.itemClass;

                // create class string
                return $.map(item_classes, function(val) {
                    return val;
                }).join(' ');
            }

            function createDataAttrs(attr) {
                attr = $.extend({}, attr);

                delete attr.children;
                delete attr.classes;
                delete attr.content;

                var data_attrs = {};

                $.each(attr, function(key, value) {
                    if (typeof value === 'object') {
                        value = JSON.stringify(value);
                    }

                    data_attrs["data-" + key] = escapeHtml(value);
                });

                return data_attrs;
            }

            var item_attrs = createDataAttrs(item);
            item_attrs["class"] = createClassesString(item, options);

            var content = options.contentCallback(item);
            var children = this._buildList(item.children, options);
            var html = $(options.itemRenderer(item_attrs, content, children, options, item));

            this.setParent(html);

            return html[0].outerHTML;
        },

        serialize: function() {
            var data, list = this, step = function(level) {
                var array = [],
                    items = level.children(list.options.itemNodeName);
                items.each(function() {
                    var li = $(this),
                        item = $.extend({}, li.data()),
                        sub = li.children(list.options.listNodeName);

                    if (list.options.includeContent) {
                        var content = li.find('.' + list.options.contentClass).html();

                        if (content) {
                            item.content = content;
                        }
                    }

                    if (sub.length) {
                        item.children = step(sub);
                    }
                    array.push(item);
                });
                return array;
            };
            data = step(list.el.find(list.options.listNodeName).first());
            return data;
        },

        asNestedSet: function() {
            var list = this, o = list.options, depth = -1, ret = [], lft = 1;
            var items = list.el.find(o.listNodeName).first().children(o.itemNodeName);

            items.each(function () {
                lft = traverse(this, depth + 1, lft);
            });

            ret = ret.sort(function(a,b){ return (a.lft - b.lft); });
            return ret;

            function traverse(item, depth, lft) {
                var rgt = lft + 1, id, pid;

                if ($(item).children(o.listNodeName).children(o.itemNodeName).length > 0 ) {
                    depth++;
                    $(item).children(o.listNodeName).children(o.itemNodeName).each(function () {
                        rgt = traverse($(this), depth, rgt);
                    });
                    depth--;
                }

                id = $(item).attr('data-id');
                if (isInt(id)) {
                    id = parseInt(id);
                }

                pid = $(item).parent(o.listNodeName).parent(o.itemNodeName).attr('data-id') || '';
                if (isInt(pid)) {
                    id = parseInt(pid);
                }

                if (id) {
                    ret.push({"id": id, "parent_id": pid, "depth": depth, "lft": lft, "rgt": rgt});
                }

                lft = rgt + 1;
                return lft;
            }

            function isInt(value) {
                return $.isNumeric(value) && Math.floor(value) == value;
            }
        },

        returnOptions: function() {
            return this.options;
        },

        serialise: function() {
            return this.serialize();
        },

        toHierarchy: function(options) {

            var o = $.extend({}, this.options, options),
                ret = [];

            $(this.element).children(o.items).each(function() {
                var level = _recursiveItems(this);
                ret.push(level);
            });

            return ret;

            function _recursiveItems(item) {
                var id = ($(item).attr(o.attribute || 'id') || '').match(o.expression || (/(.+)[-=_](.+)/));
                if (id) {
                    var currentItem = {
                        "id": id[2]
                    };
                    if ($(item).children(o.listType).children(o.items).length > 0) {
                        currentItem.children = [];
                        $(item).children(o.listType).children(o.items).each(function() {
                            var level = _recursiveItems(this);
                            currentItem.children.push(level);
                        });
                    }
                    return currentItem;
                }
            }
        },

        toArray: function() {

            var o = $.extend({}, this.options, this),
                sDepth = o.startDepthCount || 0,
                ret = [],
                left = 2,
                list = this,
                element = list.el.find(list.options.listNodeName).first();

            var items = element.children(list.options.itemNodeName);
            items.each(function() {
                left = _recursiveArray($(this), sDepth + 1, left);
            });

            ret = ret.sort(function(a, b) {
                return (a.left - b.left);
            });

            return ret;

            function _recursiveArray(item, depth, left) {

                var right = left + 1,
                    id,
                    pid;

                if (item.children(o.options.listNodeName).children(o.options.itemNodeName).length > 0) {
                    depth++;
                    item.children(o.options.listNodeName).children(o.options.itemNodeName).each(function() {
                        right = _recursiveArray($(this), depth, right);
                    });
                    depth--;
                }

                id = item.data().id;


                if (depth === sDepth + 1) {
                    pid = o.rootID;
                } else {

                    var parentItem = (item.parent(o.options.listNodeName)
                        .parent(o.options.itemNodeName)
                        .data());
                    pid = parentItem.id;

                }

                if (id) {
                    ret.push({
                        "id": id,
                        "parent_id": pid,
                        "depth": depth,
                        "left": left,
                        "right": right
                    });
                }

                left = right + 1;
                return left;
            }

        },

        reset: function() {
            this.mouse = {
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                lastX: 0,
                lastY: 0,
                nowX: 0,
                nowY: 0,
                distX: 0,
                distY: 0,
                dirAx: 0,
                dirX: 0,
                dirY: 0,
                lastDirX: 0,
                lastDirY: 0,
                distAxX: 0,
                distAxY: 0
            };
            this.isTouch = false;
            this.moving = false;
            this.dragEl = null;
            this.dragRootEl = null;
            this.dragDepth = 0;
            this.hasNewRoot = false;
            this.pointEl = null;
        },

        expandItem: function(li) {
            li.removeClass(this.options.collapsedClass);
        },

        collapseItem: function(li) {
            var lists = li.children(this.options.listNodeName);
            if (lists.length) {
                li.addClass(this.options.collapsedClass);
            }
        },

        expandAll: function() {
            var list = this;
            list.el.find(list.options.itemNodeName).each(function() {
                list.expandItem($(this));
            });
        },

        collapseAll: function() {
            var list = this;
            list.el.find(list.options.itemNodeName).each(function() {
                list.collapseItem($(this));
            });
        },

        setParent: function(li) {
            //Check if li is an element of itemNodeName type and has children
            if (li.is(this.options.itemNodeName) && li.children(this.options.listNodeName).length) {
                // make sure NOT showing two or more sets data-action buttons
                li.children('[data-action]').remove();
                li.prepend($(this.options.expandBtnHTML));
                li.prepend($(this.options.collapseBtnHTML));
            }
        },

        unsetParent: function(li) {
            li.removeClass(this.options.collapsedClass);
            li.children('[data-action]').remove();
            li.children(this.options.listNodeName).remove();
        },

        dragStart: function(e) {
            var mouse = this.mouse,
                target = $(e.target),
                dragItem = target.closest(this.options.itemNodeName),
                position = {
                    top  : e.pageY,
                    left : e.pageX
                };

            var continueExecution = this.options.onDragStart.call(this, this.el, dragItem, position);

            if (typeof continueExecution !== 'undefined' && continueExecution === false) {
                return;
            }

            this.placeEl.css('height', dragItem.height());

            mouse.offsetX = e.pageX - dragItem.offset().left;
            mouse.offsetY = e.pageY - dragItem.offset().top;
            mouse.startX = mouse.lastX = e.pageX;
            mouse.startY = mouse.lastY = e.pageY;

            this.dragRootEl = this.el;
            this.dragEl = $(document.createElement(this.options.listNodeName)).addClass(this.options.listClass + ' ' + this.options.dragClass);
            this.dragEl.css('width', dragItem.outerWidth());

            this.setIndexOfItem(dragItem);

            // fix for zepto.js
            //dragItem.after(this.placeEl).detach().appendTo(this.dragEl);
            dragItem.after(this.placeEl);
            dragItem[0].parentNode.removeChild(dragItem[0]);
            dragItem.appendTo(this.dragEl);

            $(document.body).append(this.dragEl);
            this.dragEl.css({
                'left': e.pageX - mouse.offsetX,
                'top': e.pageY - mouse.offsetY
            });
            // total depth of dragging item
            var i, depth,
                items = this.dragEl.find(this.options.itemNodeName);
            for (i = 0; i < items.length; i++) {
                depth = $(items[i]).parents(this.options.listNodeName).length;
                if (depth > this.dragDepth) {
                    this.dragDepth = depth;
                }
            }
        },

        //Create sublevel.
        //  element : element which become parent
        //  item    : something to place into new sublevel
        createSubLevel: function(element, item) {
            var list = $('<' + this.options.listNodeName + '/>').addClass(this.options.listClass);
            if (item) list.append(item);
            element.append(list);
            this.setParent(element);
            return list;
        },

        setIndexOfItem: function(item, index) {
            index = index || [];

            index.unshift(item.index());

            if ($(item[0].parentNode)[0] !== this.dragRootEl[0]) {
                this.setIndexOfItem($(item[0].parentNode), index);
            }
            else {
                this.dragEl.data('indexOfItem', index);
            }
        },

        restoreItemAtIndex: function(dragElement, indexArray) {
            var currentEl = this.el,
                lastIndex = indexArray.length - 1;

            //Put drag element at current element position.
            function placeElement(currentEl, dragElement) {
                if (indexArray[lastIndex] === 0) {
                    $(currentEl).prepend(dragElement.clone(true)); //using true saves added to element events.
                }
                else {
                    $(currentEl.children[indexArray[lastIndex] - 1]).after(dragElement.clone(true)); //using true saves added to element events.
                }
            }
            //Diggin through indexArray to get home for dragElement.
            for (var i = 0; i < indexArray.length; i++) {
                if (lastIndex === parseInt(i)) {
                    placeElement(currentEl, dragElement);
                    return;
                }
                //element can have no indexes, so we have to use conditional here to avoid errors.
                //if element doesn't exist we defenetly need to add new list.
                var element = (currentEl[0]) ? currentEl[0] : currentEl;
                var nextEl  = element.children[indexArray[i]];
                currentEl   = (!nextEl) ? this.createSubLevel($(element)) : nextEl;
            }
        },

        dragStop: function(e) {
            // fix for zepto.js
            //this.placeEl.replaceWith(this.dragEl.children(this.options.itemNodeName + ':first').detach());
            var position = {
                top  : e.pageY,
                left : e.pageX
            };
            //Get indexArray of item at drag start.
            var srcIndex = this.dragEl.data('indexOfItem');

            var el = this.dragEl.children(this.options.itemNodeName).first();

            el[0].parentNode.removeChild(el[0]);

            this.dragEl.remove(); //Remove dragEl, cause it can affect on indexing in html collection.

            //Before drag stop callback
            var continueExecution = this.options.beforeDragStop.call(this, this.el, el, this.placeEl.parent());
            if (typeof continueExecution !== 'undefined' && continueExecution === false) {
                var parent = this.placeEl.parent();
                this.placeEl.remove();
                if (!parent.children().length) {
                    this.unsetParent(parent.parent());
                }
                this.restoreItemAtIndex(el, srcIndex);
                this.reset();
                return;
            }

            this.placeEl.replaceWith(el);

            if (this.hasNewRoot) {
                if (this.options.fixed === true) {
                    this.restoreItemAtIndex(el, srcIndex);
                }
                else {
                    this.el.trigger('lostItem');
                }
                this.dragRootEl.trigger('gainedItem');
            }
            else {
                this.dragRootEl.trigger('change');
            }

            this.options.callback.call(this, this.dragRootEl, el, position);

            this.reset();
        },

        dragMove: function(e) {
            var list, parent, prev, next, depth,
                opt = this.options,
                mouse = this.mouse;

            this.dragEl.css({
                'left': e.pageX - mouse.offsetX,
                'top': e.pageY - mouse.offsetY
            });

            // mouse position last events
            mouse.lastX = mouse.nowX;
            mouse.lastY = mouse.nowY;
            // mouse position this events
            mouse.nowX = e.pageX;
            mouse.nowY = e.pageY;
            // distance mouse moved between events
            mouse.distX = mouse.nowX - mouse.lastX;
            mouse.distY = mouse.nowY - mouse.lastY;
            // direction mouse was moving
            mouse.lastDirX = mouse.dirX;
            mouse.lastDirY = mouse.dirY;
            // direction mouse is now moving (on both axis)
            mouse.dirX = mouse.distX === 0 ? 0 : mouse.distX > 0 ? 1 : -1;
            mouse.dirY = mouse.distY === 0 ? 0 : mouse.distY > 0 ? 1 : -1;
            // axis mouse is now moving on
            var newAx = Math.abs(mouse.distX) > Math.abs(mouse.distY) ? 1 : 0;

            // do nothing on first move
            if (!mouse.moving) {
                mouse.dirAx = newAx;
                mouse.moving = true;
                return;
            }

            // do scrolling if enable
            if (opt.scroll) {
                if (typeof window.jQuery.fn.scrollParent !== 'undefined') {
                    var scrolled = false;
                    var scrollParent = this.el.scrollParent()[0];
                    if (scrollParent !== document && scrollParent.tagName !== 'HTML') {
                        if ((opt.scrollTriggers.bottom + scrollParent.offsetHeight) - e.pageY < opt.scrollSensitivity)
                            scrollParent.scrollTop = scrolled = scrollParent.scrollTop + opt.scrollSpeed;
                        else if (e.pageY - opt.scrollTriggers.top < opt.scrollSensitivity)
                            scrollParent.scrollTop = scrolled = scrollParent.scrollTop - opt.scrollSpeed;

                        if ((opt.scrollTriggers.right + scrollParent.offsetWidth) - e.pageX < opt.scrollSensitivity)
                            scrollParent.scrollLeft = scrolled = scrollParent.scrollLeft + opt.scrollSpeed;
                        else if (e.pageX - opt.scrollTriggers.left < opt.scrollSensitivity)
                            scrollParent.scrollLeft = scrolled = scrollParent.scrollLeft - opt.scrollSpeed;
                    } else {
                        if (e.pageY - $(document).scrollTop() < opt.scrollSensitivity)
                            scrolled = $(document).scrollTop($(document).scrollTop() - opt.scrollSpeed);
                        else if ($(window).height() - (e.pageY - $(document).scrollTop()) < opt.scrollSensitivity)
                            scrolled = $(document).scrollTop($(document).scrollTop() + opt.scrollSpeed);

                        if (e.pageX - $(document).scrollLeft() < opt.scrollSensitivity)
                            scrolled = $(document).scrollLeft($(document).scrollLeft() - opt.scrollSpeed);
                        else if ($(window).width() - (e.pageX - $(document).scrollLeft()) < opt.scrollSensitivity)
                            scrolled = $(document).scrollLeft($(document).scrollLeft() + opt.scrollSpeed);
                    }
                } else {
                    console.warn('To use scrolling you need to have scrollParent() function, check documentation for more information');
                }
            }

            if (this.scrollTimer) {
                clearTimeout(this.scrollTimer);
            }

            if (opt.scroll && scrolled) {
                this.scrollTimer = setTimeout(function() {
                    $(window).trigger(e);
                }, 10);
            }

            // calc distance moved on this axis (and direction)
            if (mouse.dirAx !== newAx) {
                mouse.distAxX = 0;
                mouse.distAxY = 0;
            }
            else {
                mouse.distAxX += Math.abs(mouse.distX);
                if (mouse.dirX !== 0 && mouse.dirX !== mouse.lastDirX) {
                    mouse.distAxX = 0;
                }
                mouse.distAxY += Math.abs(mouse.distY);
                if (mouse.dirY !== 0 && mouse.dirY !== mouse.lastDirY) {
                    mouse.distAxY = 0;
                }
            }
            mouse.dirAx = newAx;

            /**
             * move horizontal
             */
            if (mouse.dirAx && mouse.distAxX >= opt.threshold) {
                // reset move distance on x-axis for new phase
                mouse.distAxX = 0;
                prev = this.placeEl.prev(opt.itemNodeName);
                // increase horizontal level if previous sibling exists, is not collapsed, and can have children
                if (mouse.distX > 0 && prev.length && !prev.hasClass(opt.collapsedClass) && !prev.hasClass(opt.noChildrenClass)) {
                    // cannot increase level when item above is collapsed
                    list = prev.find(opt.listNodeName).last();
                    // check if depth limit has reached
                    depth = this.placeEl.parents(opt.listNodeName).length;
                    if (depth + this.dragDepth <= opt.maxDepth) {
                        // create new sub-level if one doesn't exist
                        if (!list.length) {
                            this.createSubLevel(prev, this.placeEl);
                        }
                        else {
                            // else append to next level up
                            list = prev.children(opt.listNodeName).last();
                            list.append(this.placeEl);
                        }
                    }
                }
                // decrease horizontal level
                if (mouse.distX < 0) {
                    // we can't decrease a level if an item preceeds the current one
                    next = this.placeEl.next(opt.itemNodeName);
                    if (!next.length) {
                        parent = this.placeEl.parent();
                        this.placeEl.closest(opt.itemNodeName).after(this.placeEl);
                        if (!parent.children().length) {
                            this.unsetParent(parent.parent());
                        }
                    }
                }
            }

            var isEmpty = false;

            // find list item under cursor
            if (!hasPointerEvents) {
                this.dragEl[0].style.visibility = 'hidden';
            }
            this.pointEl = $(document.elementFromPoint(e.pageX - document.body.scrollLeft, e.pageY - (window.pageYOffset || document.documentElement.scrollTop)));
            if (!hasPointerEvents) {
                this.dragEl[0].style.visibility = 'visible';
            }
            if (this.pointEl.hasClass(opt.handleClass)) {
                this.pointEl = this.pointEl.closest(opt.itemNodeName);
            }
            if (this.pointEl.hasClass(opt.emptyClass)) {
                isEmpty = true;
            }
            else if (!this.pointEl.length || !this.pointEl.hasClass(opt.itemClass)) {
                return;
            }

            // find parent list of item under cursor
            var pointElRoot = this.pointEl.closest('.' + opt.rootClass),
                isNewRoot = this.dragRootEl.data('nestable-id') !== pointElRoot.data('nestable-id');

            /**
             * move vertical
             */
            if (!mouse.dirAx || isNewRoot || isEmpty) {
                // check if groups match if dragging over new root
                if (isNewRoot && opt.group !== pointElRoot.data('nestable-group')) {
                    return;
                }

                // fixed item's depth, use for some list has specific type, eg:'Volume, Section, Chapter ...'
                if (this.options.fixedDepth && this.dragDepth + 1 !== this.pointEl.parents(opt.listNodeName).length) {
                    return;
                }

                // check depth limit
                depth = this.dragDepth - 1 + this.pointEl.parents(opt.listNodeName).length;
                if (depth > opt.maxDepth) {
                    return;
                }
                var before = e.pageY < (this.pointEl.offset().top + this.pointEl.height() / 2);
                parent = this.placeEl.parent();
                // if empty create new list to replace empty placeholder
                if (isEmpty) {
                    list = $(document.createElement(opt.listNodeName)).addClass(opt.listClass);
                    list.append(this.placeEl);
                    this.pointEl.replaceWith(list);
                }
                else if (before) {
                    this.pointEl.before(this.placeEl);
                }
                else {
                    this.pointEl.after(this.placeEl);
                }
                if (!parent.children().length) {
                    this.unsetParent(parent.parent());
                }
                if (!this.dragRootEl.find(opt.itemNodeName).length) {
                    this.appendEmptyElement(this.dragRootEl);
                }
                // parent root list has changed
                this.dragRootEl = pointElRoot;
                if (isNewRoot) {
                    this.hasNewRoot = this.el[0] !== this.dragRootEl[0];
                }
            }
        },

        // Append the .dd-empty div to the list so it can be populated and styled
        appendEmptyElement: function(element) {
            element.append('<div class="' + this.options.emptyClass + '"/>');
        }
    };

    $.fn.nestable = function(params) {
        var lists  = this,
            retval = this,
            args   = arguments;

        if (!('Nestable' in window)) {
            window.Nestable = {};
            Nestable.counter = 0;
        }

        lists.each(function() {
            var plugin = $(this).data("nestable");

            if (!plugin) {
                Nestable.counter++;
                $(this).data("nestable", new Plugin(this, params));
                $(this).data("nestable-id", Nestable.counter);
            }
            else {
                if (typeof params === 'string' && typeof plugin[params] === 'function') {
                    if (args.length > 1){
                        var pluginArgs = [];
                        for (var i = 1; i < args.length; i++) {
                            pluginArgs.push(args[i]);
                        }
                        retval = plugin[params].apply(plugin, pluginArgs);
                    }
                    else {
                        retval = plugin[params]();
                    }
                }
            }
        });

        return retval || lists;
    };

})(window.jQuery || window.Zepto, window, document);


/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/setimmediate/setImmediate.js":
/*!***************************************************!*\
  !*** ./node_modules/setimmediate/setImmediate.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global, process) {(function (global, undefined) {
    "use strict";

    if (global.setImmediate) {
        return;
    }

    var nextHandle = 1; // Spec says greater than zero
    var tasksByHandle = {};
    var currentlyRunningATask = false;
    var doc = global.document;
    var registerImmediate;

    function setImmediate(callback) {
      // Callback can either be a function or a string
      if (typeof callback !== "function") {
        callback = new Function("" + callback);
      }
      // Copy function arguments
      var args = new Array(arguments.length - 1);
      for (var i = 0; i < args.length; i++) {
          args[i] = arguments[i + 1];
      }
      // Store and register the task
      var task = { callback: callback, args: args };
      tasksByHandle[nextHandle] = task;
      registerImmediate(nextHandle);
      return nextHandle++;
    }

    function clearImmediate(handle) {
        delete tasksByHandle[handle];
    }

    function run(task) {
        var callback = task.callback;
        var args = task.args;
        switch (args.length) {
        case 0:
            callback();
            break;
        case 1:
            callback(args[0]);
            break;
        case 2:
            callback(args[0], args[1]);
            break;
        case 3:
            callback(args[0], args[1], args[2]);
            break;
        default:
            callback.apply(undefined, args);
            break;
        }
    }

    function runIfPresent(handle) {
        // From the spec: "Wait until any invocations of this algorithm started before this one have completed."
        // So if we're currently running a task, we'll need to delay this invocation.
        if (currentlyRunningATask) {
            // Delay by doing a setTimeout. setImmediate was tried instead, but in Firefox 7 it generated a
            // "too much recursion" error.
            setTimeout(runIfPresent, 0, handle);
        } else {
            var task = tasksByHandle[handle];
            if (task) {
                currentlyRunningATask = true;
                try {
                    run(task);
                } finally {
                    clearImmediate(handle);
                    currentlyRunningATask = false;
                }
            }
        }
    }

    function installNextTickImplementation() {
        registerImmediate = function(handle) {
            process.nextTick(function () { runIfPresent(handle); });
        };
    }

    function canUsePostMessage() {
        // The test against `importScripts` prevents this implementation from being installed inside a web worker,
        // where `global.postMessage` means something completely different and can't be used for this purpose.
        if (global.postMessage && !global.importScripts) {
            var postMessageIsAsynchronous = true;
            var oldOnMessage = global.onmessage;
            global.onmessage = function() {
                postMessageIsAsynchronous = false;
            };
            global.postMessage("", "*");
            global.onmessage = oldOnMessage;
            return postMessageIsAsynchronous;
        }
    }

    function installPostMessageImplementation() {
        // Installs an event handler on `global` for the `message` event: see
        // * https://developer.mozilla.org/en/DOM/window.postMessage
        // * http://www.whatwg.org/specs/web-apps/current-work/multipage/comms.html#crossDocumentMessages

        var messagePrefix = "setImmediate$" + Math.random() + "$";
        var onGlobalMessage = function(event) {
            if (event.source === global &&
                typeof event.data === "string" &&
                event.data.indexOf(messagePrefix) === 0) {
                runIfPresent(+event.data.slice(messagePrefix.length));
            }
        };

        if (global.addEventListener) {
            global.addEventListener("message", onGlobalMessage, false);
        } else {
            global.attachEvent("onmessage", onGlobalMessage);
        }

        registerImmediate = function(handle) {
            global.postMessage(messagePrefix + handle, "*");
        };
    }

    function installMessageChannelImplementation() {
        var channel = new MessageChannel();
        channel.port1.onmessage = function(event) {
            var handle = event.data;
            runIfPresent(handle);
        };

        registerImmediate = function(handle) {
            channel.port2.postMessage(handle);
        };
    }

    function installReadyStateChangeImplementation() {
        var html = doc.documentElement;
        registerImmediate = function(handle) {
            // Create a <script> element; its readystatechange event will be fired asynchronously once it is inserted
            // into the document. Do so, thus queuing up the task. Remember to clean up once it's been called.
            var script = doc.createElement("script");
            script.onreadystatechange = function () {
                runIfPresent(handle);
                script.onreadystatechange = null;
                html.removeChild(script);
                script = null;
            };
            html.appendChild(script);
        };
    }

    function installSetTimeoutImplementation() {
        registerImmediate = function(handle) {
            setTimeout(runIfPresent, 0, handle);
        };
    }

    // If supported, we should attach to the prototype of global, since that is where setTimeout et al. live.
    var attachTo = Object.getPrototypeOf && Object.getPrototypeOf(global);
    attachTo = attachTo && attachTo.setTimeout ? attachTo : global;

    // Don't get fooled by e.g. browserify environments.
    if ({}.toString.call(global.process) === "[object process]") {
        // For Node.js before 0.9
        installNextTickImplementation();

    } else if (canUsePostMessage()) {
        // For non-IE10 modern browsers
        installPostMessageImplementation();

    } else if (global.MessageChannel) {
        // For web workers, where supported
        installMessageChannelImplementation();

    } else if (doc && "onreadystatechange" in doc.createElement("script")) {
        // For IE 6–8
        installReadyStateChangeImplementation();

    } else {
        // For older browsers
        installSetTimeoutImplementation();
    }

    attachTo.setImmediate = setImmediate;
    attachTo.clearImmediate = clearImmediate;
}(typeof self === "undefined" ? typeof global === "undefined" ? this : global : self));

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/sweetalert/dist/sweetalert.min.js":
/*!********************************************************!*\
  !*** ./node_modules/sweetalert/dist/sweetalert.min.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(setImmediate, clearImmediate) {!function(t,e){ true?module.exports=e():undefined}(this,function(){return function(t){function e(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,e),r.l=!0,r.exports}var n={};return e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:o})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=8)}([function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o="swal-button";e.CLASS_NAMES={MODAL:"swal-modal",OVERLAY:"swal-overlay",SHOW_MODAL:"swal-overlay--show-modal",MODAL_TITLE:"swal-title",MODAL_TEXT:"swal-text",ICON:"swal-icon",ICON_CUSTOM:"swal-icon--custom",CONTENT:"swal-content",FOOTER:"swal-footer",BUTTON_CONTAINER:"swal-button-container",BUTTON:o,CONFIRM_BUTTON:o+"--confirm",CANCEL_BUTTON:o+"--cancel",DANGER_BUTTON:o+"--danger",BUTTON_LOADING:o+"--loading",BUTTON_LOADER:o+"__loader"},e.default=e.CLASS_NAMES},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.getNode=function(t){var e="."+t;return document.querySelector(e)},e.stringToNode=function(t){var e=document.createElement("div");return e.innerHTML=t.trim(),e.firstChild},e.insertAfter=function(t,e){var n=e.nextSibling;e.parentNode.insertBefore(t,n)},e.removeNode=function(t){t.parentElement.removeChild(t)},e.throwErr=function(t){throw t=t.replace(/ +(?= )/g,""),"SweetAlert: "+(t=t.trim())},e.isPlainObject=function(t){if("[object Object]"!==Object.prototype.toString.call(t))return!1;var e=Object.getPrototypeOf(t);return null===e||e===Object.prototype},e.ordinalSuffixOf=function(t){var e=t%10,n=t%100;return 1===e&&11!==n?t+"st":2===e&&12!==n?t+"nd":3===e&&13!==n?t+"rd":t+"th"}},function(t,e,n){"use strict";function o(t){for(var n in t)e.hasOwnProperty(n)||(e[n]=t[n])}Object.defineProperty(e,"__esModule",{value:!0}),o(n(25));var r=n(26);e.overlayMarkup=r.default,o(n(27)),o(n(28)),o(n(29));var i=n(0),a=i.default.MODAL_TITLE,s=i.default.MODAL_TEXT,c=i.default.ICON,l=i.default.FOOTER;e.iconMarkup='\n  <div class="'+c+'"></div>',e.titleMarkup='\n  <div class="'+a+'"></div>\n',e.textMarkup='\n  <div class="'+s+'"></div>',e.footerMarkup='\n  <div class="'+l+'"></div>\n'},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1);e.CONFIRM_KEY="confirm",e.CANCEL_KEY="cancel";var r={visible:!0,text:null,value:null,className:"",closeModal:!0},i=Object.assign({},r,{visible:!1,text:"Cancel",value:null}),a=Object.assign({},r,{text:"OK",value:!0});e.defaultButtonList={cancel:i,confirm:a};var s=function(t){switch(t){case e.CONFIRM_KEY:return a;case e.CANCEL_KEY:return i;default:var n=t.charAt(0).toUpperCase()+t.slice(1);return Object.assign({},r,{text:n,value:t})}},c=function(t,e){var n=s(t);return!0===e?Object.assign({},n,{visible:!0}):"string"==typeof e?Object.assign({},n,{visible:!0,text:e}):o.isPlainObject(e)?Object.assign({visible:!0},n,e):Object.assign({},n,{visible:!1})},l=function(t){for(var e={},n=0,o=Object.keys(t);n<o.length;n++){var r=o[n],a=t[r],s=c(r,a);e[r]=s}return e.cancel||(e.cancel=i),e},u=function(t){var n={};switch(t.length){case 1:n[e.CANCEL_KEY]=Object.assign({},i,{visible:!1});break;case 2:n[e.CANCEL_KEY]=c(e.CANCEL_KEY,t[0]),n[e.CONFIRM_KEY]=c(e.CONFIRM_KEY,t[1]);break;default:o.throwErr("Invalid number of 'buttons' in array ("+t.length+").\n      If you want more than 2 buttons, you need to use an object!")}return n};e.getButtonListOpts=function(t){var n=e.defaultButtonList;return"string"==typeof t?n[e.CONFIRM_KEY]=c(e.CONFIRM_KEY,t):Array.isArray(t)?n=u(t):o.isPlainObject(t)?n=l(t):!0===t?n=u([!0,!0]):!1===t?n=u([!1,!1]):void 0===t&&(n=e.defaultButtonList),n}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(2),i=n(0),a=i.default.MODAL,s=i.default.OVERLAY,c=n(30),l=n(31),u=n(32),f=n(33);e.injectElIntoModal=function(t){var e=o.getNode(a),n=o.stringToNode(t);return e.appendChild(n),n};var d=function(t){t.className=a,t.textContent=""},p=function(t,e){d(t);var n=e.className;n&&t.classList.add(n)};e.initModalContent=function(t){var e=o.getNode(a);p(e,t),c.default(t.icon),l.initTitle(t.title),l.initText(t.text),f.default(t.content),u.default(t.buttons,t.dangerMode)};var m=function(){var t=o.getNode(s),e=o.stringToNode(r.modalMarkup);t.appendChild(e)};e.default=m},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(3),r={isOpen:!1,promise:null,actions:{},timer:null},i=Object.assign({},r);e.resetState=function(){i=Object.assign({},r)},e.setActionValue=function(t){if("string"==typeof t)return a(o.CONFIRM_KEY,t);for(var e in t)a(e,t[e])};var a=function(t,e){i.actions[t]||(i.actions[t]={}),Object.assign(i.actions[t],{value:e})};e.setActionOptionsFor=function(t,e){var n=(void 0===e?{}:e).closeModal,o=void 0===n||n;Object.assign(i.actions[t],{closeModal:o})},e.default=i},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(3),i=n(0),a=i.default.OVERLAY,s=i.default.SHOW_MODAL,c=i.default.BUTTON,l=i.default.BUTTON_LOADING,u=n(5);e.openModal=function(){o.getNode(a).classList.add(s),u.default.isOpen=!0};var f=function(){o.getNode(a).classList.remove(s),u.default.isOpen=!1};e.onAction=function(t){void 0===t&&(t=r.CANCEL_KEY);var e=u.default.actions[t],n=e.value;if(!1===e.closeModal){var i=c+"--"+t;o.getNode(i).classList.add(l)}else f();u.default.promise.resolve(n)},e.getState=function(){var t=Object.assign({},u.default);return delete t.promise,delete t.timer,t},e.stopLoading=function(){for(var t=document.querySelectorAll("."+c),e=0;e<t.length;e++){t[e].classList.remove(l)}}},function(t,e){var n;n=function(){return this}();try{n=n||Function("return this")()||(0,eval)("this")}catch(t){"object"==typeof window&&(n=window)}t.exports=n},function(t,e,n){(function(e){t.exports=e.sweetAlert=n(9)}).call(e,n(7))},function(t,e,n){(function(e){t.exports=e.swal=n(10)}).call(e,n(7))},function(t,e,n){"undefined"!=typeof window&&n(11),n(16);var o=n(23).default;t.exports=o},function(t,e,n){var o=n(12);"string"==typeof o&&(o=[[t.i,o,""]]);var r={insertAt:"top"};r.transform=void 0;n(14)(o,r);o.locals&&(t.exports=o.locals)},function(t,e,n){e=t.exports=n(13)(void 0),e.push([t.i,'.swal-icon--error{border-color:#f27474;-webkit-animation:animateErrorIcon .5s;animation:animateErrorIcon .5s}.swal-icon--error__x-mark{position:relative;display:block;-webkit-animation:animateXMark .5s;animation:animateXMark .5s}.swal-icon--error__line{position:absolute;height:5px;width:47px;background-color:#f27474;display:block;top:37px;border-radius:2px}.swal-icon--error__line--left{-webkit-transform:rotate(45deg);transform:rotate(45deg);left:17px}.swal-icon--error__line--right{-webkit-transform:rotate(-45deg);transform:rotate(-45deg);right:16px}@-webkit-keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@-webkit-keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}@keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}.swal-icon--warning{border-color:#f8bb86;-webkit-animation:pulseWarning .75s infinite alternate;animation:pulseWarning .75s infinite alternate}.swal-icon--warning__body{width:5px;height:47px;top:10px;border-radius:2px;margin-left:-2px}.swal-icon--warning__body,.swal-icon--warning__dot{position:absolute;left:50%;background-color:#f8bb86}.swal-icon--warning__dot{width:7px;height:7px;border-radius:50%;margin-left:-4px;bottom:-11px}@-webkit-keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}@keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}.swal-icon--success{border-color:#a5dc86}.swal-icon--success:after,.swal-icon--success:before{content:"";border-radius:50%;position:absolute;width:60px;height:120px;background:#fff;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.swal-icon--success:before{border-radius:120px 0 0 120px;top:-7px;left:-33px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:60px 60px;transform-origin:60px 60px}.swal-icon--success:after{border-radius:0 120px 120px 0;top:-11px;left:30px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:0 60px;transform-origin:0 60px;-webkit-animation:rotatePlaceholder 4.25s ease-in;animation:rotatePlaceholder 4.25s ease-in}.swal-icon--success__ring{width:80px;height:80px;border:4px solid hsla(98,55%,69%,.2);border-radius:50%;box-sizing:content-box;position:absolute;left:-4px;top:-4px;z-index:2}.swal-icon--success__hide-corners{width:5px;height:90px;background-color:#fff;padding:1px;position:absolute;left:28px;top:8px;z-index:1;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.swal-icon--success__line{height:5px;background-color:#a5dc86;display:block;border-radius:2px;position:absolute;z-index:2}.swal-icon--success__line--tip{width:25px;left:14px;top:46px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:animateSuccessTip .75s;animation:animateSuccessTip .75s}.swal-icon--success__line--long{width:47px;right:8px;top:38px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:animateSuccessLong .75s;animation:animateSuccessLong .75s}@-webkit-keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@-webkit-keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@-webkit-keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}@keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}.swal-icon--info{border-color:#c9dae1}.swal-icon--info:before{width:5px;height:29px;bottom:17px;border-radius:2px;margin-left:-2px}.swal-icon--info:after,.swal-icon--info:before{content:"";position:absolute;left:50%;background-color:#c9dae1}.swal-icon--info:after{width:7px;height:7px;border-radius:50%;margin-left:-3px;top:19px}.swal-icon{width:80px;height:80px;border-width:4px;border-style:solid;border-radius:50%;padding:0;position:relative;box-sizing:content-box;margin:20px auto}.swal-icon:first-child{margin-top:32px}.swal-icon--custom{width:auto;height:auto;max-width:100%;border:none;border-radius:0}.swal-icon img{max-width:100%;max-height:100%}.swal-title{color:rgba(0,0,0,.65);font-weight:600;text-transform:none;position:relative;display:block;padding:13px 16px;font-size:27px;line-height:normal;text-align:center;margin-bottom:0}.swal-title:first-child{margin-top:26px}.swal-title:not(:first-child){padding-bottom:0}.swal-title:not(:last-child){margin-bottom:13px}.swal-text{font-size:16px;position:relative;float:none;line-height:normal;vertical-align:top;text-align:left;display:inline-block;margin:0;padding:0 10px;font-weight:400;color:rgba(0,0,0,.64);max-width:calc(100% - 20px);overflow-wrap:break-word;box-sizing:border-box}.swal-text:first-child{margin-top:45px}.swal-text:last-child{margin-bottom:45px}.swal-footer{text-align:right;padding-top:13px;margin-top:13px;padding:13px 16px;border-radius:inherit;border-top-left-radius:0;border-top-right-radius:0}.swal-button-container{margin:5px;display:inline-block;position:relative}.swal-button{background-color:#7cd1f9;color:#fff;border:none;box-shadow:none;border-radius:5px;font-weight:600;font-size:14px;padding:10px 24px;margin:0;cursor:pointer}.swal-button:not([disabled]):hover{background-color:#78cbf2}.swal-button:active{background-color:#70bce0}.swal-button:focus{outline:none;box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(43,114,165,.29)}.swal-button[disabled]{opacity:.5;cursor:default}.swal-button::-moz-focus-inner{border:0}.swal-button--cancel{color:#555;background-color:#efefef}.swal-button--cancel:not([disabled]):hover{background-color:#e8e8e8}.swal-button--cancel:active{background-color:#d7d7d7}.swal-button--cancel:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(116,136,150,.29)}.swal-button--danger{background-color:#e64942}.swal-button--danger:not([disabled]):hover{background-color:#df4740}.swal-button--danger:active{background-color:#cf423b}.swal-button--danger:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(165,43,43,.29)}.swal-content{padding:0 20px;margin-top:20px;font-size:medium}.swal-content:last-child{margin-bottom:20px}.swal-content__input,.swal-content__textarea{-webkit-appearance:none;background-color:#fff;border:none;font-size:14px;display:block;box-sizing:border-box;width:100%;border:1px solid rgba(0,0,0,.14);padding:10px 13px;border-radius:2px;transition:border-color .2s}.swal-content__input:focus,.swal-content__textarea:focus{outline:none;border-color:#6db8ff}.swal-content__textarea{resize:vertical}.swal-button--loading{color:transparent}.swal-button--loading~.swal-button__loader{opacity:1}.swal-button__loader{position:absolute;height:auto;width:43px;z-index:2;left:50%;top:50%;-webkit-transform:translateX(-50%) translateY(-50%);transform:translateX(-50%) translateY(-50%);text-align:center;pointer-events:none;opacity:0}.swal-button__loader div{display:inline-block;float:none;vertical-align:baseline;width:9px;height:9px;padding:0;border:none;margin:2px;opacity:.4;border-radius:7px;background-color:hsla(0,0%,100%,.9);transition:background .2s;-webkit-animation:swal-loading-anim 1s infinite;animation:swal-loading-anim 1s infinite}.swal-button__loader div:nth-child(3n+2){-webkit-animation-delay:.15s;animation-delay:.15s}.swal-button__loader div:nth-child(3n+3){-webkit-animation-delay:.3s;animation-delay:.3s}@-webkit-keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}@keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}.swal-overlay{position:fixed;top:0;bottom:0;left:0;right:0;text-align:center;font-size:0;overflow-y:auto;background-color:rgba(0,0,0,.4);z-index:10000;pointer-events:none;opacity:0;transition:opacity .3s}.swal-overlay:before{content:" ";display:inline-block;vertical-align:middle;height:100%}.swal-overlay--show-modal{opacity:1;pointer-events:auto}.swal-overlay--show-modal .swal-modal{opacity:1;pointer-events:auto;box-sizing:border-box;-webkit-animation:showSweetAlert .3s;animation:showSweetAlert .3s;will-change:transform}.swal-modal{width:478px;opacity:0;pointer-events:none;background-color:#fff;text-align:center;border-radius:5px;position:static;margin:20px auto;display:inline-block;vertical-align:middle;-webkit-transform:scale(1);transform:scale(1);-webkit-transform-origin:50% 50%;transform-origin:50% 50%;z-index:10001;transition:opacity .2s,-webkit-transform .3s;transition:transform .3s,opacity .2s;transition:transform .3s,opacity .2s,-webkit-transform .3s}@media (max-width:500px){.swal-modal{width:calc(100% - 20px)}}@-webkit-keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}@keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}',""])},function(t,e){function n(t,e){var n=t[1]||"",r=t[3];if(!r)return n;if(e&&"function"==typeof btoa){var i=o(r);return[n].concat(r.sources.map(function(t){return"/*# sourceURL="+r.sourceRoot+t+" */"})).concat([i]).join("\n")}return[n].join("\n")}function o(t){return"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(t))))+" */"}t.exports=function(t){var e=[];return e.toString=function(){return this.map(function(e){var o=n(e,t);return e[2]?"@media "+e[2]+"{"+o+"}":o}).join("")},e.i=function(t,n){"string"==typeof t&&(t=[[null,t,""]]);for(var o={},r=0;r<this.length;r++){var i=this[r][0];"number"==typeof i&&(o[i]=!0)}for(r=0;r<t.length;r++){var a=t[r];"number"==typeof a[0]&&o[a[0]]||(n&&!a[2]?a[2]=n:n&&(a[2]="("+a[2]+") and ("+n+")"),e.push(a))}},e}},function(t,e,n){function o(t,e){for(var n=0;n<t.length;n++){var o=t[n],r=m[o.id];if(r){r.refs++;for(var i=0;i<r.parts.length;i++)r.parts[i](o.parts[i]);for(;i<o.parts.length;i++)r.parts.push(u(o.parts[i],e))}else{for(var a=[],i=0;i<o.parts.length;i++)a.push(u(o.parts[i],e));m[o.id]={id:o.id,refs:1,parts:a}}}}function r(t,e){for(var n=[],o={},r=0;r<t.length;r++){var i=t[r],a=e.base?i[0]+e.base:i[0],s=i[1],c=i[2],l=i[3],u={css:s,media:c,sourceMap:l};o[a]?o[a].parts.push(u):n.push(o[a]={id:a,parts:[u]})}return n}function i(t,e){var n=v(t.insertInto);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var o=w[w.length-1];if("top"===t.insertAt)o?o.nextSibling?n.insertBefore(e,o.nextSibling):n.appendChild(e):n.insertBefore(e,n.firstChild),w.push(e);else{if("bottom"!==t.insertAt)throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");n.appendChild(e)}}function a(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t);var e=w.indexOf(t);e>=0&&w.splice(e,1)}function s(t){var e=document.createElement("style");return t.attrs.type="text/css",l(e,t.attrs),i(t,e),e}function c(t){var e=document.createElement("link");return t.attrs.type="text/css",t.attrs.rel="stylesheet",l(e,t.attrs),i(t,e),e}function l(t,e){Object.keys(e).forEach(function(n){t.setAttribute(n,e[n])})}function u(t,e){var n,o,r,i;if(e.transform&&t.css){if(!(i=e.transform(t.css)))return function(){};t.css=i}if(e.singleton){var l=h++;n=g||(g=s(e)),o=f.bind(null,n,l,!1),r=f.bind(null,n,l,!0)}else t.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(n=c(e),o=p.bind(null,n,e),r=function(){a(n),n.href&&URL.revokeObjectURL(n.href)}):(n=s(e),o=d.bind(null,n),r=function(){a(n)});return o(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;o(t=e)}else r()}}function f(t,e,n,o){var r=n?"":o.css;if(t.styleSheet)t.styleSheet.cssText=x(e,r);else{var i=document.createTextNode(r),a=t.childNodes;a[e]&&t.removeChild(a[e]),a.length?t.insertBefore(i,a[e]):t.appendChild(i)}}function d(t,e){var n=e.css,o=e.media;if(o&&t.setAttribute("media",o),t.styleSheet)t.styleSheet.cssText=n;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(n))}}function p(t,e,n){var o=n.css,r=n.sourceMap,i=void 0===e.convertToAbsoluteUrls&&r;(e.convertToAbsoluteUrls||i)&&(o=y(o)),r&&(o+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(r))))+" */");var a=new Blob([o],{type:"text/css"}),s=t.href;t.href=URL.createObjectURL(a),s&&URL.revokeObjectURL(s)}var m={},b=function(t){var e;return function(){return void 0===e&&(e=t.apply(this,arguments)),e}}(function(){return window&&document&&document.all&&!window.atob}),v=function(t){var e={};return function(n){return void 0===e[n]&&(e[n]=t.call(this,n)),e[n]}}(function(t){return document.querySelector(t)}),g=null,h=0,w=[],y=n(15);t.exports=function(t,e){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");e=e||{},e.attrs="object"==typeof e.attrs?e.attrs:{},e.singleton||(e.singleton=b()),e.insertInto||(e.insertInto="head"),e.insertAt||(e.insertAt="bottom");var n=r(t,e);return o(n,e),function(t){for(var i=[],a=0;a<n.length;a++){var s=n[a],c=m[s.id];c.refs--,i.push(c)}if(t){o(r(t,e),e)}for(var a=0;a<i.length;a++){var c=i[a];if(0===c.refs){for(var l=0;l<c.parts.length;l++)c.parts[l]();delete m[c.id]}}}};var x=function(){var t=[];return function(e,n){return t[e]=n,t.filter(Boolean).join("\n")}}()},function(t,e){t.exports=function(t){var e="undefined"!=typeof window&&window.location;if(!e)throw new Error("fixUrls requires window.location");if(!t||"string"!=typeof t)return t;var n=e.protocol+"//"+e.host,o=n+e.pathname.replace(/\/[^\/]*$/,"/");return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,function(t,e){var r=e.trim().replace(/^"(.*)"$/,function(t,e){return e}).replace(/^'(.*)'$/,function(t,e){return e});if(/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(r))return t;var i;return i=0===r.indexOf("//")?r:0===r.indexOf("/")?n+r:o+r.replace(/^\.\//,""),"url("+JSON.stringify(i)+")"})}},function(t,e,n){var o=n(17);"undefined"==typeof window||window.Promise||(window.Promise=o),n(21),String.prototype.includes||(String.prototype.includes=function(t,e){"use strict";return"number"!=typeof e&&(e=0),!(e+t.length>this.length)&&-1!==this.indexOf(t,e)}),Array.prototype.includes||Object.defineProperty(Array.prototype,"includes",{value:function(t,e){if(null==this)throw new TypeError('"this" is null or not defined');var n=Object(this),o=n.length>>>0;if(0===o)return!1;for(var r=0|e,i=Math.max(r>=0?r:o-Math.abs(r),0);i<o;){if(function(t,e){return t===e||"number"==typeof t&&"number"==typeof e&&isNaN(t)&&isNaN(e)}(n[i],t))return!0;i++}return!1}}),"undefined"!=typeof window&&function(t){t.forEach(function(t){t.hasOwnProperty("remove")||Object.defineProperty(t,"remove",{configurable:!0,enumerable:!0,writable:!0,value:function(){this.parentNode.removeChild(this)}})})}([Element.prototype,CharacterData.prototype,DocumentType.prototype])},function(t,e,n){(function(e){!function(n){function o(){}function r(t,e){return function(){t.apply(e,arguments)}}function i(t){if("object"!=typeof this)throw new TypeError("Promises must be constructed via new");if("function"!=typeof t)throw new TypeError("not a function");this._state=0,this._handled=!1,this._value=void 0,this._deferreds=[],f(t,this)}function a(t,e){for(;3===t._state;)t=t._value;if(0===t._state)return void t._deferreds.push(e);t._handled=!0,i._immediateFn(function(){var n=1===t._state?e.onFulfilled:e.onRejected;if(null===n)return void(1===t._state?s:c)(e.promise,t._value);var o;try{o=n(t._value)}catch(t){return void c(e.promise,t)}s(e.promise,o)})}function s(t,e){try{if(e===t)throw new TypeError("A promise cannot be resolved with itself.");if(e&&("object"==typeof e||"function"==typeof e)){var n=e.then;if(e instanceof i)return t._state=3,t._value=e,void l(t);if("function"==typeof n)return void f(r(n,e),t)}t._state=1,t._value=e,l(t)}catch(e){c(t,e)}}function c(t,e){t._state=2,t._value=e,l(t)}function l(t){2===t._state&&0===t._deferreds.length&&i._immediateFn(function(){t._handled||i._unhandledRejectionFn(t._value)});for(var e=0,n=t._deferreds.length;e<n;e++)a(t,t._deferreds[e]);t._deferreds=null}function u(t,e,n){this.onFulfilled="function"==typeof t?t:null,this.onRejected="function"==typeof e?e:null,this.promise=n}function f(t,e){var n=!1;try{t(function(t){n||(n=!0,s(e,t))},function(t){n||(n=!0,c(e,t))})}catch(t){if(n)return;n=!0,c(e,t)}}var d=setTimeout;i.prototype.catch=function(t){return this.then(null,t)},i.prototype.then=function(t,e){var n=new this.constructor(o);return a(this,new u(t,e,n)),n},i.all=function(t){var e=Array.prototype.slice.call(t);return new i(function(t,n){function o(i,a){try{if(a&&("object"==typeof a||"function"==typeof a)){var s=a.then;if("function"==typeof s)return void s.call(a,function(t){o(i,t)},n)}e[i]=a,0==--r&&t(e)}catch(t){n(t)}}if(0===e.length)return t([]);for(var r=e.length,i=0;i<e.length;i++)o(i,e[i])})},i.resolve=function(t){return t&&"object"==typeof t&&t.constructor===i?t:new i(function(e){e(t)})},i.reject=function(t){return new i(function(e,n){n(t)})},i.race=function(t){return new i(function(e,n){for(var o=0,r=t.length;o<r;o++)t[o].then(e,n)})},i._immediateFn="function"==typeof e&&function(t){e(t)}||function(t){d(t,0)},i._unhandledRejectionFn=function(t){"undefined"!=typeof console&&console&&console.warn("Possible Unhandled Promise Rejection:",t)},i._setImmediateFn=function(t){i._immediateFn=t},i._setUnhandledRejectionFn=function(t){i._unhandledRejectionFn=t},void 0!==t&&t.exports?t.exports=i:n.Promise||(n.Promise=i)}(this)}).call(e,n(18).setImmediate)},function(t,e,n){function o(t,e){this._id=t,this._clearFn=e}var r=Function.prototype.apply;e.setTimeout=function(){return new o(r.call(setTimeout,window,arguments),clearTimeout)},e.setInterval=function(){return new o(r.call(setInterval,window,arguments),clearInterval)},e.clearTimeout=e.clearInterval=function(t){t&&t.close()},o.prototype.unref=o.prototype.ref=function(){},o.prototype.close=function(){this._clearFn.call(window,this._id)},e.enroll=function(t,e){clearTimeout(t._idleTimeoutId),t._idleTimeout=e},e.unenroll=function(t){clearTimeout(t._idleTimeoutId),t._idleTimeout=-1},e._unrefActive=e.active=function(t){clearTimeout(t._idleTimeoutId);var e=t._idleTimeout;e>=0&&(t._idleTimeoutId=setTimeout(function(){t._onTimeout&&t._onTimeout()},e))},n(19),e.setImmediate=setImmediate,e.clearImmediate=clearImmediate},function(t,e,n){(function(t,e){!function(t,n){"use strict";function o(t){"function"!=typeof t&&(t=new Function(""+t));for(var e=new Array(arguments.length-1),n=0;n<e.length;n++)e[n]=arguments[n+1];var o={callback:t,args:e};return l[c]=o,s(c),c++}function r(t){delete l[t]}function i(t){var e=t.callback,o=t.args;switch(o.length){case 0:e();break;case 1:e(o[0]);break;case 2:e(o[0],o[1]);break;case 3:e(o[0],o[1],o[2]);break;default:e.apply(n,o)}}function a(t){if(u)setTimeout(a,0,t);else{var e=l[t];if(e){u=!0;try{i(e)}finally{r(t),u=!1}}}}if(!t.setImmediate){var s,c=1,l={},u=!1,f=t.document,d=Object.getPrototypeOf&&Object.getPrototypeOf(t);d=d&&d.setTimeout?d:t,"[object process]"==={}.toString.call(t.process)?function(){s=function(t){e.nextTick(function(){a(t)})}}():function(){if(t.postMessage&&!t.importScripts){var e=!0,n=t.onmessage;return t.onmessage=function(){e=!1},t.postMessage("","*"),t.onmessage=n,e}}()?function(){var e="setImmediate$"+Math.random()+"$",n=function(n){n.source===t&&"string"==typeof n.data&&0===n.data.indexOf(e)&&a(+n.data.slice(e.length))};t.addEventListener?t.addEventListener("message",n,!1):t.attachEvent("onmessage",n),s=function(n){t.postMessage(e+n,"*")}}():t.MessageChannel?function(){var t=new MessageChannel;t.port1.onmessage=function(t){a(t.data)},s=function(e){t.port2.postMessage(e)}}():f&&"onreadystatechange"in f.createElement("script")?function(){var t=f.documentElement;s=function(e){var n=f.createElement("script");n.onreadystatechange=function(){a(e),n.onreadystatechange=null,t.removeChild(n),n=null},t.appendChild(n)}}():function(){s=function(t){setTimeout(a,0,t)}}(),d.setImmediate=o,d.clearImmediate=r}}("undefined"==typeof self?void 0===t?this:t:self)}).call(e,n(7),n(20))},function(t,e){function n(){throw new Error("setTimeout has not been defined")}function o(){throw new Error("clearTimeout has not been defined")}function r(t){if(u===setTimeout)return setTimeout(t,0);if((u===n||!u)&&setTimeout)return u=setTimeout,setTimeout(t,0);try{return u(t,0)}catch(e){try{return u.call(null,t,0)}catch(e){return u.call(this,t,0)}}}function i(t){if(f===clearTimeout)return clearTimeout(t);if((f===o||!f)&&clearTimeout)return f=clearTimeout,clearTimeout(t);try{return f(t)}catch(e){try{return f.call(null,t)}catch(e){return f.call(this,t)}}}function a(){b&&p&&(b=!1,p.length?m=p.concat(m):v=-1,m.length&&s())}function s(){if(!b){var t=r(a);b=!0;for(var e=m.length;e;){for(p=m,m=[];++v<e;)p&&p[v].run();v=-1,e=m.length}p=null,b=!1,i(t)}}function c(t,e){this.fun=t,this.array=e}function l(){}var u,f,d=t.exports={};!function(){try{u="function"==typeof setTimeout?setTimeout:n}catch(t){u=n}try{f="function"==typeof clearTimeout?clearTimeout:o}catch(t){f=o}}();var p,m=[],b=!1,v=-1;d.nextTick=function(t){var e=new Array(arguments.length-1);if(arguments.length>1)for(var n=1;n<arguments.length;n++)e[n-1]=arguments[n];m.push(new c(t,e)),1!==m.length||b||r(s)},c.prototype.run=function(){this.fun.apply(null,this.array)},d.title="browser",d.browser=!0,d.env={},d.argv=[],d.version="",d.versions={},d.on=l,d.addListener=l,d.once=l,d.off=l,d.removeListener=l,d.removeAllListeners=l,d.emit=l,d.prependListener=l,d.prependOnceListener=l,d.listeners=function(t){return[]},d.binding=function(t){throw new Error("process.binding is not supported")},d.cwd=function(){return"/"},d.chdir=function(t){throw new Error("process.chdir is not supported")},d.umask=function(){return 0}},function(t,e,n){"use strict";n(22).polyfill()},function(t,e,n){"use strict";function o(t,e){if(void 0===t||null===t)throw new TypeError("Cannot convert first argument to object");for(var n=Object(t),o=1;o<arguments.length;o++){var r=arguments[o];if(void 0!==r&&null!==r)for(var i=Object.keys(Object(r)),a=0,s=i.length;a<s;a++){var c=i[a],l=Object.getOwnPropertyDescriptor(r,c);void 0!==l&&l.enumerable&&(n[c]=r[c])}}return n}function r(){Object.assign||Object.defineProperty(Object,"assign",{enumerable:!1,configurable:!0,writable:!0,value:o})}t.exports={assign:o,polyfill:r}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(24),r=n(6),i=n(5),a=n(36),s=function(){for(var t=[],e=0;e<arguments.length;e++)t[e]=arguments[e];if("undefined"!=typeof window){var n=a.getOpts.apply(void 0,t);return new Promise(function(t,e){i.default.promise={resolve:t,reject:e},o.default(n),setTimeout(function(){r.openModal()})})}};s.close=r.onAction,s.getState=r.getState,s.setActionValue=i.setActionValue,s.stopLoading=r.stopLoading,s.setDefaults=a.setDefaults,e.default=s},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(0),i=r.default.MODAL,a=n(4),s=n(34),c=n(35),l=n(1);e.init=function(t){o.getNode(i)||(document.body||l.throwErr("You can only use SweetAlert AFTER the DOM has loaded!"),s.default(),a.default()),a.initModalContent(t),c.default(t)},e.default=e.init},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.MODAL;e.modalMarkup='\n  <div class="'+r+'" role="dialog" aria-modal="true"></div>',e.default=e.modalMarkup},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.OVERLAY,i='<div \n    class="'+r+'"\n    tabIndex="-1">\n  </div>';e.default=i},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.ICON;e.errorIconMarkup=function(){var t=r+"--error",e=t+"__line";return'\n    <div class="'+t+'__x-mark">\n      <span class="'+e+" "+e+'--left"></span>\n      <span class="'+e+" "+e+'--right"></span>\n    </div>\n  '},e.warningIconMarkup=function(){var t=r+"--warning";return'\n    <span class="'+t+'__body">\n      <span class="'+t+'__dot"></span>\n    </span>\n  '},e.successIconMarkup=function(){var t=r+"--success";return'\n    <span class="'+t+"__line "+t+'__line--long"></span>\n    <span class="'+t+"__line "+t+'__line--tip"></span>\n\n    <div class="'+t+'__ring"></div>\n    <div class="'+t+'__hide-corners"></div>\n  '}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.CONTENT;e.contentMarkup='\n  <div class="'+r+'">\n\n  </div>\n'},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.BUTTON_CONTAINER,i=o.default.BUTTON,a=o.default.BUTTON_LOADER;e.buttonMarkup='\n  <div class="'+r+'">\n\n    <button\n      class="'+i+'"\n    ></button>\n\n    <div class="'+a+'">\n      <div></div>\n      <div></div>\n      <div></div>\n    </div>\n\n  </div>\n'},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(4),r=n(2),i=n(0),a=i.default.ICON,s=i.default.ICON_CUSTOM,c=["error","warning","success","info"],l={error:r.errorIconMarkup(),warning:r.warningIconMarkup(),success:r.successIconMarkup()},u=function(t,e){var n=a+"--"+t;e.classList.add(n);var o=l[t];o&&(e.innerHTML=o)},f=function(t,e){e.classList.add(s);var n=document.createElement("img");n.src=t,e.appendChild(n)},d=function(t){if(t){var e=o.injectElIntoModal(r.iconMarkup);c.includes(t)?u(t,e):f(t,e)}};e.default=d},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(2),r=n(4),i=function(t){navigator.userAgent.includes("AppleWebKit")&&(t.style.display="none",t.offsetHeight,t.style.display="")};e.initTitle=function(t){if(t){var e=r.injectElIntoModal(o.titleMarkup);e.textContent=t,i(e)}},e.initText=function(t){if(t){var e=document.createDocumentFragment();t.split("\n").forEach(function(t,n,o){e.appendChild(document.createTextNode(t)),n<o.length-1&&e.appendChild(document.createElement("br"))});var n=r.injectElIntoModal(o.textMarkup);n.appendChild(e),i(n)}}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(4),i=n(0),a=i.default.BUTTON,s=i.default.DANGER_BUTTON,c=n(3),l=n(2),u=n(6),f=n(5),d=function(t,e,n){var r=e.text,i=e.value,d=e.className,p=e.closeModal,m=o.stringToNode(l.buttonMarkup),b=m.querySelector("."+a),v=a+"--"+t;if(b.classList.add(v),d){(Array.isArray(d)?d:d.split(" ")).filter(function(t){return t.length>0}).forEach(function(t){b.classList.add(t)})}n&&t===c.CONFIRM_KEY&&b.classList.add(s),b.textContent=r;var g={};return g[t]=i,f.setActionValue(g),f.setActionOptionsFor(t,{closeModal:p}),b.addEventListener("click",function(){return u.onAction(t)}),m},p=function(t,e){var n=r.injectElIntoModal(l.footerMarkup);for(var o in t){var i=t[o],a=d(o,i,e);i.visible&&n.appendChild(a)}0===n.children.length&&n.remove()};e.default=p},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(3),r=n(4),i=n(2),a=n(5),s=n(6),c=n(0),l=c.default.CONTENT,u=function(t){t.addEventListener("input",function(t){var e=t.target,n=e.value;a.setActionValue(n)}),t.addEventListener("keyup",function(t){if("Enter"===t.key)return s.onAction(o.CONFIRM_KEY)}),setTimeout(function(){t.focus(),a.setActionValue("")},0)},f=function(t,e,n){var o=document.createElement(e),r=l+"__"+e;o.classList.add(r);for(var i in n){var a=n[i];o[i]=a}"input"===e&&u(o),t.appendChild(o)},d=function(t){if(t){var e=r.injectElIntoModal(i.contentMarkup),n=t.element,o=t.attributes;"string"==typeof n?f(e,n,o):e.appendChild(n)}};e.default=d},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(2),i=function(){var t=o.stringToNode(r.overlayMarkup);document.body.appendChild(t)};e.default=i},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(5),r=n(6),i=n(1),a=n(3),s=n(0),c=s.default.MODAL,l=s.default.BUTTON,u=s.default.OVERLAY,f=function(t){t.preventDefault(),v()},d=function(t){t.preventDefault(),g()},p=function(t){if(o.default.isOpen)switch(t.key){case"Escape":return r.onAction(a.CANCEL_KEY)}},m=function(t){if(o.default.isOpen)switch(t.key){case"Tab":return f(t)}},b=function(t){if(o.default.isOpen)return"Tab"===t.key&&t.shiftKey?d(t):void 0},v=function(){var t=i.getNode(l);t&&(t.tabIndex=0,t.focus())},g=function(){var t=i.getNode(c),e=t.querySelectorAll("."+l),n=e.length-1,o=e[n];o&&o.focus()},h=function(t){t[t.length-1].addEventListener("keydown",m)},w=function(t){t[0].addEventListener("keydown",b)},y=function(){var t=i.getNode(c),e=t.querySelectorAll("."+l);e.length&&(h(e),w(e))},x=function(t){if(i.getNode(u)===t.target)return r.onAction(a.CANCEL_KEY)},_=function(t){var e=i.getNode(u);e.removeEventListener("click",x),t&&e.addEventListener("click",x)},k=function(t){o.default.timer&&clearTimeout(o.default.timer),t&&(o.default.timer=window.setTimeout(function(){return r.onAction(a.CANCEL_KEY)},t))},O=function(t){t.closeOnEsc?document.addEventListener("keyup",p):document.removeEventListener("keyup",p),t.dangerMode?v():g(),y(),_(t.closeOnClickOutside),k(t.timer)};e.default=O},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(3),i=n(37),a=n(38),s={title:null,text:null,icon:null,buttons:r.defaultButtonList,content:null,className:null,closeOnClickOutside:!0,closeOnEsc:!0,dangerMode:!1,timer:null},c=Object.assign({},s);e.setDefaults=function(t){c=Object.assign({},s,t)};var l=function(t){var e=t&&t.button,n=t&&t.buttons;return void 0!==e&&void 0!==n&&o.throwErr("Cannot set both 'button' and 'buttons' options!"),void 0!==e?{confirm:e}:n},u=function(t){return o.ordinalSuffixOf(t+1)},f=function(t,e){o.throwErr(u(e)+" argument ('"+t+"') is invalid")},d=function(t,e){var n=t+1,r=e[n];o.isPlainObject(r)||void 0===r||o.throwErr("Expected "+u(n)+" argument ('"+r+"') to be a plain object")},p=function(t,e){var n=t+1,r=e[n];void 0!==r&&o.throwErr("Unexpected "+u(n)+" argument ("+r+")")},m=function(t,e,n,r){var i=typeof e,a="string"===i,s=e instanceof Element;if(a){if(0===n)return{text:e};if(1===n)return{text:e,title:r[0]};if(2===n)return d(n,r),{icon:e};f(e,n)}else{if(s&&0===n)return d(n,r),{content:e};if(o.isPlainObject(e))return p(n,r),e;f(e,n)}};e.getOpts=function(){for(var t=[],e=0;e<arguments.length;e++)t[e]=arguments[e];var n={};t.forEach(function(e,o){var r=m(0,e,o,t);Object.assign(n,r)});var o=l(n);n.buttons=r.getButtonListOpts(o),delete n.button,n.content=i.getContentOpts(n.content);var u=Object.assign({},s,c,n);return Object.keys(u).forEach(function(t){a.DEPRECATED_OPTS[t]&&a.logDeprecation(t)}),u}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r={element:"input",attributes:{placeholder:""}};e.getContentOpts=function(t){var e={};return o.isPlainObject(t)?Object.assign(e,t):t instanceof Element?{element:t}:"input"===t?r:null}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.logDeprecation=function(t){var n=e.DEPRECATED_OPTS[t],o=n.onlyRename,r=n.replacement,i=n.subOption,a=n.link,s=o?"renamed":"deprecated",c='SweetAlert warning: "'+t+'" option has been '+s+".";if(r){c+=" Please use"+(i?' "'+i+'" in ':" ")+'"'+r+'" instead.'}var l="https://sweetalert.js.org";c+=a?" More details: "+l+a:" More details: "+l+"/guides/#upgrading-from-1x",console.warn(c)},e.DEPRECATED_OPTS={type:{replacement:"icon",link:"/docs/#icon"},imageUrl:{replacement:"icon",link:"/docs/#icon"},customClass:{replacement:"className",onlyRename:!0,link:"/docs/#classname"},imageSize:{},showCancelButton:{replacement:"buttons",link:"/docs/#buttons"},showConfirmButton:{replacement:"button",link:"/docs/#button"},confirmButtonText:{replacement:"button",link:"/docs/#button"},confirmButtonColor:{},cancelButtonText:{replacement:"buttons",link:"/docs/#buttons"},closeOnConfirm:{replacement:"button",subOption:"closeModal",link:"/docs/#button"},closeOnCancel:{replacement:"buttons",subOption:"closeModal",link:"/docs/#buttons"},showLoaderOnConfirm:{replacement:"buttons"},animation:{},inputType:{replacement:"content",link:"/docs/#content"},inputValue:{replacement:"content",link:"/docs/#content"},inputPlaceholder:{replacement:"content",link:"/docs/#content"},html:{replacement:"content",link:"/docs/#content"},allowEscapeKey:{replacement:"closeOnEsc",onlyRename:!0,link:"/docs/#closeonesc"},allowClickOutside:{replacement:"closeOnClickOutside",onlyRename:!0,link:"/docs/#closeonclickoutside"}}}])});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../timers-browserify/main.js */ "./node_modules/timers-browserify/main.js").setImmediate, __webpack_require__(/*! ./../../timers-browserify/main.js */ "./node_modules/timers-browserify/main.js").clearImmediate))

/***/ }),

/***/ "./node_modules/timers-browserify/main.js":
/*!************************************************!*\
  !*** ./node_modules/timers-browserify/main.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var scope = (typeof global !== "undefined" && global) ||
            (typeof self !== "undefined" && self) ||
            window;
var apply = Function.prototype.apply;

// DOM APIs, for completeness

exports.setTimeout = function() {
  return new Timeout(apply.call(setTimeout, scope, arguments), clearTimeout);
};
exports.setInterval = function() {
  return new Timeout(apply.call(setInterval, scope, arguments), clearInterval);
};
exports.clearTimeout =
exports.clearInterval = function(timeout) {
  if (timeout) {
    timeout.close();
  }
};

function Timeout(id, clearFn) {
  this._id = id;
  this._clearFn = clearFn;
}
Timeout.prototype.unref = Timeout.prototype.ref = function() {};
Timeout.prototype.close = function() {
  this._clearFn.call(scope, this._id);
};

// Does not start the time, just sets up the members needed.
exports.enroll = function(item, msecs) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = msecs;
};

exports.unenroll = function(item) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = -1;
};

exports._unrefActive = exports.active = function(item) {
  clearTimeout(item._idleTimeoutId);

  var msecs = item._idleTimeout;
  if (msecs >= 0) {
    item._idleTimeoutId = setTimeout(function onTimeout() {
      if (item._onTimeout)
        item._onTimeout();
    }, msecs);
  }
};

// setimmediate attaches itself to the global object
__webpack_require__(/*! setimmediate */ "./node_modules/setimmediate/setImmediate.js");
// On some exotic environments, it's not clear which object `setimmediate` was
// able to install onto.  Search each possibility in the same order as the
// `setimmediate` library.
exports.setImmediate = (typeof self !== "undefined" && self.setImmediate) ||
                       (typeof global !== "undefined" && global.setImmediate) ||
                       (this && this.setImmediate);
exports.clearImmediate = (typeof self !== "undefined" && self.clearImmediate) ||
                         (typeof global !== "undefined" && global.clearImmediate) ||
                         (this && this.clearImmediate);

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./resources/assets/js/I18n.js":
/*!*************************************!*\
  !*** ./resources/assets/js/I18n.js ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return I18n; });
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var I18n =
/*#__PURE__*/
function () {
  /**
   * Initialize a new translation instance.
   *
   * @param  {string}  key
   * @return {void}
   */
  function I18n() {
    var key = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'translations';

    _classCallCheck(this, I18n);

    this.key = key;
  }
  /**
   * Get and replace the string of the given key.
   *
   * @param  {string}  key
   * @param  {object}  replace
   * @return {string}
   */


  _createClass(I18n, [{
    key: "trans",
    value: function trans(key) {
      var replace = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return this._replace(this._extract(key), replace);
    }
    /**
     * Get and pluralize the strings of the given key.
     *
     * @param  {string}  key
     * @param  {number}  count
     * @param  {object}  replace
     * @return {string}
     */

  }, {
    key: "trans_choice",
    value: function trans_choice(key) {
      var _this = this;

      var count = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
      var replace = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

      var translations = this._extract(key, '|').split('|'),
          translation;

      translations.some(function (t) {
        return translation = _this._match(t, count);
      });
      translation = translation || (count > 1 ? translations[1] : translations[0]);
      return this._replace(translation, replace);
    }
    /**
     * Match the translation limit with the count.
     *
     * @param  {string}  translation
     * @param  {number}  count
     * @return {string|null}
     */

  }, {
    key: "_match",
    value: function _match(translation, count) {
      var match = translation.match(/^[\{\[]([^\[\]\{\}]*)[\}\]](.*)/);
      if (!match) return;

      if (match[1].includes(',')) {
        var _match$1$split = match[1].split(','),
            _match$1$split2 = _slicedToArray(_match$1$split, 2),
            from = _match$1$split2[0],
            to = _match$1$split2[1];

        if (to === '*' && count >= from) {
          return match[2];
        } else if (from === '*' && count <= to) {
          return match[2];
        } else if (count >= from && count <= to) {
          return match[2];
        }
      }

      return match[1] == count ? match[2] : null;
    }
    /**
     * Replace the placeholders.
     *
     * @param  {string}  translation
     * @param  {object}  replace
     * @return {string}
     */

  }, {
    key: "_replace",
    value: function _replace(translation, replace) {
      for (var placeholder in replace) {
        translation = translation.replace(":".concat(placeholder), replace[placeholder]).replace(":".concat(placeholder.toUpperCase()), replace[placeholder].toUpperCase()).replace(":".concat(placeholder.charAt(0).toUpperCase()).concat(placeholder.slice(1)), replace[placeholder].charAt(0).toUpperCase() + replace[placeholder].slice(1));
      }

      return translation;
    }
    /**
     * Extract values from objects by dot notation.
     *
     * @param  {string}  key
     * @param  {mixed}  value
     * @return {mixed}
     */

  }, {
    key: "_extract",
    value: function _extract(key) {
      var value = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var path = key.toString().split('::');
      var keys = path.pop().toString().split('.');

      if (path.length > 0) {
        path[0] += '::';
      }

      return path.concat(keys).reduce(function (t, i) {
        return t[i] || value || key;
      }, window[this.key]);
    }
  }]);

  return I18n;
}();



/***/ }),

/***/ "./resources/assets/js/app.js":
/*!************************************!*\
  !*** ./resources/assets/js/app.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _materialize__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./materialize */ "./resources/assets/js/materialize.js");
/* harmony import */ var _materialize__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_materialize__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _core_global__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./core/global */ "./resources/assets/js/core/global.js");
/* harmony import */ var _I18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./I18n */ "./resources/assets/js/I18n.js");
/* harmony import */ var sweetalert__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! sweetalert */ "./node_modules/sweetalert/dist/sweetalert.min.js");
/* harmony import */ var sweetalert__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(sweetalert__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var daterangepicker__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! daterangepicker */ "./node_modules/daterangepicker/daterangepicker.js");
/* harmony import */ var daterangepicker__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(daterangepicker__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var nestable2__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! nestable2 */ "./node_modules/nestable2/jquery.nestable.js");
/* harmony import */ var nestable2__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(nestable2__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var jquery_countto__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! jquery-countto */ "./node_modules/jquery-countto/jquery.countTo.js");
/* harmony import */ var jquery_countto__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(jquery_countto__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var jstree_src_jstree_search_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! jstree/src/jstree.search.js */ "./node_modules/jstree/src/jstree.search.js");
/* harmony import */ var jstree_src_jstree_search_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(jstree_src_jstree_search_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var jstree_src_jstree_sort_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! jstree/src/jstree.sort.js */ "./node_modules/jstree/src/jstree.sort.js");
/* harmony import */ var jstree_src_jstree_sort_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(jstree_src_jstree_sort_js__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var devbridge_autocomplete__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! devbridge-autocomplete */ "./node_modules/devbridge-autocomplete/dist/jquery.autocomplete.js");
/* harmony import */ var devbridge_autocomplete__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(devbridge_autocomplete__WEBPACK_IMPORTED_MODULE_9__);
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }











 // import 'bootstrap-colorpicker'

var UccelloApp =
/*#__PURE__*/
function () {
  function UccelloApp() {
    _classCallCheck(this, UccelloApp);

    this.initGlobal();
    this.initContentHeight();
    this.autoOpenMenu();
    this.initTranslation();
    this.initScrollSpy();
    this.initDateRangePicker(); // this.initColorPicker()

    this.initCountTo();
    this.initJsTree();
    this.initSearchBar();
  }

  _createClass(UccelloApp, [{
    key: "initGlobal",
    value: function initGlobal() {
      new _core_global__WEBPACK_IMPORTED_MODULE_1__["Global"]();
    }
  }, {
    key: "initContentHeight",
    value: function initContentHeight() {
      $('main .content:first').css('min-height', $(document).height() - $('.navbar-header').height() - $('.navbar-top').height());
    }
  }, {
    key: "initTranslation",
    value: function initTranslation() {
      window.I18n = _I18n__WEBPACK_IMPORTED_MODULE_2__["default"];
      window.uctrans = new _I18n__WEBPACK_IMPORTED_MODULE_2__["default"]('uctranslations');
    }
  }, {
    key: "initScrollSpy",
    value: function initScrollSpy() {
      var that = this;

      if (that.isMobileSize()) {
        $('.navbar-top nav').removeClass('transparent').removeClass('z-depth-0');
        $('.breadcrumb-container').addClass('z-depth-0');
      }

      $(window).scroll(function () {
        if (!that.isMobileSize() && $(this).scrollTop() > 20) {
          $('.navbar-top nav').removeClass('transparent').removeClass('z-depth-0');
          $('.breadcrumb-container').addClass('z-depth-0');
        } else if (!that.isMobileSize()) {
          $('.navbar-top nav').addClass('transparent').addClass('z-depth-0');
          $('.breadcrumb-container').removeClass('z-depth-0');
        }
      });
    }
  }, {
    key: "initDateRangePicker",
    value: function initDateRangePicker() {
      var _this = this;

      var today = uctrans.trans('uccello::default.calendar.ranges.today');
      var yesterday = uctrans.trans('uccello::default.calendar.ranges.yesterday');
      var tomorrow = uctrans.trans('uccello::default.calendar.ranges.tomorrow');
      var last7Days = uctrans.trans('uccello::default.calendar.ranges.last7days');
      var last30Days = uctrans.trans('uccello::default.calendar.ranges.last30days');
      var next7Days = uctrans.trans('uccello::default.calendar.ranges.next7days');
      var next30Days = uctrans.trans('uccello::default.calendar.ranges.next30days');
      var month = uctrans.trans('uccello::default.calendar.ranges.month');
      var lastMonth = uctrans.trans('uccello::default.calendar.ranges.last_month');
      var nextMonth = uctrans.trans('uccello::default.calendar.ranges.next_month');
      var quarter = uctrans.trans('uccello::default.calendar.ranges.quarter');
      var lastQuarter = uctrans.trans('uccello::default.calendar.ranges.last_quarter');
      var nextQuarter = uctrans.trans('uccello::default.calendar.ranges.next_quarter'); // Date range picker

      $('.date-range-picker').each(function (index, el) {
        var _ranges;

        $(el).daterangepicker({
          autoUpdateInput: false,
          locale: _this.getDaterangePickerLocale($(el).data('format')),
          showDropdowns: true,
          applyButtonClasses: "waves-effect primary",
          cancelClass: "waves-effect btn-flat",
          alwaysShowCalendars: $(el).data('show-calendars') ? true : false,
          ranges: (_ranges = {}, _defineProperty(_ranges, today, [moment(), moment()]), _defineProperty(_ranges, yesterday, [moment().subtract(1, 'days'), moment().subtract(1, 'days')]), _defineProperty(_ranges, tomorrow, [moment().add(1, 'days'), moment().add(1, 'days')]), _defineProperty(_ranges, last7Days, [moment().subtract(6, 'days'), moment()]), _defineProperty(_ranges, last30Days, [moment().subtract(29, 'days'), moment()]), _defineProperty(_ranges, next7Days, [moment(), moment().add(6, 'days')]), _defineProperty(_ranges, next30Days, [moment(), moment().add(29, 'days')]), _defineProperty(_ranges, month, [moment().startOf('month'), moment().endOf('month')]), _defineProperty(_ranges, lastMonth, [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]), _defineProperty(_ranges, nextMonth, [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]), _defineProperty(_ranges, quarter, [moment().startOf('quarter'), moment().endOf('quarter')]), _defineProperty(_ranges, lastQuarter, [moment().subtract(1, 'quarter').startOf('quarter'), moment().subtract(1, 'quarter').endOf('quarter')]), _defineProperty(_ranges, nextQuarter, [moment().add(1, 'quarter').startOf('quarter'), moment().add(1, 'quarter').endOf('quarter')]), _ranges)
        }, function (start, end, label) {
          $(this).change();
        }).on('apply.daterangepicker', function (ev, picker) {
          $(this).val(picker.startDate.format($(this).data('format')) + ', ' + picker.endDate.format($(this).data('format')));
        }).on('cancel.daterangepicker', function (ev, picker) {
          $(this).val('').change();
        });
      }); // Datetime range picker

      $('.datetime-range-picker').each(function (index, el) {
        $(el).daterangepicker({
          autoUpdateInput: false,
          timePicker: true,
          timePicker24Hour: true,
          locale: _this.getDaterangePickerLocale($(el).data('format')),
          showDropdowns: true,
          applyButtonClasses: "waves-effect primary",
          cancelClass: "waves-effect btn-flat"
        }, function (start, end, label) {
          $(this).change();
        }).on('apply.daterangepicker', function (ev, picker) {
          $(this).val(picker.startDate.format($(this).data('format')) + ', ' + picker.endDate.format($(this).data('format')));
        }).on('cancel.daterangepicker', function (ev, picker) {
          $(this).val('').change();
        });
      }); // Date picker

      $('.datepicker').each(function (index, el) {
        $(el).daterangepicker({
          autoUpdateInput: false,
          locale: _this.getDaterangePickerLocale($(el).data('format')),
          singleDatePicker: true,
          showDropdowns: true
        }).on('apply.daterangepicker', function (ev, picker) {
          $(this).val(picker.startDate.format($(this).data('format')));
        }).on('keyup', function () {
          var dateStr = $(this).val();

          if (moment(dateStr).isValid()) {
            var date = moment(dateStr);
            $(this).data('daterangepicker').setStartDate(date);
            $(this).data('daterangepicker').setEndDate(date);
          }
        });
      }); // Datetime picker

      $('.datetimepicker').each(function (index, el) {
        $(el).daterangepicker({
          autoUpdateInput: false,
          autoApply: true,
          locale: _this.getDaterangePickerLocale($('meta[name="datetime-format-js"]').attr("content")),
          singleDatePicker: true,
          timePicker: true,
          timePicker24Hour: true,
          showDropdowns: true,
          applyButtonClasses: "waves-effect primary",
          cancelClass: "waves-effect btn-flat"
        }).on('apply.daterangepicker', function (ev, picker) {
          $(this).val(picker.startDate.format($(this).data('format')));
          $(this).parents('.input-field:first').find('label').addClass('active');
        }).on('cancel.daterangepicker', function (ev, picker) {
          $(this).val('').change();
        }).on('keyup', function () {
          var dateStr = $(this).val();

          if (moment(dateStr).isValid()) {
            var date = moment(dateStr);
            $(this).data('daterangepicker').setStartDate(date);
            $(this).data('daterangepicker').setEndDate(date);
          }
        });
      }); // Month picker

      $('.monthpicker').each(function (index, el) {
        $(el).daterangepicker({
          autoUpdateInput: false,
          autoApply: true,
          locale: _this.getDaterangePickerLocale($(el).data('format')),
          singleDatePicker: true,
          showDropdowns: true
        }).on('apply.daterangepicker', function (ev, picker) {
          $(this).val(picker.startDate.format('YYYY-MM'));
          $(this).parents('.input-field:first').find('label').addClass('active');
        });
      }); // Week picker

      $('.weekpicker').each(function (index, el) {
        $(el).daterangepicker({
          autoUpdateInput: false,
          autoApply: true,
          locale: _this.getDaterangePickerLocale($(el).data('format')),
          singleDatePicker: true,
          showWeekNumbers: true,
          showDropdowns: true
        }).on('apply.daterangepicker', function (ev, picker) {
          $(this).val(picker.startDate.format('YYYY-w'));
          $(this).parents('.input-field:first').find('label').addClass('active');
        });
      });
    }
  }, {
    key: "getDaterangePickerLocale",
    value: function getDaterangePickerLocale(format) {
      return {
        format: format,
        separator: uctrans.trans('uccello::default.calendar.separator'),
        applyLabel: uctrans.trans('uccello::default.calendar.apply'),
        cancelLabel: uctrans.trans('uccello::default.calendar.cancel'),
        fromLabel: uctrans.trans('uccello::default.calendar.from'),
        toLabel: uctrans.trans('uccello::default.calendar.to'),
        customRangeLabel: uctrans.trans('uccello::default.calendar.custom'),
        weekLabel: uctrans.trans('uccello::default.calendar.week'),
        daysOfWeek: [uctrans.trans('uccello::default.calendar.day.su'), uctrans.trans('uccello::default.calendar.day.mo'), uctrans.trans('uccello::default.calendar.day.tu'), uctrans.trans('uccello::default.calendar.day.we'), uctrans.trans('uccello::default.calendar.day.th'), uctrans.trans('uccello::default.calendar.day.fr'), uctrans.trans('uccello::default.calendar.day.sa')],
        monthNames: [uctrans.trans('uccello::default.calendar.month.january'), uctrans.trans('uccello::default.calendar.month.february'), uctrans.trans('uccello::default.calendar.month.march'), uctrans.trans('uccello::default.calendar.month.april'), uctrans.trans('uccello::default.calendar.month.may'), uctrans.trans('uccello::default.calendar.month.june'), uctrans.trans('uccello::default.calendar.month.july'), uctrans.trans('uccello::default.calendar.month.august'), uctrans.trans('uccello::default.calendar.month.september'), uctrans.trans('uccello::default.calendar.month.october'), uctrans.trans('uccello::default.calendar.month.november'), uctrans.trans('uccello::default.calendar.month.december')],
        firstDay: 1
      };
    }
  }, {
    key: "initColorPicker",
    value: function initColorPicker() {
      $('.colorpicker').colorpicker({
        customClass: 'colorpicker-2x',
        sliders: {
          saturation: {
            maxLeft: 200,
            maxTop: 200
          },
          hue: {
            maxTop: 200
          },
          alpha: {
            maxTop: 200
          }
        }
      }).on('changeColor', function (e) {
        var rbga = e.color.toRGB();
        $(e.currentTarget).parents('.input-field').find('.input-group-addon i').css('color', "rgba(".concat(rbga.r, ",").concat(rbga.g, ",").concat(rbga.b, ",").concat(rbga.a, ")"));
      });
    }
  }, {
    key: "initCountTo",
    value: function initCountTo() {
      $('.count-to').countTo();
    }
  }, {
    key: "initJsTree",
    value: function initJsTree() {
      // Domains tree
      var domainsTree = $('#domains-tree');
      domainsTree.jstree({
        "core": {
          "themes": {
            "icons": false
          }
        },
        "plugins": ['search', 'sort']
      }) // Open tree automatically
      .on('ready.jstree', function () {
        domainsTree.jstree('open_all');
      }) // Switch on domain on click
      .on('changed.jstree', function (e, data) {
        if (data.node.a_attr.href !== '#') {
          document.location.href = data.node.a_attr.href;
        }
      });
      var to = false;
      $('.domain-search-bar #domain-name').keyup(function () {
        if (to) {
          clearTimeout(to);
        }

        to = setTimeout(function () {
          var v = $('#domain-name').val();
          domainsTree.jstree(true).search(v);
        }, 250);
      });
    }
  }, {
    key: "initSearchBar",
    value: function initSearchBar() {
      $(".navbar-header .search-btn").on('click', function () {
        $('.navbar-header .default-bar').hide();
        $('.navbar-header .search-bar').show();
        $('.navbar-header .search-bar #search').focus();
      });
      $('.navbar-header .search-bar #search').on('focusout', function () {
        $('.navbar-header .default-bar').show();
        $('.navbar-header .search-bar').hide();
        $('.navbar-header .search-bar #search').val('');
      });
    }
  }, {
    key: "autoOpenMenu",
    value: function autoOpenMenu() {
      var collapsible = $('.sidenav ul.collapsible ul li.active').parents('ul.collapsible:first');
      collapsible.collapsible('open');
      collapsible.find('li:first').addClass('active');
    }
  }, {
    key: "isMobileSize",
    value: function isMobileSize() {
      return $(document).width() < 601;
    }
  }]);

  return UccelloApp;
}();

new UccelloApp();

/***/ }),

/***/ "./resources/assets/js/core/global.js":
/*!********************************************!*\
  !*** ./resources/assets/js/core/global.js ***!
  \********************************************/
/*! exports provided: Global */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Global", function() { return Global; });
/* harmony import */ var _link__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./link */ "./resources/assets/js/core/link.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var Global =
/*#__PURE__*/
function () {
  function Global() {
    _classCallCheck(this, Global);

    this.initLinks();
    this.initNotifications();
  }

  _createClass(Global, [{
    key: "initLinks",
    value: function initLinks() {
      new _link__WEBPACK_IMPORTED_MODULE_0__["Link"]();
    }
  }, {
    key: "initNotifications",
    value: function initNotifications() {
      $('.notification-container').each(function (index, element) {
        var text = $(element).html();
        var classes = $(element).data('classes') ? $(element).data('classes') : '';
        M.toast({
          html: text,
          classes: classes
        });
      });
    }
  }]);

  return Global;
}();

/***/ }),

/***/ "./resources/assets/js/core/link.js":
/*!******************************************!*\
  !*** ./resources/assets/js/core/link.js ***!
  \******************************************/
/*! exports provided: Link */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Link", function() { return Link; });
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Link =
/*#__PURE__*/
function () {
  function Link(initListeners) {
    _classCallCheck(this, Link);

    if (initListeners !== false) {
      this.initListeners();
    }
  }

  _createClass(Link, [{
    key: "initListeners",
    value: function initListeners() {
      this.initClickListener();
    }
  }, {
    key: "initClickListener",
    value: function initClickListener(parentElement) {
      var _this = this;

      if (typeof parentElement === 'undefined') {
        parentElement = null;
      }

      $("a[data-config], button[data-config]", parentElement).on('click', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        _this.element = $(event.currentTarget);
        _this.config = _this.element.data('config'); // Remove focus

        _this.element.blur();

        if (_this.config === null) {
          _this.config = {};
        } // Check if we need a confirmation or if we laucnh action directly


        if (_this.config.confirm === true) {
          _this.confirm();
        } else {
          _this.launchAction();
        }
      });
    }
    /**
     * Launch action according to its type
     */

  }, {
    key: "launchAction",
    value: function launchAction() {
      switch (this.config.actionType) {
        // Simple link
        case 'link':
          this.gotoUrl();
          break;
        // Ajax call

        case 'ajax':
          this.callUrl();
          break;
      }
    }
    /**
     * Go to URL according to the target if it is defined
     */

  }, {
    key: "gotoUrl",
    value: function gotoUrl() {
      // Get target if defined, or use default one
      var target = this.config.target ? this.config.target : '_self'; // Go to the link

      window.open(this.element.attr('href'), target);
    }
    /**
     * Call URL by Ajax
     */

  }, {
    key: "callUrl",
    value: function callUrl() {
      // Get URL
      var url = this.element.attr('href'); // Get Ajax config

      var ajaxConfig = _typeof(this.config.ajax) === 'object' ? this.config.ajax : {}; // Method

      var method = "get";

      if (ajaxConfig.method) {
        method = ajaxConfig.method;
      } // Params


      var params = {};

      if (ajaxConfig.params) {
        params = ajaxConfig.params;
      } // If POST is used, add CSRF token to parameters


      if (method === 'post') {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        params['_token'] = csrfToken;
      } // Element to update with the call response


      var elementToUpdate = null;

      if (ajaxConfig.elementToUpdate) {
        elementToUpdate = ajaxConfig.elementToUpdate;
      } // Call


      $.ajax({
        url: url,
        method: method,
        data: params
      }).then(function (response) {
        // If we want to update an element in the page, do it
        if (elementToUpdate) {
          $(elementToUpdate).html(response.content);
        } // Display dialog displaying success or error
        else {
            if (response.success === false) {
              swal(uctrans.trans('uccello::default.dialog.error.title'), response.message, "error");
            } else {
              swal(uctrans.trans('uccello::default.dialog.success.title'), response.message, "success"); // Reload page if needed

              if (ajaxConfig.refresh === true) {
                document.location.reload();
              }
            }
          }
      }) // Impossible to reach the URL. Display error
      ["catch"](function (error) {
        swal(uctrans.trans('uccello::default.dialog.error.title'), error.message, "error");
      });
    }
    /**
     * Show confirm dialog
     */

  }, {
    key: "confirm",
    value: function confirm() {
      var _this2 = this;

      // Get config
      var title, text, confirmButtonText, confirmButtonColor, closeOnConfirm;

      if (_typeof(this.config.dialog) === 'object') {
        title = this.config.dialog.title;
        text = this.config.dialog.text;
        confirmButtonText = this.config.dialog.confirmButtonText;
        confirmButtonColor = this.config.dialog.confirmButtonColor;
        closeOnConfirm = this.config.dialog.closeOnConfirm;
      } // Default config


      if (!title) {
        title = uctrans.trans('uccello::default.confirm.dialog.title');
      }

      if (!confirmButtonText) {
        confirmButtonText = uctrans.trans('uccello::default.button.yes');
      }

      if (!confirmButtonColor) {
        confirmButtonColor = '#DD6B55';
      }

      if (!closeOnConfirm) {
        closeOnConfirm = true;
      } // If it is an AJAX call, show loader


      var showLoaderOnConfirm = false;

      if (this.config.actionType === 'ajax') {
        showLoaderOnConfirm = true;

        if (!this.config.ajax || !this.config.ajax.elementToUpdate) {
          closeOnConfirm = false;
        }
      } // Show confirm dialog


      swal({
        title: title,
        text: text,
        icon: "warning",
        buttons: {
          cancel: {
            text: uctrans.trans('uccello::default.button.no'),
            value: null,
            visible: true
          },
          confirm: {
            text: confirmButtonText,
            value: true,
            className: "red",
            closeModal: closeOnConfirm
          }
        }
      }).then(function (response) {
        if (response === true) {
          _this2.launchAction();
        }
      });
    }
  }]);

  return Link;
}();

/***/ }),

/***/ "./resources/assets/js/materialize.js":
/*!********************************************!*\
  !*** ./resources/assets/js/materialize.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

window._ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
window.moment = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.Popper = __webpack_require__(/*! popper.js */ "./node_modules/popper.js/dist/esm/popper.js")["default"];
  window.$ = window.jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

  __webpack_require__(/*! materialize-css */ "./node_modules/materialize-css/dist/js/materialize.js");
} catch (e) {} // Fastclick


var FastClick = __webpack_require__(/*! fastclick */ "./node_modules/fastclick/lib/fastclick.js");

FastClick.attach(document.body); // Init components

$('#sidenav-menu').sidenav({
  edge: 'left',
  onOpenStart: function onOpenStart() {
    if ($(document).width() < 993) {
      $('#sidenav-domains').sidenav('close');
    }
  }
});
$('#sidenav-domains').sidenav({
  edge: 'right',
  onOpenStart: function onOpenStart() {
    if ($(document).width() < 993) {
      $('#sidenav-menu').sidenav('close');
    }
  }
});
$('.dropdown-trigger').each(function (index, el) {
  $(el).dropdown({
    alignment: $(el).data('alignment') ? $(el).data('alignment') : 'left',
    constrainWidth: $(el).data('constrain-width') === false ? false : true,
    container: $(el).data('container') ? $($(el).data('container')) : null,
    coverTrigger: $(el).data('cover-trigger') === true ? true : false,
    closeOnClick: $(el).data('close-on-click') === false ? false : true,
    hover: $(el).data('hover') === true ? true : false
  });
});
$('.tabs').tabs({// swipeable: true
});
$('select').each(function (index, el) {
  $(el).formSelect({
    dropdownOptions: {
      alignment: $(el).data('alignment') ? $(el).data('alignment') : 'left',
      constrainWidth: $(el).data('constrain-width') === false ? false : true,
      container: $(el).data('container') ? $($(el).data('container')) : null,
      coverTrigger: $(el).data('cover-trigger') === true ? true : false,
      closeOnClick: $(el).data('close-on-click') === false ? false : true,
      hover: $(el).data('hover') === true ? true : false
    }
  });
});
$('[data-tooltip]').tooltip({
  transitionMovement: 0
});
$('.modal').modal();
$('.collapsible').collapsible();

/***/ }),

/***/ 4:
/*!******************************************!*\
  !*** multi ./resources/assets/js/app.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! f:\VMs\Partage\ginkgo\packages\jerome-savin\uccello\resources\assets\js\app.js */"./resources/assets/js/app.js");


/***/ })

},[[4,"/js/manifest","/js/vendor"]]]);