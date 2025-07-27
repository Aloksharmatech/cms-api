<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
  
    public function viewAny(User $user): bool
    {
        return false;
    }

   
    public function view(User $user, Article $article): bool
    {
        return false;
    }


    public function create(User $user): bool
    {
        return false;
    }

    
    public function update(User $user, Article $article): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $article->user_id;
    }

    public function delete(User $user, Article $article): bool
    {
       
        if ($user->hasRole('admin')) {
            return true;
        }

     
        return $user->id === $article->user_id;
    }

    
    public function restore(User $user, Article $article): bool
    {
        return false;
    }

    
    public function forceDelete(User $user, Article $article): bool
    {
        return false;
    }
}
