import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

// ── Counter Animation ────────────────────────────────────────────
Alpine.data('counterAnimation', (target, duration = 2000) => ({
    current: 0,
    target: target,
    init() {
        this.animateCounter();
    },
    animateCounter() {
        const startTime = performance.now();
        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            this.current = Math.floor(eased * this.target);
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                this.current = this.target;
            }
        };
        requestAnimationFrame(step);
    },
    formattedNumber() {
        return new Intl.NumberFormat('id-ID').format(this.current);
    }
}));

Alpine.plugin(intersect);
window.Alpine = Alpine;
Alpine.start();

// ── Format Currency ──────────────────────────────────────────────
window.formatRupiah = function(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(number);
};
