<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'libraries/jwt/vendor/autoload.php';
/** Requiere the JWT library. */

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!class_exists('WORKREAPAPI_JWT')) {
    /**
     * Servento jwt auth Module
     * 
     * @package  WorkreapAppApi
     */

    /**
     * Register all jwt auth function
     *
     * @link       servento.com
     * @since      1.0.0
     *
     * @package    WorkreapAppApi
     * @subpackage WorkreapAppApi/includes
     */

    /**
     * Register all actions and filters for the plugin.
     *
     * Maintain a list of all hooks that are registered throughout
     * the plugin, and register them with the WordPress API. Call the
     * run function to execute the list of actions and filters.
     *
     * @package    WorkreapAppApi
     * @subpackage WorkreapAppApi/includes
     * @author     servento <wpguppy@gmail.com>
     */

    class WORKREAPAPI_JWT
    {

        /**
         * The unique identifier of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $plugin_name    The string used to uniquely identify this plugin.
         */
        private $plugin_name;

        /**
         * The current version of the plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $version    The current version of the plugin.
         */
        private $version;

        /**
         * private key for jwt.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $secretKey   secret key for jwt.
         */
        private $secretKey;


        /**
         * Initialize the construct.
         *
         * @since    1.0.0
         */
        public function __construct($plugin_name, $version)
        {
            $this->plugin_name      = $plugin_name;
            $this->version          = $version;
            $this->secretKey        = '^W#cdH,DxA79@NVQJ}X!6RT6>SwO@m3_D7K[mEt]W[Uq.f~f+r;5-vlBk&,{L|}JKCaK';
        }

        /**
         * get jwt token
         *
         * @since    1.0.0
         */
        public  function getToken($params)
        {
            $jwt = array();
            $issuedAt       = time();
            $notBefore      = $issuedAt + 10;
            $expire         = $issuedAt + (DAY_IN_SECONDS * 3);
            $token = array(
                'iss'   => home_url(),
                'iat'   => $issuedAt,
                'nbf'   => $notBefore,
                'exp'   => $expire,
                'data'  => array(
                    'user'      => array(
                        'id'    => $params['userId'],
                    ),
                ),
            );

            $authToken = JWT::encode($token, $this->secretKey, 'HS256'); // get access token

            // get refresh token
            $token['exp'] = $issuedAt + (DAY_IN_SECONDS * 90);
            $refreshToken = JWT::encode($token, $this->secretKey, 'HS256');

            $jwt['authToken']       = $authToken;
            $jwt['refreshToken']    = $refreshToken;
            return $jwt;
        }

        /**
         * verify jwt token
         *
         * @since    1.0.0
         */
        public  function verifyToken($params)
        {
            $json           = array();
            $type           = 'success';
            $message        = '';
            list($token) = sscanf($params['authToken'], 'Bearer %s');

            if (!$token) {
                $message        = esc_html__('Authorization Token does not found!', 'workreap_api');
                $type           = 'error';
            } else {
                try {
                    JWT::$leeway = 60;
                    $token      = JWT::decode($token, new Key($this->secretKey, 'HS256'));
                    $now        = time();
                    if (
                        $token->iss != home_url()
                        || !isset($token->data->user->id)
                        || $token->data->user->id != $params['user_id']
                        || $token->exp < $now
                    ) {
                        $message    = esc_html__('You are not allowed to perform this action.!', 'workreap_api');
                        $type       = 'error';
                    }
                } catch (Exception $e) {
                    $message        = $e->getMessage();
                    $type           = 'error';
                }
            }

            $json['type']               = $type;
            $json['message_desc']       = $message;
            return $json;
        }

        /**
         * get token by refresh token
         *
         * @since    1.0.0
         */
        public  function getTokenByRefresh($params)
        {
        }
    }
}
