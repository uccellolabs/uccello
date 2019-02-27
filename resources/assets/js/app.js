// Bootstrap
import './bootstrap';

// Slimscroll
import 'adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js';

// Admin BSB
import 'adminbsb-materialdesign';

// Admin BSB Plugins
import 'adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js';
import 'adminbsb-materialdesign/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js';
import noUiSlider from 'adminbsb-materialdesign/plugins/nouislider/nouislider';

// Node waves (for Admin BSB)
import 'node-waves';

import 'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker';
import 'daterangepicker'

// JSTree
import 'jstree/src/jstree.search.js';
import 'jstree/src/jstree.sort.js';

// Notify
import 'adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify'

// Sweetalert
import 'adminbsb-materialdesign/plugins/sweetalert/sweetalert.min.js'

// jQuery Count To
import 'adminbsb-materialdesign/plugins/jquery-countto/jquery.countTo.js'

// jQuery Nestable 2
import 'nestable2'

// jQuery Spinner
import 'adminbsb-materialdesign/plugins/jquery-spinner/js/jquery.spinner.js'

//
import 'ajax-bootstrap-select'

// Autosize
import autosize from 'adminbsb-materialdesign/plugins/autosize/autosize.js';
autosize($('textarea.auto-growth'));

// Translation
import i18next from 'i18next';
import i18nextLanguageBundle from '@kirschbaum-development/laravel-translations-loader?namespace=uccello!@kirschbaum-development/laravel-translations-loader';

i18next.init({
    fallbackLng: 'en',
    debug: false,
    resources: i18nextLanguageBundle
})
i18next.changeLanguage($('html').attr('lang')) // Use project locale

/**
 * Translates a string using i18nex library.
 * Replaces nsSeparator and keySeparator to be able to use '.' in the string we want to translate.
 * This function is available in all scripts.
 *
 * @param {String} string String to translate
 * @param {String} file File to use for translation. Default: default
 * @param {String} namespace Namespace to use for translation. Default: uccello
 */
window.uctrans = (string, file, namespace) => {
    if (typeof file === 'undefined') {
        file = 'default'
    }

    if (typeof namespace === 'undefined') {
        namespace = 'uccello'
    }

    return i18next.t(`${namespace}::${file}:${string}`, { nsSeparator: '::', keySeparator: ':' })
}

$('select[data-abs-ajax-url]')
    .selectpicker({
        liveSearch: true
    })
    .ajaxSelectPicker({
        emptyRequest: true,
        cache: true,
        ajax: {
            url: $(this),
            data: {
                q: '{{{q}}}',
                _token: $("meta[name='csrf-token']").attr("content")
            }
        },
        locale: {
            emptyTitle: uctrans('search'),
            currentlySelected: uctrans('autocomplete.currently_selected'),
            errorText: uctrans('autocomplete.error'),
            searchPlaceholder: uctrans('autocomplete.placeholder'),
            statusInitialized: uctrans('autocomplete.status.initialized'),
            statusNoResults:  uctrans('autocomplete.status.no_results'),
            statusSearching:  uctrans('autocomplete.status.searching'),
            statusTooShort:  uctrans('autocomplete.status.too_short'),
        },
        preprocessData: function(response){
            var items = [];
            for(var row of response.data){
                items.push(
                    {
                        'value': row.id,
                        'text': row.recordLabel,
                        'disabled': false
                    }
                )
            }

            return items
        },
    })

$('select').on('changed.bs.select', function (e) {
	$(this).parents('.btn-group:first').removeClass('bs-placeholder')
	$(this).prevAll('.dropdown-toggle').toggleClass('bs-placeholder', this.value === '')
}).trigger('changed.bs.select')

// Tooltip
$("[data-toggle='tooltip']").tooltip();

// Color picker
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
    let rbga = e.color.toRGB()
    $(e.currentTarget).parents('.input-group').find('.input-group-addon i')
        .css('color', `rgba(${rbga.r},${rbga.g},${rbga.b},${rbga.a})`);
});

// Date range picker
$('.date-range-picker')
.daterangepicker({
    autoUpdateInput: false,
    locale: getDaterangePickerLocale(),
    showDropdowns: true
}, function(start, end, label) {
    $(this).change()
})
.on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ', ' + picker.endDate.format('YYYY-MM-DD'));
})
.on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('').change()
})

// Datetime range picker
$('.datetime-range-picker')
.daterangepicker({
    autoUpdateInput: false,
    timePicker: true,
    timePicker24Hour: true,
    locale: getDaterangePickerLocale(),
    showDropdowns: true
}, function(start, end, label) {
    $(this).change()
})
.on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ', ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
})
.on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('').change()
})

// Time picker
$('.timepicker').bootstrapMaterialDatePicker({
    date: false,
    format : 'HH:mm',
    cancelText: uctrans('calendar.cancel'),
    clearButton: true,
    clearText: uctrans('calendar.clear')
})

// Date picker
$('.datepicker').daterangepicker({
    autoUpdateInput: false,
    locale: getDaterangePickerLocale(),
    singleDatePicker: true,
    showDropdowns: true
})
.on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'))
})
.on('keyup', function() {
    let dateStr = $(this).val()
    if (moment(dateStr).isValid()) {
        let date = moment(dateStr)
        $(this).data('daterangepicker').setStartDate(date)
        $(this).data('daterangepicker').setEndDate(date)
    }
})

// Datetime picker
$('.datetimepicker').daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    locale: getDaterangePickerLocale(),
    singleDatePicker: true,
    timePicker: true,
    timePicker24Hour: true,
    showDropdowns: true
})
.on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm')).parents('.form-line:first').addClass('focused')
})
.on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('').change()
})
.on('keyup', function() {
    let dateStr = $(this).val()
    if (moment(dateStr).isValid()) {
        let date = moment(dateStr)
        $(this).data('daterangepicker').setStartDate(date)
        $(this).data('daterangepicker').setEndDate(date)
    }
})

// Month picker
$('.monthpicker').daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    locale: getDaterangePickerLocale(),
    singleDatePicker: true,
    showDropdowns: true
})
.on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM')).parents('.form-line:first').addClass('focused')
})

// Week picker
$('.weekpicker').daterangepicker({
    autoUpdateInput: false,
    autoApply: true,
    locale: getDaterangePickerLocale(),
    singleDatePicker: true,
    showWeekNumbers: true,
    showDropdowns: true
})
.on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-w')).parents('.form-line:first').addClass('focused')
})

$('.count-to').countTo();
$('[data-trigger="spinner"]').spinner();

var rangeSliders = $('.nouislider_range');
if (rangeSliders.length > 0) {

    for (var rangeSlider of rangeSliders) {

        var start = $(rangeSlider).data('start')
        var min = $(rangeSlider).data('min')
        var max = $(rangeSlider).data('max')
        var step = $(rangeSlider).data('step')
        var margin = $(rangeSlider).data('margin')
        var limit = $(rangeSlider).data('limit')
        var value = $(rangeSlider).data('value')

        var connect = typeof start === 'object' && start.length > 1 ? true : 'lower'

        // If a value is defined, update start value
        if (value) {
            start = [];

            if (typeof value === 'string' && value.search(',') > -1) {
                start = value.split(',')
            } else {
                start.push(value)
            }
        }

        noUiSlider.create(rangeSlider, {
            start: start,
            connect: connect,
            step: step,
            range: {
                'min': min,
                'max': max
            },
            margin: margin ? margin : 1,
            limit: limit ? limit : max
            // Legend
            // pips: {
            //     mode: 'steps',
            //     stepped: true,
            //     density: 4
            // }
        });

        getNoUISliderValue(rangeSlider, false);
    }
}

//Get noUISlider Value and write on
function getNoUISliderValue(slider, percentage) {
    slider.noUiSlider.on('update', function () {
        var val = slider.noUiSlider.get();

        if (percentage) {
            val = parseInt(val);
            val += '%';
        }
        $(slider).parent().find('span.js-nouislider-value').text(val);
        $(slider).parent().find('input').val(val);
    });
}

// Domains tree
let domainsTree = $('#domains-tree')
domainsTree.jstree({
    "core" : {
        "themes" : {
            "icons": false
        }
    },
    "plugins" : ['search', 'sort']
})

// Open tree automatically
.on('ready.jstree', () => {
    domainsTree.jstree('open_all')
})

// Switch on domain on click
.on('changed.jstree', (e, data) => {
    if (data.node.a_attr.href !== '#') {
        document.location.href = data.node.a_attr.href;
    }
})

let to = false;
$('.domain-search-bar #domain-name').keyup(() => {
    if(to) {
        clearTimeout(to)
    }

    to = setTimeout(() => {
        let v = $('#domain-name').val()
        domainsTree.jstree(true).search(v)
    }, 250)
});

$('table.dataTable thead th input, table.dataTable thead th select').on('click', (e) => {
    e.stopImmediatePropagation();
})

function getDaterangePickerLocale() {
    return {
        format: uctrans('calendar.format'),
        separator: uctrans('calendar.separator'),
        applyLabel: uctrans('calendar.apply'),
        cancelLabel: uctrans('calendar.cancel'),
        fromLabel: uctrans('calendar.from'),
        toLabel: uctrans('calendar.to'),
        customRangeLabel: uctrans('calendar.custom'),
        weekLabel: uctrans('calendar.week'),
        daysOfWeek: [
            uctrans('calendar.day.su'),
            uctrans('calendar.day.mo'),
            uctrans('calendar.day.tu'),
            uctrans('calendar.day.we'),
            uctrans('calendar.day.th'),
            uctrans('calendar.day.fr'),
            uctrans('calendar.day.sa'),
        ],
        monthNames: [
            uctrans('calendar.month.january'),
            uctrans('calendar.month.february'),
            uctrans('calendar.month.march'),
            uctrans('calendar.month.april'),
            uctrans('calendar.month.may'),
            uctrans('calendar.month.june'),
            uctrans('calendar.month.july'),
            uctrans('calendar.month.august'),
            uctrans('calendar.month.september'),
            uctrans('calendar.month.october'),
            uctrans('calendar.month.november'),
            uctrans('calendar.month.december'),
        ],
        firstDay: 1,
    }
}