<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Comment extends Model
{
    use NodeTrait;

    /**
     * @DBRM
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * @DBRM
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
