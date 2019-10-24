import { Datatable } from './datatable'

export class Detail {
    constructor() {
        this.initRelatedLists()
        this.initRelatedListSelectionModalListener()
    }

    /**
     * Initalize datatable for all related lists
     */
    initRelatedLists() {
        if ($('table[data-filter-type="related-list"]').length == 0) {
            return
        }

        this.relatedListDatatables = {}

        $('table[data-filter-type="related-list"]').each((index, el) => {
            let datatable = new Datatable()
            datatable.init(el)
            datatable.makeQuery()

            this.relatedListDatatables[$(el).attr('id')] = datatable
        })
    }

    /**
     * Show a selection modal and initialize datatable in it
     */
    initRelatedListSelectionModalListener() {
        $('.btn-relatedlist-select').on('click', (event) => {
            const element = event.currentTarget

            const relatedListId = $(element).data('relatedlist')
            const modalTitle = $(element).data('modal-title')
            const modalIcon = $(element).data('modal-icon')
            const modalBody = $(`.selection-modal-content[data-relatedlist='${relatedListId}']`).html()
            const relatedListTable = $(element).data('table')

            // Get modal
            const modal = $('#relatedListSelectionModal')

            // Change modal title
            $('h4 span', modal).text(modalTitle)

            // Change modal icon
            $('h4 i', modal).text(modalIcon)

            // Change modal body
            $('.modal-body', modal).html(modalBody)

            // Init datatable
            $('table tbody tr.record', modal).remove()

            // Display search fields
            $('table thead .search', modal).removeClass('hide')

            // Click callback
            let rowClickCallback = (event, datatable, recordId, oldRelatedName) => {
                
                if(oldRelatedName)
                {
                    swal({
                        title: uctrans.trans('uccello::default.confirm.dialog.title'),
                        text: "Cette entité est déjà liée à "+oldRelatedName+". Si vous continuez, "+oldRelatedName+" sera remplacé par l'entité actuelle.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        buttons: [
                            uctrans.trans('uccello::default.button.no'),
                            uctrans.trans('uccello::default.button.yes')
                        ],
                    })
                    .then((response) => {
                        if (response === true) {
                            this.relatedListNNRowClickCallback(relatedListId, relatedListTable, datatable, recordId)
                        }
                    })
                }
                else
                    this.relatedListNNRowClickCallback(relatedListId, relatedListTable, datatable, recordId)

            }

            // Init datatable for selection
            let datatable = new Datatable()
            datatable.init($('table', modal), rowClickCallback)
            datatable.makeQuery()
        })
    }

    /**
     * Callback to call when a row is clicked in a datatable for a N-N related list
     * @param {integer} relatedListId
     * @param {Element} relatedListTable
     * @param {Object} modalDatatableInstance
     * @param {integer} relatedRecordId
     */
    relatedListNNRowClickCallback(relatedListId, relatedListTable, modalDatatableInstance, relatedRecordId) {
        const url = $(modalDatatableInstance.table).data('add-relation-url')

        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: $('meta[name="record"]').attr('content'),
            relatedlist: relatedListId,
            related_id: relatedRecordId
        }

        // Ajax call to make a relation between two records
        $.post(url, data)
        .then((response) => {
            // Display an alert if an error occured
            if (response.success === false) {
                swal(uctrans.trans('uccello::default.dialog.error.title'), response.message, 'error')
            }
            else {
                // Refresh relatedlist datatable
                let relatedListDatatable = this.relatedListDatatables[relatedListTable]
                if (relatedListDatatable) {
                    relatedListDatatable.makeQuery()
                }

                // Hide modal
                $('#relatedListSelectionModal').modal('close')
            }
        })
    }
}