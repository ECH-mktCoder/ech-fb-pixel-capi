<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Fb_Pixel_Capi
 * @subpackage Ech_Fb_Pixel_Capi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ech_Fb_Pixel_Capi
 * @subpackage Ech_Fb_Pixel_Capi/public
 * @author     Rowan Chang <rowanchang@prohaba.com>
 */
class Ech_Fb_Pixel_Capi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ech_Fb_Pixel_Capi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ech_Fb_Pixel_Capi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ech-fb-pixel-capi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ech-fb-pixel-capi-public.js', array( 'jquery' ), $this->version, false );

		$getAcceptPll = get_option('ech_lfg_accept_pll');
		wp_localize_script($this->plugin_name, 'echPll', $getAcceptPll);
	}

	public function FB_event_click() {
		
		$event_id = $_POST['event_id'];
		$event_name = $_POST['event_name'];
		$content_name = $_POST['content_name'];
		$extra_event = $_POST['extra_event'];
		$current_page = $_POST['website_url'];
		$user_agent = $_POST['user_agent'];
		$fbp = $_POST['fbp'];
		$fbc = $_POST['fbc'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}

		$user_data = [
			"client_ip_address"  => $user_ip,
			"client_user_agent"  => $user_agent,
			"fbp"                => $fbp,
			"fbc"                => $fbc,
		];

		$registered_settings = get_registered_settings();
		$withoutPII_str='';
		if (array_key_exists('ech_lfg_accept_pll', $registered_settings)) {
				$accept_pll = get_option( 'ech_lfg_accept_pll' );
				if( intval($accept_pll) == 0 ) {
					$withoutPII_str='WithoutPII';
				}
		}

		$param_datas = [
			$event_name => [
				"event_id" => $event_name.$event_id,
				"event_name" => $event_name,
				"event_time" => time(),
				"action_source" => "website",
				"event_source_url" => $current_page,
				"user_data" => $user_data
			],
			'Purchase'.$withoutPII_str => [
				"event_id" => "Purchase".$event_id,
				"event_name" => "Purchase".$withoutPII_str,
				"event_time" => time(),
				"action_source" => "website",
				"event_source_url" => $current_page,
				"user_data" => $user_data,
				"custom_data" => [
					"content_name" => $content_name,
					"currency" => "HKD",
					"value" => 0.00
				],
			],
		];
		if(!empty($extra_event)){
			$param_datas[$extra_event.$withoutPII_str] = [
				"event_id" => $extra_event.$event_id,
				"event_name" => $extra_event.$withoutPII_str,
				"event_time" => time(),
				"action_source" => "website",
				"event_source_url" => $current_page,
				"user_data" => $user_data
			];
		}
		$results = [];
    foreach ($param_datas as $key => $data) {
        $results[$key] = json_decode($this->fb_curl(json_encode(['data' => [$data]])));
    }
		echo json_encode($results);
		wp_die();
	}

	public function FB_thanks_page_view() {
		
		$event_id = $_POST['event_id'];
		$current_page = $_POST['website_url'];
		$user_agent = $_POST['user_agent'];
		$fbp = $_POST['fbp'];
		$fbc = $_POST['fbc'];
		$user_email = $_POST['user_email'];
		$phone = $_POST['user_phone'];
		$user_fn = $_POST['user_fn'];
		$user_ln = $_POST['user_ln'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$user_data = [
			"em" => hash('sha256', $user_email),
			"client_ip_address" => $user_ip,
			"client_user_agent" => $user_agent,
			"fbp" => $fbp,
			"fbc" => $fbc
		];

		$registered_settings = get_registered_settings();
		if (array_key_exists('ech_lfg_accept_pll', $registered_settings)) {
			$accept_pll = get_option( 'ech_lfg_accept_pll' );
			if( intval($accept_pll)) {
				$user_data['ph'] = hash('sha256', $user_phone);
				$user_data['fn'] = hash('sha256', $user_fn);
				$user_data['ln'] = hash('sha256', $user_ln);
			}
		}

		$param_data = [
			'event_id' => 'ThanksPageView' . $event_id,
			'event_name' => 'ThanksPageView',
			'event_time' => time(),
			'action_source' => 'website',
			'event_source_url' => $current_page,
			'user_data' => $user_data,
			"custom_data" => [
				"currency" => "HKD",
				"value" => 0.00,
				"content_category" => "Thank You Page"
			],
		];

		$result = $this->fb_curl(json_encode(['data' => [$param_data]]));
		echo $result;
		wp_die();
	}

	private function fb_curl($param_data) {
    $ch = curl_init();

		$fbAPI_version = "v11.0";
		$pixel_id = get_option( 'ech_lfg_pixel_id' );
		$fb_access_token= get_option( 'ech_lfg_fb_access_token' );
		$fb_graph_link = "https://graph.facebook.com/".$fbAPI_version."/".$pixel_id."/events?access_token=".$fb_access_token;

    $headers = array(
        "content-type: application/json",
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $fb_graph_link);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return $result;
	}


}
