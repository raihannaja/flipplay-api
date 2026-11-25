<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Goblin', 'type' => 'Drama', 'genre' => 'Fantasy', 'year' => 2016, 'rating' => 8.6, 'episodes' => 16],
            ['title' => 'Reply 1988', 'type' => 'Drama', 'genre' => 'Drama', 'year' => 2015, 'rating' => 9.0, 'episodes' => 20],
            ['title' => 'Hospital Playlist', 'type' => 'Drama', 'genre' => 'Medical', 'year' => 2020, 'rating' => 8.8, 'episodes' => 12],
            ['title' => 'Parasite', 'type' => 'Film', 'genre' => 'Thriller', 'year' => 2019, 'rating' => 8.5, 'duration' => 132],
            ['title' => 'Your Name', 'type' => 'Film', 'genre' => 'Animation', 'year' => 2016, 'rating' => 8.4, 'duration' => 107],
        ];

        foreach ($items as $item) {
            Media::create($item);
        }
    }
}
