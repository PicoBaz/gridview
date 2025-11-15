# Contributing to GridView

First off, thank you for considering contributing to GridView! 🎉

It's people like you that make GridView such a great tool for the Laravel community.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Pull Request Process](#pull-request-process)
- [Style Guidelines](#style-guidelines)
- [Commit Message Guidelines](#commit-message-guidelines)

---

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

### Our Standards

- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

---

## How Can I Contribute?

### 🐛 Reporting Bugs

Before creating bug reports, please check the existing issues as you might find out that you don't need to create one.

When you are creating a bug report, please include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples**
- **Describe the behavior you observed after following the steps**
- **Explain which behavior you expected to see instead and why**
- **Include screenshots if possible**

**Bug Report Template:**

```markdown
**Environment:**
- GridView Version: [e.g., 1.3.0]
- Laravel Version: [e.g., 10.x]
- PHP Version: [e.g., 8.2]
- Browser: [e.g., Chrome 120]

**Steps to Reproduce:**
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected Behavior:**
A clear description of what you expected to happen.

**Actual Behavior:**
A clear description of what actually happened.

**Screenshots:**
If applicable, add screenshots.

**Additional Context:**
Any other context about the problem.
```

### 💡 Suggesting Features

Feature requests are welcome! Before creating a feature request:

- Check if the feature has already been suggested
- Provide a clear and detailed explanation of the feature
- Explain why this feature would be useful

**Feature Request Template:**

```markdown
**Is your feature request related to a problem?**
A clear description of what the problem is.

**Describe the solution you'd like:**
A clear description of what you want to happen.

**Describe alternatives you've considered:**
Any alternative solutions or features you've considered.

**Additional context:**
Add any other context or screenshots.
```

### 🔧 Contributing Code

1. Fork the repository
2. Create a new branch for your feature
3. Make your changes
4. Write or update tests
5. Ensure all tests pass
6. Submit a pull request

---

## Development Setup

### Prerequisites

- PHP 7.3 or higher
- Composer
- Laravel 8.0 or higher
- Node.js & NPM (for asset compilation)

### Setup Steps

1. **Fork and Clone**

```bash
git clone https://github.com/YOUR_USERNAME/gridview.git
cd gridview
```

2. **Install Dependencies**

```bash
composer install
npm install
```

3. **Link to Local Laravel Project**

```bash
# In your Laravel project
composer config repositories.gridview path ../path/to/gridview
composer require picobaz/gridview:dev-main
```

4. **Run Tests**

```bash
composer test
```

### Project Structure

```
gridview/
├── config/              # Configuration files
├── src/                 # Source code
│   ├── Console/        # Artisan commands
│   ├── Controllers/    # HTTP controllers
│   ├── Contracts/      # Interfaces
│   ├── Traits/         # Reusable traits
│   └── helpers.php     # Helper functions
├── resources/
│   ├── assets/         # JavaScript and CSS
│   └── views/          # Blade templates
├── tests/              # Test files
└── routes/             # Route definitions
```

---

## Pull Request Process

### Before Submitting

1. **Update Documentation**: If you're adding a new feature, update the README
2. **Write Tests**: Ensure your code is covered by tests
3. **Follow Code Style**: Run `composer format` to format your code
4. **Update Changelog**: Add your changes to CHANGELOG.md
5. **Test Locally**: Make sure everything works in a real Laravel project

### PR Checklist

- [ ] My code follows the project's style guidelines
- [ ] I have performed a self-review of my code
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] I have made corresponding changes to the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests that prove my fix is effective or my feature works
- [ ] New and existing unit tests pass locally with my changes
- [ ] Any dependent changes have been merged and published

### PR Template

```markdown
## Description
Brief description of what this PR does.

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## How Has This Been Tested?
Describe the tests you ran to verify your changes.

## Screenshots (if applicable):
Add screenshots to help explain your changes.

## Checklist:
- [ ] My code follows the style guidelines of this project
- [ ] I have performed a self-review
- [ ] I have commented my code where necessary
- [ ] I have updated the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests
- [ ] All tests pass
```

---

## Style Guidelines

### PHP Code Style

We follow PSR-12 coding standards:

```php
<?php

namespace Picobaz\GridView;

class Example
{
    /**
     * Method description
     *
     * @param string $parameter Description
     * @return mixed
     */
    public function exampleMethod(string $parameter): mixed
    {
        // Use camelCase for variables
        $exampleVariable = 'value';
        
        // Use type hints
        return $exampleVariable;
    }
}
```

### JavaScript Code Style

```javascript
// Use ES6+ features
const exampleFunction = (parameter) => {
    // Use camelCase for variables
    const exampleVariable = 'value';
    
    // Use template literals
    return `Result: ${exampleVariable}`;
};

// Use arrow functions for callbacks
array.map(item => item.value);
```

### Blade Templates

```blade
{{-- Use proper indentation --}}
<div class="example">
    @if($condition)
        <p>{{ $variable }}</p>
    @endif
</div>

{{-- Use blade directives instead of PHP --}}
@foreach($items as $item)
    <div>{{ $item->name }}</div>
@endforeach
```

---

## Commit Message Guidelines

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation only changes
- **style**: Changes that don't affect code meaning (formatting, etc.)
- **refactor**: Code change that neither fixes a bug nor adds a feature
- **perf**: Performance improvements
- **test**: Adding or correcting tests
- **chore**: Changes to build process or auxiliary tools

### Examples

```bash
feat(bulk-actions): add bulk delete functionality

Added ability to delete multiple records at once using checkboxes.
Includes UI updates and AJAX handler.

Closes #123

---

fix(inline-edit): resolve XSS vulnerability

Properly escape user input before rendering in editable cells.

---

docs(readme): update installation instructions

Added steps for Laravel 12 compatibility.
```

### Commit Message Rules

- Use present tense ("add feature" not "added feature")
- Use imperative mood ("move cursor to..." not "moves cursor to...")
- First line should be 50 characters or less
- Reference issues and pull requests after the first line
- Use the body to explain **what** and **why**, not **how**

---

## Testing

### Running Tests

```bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/Feature/GridViewTest.php

# Run with coverage
composer test-coverage
```

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class GridViewTest extends TestCase
{
    /** @test */
    public function it_can_render_basic_grid()
    {
        $response = $this->get('/users');
        
        $response->assertStatus(200);
        $response->assertSee('table');
    }
    
    /** @test */
    public function it_can_filter_data()
    {
        // Your test code
    }
}
```

---

## Documentation

When adding new features, please update:

1. **README.md** - Main documentation
2. **CHANGELOG.md** - Version history
3. **Inline Comments** - Code documentation
4. **Examples** - Add practical examples

---

## Questions?

Feel free to reach out:

- 📧 Email: picobaz3@gmail.com
- 💬 Telegram: @picobaz
- 🐛 GitHub Issues: https://github.com/PicoBaz/gridview/issues

---

## Recognition

Contributors will be recognized in:
- README.md contributors section
- CHANGELOG.md for significant contributions
- GitHub contributors graph

---

Thank you for contributing to GridView! 🙏

**[⬆ Back to Top](#contributing-to-gridview)**