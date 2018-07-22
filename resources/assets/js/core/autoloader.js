import { List } from './list'

class Autoloader {
    constructor() {
        this.lazyLoad()
    }

    lazyLoad() {
        let page = $('meta[name="page"]').attr('content')

        switch (page) {
            case 'list':
                new List()
                break;
        }
    }
}

new Autoloader()