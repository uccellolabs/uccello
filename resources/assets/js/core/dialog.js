export class Dialog {

    show(config, callback) {
        console.log(config)
        swal(config).then(callback)
    }
}
