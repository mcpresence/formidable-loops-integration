<?php
/**
 * Loops.so Action Form View
 * This file creates the interface users see when configuring a Loops action
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current settings with defaults
$settings = wp_parse_args($form_action->post_content, array(
    'template_id' => '',
    'to_field' => '',
    'to_email' => '',
    'subject_line' => '',
    'cc_emails' => '',
    'event' => array('create')
));

// Get form fields for dropdown
$form_fields = FrmField::get_all_for_form($form->id);
$email_fields = array();
$all_fields = array();

foreach ($form_fields as $field) {
    $all_fields[] = $field;
    if ($field->type === 'email') {
        $email_fields[] = $field;
    }
}

// Check if API key is configured
$api_key = get_option('loops_api_key', '');
$api_configured = !empty($api_key);
?>

<table class="form-table frm-no-margin">
    <tbody>
        
        <?php if (!$api_configured): ?>
        <tr>
            <td colspan="2">
                <div class="frm_warning_style">
                    <p><strong><?php _e('API Key Required', 'formidable-loops'); ?></strong></p>
                    <p>
                        <?php 
                        printf(
                            __('Please configure your Loops.so API key in %sSettings → Loops Integration%s first.', 'formidable-loops'),
                            '<a href="' . admin_url('options-general.php?page=loops-integration') . '">',
                            '</a>'
                        ); 
                        ?>
                    </p>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        
        <!-- Template ID -->
        <tr>
            <td width="200">
                <label for="<?php echo esc_attr($action_control->get_field_id('template_id')); ?>">
                    <strong><?php _e('Loops Template ID', 'formidable-loops'); ?></strong>
                    <span class="frm_required">*</span>
                </label>
            </td>
            <td>
                <input type="text" 
                       id="<?php echo esc_attr($action_control->get_field_id('template_id')); ?>"
                       name="<?php echo esc_attr($action_control->get_field_name('template_id')); ?>" 
                       value="<?php echo esc_attr($settings['template_id']); ?>" 
                       class="large-text" 
                       placeholder="cm2a1b2c3d4e5f6g7h8i9j0k"
                       required />
                <p class="howto">
                    <?php _e('Enter your transactional template ID from Loops.so dashboard.', 'formidable-loops'); ?>
                    <a href="https://app.loops.so/transactional" target="_blank"><?php _e('Find your template ID →', 'formidable-loops'); ?></a>
                </p>
            </td>
        </tr>

        <!-- Recipient Email -->
        <tr>
            <td>
                <label><strong><?php _e('Send To', 'formidable-loops'); ?></strong> <span class="frm_required">*</span></label>
            </td>
            <td>
                <div style="margin-bottom: 15px;">
                    <p><strong><?php _e('Option 1: Use form field', 'formidable-loops'); ?></strong></p>
                    <select name="<?php echo esc_attr($action_control->get_field_name('to_field')); ?>" 
                            id="<?php echo esc_attr($action_control->get_field_id('to_field')); ?>"
                            class="frm_with_key">
                        <option value=""><?php _e('— Select Email Field —', 'formidable-loops'); ?></option>
                        
                        <?php if (!empty($email_fields)): ?>
                            <optgroup label="<?php _e('Email Fields', 'formidable-loops'); ?>">
                                <?php foreach ($email_fields as $field): ?>
                                    <option value="<?php echo esc_attr($field->id); ?>" 
                                            <?php selected($settings['to_field'], $field->id); ?>>
                                        <?php echo esc_html($field->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <optgroup label="<?php _e('All Fields', 'formidable-loops'); ?>">
                            <?php foreach ($all_fields as $field): ?>
                                <option value="<?php echo esc_attr($field->id); ?>" 
                                        <?php selected($settings['to_field'], $field->id); ?>>
                                    <?php echo esc_html($field->name . ' (' . $field->type . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
                
                <div>
                    <p><strong><?php _e('Option 2: Fixed email address', 'formidable-loops'); ?></strong></p>
                    <input type="email" 
                           name="<?php echo esc_attr($action_control->get_field_name('to_email')); ?>" 
                           id="<?php echo esc_attr($action_control->get_field_id('to_email')); ?>"
                           value="<?php echo esc_attr($settings['to_email']); ?>" 
                           class="large-text" 
                           placeholder="admin@example.com" />
                    <p class="howto"><?php _e('If a form field is selected above, this will be ignored.', 'formidable-loops'); ?></p>
                </div>
            </td>
        </tr>

        <!-- Dynamic Subject Line -->
        <tr>
            <td>
                <label for="<?php echo esc_attr($action_control->get_field_id('subject_line')); ?>">
                    <strong><?php _e('Dynamic Subject', 'formidable-loops'); ?></strong>
                </label>
            </td>
            <td>
                <input type="text" 
                       id="<?php echo esc_attr($action_control->get_field_id('subject_line')); ?>"
                       name="<?php echo esc_attr($action_control->get_field_name('subject_line')); ?>" 
                       value="<?php echo esc_attr($settings['subject_line']); ?>" 
                       class="large-text" 
                       placeholder="<?php _e('New submission from {{ name }}', 'formidable-loops'); ?>" />
                
                <p class="howto">
                    <?php _e('Use field variables like {{ field_id }}. Leave empty to use your template\'s default subject.', 'formidable-loops'); ?>
                    <br>
                    <strong><?php _e('Available variables:', 'formidable-loops'); ?></strong>
                    {{ trigger_type }}, {{ entry_id }}, {{ form_id }}, 
                    <?php foreach ($all_fields as $field): ?>
                        {{ <?php echo $field->id; ?> }}<span style="color: #999;"> (<?php echo esc_html($field->name); ?>)</span><?php if ($field !== end($all_fields)) echo ', '; ?>
                    <?php endforeach; ?>
                </p>
            </td>
        </tr>

        <!-- CC Recipients -->
        <tr>
            <td>
                <label for="<?php echo esc_attr($action_control->get_field_id('cc_emails')); ?>">
                    <strong><?php _e('CC Recipients', 'formidable-loops'); ?></strong>
                </label>
            </td>
            <td>
                <input type="text" 
                       id="<?php echo esc_attr($action_control->get_field_id('cc_emails')); ?>"
                       name="<?php echo esc_attr($action_control->get_field_name('cc_emails')); ?>" 
                       value="<?php echo esc_attr($settings['cc_emails']); ?>" 
                       class="large-text" 
                       placeholder="manager@example.com, support@example.com" />
                <p class="howto"><?php _e('Comma-separated list of additional email recipients (optional).', 'formidable-loops'); ?></p>
            </td>
        </tr>

        <!-- Trigger Events -->
        <tr>
            <td>
                <label><strong><?php _e('Send Email When', 'formidable-loops'); ?></strong></label>
            </td>
            <td>
                <p>
                    <label>
                        <input type="checkbox" 
                               name="<?php echo esc_attr($action_control->get_field_name('event')); ?>[]" 
                               value="create" 
                               <?php checked(in_array('create', (array)$settings['event'])); ?> />
                        <?php _e('Entry is created (form submission)', 'formidable-loops'); ?>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" 
                               name="<?php echo esc_attr($action_control->get_field_name('event')); ?>[]" 
                               value="update" 
                               <?php checked(in_array('update', (array)$settings['event'])); ?> />
                        <?php _e('Entry is updated (edit entry)', 'formidable-loops'); ?>
                    </label>
                </p>
                <p class="howto"><?php _e('Select when this email should be sent via Loops.so.', 'formidable-loops'); ?></p>
            </td>
        </tr>

        <!-- Test Email Section -->
        <tr>
            <td></td>
            <td>
                <div style="background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-top: 10px;">
                    <h4 style="margin-top: 0;"><?php _e('💡 Pro Tips', 'formidable-loops'); ?></h4>
                    <ul style="margin: 0;">
                        <li><?php _e('Create and test your email template in Loops.so before configuring this action.', 'formidable-loops'); ?></li>
                        <li><?php _e('Use descriptive template IDs to easily identify them later.', 'formidable-loops'); ?></li>
                        <li><?php _e('Test with a real form submission to ensure variables are working correctly.', 'formidable-loops'); ?></li>
                        <li><?php _e('Check your WordPress error logs if emails aren\'t being sent.', 'formidable-loops'); ?></li>
                    </ul>
                </div>
            </td>
        </tr>

    </tbody>
</table>

<script>
jQuery(document).ready(function($) {
    // Show/hide fixed email field based on field selection
    function toggleEmailFields() {
        var fieldSelected = $('#<?php echo esc_js($action_control->get_field_id('to_field')); ?>').val();
        var fixedEmailField = $('#<?php echo esc_js($action_control->get_field_id('to_email')); ?>');
        
        if (fieldSelected) {
            fixedEmailField.attr('disabled', true).css('opacity', 0.5);
        } else {
            fixedEmailField.attr('disabled', false).css('opacity', 1);
        }
    }
    
    $('#<?php echo esc_js($action_control->get_field_id('to_field')); ?>').change(toggleEmailFields);
    toggleEmailFields(); // Run on page load
    
    // Ensure at least one trigger is selected
    $('input[name="<?php echo esc_js($action_control->get_field_name('event')); ?>[]"]').change(function() {
        var checked = $('input[name="<?php echo esc_js($action_control->get_field_name('event')); ?>[]"]:checked').length;
        if (checked === 0) {
            $(this).prop('checked', true);
            alert('<?php _e('At least one trigger must be selected.', 'formidable-loops'); ?>');
        }
    });
});
</script>