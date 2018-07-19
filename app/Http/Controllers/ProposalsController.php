<?php

namespace App\Http\Controllers;

use App\Article;
use App\Proposal;
use App\Comment;
use App\User;
use App\Votes4proposal;
use Illuminate\Http\Request;
use EOSPHP\EOSClient;

class ProposalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @todo The comments should belong to the proposals listed.
     * @todo The comments should link to the proposal/article.
     */
    public function index()
    {
        if (empty($_GET['type'])) {
            $elements = Proposal::all();
        } else {
            $elements = Proposal::where('type', $_GET['type'])->get();
        }
        $elements->sortByDesc('updated_at');
        $comments = Comment::paginate(15)->sortByDesc('updated_at');
        return view('proposals.index', compact('elements', 'comments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $element = new Proposal();
        $element->populatePlaceholders();
        return view('proposals.form', compact('element'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @todo get user ID
     */
    public function store(Request $request)
    {

        $eos  = getEnv('EOS_PROT').'://'.getEnv('EOS_NODE').':'.getEnv('EOS_PORT');
        $eos  = new EOSClient($eos);
        $data = $request->all();

        $transaction = $eos->history()->getTransaction($data['data']['Proposal']['transaction']);
        $transaction = $transaction->trx->trx->actions[0]->data;
        $meta        = json_decode($transaction->json_meta);

        $user    = User::factory($eos, $transaction->account);
        $element = new Proposal();
        $element->transaction = $data['data']['Proposal']['transaction'];
        $element->name        = $transaction->title;
        $element->user_id     = $user->id;
        $element->type        = $meta->type;
        $element->description = $meta->description;
        $element->save();

        $articles = $element->createArticlesFromContent($transaction->content);
        $element->articles()->saveMany($articles);

        $vote = new Votes4proposal();
        $vote->transaction = $element->transaction;
        $vote->user_id     = $user->id;
        $vote->proposal_id = $element->id;
        $vote->value       = 1;
        $vote->save();
        return redirect()->route('home');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal)
    {
        return redirect()->route('proposals.articles.show', [$proposal->id, $proposal->articles[0]->id]);
    }
}
