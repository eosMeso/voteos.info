<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Article;
use App\Comment;
use App\User;
use App\Votes4comment;
use EOSPHP\EOSClient;


class Votes4commentsController extends Controller
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
        $eos  = getEnv('EOS_PROT').'://'.getEnv('EOS_NODE').':'.getEnv('EOS_PORT');
        $eos  = new EOSClient($eos);
        $data = $request->all();
        try {
            $transaction = $eos->history()->getTransaction($data['transaction']);
            $transaction = $transaction->traces[0]->act->data;
            $account     = $eos->chain()->getAccount($transaction->voter);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $user = User::where('name', $account->account_name)->first();
        if (!$user) {
            $user = new User();
            $user->name = $account->account_name;
        }
        $user->stake = ($account->net_weight + $account->cpu_weight) / (10 * 1000);
        $user->save();

        $commentId = parse_url($transaction->proposition);
        $commentId = $commentId['fragment'];
        $commentId = explode('-', $commentId);
        $commentId = $commentId[1];


        $deleted = Votes4comment::where('user_id', $user->id)->where('comment_id', $commentId)->delete();
        if (!$deleted) {

          $element = new Votes4comment();
          $element->transaction = $data['transaction'];
          $element->user_id     = $user->id;
          $element->comment_id  = $commentId;
          $element->value       = $transaction->vote_value;
          $element->save();


        }
        $comment = Comment::findOrfail($commentId);
        $sum     = $comment->votes($transaction->vote_value);
        return $sum;
    }
}