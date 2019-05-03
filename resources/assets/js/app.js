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
        this.initContentHeight()
        this.autoOpenMenu()
        this.initTranslation()
        this.initScrollSpy()
        this.initDateRangePicker()
        // this.initColorPicker()
        this.initCountTo()
        this.initJsTree()
        this.initSearchBar()
    }

    initGlobal() {
        new Global()
    }

    initContentHeight() {
        $('main .content:first').css('min-height', $(document).height() - $('.navbar-header').height() - $('.navbar-top').height())
    }

    initTranslation() {
        window.I18n = I18n
        window.uctrans = new I18n('uctranslations')
    }

    initScrollSpy() {
        let that = this

        if (that.isMobileSize()) {
            $('.navbar-top nav').removeClass('transparent').removeClass('z-depth-0')
            $('.breadcrumb-container').addClass('z-depth-0')
        }

        $(window).scroll(function() {
            if (!that.isMobileSize() && $(this).scrollTop() > 20) {
                $('.navbar-top nav').removeClass('transparent').removeClass('z-depth-0')
                $('.breadcrumb-container').addClass('z-depth-0')
            } else if (!that.isMobileSize()) {
                $('.navbar-top nav').addClass('transparent').addClass('z-depth-0')
                $('.breadcrumb-container').removeClass('z-depth-0')
            }
        })
    }

    initDateRangePicker() {
        // Date range picker
        $('.date-range-picker').each((index, el) => {
            $(el).daterangepicker({
                autoUpdateInput: false,
                locale: this.getDaterangePickerLocale($(el).data('format')),
                showDropdowns: true,
                applyButtonClasses: "waves-effect primary",
                cancelClass: "waves-effect btn-flat"
            }, function(start, end, label) {
                $(this).change()
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format($(this).data('format')) + ', ' + picker.endDate.format($(this).data('format')))
            })
            .on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('').change()
            })
        })


        // Datetime range picker
        $('.datetime-range-picker').each((index, el) => {
            $(el).daterangepicker({
                autoUpdateInput: false,
                timePicker: true,
                timePicker24Hour: true,
                locale: this.getDaterangePickerLocale($(el).data('format')),
                showDropdowns: true,
                applyButtonClasses: "waves-effect primary",
                cancelClass: "waves-effect btn-flat"
            }, function(start, end, label) {
                $(this).change()
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format($(this).data('format')) + ', ' + picker.endDate.format($(this).data('format')))
            })
            .on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('').change()
            })
        })

        // Date picker
        $('.datepicker').each((index, el) => {
            $(el).daterangepicker({
                autoUpdateInput: false,
                locale: this.getDaterangePickerLocale($(el).data('format')),
                singleDatePicker: true,
                showDropdowns: true
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format($(this).data('format')))
            })
            .on('keyup', function() {
                let dateStr = $(this).val()
                if (moment(dateStr).isValid()) {
                    let date = moment(dateStr)
                    $(this).data('daterangepicker').setStartDate(date)
                    $(this).data('daterangepicker').setEndDate(date)
                }
            })
        })

        // Datetime picker
        $('.datetimepicker').each((index, el) => {
            $(el).daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: this.getDaterangePickerLocale($('meta[name="datetime-format-js"]').attr("content")),
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                showDropdowns: true,
                applyButtonClasses: "waves-effect primary",
                cancelClass: "waves-effect btn-flat"
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format($(this).data('format')))
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
        })

        // Month picker
        $('.monthpicker').each((index, el) => {
            $(el).daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: this.getDaterangePickerLocale($(el).data('format')),
                singleDatePicker: true,
                showDropdowns: true
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM'))
                $(this).parents('.input-field:first').find('label').addClass('active')
            })
        })

        // Week picker
        $('.weekpicker').each((index, el) => {
            $(el).daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: this.getDaterangePickerLocale($(el).data('format')),
                singleDatePicker: true,
                showWeekNumbers: true,
                showDropdowns: true
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-w'))
                $(this).parents('.input-field:first').find('label').addClass('active')
            })
        })
    }

    getDaterangePickerLocale(format) {
        return {
            format: format,
            separator: uctrans.trans('uccello::default.calendar.separator'),
            applyLabel: uctrans.trans('uccello::default.calendar.apply'),
            cancelLabel: uctrans.trans('uccello::default.calendar.cancel'),
            fromLabel: uctrans.trans('uccello::default.calendar.from'),
            toLabel: uctrans.trans('uccello::default.calendar.to'),
            customRangeLabel: uctrans.trans('uccello::default.calendar.custom'),
            weekLabel: uctrans.trans('uccello::default.calendar.week'),
            daysOfWeek: [
                uctrans.trans('uccello::default.calendar.day.su'),
                uctrans.trans('uccello::default.calendar.day.mo'),
                uctrans.trans('uccello::default.calendar.day.tu'),
                uctrans.trans('uccello::default.calendar.day.we'),
                uctrans.trans('uccello::default.calendar.day.th'),
                uctrans.trans('uccello::default.calendar.day.fr'),
                uctrans.trans('uccello::default.calendar.day.sa'),
            ],
            monthNames: [
                uctrans.trans('uccello::default.calendar.month.january'),
                uctrans.trans('uccello::default.calendar.month.february'),
                uctrans.trans('uccello::default.calendar.month.march'),
                uctrans.trans('uccello::default.calendar.month.april'),
                uctrans.trans('uccello::default.calendar.month.may'),
                uctrans.trans('uccello::default.calendar.month.june'),
                uctrans.trans('uccello::default.calendar.month.july'),
                uctrans.trans('uccello::default.calendar.month.august'),
                uctrans.trans('uccello::default.calendar.month.september'),
                uctrans.trans('uccello::default.calendar.month.october'),
                uctrans.trans('uccello::default.calendar.month.november'),
                uctrans.trans('uccello::default.calendar.month.december'),
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

    initSearchBar() {
        $(".navbar-header .search-btn").on('click', () => {
            $('.navbar-header .default-bar').hide()
            $('.navbar-header .search-bar').show()
            $('.navbar-header .search-bar #search').focus()
        })

        $('.navbar-header .search-bar #search').on('focusout', () => {
            $('.navbar-header .default-bar').show()
            $('.navbar-header .search-bar').hide()
            $('.navbar-header .search-bar #search').val('')
        })
    }

    autoOpenMenu() {
        let collapsible = $('.sidenav ul.collapsible ul li.active').parents('ul.collapsible:first')
        collapsible.collapsible('open')
        collapsible.find('li:first').addClass('active')
    }

    isMobileSize() {
        return $(document).width() < 601;
    }
}

new UccelloApp()