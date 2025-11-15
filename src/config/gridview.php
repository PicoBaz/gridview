<?php

/**
 * GridView Configuration File
 *
 * @author PicoBaz <picobaz3@gmail.com>
 * @package Picobaz\GridView
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Table Styling Configuration
    |--------------------------------------------------------------------------
    |
    | Customize the CSS classes for your GridView tables
    |
    */
    'styles' => [
        'table_class' => 'table table-sm table-bordered table-hover tableGridView col-12 p-0 m-0',
        'header_class' => 'thead-dark header-gridView',
        'row_class' => 'strip-grid hovered-row',
        'footer_class' => 'gridview-footer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Settings
    |--------------------------------------------------------------------------
    |
    | Configure default pagination behavior
    |
    */
    'pagination' => [
        'per_page' => 30,
        'per_page_options' => [10, 20, 30, 50, 100],
        'show_page_info' => true,
        'show_per_page_selector' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    |
    | Configure export functionality
    |
    */
    'export' => [
        'enabled' => true,
        'formats' => ['csv', 'excel', 'pdf'],
        'default_format' => 'excel',
        'chunk_size' => 1000, // For large exports
    ],

    /*
    |--------------------------------------------------------------------------
    | Bulk Actions Settings
    |--------------------------------------------------------------------------
    |
    | Configure bulk actions functionality
    |
    */
    'bulk_actions' => [
        'enabled' => true,
        'confirm_delete' => true,
        'default_actions' => [
            'delete' => [
                'label' => 'Delete Selected',
                'icon' => 'fa fa-trash',
                'class' => 'btn btn-danger btn-sm',
                'confirm' => 'Are you sure you want to delete selected items?',
            ],
            'activate' => [
                'label' => 'Activate',
                'icon' => 'fa fa-check',
                'class' => 'btn btn-success btn-sm',
            ],
            'deactivate' => [
                'label' => 'Deactivate',
                'icon' => 'fa fa-ban',
                'class' => 'btn btn-warning btn-sm',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inline Editing Settings
    |--------------------------------------------------------------------------
    |
    | Configure inline editing functionality
    |
    */
    'inline_editing' => [
        'enabled' => true,
        'auto_save' => true,
        'confirm_on_change' => false,
        'allowed_types' => ['text', 'number', 'email', 'date', 'select', 'textarea'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Paths
    |--------------------------------------------------------------------------
    |
    | Define paths to required assets
    |
    */
    'assets' => [
        'jquery_ui_js' => 'vendor/gridview/assets/jquery-ui.js',
        'jquery_multiselect_js' => 'vendor/gridview/assets/jquery_multiselect.js',
        'jquery_ui_css' => 'vendor/gridview/assets/jquery-ui.css',
        'jquery_multiselect_css' => 'vendor/gridview/assets/jquery_multiselect.css',
        'bulk_actions_js' => 'vendor/gridview/assets/gridview-bulk-actions.js',
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Settings
    |--------------------------------------------------------------------------
    |
    | Configure search behavior
    |
    */
    'search' => [
        'debounce_delay' => 300, // milliseconds
        'min_characters' => 2,
        'highlight_results' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Enable caching for better performance
    |
    */
    'cache' => [
        'enabled' => false,
        'ttl' => 3600, // seconds
        'key_prefix' => 'gridview_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    |
    | Icon classes for various actions
    |
    */
    'icons' => [
        'sort_asc' => 'fa fa-sort-up',
        'sort_desc' => 'fa fa-sort-down',
        'sort_both' => 'fa fa-sort',
        'filter' => 'fa fa-filter',
        'export' => 'fa fa-download',
        'refresh' => 'fa fa-refresh',
        'settings' => 'fa fa-cog',
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Default texts and labels
    |
    */
    'i18n' => [
        'no_data' => 'No data available',
        'loading' => 'Loading...',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',
        'search' => 'Search',
        'export' => 'Export',
        'actions' => 'Actions',
        'selected' => 'selected',
    ],

];