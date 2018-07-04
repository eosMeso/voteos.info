@extends('layouts.app')

@section('title', 'proposals')

@section('content')
    <p>
        Let's build the constitution together!
    </p>
    <div class="row">
        <div class="col-3">
            <img src="{{ asset('img/homepage_sidebar.jpeg') }}" class="img-fluid rounded" />
            <p>
                Do you have a strong opinion for multiple changes? Create your own constitution proposal.
            </p>
            <center>
                <a class="btn btn-primary disabled" disabled="disabled" href="{{ route('proposals.create') }}">Create your own proposal</a>
            </center>
        </div>
        <div class="col-9">
            @foreach ($elements as $element)
            <div>
                <h3>{{ $element->name }}</h3>

                <div class="row">
                    <div class="col-2 text-center">
                        <a href="{{ route('proposals.show', $element->id) }}">
                            <i class="fas fa-book" style="font-size: 4rem;"></i>
                            <br />
                            review it
                        </a>
                    </div>
                    <div class="col-10">
                        <p>{!! nl2br(e($element->description)) !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection