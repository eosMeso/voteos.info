<?php

namespace App\Http\Controllers;

use App\Article;
use App\Proposal;
use Illuminate\Http\Request;

class ProposalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elements = Proposal::all()->sortByDesc('updated_at');
        return view('proposals.index', compact('elements'));
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
        $data     = $request->all();
        $proposal = new Proposal();

        $proposal->user_id     = 1;
        $proposal->name        = $data['data']['Proposal']['name'];
        $proposal->description = $data['data']['Proposal']['description'];

        $proposal->save();

        foreach ($data['data']['Article']['name'] as $i => $name) {
            $article = new Article();
            $article->name = $name;
            $article->description = $data['data']['Article']['description'][$i];
            $proposal->articles()->save($article);
        }
        return redirect()->route('proposals.show',$proposal->id);
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
