<?php

namespace App\Models\Traits;


trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~', '"'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = array_filter(explode(' ', $term));
        $s = '';
        foreach ($words AS $e)
        {
            if(mb_strlen($e) < 3){continue;}
            $s .= "+$e ";
        }
        return $s;
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearching($query, $term)
    {
        $columns = implode(',', $this->searchable);
        $query->whereRaw('MATCH ( '.$columns.' )
        AGAINST ("\''.$this->fullTextWildcards($term).'\'" IN BOOLEAN MODE)');

        return $query;
    }


}
