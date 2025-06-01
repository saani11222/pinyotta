<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Show;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUserCsvRow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $userData = $this->data;

        $user = User::where('email', $userData['email'])->first();

        if (!$user) {
            $email = $userData['email'];
            $name = strstr($email, '@', true);

            $user = new User;
            $user->email = $email;
            $user->name = $name;
            $user->type = '0';
            $user->save();
        

            $imdbIds = explode(',', $userData['imdb_ids']);

            foreach ($imdbIds as $imdbId) {
                $imdbId = trim($imdbId);

                $response = Http::get("https://api.tvmaze.com/lookup/shows", [
                    'imdb' => $imdbId
                ]);

                if ($response->ok()) {
                    $showData = $response->json();

                    $show = new Show;
                    $show->show_id = $showData['id'];
                    $show->user_id = $user->id;
                    $show->name = $showData['name'];

                    $genres = isset($showData['genres']) && is_array($showData['genres']) ? implode(', ', $showData['genres']) : null;
                    $year = isset($showData['premiered']) ? date('Y', strtotime($showData['premiered'])) : null;

                    if ($year && $genres) {
                        $show->genres = $year . ', ' . $genres;
                    } elseif ($year) {
                        $show->genres = $year;
                    } elseif ($genres) {
                        $show->genres = $genres;
                    } else {
                        $show->genres = null;
                    }

                    $image = $image = $showData['image']['medium'] ?? 'https://pinyotta.com/assets/img/No Image.png';
                    $show->image = $image;

                    $show->type = 'shows-love';
                    $show->save();
                }
            }

        }
    }
}
