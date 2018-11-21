import { CustomLink } from './custom-link'

export class Global {
    constructor() {
        this.initCustomLinks()
    }

    initCustomLinks() {
        new CustomLink()
    }
}