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
        $eos = getEnv('EOS_NODE');
        $eos = "http://$eos:8888";
        $eos = new EOSClient($eos);

        $data = $request->all();

        $account = $data['data']['User']['name'];
        try {
            $account = $eos->chain()->getAccount($account);
            $account->stake = ($account->net_weight + $account->cpu_weight) / (10 * 1000);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $user = User::where('name', $account->account_name)->first();
        if (!$user) {
            $user = new User();
            $user->name = $account->account_name;
        }
        $user->stake = $account->stake;
        $user->save();

        $element = new Comment();
        $element->user_id     = $user->id;
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
