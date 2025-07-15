<?php

namespace Picobaz\GridView;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class GridView implements Renderable
{
    public $dataProvider;
    public $searchModel;
    public $pagination;
    public $columns;
    public $export;
    public $fixHeader;
    public $rowOptions;
    public $exportColumns;
    public $myTotal;

    protected $basePathView;

    public function __construct(array $data)
    {
        $this->basePathView = config('gridview.base_view_path', 'gridview::');
        $this->_init($data);
    }

    protected function _init(array $data)
    {
        if (!isset($data['dataProvider'], $data['columns'])) {
            throw new \Exception('DataProvider and columns are required.');
        }

        $this->dataProvider = $data['dataProvider'];
        $this->columns = $data['columns'];
        $this->searchModel = $data['searchModel'] ?? null;
        $this->pagination = $data['pagination'] ?? config('gridview.default_pagination', true);
        $this->export = $data['export'] ?? false;
        $this->fixHeader = $data['fixHeader'] ?? config('gridview.default_fix_header', false);
        $this->rowOptions = $data['rowOptions'] ?? false;
        $this->exportColumns = $data['exportColumns'] ?? false;
        $this->myTotal = $data['myTotal'] ?? false;

        $this->pagination &= $this->dataProvider instanceof LengthAwarePaginator;
    }

    public function render()
    {
        $headers = $this->createHeader();
        $rows = $this->createRows();
        $filter = $this->searchModel ? $this->createFilter() : false;
        $total = $this->pagination ? $this->dataProvider->total() : ($this->myTotal ?? false);

        return view($this->pathView('table_view'), [
            'headers' => $headers,
            'filter' => $filter,
            'rows' => $rows,
            'pagination' => $this->pagination ? $this->dataProvider->onEachSide(0)->withQueryString()->links() : '',
            'total' => $total,
            'export' => $this->export,
            'exportColumns' => $this->exportColumns,
        ]);
    }

    public function createHeader()
    {
        $header = '';
        $classFilterName = $this->getClassFilter();

        foreach ($this->columns as $column) {
            if (isset($column['class'])) {
                $column['label'] = $column['class']::headerLabel();
            } elseif (!isset($column['label'])) {
                $column['label'] = __($column['attribute']) ?? $column['attribute'];
            }

            $column['sort']['column'] = $classFilterName . '[colName]';
            $column['state']['column'] = $classFilterName;

            $header .= view($this->pathView('th'), compact('column'))->render();
        }

        return $header;
    }

    public function createFilter()
    {
        if (!$this->searchModel) {
            return '';
        }

        $filter = '<tr class="filters" style="background: white"><form>';
        $searchFields = $this->searchModel->fields();

        if (empty($searchFields)) {
            return '';
        }

        $classFilterName = $this->getClassFilter();

        foreach ($this->columns as $column) {
            $attribute = $column['attribute'] ?? false;
            if (!in_array($attribute, $searchFields)) {
                $filter .= '<td></td>';
            } else {
                $column['filter']['inputName'] = $classFilterName . '[' . $attribute . ']';
                $column['filter']['inputValue'] = $this->searchModel->{$attribute};
                if (isset($column['filterView'])) {
                    $column['filter']['inputId'] = $this->randomString(10);
                    $filter .= view($column['filterView'], compact('column'))->render();
                } else {
                    $filter .= view($this->pathView('filter'), compact('column'))->render();
                }
            }
        }

        return $filter . '</form></tr>';
    }

    public function createRows()
    {
        $rows = '';
        $rowStyle = null;
        $rowClass = null;

        if (!count($this->dataProvider)) {
            return view(
                $this->pathView('row'),
                [
                    'tds' => view($this->pathView('td'), [
                        'column' => ['tdOption' => ['style' => 'text-align: center', 'attributes' => ['colspan' => 45]]],
                        'value' => 'No records found'
                    ])->render(),
                    'rowStyle' => $rowStyle,
                    'rowClass' => $rowClass
                ]
            )->render();
        }

        foreach ($this->dataProvider as $model) {
            if ($this->rowOptions) {
                $rowClass = isset($this->rowOptions['class']) ? (is_callable($this->rowOptions['class']) ? $this->rowOptions['class']($model) : $this->rowOptions['class']) : null;
                $rowStyle = isset($this->rowOptions['style']) ? (is_callable($this->rowOptions['style']) ? $this->rowOptions['style']($model) : $this->rowOptions['style']) : null;
            }

            $tds = '';

            foreach ($this->columns as $column) {
                $value = __('app.null');
                $tdOptionStyle = '';

                if (isset($column['class'])) {
                    $value = $column['class']::run($this);
                } elseif (isset($column['value']) && is_callable($column['value'])) {
                    $value = $column['value']($model);
                } elseif (isset($column['attribute'])) {
                    $attributes = array_keys($model->getAttributes());
                    if (in_array($column['attribute'], $attributes)) {
                        $value = $model->{$column['attribute']};
                    }
                }

                $value = is_null($value) ? '-' : $value;

                if (isset($column['tdOption']['style'])) {
                    $tdOptionStyle = is_callable($column['tdOption']['style']) ? $column['tdOption']['style']($model) : $column['tdOption']['style'];
                }

                $tds .= view($this->pathView('td'), [
                    'column' => $column,
                    'value' => $value,
                    'tdOptionStyle' => $tdOptionStyle
                ])->render();
            }

            $rows .= view($this->pathView('row'), compact('tds', 'rowClass', 'rowStyle'))->render();
        }

        return $rows;
    }

    protected function pathView($path)
    {
        return $this->basePathView . $path;
    }

    protected function getClassFilter()
    {
        return $this->searchModel ? class_basename($this->searchModel) : false;
    }

    protected function randomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}