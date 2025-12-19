import './lightbox.css'

export default {
    /**
     * @param src {string} Path to image
     * @param alt {string} Alternative text
     */
    openWithImage: function (src, alt = '') {
        const image = document.createElement('img');
        image.src = src;
        image.alt = alt;
        lightbox.open(image);
    },

    close: function () {
        lightbox.close()
    }
};

const lightbox = {
    /** @type {?HTMLDialogElement} */
    dialog: null,

    /**
     * @param element {HTMLElement}
     * */
    open: function (element) {
        this.dialog = document.createElement('dialog');
        this.dialog.className = 'lightbox';
        this.dialog.innerHTML = `<div class="viewport">${element.outerHTML}</div>`;
        document.body.appendChild(this.dialog);

        scroll.disable();
        this.dialog.showModal();

        this.dialog.addEventListener('click', (e) => {
            this.dialog.close();
        });

        this.dialog.addEventListener('close', (e) => {
            this.close();
        });

        this.dialog.addEventListener('cancel', (e) => {
            this.close();
        });
    },

    close: function () {
        if (this.dialog === null) {
            return;
        }

        scroll.enable();
        this.dialog.remove();
        this.dialog = null;
    },
}

const scroll = {
    /** @type {number} */
    scrollY: 0,

    disable() {
        this.scrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${this.scrollY}px`;
        document.body.style.width = '100%';
    },

    enable() {
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        window.scrollTo(0, this.scrollY);
    },
};
