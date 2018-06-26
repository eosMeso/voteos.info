<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $element = new Comment();
        $element->user_id     = 1;
        $element->article_id  = $data['data']['Comment']['article_id'];
        $element->description = $data['data']['Comment']['description'];

        if (!empty($data['data']['Comment']['parent_id'])) {
            $element->parent_id = $data['data']['Comment']['parent_id'];
        }
        $element->save();
        $article = Article::findOrFail($element->article_id);
        return redirect()->route('proposals.articles.show', [$article->proposal_id, $article->id]);
    }
}
