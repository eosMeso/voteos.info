@extends('layouts.app')

@section('title', 'proposals')

@section('content')
    <script src="https://cdn.rawgit.com/showdownjs/showdown/1.8.6/dist/showdown.min.js"></script>
    <script src="{{ asset('js/proposal.js') }}"></script>
    <script>
        $(function() {
            var text      = document.getElementById('content'),
                target    = document.getElementById('markdown'),
                converter = new showdown.Converter(),
                html      = converter.makeHtml(text.value);
            target.innerHTML = html;
            $(document).on('keydown', text,function() {
                target.innerHTML = converter.makeHtml(text.value);
                $('#markdown h1').addClass('h4');
                $('#markdown h2').addClass('h5');
                $('#markdown h3').addClass('h6');
                $('#markdown h4').addClass('h7');
            });
            $(text).keydown();
        });
    </script>
    {!! Form::model($element, ['route' => 'proposals.store', $element->id]) !!}
        {!! Form::hidden('data[Proposal][transaction]') !!}

        <div class="form-group row">
            <label class="col-3 col-form-label" for="data[Proposal][title]">Title:</label>
            <div class="col-9">
                <input class="form-control" name="data[Proposal][title]" value="{{ $element->name }}" required="required" placeholder="The title of your proposal." />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label" for="data[Proposal][type]">Type:</label>
            <div class="col-9">
                {{ Form::select('data[Proposal][type]',
                    ['constitution' => 'constitution' , 'working_proposal' => 'working proposal', 'general' => 'general'],
                    null,
                    ['class' => 'form-control']
                )}}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label" for="data[Proposal][desciption]">Description:</label>
            <div class="col-9">
                <textarea class="form-control" name="data[Proposal][description]" required="required" style="height: 14em;" placeholder="A way for people to understand what your proposal is about.">{{ $element->description }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <label class="form-label" for="data[Proposal][desciption]">Content:</label>
                <p class="small">You can use <a target="_blank" href="https://help.github.com/articles/basic-writing-and-formatting-syntax/">markdown</a> to edit the content</p>
            </div>
            <div class="col-6">
                <label class="form-label" for="data[Proposal][desciption]">Preview:</label>
            </div>
        </div>


        <div class="form-group row">
            <div class="col-6">
                <textarea id="content" class="form-control" name="data[Proposal][content]" required="required"
                    style="height: 100%; min-height: 14em;" placeholder="Here you can write in full detail your proposal.

# Split your proposal in articles.

If you split your proposals in articles, people can post comments to a specific artice, to keep a better flow of the discussion.
                    "></textarea>
            </div>
            <div class="col-6">
                <div id="markdown"></div>
            </div>
        </div>

        <center>
            <button type="submit" class="btn btn-primary">save your proposal</button>
        </center>

    {!! Form::close() !!}
@endsection