import './materialize'
import './core/global'

import { Global } from './core/global'
import I18n from './I18n'

import 'sweetalert'
import 'daterangepicker'
import 'nestable2'
import 'jquery-countto'
import 'jstree/src/jstree.search.js'
import 'jstree/src/jstree.sort.js'
// import 'bootstrap-colorpicker'


class UccelloApp {
    constructor() {
        this.initGlobal()
        this.autoOpenMenu()
        this.initTranslation()
        this.initScrollSpy()
        this.initDateRangePicker()
        // this.initColorPicker()
        this.initCountTo()
        this.initJsTree()
    }

    initGlobal() {
        new Global()
    }

    initTranslation() {
        window.I18n = I18n
        window.uctrans = new I18n('uctranslations', ':')
    }

    initScrollSpy() {
        $(window).scroll(function(event) {
            if ($(this).scrollTop() > 20) {
                $('.navbar-top nav').removeClass('transparent').removeClass('z-depth-0')
                $('.breadcrumb-container').addClass('z-depth-0')
            } else {
                $('.navbar-top nav').addClass('transparent').addClass('z-depth-0')
                $('.breadcrumb-container').removeClass('z-depth-0')
            }
        })
    }

    initDateRangePicker() {
        // Date range picker
        $('.date-range-picker')
        .daterangepicker({
            autoUpdateInput: false,
            locale: this.getDaterangePickerLocale(),
            showDropdowns: true,
            applyButtonClasses: "waves-effect primary",
            cancelClass: "waves-effect btn-flat"
        }, function(start, end, label) {
            $(this).change()
        })
        .on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ', ' + picker.endDate.format('YYYY-MM-DD'))
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
            locale: this.getDaterangePickerLocale(),
            showDropdowns: true,
            applyButtonClasses: "waves-effect primary",
            cancelClass: "waves-effect btn-flat"
        }, function(start, end, label) {
            $(this).change()
        })
        .on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ', ' + picker.endDate.format('YYYY-MM-DD HH:mm'))
        })
        .on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('').change()
        })

        // Date picker
        $('.datepicker').daterangepicker({
            autoUpdateInput: false,
            locale: this.getDaterangePickerLocale(),
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
            locale: this.getDaterangePickerLocale(),
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            showDropdowns: true,
            applyButtonClasses: "waves-effect primary",
            cancelClass: "waves-effect btn-flat"
        })
        .on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'))
            $(this).parents('.input-field:first').find('label').addClass('active')
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
            locale: this.getDaterangePickerLocale(),
            singleDatePicker: true,
            showDropdowns: true
        })
        .on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM'))
            $(this).parents('.input-field:first').find('label').addClass('active')
        })

        // Week picker
        $('.weekpicker').daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: this.getDaterangePickerLocale(),
            singleDatePicker: true,
            showWeekNumbers: true,
            showDropdowns: true
        })
        .on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-w'))
            $(this).parents('.input-field:first').find('label').addClass('active')
        })
    }

    getDaterangePickerLocale() {
        return {
            format: uctrans.trans('default:calendar.format'),
            separator: uctrans.trans('default:calendar.separator'),
            applyLabel: uctrans.trans('default:calendar.apply'),
            cancelLabel: uctrans.trans('default:calendar.cancel'),
            fromLabel: uctrans.trans('default:calendar.from'),
            toLabel: uctrans.trans('default:calendar.to'),
            customRangeLabel: uctrans.trans('default:calendar.custom'),
            weekLabel: uctrans.trans('default:calendar.week'),
            daysOfWeek: [
                uctrans.trans('default:calendar.day.su'),
                uctrans.trans('default:calendar.day.mo'),
                uctrans.trans('default:calendar.day.tu'),
                uctrans.trans('default:calendar.day.we'),
                uctrans.trans('default:calendar.day.th'),
                uctrans.trans('default:calendar.day.fr'),
                uctrans.trans('default:calendar.day.sa'),
            ],
            monthNames: [
                uctrans.trans('default:calendar.month.january'),
                uctrans.trans('default:calendar.month.february'),
                uctrans.trans('default:calendar.month.march'),
                uctrans.trans('default:calendar.month.april'),
                uctrans.trans('default:calendar.month.may'),
                uctrans.trans('default:calendar.month.june'),
                uctrans.trans('default:calendar.month.july'),
                uctrans.trans('default:calendar.month.august'),
                uctrans.trans('default:calendar.month.september'),
                uctrans.trans('default:calendar.month.october'),
                uctrans.trans('default:calendar.month.november'),
                uctrans.trans('default:calendar.month.december'),
            ],
            firstDay: 1,
        }
    }

    initColorPicker() {
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
            $(e.currentTarget).parents('.input-field').find('.input-group-addon i')
                .css('color', `rgba(${rbga.r},${rbga.g},${rbga.b},${rbga.a})`)
        })
    }

    initCountTo() {
        $('.count-to').countTo()
    }

    initJsTree() {
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
                document.location.href = data.node.a_attr.href
            }
        })

        let to = false
        $('.domain-search-bar #domain-name').keyup(() => {
            if(to) {
                clearTimeout(to)
            }

            to = setTimeout(() => {
                let v = $('#domain-name').val()
                domainsTree.jstree(true).search(v)
            }, 250)
        })
    }

    autoOpenMenu() {
        let collapsible = $('.sidenav ul.collapsible ul li.active').parents('ul.collapsible:first')
        collapsible.collapsible('open')
        collapsible.find('li:first').addClass('active')
    }
}

new UccelloApp()