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

class Pipes_Command_List {
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
		// Get all files matching the package name
		$files = scandir(PIPES_PACKAGE_DIR);
		$packages = array();
		
		foreach ($files as $file) {
			if (substr($file, -5) == '.pipe') {
				$packages[] = substr($file, 0, strlen($file)-5);
			}
		}
		
		// Get the width
		$width = max(array_map(array('Pipes_Command_List', 'map_package_array'), $packages)) + 3;
		
		// Write them
		foreach ($packages as $package) {
			$package 	= explode('-', $package);
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
		$package 	= explode('-', $package);
		$name 		= $package[0];
		
		return strlen($name);
	}
}