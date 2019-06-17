import { UserAccount } from './user-account'

class Autoloader {
    constructor() {
        this.lazyLoad()
    }

    lazyLoad() {
        let page = $('meta[name="page"]').attr('content')

        switch (page) {
            case 'user-account':
                new UserAccount()
                break;
        }
    }
}

new Autoloader()