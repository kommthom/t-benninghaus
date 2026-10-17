declare global {
    interface Window {
        setupScrollToTopButton: (button: HTMLButtonElement) => void;
    }
}

// Scroll to the top of the page
function scrollToTop(): void {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.setupScrollToTopButton = function (
    scrollToTopButton: HTMLButtonElement,
): void {
    scrollToTopButton.addEventListener('click', scrollToTop);

    const header = document.getElementById('header');
    const footer = document.getElementById('footer');

    // Adjust the button style based on whether the header/footer appears on the screen.
    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.target === header) {
                    if (entry.isIntersecting) {
                        // header On the screen
                        scrollToTopButton.classList.remove('xl:flex');
                    } else {
                        // header Not on the screen
                        scrollToTopButton.classList.add('xl:flex');
                    }
                } else if (entry.target === footer) {
                    if (entry.isIntersecting) {
                        // footer On the screen
                        scrollToTopButton.classList.remove('fixed', 'bottom-7');
                        scrollToTopButton.classList.add('absolute', 'bottom-1');
                    } else {
                        // footer Not on the screen
                        scrollToTopButton.classList.add('fixed', 'bottom-7');
                        scrollToTopButton.classList.remove('absolute', 'bottom-1');
                    }
                }
            }
        },
        { threshold: [0] },
    );

    if (header) {
        observer.observe(header);
    }
    if (footer) {
        observer.observe(footer);
    }

    document.addEventListener(
        'livewire:navigating',
        () => {
            observer.disconnect();
            scrollToTopButton.removeEventListener('click', scrollToTop);
        },
        { once: true },
    );
};
