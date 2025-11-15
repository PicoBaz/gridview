# Changelog

All notable changes to `gridview` will be documented in this file.

## [v1.3.0] - 2025-11-15

### 🎉 Major Features Added

#### ✨ Bulk Actions
- Added `BulkActionColumn` for selecting multiple rows
- Implemented bulk delete, activate, and deactivate actions
- Added JavaScript handler for bulk operations
- Custom bulk actions support via events
- Select all/deselect all functionality
- Visual feedback for selected items count

#### ✏️ Inline Editing
- Added inline editing capability for table cells
- Support for multiple input types: text, number, email, date, select, textarea
- Real-time AJAX updates without page reload
- Click-to-edit with visual indicators
- Keyboard shortcuts (Enter to save, Escape to cancel)
- Success/error notifications
- Automatic model validation

### 🔧 Improvements
- Enhanced security with model class validation
- Added CSRF protection for AJAX requests
- Improved error handling and user feedback
- Better JavaScript organization with vanilla JS
- Added configurable debounce for search
- Performance optimizations for large datasets

### 📚 Documentation
- Completely rewritten README with modern design
- Added comprehensive examples and use cases
- Included step-by-step quick start guide
- Added security best practices section
- Improved code examples with real-world scenarios

### 🐛 Bug Fixes
- Fixed XSS vulnerabilities in custom column rendering
- Resolved pagination issues with filtered data
- Fixed export functionality with large datasets
- Corrected sorting behavior on related models

---

## [v1.2.1] - 2025-07-15

### 🔧 Improvements
- Updated dependencies for Laravel 12 support
- Minor performance improvements

---

## [v1.2.0] - 2025-06-10

### ✨ Features
- Added Laravel 11 compatibility
- Improved export performance with chunking

### 🐛 Bug Fixes
- Fixed filter state persistence
- Resolved conflicts with Bootstrap 5

---

## [v1.1.2] - 2024-12-20

### 🐛 Bug Fixes
- Fixed search model generation command
- Corrected asset publishing paths

---

## [v1.1.1] - 2024-11-15

### 🔧 Improvements
- Enhanced filter customization options
- Added more configuration options

---

## [v1.1.0] - 2024-10-01

### ✨ Features
- Added PDF export support
- Custom filter views
- Improved pagination controls

### 🐛 Bug Fixes
- Fixed sorting on nullable columns
- Resolved export encoding issues

---

## [v1.0.0] - 2024-08-15

### 🎉 Initial Release
- Basic GridView functionality
- Pagination support
- Filtering and sorting
- CSV and Excel export
- Search model generation command
- Customizable columns
- Bootstrap integration

---

## Upgrade Guide

### Upgrading to v1.3.0

#### New Routes
The package now registers routes automatically. If you have custom routes, make sure they don't conflict:

```php
// These routes are now automatically registered:
POST /gridview/inline-update
POST /gridview/bulk-action
```

#### New Configuration Options
Run the following to update your config file:

```bash
php artisan vendor:publish --tag=gridview-config --force
```

New config options added:
- `bulk_actions` - Bulk action settings
- `inline_editing` - Inline editing settings
- `search.debounce_delay` - Search debounce timing

#### New Assets
Publish the updated assets:

```bash
php artisan vendor:publish --tag=gridview-assets --force
```

#### Breaking Changes
None! All changes are backward compatible.

#### New Features Usage

**Bulk Actions:**
```php
// Add to your columns array:
['class' => \Picobaz\GridView\BulkActionColumn::class]

// Add model class to your view:
<div data-gridview-model="App\Models\YourModel"></div>
```

**Inline Editing:**
```php
// Add to your column:
[
    'attribute' => 'name',
    'editable' => true,
    'editableType' => 'text',
]
```

---

## Support

For support and questions:
- 📧 Email: picobaz3@gmail.com
- 💬 Telegram: @picobaz
- 🐛 Issues: https://github.com/PicoBaz/gridview/issues

---

**[⬆ Back to Top](#changelog)**