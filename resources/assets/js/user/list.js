import 'devbridge-autocomplete'

export class List {
    constructor() {
        this.initUserSearchField()
        this.initDomainRolesListener()
    }

    initUserSearchField() {
        let modal = $('#importUserModal')
        let el = $('#user_name', modal)

        el.devbridgeAutocomplete({
            serviceUrl: el.attr('data-url'),
            type: 'get',
            paramName: 'q',
            onSearchStart: () => {
                el.removeClass('invalid')
                $('#user_id', modal).val('')
                $('#domain_roles', modal).addClass('hide')
                $('button.save', modal).attr('disabled', true)
            },
            onSearchComplete: (query, suggestions) => {
                if (suggestions.length === 0) {
                    el.addClass('invalid')
                }
            },
            onSelect: (record) => {
                let hasRoles = $('#domain_roles select').formSelect('getSelectedValues').length > 0

                $('#user_id', modal).val(record.data.id)
                $('#domain_roles', modal).removeClass('hide')
                $('button.save', modal).attr('disabled', !hasRoles)
            },
            showNoSuggestionNotice: false,
            transformResult: function(response, originalQuery) {
                let results = {
                    suggestions: []
                }

                let originalResults = JSON.parse(response)
                for (let result of originalResults) {
                    results.suggestions.push({
                        value: result.title,
                        data: {
                            module: result.type,
                            id: result.searchable.id
                        }
                    })
                }

                return results
            }
        })
    }

    initDomainRolesListener() {
        let modal = $('#importUserModal')

        $('#domain_roles select', modal).on('change', event => {
            let hasRoles = $(event.currentTarget).formSelect('getSelectedValues').length > 0
            $('button.save', modal).attr('disabled', !hasRoles)
        })
    }
}