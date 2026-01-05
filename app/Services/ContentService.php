<?php

declare(strict_types=1);

namespace App\Services;

use Dom\HTMLDocument;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class ContentService
{
    /**
     * Generate slug titles for SEO optimization
     *
     * @param  string  $title Title
     */
    public static function getSlug(string $title): string
    {
        // Remove special characters and leave only Chinese and English
        $title = preg_replace('/[^A-Za-z0-9 \p{Han}]+/u', '', $title);
         // Replace spaces with '-'
        $title = preg_replace('/\s+/u', '-', $title);
        // Change all English to lowercase
        $title = strtolower($title);

        // Add a '-post' after the slug to avoid conflicts with the route of the editing page
        return $title.'-post';
    }

    /**
     * Filter the content of the article in html format to avoid XSS attacks
     */
    public static function getPurifiedBody(string $html): string
    {
        $htmlSanitizer = new HtmlSanitizer(
            new HtmlSanitizerConfig()
                ->allowSafeElements()
                ->allowAttribute('data-language', 'pre')
                ->allowAttribute('class', ['span', 'code', 'figure'])
                ->allowAttribute('style', ['p', 'figure'])
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
                ->forceAttribute('a', 'target', '_blank')
                ->allowElement('oembed', ['url', 'class'])
                ->withMaxInputLength(-1)
        );

        return $htmlSanitizer->sanitize($html);
    }

    /**
     * Generate excerpts of the article content
     */
    public static function getExcerpt(string $body, int $length = 200): string
    {
        return (string) str(strip_tags($body))->limit($length);
    }

    /**
     * Get the link to the image in the article
     */
    public static function getImagesInContent(string $body): array
    {
        $dom = HTMLDocument::createFromString($body, LIBXML_NOERROR);

        $imageList = [];

        foreach ($dom->getElementsByTagName('img') as $img) {
            $pattern = '/\d{4}_\d{2}_\d{2}_\d{2}_\d{2}_\d{2}_[a-zA-Z0-9]+\.(jpeg|png|jpg|gif|svg)/u';

            $imageName = basename($img->getAttribute('src'));

            if (preg_match($pattern, $imageName)) {
                $imageList[] = $imageName;
            }
        }

        // format:
        // [
        //     '2023_01_01_10_18_21_63b0ed6d06d52.jpg',
        //     '2022_12_30_22_39_21_63aef81999216.jpg',
        //     ...
        // ]
        return $imageList;
    }

    /**
     * Get read time of the article, article may mix with Chinese characters and English words,
     * so I count Chinese characters and English words separately. The read time is calculated by
     * dividing the total number of words by different reading speeds for each language.
     */
    public static function getReadTime(string $body): int
    {
        if (in_array(trim($body), ['', '0'], true)) {
            return 1;
        }

        $body = html_entity_decode($body);
        $body = strip_tags($body);
        $body = trim($body);

        // English words including programming terms (variables, functions, CSS classes, etc.)
        $englishWordPattern = '/[\w\'-]+/';
        preg_match_all($englishWordPattern, $body, $matches);
        $englishWordCount = count($matches[0]);

        // Chinese characters pattern
        $chineseWordPattern = '/\p{Han}/u';
        preg_match_all($chineseWordPattern, $body, $matches);
        $chineseWordCount = count($matches[0]);

        // Different reading speeds: English 200 WPM, Chinese 300 characters per minute
        $englishReadTime = $englishWordCount / 200;
        $chineseReadTime = $chineseWordCount / 300;

        $totalMinutes = $englishReadTime + $chineseReadTime;

        // Always return at least 1 minute
        return max(1, intval(ceil($totalMinutes)));
    }
}
