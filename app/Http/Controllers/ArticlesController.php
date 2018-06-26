<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Proposal;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal, Article $article)
    {
        $forum = [];
        foreach ($article->comments as $comment) {
            if (!$comment->parent_id) {
                $forum[] = Comment::descendantsAndSelf($comment->id)->toTree();
            }
        }

        //dd($forum);

        $prev = Article::where('proposal_id', $proposal->id)->where('id', '<', $article->id)->max('id');
        $next = Article::where('proposal_id', $proposal->id)->where('id', '>', $article->id)->min('id');
        return view('articles.show', compact('article', 'forum','proposal', 'prev', 'next'));
    }

}
