<?php

namespace Picobaz\GridView;

/**
 * BulkActionColumn - Checkbox column for bulk actions
 *
 * @author PicoBaz <picobaz3@gmail.com>
 * @package Picobaz\GridView
 */
class BulkActionColumn
{
    /**
     * Render the checkbox column header
     *
     * @param array $column
     * @return string
     */
    public static function renderHeader($column)
    {
        $checkboxId = $column['checkboxOptions']['id'] ?? 'select-all-grid';
        $checkboxClass = $column['checkboxOptions']['class'] ?? 'gridview-select-all';

        return '<th class="gridview-bulk-column">
            <input type="checkbox" 
                   id="' . $checkboxId . '" 
                   class="' . $checkboxClass . '" 
                   data-toggle="tooltip" 
                   title="Select All">
        </th>';
    }

    /**
     * Render the checkbox for each row
     *
     * @param mixed $model
     * @param array $column
     * @return string
     */
    public static function renderCell($model, $column)
    {
        $primaryKey = $column['primaryKey'] ?? 'id';
        $checkboxName = $column['checkboxOptions']['name'] ?? 'selection[]';
        $checkboxClass = $column['checkboxOptions']['class'] ?? 'gridview-checkbox';

        $value = is_object($model) ? $model->$primaryKey : $model[$primaryKey];

        return '<td class="gridview-bulk-column">
            <input type="checkbox" 
                   name="' . $checkboxName . '" 
                   value="' . htmlspecialchars($value) . '" 
                   class="' . $checkboxClass . '">
        </td>';
    }
}