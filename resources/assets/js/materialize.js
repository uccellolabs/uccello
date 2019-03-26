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

// Fastclick
var FastClick = require('fastclick')
FastClick.attach(document.body)

// Init components
$('.sidenav').sidenav()

$('.sidenav-right').sidenav({
    edge: 'right'
})

$('.dropdown-trigger').each((index, el) => {
    $(el).dropdown({
        alignment: $(el).data('alignment') ? $(el).data('alignment') : 'left',
        constrainWidth: $(el).data('constrain-width') === false ? false : true,
        coverTrigger: $(el).data('cover-trigger') === true ? true : false,
        closeOnClick: $(el).data('close-on-click') === false ? false : true,
        hover: $(el).data('hover') === true ? true : false,
    })
})

$('.tabs').tabs()

$('select').formSelect()

$('[data-tooltip]').tooltip()