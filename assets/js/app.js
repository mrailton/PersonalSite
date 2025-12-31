import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('mobileMenu', () => ({
    open: false,
    toggle() {
        this.open = !this.open
    }
}));

Alpine.start();
