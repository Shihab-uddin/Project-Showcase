document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sp-portfolio-snippet-button').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const imgUrl = btn.dataset.img;
            const popup = document.createElement('div');
            popup.style.position = 'fixed';
            popup.style.top = 0;
            popup.style.left = 0;
            popup.style.width = '100%';
            popup.style.height = '100%';
            popup.style.background = 'rgba(0,0,0,0.8)';
            popup.style.display = 'flex';
            popup.style.alignItems = 'center';
            popup.style.justifyContent = 'center';
            popup.style.zIndex = 10000;
            popup.innerHTML = '<img src="' + imgUrl + '" style="max-width:90%; max-height:90%; border-radius:10px;" />';
            popup.addEventListener('click', () => popup.remove());
            document.body.appendChild(popup);
        });
    });
});