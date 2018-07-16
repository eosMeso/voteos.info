@extends('layouts.app')

@section('title', 'proposals')

@section('content')
    {!! Form::model($element, ['route' => 'proposals.store', $element->id]) !!}

        <div class="form-group row">
            <label class="col-2 col-form-label" for="data[Proposal][name]">Name</label>
            <div class="col-10">
                <input class="form-control" name="data[Proposal][name]" value="{{ $element->name }}" required="required" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label" for="data[Proposal][name]">Type</label>
            <div class="col-10">
                {{ Form::select('data[Proposal][type]',
                    ['constitution', 'working proposal', 'general'],
                    null,
                    ['class' => 'form-control']
                )}}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label" for="data[Proposal][desciption]">Description</label>
            <div class="col-10">
                <textarea class="form-control" name="data[Proposal][description]"  required="required" style="height: 14em;">{{ $element->description }}</textarea>
            </div>
        </div>


        <div class="form-group row">
            <label class="col-2 col-form-label" for="data[Proposal][desciption]">Description</label>
            <div class="col-10">
                <textarea id="content" class="form-control" name="data[Proposal][content]" required="required" style="height: 14em;"></textarea>
            </div>
        </div>

        <center>
            <button type="submit" class="btn btn-primary">save your proposal</button>
        </center>



    {!! Form::close() !!}
@endsection