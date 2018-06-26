@extends('layouts.app')

@section('title', 'proposals')

@section('content')
    <p>
        Let's build the constitution together! Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bonum liberi: misera orbitas. Cur id non ita fit? Sed ad haec, nisi molestum est, habeo quae velim. Bonum negas esse divitias, praeposìtum esse dicis? Saepe ab Aristotele, a Theophrasto mirabiliter est laudata per se ipsa rerum scientia.
    </p>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bonum liberi: misera orbitas. Cur id non ita fit? Sed ad haec, nisi molestum est, habeo quae velim. Bonum negas esse divitias, praeposìtum esse dicis? Saepe ab Aristotele, a Theophrasto mirabiliter est laudata per se ipsa rerum scientia.
    </p>
    <div class="row">
        <div class="col-3">
            <img src="http://via.placeholder.com/350x150?text=placeholder" class="figure-img img-fluid rounded" />
            <p>
                Do you have a strong opinion for multiple changes? Create your own constitution proposal.
            </p>
            <p>
                Sin eam, quam Hieronymus, ne fecisset idem, ut voluptatem illam Aristippi in prima commendatione poneret. Philosophi autem in suis lectulis plerumque moriuntur
            </p>
            <center>
                <a class="btn btn-primary" href="{{ route('proposals.create') }}">Create your own proposal</a>
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