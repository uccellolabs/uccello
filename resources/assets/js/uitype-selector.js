import { UitypeText } from './uitypes/text'
import { UitypeEntity } from './uitypes/entity'
import { UitypeCheckbox } from './uitypes/checkbox'
import { UitypeFile } from './uitypes/file'
import { UitypeImage } from './uitypes/image'

export class UitypeSelector {
    uitypes() {
        return {
            text: UitypeText,
            entity: UitypeEntity,
            boolean: UitypeCheckbox,
            checkbox: UitypeCheckbox,
            file: UitypeFile,
            image: UitypeImage,
            default: UitypeText
        }
    }

    get(name) {
        let uitypes = this.uitypes()
        let uitype = uitypes[name] || uitypes.default

        return new uitype()
    }
}