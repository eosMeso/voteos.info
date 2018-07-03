<?php
/**
 * Draws the block per answer.
 */
$tree = function ($nodes, $level = 0) use (&$tree) {
    foreach ($nodes as $node) {
        $width = 12 - $level
        ?>

        <div class="row">
            <div class="col-{{ $width }} offset-md-{{ $level}}">
                <p>{{ $node->description }}</p>
                <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#reply" data-comment="{{ json_encode($node) }}">reply</a>
            </div>
        </div>

        <?
        $tree($node->children, $level +1);
    }
};
?>
@extends('layouts.app')

@section('title', 'proposals')

@section('content')
    <script src="{{ asset('js/forum.js') }}"></script>

    <script>
        $(function() {

            $('#reply').on('show.bs.modal', function (event) {
                var modal   = $(this);
                var button  = $(event.relatedTarget);
                var comment = button.data('comment');
                modal.find('.comment').html(comment.description);
                modal.find('.parent_id').val(comment.id);
            });
        });
    </script>


    <h2>{{ $proposal->name }}</h2>
    <p>{!! nl2br(e($proposal->description)) !!}</p>
    <div class="center text-center m-4">
            <a class="btn btn-secondary @if(!$prev) disabled @endif" href="{{ route('proposals.articles.show', [$proposal->id, $prev]) }}">&lt; prev article</a>
            <a class="btn btn-secondary @if(!$next) disabled @endif" href="{{ route('proposals.articles.show', [$proposal->id, $next]) }}">next article &gt;</a>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="jumbotron">
                <h3>{{ $article->name }}</h3>
                <p class="lead">{{ $article->description }}</p>
            </div>
        </div>
        <div class="col-6">
            @foreach ($forum as $node)
                {!! $tree($node) !!}
                <hr />
            @endforeach
            <p>Facile est hoc cernere in primis puerorum aetatulis. Graccho, eius fere, aequalí?</p>
            <p>Post as: <span class="accountName"></span> [+<span class="accountStaked">xx,xxx</span>]</p>
            {!! Form::open(['route' => 'comments.store']) !!}
                {!! Form::hidden('data[Comment][article_id]', $article->id) !!}
                <div class="form-group">
                    {!! Form::textarea('data[Comment][description]', null, ['class' => 'form-control']) !!}
                </div>
                <center>
                    <button type="submit" class="btn btn-primary">submit</button>
                </center>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="center text-center mt-4">
        <a class="btn btn-secondary @if(!$prev) disabled @endif" href="{{ route('proposals.articles.show', [$proposal->id, $prev]) }}">&lt; prev article</a>
        <a class="btn btn-secondary @if(!$next) disabled @endif" href="{{ route('proposals.articles.show', [$proposal->id, $next]) }}">next article &gt;</a>
    </div>

    <div class="modal fade" id="reply" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['route' => 'comments.store']) !!}
                    {!! Form::hidden('data[Comment][article_id]', $article->id) !!}
                    <input name="data[Comment][parent_id]" class="parent_id" type="hidden" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Reply:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <blockquote class="blockquote comment"></blockquote>
                        <p>Post as: <span class="accountName"></span> [+<span class="accountStaked">xx,xxx</span>]</p>
                        <div class="form-group">
                            {!! Form::textarea('data[Comment][description]', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
                        <button type="submit" class="btn btn-primary">reply</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection