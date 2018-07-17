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
     * Reads the existing constitution to put as placeholders
     *
     * @return string[]
     */
    public function populatePlaceholders()
    {
        $base = '../';
        $file = \file_get_contents($base.'resources/eos-mainnet-governance/eosio.system/eosio.system-clause-constitution-rc.md');

        $this->content = $file;

        $file = explode('# ', $file);
        $articles = [];
        foreach ($file as $row) {
            $row = trim($row);
            $row = explode("\n", $row);
            if (count($row) == 1) continue;

            $article = new Article();
            $article->name = array_shift($row);
            $article->description = implode("\n", $row);
            $this->articles[] = $article;
        }
    }
}
