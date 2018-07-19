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

    /**
     * Reads the existing constitution to put as placeholders
     *
     * @return string[]
     */
    public function populatePlaceholders()
    {
        $base = '../';
        $file = \file_get_contents($base.'resources/eos-mainnet-governance/eosio.system/eosio.system-clause-constitution-rc.md');

        $this->content = $file;
    }

    public function createArticlesFromContent($content)
    {
        $content  = "\n$content";
        $content  = preg_split('/\n# /', $content);
        $articles = [];
        foreach ($content as $row) {
            $row = trim($row);
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
