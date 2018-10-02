import { List } from './list'
import { Edit } from './edit'

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

            case 'edit':
                new Edit()
                break;
        }
    }
}

new Autoloader()