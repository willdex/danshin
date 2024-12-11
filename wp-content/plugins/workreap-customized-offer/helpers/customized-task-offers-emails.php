<?php

/**
 *
 * Class 'HourlyAddonEmails' defines User active or deactive
 *
 * @package    Workreap_Customized_Task_Offers_Addon
 * @subpackage Workreap_Customized_Task_Offers_Addon/Helpers
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
/* get the EmailHelper class */
if (!class_exists('Workreap_Email_helper') && in_array('workreap/init.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once WP_PLUGIN_DIR . '/workreap/helpers/EmailHelper.php';
}

if (!class_exists('WorkreapOffersAddonEmails') && class_exists('Workreap_Email_helper')) {
    class WorkreapOffersAddonEmails extends Workreap_Email_helper
    {
        /* hourly project request employer email */
        public function custom_offer_employer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);
            $email_to       = !empty($employer_email) ? $employer_email : '';
            $employer_name     = !empty($employer_name) ? $employer_name : '';
            $freelancer_name    = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title  = !empty($offer_name) ? $offer_name : '';
            $project_link   = !empty($offer_link) ? $offer_link : '';

            $subject_default  = esc_html__('Customized task offer request', 'customized-task-offer'); //default email subject
            $contact_default  = wp_kses(
                __('{{freelancer_name}} send you an custom task offer.<br/>Please click on the button below to view the offer <br/> {{offer_link}}', 'customized-task-offer'), //default email content
                array(
                    'a' => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject        = !empty($workreap_settings['offer_send_employer_email_subject']) ? $workreap_settings['offer_send_employer_email_subject'] : $subject_default; //getting subject
            $email_content  = !empty($workreap_settings['offer_send_employer_email_content']) ? $workreap_settings['offer_send_employer_email_content'] : $contact_default; //getting content
            $project_link   = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{offer_title}}", $project_title, $email_content);
            $email_content = str_replace("{{offer_link}}", $project_link, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'offer_send_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_project_offer_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* hourly project request employer email */
        public function custom_offer_decline_freelancer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);
            $email_to       = !empty($freelancer_email) ? $freelancer_email : '';
            $freelancer_name    = !empty($freelancer_name) ? $freelancer_name : '';
            $employer_name     = !empty($employer_name) ? $employer_name : '';
            $employer_email    = !empty($employer_email) ? $employer_email : '';
            $noti_type      = !empty($notification_type) ? $notification_type : '';
            $sender_id      = !empty($sender_id) ? $sender_id : '';
            $receiver_id    = !empty($receiver_id) ? $receiver_id : '';
            $project_title  = !empty($offer_name) ? $offer_name : '';
            $decline_reason = !empty($decline_reason) ? $decline_reason : '';

            $subject_default    = esc_html__('Customized task offer decline', 'customized-task-offer'); //default email subject
            $contact_default    = wp_kses(
                __('{{employer_name}} has been decline your custom offer request.<br/>{{decline_reason}}', 'customized-task-offer'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject       = !empty($workreap_settings['offer_decline_freelancer_email_subject']) ? $workreap_settings['offer_decline_freelancer_email_subject'] : $subject_default; //getting subject
            $email_content = !empty($workreap_settings['decline_offer_send_freelancer_email_content']) ? $workreap_settings['decline_offer_send_freelancer_email_content'] : $contact_default; //getting content
            $project_link  = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{offer_title}}", $project_title, $email_content);
            $email_content = str_replace("{{decline_reason}}", $decline_reason, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
 
            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'decline_offer_send_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_project_offer_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }
    }
    new WorkreapOffersAddonEmails();
}
