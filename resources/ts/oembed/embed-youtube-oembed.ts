declare global {
    interface Window {
        processYoutubeOembeds: Function;
    }
}

// Define a function to handle oembed conversion
async function convertOembedToIframe(oembedElement: HTMLElement) {
    const screenWidth: number = window.screen.width;

    let maxWidth: number = 640;
    let maxHeight: number = 360;

    if (screenWidth <= 425) {
        maxWidth = 320;
        maxHeight = 180;
    }

    const url = oembedElement.getAttribute('url');

    if (!url || !isYouTubeUrl(url)) {
        return;
    }

    let oembedApiUrl = `https://www.youtube.com/oembed?format=json&url=${encodeURIComponent(
        url,
    )}`;
    oembedApiUrl += `&maxwidth=${maxWidth}&maxheight=${maxHeight}`;

    let response = await fetch(oembedApiUrl);
    let data = await response.json();

    if (data.html) {
        oembedElement.insertAdjacentHTML('afterend', data.html);
        // Mark as processed, to avoid repeated processing in SPA applications
        oembedElement.classList.add('oembed-processed');
    }
}

// Define a function to check if it is a YouTube link
function isYouTubeUrl(url: string): boolean {
    return (
        /^https?:\/\/(www\.)?youtube\.com\/watch\?v=/.test(url) ||
        /^https?:\/\/youtu\.be\//.test(url)
    );
}

// Main processing function
window.processYoutubeOembeds = function () {
    const oembedElements: NodeListOf<HTMLElement> = document.querySelectorAll(
        'oembed:not(.oembed-processed)',
    );

    oembedElements.forEach((oembedElement) => {
        const figureElement = oembedElement.closest('figure.media');

        if (figureElement) {
            convertOembedToIframe(oembedElement).catch((error) => {
                console.error(error);
            });
        }
    });
};
