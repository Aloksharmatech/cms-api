<?php
    
namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Bus\Batchable;


class GenerateSummaryJob implements ShouldQueue
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

        if (!$article || (!$this->force && $article->summary)) {
            Log::info('Skipping summary generation', ['articleId' => $this->articleId]);
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
                    'content' => "Summarize the following article in 3-4 lines:\n\n" . $article->content,
                ],
            ],
            'max_tokens' => 100,
        ]);

        if (!$response->successful()) {
            Log::warning('OpenRouter API call failed for summary generation', [
                'response' => $response->json(),
            ]);
            return;
        }

        $summaryText = data_get($response->json(), 'choices.0.message.content', null);

        $article->update([
            'summary' => $summaryText,
        ]);

        Log::info('Summary generated and updated', ['summary' => $summaryText]);
    }
}
