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

	private   $cacheTime ;
	private   $dribbble = 'dribbble_count';
	private   $vimeo 	= 'vimeo_count';
	private   $youtube = 'youtube_count';
	private   $facebook = 'facebook_count';
	private   $github = 'github_count';
	private   $twitter = 'twitter_count';
	private   $instagram = 'instagram_count';
	private   $google = 'google_count';

	function __construct($cacheTime = 30){
		$this->cacheTime = 60 * $cacheTime ; // 30 minute
	}

	private function curl($url){

		if (function_exists("wp_remote_get")) {

			$data = wp_remote_get($url , array( 'timeout' => 5 ));
			if ( is_wp_error( $data  ) ) {
				return '';
			}
			return $data['body'];
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;

	}

	private function update( $data = null , $id = null) {

		$data = (int) $data;

		if ( $data != 0  ) {
			// Save our new transient.
			$this->set_transient($id, $data , $this->cacheTime );
		}

		return  $data ;
	}

	public function reset() {

		$this->set_transient( $this->dribbble , 0 , 1);
		$this->set_transient( $this->vimeo , 0 , 1);
		$this->set_transient( $this->youtube , 0 , 1);
		$this->set_transient( $this->facebook , 0 , 1);
		$this->set_transient( $this->github , 0 , 1);
		$this->set_transient( $this->twitter , 0 , 1);
		$this->set_transient( $this->instagram , 0 , 1);
		$this->set_transient( $this->google , 0 , 1);

	}

	public function set_prefix( $prefix = '') {

		$this->dribbble 	= $prefix . $this->dribbble ; 
		$this->vimeo 		= $prefix . $this->vimeo ;	
		$this->youtube 		= $prefix . $this->youtube ; 
		$this->facebook 	= $prefix . $this->facebook ;
		$this->github 		= $prefix . $this->github;
		$this->twitter 		= $prefix . $this->twitter;
		$this->instagram 	= $prefix . $this->instagram;
		$this->google 		= $prefix . $this->google;

	}

	private function set_transient($id , $data , $expiration){

		if (function_exists("set_transient")) {
			return set_transient($id , $data , $expiration);
		}

		if ($expiration == 1) {
			return setcookie( $id , '', time()-60 );
		}else {
			return setcookie ( $id , $data , time()+$expiration );
		}
	}

	private function get_transient($id){

		if (function_exists("get_transient")) {
			return get_transient($id);
		}

		if (isset($_COOKIE[$id])) {
			return $_COOKIE[$id];
		}

		return false;
	}

	public function dribbble( $page_link , $id = null ) {

		if (!empty($id))
			$this->dribbble = $id;

		$id = $this->dribbble;

		if( false === ( $count = $this->get_transient($id) ) ){

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

		return (int) $count;

	}

	public function vimeo( $page_link , $id = null ) {

		if (!empty($id))
			$this->vimeo = $id;

		$id = $this->vimeo;

		if( false === ( $count = $this->get_transient($id) ) ){

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

		return (int) $count;

	}

	public function youtube( $page_link , $id = null ) {

		if (!empty($id))
			$this->youtube = $id;

		$id = $this->youtube;

		if( false === ( $count = $this->get_transient($id) ) ){

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

		return (int) $count;

	}

	public function facebook( $page_link , $id = null ) {

		if (!empty($id))
			$this->facebook = $id;

		$id = $this->facebook;

		if( false === ( $count = $this->get_transient($id) ) ){

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

		return (int) $count;

	}

	public function github( $page_link , $id = null ) {

		if (!empty($id))
			$this->github = $id;

		$id = $this->github;

		if( false === ( $count = $this->get_transient($id) ) ){

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

		return (int) $count;

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

		if( false === ( $count = $this->get_transient($id) ) ){

			$Connection = new TwitterOAuth( $consumer_key , $consumer_secret , $access_token , $access_token_secret	);
			$data = (array) $Connection->get('users/show', array('screen_name' => $twitter_username));

			if (isset($data['followers_count'])) {
				$count = $data['followers_count'];
			}

			return $this->update($count, $id);
		}

		return (int) $count;

	}

	//  find your instagram access token http://stylehatch.co/instagram/
	public function instagram( $access_token = null , $userid = null , $id = null ) {

		if ( empty($access_token) )
			return '-1';

		if (!empty($id))
			$this->instagram = $id;

		$id = $this->instagram;

		if( false === ( $count = $this->get_transient($id) ) ){

				if ( empty($userid) ) {
					$userid = explode(".", $access_token);
					$userid  = $userid[0];
				}

				if ( !is_numeric($userid) ) {
					$userid = json_decode($this->curl("https://api.instagram.com/v1/users/search?q={$userid}&access_token={$access_token}") , true );
					$userid = (isset($userid['data']['0']['id'])) ? $userid['data']['0']['id'] : '' ;
				}

				$json = "https://api.instagram.com/v1/users/{$userid}/?access_token={$access_token}";
				$data = json_decode( $this->curl($json) , true);

				if ( isset( $data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data']['counts']['followed_by']) ) {
					$count = $data['data']['counts']['followed_by'];
				}

			return $this->update($count, $id);

		}

		return (int) $count;
	}



	public function google( $url = null , $id = null ) {

		if (!empty($id))
			$this->google = $id;

		$id = $this->google;

		if( false === ( $count = $this->get_transient($id) ) ){

			$face_link = @parse_url($url);

			if( isset($face_link['host']) && ( $face_link['host'] == 'plus.google.com' ) ){

				$url = str_replace('+', '%2B', $url);
				$url = "https://apis.google.com/u/0/_/widget/render/page?usegapi=1&bsv=o&width=180&showcoverphoto=0&showtagline=0&hl=en-US&href={$url}";
				$data =  $this->curl($url);

				preg_match('/<div class="gge mgd Oae" style="font-size:11px;">(.*?)<\/div>/s', $data, $data);

				if (!empty($data)){
					$count = preg_replace('/[^0-9_]/','', $data[1] );
				}

				return $this->update($count, $id);
			}

		}

		return (int) $count;
	}



}
