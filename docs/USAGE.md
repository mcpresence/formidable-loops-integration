# 📖 Usage Guide

## 🚀 Quick Start

Once the plugin is installed and configured, here's how to start sending beautiful emails:

### 1. Create Email Template in Loops.so

1. **Access Loops.so Dashboard**
   - Log in to [app.loops.so](https://app.loops.so)
   - Navigate to **Transactional → Templates**

2. **Create New Template**
   - Click **Create Template**
   - Choose **Transactional** template type
   - Give it a descriptive name (e.g., "Contact Form Confirmation")

3. **Design Your Email**
   ```html
   <h2>Thank you for contacting us!</h2>
   
   <p>Hi {{ name }},</p>
   
   <p>We received your message and will respond within 24 hours.</p>
   
   <p><strong>Your message:</strong></p>
   <blockquote>{{ message }}</blockquote>
   
   <p>Best regards,<br>The Team</p>
   ```

4. **Save and Note Template ID**
   - Save the template
   - Copy the template ID (e.g., `cm2a1b2c3d4e5f6g7h8i9j0k`)

### 2. Configure Formidable Form

1. **Edit Your Form**
   - Go to **Formidable → Forms**
   - Edit an existing form or create a new one

2. **Add Loops Action**
   - Click **Settings** tab
   - Go to **Actions** section
   - Click **Add** → Select **"Loops.so Email"**

3. **Configure Action Settings**
   - **Template ID**: Enter your Loops.so template ID
   - **Send To**: Choose form email field or enter fixed email
   - **Subject**: Optional dynamic subject line
   - **Triggers**: Select when to send (create/update/both)

4. **Save and Test**
   - Save the form settings
   - Submit a test entry to verify emails are sent

## ⚙️ Configuration Options

### Template ID
- **Required**: Yes
- **Format**: Alphanumeric string (e.g., `cm2a1b2c3d4e5f6g7h8i9j0k`)
- **Where to find**: Loops.so dashboard → Transactional → Templates
- **Example**: `cm2a1b2c3d4e5f6g7h8i9j0k`

### Recipient Options

#### Option 1: Use Form Field (Recommended)
```
Send To Field: [Select Email Field]
```
- Choose any email field from your form
- Plugin will send to the email address entered by the user
- Best for confirmations and user notifications

#### Option 2: Fixed Email Address
```
Send To Email: admin@example.com
```
- Send to a specific email address every time
- Best for admin notifications
- Can be used alongside form field option

#### Option 3: CC Recipients
```
CC Emails: manager@example.com, support@example.com
```
- Send copies to additional recipients
- Comma-separated list of email addresses
- Useful for team notifications

### Subject Line Configuration

#### Static Subject (Set in Loops.so)
- Configure subject in your Loops.so template
- Same subject for all emails from this template

#### Dynamic Subject (Plugin Setting)
```
Subject: New message from {{ 124 }} about {{ 126 }}
```
- Use form field variables in subject line
- Variables format: `{{ field_id }}`
- Overrides template's default subject

### Trigger Options

#### Send on Create
- ✅ **Entry is created (form submission)**
- ❌ Entry is updated (edit entry)
- Best for: New form submissions, initial confirmations

#### Send on Update  
- ❌ Entry is created (form submission)
- ✅ **Entry is updated (edit entry)**
- Best for: Status updates, edit notifications

#### Send on Both
- ✅ **Entry is created (form submission)**
- ✅ **Entry is updated (edit entry)**
- Best for: Comprehensive tracking, audit trails

## 🔤 Template Variables

### System Variables (Always Available)

| Variable | Description | Example Value |
|----------|-------------|---------------|
| `{{ trigger_type }}` | Action that triggered email | "create" or "update" |
| `{{ entry_id }}` | Unique entry identifier | "1234" |
| `{{ entry_key }}` | Unique entry key | "abc-123-def" |
| `{{ form_id }}` | Form identifier | "5" |
| `{{ created_date }}` | Entry creation timestamp | "2025-08-25 14:30:00" |
| `{{ updated_date }}` | Last update timestamp | "2025-08-25 15:45:00" |

### Form Field Variables

Use `{{ field_id }}` where `field_id` is the numeric ID from Formidable:

| Field Type | Variable Example | Usage |
|------------|------------------|--------|
| Single Line Text | `{{ 124 }}` | Names, titles, short text |
| Email Address | `{{ 125 }}` | Email addresses |
| Paragraph Text | `{{ 126 }}` | Messages, descriptions |
| Phone Number | `{{ 127 }}` | Phone numbers |
| Dropdown | `{{ 128 }}` | Selected option |
| Radio Buttons | `{{ 129 }}` | Selected choice |
| Checkboxes | `{{ 130 }}` | Comma-separated selections |
| File Upload | `{{ 131 }}` | File name or URL |

### Finding Field IDs

1. **Form Builder Method**
   - Edit your form in Formidable
   - Click on any field
   - Look for "Field ID" in field settings

2. **Source Code Method**
   - View form on frontend
   - Inspect element → look for `name="item_meta[124]"`
   - The number (124) is your field ID

## 📋 Common Use Cases

### Contact Form Confirmation

**Form Fields:**
- Name (ID: 124)
- Email (ID: 125) 
- Subject (ID: 126)
- Message (ID: 127)

**Loops Template:**
```html
<h2>Thanks for contacting us, {{ 124 }}!</h2>

<p>We received your message about "{{ 126 }}" and will respond within 24 hours.</p>

<div style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
    <strong>Your message:</strong><br>
    {{ 127 }}
</div>

<p>We'll reply to: {{ 125 }}</p>
```

**Action Settings:**
- Template ID: `cm2contact123form456`
- Send To: Field 125 (Email)
- Subject: `Re: {{ 126 }}`
- Trigger: Create

### Order Confirmation

**Form Fields:**
- Customer Name (ID: 201)
- Email (ID: 202)
- Product (ID: 203)  
- Quantity (ID: 204)
- Total (ID: 205)

**Loops Template:**
```html
<h2>Order Confirmed!</h2>

<p>Hi {{ 201 }},</p>

<p>Your order has been confirmed. Here are the details:</p>

<table style="border-collapse: collapse; width: 100%;">
  <tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Product:</strong></td>
    <td style="border: 1px solid #ddd; padding: 8px;">{{ 203 }}</td>
  </tr>
  <tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Quantity:</strong></td>
    <td style="border: 1px solid #ddd; padding: 8px;">{{ 204 }}</td>
  </tr>
  <tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Total:</strong></td>
    <td style="border: 1px solid #ddd; padding: 8px;">${{ 205 }}</td>
  </tr>
</table>

<p>Order ID: {{ entry_id }}</p>
```

### Event Registration

**Form Fields:**
- First Name (ID: 301)
- Last Name (ID: 302)
- Email (ID: 303)
- Event (ID: 304)
- Ticket Type (ID: 305)

**Dynamic Subject:**
```
You're registered for {{ 304 }}!
```

**Loops Template:**
```html
<h2>Registration Confirmed 🎉</h2>

<p>Hi {{ 301 }},</p>

<p>You're all set for {{ 304 }}!</p>

<div style="background: #e8f5e8; padding: 15px; border-radius: 5px;">
    <strong>Registration Details:</strong><br>
    Name: {{ 301 }} {{ 302 }}<br>
    Event: {{ 304 }}<br>
    Ticket: {{ 305 }}<br>
    Registration ID: {{ entry_id }}
</div>

<p>We'll send event details closer to the date.</p>
```

### Support Ticket

**Action 1: Customer Confirmation**
- Send To: Field (customer email)
- Template: Customer confirmation template

**Action 2: Admin Notification**  
- Send To: Fixed email (support@company.com)
- Template: Admin alert template
- CC: manager@company.com

## 🔍 Testing and Debugging

### Test Your Setup

1. **Create Test Form**
   - Simple form with name, email, message
   - Add Loops action with test template

2. **Submit Test Entry**
   - Fill out form with your email
   - Submit and check for email delivery

3. **Verify Variables**
   - Check that form data appears correctly in email
   - Ensure subject line populates if dynamic

### Debug Common Issues

#### Email Not Sending
```php
// Check error logs
tail -f /wp-content/debug.log

// Look for Loops API errors
grep "Loops API Error" /wp-content/debug.log
```

#### Variables Not Populating
- Verify field IDs are correct
- Check template syntax in Loops.so
- Ensure fields have values when testing

#### API Connection Issues
- Verify API key is correct
- Check HTTPS is enabled
- Test API key directly in Loops.so dashboard

### Enable Debug Logging

Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## 🚀 Advanced Features

### Multiple Actions per Form

You can add multiple Loops actions to one form:

1. **Customer Confirmation**
   - Send to form email field
   - Template: Thank you message

2. **Admin Notification**
   - Send to admin email
   - Template: New submission alert

3. **Manager Alert** (for high-value inquiries)
   - Conditional logic: if budget > $10,000
   - Send to manager email
   - Template: High-value lead alert

### Conditional Logic Integration

Use Formidable's conditional logic with Loops actions:

```
IF budget_field > 10000
THEN send "High Value Lead" template to sales team
ELSE send standard confirmation
```

### Custom Field Mapping

For complex forms, create template variables that match your needs:

**Form Fields:**
- field_124 = first_name  
- field_125 = last_name
- field_126 = company
- field_127 = phone

**In Loops Template:**
```html
<p>Contact: {{ 124 }} {{ 125 }}</p>
<p>Company: {{ 126 }}</p>  
<p>Phone: {{ 127 }}</p>
```

## 📊 Best Practices

### Template Design
- Keep templates clean and mobile-friendly
- Use consistent branding and colors
- Test across different email clients
- Include clear call-to-action buttons

### Variable Usage
- Always provide fallback text for optional fields
- Validate data before sending to Loops.so
- Use descriptive template names and IDs

### Performance
- Monitor API usage in Loops.so dashboard
- Set up proper error handling
- Test with high-volume forms

### Security
- Keep API keys secure
- Don't include sensitive data in templates
- Validate all form inputs

---

**🎯 Ready to create amazing email experiences with your Formidable Forms!**

Need help? Check out [troubleshooting tips](INSTALLATION.md#troubleshooting) or [create an issue](https://github.com/yourusername/formidable-loops-integration/issues) for support.