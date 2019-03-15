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

$('.dropdown-trigger').dropdown({
    coverTrigger: false
})

$('.tabs').tabs()

$('select').formSelect()

$('[data-tooltip]').tooltip()
