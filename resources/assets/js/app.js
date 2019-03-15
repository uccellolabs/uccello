import './materialize'

// Translation
import i18next from 'i18next'
import i18nextLanguageBundle from '@kirschbaum-development/laravel-translations-loader?namespace=uccello!@kirschbaum-development/laravel-translations-loader'

class UccelloApp {
    constructor() {
        this.initTranslation()
    }

    initTranslation() {
        i18next.init({
            fallbackLng: 'en',
            debug: false,
            resources: i18nextLanguageBundle
        })
        i18next.changeLanguage($('html').attr('lang')) // Use project locale

        /**
         * Translates a string using i18nex library.
         * Replaces nsSeparator and keySeparator to be able to use '.' in the string we want to translate.
         * This function is available in all scripts.
         *
         * @param {String} string String to translate
         * @param {String} file File to use for translation. Default: default
         * @param {String} namespace Namespace to use for translation. Default: uccello
         */
        window.uctrans = (string, file, namespace) => {
            if (typeof file === 'undefined') {
                file = 'default'
            }

            if (typeof namespace === 'undefined') {
                namespace = 'uccello'
            }

            return i18next.t(`${namespace}::${file}:${string}`, { nsSeparator: '::', keySeparator: ':' })
        }
    }
}

new UccelloApp()