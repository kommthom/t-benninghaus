<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap
            ->add(Url::create('/login')
            ->setPriority(0.6)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap
            ->add(Url::create('/robots.txt')
            ->setPriority(0.5)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Blog posts
        Post::all()
            ->reject(function (Post $post) {
                return $post->is_private === true;
            })
            ->lazy()
            ->each(function ($post) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('posts.show', ['id' => $post->id, 'slug' => $post->slug,]))
                        ->setLastModificationDate($post->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8));
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml');
    }
}
