import { List } from './list'
import { Edit } from './edit'
import { Detail } from './detail'
import { Global } from './global'

class Autoloader {
    constructor() {
        this.lazyLoad()
        this.initGlobal()
    }

    lazyLoad() {
        let page = $('meta[name="page"]').attr('content')

        switch (page) {
            case 'list':
                new List()
                break;

            case 'edit':
                new Edit()
                break;

            case 'detail':
                new Detail()
                break;
        }
    }

    initGlobal() {
        new Global();
    }
}

new Autoloader()