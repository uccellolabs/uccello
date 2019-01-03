import { Link } from './link'
import { Notify } from './notify'

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
            let color = $(element).data('color')
            let placementFrom = $(element).data('placement')
            let placementAlign = $(element).data('align')
            let animationEnter = $(element).data('animation-enter')
            let animationExit = $(element).data('animation-exit')

            let notify = new Notify()
            notify.show(text, color, placementFrom, placementAlign, animationEnter, animationExit)
        })
    }
}