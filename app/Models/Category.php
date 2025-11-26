<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $link_with_name Kategorielink mit Name-Slug, gesetzt durch linkWithName()
 */
class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'icon', 'description',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // FŸgt dem Link den Kategorienamen hinzu
    public function linkWithName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => route('categories.show', [
                'id' => $this->id,
                'name' => $this->name,
            ])
        );
    }
}
