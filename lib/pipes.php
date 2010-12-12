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

require_once 'pipes/cli.php';
require_once 'pipes/downloader.php';
require_once 'pipes/package.php';
require_once 'pipes/vcs.php';

class Pipes {
	static $commands = array('install', 'uninstall', 'update', 'list', 'search', 'sources', 'build', 'help');
	public $cli;
	
	/**
	 * Load and execute the command
	 *
	 * @param Pipes_Cli $cli the ready Pipes_Cli object
	 * @author Jamie Rumbelow
	 */
	public function __construct($cli) {
		$this->cli = $cli;
		$this->command($cli->command, $cli->command_arguments, $cli->flags);
	}
	
	/**
	 * Take a command, the args and the flags, load the
	 * command file and run the command
	 *
	 * @param string $command The name of the command 
	 * @param array $args An array of command arguments
	 * @param array $flags Any flags
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function command($command = 'help', $args = array(), $flags = array()) {
		// Load the command file
		if (file_exists('pipes/commands/'.$command.'.php')) {
			require 'pipes/commands/'.$command.'.php';
		} else {
			// Default to help, but show an error
			$this->cli->error("Unknown command '$command'");
			$this->command('help');
			exit;
		}
		
		// Version flag?
		if (in_array('-version', $flags)) {
			$this->command('version');
			exit;
		}
		
		// Help flag?
		if (in_array('-help', $flags)) {
			$this->command('help');
			exit;
		}
		
		// Okay, instanciate the new command class
		$class = "Pipes_Command_".ucwords($command);
		$object = new $class($this->cli, $args, $flags);
		
		// Run run run
		$object->run();
	}
}