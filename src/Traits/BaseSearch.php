<?php

namespace Picobaz\GridView\Traits;

use Maatwebsite\Excel\Facades\Excel;

trait BaseSearch
{
    public function loadSearch()
    {
        if (request()->has(class_basename($this))) {
            $data = request()->get(class_basename($this));
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fields())) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function search($query = null)
    {
        $this->loadSearch();

        $searchRules = $this->searchRules();

        foreach ($this->fields() as $field) {
            if ($this->{$field}) {
                if (isset($searchRules[$field]) && is_callable($searchRules[$field])) {
                    $query = $searchRules[$field]($query, $this->{$field});
                } else {
                    $query->where($field, 'LIKE', '%' . $this->{$field} . '%');
                }
            }
        }

        if (request()->get('export')) {
            $type = request()->get('type_export');
            $exportModel = $this->exportModel($query);
            return Excel::download($exportModel, 'export.' . $type);
        }

        return $query->paginate(config('gridview.pagination.per_page', 30));
    }

    public function searchRules(): array
    {
        return [];
    }
}