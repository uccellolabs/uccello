import Sortable from 'sortablejs';

export class Summary {
    constructor() {
        var dico = this.initDico();
        this.moveElement(dico);
    }

    moveElement(dico) {
        var el = document.getElementById('movable');
        Sortable.create(el, {
            handle: '.material-icons',
            animation: 200,
            //if we move an element, save the new position in dico.
            onSort: function (evt, originalEvent) {
                for (var key in dico) {
                    if (dico.hasOwnProperty(key) && key != evt.item.dataset.widget) {
                        if (dico[evt.item.dataset.widget] > dico[key] && dico[key] >= evt.newIndex)
                            dico[key]++;
                        if (dico[evt.item.dataset.widget] < dico[key] && dico[key] <= evt.newIndex)
                            dico[key]--;
                    }
                }
                dico[evt.item.dataset.widget] = evt.newIndex;

                //Test but doesn't work
                /*
                $.post($('table[data-filter-type="list"]').prevObject[0].URL, "data");

                $.ajax({
                    type : 'post',
                    url : $('table[data-filter-type="list"]').prevObject[0].URL,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success:function(){
                        console.log("GG");
                    }
                }).then(resultat => console.log("non")); */
            }
        });
    }

    /**
     * Init the dictionary, to save position
     * Key : id of the module
     * Value : sequance num
     */
    initDico() {
        var dico = {};
        var elements = document.getElementsByClassName("item");

        for (let i = 0; i < elements.length; i++) {
            dico[elements[i].dataset.widget.toString()] = i;
        }
        return dico;
    }
}
