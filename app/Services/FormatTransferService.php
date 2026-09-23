<?php

declare(strict_types=1);

namespace App\Services;

class FormatTransferService
{
    /**
     * Convert the JSON data of the tag to an array
     * ex. [{"id":1,"name":"PHP"},{"id":2,"name":"Laravel"}]
     *
     * @return array<int, int|string>
     */
    public function tagsJsonToTagIdsArray(?string $tagsJson = null): array
    {
        // No tags set
        if (is_null($tagsJson)) {
            return [];
        }

        /** @var array<int, object{id: int|string}>|null $tags */
        $tags = json_decode($tagsJson);

        if (! is_array($tags)) {
            return [];
        }

        // Generate an array of tag IDs.
        return collect($tags)
            ->map(fn (object $tag): int|string => $tag->id)
            ->all();
    }
}