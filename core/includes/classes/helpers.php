<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Inno_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		INNOCS
 * @subpackage	Classes/Inno_Helpers
 * @author		Mohamed Yassin
 * @since		1.0.0
 */
class Inno_Helpers{

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */


	public static function pre($element,$title = '',$return = false){
		if($title != 'no_title'){
			$excuting_line = debug_backtrace()[0]['line'];
	
			$excuting_file = debug_backtrace()[0]['file'];
			$excuting_file = explode("\\" ,$excuting_file);
			
			$count = count($excuting_file);
		
			$excuting_folder = @$excuting_file[($count-2)];		
			$excuting_file = $excuting_file[($count-1)];
			$excuting_file = explode('.',$excuting_file)[0];
		
			$title  =  "$title ($excuting_folder/$excuting_file@$excuting_line)";
		
			$title = $title != '' ? "<h3>$title</h3>\n" : '';
			$title = $title ."\n";	
		}
		
		if($return ==  false){
			echo "$title<pre>";
			print_r ($element);
			echo "</pre>";
			return;
		}else {
			return "$title<pre>".print_r($element)."</pre>";
		}
	}

	public static function remote_pre($args = array()){
		if(!is_array($args) && !is_object($args)){
			$args 	= array('info' => $args);
		}
		$args 		= http_build_query($args);
		$url  		= 'https://c9a33e7d3660c4a1875933e6cb841619.m.pipedream.net'.'/?'.$args;
		$response 	= wp_remote_get($url);
		return json_decode( wp_remote_retrieve_body( $response ), true );
	}
}
