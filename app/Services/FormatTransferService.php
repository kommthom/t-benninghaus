<?php

declare(strict_types=1);

namespace App\Services;

class FormatTransferService
{
    /**
     * Convert the JSON data of the tag to an array
     * ex. [{"id":1,"name":"PHP"},{"id":2,"name":"Laravel"}]
     */
    public function tagsJsonToTagIdsArray(?string $tagsJson = null): array
    {
        // No tags
        if (is_null($tagsJson)) {
            return [];
        }

        $tags = json_decode($tagsJson);

        // Generate an array composed of tag IDs
        return collect($tags)
            ->map(fn ($tag) => $tag->id)
            ->all();
    }
}
