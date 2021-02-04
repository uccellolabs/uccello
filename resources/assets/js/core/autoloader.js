import { List } from './list'
import { Edit } from './edit'
import { Detail } from './detail'
import { Tree } from './tree'
import { Summary } from './summary';

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

            case 'detail':
                new Detail()
                new Summary()
                break;

            case 'tree':
                new Tree()
                break;
        }
    }
}

new Autoloader()
