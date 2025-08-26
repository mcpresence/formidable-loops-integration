<?php
/**
 * Plugin Name: Formidable Loops.so Add-On
 * Description: Send transactional emails through Loops.so API when Formidable Forms are submitted
 * Version: 1.0.0
 * Author: Presence Platform
 * Author URI: https://presenceplatform.io
 * Text Domain: formidable-loops
 * Domain Path: /languages
 * Requires Plugins: formidable
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FORMIDABLE_LOOPS_VERSION', '1.0.0');
define('FORMIDABLE_LOOPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FORMIDABLE_LOOPS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Formidable Loops Add-On Class
 */
class FormidableLoopsAddon {
    
    public function __construct() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'load_addon'));
        add_action('admin_notices', array($this, 'admin_notices'));
    }
    
    /**
     * Load the add-on after plugins are loaded
     */
    public function load_addon() {
        if (!$this->check_dependencies()) {
            return;
        }
        
        // Load text domain
        load_plugin_textdomain('formidable-loops', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialize the add-on only after dependencies are confirmed
        add_action('init', array($this, 'init_integration'), 20);
    }
    
    /**
     * Initialize integration after WordPress and plugins are fully loaded
     */
    public function init_integration() {
        // Double-check that Formidable is still available
        if (class_exists('FrmForm') && class_exists('FrmFormAction')) {
            new FormidableLoopsIntegration();
        }
    }
    
    /**
     * Check if required dependencies are active
     */
    private function check_dependencies() {
        // Check if Formidable Forms is active
        if (!class_exists('FrmForm')) {
            return false;
        }
        
        // Check for minimum Formidable version
        if (defined('FRM_VERSION') && version_compare(FRM_VERSION, '6.0', '<')) {
            return false;
        }
        
        // Check if we have the required action class (Pro feature)
        if (!class_exists('FrmFormAction')) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Show admin notices for missing dependencies
     */
    public function admin_notices() {
        if (!class_exists('FrmForm')) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>Formidable Loops.so Add-On</strong> requires Formidable Forms to be installed and activated.</p>';
            echo '<p><a href="' . admin_url('plugin-install.php?s=formidable+forms&tab=search&type=term') . '" class="button">Install Formidable Forms</a></p>';
            echo '</div>';
            return;
        }
        
        if (!class_exists('FrmFormAction')) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>Formidable Loops.so Add-On</strong> requires Formidable Forms Pro to use form actions.</p>';
            echo '<p><a href="https://formidableforms.com/pricing/" target="_blank" class="button">Upgrade to Formidable Pro</a></p>';
            echo '</div>';
            return;
        }
        
        if (defined('FRM_VERSION') && version_compare(FRM_VERSION, '6.0', '<')) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>Formidable Loops.so Add-On</strong> requires Formidable Forms v6.0 or higher. You have v' . FRM_VERSION . '.</p>';
            echo '<p><a href="' . admin_url('plugins.php') . '" class="button">Update Formidable Forms</a></p>';
            echo '</div>';
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        if (!$this->check_dependencies()) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(__('Formidable Loops.so Add-On requires Formidable Forms to be installed and activated.', 'formidable-loops'));
        }
        
        // Set default options
        if (!get_option('loops_api_key')) {
            add_option('loops_api_key', '');
        }
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Cleanup if needed
    }
}

/**
 * Core Integration Class
 */
class FormidableLoopsIntegration {
    
    private $api_key;
    private $api_url = 'https://app.loops.so/api/v1/transactional';
    
    public function __construct() {
        $this->api_key = get_option('loops_api_key', '');
        
        // Wait for Formidable to load before registering our action
        add_action('frm_registered_form_actions', array($this, 'register_loops_action'));
        
        // Admin interface
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        
        // Handle form submissions
        add_action('frm_after_create_entry', array($this, 'handle_form_create'), 30, 2);
        add_action('frm_after_update_entry', array($this, 'handle_form_update'), 30, 2);
    }
    
    /**
     * Register Loops action with Formidable (called after Formidable loads)
     */
    public function register_loops_action() {
        // Only register if FrmFormAction class exists
        if (class_exists('FrmFormAction')) {
            new FormidableLoopsAction();
        }
    }
    
    /**
     * Handle Formidable Form creation
     */
    public function handle_form_create($entry_id, $form_id) {
        $this->process_form_actions($entry_id, $form_id, 'create');
    }
    
    /**
     * Handle Formidable Form update
     */
    public function handle_form_update($entry_id, $form_id) {
        $this->process_form_actions($entry_id, $form_id, 'update');
    }
    
    /**
     * Process form actions for Loops
     */
    private function process_form_actions($entry_id, $form_id, $trigger) {
        if (!$this->api_key) {
            return;
        }
        
        // Get all Loops actions for this form
        $actions = FrmFormAction::get_action_for_form($form_id, 'loops');
        
        foreach ($actions as $action) {
            if ($this->should_trigger_action($action, $trigger)) {
                $this->send_loops_email($entry_id, $form_id, $action, $trigger);
            }
        }
    }
    
    /**
     * Check if action should be triggered
     */
    private function should_trigger_action($action, $trigger) {
        $action_trigger = isset($action->post_content['event']) ? $action->post_content['event'] : array('create');
        
        if (is_string($action_trigger)) {
            $action_trigger = array($action_trigger);
        }
        
        return in_array($trigger, $action_trigger) || in_array('both', $action_trigger);
    }
    
    /**
     * Send email via Loops API
     */
    private function send_loops_email($entry_id, $form_id, $action, $trigger) {
        $entry = FrmEntry::getOne($entry_id);
        if (!$entry) {
            return false;
        }
        
        $settings = $action->post_content;
        $form_data = FrmEntry::get_field_values($entry_id);
        
        // Prepare email data
        $email_data = $this->prepare_email_data($form_data, $settings, $entry, $trigger);
        
        if (!$email_data) {
            return false;
        }
        
        // Send via Loops API
        $response = wp_remote_post($this->api_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($email_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            error_log('Loops API Error: ' . $response->get_error_message());
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            $response_body = wp_remote_retrieve_body($response);
            error_log("Loops API Error: HTTP {$response_code} - {$response_body}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Prepare email data for Loops API
     */
    private function prepare_email_data($form_data, $settings, $entry, $trigger) {
        // Get recipient email
        $to_email = $this->get_recipient_email($form_data, $settings);
        
        if (!$to_email || !is_email($to_email)) {
            error_log('Loops Integration: Invalid recipient email');
            return false;
        }
        
        // Prepare template variables
        $template_vars = array(
            'trigger_type' => $trigger,
            'entry_id' => $entry->id,
            'entry_key' => $entry->item_key,
            'form_id' => $entry->form_id,
            'created_date' => $entry->created_at,
            'updated_date' => $entry->updated_at
        );
        
        // Add form field data
        foreach ($form_data as $field_key => $value) {
            $var_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $field_key);
            $template_vars[$var_name] = is_array($value) ? implode(', ', $value) : $value;
        }
        
        $email_data = array(
            'transactionalId' => $settings['template_id'] ?? '',
            'email' => $to_email,
            'dataVariables' => $template_vars
        );
        
        // Add dynamic subject if configured
        if (!empty($settings['subject_line'])) {
            $subject = $this->replace_variables($settings['subject_line'], $template_vars);
            $email_data['dataVariables']['subject'] = $subject;
        }
        
        return $email_data;
    }
    
    /**
     * Get recipient email from form data or settings
     */
    private function get_recipient_email($form_data, $settings) {
        if (!empty($settings['to_field']) && isset($form_data[$settings['to_field']])) {
            return $form_data[$settings['to_field']];
        }
        
        return $settings['to_email'] ?? '';
    }
    
    /**
     * Replace variables in string with form data
     */
    private function replace_variables($string, $data) {
        foreach ($data as $key => $value) {
            $string = str_replace("{{ {$key} }}", $value, $string);
            $string = str_replace("{{{$key}}}", $value, $string);
        }
        return $string;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Loops.so Integration', 'formidable-loops'),
            __('Loops Integration', 'formidable-loops'),
            'manage_options',
            'loops-integration',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Initialize settings
     */
    public function settings_init() {
        register_setting('loops_settings', 'loops_api_key');
        
        add_settings_section(
            'loops_settings_section',
            __('Loops.so API Configuration', 'formidable-loops'),
            null,
            'loops_settings'
        );
        
        add_settings_field(
            'loops_api_key',
            __('API Key', 'formidable-loops'),
            array($this, 'api_key_field'),
            'loops_settings',
            'loops_settings_section'
        );
    }
    
    /**
     * API Key field callback
     */
    public function api_key_field() {
        $value = get_option('loops_api_key', '');
        echo '<input type="password" name="loops_api_key" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('Enter your Loops.so API key', 'formidable-loops') . '</p>';
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Loops.so Integration Settings', 'formidable-loops'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('loops_settings');
                do_settings_sections('loops_settings');
                submit_button();
                ?>
            </form>
            
            <div class="card">
                <h2><?php _e('How to Use This Add-On', 'formidable-loops'); ?></h2>
                <ol>
                    <li><?php _e('Enter your Loops.so API key above and save', 'formidable-loops'); ?></li>
                    <li><?php _e('Create transactional email templates in your Loops.so dashboard', 'formidable-loops'); ?></li>
                    <li><?php _e('Go to your form → Settings → Actions → Add "Loops.so Email" action', 'formidable-loops'); ?></li>
                    <li><?php _e('Configure the template ID and recipient settings', 'formidable-loops'); ?></li>
                    <li><?php _e('Test your form to ensure emails are sent via Loops', 'formidable-loops'); ?></li>
                </ol>
                
                <h3><?php _e('Template Variables', 'formidable-loops'); ?></h3>
                <p><?php _e('The following system variables are always available in your Loops templates:', 'formidable-loops'); ?></p>
                <ul>
                    <li><code>{{ trigger_type }}</code> - <?php _e('Will be "create" or "update"', 'formidable-loops'); ?></li>
                    <li><code>{{ entry_id }}</code> - <?php _e('The unique entry ID', 'formidable-loops'); ?></li>
                    <li><code>{{ entry_key }}</code> - <?php _e('The unique entry key', 'formidable-loops'); ?></li>
                    <li><code>{{ form_id }}</code> - <?php _e('The form ID', 'formidable-loops'); ?></li>
                    <li><code>{{ created_date }}</code> - <?php _e('Entry creation date', 'formidable-loops'); ?></li>
                    <li><code>{{ updated_date }}</code> - <?php _e('Entry last update date', 'formidable-loops'); ?></li>
                </ul>
                <p><?php _e('Plus all your form fields will be available as variables using their field IDs.', 'formidable-loops'); ?></p>
            </div>
        </div>
        <?php
    }
}

/**
 * Formidable Loops Action Class
 * Only instantiated after Formidable Forms is loaded
 */
class FormidableLoopsAction extends FrmFormAction {
    
    public function __construct() {
        // Verify parent class exists before calling parent constructor
        if (!class_exists('FrmFormAction')) {
            return;
        }
        
        $action_ops = array(
            'classes' => 'frm_email_icon frm_icon_font',
            'limit' => 99,
            'active' => true,
            'priority' => 30,
            'event' => array('create', 'update')
        );
        
        $this->FrmFormAction('loops', __('Loops.so Email', 'formidable-loops'), $action_ops);
    }
    
    /**
     * Add to action dropdown
     */
    public function add_to_dropdown($actions) {
        $actions['loops'] = __('Loops.so Email', 'formidable-loops');
        return $actions;
    }
    
    /**
     * Get default settings for the action
     */
    public function get_defaults() {
        return array(
            'template_id' => '',
            'to_field' => '',
            'to_email' => '',
            'subject_line' => '',
            'cc_emails' => '',
            'event' => array('create')
        );
    }
    
    /**
     * Show action form in admin
     */
    public function form($form_action, $args = array()) {
        $form_id = $args['form']->id;
        $action_control = $this;
        $form = $args['form'];
        
        $view_file = FORMIDABLE_LOOPS_PLUGIN_DIR . 'views/loops-action-form.php';
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            // Fallback inline form if view file doesn't exist
            $this->inline_form($form_action, $args);
        }
    }
    
    /**
     * Fallback inline form
     */
    private function inline_form($form_action, $args) {
        $form = $args['form'];
        $settings = wp_parse_args($form_action->post_content, $this->get_defaults());
        ?>
        <table class="form-table">
            <tr>
                <td><label><?php _e('Template ID', 'formidable-loops'); ?></label></td>
                <td>
                    <input type="text" name="<?php echo esc_attr($this->get_field_name('template_id')); ?>" 
                           value="<?php echo esc_attr($settings['template_id']); ?>" class="large-text" />
                </td>
            </tr>
            <tr>
                <td><label><?php _e('Send To Email', 'formidable-loops'); ?></label></td>
                <td>
                    <input type="email" name="<?php echo esc_attr($this->get_field_name('to_email')); ?>" 
                           value="<?php echo esc_attr($settings['to_email']); ?>" class="large-text" />
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Execute the action
     */
    public function trigger($action, $entry, $form) {
        $api_key = get_option('loops_api_key', '');
        if (!$api_key) {
            return;
        }
        
        $settings = $action->post_content;
        $form_data = FrmEntry::get_field_values($entry->id);
        
        // Determine trigger type
        $trigger = did_action('frm_after_update_entry') ? 'update' : 'create';
        
        // Prepare and send email
        $this->send_loops_email($entry, $form_data, $settings, $trigger);
    }
    
    /**
     * Send email via Loops API
     */
    private function send_loops_email($entry, $form_data, $settings, $trigger) {
        $api_key = get_option('loops_api_key', '');
        $api_url = 'https://app.loops.so/api/v1/transactional';
        
        // Get recipient
        $to_email = $this->get_recipient_email($form_data, $settings);
        if (!$to_email || !is_email($to_email)) {
            return false;
        }
        
        // Prepare variables
        $template_vars = array(
            'trigger_type' => $trigger,
            'entry_id' => $entry->id,
            'entry_key' => $entry->item_key,
            'form_id' => $entry->form_id,
            'created_date' => $entry->created_at,
            'updated_date' => $entry->updated_at
        );
        
        foreach ($form_data as $field_key => $value) {
            $var_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $field_key);
            $template_vars[$var_name] = is_array($value) ? implode(', ', $value) : $value;
        }
        
        $email_data = array(
            'transactionalId' => $settings['template_id'],
            'email' => $to_email,
            'dataVariables' => $template_vars
        );
        
        // Add dynamic subject
        if (!empty($settings['subject_line'])) {
            $subject = $this->replace_variables($settings['subject_line'], $template_vars);
            $email_data['dataVariables']['subject'] = $subject;
        }
        
        // Send request
        $response = wp_remote_post($api_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($email_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            error_log('Loops API Error: ' . $response->get_error_message());
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            $response_body = wp_remote_retrieve_body($response);
            error_log("Loops API Error: HTTP {$response_code} - {$response_body}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Get recipient email
     */
    private function get_recipient_email($form_data, $settings) {
        if (!empty($settings['to_field']) && isset($form_data[$settings['to_field']])) {
            return $form_data[$settings['to_field']];
        }
        return $settings['to_email'] ?? '';
    }
    
    /**
     * Replace variables in string
     */
    private function replace_variables($string, $data) {
        foreach ($data as $key => $value) {
            $string = str_replace("{{ {$key} }}", $value, $string);
            $string = str_replace("{{{$key}}}", $value, $string);
        }
        return $string;
    }
}

// Initialize the add-on
new FormidableLoopsAddon();

// Create action form view file content (would be in separate file)
if (!function_exists('formidable_loops_action_view')) {
    function formidable_loops_action_view() {
        return '
<tr>
    <td>
        <label for="<?php echo esc_attr($action_control->get_field_id(\'template_id\')); ?>">
            <?php _e(\'Loops Template ID\', \'formidable-loops\'); ?>
        </label>
    </td>
    <td>
        <input type="text" 
               name="<?php echo esc_attr($action_control->get_field_name(\'template_id\')); ?>" 
               value="<?php echo esc_attr($form_action->post_content[\'template_id\'] ?? \'\'); ?>" 
               class="large-text" />
        <p class="howto"><?php _e(\'Enter your transactional template ID from Loops.so\', \'formidable-loops\'); ?></p>
    </td>
</tr>

<tr>
    <td>
        <label for="<?php echo esc_attr($action_control->get_field_id(\'to_email\')); ?>">
            <?php _e(\'Send To\', \'formidable-loops\'); ?>
        </label>
    </td>
    <td>
        <p><strong><?php _e(\'Option 1: Use form field\', \'formidable-loops\'); ?></strong></p>
        <select name="<?php echo esc_attr($action_control->get_field_name(\'to_field\')); ?>">
            <option value=""><?php _e(\'Select a field...\', \'formidable-loops\'); ?></option>
            <?php foreach ($form->fields as $field): ?>
                <option value="<?php echo esc_attr($field->id); ?>" 
                        <?php selected($form_action->post_content[\'to_field\'] ?? \'\', $field->id); ?>>
                    <?php echo esc_html($field->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <p><strong><?php _e(\'Option 2: Fixed email address\', \'formidable-loops\'); ?></strong></p>
        <input type="email" 
               name="<?php echo esc_attr($action_control->get_field_name(\'to_email\')); ?>" 
               value="<?php echo esc_attr($form_action->post_content[\'to_email\'] ?? \'\'); ?>" 
               class="large-text" />
    </td>
</tr>

<tr>
    <td>
        <label for="<?php echo esc_attr($action_control->get_field_id(\'subject_line\')); ?>">
            <?php _e(\'Dynamic Subject Line\', \'formidable-loops\'); ?>
        </label>
    </td>
    <td>
        <input type="text" 
               name="<?php echo esc_attr($action_control->get_field_name(\'subject_line\')); ?>" 
               value="<?php echo esc_attr($form_action->post_content[\'subject_line\'] ?? \'\'); ?>" 
               class="large-text" 
               placeholder="<?php _e(\'New submission from {{ name }}\', \'formidable-loops\'); ?>" />
        <p class="howto"><?php _e(\'Use {{ field_id }} variables. Leave empty to use template default.\', \'formidable-loops\'); ?></p>
    </td>
</tr>

<tr>
    <td>
        <label for="<?php echo esc_attr($action_control->get_field_id(\'cc_emails\')); ?>">
            <?php _e(\'CC Emails\', \'formidable-loops\'); ?>
        </label>
    </td>
    <td>
        <input type="text" 
               name="<?php echo esc_attr($action_control->get_field_name(\'cc_emails\')); ?>" 
               value="<?php echo esc_attr($form_action->post_content[\'cc_emails\'] ?? \'\'); ?>" 
               class="large-text" 
               placeholder="<?php _e(\'admin@example.com, manager@example.com\', \'formidable-loops\'); ?>" />
        <p class="howto"><?php _e(\'Comma-separated list of additional recipients\', \'formidable-loops\'); ?></p>
    </td>
</tr>';
    }
}
?>