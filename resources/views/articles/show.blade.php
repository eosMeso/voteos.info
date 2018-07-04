@extends('layouts.app')
<?php
/**
 * Draws the block per answer.
 */
function tree($nodes, $level = 0) {
    foreach ($nodes as $node) {
        $width = 12 - $level;
        ?>
            <div class="forumPost border-left">
                <blockquote class="blockquote">
                    <p>{{ $node->description }}</p>
                    <footer class="blockquote-footer">
                          <i class="far fa-user"></i> {{ $node->user->name}}
                        | <i class="far fa-calendar-alt"></i> {{ $node->created_at->format('m/d/Y')}}
                        + <i class="fas fa-weight-hanging"></i> {{ $node->user->stake}}
                        <a href="#" class="btn btn-sm btn-secondary float-right eos" data-toggle="modal" data-target="#reply" data-comment="{{ json_encode($node) }}">reply</a>
                    </footer>
                </blockquote>
                <hr />
                <?php tree($node->children, $level +1);?>
            </div>
        <?php
    }
}
?>
@section('title', 'proposals')
@section('content')
    <script src="{{ asset('js/forum.js') }}"></script>
    <script>
    </script>
    <style>
        .forumPost {padding: 0.5em 0 0 0.5em;}
        .blockquote {font-size: 1em;}
        #reply .modal-dialog {max-width: 75%}
    </style>

    <h2>{{ $proposal->name }}</h2>
    <p>{!! $proposal->description !!}</p>

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
                {!! tree($node) !!}
                <hr />
            @endforeach
            <p class="account">Post as:
                <i class="far fa-user"></i> <span class="accountName"></span>
                + <i class="fas fa-weight-hanging"></i> <span class="accountStaked">xx,xxx</span>
            </p>

            {!! Form::open(['route' => 'comments.store']) !!}
                {!! Form::hidden('data[Comment][article_id]', $article->id) !!}
                <input name="data[User][name]" type="hidden" />
                <input name="data[User][stake]" type="hidden" />
                <div class="form-group">
                    {!! Form::textarea('data[Comment][description]', null, ['class' => 'form-control']) !!}
                </div>
                <center>
                    <button type="submit" class="btn btn-primary eos">submit</button>
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
                    <input name="data[User][name]" type="hidden" />
                    <input name="data[User][stake]" type="hidden" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Reply:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <blockquote class="blockquote comment"></blockquote>
                        <p class="account">Post as:
                            <i class="far fa-user"></i> <span class="accountName"></span>
                            + <i class="fas fa-weight-hanging"></i> <span class="accountStaked">xx,xxx</span>
                        </p>
                        <div class="form-group">
                            {!! Form::textarea('data[Comment][description]', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
                        <button type="submit" class="btn btn-primary eos">reply</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection