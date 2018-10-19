<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{

    /**
     * @DBRM
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
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
    public function votes4proposal()
    {
        return $this->hasMany(Votes4proposal::class);
    }

    public function votes($type)
    {
        $votes = $this->votes4proposal()->where('value', $type)->get();
        $sum   = 0;
        foreach ($votes as $vote) {
            $sum += $vote->user->stake;
        }
        return $sum;
    }

    public function createArticlesFromContent($content)
    {
        $content  = "\n$content";
        $content  = preg_split('/\n# /', $content);
        $articles = [];
        foreach ($content as $row) {
            $row = trim($row);
            if (!$row) continue;
            $row = explode("\n", $row);
            if (count($row) === 1)  {
                $title = $this->name;
            } else {
                $title = array_shift($row);
            }

            $article = new Article();
            $article->name        = $title;
            $article->description = implode("\n", $row);
            $articles[] = $article;
        }
        return $articles;
    }
}
