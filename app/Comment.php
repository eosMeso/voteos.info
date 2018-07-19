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

    /**
     * @DBRM
     */
    public function votes4comments()
    {
        return $this->hasMany(Votes4comment::class);
    }


   public function votes($type)
   {
       $votes = $this->votes4comments()->where('value', $type)->get();
       $sum   = 0;
       foreach ($votes as $vote) {
           $sum += $vote->user->stake;
       }
       return $sum;
   }

}
