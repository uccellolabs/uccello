import { UitypeText } from './uitypes/text'
import { UitypeEntity } from './uitypes/entity'

export class UitypeSelector {
    uitypes() {
        return {
            text: UitypeText,
            entity: UitypeEntity,
            default: UitypeText
        }
    }

    get(name) {
        let uitypes = this.uitypes()
        let uitype = uitypes[name] || uitypes.default

        return new uitype()
    }
}