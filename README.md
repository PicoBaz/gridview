<div align="center">

# 🎯 GridView

### *The Ultimate Data Grid Package for Laravel*

[![Latest Version on Packagist](https://img.shields.io/packagist/v/picobaz/gridview.svg?style=for-the-badge&logo=packagist)](https://packagist.org/packages/picobaz/gridview)
[![Total Downloads](https://img.shields.io/packagist/dt/picobaz/gridview.svg?style=for-the-badge&logo=packagist)](https://packagist.org/packages/picobaz/gridview)
[![License](https://img.shields.io/packagist/l/picobaz/gridview.svg?style=for-the-badge)](https://packagist.org/packages/picobaz/gridview)
[![PHP Version](https://img.shields.io/packagist/php-v/picobaz/gridview.svg?style=for-the-badge&logo=php)](https://packagist.org/packages/picobaz/gridview)
[![Laravel Version](https://img.shields.io/badge/Laravel-8%2B%20%7C%209%20%7C%2010%20%7C%2011%20%7C%2012-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)

**Build powerful, flexible, and beautiful data tables in Laravel with ease!**

[Installation](#-installation) • [Quick Start](#-quick-start) • [Features](#-features) • [Documentation](#-documentation) • [Examples](#-examples) • [Support](#-support)

---

</div>

## 🌟 Why GridView?

GridView transforms your boring data tables into **powerful, interactive data grids** with just a few lines of code. Whether you're building an admin panel, a dashboard, or any data-intensive application, GridView has got you covered!

### ✨ Key Highlights

- 🚀 **Lightning Fast** - Optimized for performance with large datasets
- 🎨 **Beautiful UI** - Modern, responsive design out of the box
- 🔍 **Advanced Filtering** - Search and filter with ease
- 📊 **Smart Sorting** - Multi-column sorting support
- 📤 **Export Anywhere** - CSV, Excel, and PDF exports
- ✏️ **Inline Editing** - Edit data without leaving the table
- ☑️ **Bulk Actions** - Perform actions on multiple rows at once
- 🎯 **Highly Customizable** - Style it your way
- 📱 **Responsive** - Looks great on all devices
- 🔐 **Secure** - Built with security best practices

---

## 📦 Installation

Install via Composer:

```bash
composer require picobaz/gridview
```

### Publish Assets (Optional)

```bash
# Publish configuration
php artisan vendor:publish --tag=gridview-config

# Publish views (for customization)
php artisan vendor:publish --tag=gridview-views

# Publish JavaScript/CSS assets
php artisan vendor:publish --tag=gridview-assets
```

---

## 🚀 Quick Start

### Step 1: Create a Search Model

Generate a search model for your data:

```bash
php artisan make:gridview-search UserSearch
```

This creates `app/SearchModel/UserSearch.php`:

```php
<?php

namespace App\SearchModel;

use App\Models\User;
use Picobaz\GridView\Contracts\SearchContract;
use Picobaz\GridView\Traits\BaseSearch;

class UserSearch extends User implements SearchContract
{
    use BaseSearch;

    public function fields(): array
    {
        return ['name', 'email', 'status', 'created_at'];
    }

    public function searchRules(): array
    {
        return [
            'name' => function ($query, $value) {
                return $query->where('name', 'LIKE', "%{$value}%");
            },
            'email' => function ($query, $value) {
                return $query->where('email', 'LIKE', "%{$value}%");
            },
            'status' => function ($query, $value) {
                return $query->where('status', $value);
            },
        ];
    }
}
```

### Step 2: Controller Setup

```php
use App\Models\User;
use App\SearchModel\UserSearch;

public function index()
{
    $users = User::query();
    $searchModel = new UserSearch();
    $dataProvider = $searchModel->search($users);

    return view('users.index', compact('searchModel', 'dataProvider'));
}
```

### Step 3: Blade View

```php
{!! gridview([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'export' => true,
    'columns' => [
        // Serial number column
        ['class' => \Picobaz\GridView\SerialColumns::class],
        
        // Bulk action checkbox
        [
            'class' => \Picobaz\GridView\BulkActionColumn::class,
            'checkboxOptions' => [
                'name' => 'selection[]',
                'class' => 'gridview-checkbox'
            ]
        ],
        
        // Regular columns
        [
            'label' => 'Name',
            'attribute' => 'name',
            'sortable' => true,
        ],
        [
            'label' => 'Email',
            'attribute' => 'email',
            'sortable' => true,
        ],
        
        // Inline editable column
        [
            'label' => 'Status',
            'attribute' => 'status',
            'editable' => true,
            'editableType' => 'select',
            'editableOptions' => [
                'source' => [
                    ['value' => 'active', 'text' => 'Active'],
                    ['value' => 'inactive', 'text' => 'Inactive']
                ]
            ]
        ],
        
        // Action buttons
        [
            'label' => 'Actions',
            'value' => function($model) {
                return '
                    <a href="'.route('users.edit', $model->id).'" class="btn btn-sm btn-primary">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="'.route('users.show', $model->id).'" class="btn btn-sm btn-info">
                        <i class="fa fa-eye"></i> View
                    </a>
                ';
            }
        ],
    ]
]) !!}

{{-- Bulk Actions Toolbar --}}
<div class="gridview-bulk-toolbar mt-3" style="display: none;">
    <span class="badge badge-info">
        <span class="gridview-selected-count">0</span> items selected
    </span>
    
    <button onclick="executeGridViewBulkAction('delete', 'Are you sure?')" 
            class="btn btn-sm btn-danger gridview-bulk-action-btn">
        <i class="fa fa-trash"></i> Delete Selected
    </button>
    
    <button onclick="executeGridViewBulkAction('activate')" 
            class="btn btn-sm btn-success gridview-bulk-action-btn">
        <i class="fa fa-check"></i> Activate
    </button>
</div>

{{-- Add model class for bulk actions --}}
<div data-gridview-model="App\Models\User" style="display: none;"></div>
```

**That's it!** 🎉 You now have a fully functional data grid with filtering, sorting, pagination, and export capabilities!

---

## 🎯 Features

### 📊 Smart Data Table

Create beautiful, responsive data tables with minimal code:

```php
{!! gridview([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [...]
]) !!}
```

### 🔍 Advanced Filtering

Filter data with custom search rules:

```php
public function searchRules(): array
{
    return [
        'name' => function ($query, $value) {
            return $query->where('name', 'LIKE', "%{$value}%");
        },
        'category_id' => function ($query, $value) {
            return $query->whereHas('category', function ($q) use ($value) {
                $q->where('id', $value);
            });
        },
        'price_range' => function ($query, $value) {
            [$min, $max] = explode('-', $value);
            return $query->whereBetween('price', [$min, $max]);
        },
    ];
}
```

### 📈 Multi-Column Sorting

```php
[
    'label' => 'Name',
    'attribute' => 'name',
    'sortable' => true,
]
```

### 📤 Export to Multiple Formats

Export your data to CSV, Excel, or PDF:

```php
{!! gridview([
    'export' => true,
    'exportColumns' => [
        'Name' => 'name',
        'Email' => 'email',
        'Status' => 'status',
    ]
]) !!}
```

### ✏️ Inline Editing (NEW! 🔥)

Edit data directly in the table without navigating away:

```php
[
    'label' => 'Title',
    'attribute' => 'title',
    'editable' => true,
    'editableType' => 'text', // text, number, email, date, select, textarea
    'editableUrl' => route('gridview.inline.update'),
]
```

**Select dropdown example:**

```php
[
    'label' => 'Status',
    'attribute' => 'status',
    'editable' => true,
    'editableType' => 'select',
    'editableOptions' => [
        'source' => [
            ['value' => 'pending', 'text' => 'Pending'],
            ['value' => 'approved', 'text' => 'Approved'],
            ['value' => 'rejected', 'text' => 'Rejected']
        ]
    ]
]
```

### ☑️ Bulk Actions (NEW! 🔥)

Perform actions on multiple rows at once:

```php
// Add bulk action column
[
    'class' => \Picobaz\GridView\BulkActionColumn::class,
    'checkboxOptions' => [
        'name' => 'selection[]'
    ]
]

// Add bulk action buttons
<button onclick="executeGridViewBulkAction('delete', 'Confirm delete?')">
    Delete Selected
</button>
<button onclick="executeGridViewBulkAction('activate')">
    Activate Selected
</button>
```

### 🎨 Custom Column Rendering

```php
[
    'label' => 'Avatar',
    'value' => function($model) {
        return '<img src="'.$model->avatar_url.'" class="rounded-circle" width="40">';
    },
    'format' => 'raw'
]
```

### 🔧 Custom Filters

Create custom filter views:

```php
[
    'label' => 'Status',
    'attribute' => 'status',
    'filterView' => 'vendor.gridview.filters.status_filter',
]
```

**Custom filter view:**

```blade
{{-- resources/views/vendor/gridview/filters/status_filter.blade.php --}}
<td class="filterGrid">
    <select class="form-control filter_field" name="{{ $column['filter']['inputName'] }}">
        <option value="">All</option>
        <option value="active" {{ $column['filter']['inputValue'] == 'active' ? 'selected' : '' }}>
            Active
        </option>
        <option value="inactive" {{ $column['filter']['inputValue'] == 'inactive' ? 'selected' : '' }}>
            Inactive
        </option>
    </select>
</td>
```

---

## 🎨 Customization

### Styling

Customize table appearance in `config/gridview.php`:

```php
'styles' => [
    'table_class' => 'table table-striped table-hover',
    'header_class' => 'thead-dark',
    'row_class' => 'table-row',
],
```

### Pagination

```php
'pagination' => [
    'per_page' => 30,
    'per_page_options' => [10, 20, 30, 50, 100],
],
```

### Icons

```php
'icons' => [
    'sort_asc' => 'fa fa-sort-up',
    'sort_desc' => 'fa fa-sort-down',
    'export' => 'fa fa-download',
],
```

---

## 📚 Advanced Examples

### Example 1: E-commerce Products Grid

```php
{!! gridview([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'export' => true,
    'columns' => [
        ['class' => \Picobaz\GridView\SerialColumns::class],
        ['class' => \Picobaz\GridView\BulkActionColumn::class],
        
        [
            'label' => 'Image',
            'value' => function($model) {
                return '<img src="'.$model->image_url.'" width="50" class="rounded">';
            },
            'format' => 'raw',
        ],
        [
            'label' => 'Product Name',
            'attribute' => 'name',
            'sortable' => true,
            'editable' => true,
            'editableType' => 'text',
        ],
        [
            'label' => 'Category',
            'attribute' => 'category.name',
            'sortable' => true,
        ],
        [
            'label' => 'Price',
            'attribute' => 'price',
            'sortable' => true,
            'editable' => true,
            'editableType' => 'number',
            'value' => function($model) {
                return '$' . number_format($model->price, 2);
            }
        ],
        [
            'label' => 'Stock',
            'attribute' => 'stock',
            'sortable' => true,
            'value' => function($model) {
                $class = $model->stock > 10 ? 'success' : ($model->stock > 0 ? 'warning' : 'danger');
                return '<span class="badge badge-'.$class.'">'.$model->stock.'</span>';
            },
            'format' => 'raw',
        ],
        [
            'label' => 'Status',
            'attribute' => 'status',
            'editable' => true,
            'editableType' => 'select',
            'editableOptions' => [
                'source' => [
                    ['value' => 'active', 'text' => 'Active'],
                    ['value' => 'inactive', 'text' => 'Inactive'],
                    ['value' => 'draft', 'text' => 'Draft']
                ]
            ],
            'value' => function($model) {
                $badges = [
                    'active' => 'success',
                    'inactive' => 'danger',
                    'draft' => 'secondary'
                ];
                return '<span class="badge badge-'.$badges[$model->status].'">'
                    . ucfirst($model->status) . '</span>';
            },
            'format' => 'raw',
        ],
        [
            'label' => 'Actions',
            'value' => function($model) {
                return '
                    <a href="'.route('products.edit', $model).'" class="btn btn-sm btn-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="'.route('products.show', $model).'" class="btn btn-sm btn-info">
                        <i class="fa fa-eye"></i>
                    </a>
                ';
            },
            'format' => 'raw',
        ],
    ],
    'exportColumns' => [
        'ID' => 'id',
        'Product Name' => 'name',
        'Category' => 'category.name',
        'Price' => 'price',
        'Stock' => 'stock',
        'Status' => 'status',
    ]
]) !!}
```

### Example 2: User Management Grid

```php
{!! gridview([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'columns' => [
        ['class' => \Picobaz\GridView\SerialColumns::class],
        ['class' => \Picobaz\GridView\BulkActionColumn::class],
        
        [
            'label' => 'Avatar',
            'value' => function($model) {
                return '<img src="'.$model->avatar.'" class="rounded-circle" width="40">';
            },
            'format' => 'raw',
        ],
        [
            'label' => 'Name',
            'attribute' => 'name',
            'sortable' => true,
            'editable' => true,
        ],
        [
            'label' => 'Email',
            'attribute' => 'email',
            'sortable' => true,
        ],
        [
            'label' => 'Role',
            'attribute' => 'role',
            'editable' => true,
            'editableType' => 'select',
            'editableOptions' => [
                'source' => [
                    ['value' => 'admin', 'text' => 'Admin'],
                    ['value' => 'user', 'text' => 'User'],
                    ['value' => 'moderator', 'text' => 'Moderator']
                ]
            ]
        ],
        [
            'label' => 'Joined',
            'attribute' => 'created_at',
            'sortable' => true,
            'value' => function($model) {
                return $model->created_at->format('M d, Y');
            }
        ],
    ]
]) !!}
```

---

## ⚙️ Configuration

Full configuration options in `config/gridview.php`:

```php
return [
    'styles' => [...],
    'pagination' => [...],
    'export' => [...],
    'bulk_actions' => [
        'enabled' => true,
        'default_actions' => [...]
    ],
    'inline_editing' => [
        'enabled' => true,
        'auto_save' => true,
    ],
    'search' => [
        'debounce_delay' => 300,
        'min_characters' => 2,
    ],
    'cache' => [
        'enabled' => false,
        'ttl' => 3600,
    ],
];
```

---

## 🔒 Security

GridView takes security seriously:

- ✅ CSRF protection on all forms
- ✅ XSS prevention with proper escaping
- ✅ SQL injection prevention via Eloquent
- ✅ Fillable attribute checking for inline editing
- ✅ Model class validation

### Inline Edit Security

The package validates:
- Model class exists
- Attribute is fillable
- User has permission (implement your own middleware)

---

## 🧪 Testing

```bash
composer test
```

---

## 📖 Requirements

- PHP >= 7.3 | 8.0+
- Laravel >= 8.0 | 9.0 | 10.0 | 11.0 | 12.0
- maatwebsite/excel >= 3.1

---

## 🤝 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

### How to Contribute

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.

### v1.3.0 (Latest)
- ✨ Added Bulk Actions feature
- ✨ Added Inline Editing capability
- 🔧 Improved performance for large datasets
- 🐛 Bug fixes and improvements

---

## 💬 Support

Need help? Have questions?

- 📧 Email: [picobaz3@gmail.com](mailto:picobaz3@gmail.com)
- 💬 Telegram: [@picobaz](https://t.me/picobaz)
- 🐛 Issues: [GitHub Issues](https://github.com/PicoBaz/gridview/issues)
- ⭐ Star us on [GitHub](https://github.com/PicoBaz/gridview)

---

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

---

## 🌟 Show Your Support

If you find GridView helpful, please consider:

- ⭐ Starring the repository on [GitHub](https://github.com/PicoBaz/gridview)
- 🐦 Sharing it on social media
- 📝 Writing a blog post about your experience
- 💰 [Sponsoring the project](https://github.com/sponsors/PicoBaz)

---

## 🙏 Credits

- **Author**: [PicoBaz](https://github.com/PicoBaz)
- **Contributors**: [All Contributors](https://github.com/PicoBaz/gridview/graphs/contributors)
- Inspired by Yii2 GridView

---

<div align="center">

**Made with ❤️ by [PicoBaz](https://github.com/PicoBaz)**

[⬆ Back to Top](#-gridview)

</div>