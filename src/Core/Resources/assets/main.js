import 'vite/modulepreload-polyfill';

import './reset.css';
import './typography.css';
import './colors.css';
import './layout.css';
import './footer.css';
import './link_shutter.css';

import lightbox from './lightbox/lightbox.js';
const mediaQueryList = window.matchMedia('(max-width: 1199px)');
document.addEventListener('click', e => {
    /** @type {HTMLImageElement} */
    const image = e.target.closest('img.lightbox-on-small-vw');

    if (!image) {
        return
    }

    if (!mediaQueryList.matches) {
        return
    }

    e.preventDefault();
    const scale = parseFloat(image.dataset.lightboxScale ?? 1);
    lightbox.openWithImage(image.src, scale, image.alt);
});
