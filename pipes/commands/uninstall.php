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

class Pipes_Command_Uninstall {
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
		if (empty($this->args)) {
			$this->cli->error("Package name needed");
			exit;
		}
		
		// Package name
		$package_name = $this->args[0];
		
		// Get all files matching the package name
		$files = preg_grep("/^$package_name/", scandir(PIPES_PACKAGE_DIR));
		$packages = array();
		
		foreach ($files as $file) {
			if (substr($file, -5) == '.pipe') {
				$packages[] = substr($file, 0, strlen($file)-5);
			}
		}
		
		// Delete each package
		foreach ($packages as $package) {
			$package = explode('-', $package);
			$name = $package[0];
			$version = $package[1];
			
			// Is there a symlink? Remove it
			if (file_exists(PIPES_PACKAGE_DIR . $name)) {
				unlink(PIPES_PACKAGE_DIR . $name);
			}
			
			// Delete the folder and pipe file
			$this->unlink_directory(PIPES_PACKAGE_DIR . $name . '-' . $version);
			@unlink(PIPES_PACKAGE_DIR . $name . '-' . $version . '.pipe');
			
			// Output a successful error message
			$this->cli->success('Successfully uninstalled '.$name.'-'.$version);
		}
		
		// Done
		$this->cli->success('Successfully uninstalled all packages matching '.$package_name);
	}
	
	/**
	 * Unlink a directory
	 *
	 * @param string $dirname The dir path
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function unlink_directory($dirname) {
		$dir = scandir($dirname);
		array_shift($dir);
		array_shift($dir);
		
		// DIRNAME/
		if (substr($dirname, 0, strlen($dirname)-1) !== '/') {
			$dirname .= '/';
		}
		
		// Loop and unlink the files
		foreach ($dir as $file) {
			unlink($dirname . $file);
		}
		
		// Remove the directory
		rmdir($dirname);
	}
}