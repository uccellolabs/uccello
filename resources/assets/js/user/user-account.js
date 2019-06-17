import Cropper from 'cropperjs'

export class UserAccount {
    constructor() {
        this.addAvatarTypeListener()
        this.addAvatarSubmitButtonListener()
        this.initFileUpload()
        this.initAvatarCropper()
    }

    /**
     * Add a listener on the avatar type radio buttons and ajust display according to the user choice.
     */
    addAvatarTypeListener() {
        let container = $('#change_avatar')

        $('input[name="avatar_type"]', container).on('change', (event) => {
            let element = event.currentTarget

            $('.info span', container).hide()
            $('.profile-image img, .profile-image div').hide()
            $('.image-upload').hide()

            switch($(element).val()) {
                case 'initials':
                        $('.info span.avatar-initials', container).show()
                        $('.profile-image .initials').show()
                    break

                case 'gravatar':
                        $('.info span.avatar-gravatar', container).show()
                        $('.profile-image .gravatar').show()
                    break

                case 'image':
                        $('.info span.avatar-image', container).show()
                        $('.profile-image .image').show()
                        $('.image-upload').show()
                    break
            }
        })
    }

    /**
     * Add a listner on the submit button
     */
    addAvatarSubmitButtonListener() {
        $('#avatar-submit').on('click', (event) => {
            $("#avatar-input").val(this.canvasDataUrl)
            $('#change_avatar form').submit()
        })
    }

    /**
     * Init file upload.
     */
    initFileUpload() {
        $('#avatar-file').on('change', (event) => {
            let files = event.currentTarget.files

            if (files && files.length > 0) {
                let file = files[0]

                let reader = new FileReader()
                reader.onload = (e) => {
                    this.cropper.replace(reader.result)
                }
                reader.readAsDataURL(file)
            }
        })

        $('#upload-link').on('click', event => {
            event.preventDefault()
            $('#avatar-file').click()
        })
    }

    /**
     * Init cropper for adjust the avatar image.
     */
    initAvatarCropper() {
        const image = document.getElementById('avatar-image-preview');
        this.cropper = new Cropper(image, {
            aspectRatio: 1,
            autoCropArea: 1,
            minContainerWidth: 250,
            minContainerHeight: 250,
            zoomable: false,
            crop: (event) => {
                let canvas = this.cropper.getCroppedCanvas()

                // Convert image into data string
                this.canvasDataUrl = canvas.toDataURL()

                $("img.image").prop('src', this.canvasDataUrl)
            },
        })
    }
}