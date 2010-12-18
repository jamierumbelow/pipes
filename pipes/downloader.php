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
		Pipes_Cli::write('Downloading pipe...');
		
		// Open a temporary file
		$name = tempnam('/tmp', 'pipes_');
		$file = fopen($name, 'w+');
		
		// Start the cURL request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FILE, $file);
		curl_exec($ch);
		
		// Close the handles
		curl_close($ch);
		fclose($file);
		
		// Return the filename
		return $name;
	}
}