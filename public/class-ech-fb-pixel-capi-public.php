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

	}

	public function FB_event_click() {

		$event_id = $_POST['event_id'];
		$event_name = $_POST['event_name'];
		$content_name = $_POST['content_name'];
		$extra_event = $_POST['extra_event'];
		$current_page = $_POST['website_url'];
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$user_agent = $_POST['user_agent'];
		$fbp = $_POST['fbp'];
		$fbc = $_POST['fbc'];
		$param_data1 = '{
				"data": [
						{
								"event_id": "'.$event_name.$event_id.'",
								"event_name": "'.$event_name.'",
								"event_time": '.time().',
								"action_source": "website",
								"event_source_url": "'.$current_page.'",
								"user_data": {
										"client_ip_address": "'.$user_ip.'",
										"client_user_agent": "'.$user_agent.'",
										"fbp": "'.$fbp.'",
										"fbc": "'.$fbc.'",
								}
						}
				]
		}'; //param_data1

		$param_data2 = '{
				"data": [
						{
								"event_id": "Purchase'.$event_id.'",
								"event_name": "Purchase",
								"event_time": '.time().',
								"action_source": "website",
								"event_source_url": "'.$current_page.'",
								"custom_data":{
                    "content_name": "'.$content_name.'",
                    "currency": "HKD",
                    "value": "0"
                },
								"user_data": {
										"client_ip_address": "'.$user_ip.'",
										"client_user_agent": "'.$user_agent.'",
										"fbp": "'.$fbp.'",
										"fbc": "'.$fbc.'",
								}
						}
				]
		}'; //param_data2
		
		$event_result	= $this->fb_curl($param_data1);
		$purchase	= $this->fb_curl($param_data2);
		$result_ary = array(
			$event_name => json_decode($event_result),
			'Purchase' => json_decode($purchase)
		);
		if(!empty($extra_event)){
			$param_data3 = '{
					"data": [
							{
									"event_id": "'.$extra_event.$event_id.'",
									"event_name": "'.$extra_event.'",
									"event_time": '.time().',
									"action_source": "website",
									"event_source_url": "'.$current_page.'",
									"user_data": {
											"client_ip_address": "'.$user_ip.'",
											"client_user_agent": "'.$user_agent.'",
											"fbp": "'.$fbp.'",
											"fbc": "'.$fbc.'"
									}
							}
					]
			}'; //param_data3
			$completeRegistration	= $this->fb_curl($param_data3);
			$result_ary[$extra_event]=json_decode($completeRegistration);
		}
		$result = json_encode($result_ary);
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
