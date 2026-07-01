export function initImageProtect() {
    const images = document.querySelectorAll('img');
    images.forEach((img) => {
        img.setAttribute('draggable', 'false');
        
        // Prevent right click on images
        img.addEventListener('contextmenu', (e) => {
            e.preventDefault();
        });
    });
}
