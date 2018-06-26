@extends('layouts.app')

@section('title', 'proposals')

@section('content')

    <p>
        Do you have a strong opinion for multiple changes? Create your own constitution proposal.
    </p>
    <p>
        Sin eam, quam Hieronymus, ne fecisset idem, ut voluptatem illam Aristippi in prima commendatione poneret. Philosophi autem in suis lectulis plerumque moriuntur
    </p>
    {!! Form::model($element, ['route' => 'proposals.store', $element->id]) !!}

        <div class="form-group row">
            <label class="col-2 col-form-label" for="data[Proposal][name]">Name</label>
            <div class="col-10">
                <input class="form-control" name="data[Proposal][name]" value="{{ $element->name }}" required="required" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label" for="data[Proposal][desciption]">Description</label>
            <div class="col-10">
                <textarea class="form-control" name="data[Proposal][description]"  required="required" style="height: 14em;">{{ $element->description }}</textarea>
            </div>
        </div>

        <h2>Articles</h2>
        <div class="card-columns">
            @foreach ($element->articles as $article)
                <div class="card">
                    <input class="form-control card-header" name="data[Article][name][]" value="{{ $article->name }}" />
                    <div class="card-body">
                        <textarea class="form-control card-text" name="data[Article][description][]" style="height: 14em;">{{ $article->description }}</textarea>
                    </div>

                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-sm btn-success"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
        <center>
            <button type="submit" class="btn btn-primary">save your proposal</button>
        </center>



    {!! Form::close() !!}
@endsection