window._ = require('lodash')
window.moment = require('moment')

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

function initMaterializeJs(element) {
    if (typeof element === 'undefined') {
        element = $(document);
    }

    // Init components
    $('#sidenav-menu', element).sidenav({
        edge: 'left',
        onOpenStart: () => {
            if ($(document).width() < 993) {
                $('#sidenav-domains', element).sidenav('close')
            }
        }
    })

    $('#sidenav-domains', element).sidenav({
        edge: 'right',
        onOpenStart: () => {
            if ($(document).width() < 993) {
                $('#sidenav-menu').sidenav('close')
            }
        }
    })

    $('.dropdown-trigger', element).each((index, el) => {
        $(el).dropdown({
            alignment: $(el).data('alignment') ? $(el).data('alignment') : 'left',
            constrainWidth: $(el).data('constrain-width') === false ? false : true,
            container: $(el).data('container') ? $($(el).data('container')) : null,
            coverTrigger: $(el).data('cover-trigger') === true ? true : false,
            closeOnClick: $(el).data('close-on-click') === false ? false : true,
            hover: $(el).data('hover') === true ? true : false,
        })
    })

    $('.tabs', element).tabs({
        // swipeable: true
    })

    $('select', element).each((index, el) => {
        $(el).formSelect({
            dropdownOptions: {
                alignment: $(el).data('alignment') ? $(el).data('alignment') : 'left',
                constrainWidth: $(el).data('constrain-width') === false ? false : true,
                container: $(el).data('container') ? $($(el).data('container')) : null,
                coverTrigger: $(el).data('cover-trigger') === true ? true : false,
                closeOnClick: $(el).data('close-on-click') === false ? false : true,
                hover: $(el).data('hover') === true ? true : false,
            }
        })
    })

    $('[data-tooltip]', element).tooltip({
        transitionMovement: 0,
    })

    $('.modal', element).each((index, el) => {
        $(el).modal()

        if ($(el).data('open')) {
            // Open by default
            $(el).modal('open')
        }
    })

    $('.collapsible', element).collapsible()
}

addEventListener('js.init.materialize', event => { // Used in uccello/import and uccello/module-designer-ui packages
    initMaterializeJs(event.detail.element || null);
});

initMaterializeJs();
