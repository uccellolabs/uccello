import { UitypeText } from './uitypes/text'
import { UitypeEntity } from './uitypes/entity'
import { UitypeCheckbox } from './uitypes/checkbox'
import { UitypeFile } from './uitypes/file'

export class UitypeSelector {
    uitypes() {
        return {
            text: UitypeText,
            entity: UitypeEntity,
            boolean: UitypeCheckbox,
            checkbox: UitypeCheckbox,
            file: UitypeFile,
            default: UitypeText
        }
    }

    get(name) {
        let uitypes = this.uitypes()
        let uitype = uitypes[name] || uitypes.default

        return new uitype()
    }
}