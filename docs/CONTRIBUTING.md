# 🤝 Contributing Guide

## Welcome Contributors! 🎉

Thank you for your interest in improving the Formidable Loops.so Integration plugin! This guide will help you get started with contributing to the project.

## 📋 Table of Contents

- [Ways to Contribute](#ways-to-contribute)
- [Development Setup](#development-setup)
- [Code Standards](#code-standards)
- [Pull Request Process](#pull-request-process)
- [Issue Guidelines](#issue-guidelines)
- [Testing](#testing)
- [Documentation](#documentation)

## 🛠 Ways to Contribute

### 🐛 Report Bugs
Help us improve by reporting issues:
- Use the [bug report template](../.github/ISSUE_TEMPLATE/bug_report.md)
- Include WordPress/PHP/plugin versions
- Provide clear steps to reproduce
- Include error messages and logs
- Add screenshots if helpful

### 💡 Suggest Features  
Share your ideas for improvements:
- Use the [feature request template](../.github/ISSUE_TEMPLATE/feature_request.md)
- Describe the use case and benefits
- Consider implementation complexity
- Provide mockups or examples if applicable

### 📝 Improve Documentation
Help make our docs better:
- Fix typos or unclear instructions
- Add examples and use cases
- Translate to other languages
- Improve code comments

### 🔧 Submit Code Changes
Contribute code improvements:
- Bug fixes and performance improvements
- New features and enhancements  
- Code refactoring and optimization
- Test coverage improvements

### 🌍 Translations
Help make the plugin accessible worldwide:
- Translate strings to your language
- Review existing translations
- Update translation files

## 🏗 Development Setup

### Prerequisites
- WordPress development environment (Local, XAMPP, Docker, etc.)
- Formidable Forms Pro plugin
- Loops.so account for testing
- Git for version control
- Code editor (VS Code, PhpStorm, etc.)

### Local Environment Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/formidable-loops-integration.git
   cd formidable-loops-integration
   ```

2. **Set Up WordPress**
   ```bash
   # If using Local by Flywheel, WP-CLI, or similar
   # Create a fresh WordPress install with:
   # - WordPress 5.0+
   # - PHP 7.4+
   # - Formidable Forms Pro
   ```

3. **Install Plugin**
   ```bash
   # Symlink plugin to WordPress plugins directory
   ln -s $(pwd) /path/to/wordpress/wp-content/plugins/formidable-loops-integration
   
   # Or copy files directly
   cp -r . /path/to/wordpress/wp-content/plugins/formidable-loops-integration/
   ```

4. **Activate Plugin**
   - Go to WordPress admin → Plugins
   - Activate "Formidable Loops.so Integration"
   - Configure with test Loops.so API key

### Development Tools

**Recommended Extensions (VS Code):**
- PHP Intelephense
- WordPress Hooks Intellisense  
- Prettier - Code formatter
- GitLens — Git supercharged

**Useful Command Line Tools:**
```bash
# PHP CodeSniffer for WordPress standards
composer global require "squizlabs/php_codesniffer=*"
composer global require wp-coding-standards/wpcs

# WP-CLI for WordPress management
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

## 📏 Code Standards

### WordPress Coding Standards
We follow the official [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/):

**PHP Standards:**
```php
<?php
/**
 * Class description
 */
class My_Class_Name {
    
    /**
     * Method description
     *
     * @param string $param Parameter description.
     * @return bool Return value description.
     */
    public function my_method( $param ) {
        if ( empty( $param ) ) {
            return false;
        }
        
        $result = $this->helper_method( $param );
        return $result;
    }
    
    /**
     * Helper method description
     *
     * @param string $input Input parameter.
     * @return string Processed output.
     */
    private function helper_method( $input ) {
        return sanitize_text_field( $input );
    }
}
```

**JavaScript Standards:**
```javascript
( function( $ ) {
    'use strict';
    
    /**
     * Initialize plugin functionality
     */
    function initPlugin() {
        $( '.loops-field' ).on( 'change', function() {
            var fieldValue = $( this ).val();
            if ( fieldValue ) {
                updatePreview( fieldValue );
            }
        });
    }
    
    /**
     * Update preview display
     */
    function updatePreview( value ) {
        $( '#preview-area' ).html( value );
    }
    
    // Initialize when document ready
    $( document ).ready( initPlugin );
    
}( jQuery ) );
```

**CSS Standards:**
```css
/* Main container */
.loops-settings-container {
    max-width: 700px;
    margin: 20px 0;
}

/* Form fields */
.loops-form-field {
    margin-bottom: 20px;
}

.loops-form-field label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
}

.loops-form-field input {
    width: 100%;
    max-width: 400px;
}
```

### Security Best Practices

**Always Sanitize Input:**
```php
// Sanitize text input
$clean_text = sanitize_text_field( $_POST['field_name'] );

// Sanitize email
$clean_email = sanitize_email( $_POST['email_field'] );

// Sanitize URLs  
$clean_url = esc_url_raw( $_POST['url_field'] );
```

**Always Escape Output:**
```php
// Escape for HTML output
echo esc_html( $user_input );

// Escape for attributes
echo '<input value="' . esc_attr( $user_input ) . '">';

// Escape for URLs
echo '<a href="' . esc_url( $user_url ) . '">Link</a>';
```

**Use Nonces for Forms:**
```php
// Create nonce
wp_nonce_field( 'save_loops_settings', 'loops_nonce' );

// Verify nonce
if ( ! wp_verify_nonce( $_POST['loops_nonce'], 'save_loops_settings' ) ) {
    wp_die( 'Security check failed' );
}
```

**Check Capabilities:**
```php
// Check user permissions
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Insufficient permissions' );
}
```

## 🔄 Pull Request Process

### 1. Fork and Branch
```bash
# Fork the repository on GitHub
# Clone your fork
git clone https://github.com/yourusername/formidable-loops-integration.git

# Create feature branch
git checkout -b feature/my-awesome-feature

# Or for bug fixes
git checkout -b fix/bug-description
```

### 2. Make Changes
- Write clean, documented code
- Follow WordPress coding standards
- Add/update tests as needed
- Update documentation if required

### 3. Test Thoroughly
```bash
# Check PHP syntax
find . -name "*.php" -exec php -l {} \;

# Run WordPress coding standards check
phpcs --standard=WordPress --extensions=php .

# Test functionality
# - Install on fresh WordPress
# - Test with different Formidable versions  
# - Verify Loops.so integration
# - Test edge cases and error conditions
```

### 4. Commit Changes
```bash
# Stage changes
git add .

# Commit with descriptive message
git commit -m "Add feature: Dynamic subject line support

- Allow users to set custom subject lines with variables
- Add field validation and sanitization
- Update admin interface with new option
- Add tests for subject line processing
- Update documentation with examples"
```

### 5. Submit Pull Request
- Push branch to your fork
- Create pull request on GitHub
- Use the [pull request template](../.github/pull_request_template.md)
- Link related issues
- Include screenshots for UI changes

### Pull Request Requirements

**Code Quality:**
- [ ] Follows WordPress coding standards
- [ ] Includes proper documentation  
- [ ] Has appropriate error handling
- [ ] Passes all existing tests
- [ ] Includes new tests for new features

**Functionality:**
- [ ] Works with WordPress 5.0+
- [ ] Compatible with Formidable Forms 6.0+
- [ ] Doesn't break existing functionality
- [ ] Handles edge cases gracefully

**Documentation:**
- [ ] Updates relevant documentation
- [ ] Includes code comments
- [ ] Updates changelog if needed

## 🐛 Issue Guidelines

### Before Creating an Issue
1. **Search existing issues** to avoid duplicates
2. **Check documentation** for existing solutions
3. **Test with minimal setup** to isolate the problem
4. **Gather debug information** (logs, versions, etc.)

### Creating Quality Issues

**Use Appropriate Templates:**
- [Bug Report](../.github/ISSUE_TEMPLATE/bug_report.md) for problems
- [Feature Request](../.github/ISSUE_TEMPLATE/feature_request.md) for enhancements
- [Support Question](../.github/ISSUE_TEMPLATE/support_question.md) for help

**Include Required Information:**
- WordPress version
- Formidable Forms version
- Plugin version
- PHP version
- Error messages/logs
- Steps to reproduce

**Write Clear Descriptions:**
- Use descriptive titles
- Explain expected vs actual behavior
- Include relevant code snippets
- Add screenshots if helpful

## 🧪 Testing

### Manual Testing Checklist

**Basic Functionality:**
- [ ] Plugin activates without errors
- [ ] Settings page loads and saves API key
- [ ] Loops action appears in Formidable actions
- [ ] Email sends on form submission
- [ ] Variables populate correctly in emails

**Edge Cases:**
- [ ] Empty form fields don't break email
- [ ] Invalid API key shows error message
- [ ] Network failures are handled gracefully
- [ ] Special characters in form data work correctly
- [ ] Large form submissions process successfully

**Compatibility:**
- [ ] Works with latest WordPress version
- [ ] Works with latest Formidable Forms version
- [ ] Doesn't conflict with popular plugins
- [ ] Works on different PHP versions (7.4, 8.0+)

### Automated Testing

**PHP Unit Tests:**
```bash
# Run PHP unit tests (when available)
phpunit

# Run specific test file
phpunit tests/LoopsIntegrationTest.php
```

**Code Quality Checks:**
```bash
# Check coding standards
phpcs --standard=WordPress .

# Fix automatically fixable issues
phpcbf --standard=WordPress .

# Check for deprecated functions
wp plugin check formidable-loops-integration
```

## 📚 Documentation

### Code Documentation

**Inline Comments:**
```php
/**
 * Send email via Loops.so API
 *
 * This method prepares form data and sends it to the Loops.so
 * transactional email API. It handles errors gracefully and
 * logs any issues for debugging.
 *
 * @since 1.0.0
 *
 * @param array  $form_data Form field data from submission.
 * @param array  $settings  Action settings including template ID.
 * @param object $entry     Formidable entry object.
 * @param string $trigger   Trigger type ('create' or 'update').
 *
 * @return bool True on success, false on failure.
 */
private function send_loops_email( $form_data, $settings, $entry, $trigger ) {
    // Implementation here
}
```

**Hook Documentation:**
```php
/**
 * Filters the email data before sending to Loops.so
 *
 * Allows modification of email data before it's sent to the
 * Loops.so API. Useful for custom field mapping or data processing.
 *
 * @since 1.0.0
 *
 * @param array  $email_data The email data array.
 * @param array  $form_data  Original form field data.
 * @param object $entry      Formidable entry object.
 */
$email_data = apply_filters( 'formidable_loops_email_data', $email_data, $form_data, $entry );
```

### User Documentation

When adding features, update relevant documentation:

- **Installation Guide** - Setup steps for new features
- **Usage Guide** - How to use new functionality  
- **Changelog** - Record of changes made
- **README** - Update feature list and examples

### Translation Files

**Update POT File:**
```bash
# Generate new POT file when adding translatable strings
wp i18n make-pot . languages/formidable-loops.pot
```

**Translatable Strings:**
```php
// Use translation functions for all user-facing strings
__( 'Loops.so Email', 'formidable-loops' )
_e( 'Send beautiful emails via Loops.so', 'formidable-loops' )
esc_html__( 'Template ID', 'formidable-loops' )

// Include translator comments for context
/* translators: %s: Template ID */
sprintf( __( 'Template %s not found', 'formidable-loops' ), $template_id )
```

## 🏷 Issue Labels

We use these labels to organize issues and pull requests:

### Type Labels
- `bug` - Something isn't working correctly
- `enhancement` - New feature or improvement  
- `documentation` - Documentation changes
- `question` - Support questions
- `duplicate` - Issue already reported
- `wontfix` - Won't be implemented

### Priority Labels  
- `critical` - Breaks core functionality
- `high` - Important improvement or significant bug
- `medium` - Standard priority
- `low` - Nice to have, minor issue

### Status Labels
- `good first issue` - Great for new contributors
- `help wanted` - Looking for contributors
- `needs testing` - Requires testing on different setups
- `needs design` - UI/UX input needed

### Component Labels
- `api` - Loops.so API integration
- `admin` - WordPress admin interface
- `formidable` - Formidable Forms integration
- `email` - Email functionality
- `i18n` - Internationalization

## 🎯 Contribution Ideas

### Good First Issues
Perfect for new contributors:
- Fix typos in documentation
- Add translation for your language
- Improve error messages
- Add more examples to usage guide
- Update outdated screenshots

### Medium Complexity
For contributors with some experience:
- Add new template variables
- Improve admin interface design
- Add validation for settings
- Create automated tests
- Optimize API requests

### Advanced Features  
For experienced developers:
- Multiple template support per form
- Advanced conditional logic integration
- Email delivery analytics
- Template preview in WordPress admin
- Webhook integration
- Performance optimizations

## 📞 Getting Help

### Development Questions
- **GitHub Discussions** - Ask development questions
- **Code Review** - Request feedback on your changes
- **Architecture Decisions** - Discuss major changes

### WordPress Development Resources
- [WordPress Developer Handbook](https://developer.wordpress.org/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Formidable Forms Documentation](https://formidableforms.com/knowledgebase/)
- [Loops.so API Documentation](https://loops.so/docs/api-reference)

### Community
- [WordPress Slack](https://make.wordpress.org/chat/) - #core-php, #pluginreview
- [Formidable Forms Facebook Group](https://www.facebook.com/groups/formidableforms/)
- [WordPress Stack Overflow](https://stackoverflow.com/questions/tagged/wordpress)

## 🎉 Recognition

### Contributor Credits
All contributors are recognized in:
- **GitHub Contributors** - Automatic GitHub recognition
- **Plugin Credits** - Listed in plugin header comments
- **Release Notes** - Major contributions highlighted in releases
- **Hall of Fame** - Special recognition for significant contributions

### Types of Recognition
- **Code Contributors** - GitHub commits and pull requests
- **Issue Reporters** - Finding and reporting bugs
- **Documentation** - Improving docs and examples  
- **Translations** - Making plugin accessible worldwide
- **Testing** - Helping verify fixes and features
- **Community Support** - Helping other users

## 📋 Contributor Checklist

Before your first contribution:
- [ ] Read this contributing guide completely
- [ ] Set up local development environment
- [ ] Test plugin with your setup
- [ ] Read WordPress coding standards
- [ ] Join GitHub discussions
- [ ] Look for "good first issue" labels

For each contribution:
- [ ] Create descriptive branch name
- [ ] Follow code standards and security practices
- [ ] Add tests for new functionality
- [ ] Update documentation as needed  
- [ ] Test thoroughly before submitting
- [ ] Write clear commit messages
- [ ] Submit detailed pull request

## 🚀 Release Process

### Version Numbering
We use [Semantic Versioning](https://semver.org/):
- **MAJOR** (1.0.0) - Breaking changes
- **MINOR** (1.1.0) - New features, backwards compatible
- **PATCH** (1.0.1) - Bug fixes, backwards compatible

### Release Schedule
- **Patch releases** - As needed for critical bugs
- **Minor releases** - Monthly or bi-monthly
- **Major releases** - When significant changes warrant

### Release Preparation
1. Update version numbers in plugin files
2. Update changelog with all changes
3. Test release candidate thoroughly  
4. Create GitHub release with ZIP file
5. Submit to WordPress.org (if applicable)

## 🙏 Thank You

Thank you for contributing to make the Formidable Loops.so Integration better for everyone! Your contributions help thousands of WordPress users create better email experiences.

### Special Thanks
- **Formidable Forms** - For the excellent form builder platform
- **Loops.so** - For the beautiful email delivery service  
- **WordPress Community** - For continuous inspiration and support
- **All Contributors** - Every contribution makes a difference!

---

**🤝 Ready to contribute? Start by looking for [good first issues](https://github.com/yourusername/formidable-loops-integration/labels/good%20first%20issue) or [join the discussion](https://github.com/yourusername/formidable-loops-integration/discussions)!**