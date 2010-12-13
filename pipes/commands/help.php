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

class Pipes_Command_Help {
	public function __construct($cli, $args = array(), $flags = array()) {
		$this->cli = $cli;
		$this->args = $args;
	}
	
	/**
	 * Run the command
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function run() {
		$this->cli->write("Pipes is a sexy PHP package manager.");
		
		// If they don't pass anything, default
		if (empty($this->args)) {
			$this->help();
		} else {
			Pipes::command_help($this->cli, $this->args[0]);
		}
	}
	
	/**
	 * Write default help messages
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function help() {
		$this->cli->newline();
			$this->cli->write("Usage:", FALSE, 1);
				$this->cli->write("pipes --help", FALSE, 2);
				$this->cli->write("pipes --version", FALSE, 2);
				$this->cli->write("pipes command [arguments...] [flags...]", FALSE, 2);
		$this->cli->newline();
			$this->cli->write("Common Commands:", FALSE, 1);
				$this->cli->write("pipes install <package-name>", FALSE, 2);
				$this->cli->write("pipes search <term>", FALSE, 2);
				$this->cli->write("pipes install <package>", FALSE, 2);
				$this->cli->write("pipes list --local", FALSE, 2);
		$this->cli->newline();
			$this->cli->write("Help:", FALSE, 1);
				$this->cli->write("pipes help <command>", FALSE, 2);
				$this->cli->write("Go to http://PIPES_URL", FALSE, 2);
	}
}