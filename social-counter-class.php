<?php

/**
 * 
 * Social Counter
 *
 * @package     WordPress
 * @subpackage  wpmu
 * @since       1.0.0
 * @author      http://Mustafaismail.co/
 *
 */


class Social{

	private  $cacheTime ;

	private   $dribbble = 'dribbble_count';
	private   $vimeo 	= 'vimeo_count';
	private   $youtube = 'youtube_count';
	private   $facebook = 'facebook_count';
	private   $github = 'github_count';
	private   $twitter = 'twitter_count';

	

	function __construct(){
		
		$this->cacheTime = 60 * 5 ; // 5 minute
	}

	private function curl($url){

		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_HEADER, 0);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_URL, $url);
		// $data = curl_exec($ch);
		// curl_close($ch);

		$data = wp_remote_get($url);

		if ( is_wp_error( $data  ) ) {
			return '';
		}

		return $data['body'];
	}

	private function update( $data = null , $id = null) {

		$data = (int) $data;

		if ( $data != 0  ) {
			// Save our new transient.
			set_transient($id, $data , $this->cacheTime );
		}

		return  $data ;

	}

	public function reset() {

		set_transient( $this->dribbble , 0 , 1);
		set_transient( $this->vimeo , 0 , 1);
		set_transient( $this->youtube , 0 , 1);
		set_transient( $this->facebook , 0 , 1);
		set_transient( $this->github , 0 , 1);
		set_transient( $this->twitter , 0 , 1);

	}

	public function dribbble( $page_link , $id = null ) {

		if (!empty($id))
			$this->dribbble = $id;

		$id = $this->dribbble;

		$count = 0;

		if( false === ( $count = get_transient($id) ) ){

			$face_link = @parse_url($page_link);

			if( isset($face_link['host']) && ( $face_link['host'] == 'www.dribbble.com' || $face_link['host']  == 'dribbble.com' ) ){

				$page_name = substr( @parse_url($page_link, PHP_URL_PATH), 1);

				$json = "http://api.dribbble.com/{$page_name}";
				
				$data = json_decode( $this->curl($json) , true);

				if (isset($data['followers_count'])) {
					$count = $data['followers_count'];
				}

			}

			return $this->update($count, $id);

		}

		return $count;

	}

	public function vimeo( $page_link , $id = null ) {

		if (!empty($id))
			$this->vimeo = $id;

		$id = $this->vimeo;

		$count = 0;

		if( false === ( $count = get_transient($id) ) ){

			$face_link = @parse_url($page_link);

			if( isset($face_link['host']) && ( $face_link['host'] == 'www.vimeo.com' || $face_link['host']  == 'vimeo.com' ) ){

				$page_name = substr( @parse_url($page_link, PHP_URL_PATH), 10);

				$json = "http://vimeo.com/api/v2/channel/{$page_name}/info.json";

				$data = json_decode( $this->curl($json) , true);

				if (isset($data['total_subscribers'])) {
					$count = $data['total_subscribers'];
				}

			}

			return $this->update($count, $id);

		}

		return $count;

	}

	public function youtube( $page_link , $id = null ) {

		if (!empty($id))
			$this->youtube = $id;

		$id = $this->youtube;

		$count = 0;

		if( false === ( $count = get_transient($id) ) ){

			$face_link = @parse_url($page_link);

			if( isset($face_link['host']) && ( $face_link['host'] == 'www.youtube.com' || $face_link['host']  == 'youtube.com' ) ){

				$page_name = substr( @parse_url($page_link, PHP_URL_PATH), 6);

				$json = "http://gdata.youtube.com/feeds/api/users/{$page_name}?alt=json";

				$data = json_decode( $this->curl($json) , true);

				if ( isset($data['entry']['yt$statistics']['subscriberCount']) ) {
					$count =  $data['entry']['yt$statistics']['subscriberCount'];
				}

			}

			return $this->update($count, $id);

		}

		return $count;

	}

	public function facebook( $page_link , $id = null ) {

		if (!empty($id))
			$this->facebook = $id;

		$id = $this->facebook;

		$count = 0;

		if( false === ( $count = get_transient($id) ) ){

			$face_link = @parse_url($page_link);

			if( isset($face_link['host']) && ( $face_link['host'] == 'www.facebook.com' || $face_link['host']  == 'facebook.com' ) ){

				$page_name = substr( @parse_url($page_link, PHP_URL_PATH), 1);

				$json = "http://graph.facebook.com/{$page_name}";

				$data = json_decode( $this->curl($json) , true);

				if ( isset($data['likes']) ) {
					$count =  $data['likes'];
				}

			}

			return $this->update($count, $id);

		}

		return $count;

	}

	public function github( $page_link , $id = null ) {

		if (!empty($id))
			$this->github = $id;

		$id = $this->github;
		$count = 0;

		if( false === ( $count = get_transient($id) ) ){

			$face_link = @parse_url($page_link);

			if( isset($face_link['host']) && ( $face_link['host'] == 'www.github.com' || $face_link['host']  == 'github.com' ) ){

				$page_name = substr( @parse_url($page_link, PHP_URL_PATH), 1);

				$json = "https://api.github.com/users/{$page_name}";

				$data = json_decode( $this->curl($json) , true);


				if (isset($data['followers'])) {
					$count = $data['followers'];
				}

			}

			return $this->update($count, $id);

		}

		return $count;

	}

	public function twitter( $twitter_username = null , $consumer_key = null , $consumer_secret = null , $access_token = null , $access_token_secret = null  , $id = null ) {

		if (!class_exists('TwitterOAuth'))
			return '-2';

		if( empty($twitter_username) || empty($consumer_key) || empty($consumer_secret) || empty($access_token) || empty($access_token_secret) ){
			return '-1';
		}


		if (!empty($id))
			$this->twitter = $id;

		$id = $this->twitter;

		$count = 0;

		if( false === ( $count = get_transient($id) ) ){

			$Connection = new TwitterOAuth( $consumer_key , $consumer_secret , $access_token , $access_token_secret	);
			$data = (array) $Connection->get('users/show', array('screen_name' => $twitter_username));

			if (isset($data['followers_count'])) {
				$count = $data['followers_count'];
			}

			return $this->update($count, $id);
		}

		return $count;

	}

}
