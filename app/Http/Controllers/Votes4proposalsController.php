<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Article;
use App\Proposal;
use App\User;
use App\Votes4proposal;
use EOSPHP\EOSClient;


class Votes4proposalsController extends Controller
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

        $transaction = $eos->history()->getTransaction($data['transaction']);
        $transaction = $transaction->trx->trx->actions[0]->data;
        $meta        = json_decode($transaction->json_meta);
        $user        = User::factory($eos, $transaction->voter);

        $proposalId = $meta->proposalId;


        $deleted = Votes4proposal::where('user_id', $user->id)->where('proposal_id', $proposalId)->delete();
        if (!$deleted) {
          $element = new Votes4proposal();
          $element->transaction = $data['transaction'];
          $element->user_id     = $user->id;
          $element->proposal_id = $proposalId;
          $element->value       = $transaction->vote_value;
          $element->save();
        }
        $comment = Proposal::findOrfail($proposalId);
        $sum     = $comment->votes($transaction->vote_value);
        return number_format($sum, 0);
    }
}