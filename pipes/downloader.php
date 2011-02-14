<?php
/**
 * Pipes
 *
 * Sexy PHP package management
 *
 * @package pipes
 * @subpackage core
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @copyright Copyright (c) 2010 Jamie Rumbelow
 * @license MIT License
 **/

class Pipes_Downloader {
	
	/**
	 * Download a pipe from a URL
	 *
	 * @param string $url The URL
	 * @return string
	 * @author Jamie Rumbelow
	 */
	static public function download_from_url($url) {
		// Open a temporary file
		$name = tempnam('/tmp', 'pipes_');
		$file = fopen($name, 'w+');
		
		// Start the cURL request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FILE, $file);
		
		// Make sure we return FALSE if something goes bad
		if (!curl_exec($ch)) {
			$name = FALSE;
		}
		
		// Close the handles
		curl_close($ch);
		fclose($file);
		
		// Return the filename
		return $name;
	}
	
	/**
	 * Make an API request (for releasing pipes)
	 *
	 * @return array
	 * @author Jamie Rumbelow
	 **/
	static public function api_request($endpoint, $method = 'GET', $parameters = array()) {
		foreach (Pipes::$config->sources as $source) {
			// Set some stuff up
			$url = $source . $endpoint;
			$curl = curl_init();
			
			// Set curlopts
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			
			// POST? Params?
			if (strtoupper($method) == 'POST') {
				curl_setopt($curl, CURLOPT_POST, TRUE);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
			}
			
			// Make the request!
			$response = curl_exec($curl);
			curl_close($curl);
			
			// Decode it
			$response = json_decode($response);
			
			// Break if it's good, otherwise 
			if ($response->success) {
				break;
			}
		}
		
		// Return the response
		return $response;
	}
}