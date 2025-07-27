<?php

namespace App\Http\Controllers\API;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Jobs\GenerateSlugJob;
use App\Jobs\GenerateSummaryJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ArticleController extends Controller
{
    use AuthorizesRequests;



    public function index(Request $request)
    {
        $query = Article::query()->with('categories', 'author');

   
        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

      
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

      
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('published_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        return $query->paginate(10);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'status' => 'required|in:Draft,Published,Archived',
            'published_at' => 'nullable|date'
        ]);

        $article = new Article();
        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']); 
        $article->content = $validated['content'];
        $article->status = $validated['status'];
        $article->published_at = $validated['published_at'];
        $article->user_id = Auth::id();


        $response = Http::withToken(env('OPENROUTER_API_KEY'))->post('https://openrouter.ai/api/v1/chat/completions', [
                       'model' => 'mistralai/mistral-7b-instruct',
                       'messages' => [
                           [
                               'role' => 'user',
                               'content' => "Summarize the following article content in 2-3 concise sentences:\n\n" . $validated['content'],
                           ],
                       ],
                       'max_tokens' => 100,
                   ]);


        $summary = $response['choices'][0]['message']['content'] ?? null;
        $article->summary = trim($summary ?? '');

        $article->save();

        $article->categories()->sync($validated['category_ids']);

   
        dispatch(new GenerateSlugJob($article->id));

        return response()->json($article->load('categories'), 201);
    }

    public function show(Article $article)
    {
        return $article->load('categories', 'author');
    }

    public function update(Request $request, Article $article)
    {
        try {
            $this->authorize('update', $article);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'category_ids' => 'sometimes|array',
                'category_ids.*' => 'exists:categories,id',
                'status' => 'sometimes|in:Draft,Published,Archived',
                'published_at' => 'nullable|date',
            ]);

            $article->fill($validated)->save();

            if (isset($validated['category_ids'])) {
                $article->categories()->sync($validated['category_ids']);
            }


            if (isset($validated['title'])) {

                $slugJob = new GenerateSlugJob($article->id, true);
                $slugJob->handle();
            }

            if (isset($validated['content'])) {
                $summaryJob = new GenerateSummaryJob($article->id, true);
                $summaryJob->handle();
            }

            return response()->json([
                'message' => 'Article updated. AI jobs completed synchronously.',
                'article' => $article->fresh()->load('categories'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error in update(): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }



    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        return response()->json(['message' => 'Article deleted']);
    }
}
