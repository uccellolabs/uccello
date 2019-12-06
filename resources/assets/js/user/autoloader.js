import { UserAccount } from './user-account'
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

            case 'user-account':
                new UserAccount()
                break;
        }
    }
}

new Autoloader()