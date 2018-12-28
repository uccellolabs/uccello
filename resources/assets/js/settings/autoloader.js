import { ModuleManager } from './module-manager'
import { MenuManager } from './menu-manager'

class Autoloader {
    constructor() {
        this.lazyLoad()
    }

    lazyLoad() {
        let page = $('meta[name="page"]').attr('content')

        switch (page) {
            case 'module-manager':
                new ModuleManager()
                break;

            case 'menu-manager':
                new MenuManager()
                break;
        }
    }
}

new Autoloader()