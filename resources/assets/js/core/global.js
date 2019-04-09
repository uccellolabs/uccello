import { Link } from './link'

export class Global {
    constructor() {
        this.initLinks()
        this.initNotifications()
    }

    initLinks() {
        new Link()
    }

    initNotifications() {
        $('.notification-container').each((index, element) => {
            let text = $(element).html()
            let classes = $(element).data('classes') ? $(element).data('classes') : ''

            M.toast({html: text, classes: classes})
        })
    }
}