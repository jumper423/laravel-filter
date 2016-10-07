<?php

namespace jumper423\LaravelTrait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;

/**
 * Trait Sortable.
 */
trait Filter
{
    /**
     * @param Builder $query
     * @param null|array $filter
     *
     * @return Builder
     */
    public function scopeFilter($query, $filter = null)
    {
        if (is_null($filter) && Input::has('filter')) {
            $filter = Input::get('filter');
        }
        if (is_array($filter)) {
            $columns = $this->getFilterColumns();
            if (count($columns)) {
                $query->where(function($query) use($filter, $columns){
                    foreach ($filter as $item) {
                        if (!array_key_exists('name', $item) ||
                            !array_key_exists('value', $item) ||
                            !isset($columns[$item['name']]) || is_null($item['value'])) {
                            continue;
                        }
                        $query->where($columns[$item['name']], '=', $item['value']);
                    }
                });
            }
        }
        return $query;
    }

    /**
     * @return array
     */
    private function getFilterColumns()
    {
        if (!isset($this->filterColumns)) {
            return [];
        } else {
            return $this->filterColumns;
        }
    }
}
