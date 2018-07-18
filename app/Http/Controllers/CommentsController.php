<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use EOSPHP\EOSClient;

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
        $eos  = getEnv('EOS_PROT').'://'.getEnv('EOS_NODE').':'.getEnv('EOS_PORT');
        $eos  = new EOSClient($eos);
        $data = $request->all();

        $transaction = $eos->history()->getTransaction($data['data']['Comment']['transaction']);
        $transaction = $transaction->traces[0]->act->data;

        $user    = User::factory($eos, $transaction->account);
        $element = new Comment();
        $element->user_id     = $user->id;
        $element->article_id  = $data['data']['Comment']['article_id'];
        $element->description = $transaction->content;
        $element->transaction = $data['data']['Comment']['transaction'];

        if (!empty($data['data']['Comment']['parent_id'])) {
            $element->parent_id = $data['data']['Comment']['parent_id'];
        }
        $element->save();
        $article = Article::findOrFail($element->article_id);
        return redirect()->route('proposals.articles.show', [$article->proposal_id, $article->id]);
    }
}
