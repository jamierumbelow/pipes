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

class Pipes_Command_Build {
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
			$this->cli->error(".pipespec file required");
			exit;
		}
		
		// Pipespec file
		$pipespec_file = $this->args[0];
		$pipespec_dir = realpath(dirname($pipespec_file)) . '/';
		
		// Make sure we can access the .pipespec file
		if (!file_exists($pipespec_file)) {
			$this->cli->error("Invalid .pipespec file name");
			exit;
		}
		
		// Get pipespec
		$pipespec = include($pipespec_file);
		
		// Create the ZipArchive class and add all the files
		$pipe = new ZipArchive();
		$pipe->open($pipespec_dir . $pipespec['name'] . '-' . $pipespec['version'] . '.pipe', ZipArchive::CREATE);
		
		foreach ($pipespec['files'] as $file) {
			if (file_exists($pipespec_dir . $file)) {
				$pipe->addFile($pipespec_dir . $file);
				$pipe->renameName($pipespec_dir . $file, $file);
			} else {
				$this->cli->error("File: " . $file . " couldn't be found. Continuing anyway.");
			}
		}
		
		// Add the .pipespec back in for later!
		$pipe->addFile($pipespec_dir . $pipespec_file);
		$pipe->renameName($pipespec_dir . $pipespec_file, basename($pipespec_file));
		
		// We're done. Fab.
		$pipe->close();
		$this->cli->success("Successfully built pipe '".$pipespec['name'].'-'.$pipespec['version'].".pipe'");
	}
	
	/**
	 * Write the help message
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function help() {
		$this->cli->newline();
			$this->cli->write("Summary:", FALSE, 1);
				$this->cli->write("Build a pipe from a pipespec", FALSE, 2);
	}
}