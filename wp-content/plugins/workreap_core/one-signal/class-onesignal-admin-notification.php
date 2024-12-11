<?php

/**
 * Notification from admin side
 */

if (!class_exists('OneSignal_Admin_Notification')) {
    class OneSignal_Admin_Notification
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_onesignal_notification_page'));
            add_action('updated_option', array($this, 'send_onesignal_notification'), 10, 3);
            add_action('admin_init', array($this, 'onesignal_notification_page_init'));
        }

        public function send_onesignal_notification($option_name, $old_value, $value)
        {
            if ('onesignal_notify_option' === $option_name) {
                if (!empty($value['message'])) {
                    $title                      = !empty($value['title']) ? $value['title'] : '';
                    $message                    = !empty($value['message']) ? $value['message'] : '';
                    if (!empty($title) && !empty($message)) {
                        /* Send onesignal notifications from admin side */
                        do_action('workreap_onesignal_admin_notification', $value);
                    }
                }
            }
        }

        /**
         * Add options page
         */
        public function add_onesignal_notification_page()
        {
            // This page will be under "Settings"
            add_options_page(
                esc_html__('OneSignal admin notifications', 'workreap_core'),
                esc_html__('OneSignal notifications', 'workreap_core'),
                'manage_options',
                'onesignal-admin-notifications',
                array($this, 'onesignal_admin_notification_page')
            );
        }

        /**
         * Options page callback
         */
        public function onesignal_admin_notification_page()
        {
            // Set class property
            $this->options = get_option('onesignal_notify_option');
?>
            <div class="wrap">
                <h1><?php esc_html_e('OneSignal admin notification', 'workreap_core'); ?></h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('onesignal_admin_notify_option_group');
                    do_settings_sections('onesignal_notify_admin_settings');
                    submit_button(
                        esc_html__('Send notifications', 'workreap_core'),
                        'primary',
                        'send_notifications',
                    );
                    ?>
                </form>
            </div>
<?php
        }

        /**
         * Register and add settings
         */
        public function onesignal_notification_page_init()
        {
            register_setting(
                'onesignal_admin_notify_option_group', // Option group
                'onesignal_notify_option', // Option name
                array($this, 'sanitize') // Sanitize
            );
            add_settings_section(
                'onesignal_notify_settings', // ID
                esc_html__('OneSignal admin notifications', 'workreap_core'), // Title
                array($this, 'print_section_info'), // Callback
                'onesignal_notify_admin_settings' // Page
            );
            add_settings_field(
                'title',
                esc_html__('Title', 'workreap_core'),
                array($this, 'title_callback'),
                'onesignal_notify_admin_settings',
                'onesignal_notify_settings'
            );
            add_settings_field(
                'message',
                esc_html__('Message', 'workreap_core'),
                array($this, 'message_callback'),
                'onesignal_notify_admin_settings',
                'onesignal_notify_settings'
            );
            add_settings_field(
                'message_desc',
                esc_html__('', 'workreap_core'),
                array($this, 'message_description'),
                'onesignal_notify_admin_settings',
                'onesignal_notify_settings'
            );
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize($input)
        {
            $new_input = array();
            if (isset($input['message']))
                $new_input['message'] = sanitize_textarea_field($input['message']);

            if (isset($input['title']))
                $new_input['title'] = sanitize_text_field($input['title']);

            return $new_input;
        }

        /** 
         * Print the Section text
         */
        public function print_section_info()
        {
            print esc_html__('Enter your settings below:', 'workreap_core');
        }

        /** 
         * Get the settings option array and print one of its values
         */
        public function title_callback()
        {
            printf(
                '<input type="text" id="title" size="60" name="onesignal_notify_option[title]" value="%s" />',
                isset($this->options['title']) ? esc_attr($this->options['title']) : ''
            );
        }

        /** 
         * Get the settings option array and print one of its values
         */
        public function message_callback()
        {
            printf(
                '<textarea id="message" name="onesignal_notify_option[message]" rows="15" cols="80">%s</textarea>',
                isset($this->options['message']) ? esc_attr($this->options['message']) : ''
            );
        }

        /**
         * Message hint
         */
        public function message_description()
        {
            echo esc_html__(':: Here admin can send message to all oneSignal subscribed users only.', 'workreap_core');
        }
    }

    if (is_admin())
        new OneSignal_Admin_Notification();
}
