// Menu burger mobile
const menuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const iconOpen = document.getElementById('menu-icon-open');
const iconClose = document.getElementById('menu-icon-close');

let isMenuOpen = false;

if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', function() {
        isMenuOpen = !isMenuOpen;

        if (isMenuOpen) {
            mobileMenu.classList.remove('translate-x-full');
            mobileMenu.classList.add('translate-x-0');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            mobileMenu.classList.add('translate-x-full');
            mobileMenu.classList.remove('translate-x-0');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // Fermer le menu quand on clique sur un lien
    mobileMenu.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            isMenuOpen = false;
            mobileMenu.classList.add('translate-x-full');
            mobileMenu.classList.remove('translate-x-0');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
            document.body.style.overflow = '';
        });
    });
}
