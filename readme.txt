=== Formidable Loops.so Integration ===
Contributors: yourname
Tags: formidable, forms, email, loops, transactional-email, automation
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Send beautiful transactional emails via Loops.so when Formidable Forms are submitted or updated.

== Description ==

**Formidable Loops.so Integration** seamlessly connects your Formidable Forms with Loops.so's powerful transactional email platform. Create stunning, personalized email notifications that are delivered reliably and track beautifully.

### ✨ Key Features

* **Native Formidable Integration** - Works exactly like built-in Formidable actions
* **Dynamic Email Templates** - Use form field data in your email content and subjects  
* **Flexible Recipients** - Send to form submitters, fixed addresses, or multiple recipients
* **Trigger Control** - Send emails on form creation, updates, or both
* **Variable Mapping** - Automatically map form fields to email template variables
* **Multiple Actions** - Configure different templates for different scenarios
* **Professional UI** - Clean, intuitive interface that matches Formidable's design

### 🚀 Perfect For

* Contact form confirmations
* Order/booking notifications  
* User registration emails
* Support ticket acknowledgments
* Event registration confirmations
* Newsletter signup confirmations
* Lead generation follow-ups

### 📋 Requirements

* [Formidable Forms](https://formidableforms.com/) (v6.0 or higher) 
* [Loops.so](https://loops.so/) account with API access
* WordPress 5.0+
* PHP 7.4+

### 🛠 How It Works

1. **Install & Configure** - Add your Loops.so API key in WordPress settings
2. **Create Templates** - Design beautiful email templates in your Loops.so dashboard  
3. **Add Action** - Go to your form → Settings → Actions → Add "Loops.so Email"
4. **Configure & Test** - Set template ID, recipients, and triggers
5. **Go Live** - Your forms now send professional emails via Loops.so!

### 💡 Template Variables

The plugin automatically provides these variables in your Loops templates:

**System Variables:**
* `{{ trigger_type }}` - "create" or "update"  
* `{{ entry_id }}` - Unique entry identifier
* `{{ form_id }}` - Form identifier
* `{{ created_date }}` - Entry creation timestamp
* `{{ updated_date }}` - Last update timestamp

**Form Field Variables:**
All your form fields become template variables using their field IDs (e.g., `{{ 125 }}` for field ID 125).

### 🎯 Use Cases

**Contact Forms:**
```
Subject: New message from {{ name }}
Content: Hi there! {{ name }} ({{ email }}) sent: {{ message }}
```

**Order Confirmations:**  
```
Subject: Order #{{ order_id }} confirmed
Content: Thanks {{ first_name }}! Your order for {{ product }} is confirmed.
```

**Event Registrations:**
```
Subject: You're registered for {{ event_name }}!  
Content: Hi {{ name }}, you're all set for {{ event_name }} on {{ event_date }}.
```

== Installation ==

### Automatic Installation

1. Log in to your WordPress admin dashboard
2. Go to Plugins → Add New
3. Search for "Formidable Loops.so Integration"
4. Click "Install Now" and then "Activate"

### Manual Installation

1. Download the plugin ZIP file
2. Go to Plugins → Add New → Upload Plugin
3. Choose the ZIP file and click "Install Now"
4. Activate the plugin

### Setup

1. **Get Loops.so API Key:**
   - Log in to your [Loops.so dashboard](https://app.loops.so/)
   - Go to Settings → API Keys
   - Create or copy your API key

2. **Configure Plugin:**
   - In WordPress, go to Settings → Loops Integration
   - Enter your API key and save

3. **Create Email Templates:**
   - In Loops.so, go to Transactional → Templates
   - Create templates with your desired content and variables
   - Note the template IDs for use in forms

4. **Add to Forms:**
   - Edit any Formidable form
   - Go to Settings → Actions → Add → "Loops.so Email"
   - Configure template ID, recipients, and triggers
   - Save and test!

== Frequently Asked Questions ==

= Do I need a Loops.so account? =

Yes, you need an active Loops.so account with API access. Loops.so offers excellent deliverability, beautiful templates, and detailed analytics for your transactional emails.

= Can I use this with Formidable Lite (free version)? =

This plugin requires Formidable Forms Pro as it uses the form actions system which is not available in the free version.

= How do I find my template ID in Loops.so? =

In your Loops.so dashboard:
1. Go to Transactional → Templates
2. Click on your template to edit it
3. The template ID appears in the URL and template details

= Can I send to multiple recipients? =

Yes! You can:
- Use the CC field for additional fixed recipients
- Create multiple Loops actions with different recipients
- Send to form submitters and admin emails simultaneously

= What happens if the API request fails? =

The plugin includes error logging. Failed API requests are logged to your WordPress error logs. Form submission continues normally even if the email fails.

= Can I customize the email content? =

Email content is designed in your Loops.so dashboard using their template builder. The plugin provides the data variables that populate your templates.

= Does this work with conditional logic? =

Yes! Since this integrates with Formidable's action system, you can use conditional logic to control when emails are sent based on form field values.

== Screenshots ==

1. **Settings Page** - Configure your Loops.so API key
2. **Action Configuration** - Easy-to-use interface for setting up email actions  
3. **Template Variables** - See all available variables for your templates
4. **Form Actions List** - Loops actions appear alongside other Formidable actions
5. **Email Examples** - Beautiful emails sent via Loops.so

== Changelog ==

= 1.0.0 =
* Initial release
* Native Formidable Actions integration
* Dynamic template variables
* Flexible recipient options  
* Multiple trigger support (create/update/both)
* CC recipient functionality
* Professional admin interface
* Comprehensive error logging
* Translation ready

== Upgrade Notice ==

= 1.0.0 =
Initial release of Formidable Loops.so Integration. Requires Formidable Forms v6.0+ and a Loops.so account.

== Support ==

For support and documentation, please visit:
* [Plugin Documentation](https://yoursite.com/docs)
* [WordPress.org Support Forum](https://wordpress.org/support/plugin/formidable-loops-integration/)
* [Loops.so Documentation](https://loops.so/docs)

== Privacy ==

This plugin sends form submission data to Loops.so for email delivery. Please review Loops.so's privacy policy and ensure compliance with applicable privacy laws (GDPR, CCPA, etc.) when collecting and transmitting personal data.

Form data is transmitted securely via HTTPS to Loops.so's API. No data is stored locally by this plugin beyond WordPress's standard form entry storage in Formidable Forms.

== Credits ==

* Built for [Formidable Forms](https://formidableforms.com/)
* Integrates with [Loops.so](https://loops.so/)
* Follows WordPress coding standards