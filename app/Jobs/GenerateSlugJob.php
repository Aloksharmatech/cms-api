<?php
namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Illuminate\Bus\Batchable;


class GenerateSlugJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    protected $articleId;
    protected $force;

    public function __construct(int $articleId, bool $force = false)
    {
        $this->articleId = $articleId;
        $this->force = $force;
    }

    public function handle(): void
    {
        $article = Article::find($this->articleId);

        if (!$article || (!$this->force && $article->slug)) {
            Log::info('Skipping slug generation', ['articleId' => $this->articleId]);
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'HTTP-Referer' => env('OPENROUTER_REFERER', 'http://localhost:8000'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-7b-instruct',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Generate a short, SEO-friendly slug for the following article title:\n\n" . $article->title,
                ],
            ],
            'max_tokens' => 20,
        ]);

        if (!$response->successful()) {
            Log::warning('OpenRouter API call failed for slug generation', [
                'response' => $response->json(),
            ]);
            return;
        }

        $slugText = data_get($response->json(), 'choices.0.message.content', $article->title);

        $article->update([
            'slug' => Str::slug($slugText),
        ]);

        Log::info('Slug generated and updated', ['slug' => $article->slug]);
    }
}
