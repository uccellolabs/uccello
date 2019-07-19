import { Link } from './link'

export class Datatable {
    /**
     * Init Datatable configuration
     * @param {Element} element
     */
    init(element, rowClickCallback) {
        this.table = $(element)
        this.linkManager = new Link(false)
        this.rowClickCallback = rowClickCallback

        this.initColumns()
        this.initColumnsSortListener()
        this.initColumnsVisibilityListener()
        this.initRecordsNumberListener()
        this.initColumnsSearchListener()
        this.initRefreshContentEventListener()
    }

    initColumns() {
        this.columns = {}

        if (!this.table) {
            return
        }

        $('th[data-field]', this.table).each((index, el) => {
            let element = $(el)
            let fieldName = element.data('field')

            if (typeof fieldName !== 'undefined') {
                this.columns[fieldName] = {
                    columnName: element.data('column'),
                    search: $('.field-search', element).val()
                }
            }
        })
    }

    makeQuery(page) {
        if (!this.table) {
            return
        }

        // Get query URL
        let url = $(this.table).attr('data-content-url')

        // Delete old records
        // $('tbody tr.record', this.table).remove()

        // Hide no_result row
        $('tbody tr.no-results', this.table).hide()

        // Hide pagination
        $(`.pagination[data-table="${this.table.attr('id')}"]`).hide()

        // Show loader
        $(`.loader[data-table="${this.table.attr('id')}"]`).show()

        // Query data
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: $('meta[name="record"]').attr('content'),
            columns: this.columns,
            order: $(this.table).attr('data-order') ? JSON.parse($(this.table).attr('data-order')) : null,
            relatedlist: $(this.table).attr('data-relatedlist') ? $(this.table).attr('data-relatedlist') : null,
            length: $(this.table).attr('data-length'),
        }

        if (typeof page !== 'undefined') {
            data.page = page
        }

        // Make query
        $.post(url, data).then((response) => {
            this.displayResults(response)
            this.displayPagination(response)

            // Hide loader
            $(`.loader[data-table="${this.table.attr('id')}"]`).hide()
        })
        .catch((error) => {
            // Hide loader
            $(`.loader[data-table="${this.table.attr('id')}"]`).addClass('hide')

            // Show error
            swal(uctrans.trans('uccello::default.dialog.error.title'), uctrans.trans('uccello::default.dialog.error.message'), 'error')
        })
    }

    displayResults(response) {
        if (!this.table || !response.data) {
            return
        }

        // Delete old records
        $('tbody tr.record', this.table).remove()

        if (response.data.length === 0) {
            // No result
            $('tbody tr.no-results', this.table).show()
        } else {
            // Add a row by record
            for(let record of response.data) {
                this.addRowToTable(record)
            }
        }
    }

    displayPagination(response) {
        if (!this.table || !response.data) {
            return
        }

        let currentPage = response.current_page || 1
        let pageRange = 2
        let totalPage = response.last_page

        let rangeStart = currentPage - pageRange
        let rangeEnd = currentPage + pageRange

        if (rangeEnd > totalPage) {
          rangeEnd = totalPage
          rangeStart = totalPage - pageRange * 2
          rangeStart = rangeStart < 1 ? 1 : rangeStart
        }

        if (rangeStart <= 1) {
          rangeStart = 1
          rangeEnd = Math.min(pageRange * 2 + 1, totalPage)
        }

        // Add a link to the previous page
        let previousLinkClass = response.prev_page_url === null ? 'disabled' : 'waves-effect'
        let previousDataPage = response.prev_page_url ? `data-page="${response.current_page - 1}"` : ''
        let paginationHtml = `<li class="${previousLinkClass}"><a href="javascript:void(0);" ${previousDataPage}><i class="material-icons">chevron_left</i></a></li>`

        let i

        if (rangeStart <= pageRange + 1) {
            // Add 1 to 5
            for (i = 1; i < rangeStart; i++) {
                if (i == currentPage) {
                    paginationHtml += `<li class="active primary"><a href="javascript:void(0);">${i}</a></li>`
                } else {
                    paginationHtml += `<li class="waves-effect"><a href="javascript:void(0);" data-page="${i}">${i}</a></li>`
                }
            }
        } else {
            // Add 1 ... at the beginning
            paginationHtml += `<li class="waves-effect"><a href="javascript:void(0);" data-page="1">1</a></li> <li>...</li>`
        }

        // Add 2 pages before and after the current page
        for (i=rangeStart; i<=rangeEnd; i++) {
            if (i === currentPage) {
                paginationHtml += `<li class="active primary"><a href="javascript:void(0);">${i}</a></li>`
            } else {
                paginationHtml += `<li class="waves-effect"><a href="javascript:void(0);" data-page="${i}">${i}</a></li>`
            }
        }

        // Add ... lastPage
        if (rangeEnd < totalPage) {
            paginationHtml += `<li>...</li> <li class="waves-effect"><a href="javascript:void(0);" data-page="${totalPage}">${totalPage}</a></li>`
        }

        // Add a link to the next page
        let nextLinkClass = response.next_page_url === null ? 'disabled' : 'waves-effect'
        let nextDataPage = response.next_page_url ? `data-page="${response.current_page + 1}"` : ''
        paginationHtml += `<li class="${nextLinkClass}"><a href="javascript:void(0);" ${nextDataPage}><i class="material-icons">chevron_right</i></a></li>`

        let paginationElement = $(`.pagination[data-table="${this.table.attr('id')}"]`)
        paginationElement.html(paginationHtml)
        paginationElement.show()

        // Init click listener
        $('a[data-page]', paginationElement).on('click', (el) => {
            let page = $(el.currentTarget).attr('data-page')

            this.makeQuery(page)
        })
    }

    addRowToTable(record) {
        if (!this.table || !record) {
            return
        }

        let that = this

        // Clone row template
        let tr = $('tbody tr.template', this.table).clone()

        // Create each cell according to all headers
        $('th[data-field]', this.table).each(function() {
            let fieldName = $(this).data('field')
            // let fieldColumn = $(this).data('column')

            // Add content to the cell
            let td = $(`td[data-field="${fieldName}"] `, tr)
            td.html(record[fieldName + '_html']) // Get html content

            // Hide if necessary
            if ($(this).css('display') === 'none') {
                td.hide()
            }
        })

        // Replace RECORD_ID by the record's id in all links
        $('a', tr).each(function() {
            let href = $(this).attr('href')
            href = href.replace('RECORD_ID', record.id)
            $(this).attr('href', href)

            if ($(this).attr('data-tooltip')) {
                $(this).tooltip()
            }
        })

        // Replace RECORD_ID by the record's id in the row url
        let rowUrl = $(tr).attr('data-row-url')
        rowUrl = rowUrl.replace('RECORD_ID', record.id)
        $(tr).attr('data-row-url', rowUrl)

        $(tr).attr('data-record-id', record.id)
        $(tr).attr('data-record-label', record.recordLabel)

        // Add click listener
        $(tr).on('click', function(event) {
            if (typeof that.rowClickCallback !== 'undefined') {
                that.rowClickCallback(event, that, $(this).attr('data-record-id'), $(this).attr('data-record-label'))
            } else {
                document.location.href = $(this).attr('data-row-url')
            }
        })

        // Dispatch event
        let event = new CustomEvent('uccello.list.row_added', {
            detail: {
                element: tr,
                record: record
            }
        })
        dispatchEvent(event)

        // Init click listener on delete button
        this.linkManager.initClickListener(tr)

        // Add the record to tbody
        tr.removeClass('hide')
            .removeClass('template')
            .addClass('record')
            .appendTo(`#${this.table.attr('id')} tbody`) // We use the id else it append not always into the good table (if there are several)
    }

    initColumnsVisibilityListener() {
        $(`ul.columns[data-table="${this.table.attr('id')}"] li a`).on('click', (el) => {
            let element = $(el.currentTarget)
            let fieldName = $(element).data('field')

            // Select or unselect item in dropdown
            let liElement = $(element).parents('li:first')
            liElement.toggleClass('active')

            // Show or hide column
            if (liElement.hasClass('active')) {
                $(`th[data-field="${fieldName}"]`).show() // Label
                $(`td[data-field="${fieldName}"]`).show() // Content
            } else {
                $(`th[data-field="${fieldName}"]`).hide() // Label
                $(`td[data-field="${fieldName}"]`).hide() // Content
            }
        })
    }

    initRecordsNumberListener() {
        if (!this.table) {
            return
        }

        $(`ul.records-number[data-table="${this.table.attr('id')}"] li a`).on('click', (el) => {
            let element = $(el.currentTarget)
            let number = $(element).data('number')

            // Select or unselect item in dropdown
            let ulId = $(element).parents('ul:first').attr('id')
            $(`a[data-target="${ulId}"] strong.records-number`).text(number)

            $(this.table).attr('data-length', number)

            this.makeQuery()
        })
    }

    initColumnsSearchListener() {
        if (!this.table) {
            return
        }

        this.timer = 0
        let that = this

        // Config each column
        $('th[data-field]', this.table).each((index, el) => {
            let element = $(el)
            let fieldName = element.data('field')

            $('input:not(.nosearch)', element).on('keyup apply.daterangepicker cancel.daterangepicker', function() {
                that.launchSearch(fieldName, $(this).val())
            })

            $('select:not(.nosearch)', element).on('change', function() {
                that.launchSearch(fieldName, $(this).val())
            })
        })

        // Add clear search button listener
        this.addClearSearchButtonListener()
    }

    /* Refresh content */
    initRefreshContentEventListener() {
        if (!this.table) {
            return
        }

        addEventListener('uccello.list.refresh', (event) => {
            if (event.detail === $(this.table).attr('id')) {
                this.makeQuery()
            }
        })
    }

    /**
     * Launch search
     * @param {String} fieldName
     * @param {String} q
     */
    launchSearch(fieldName, q)
    {
        if (q !== '') {
            $('.clear-search').show()
        }

        if (this.columns[fieldName].search !== q) {
            this.columns[fieldName].search = q

            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
                this.makeQuery()
            }, 700)
        }
    }

    /**
     * Clear datatable search
     */
    addClearSearchButtonListener() {
        if (!this.table) {
            return
        }

        $('.actions-column .clear-search').on('click', (event) => {
            // Clear all search fields
            $('thead select', this.table).val('')
            $('thead input', this.table).val('')

            // Update columns
            this.initColumns()

            // Disable clear search button
            $(event.currentTarget).hide()

            // Update data
            this.makeQuery()
        })
    }

    initColumnsSortListener() {
        if (!this.table) {
            return
        }

        $('th[data-field].sortable', this.table).each((index, el) => {
            let element = $(el)
            let fieldColumn = element.data('column')

            $('a.column-label', element).on('click', (event) => {
                // Get current sort order
                let order = this.table.attr('data-order') ? JSON.parse(this.table.attr('data-order')) : null

                // Hide all sort icons
                $('a.column-label i').hide()

                // Adapt icon according to sort order
                if (order !== null && order[fieldColumn] === 'asc') {
                    order[fieldColumn] = 'desc'
                    $('a.column-label i', element).removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down')
                } else {
                    order = {}
                    order[fieldColumn] = 'asc'
                    $('a.column-label i', element).removeClass('fa-sort-amount-down').addClass('fa-sort-amount-up')
                }

                // Show column's sort icon
                $('a.column-label i', element).show()

                // Update sort order in the datatable
                this.table.attr('data-order', JSON.stringify(order))

                // Make query
                this.makeQuery()
            })
        })
    }
}