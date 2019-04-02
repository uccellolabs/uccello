import { Detail } from './detail'
import { Edit } from './edit'

class Autoloader {
    constructor() {
        this.lazyLoad()
    }

    lazyLoad() {
        let page = $('meta[name="page"]').attr('content')

        switch (page) {
            case 'detail':
                new Detail()
                break;

            case 'edit':
                new Edit()
                break;
        }
    }
}

new Autoloader()