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
require_once 'pipes/pipe.php';
require_once 'pipes/version.php';
require_once 'pipes/helpers.php';
require_once 'pipes/constants.php';

set_exception_handler(array('Pipes_Cli', 'exception'));
set_error_handler(array('Pipes_Cli', 'error_handler'));

class Pipes {
	static $commands = array('install', 'uninstall', 'list', 'search', 'sources', 'build', 'release', 'help');
	static $config = array();
	public $cli;
	
	/**
	 * Output a command's help message
	 *
	 * @param object $cli Pipes_Cli object
	 * @param string $command Command name
	 * @return void
	 * @author Jamie Rumbelow
	 */
	static public function command_help($cli, $command) {
		// Load the command file
		if (file_exists(PIPES_DIR . 'pipes/commands/'.$command.'.php')) {
			require_once PIPES_DIR . 'pipes/commands/'.$command.'.php';
		} else {
			// Default to help, but show an error
			$cli->error("Unknown command '$command'");
			static::command_help($cli, 'help');
			exit;
		}
		
		// Okay, instanciate the new command class
		$class = "Pipes_Command_".ucwords($command);
		$object = new $class($cli);
		
		// Run run run
		if (method_exists($object, 'help')) {
			$object->help();
		} else {
			$cli->error("No help docs for command '$command'");
		}
	}
	
	/**
	 * Write the config file
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	static public function write_config() {
		file_put_contents(PIPES_DIR . 'config.json', json_encode(self::$config));
	}
	
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
		// Version flag?
		if (isset($flags["-version"])) {
			$this->command('version');
			exit;
		}
		
		// Help flag?
		if (isset($flags["-help"])) {
			$this->command('help');
			exit;
		}
		
		// Load the config file
		$json = file_get_contents(PIPES_DIR . 'config.json');
		self::$config = json_decode($json);
		
		// Load the command file
		if ($command) {
			if (file_exists(PIPES_DIR . 'pipes/commands/'.$command.'.php')) {
				require_once PIPES_DIR . 'pipes/commands/'.$command.'.php';
			} else {
				// Default to help, but show an error
				$this->cli->error("Unknown command '$command'");
				$this->command('help');
				exit;
			}
		} else {
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