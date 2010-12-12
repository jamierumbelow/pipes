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

/**
 * The Pipes command line system. Provides all of Pipes'
 * IO handling, parses command line flags and such.
 *
 * @package pipes
 * @author Jamie Rumbelow
 */
class Pipes_Cli {
	public $command = '';
	public $command_arguments = array();
	public $flags = array();
	
	protected $raw_argv = array();
	
	/**
	 * Creates the class and parses arguments
	 *
	 * @param array $args Array of argv arguments
	 * @author Jamie Rumbelow
	 */
	public function __construct($args = array()) {
		if (!empty($args)) {
			$this->parse_arguments($args);
			$this->raw_argv = $args;
		}
	}
	
	/**
	 * Parses an array of arguments
	 *
	 * @param array $args Array of args
	 * @return array
	 * @author Jamie Rumbelow
	 */
	public function parse_arguments($args) {
		// Examine the first argument. It could be this script.
		// If it is, get rid of it!
		if ($args[0]) {
			if (substr($args[0], strlen($args[0])-strlen(PIPES_CMD_FILENAME)) == PIPES_CMD_FILENAME) {
				array_shift($args);
			}
		}
		
		// Keep track of whether we're mid-flagging
		$flagify = FALSE;
		$flagify_key = '';
		
		// Loop through the arguments
		foreach ($args as $key => $arg) {
			// Are we mid flagging? If so, pick up on it ASAP
			if ($flagify) {
				$flagify = FALSE;
				
				// If the next value isn't another flag...
				if (substr($arg, 0, 1) !== '-') {
					$this->flags[$flagify_key] = $arg;
					continue;
				}
			}
			
			// Take a look. Is this a command?
			if (in_array($arg, Pipes::$commands)) {
				if (empty($this->command)) {
					// We've got our command
					$this->command = $arg;
					continue;
				}
			}
			
			// Okay, is it a flag?
			if (substr($arg, 0, 1) == '-') {
				// Track we're mid-flagging
				$flagify = TRUE;
				$this->flags[(string)substr($arg, 1)] = TRUE;
				$flagify_key = (string)substr($arg, 1);
				
				continue;
			}
			
			// It must be a command argument
			$this->command_arguments[] = $arg;
		}
	}
}