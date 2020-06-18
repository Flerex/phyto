!function() {
    const preventDefaultEvent = e => {
        e.preventDefault()
    }
    document.querySelectorAll('a[disabled]').forEach(a => {
        a.addEventListener('click', preventDefaultEvent)
    })
}()
