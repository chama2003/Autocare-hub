
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('theme', {
    dark: localStorage.getItem('theme') === 'dark',
    init() {
        this.sync();
    },
    toggle() {
        this.dark = !this.dark;
        this.sync();
    },
    sync() {
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

Alpine.start();
