# 📦 Installation Guide

## 🔧 Requirements

Before installing the Formidable Loops.so Integration plugin, ensure you have:

- **WordPress** 5.0 or higher
- **Formidable Forms Pro** 6.0 or higher
- **Loops.so account** with API access
- **PHP** 7.4 or higher
- **HTTPS** enabled (required for Loops.so API)

## 🚀 Installation Methods

### Method 1: WordPress Admin (Recommended)

1. **Download the Plugin**
   - Go to [GitHub Releases](https://github.com/yourusername/formidable-loops-integration/releases)
   - Download the latest `formidable-loops-integration.zip` file

2. **Upload to WordPress**
   - Log in to your WordPress admin dashboard
   - Navigate to **Plugins → Add New**
   - Click **Upload Plugin** button
   - Choose the downloaded ZIP file
   - Click **Install Now**

3. **Activate the Plugin**
   - Click **Activate Plugin** after installation completes
   - You'll see a success message confirming activation

### Method 2: Manual FTP Installation

1. **Download and Extract**
   - Download the plugin ZIP file
   - Extract the ZIP to get the `formidable-loops-integration` folder

2. **Upload via FTP**
   - Connect to your website via FTP
   - Navigate to `/wp-content/plugins/`
   - Upload the entire `formidable-loops-integration` folder

3. **Activate in WordPress**
   - Go to **Plugins → Installed Plugins**
   - Find "Formidable Loops.so Integration"
   - Click **Activate**

### Method 3: Git Clone (Developers)

```bash
# Navigate to plugins directory
cd /path/to/wordpress/wp-content/plugins/

# Clone the repository
git clone https://github.com/yourusername/formidable-loops-integration.git

# Activate via WordPress admin or WP-CLI
wp plugin activate formidable-loops-integration
```

## 🔑 Initial Configuration

### Step 1: Get Loops.so API Key

1. **Log in to Loops.so**
   - Visit [app.loops.so](https://app.loops.so)
   - Sign in to your account

2. **Access API Settings**
   - Navigate to **Settings → API**
   - Click **Create API Key** (if you don't have one)

3. **Copy API Key**
   - Copy the API key (starts with `cl_`)
   - Keep this secure - don't share publicly

### Step 2: Configure Plugin Settings

1. **Access Plugin Settings**
   - In WordPress admin, go to **Settings → Loops Integration**
   - You'll see the API configuration page

2. **Enter API Key**
   - Paste your Loops.so API key in the "API Key" field
   - Click **Save Changes**
   - You should see a success message

3. **Verify Connection**
   - The plugin will test the API key automatically
   - Look for a green checkmark or success message

### Step 3: Create Email Templates

1. **Design Templates in Loops.so**
   - Go to **Transactional → Templates** in Loops.so
   - Click **Create Template**
   - Design your email with HTML/text content

2. **Add Template Variables**
   - Use variables like `{{ name }}`, `{{ email }}`, `{{ message }}`
   - These will be populated from form submissions

3. **Note Template ID**
   - After saving, copy the template ID (e.g., `cm2a1b2c3d4e5f6g7h8i9j0k`)
   - You'll need this when configuring forms

## ✅ Verification Checklist

After installation, verify everything is working:

### Plugin Status
- [ ] Plugin appears in **Plugins → Installed Plugins**
- [ ] Status shows "Active"
- [ ] No error messages in WordPress admin
- [ ] Settings page accessible at **Settings → Loops Integration**

### API Connection
- [ ] API key saved successfully
- [ ] No connection errors displayed
- [ ] Test API call succeeds (check error logs if unsure)

### Formidable Integration
- [ ] Formidable Forms Pro is active
- [ ] "Loops.so Email" appears in form actions list
- [ ] Action configuration form loads without errors

### Template Setup
- [ ] At least one template created in Loops.so
- [ ] Template ID noted and ready to use
- [ ] Template includes necessary variables

## 🔧 Troubleshooting

### Common Installation Issues

**Plugin Won't Activate**
- Check WordPress and PHP versions meet requirements
- Ensure Formidable Forms Pro is installed and active
- Look for PHP errors in debug log

**API Key Not Saving**
- Verify API key format (should start with `cl_`)
- Check if HTTPS is enabled on your site
- Ensure no caching plugins are interfering

**Settings Page Not Loading**
- Check for plugin conflicts by deactivating other plugins temporarily
- Verify user has admin permissions
- Clear any caching

### Debug Information

Enable WordPress debug logging to troubleshoot issues:

```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs at: `/wp-content/debug.log`

### Getting Help

If you encounter issues:

1. **Check Documentation**
   - Read the [Usage Guide](USAGE.md)
   - Review [Troubleshooting section](#troubleshooting)

2. **Search Existing Issues**
   - Visit [GitHub Issues](https://github.com/yourusername/formidable-loops-integration/issues)
   - Search for similar problems

3. **Report New Issues**
   - Create a detailed bug report
   - Include WordPress/PHP/plugin versions
   - Provide error messages and logs

4. **Community Support**
   - Join [GitHub Discussions](https://github.com/yourusername/formidable-loops-integration/discussions)
   - Ask questions in WordPress forums

## 🎯 Next Steps

After successful installation:

1. **Configure Your First Form**
   - Edit a Formidable form
   - Add a "Loops.so Email" action
   - Set up template ID and recipients

2. **Test Email Delivery**
   - Submit a test form entry
   - Check if email is received
   - Verify variables are populated correctly

3. **Monitor Performance**
   - Check error logs regularly
   - Monitor email delivery rates in Loops.so
   - Optimize based on usage patterns

## 🔄 Updates

### Automatic Updates
- Enable automatic updates in WordPress admin
- Plugin will update automatically when new versions are released

### Manual Updates
- Download new version from GitHub
- Upload and activate like initial installation
- Settings and configurations are preserved

### Update Notifications
- Watch the GitHub repository for release notifications
- Subscribe to plugin announcements
- Check changelog for new features and fixes

---

**🎉 Congratulations! Your Formidable Loops.so Integration is now installed and ready to send beautiful emails!**

Next: [Read the Usage Guide](USAGE.md) to learn how to configure your forms and create amazing email experiences.