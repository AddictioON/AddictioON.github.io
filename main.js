document.addEventListener('DOMContentLoaded', () => {
    const newsGrid = document.querySelector('.news-grid');
    if (!newsGrid) {
        console.error('news-grid element not found.');
        return;
    }

    const newsItems = Array.from(newsGrid.children);
    const prevButton = document.querySelector('.news-nav.prev');
    const nextButton = document.querySelector('.news-nav.next');

    if (!prevButton || !nextButton) {
        console.error('Navigation buttons not found.');
        return;
    }

    let currentIndex = 0;
    const visibleItems = 3;

    const updateVisibility = () => {
        newsItems.forEach((item, index) => {
            if (index >= currentIndex && index < currentIndex + visibleItems) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    };

    const slideNews = (direction) => {
        currentIndex += direction;
        if (currentIndex < 0) {
            currentIndex = 0;
        } else if (currentIndex + visibleItems > newsItems.length) {
            currentIndex = newsItems.length - visibleItems;
        }
        updateVisibility();
    };

    updateVisibility();
    prevButton.addEventListener('click', () => slideNews(-1));
    nextButton.addEventListener('click', () => slideNews(1));
});

function openNews(id) {
    const content = document.getElementById('news-content-' + id);
    if (content) {
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
    }
}
