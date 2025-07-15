<?php

return [
    'base_view_path' => 'gridview::',
    'default_export_formats' => ['csv', 'xlsx', 'pdf'],
    'pagination' => [
        'per_page' => 30,
    ],
    'default_fix_header' => false,
    'styles' => [
        'table_class' => 'table table-sm table-bordered tableGridView col-12 p-0 m-0',
        'header_class' => 'header-gridView',
        'row_class' => 'strip-grid hovered-row',
    ],
    'assets' => [
        'jquery_ui_js' => 'vendor/gridview/assets/jquery-ui.js',
        'jquery_multiselect_js' => 'vendor/gridview/assets/jquery_multiselect.js',
        'jquery_ui_css' => 'vendor/gridview/assets/jquery-ui.css',
        'jquery_multiselect_css' => 'vendor/gridview/assets/jquery_multiselect.css',
    ],
];