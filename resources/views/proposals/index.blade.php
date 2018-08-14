<?php use Michelf\Markdown;?>
@extends('layouts.app')

@section('title', 'proposals')

@section('content')
    <script src="{{ asset('js/proposal.js') }}"></script>

    <p>The purpose of this site is to help the EOS community develop a way to discover the wishes of the tokenholders; where there is agreement and where there is controversy, based on the amount of support a given proposal attains.  </p>
    <p>Here, you can let your voice be heard and your tokens be counted on any issue.  You can show support for any proposal or comment, or start your own initiative to see if it is widely supported!</p>
    <p>Join us in our effort to help the EOS better communicate, with on-chain discussions, which can lead to referenda, worker proposals, changes to the constitution, system contracts or simply gauging the support for your ideas.  Use your stake to make your opinion count!</p>

    <h2>How to create your own proposal</h2>

    <ul>
        <li>First make sure you have <a href="https://get-scatter.com/" target="_blank">Scatter</a> installed and unlocked.</li>
        <li>Click on
            <a href="{{ url('/proposals/create') }}"><i class="fa fa-plus"></i> create proposal</a>
            on the menu at the top of the page. </li>
        <li>Next, on the create page you will be shown a form where you can input the Title of your proposal, select the Type or category, and write de description of your idea or proposal. </li>
        <li>Finally at the bottom of the page, click on Save Your Proposal.</li>
    </ul>

    @if (!count($elements))
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">There was no proposal found yet.</h4>
            <p>
                There was no proposal found for this category. This category was created
                with the idea that in the future, someone will add a proposal to discuss
                and share. We encourage peopole to try creating a new proposal. It's free!
            </p>

            <a href="/proposals/create" class="btn btn-primary">Do you want to create a new one?</a>
        </div>
    @else

        <div class="row mt-4">
            <div class="col-md-8">
                @foreach ($elements as $element)
                <div>
                    <div class="row">
                        <div class="col-1 text-center">
                            <i class="far fa-check-square" style="font-size: 3rem;  transform: rotate(-20deg);"></i>
                        </div>
                        <div class="col-11">
                            <a href="{{ route('proposals.show', $element->id) }}">
                                <h3>{{ $element->name }}</h3>
                            </a>

                            {!! Markdown::defaultTransform($element->description) !!}

                            <p>
                                <i class="far fa-user"></i> <a target="_blank" href="https://eosflare.io/account/{{ $element->user->name}}">{{ $element->user->name}}</a>
                                <i class="fas fa-weight-hanging"></i> {{ number_format($element->user->stake, 0)}}
                                <i class="far fa-calendar-alt"></i> {{ $element->created_at->format('m/d/Y')}}
                                <a href="https://eosflare.io/tx/{{$element->transaction}}" target="_blank" title="analyze the transaction stored in the chain"><i class="fas fa-link"></i>  {{ substr($element->transaction, 0, 4)}}…</a>
                                <a href="#" class="eos vote4proposal text-success" data-proposal="{{ json_encode($element) }}" data-vote="1">
                                    <i class="far fa-thumbs-up"></i>
                                    + <span class="sum">{{ number_format($element->votes(1), 0) }}</span>
                                </a>
                                <a href="#" class="eos vote4proposal text-danger"  data-proposal="{{ json_encode($element) }}" data-vote="0">
                                    <i class="far fa-thumbs-down"></i>
                                    - <span class="sum">{{ number_format($element->votes(0), 0) }}</span>
                                </a>
                            </p>
                            <p>
                                <a href="{{ route('proposals.show', $element->id) }}">
                                    Read the proposal: “{{ $element->name }}”
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <hr />
                @endforeach
            </div>
            <div class="col-md-4">
                <h4>Latest comments</h4>

                @foreach ($comments as $node)
                <div class="row">
                    <div class="col-1 text-center">
                        <i class="far fa-comments"></i>
                    </div>
                    <div class="col-11">
                        <blockquote class="blockquote">
                            <p>{!! $node->description !!}</p>
                            <footer class="blockquote-footer">
                                <i class="far fa-user"></i> <a target="_blank" href="https://eosflare.io/account/{{ $node->user->name}}">{{ $node->user->name}}</a>
                                <i class="fas fa-weight-hanging"></i> {{ number_format($node->user->stake, 0)}}
                                <a href="https://eosflare.io/tx/{{$node->transaction}}" target="_blank" title="analyze the transaction stored in the chain"><i class="fas fa-link"></i>  {{ substr($node->transaction, 0, 4)}}…</a>
                            </footer>
                        </blockquote>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection