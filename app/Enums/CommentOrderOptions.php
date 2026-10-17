<?php

declare(strict_types=1);

namespace App\Enums;

use App\Interfaces\OptionsInterface;

enum CommentOrderOptions: string implements OptionsInterface
{
    case POPULAR = 'popular';

    case LATEST = 'latest';

    case OLDEST = 'oldest';

    public function label(): string
    {
        return match ($this) {
            self::POPULAR => __('Popular Comments'),
            self::LATEST => __('Newest to Oldest'),
            self::OLDEST => __('Oldest to Newest'),
        };
    }
}
