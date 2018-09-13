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

// Autosize
import autosize from 'adminbsb-materialdesign/plugins/autosize/autosize.js';
autosize($('textarea.auto-growth'));

// Tooltip
$("[data-toggle='tooltip']").tooltip();

// Color picker
$('.colorpicker').colorpicker();

// Datetime picker
$('.datepicker').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
$('.timepicker').bootstrapMaterialDatePicker({ date: false, format : 'HH:mm' });
$('.datetimepicker').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm' });
$('.monthpicker').bootstrapMaterialDatePicker({ format : 'MM', time: false });
$('.weekpicker').bootstrapMaterialDatePicker({ format : 'w', time: false });


var rangeSliders = $('.nouislider_range');
if (rangeSliders.length > 0) {

    for (var rangeSlider of rangeSliders) {
        noUiSlider.create(rangeSlider, {
            start: [0],
            connect: 'lower',
            step: 1,
            range: {
                'min': [0],
                'max': [100]
            }
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

.on('ready.jstree', () => {
    domainsTree.jstree('open_all')
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