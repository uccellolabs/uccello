// Bootstrap
import './bootstrap';

// Slimscroll
// import 'adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js';

// Admin BSB
import 'adminbsb-materialdesign';

// Admin BSB Plugins
import 'adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js';

// Node waves (for Admin BSB)
import 'node-waves';

// JSTree
import 'jstree/src/jstree.search.js';
import 'jstree/src/jstree.sort.js';

// Autosize
import autosize from 'adminbsb-materialdesign/plugins/autosize/autosize.js';
autosize($('textarea.auto-growth'));

// Tooltip
$("[data-toggle='tooltip']").tooltip();

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