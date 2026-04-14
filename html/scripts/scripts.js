/* scripts.js */
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('copy', e => {
    e.preventDefault();
});

const telescriptlist = [
    "Refuge du Roedelen",
    "Ecole de Ski Enfant 2022 / 2023",
    "Nouvelle tranche de travaux 2022"
];

const imgheader = [
    "media/bannieretrav.png",
    "media/ecoledeski.jpg",
    "media/roedelen_hiver.jpg",
    "media/ecoledeski.jpg",
    "media/bancarna.jpg",
    "media/ecolesdf.jpg"
];

let currentTextIndex = 0;
let currentImgIndex = 0;

function startCarousels() {
    const navTelescript = document.querySelector('nav.telescript');
    const headerElement = document.querySelector('header');

    // Text Carousel
    function updateText() {
        if (navTelescript) {
            //navTelescript.style.opacity = 0;
            setTimeout(() => {
                navTelescript.innerHTML = "<a href=''>⋆⋆ " + telescriptlist[currentTextIndex] + " ⋆⋆</a>";
                //navTelescript.style.opacity = 1;
                currentTextIndex = (currentTextIndex + 1) % telescriptlist.length;
            }, 500);
        }
    }

    // Image Carousel
    function updateImage() {
        if (headerElement) {
            let basePath = window.location.pathname.includes('/html/') ? '../' : '';
            headerElement.style.backgroundImage = `linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.4)), url('${basePath}${imgheader[currentImgIndex]}')`;
            currentImgIndex = (currentImgIndex + 1) % imgheader.length;
        }
    }

    // Initial call
    updateText();
    updateImage();

    // Intervals
    setInterval(updateText, 5000); // Change text every 5s
    setInterval(updateImage, 10000); // Change image every 10s
}

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
        if (target.matches('div.photos > img, div.article-content > img, div.row > div.column > img')) {
            lightboxImg.src = target.src;
            lightbox.style.display = 'flex';
            setTimeout(() => lightbox.classList.add('show'), 10);
        }
    });

    // We add a global style chunk to ensure the cursor is always a pointer for these images, 
    // even the dynamically created ones.
    const style = document.createElement('style');
    style.innerHTML = `
        div.photos > img, div.article-content > img, div.row > img {
            cursor: pointer;
        }
    `;
    document.head.appendChild(style);
}

document.addEventListener('DOMContentLoaded', () => {
    startCarousels();
    initLightbox();
});