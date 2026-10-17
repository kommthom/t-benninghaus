<?php

declare(strict_types=1);

namespace App\Enums;

use App\Interfaces\OptionsInterface;

enum UserInfoOptions: string implements OptionsInterface
{
    case INFORMATION = 'information';
    case POSTS = 'posts';
    case COMMENTS = 'comments';

    public function label(): string
    {
        return match ($this) {
            self::INFORMATION => __('Personal Information'),
            self::POSTS => __('Published Articles'),
            self::COMMENTS => __('Comment History'),
        };
    }

    public function iconComponentName(): string
    {
        return match ($this) {
            self::INFORMATION => 'icons.info-circle',
            self::POSTS => 'icons.file-earmark-richtext',
            self::COMMENTS => 'icons.chat-square-text',
        };
    }

    public function livewireComponentName(): string
    {
        return match ($this) {
            self::INFORMATION => 'users.info-cards',
            self::POSTS => 'users.posts',
            self::COMMENTS => 'users.comments',
        };
    }
}
