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

class Pipes_Command_Version {
	public function __construct($cli, $args, $flags) {
		// Save the $cli, forget the rest as we're 
		// just outputting a version number and nothing else
		$this->cli = $cli;
	}
	
	public function run() {
		$this->cli->success("Pipes version: ".PIPES_VERSION);
	}
}