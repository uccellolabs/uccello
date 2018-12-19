import { Index } from './index'

class Autoloader {
    constructor() {
        this.lazyLoad()
    }

    lazyLoad() {
        let page = $('meta[name="page"]').attr('content')

        switch (page) {
            case 'index':
                new Index()
                break;
        }
    }
}

new Autoloader()