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
                        if (!is_array($item) ||
                            !array_key_exists('name', $item) ||
                            !array_key_exists('value', $item) ||
                            !isset($columns[ltrim($item['name'], '!')]) || is_null($item['value'])) {
                            continue;
                        }
                        if ($item['name'] != ltrim($item['name'], '!')) {
                            $item['name'] = ltrim($item['name']);
                            if (is_array($item['value'])) {
                                $query->whereNotIn($columns[$item['name']], $item['value']);
                            } else {
                                $query->where($columns[$item['name']], '!=', $item['value']);
                            }
                        } else {
                            if (is_array($item['value'])) {
                                $query->whereIn($columns[$item['name']], $item['value']);
                            } else {
                                if ($item['value'] == 'null' || $item['value'] == 'NULL') {
                                    $query->whereNull($columns[$item['name']]);
                                } else if ($item['value'] == 'not_null' || $item['value'] == 'NOT_NULL') {
                                    $query->whereNotNull($columns[$item['name']]);
                                } else {
                                    $query->where($columns[$item['name']], '=', $item['value']);
                                }
                            }
                        }
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
