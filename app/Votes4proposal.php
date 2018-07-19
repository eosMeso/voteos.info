<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Votes4proposal extends Model
{
    /**
     * @DBRM
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * @DBRM
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
