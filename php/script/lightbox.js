// Lightbox implementation
function initLightbox() {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox-modal';
    lightbox.innerHTML = `
        <span class="lightbox-close">&times;</span>
        <img src="" alt="Agrandissement">
    `;
    document.body.appendChild(lightbox);

    const lightboxImg = lightbox.querySelector('img');
    const closeBtn = lightbox.querySelector('.lightbox-close');

    lightbox.addEventListener('click', (e) => {
        if (e.target !== lightboxImg) {
            lightbox.classList.remove('show');
            setTimeout(() => {
                if (!lightbox.classList.contains('show')) {
                    lightbox.style.display = 'none';
                }
            }, 300);
        }
    });

    closeBtn.addEventListener('click', () => {
        lightbox.classList.remove('show');
    });

    // Event delegation for opening the lightbox
    // We listen on the body so that dynamically added images (via fetch) are also caught
    document.body.addEventListener('click', (e) => {
        const target = e.target;
        if (target.matches('figure.centenaire>img, figure.histoire >img, div.skdf>img, div.histoire > img, div.photos > img, div.article-content > img, div.row> div.column > img')) {
            console.log(target);
            lightboxImg.src = target.src;
            lightbox.style.display = 'flex';
            setTimeout(() => lightbox.classList.add('show'), 10);
        }
    });

    // We add a global style chunk to ensure the cursor is always a pointer for these images, 
    // even the dynamically created ones.
    const style = document.createElement('style');
    style.innerHTML = `
        figure.centenaire>img, figure.histoire >img, div.skdf>img, div.histoire > img, div.photos > img, div.article-content > img, div.row>div.column > img {
            cursor: pointer;
        }
    `;
    document.head.appendChild(style);
}

document.addEventListener('DOMContentLoaded', () => {
    initLightbox();
});