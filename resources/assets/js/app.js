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

// Autosize
import autosize from 'adminbsb-materialdesign/plugins/autosize/autosize.js';
autosize($('textarea.auto-growth'));

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

// Datetime picker
$('.datepicker').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
$('.timepicker').bootstrapMaterialDatePicker({ date: false, format : 'HH:mm' });
$('.datetimepicker').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm' });
$('.monthpicker').bootstrapMaterialDatePicker({ format : 'MM', time: false });
$('.weekpicker').bootstrapMaterialDatePicker({ format : 'w', time: false });

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