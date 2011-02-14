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

class Pipes_Command_Sources {
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
		$this->args[0] = (isset($this->args[0])) ? $this->args[0] : 'list';
		
		// Switchy switch
		switch ($this->args[0]) {
			case 'add':
				$this->add_source($this->args[1]);
				break;
			
			case 'remove':
				$this->remove_source($this->args[1]);
				break;
				
			case 'list':
			default:
				$this->list_sources();
				break;
		}
	}
	
	/**
	 * Validate and add a source
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function add_source($url) {
		// Make sure it's a correct URL
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			$this->cli->error("Invalid URL: " . $url);
			exit;
		}
		
		// Check that it's a Pipes package server
		$check = $url . '?pipes_check';
		$response = file_get_contents($check);
		
		if ($response !== 'YES') {
			$this->cli->error("URL provided is not a valid Pipes server.");
			exit;
		}
		
		// Add it to the sources and write the config
		Pipes::$config->sources[] = $url;
		Pipes::write_config();
		
		// Done
		$this->cli->success('Successfully validated and added ' . $url . ' to the list of sources');
	}
	
	/**
	 * Remove a source
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function remove_source($url) {
		if (in_array($url, Pipes::$config->sources)) {
			$keys = array_flip(Pipes::$config->sources);
			$key = $keys[$url];
			
			// Remove it and write the config
			unset(Pipes::$config->sources[$key]);
			Pipes::write_config();
			
			// Done
			$this->cli->success('Successfully removed ' . $url . ' from the list of sources');
		} else {
			$this->cli->error("Couldn't find URL in list of sources");
		}
	}
	
	/**
	 * List sources
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function list_sources() {
		$this->cli->write("Sources");
		$this->cli->write("=======\n");
		
		foreach (Pipes::$config->sources as $url) {
			$this->cli->write("* $url");
		}
	}
}