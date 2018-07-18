<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->decimal('stake', 16, 4);
        });

        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->text('transaction');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->longText('description');
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proposal_id')->unsigned();
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->string('name');
            $table->longText('description');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('transaction');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->longText('description');
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::create('votes4proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->text('transaction');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('proposal_id')->unsigned();
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->text('value');
            $table->timestamps();
        });

        Schema::create('votes4articles', function (Blueprint $table) {
            $table->increments('id');
            $table->text('transaction');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->text('value');
            $table->timestamps();
        });

        Schema::create('votes4comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('transaction');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('comment_id')->unsigned();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->text('value');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropNestedSet();
        });

        Schema::dropIfExists('comments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('proposal_supporters');
        Schema::dropIfExists('proposals');

    }
}
