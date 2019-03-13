window._ = require('lodash')

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default
    window.$ = window.jQuery = require('jquery')

    require('materialize-css')
} catch (e) {}

// import noUiSlider from 'nouislider'

$('.sidenav').sidenav()

$('.sidenav-right').sidenav({
    edge: 'right'
})

$('.dropdown-trigger').dropdown({
    coverTrigger: false
})

$('.tabs').tabs()

$('select').formSelect()

// var rangeSliders = $('.nouislider_range');
// if (rangeSliders.length > 0) {

//     for (var rangeSlider of rangeSliders) {

//         var start = $(rangeSlider).data('start')
//         var min = $(rangeSlider).data('min')
//         var max = $(rangeSlider).data('max')
//         var step = $(rangeSlider).data('step')
//         var margin = $(rangeSlider).data('margin')
//         var limit = $(rangeSlider).data('limit')
//         var value = $(rangeSlider).data('value')

//         var connect = typeof start === 'object' && start.length > 1 ? true : 'lower'

//         // If a value is defined, update start value
//         if (value) {
//             start = [];

//             if (typeof value === 'string' && value.search(',') > -1) {
//                 start = value.split(',')
//             } else {
//                 start.push(value)
//             }
//         }

//         noUiSlider.create(rangeSlider, {
//             start: start,
//             connect: connect,
//             step: step,
//             range: {
//                 'min': min,
//                 'max': max
//             },
//             margin: margin ? margin : 1,
//             limit: limit ? limit : null,
//             // Legend
//             // pips: {
//             //     mode: 'steps',
//             //     stepped: true,
//             //     density: 4
//             // }
//         });

//         // getNoUISliderValue(rangeSlider, false);
//     }
// }