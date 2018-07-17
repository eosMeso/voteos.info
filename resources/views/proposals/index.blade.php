@extends('layouts.app')

@section('title', 'proposals')

@section('content')

    <p>The purpose of this site is to help the EOS community develop a way to discover the wishes of the tokenholders; where there is agreement and where there is controversy, based on the amount of support a given proposal attains.  </p>
    <p>Here, you can let your voice be heard and your tokens be counted on any issue.  You can show support for any proposal or comment, or start your own initiative to see if it is widely supported!</p>
    <p>Join us in our effort to help the EOS better communicate, with on-chain discussions, which can lead to referenda, worker proposals, changes to the constitution, system contracts or simply gauging the support for your ideas.  Use your stake to make your opinion count!</p>

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

                        <p>{!! $element->description !!}</p>

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
                                <i class="far fa-user"></i> <a target="_blank" href="https://eostracker.io/accounts/{{ $node->user->name}}">{{ $node->user->name}}</a>
                                <i class="fas fa-weight-hanging"></i> {{ number_format($node->user->stake, 0)}}
                                <a href="https://eostracker.io/transactions/{{$node->transaction}}" target="_blank" title="analyze the transaction stored in the chain"><i class="fas fa-link"></i>  {{ substr($node->transaction, 0, 4)}}…</a>
                            </footer>
                        </blockquote>
                    </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection