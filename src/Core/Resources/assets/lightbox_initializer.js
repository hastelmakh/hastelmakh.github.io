import lightbox from './lightbox/lightbox.js';

const smallVwSize = 1199;
const mediaQueryList = window.matchMedia(`(max-width: ${smallVwSize}px)`);

const sheet = new CSSStyleSheet();
sheet.replaceSync(`
    img[data-lightbox] {
        &:not([data-lightbox=on-small-vw]) {
            cursor: pointer;
        }
        &[data-lightbox=on-small-vw] {
            @media (max-width: ${smallVwSize}px) {
                cursor: pointer;
            }
        }
    }
`);
document.adoptedStyleSheets = [
    ...document.adoptedStyleSheets,
    sheet
];

document.addEventListener('click', e => {
    /** @type {HTMLImageElement} */
    const image = e.target.closest('img[data-lightbox]');

    if (!image) {
        return
    }

    const isOnlySmallVw = image.dataset.lightbox === 'on-small-vw';
    if (isOnlySmallVw && !mediaQueryList.matches) {
        return
    }

    e.preventDefault();
    const scale = parseFloat(image.dataset.lightboxScale ?? 1);
    lightbox.openWithImage(image.src, scale, image.alt);
});
