/**
 * @description This is a typescript clone of sharer.js(https://github.com/ellisonleao/sharer.js)
 *
 */
declare global {
    interface Window {
        setupSharer: Function;
    }
}

type facebookParams = {
    shareUrl: string;
    params: {
        quote: string;
        u: string;
        hashtag: string;
    };
    width: number;
    height: number;
};

type xParams = {
    shareUrl: string;
    params: {
        hashtags: string;
        text: string;
        url: string;
        via: string;
    };
    width: number;
    height: number;
};

class Sharer {
    private elem: HTMLButtonElement;

    constructor(elem: HTMLButtonElement) {
        this.elem = elem;
    }

    // Retrieve values of all attributes prefixed with data- in the label
    private getValue(attr: String): string {
        let val: string | null = this.elem.getAttribute('data-' + attr);
        // handing facebook hashtag attribute
        if (val && attr === 'hashtag') {
            if (!val.startsWith('#')) {
                val = '#' + val;
            }
        }

        return val === null ? '' : val;
    }

    // share and urlSharer are used to set sharing parameters and open the sharing popup window
    public share(): void | boolean {
        // Get the value of the data-sharer attribute of the label
        let sharer: string = this.getValue('sharer').toLowerCase();
        let sharers = {
            facebook: {
                shareUrl: 'https://www.facebook.com/sharer/sharer.php',
                params: {
                    u: this.getValue('url'),
                    hashtag: this.getValue('hashtag'),
                    quote: this.getValue('quote'),
                },
                width: 0,
                height: 0,
            },
            x: {
                shareUrl: 'https://x.com/intent/tweet/',
                params: {
                    text: this.getValue('title'),
                    url: this.getValue('url'),
                    hashtags: this.getValue('hashtags'),
                    via: this.getValue('via'),
                },
                width: 0,
                height: 0,
            },
        };

        let s = sharers[sharer as keyof typeof sharers];

        // Adjust the size of popups
        if (s) {
            s.width = Number(this.getValue('width'));
            s.height = Number(this.getValue('height'));
        }

        return s !== undefined ? this.urlSharer(s) : false;
    }

    private urlSharer(sharer: facebookParams | xParams): void {
        let params = sharer.params || {};
        let keys = Object.keys(params);
        let str = keys.length > 0 ? '?' : '';

        for (let i = 0, l = keys.length; i < l; i++) {
            if (str !== '?') {
                str += '&';
            }
            if (params[keys[i] as keyof typeof params]) {
                str +=
                    keys[i] +
                    '=' +
                    encodeURIComponent(params[keys[i] as keyof typeof params]);
            }
        }

        sharer.shareUrl += str;

        let isLink = this.getValue('link') === 'true';
        let isBlank = this.getValue('blank') === 'true';

        if (isLink) {
            if (isBlank) {
                window.open(sharer.shareUrl, '_blank');
            } else {
                window.location.href = sharer.shareUrl;
            }
        } else {
            console.log(sharer.shareUrl);
            // If data-link is not set, set the initial value for the popup window
            let popWidth = sharer.width || 600;
            let popHeight = sharer.height || 480;
            let left = window.innerWidth / 2 - popWidth / 2 + window.screenX;
            let top = window.innerHeight / 2 - popHeight / 2 + window.screenY;
            let popParams: string =
                `scrollbars=no, width=${popWidth}` +
                `, height=${popHeight}` +
                `, top=${top}` +
                `, left=${left}`;
            let newWindow = window.open(sharer.shareUrl, '', popParams);

            newWindow?.focus();
        }
    }
}

// Get all buttons that have the data-sharer attribute
function init(): void {
    let elems: NodeListOf<HTMLButtonElement> =
        document.querySelectorAll('[data-sharer]');

    let clipboardElems: NodeListOf<HTMLButtonElement> =
        document.querySelectorAll('[data-clipboard]');

    for (const elem of elems) {
        elem.addEventListener('click', addShareFeature);
    }

    for (const clipboardElem of clipboardElems) {
        clipboardElem.addEventListener('click', copyToClipboard);
    }
}

// Add sharing functionality
function addShareFeature(event: Event): void {
    let target = event.currentTarget;

    if (target instanceof HTMLButtonElement) {
        let sharer = new Sharer(target);

        sharer.share();
    }
}

function copyToClipboard(event: Event): void {
    let target = event.currentTarget;

    if (target instanceof HTMLButtonElement) {
        let text = target.getAttribute('data-clipboard');

        if (text) {
            navigator.clipboard.writeText(text).then(
                () => console.log('copy success'),
                () => console.log('copy fail'),
            );
        }
    }
}

window.setupSharer = function () {
    // Add sharing function settings in the DOMContentLoaded event
    if (
        document.readyState === 'complete' ||
        document.readyState !== 'loading'
    ) {
        init();
    } else {
        document.addEventListener('DOMContentLoaded', init);
    }
};
