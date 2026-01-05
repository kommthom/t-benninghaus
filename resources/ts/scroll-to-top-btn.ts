declare global {
    interface Window {
        setupScrollToTopButton: Function;
    }
}

// Scroll to the top of the webpage
function scrollToTop(): void {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.setupScrollToTopButton = function (
    scrollToTopButton: HTMLButtonElement,
): void {
    scrollToTopButton.addEventListener('click', scrollToTop);

    let header = <HTMLElement>document.getElementById('header');
    let footer = <HTMLElement>document.getElementById('footer');

    // Adjust the button's style based on whether the header appears on the screen
    let headerObserver = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting) {
                // Header is on the screen
                scrollToTopButton.classList.remove('xl:flex');
            } else {
                // Header is not on the screen
                scrollToTopButton.classList.add('xl:flex');
            }
        },
        { threshold: [0] },
    );

    // Adjust the button's style based on whether the footer appears on the screen
    let footerObserver = new IntersectionObserver(
        function (entries) {
            if (entries[0].isIntersecting) {
                // Footer is on the screen
                scrollToTopButton.classList.remove('fixed', 'bottom-7');
                scrollToTopButton.classList.add('absolute', 'bottom-1');
            } else {
                // Footer is not on the screen
                scrollToTopButton.classList.add('fixed', 'bottom-7');
                scrollToTopButton.classList.remove('absolute', 'bottom-1');
            }
        },
        { threshold: [0] },
    );

    headerObserver.observe(header);
    footerObserver.observe(footer);
};
