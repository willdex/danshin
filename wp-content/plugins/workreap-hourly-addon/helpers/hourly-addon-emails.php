<?php

/**
 *
 * Class 'HourlyAddonEmails' defines User active or deactive
 *
 * @package    Workreap_Hourly_Addon
 * @subpackage Workreap_Hourly_Addon/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
/* get the EmailHelper class */
if (!class_exists('Workreap_Email_helper') && in_array('workreap/init.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once WP_PLUGIN_DIR . '/workreap/helpers/EmailHelper.php';
}

if (!class_exists('HourlyAddonEmails') && class_exists('Workreap_Email_helper')) {
    class HourlyAddonEmails extends Workreap_Email_helper
    {
        /* hourly project request employer email */
        public function hourly_project_request_employer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);
            $email_to           = !empty($employer_email) ? $employer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name         = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';

            $subject_default         = esc_html__('Hourly request on project', 'workreap-hourly-addon'); //default email subject
            $contact_default         = wp_kses(
                __('{{freelancer_name}} send you a hourly project request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'workreap-hourly-addon'), //default email content
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

            $subject            = !empty($workreap_settings['hourly_request_send_employer_email_subject']) ? $workreap_settings['hourly_request_send_employer_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty($workreap_settings['hourly_request_send_employer_email_content']) ? $workreap_settings['hourly_request_send_employer_email_content'] : $contact_default; //getting content

            $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'hourly_request_send_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_project_hourly_request_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* hourly project request approve freelancer email */
        public function hourly_project_request_approve_freelancer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($freelancer_email) ? $freelancer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name         = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';

            $subject_default         = esc_html__('Project hourly request approved', 'workreap-hourly-addon'); //default email subject
            $contact_default         = wp_kses(
                __('Congratulation! {{employer_name}} have approve your project hourly request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'workreap-hourly-addon'), //default email content
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

            $subject            = !empty($workreap_settings['hourly_request_approve_freelancer_email_subject']) ? $workreap_settings['hourly_request_approve_freelancer_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty($workreap_settings['hourly_request_approve_freelancer_email_content']) ? $workreap_settings['hourly_request_approve_freelancer_email_content'] : $contact_default; //getting content

            $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'hourly_request_approve_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_project_hourly_request_approve_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* hourly project request decline freelancer email */
        public function hourly_project_request_decline_freelancer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($freelancer_email) ? $freelancer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name        = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';
            $decline_detail     = !empty($decline_detail) ? $decline_detail : '';

            $subject_default         = esc_html__('Project hourly request declined', 'workreap-hourly-addon'); //default email subject
            $contact_default         = wp_kses(
                __('Oho! A project hourly request has been declined by {{employer_name}} with the reason of <br/> {{decline_detail}} <br />Please click on the button below to view the decline details.<br />{{project_link}}', 'workreap-hourly-addon'), //default email content
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

            $subject            = !empty($workreap_settings['hourly_request_decline_freelancer_email_subject']) ? $workreap_settings['hourly_request_decline_freelancer_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty($workreap_settings['hourly_request_decline_freelancer_email_content']) ? $workreap_settings['hourly_request_decline_freelancer_email_content'] : $contact_default; //getting content

            $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{decline_detail}}", $decline_detail, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'hourly_request_decline_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_project_hourly_request_declined_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }
    }
    new HourlyAddonEmails();
}
