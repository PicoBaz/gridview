# Picobaz GridView

[![Latest Version on Packagist](https://img.shields.io/packagist/v/picobaz/gridview.svg?style=flat-square)](https://packagist.org/packages/picobaz/gridview)
[![Total Downloads](https://img.shields.io/packagist/dt/picobaz/gridview.svg?style=flat-square)](https://packagist.org/packages/picobaz/gridview)
[![License](https://img.shields.io/packagist/l/picobaz/gridview.svg?style=flat-square)](https://packagist.org/packages/picobaz/gridview)

A flexible and customizable data grid component for Laravel, providing features like pagination, filtering, sorting, and exporting to CSV, Excel, and PDF formats using `maatwebsite/excel`.

## Installation

Install the package via Composer:

```bash
composer require picobaz/gridview
```

Publish the configuration, views, and assets (optional):

```bash
php artisan vendor:publish --tag=gridview-config
php artisan vendor:publish --tag=gridview-views
php artisan vendor:publish --tag=gridview-assets
```

## Requirements

- PHP >= 7.3
- Laravel >= 8.0
- maatwebsite/excel >= 3.1
- jQuery UI and jQuery MultiSelect (for filtering and export functionality)

## Usage

### Creating a Search Model

You can generate a search model using the provided Artisan command. This will create a model that implements `SearchContract` and uses `BaseSearch` trait.

```bash
php artisan make:gridview-search FaqSearch
```

This command creates a `FaqSearch` class in the `app/SearchModel` directory, extending the corresponding model (e.g., `App\Models\Faq`). You can customize the fields and search rules as needed.

Example of a generated search model:

```php
<?php

namespace App\SearchModel;

use App\Models\Faq;
use Picobaz\GridView\Contracts\SearchContract;
use Picobaz\GridView\Traits\BaseSearch;

class FaqSearch extends Faq implements SearchContract
{
    use BaseSearch;

    public function fields(): array
    {
        return [];
    }

    public function searchRules(): array
    {
        return [];
    }

    public function exportModel($query)
    {
        return new \App\Exports\FaqSearchExport($query->get());
    }
}
```

### Controller Example

In your controller, prepare the data provider and search model:

```php
use App\Models\Faq;
use App\SearchModel\FaqSearch;

public function index()
{
    $faqs = Faq::latest();
    $searchModel = new FaqSearch();
    $dataProvider = $searchModel->search($faqs);

    return view('admin.faq.index', compact('searchModel', 'dataProvider'));
}
```

### Blade Example

Use the `gridview()` helper or `@gridview` directive in your Blade view:

```blade
{!! gridview([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'export' => true,
    'columns' => [
        ['class' => \Picobaz\GridView\SerialColumns::class],
        [
            'label' => 'Title',
            'attribute' => 'title',
            'tdOption' => ['style' => 'text-align:center;']
        ],
        [
            'label' => 'Description',
            'attribute' => 'description',
            'tdOption' => ['style' => 'text-align:center;']
        ],
        [
            'label' => 'Actions',
            'attribute' => 'a',
            'tdOption' => ['style' => 'width:100px', 'class' => 'small'],
            'value' => function($model) {
                return "<a class='px-1 text-dark btn btn-sm btn-outline-primary' href='".route('faq.show', ['faq' => $model->id])."'><i class='fa fa-eye' title='View'></i></a>";
            }
        ],
    ],
    'exportColumns' => [
        'Title' => 'title',
        'Description' => 'description',
    ]
]) !!}
```

### Customizing Search Models

Edit the generated search model to define searchable fields and custom search rules:

```php
public function fields(): array
{
    return ['title', 'description', 'status', 'category_id'];
}

public function searchRules(): array
{
    return [
        'title' => function ($query, $value) {
            return $query->where('title', 'LIKE', '%' . $value . '%');
        },
        'description' => function ($query, $value) {
            return $query->where('description', 'LIKE', '%' . $value . '%');
        },
        'status' => function ($query, $value) {
            return $query->where('status', '=', $value);
        },
        'category_id' => function ($query, $value) {
            return $query->whereHas('category', function ($q) use ($value) {
                $q->where('id', $value);
            });
        },
    ];
}

public function exportModel($query)
{
    return new \App\Exports\FaqSearchExport($query->get());
}
```

### Customizing Filters

To create custom filters, define a Blade view in `resources/views/vendor/gridview/filters/` and reference it in the column configuration:

```php
'columns' => [
    [
        'label' => 'Status',
        'attribute' => 'status',
        'filterView' => 'vendor.gridview.filters.custom_status_filter',
    ],
]
```

Create the custom filter view (`resources/views/vendor/gridview/filters/custom_status_filter.blade.php`):

```blade
<td class="filterGrid">
    <select class="form-control filter_field" name="{{ $column['filter']['inputName'] }}">
        <option value="">All</option>
        <option value="active" {{ $column['filter']['inputValue'] == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $column['filter']['inputValue'] == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</td>
```

### Customizing Styles

Edit the `config/gridview.php` file to customize table styles, pagination settings, and asset paths:

```php
return [
    'styles' => [
        'table_class' => 'table table-sm table-bordered tableGridView col-12 p-0 m-0',
        'header_class' => 'header-gridView',
        'row_class' => 'strip-grid hovered-row',
    ],
    'pagination' => [
        'per_page' => 30,
    ],
    'assets' => [
        'jquery_ui_js' => 'vendor/gridview/assets/jquery-ui.js',
        'jquery_multiselect_js' => 'vendor/gridview/assets/jquery_multiselect.js',
        'jquery_ui_css' => 'vendor/gridview/assets/jquery-ui.css',
        'jquery_multiselect_css' => 'vendor/gridview/assets/jquery_multiselect.css',
    ],
];
```

### Customizing Views

Override the default views by publishing them:

```bash
php artisan vendor:publish --tag=gridview-views
```

Edit the views in `resources/views/vendor/gridview/` to match your application's design.

### Assets

The package relies on jQuery UI and jQuery MultiSelect for some features. Ensure these are included in your project or publish the provided assets:

```bash
php artisan vendor:publish --tag=gridview-assets
```

Include the assets in your Blade layout:

```blade
<link rel="stylesheet" href="{{ asset('vendor/gridview/assets/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/gridview/assets/jquery_multiselect.css') }}">
<script src="{{ asset('vendor/gridview/assets/jquery-ui.js') }}"></script>
<script src="{{ asset('vendor/gridview/assets/jquery_multiselect.js') }}"></script>
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.