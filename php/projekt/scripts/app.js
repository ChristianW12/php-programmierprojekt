document.addEventListener('DOMContentLoaded', function () {
    const menu = document.querySelector('.user-menu');
    const toggleButton = document.querySelector('#user-menu-toggle'); // ID verwenden
    const panel = document.querySelector('#user-menu-panel');

    if (!menu || !toggleButton || !panel) return;

    function openMenu() {
        menu.classList.add('open');
    }

    function closeMenu() {
        menu.classList.remove('open');
    }

    toggleButton.addEventListener('click', function (event) {
        event.stopPropagation(); // verhindert sofortiges Schließen durch document-Handler
        if (menu.classList.contains('open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // Klick außerhalb schließt
    document.addEventListener('click', function (event) {
        if (!menu.contains(event.target)) {
            closeMenu();
        }
    });

    // Klicks im Panel nicht nach oben „blubbern“
    panel.addEventListener('click', function (event) {
        event.stopPropagation();
    });
});
