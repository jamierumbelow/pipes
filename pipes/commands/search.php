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

class Pipes_Command_Search {
	public function __construct($cli, $args = array(), $flags = array()) {
		$this->cli = $cli;
		$this->args = $args;
		$this->flags = $flags;
	}
	
	/**
	 * Run the command
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function run() {
		if (!isset($this->args[0])) {
			$this->cli->error('Search name required!');
			exit;
		}
		
		// Get the search term
		$search = $this->args[0];
		
		// Header
		$this->cli->write('Remote pipes: ' . $search);
		$this->cli->write(str_pad('', strlen('Remote pipes: ' . $search), '-'));
		$this->cli->newline();
		
		// Make the API request
		$pipes = Pipes_Downloader::api_request('pipes?search='.$search);
		
		// Do we have any pipes?
		if (!$pipes->pipes) {
			$this->cli->error('No pipes could be found!');
			exit;
		}
		
		// Make it a little normalised
		$packages = array();
		
		foreach ($pipes->pipes as $pipe) {
			$packages[] = array($pipe->name, $pipe->version);
		}
		
		// Get the width
		$width = max(array_map(array('Pipes_Command_Search', 'map_package_array'), $packages)) + 3;
		
		// Write them
		foreach ($packages as $package) {
			$name 		= $package[0];
			$version 	= $package[1];
			
			$this->cli->write(str_pad($name, $width) . "\t" . $version);
		}
	}
	
	/**
	 * Map the package array
	 *
	 * @return integer
	 * @access array_map
	 * @author Jamie Rumbelow
	 */
	static public function map_package_array($package) {
		return strlen($package[0]);
	}
}