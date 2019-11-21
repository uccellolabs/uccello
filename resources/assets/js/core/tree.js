export class Tree {
    constructor() {
        this.initJsTree();
    }

    initJsTree() {
        let moduleTree = $('#treeview')
        moduleTree.jstree({
            "core" : {
                "themes" : {
                    "icons": false
                },
                "data" : {
                    "url" : function (node) {
                        return node.id === '#' ?
                        $("meta[name='module-tree-default-url']").attr('content') :
                        $("meta[name='module-tree-children-url']").attr('content');
                    },
                    "data" : function (node) {
                        return { 'id' : node.id };
                    }
                }
            },
            "plugins" : ['search', 'sort'],
            "search" : {
                "show_only_matches" : true
            }
        })

        // Open tree automatically
        .on('ready.jstree', () => {
            if ($("meta[name='module-tree-open-all']").attr('content')) {
                moduleTree.jstree('open_all')
            }
        })

        // Switch on detail view on click
        .on('changed.jstree', (e, data) => {
            if (data.node.a_attr.href !== '#') {
                document.location.href = data.node.a_attr.href
            }
        })

        let to = false
        $('.treeview-search-bar #record-name').keyup(() => {
            if(to) {
                clearTimeout(to)
            }

            to = setTimeout(() => {
                let v = $('#record-name').val()
                moduleTree.jstree(true).search(v)
            }, 250)
        })
    }
}