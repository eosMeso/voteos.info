<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * @DBRM
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @DBRM
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

}
