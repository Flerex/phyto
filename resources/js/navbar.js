document.addEventListener('DOMContentLoaded', e => {

    /**
     * Hamburger menu toggle in mobile version.
     */
    !function () {
        const trigger = document.querySelector('.navbar-burger'),
            menu = document.getElementById('navMenu')

        if (!trigger || !menu) return;

        trigger.addEventListener('click', e => {
            menu.classList.toggle('is-active')
        })
    }()

    /**
     * Navbar items with dropdown
     */
    !function () {
        const navbar = document.querySelector('.navbar'),
        items = document.querySelectorAll('.navbar-item.is-hoverable')

        if(!navbar || !items) return;

        document.addEventListener('click', e => {
            const item = e.target.parentNode;

            if(!item.classList.contains('navbar-item') || !item.classList.contains('has-dropdown')) return;

            item.classList.toggle('is-active');

        })


    }()

})
