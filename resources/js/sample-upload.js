!function () {
    const mimetype2fa = require('mimetype-to-fontawesome')({prefix: 'fa-'}),
        dropZone = document.getElementById('upload-dropzone'),
        submitButton = document.querySelector('button[type="submit"]')

    const r = new Resumable({
        target: dropZone.dataset.url,
        simultaneousUploads: 3,
        query: file => {
            if (file.token) {
                return {token: file.token}
            }
            return {}
        },
        chunkSize: (10 * 1024 * 1024),
        forceChunkSize: true,
        testChunks: true,
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        }
    })

    r.on('fileAdded', file => {

        const allowedTypes = ['image/tiff', 'image/jpeg', 'image/png', 'application/x-compressed',
            'application/x-zip-compressed', 'application/zip', 'application/x-tar', 'application/gnutar',
            'application/x-compressed', 'application/x-compressed', 'application/x-gzip', 'multipart/x-gzip']

        if (!allowedTypes.includes(file.file.type)) {
            r.removeFile(file)
            document.querySelector('.upload-dropzone__error').classList.add('active')
            return
        }

        setSubmitButtonState(false)

        dropZone.classList.remove('empty')

        const fileElement = createFileNode(file)

        dropZone.appendChild(fileElement)

        r.upload()
    })

    r.on('fileProgress', file => {
        const progressNode = getFileNode(file.uniqueIdentifier).querySelector('.progress')

        progressNode.value = parseInt(file.progress() * 100)

    })

    r.on('complete', () => {
        setSubmitButtonState(true)
    })

    r.on('fileSuccess', (file, data) => {

        dropZone.append(createInvisibleInputNode(data))

        const fileNode = getFileNode(file.uniqueIdentifier)

        fileNode.classList.add('done')

        const progressNode = fileNode.querySelector('.progress');
        progressNode.classList.remove('is-link')
        progressNode.classList.add('is-success')

    })

    r.assignDrop(dropZone)
    r.assignBrowse(dropZone)


    dropZone.addEventListener('transitionend', e => {
        if(!e.target.classList.contains('progress')) return;

        e.target.classList.add('hidden')
    })


    function createInvisibleInputNode(id) {
        const input = document.createElement('INPUT');

        input.type = 'hidden'
        input.name = 'files[]'
        input.value = id

        return input
    }

    function createFileNode(file) {
        const fileElement = document.createElement('DIV'),
            icon = document.createElement('I'),
            span = document.createElement('SPAN'),
            progress = document.createElement('PROGRESS')

        fileElement.dataset.id = file.uniqueIdentifier

        fileElement.classList.add('file-upload')

        icon.classList.add('fas', mimetype2fa(file.file.type))
        span.textContent = file.file.name


        progress.classList.add('progress', 'is-link')
        progress.max = 100
        progress.value = 0
        progress.textContent = '0%'

        fileElement.appendChild(icon)
        fileElement.appendChild(span)
        fileElement.appendChild(progress)

        return fileElement
    }

    function getFileNode(uniqueIdentifier) {
        return document.querySelector(`.file-upload[data-id="${uniqueIdentifier}"]`)
    }

    function setSubmitButtonState(state) {
        // True enabled, false disabled
        submitButton.disabled = !state
    }

}()
