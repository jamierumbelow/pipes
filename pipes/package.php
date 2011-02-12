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

class Pipes_Package {
	
	/**
	 * Build a Pipes package
	 *
	 * @param string $dir The directory path
	 * @param string $pipespec The path to the pipespec file
	 * @return void
	 * @author Jamie Rumbelow
	 */
	static public function build($dir, $pipespec_file) {
		// Get pipespec
		$pipespec = include($pipespec_file);
		
		// Create the ZipArchive class and add all the files
		$pipe = new ZipArchive();
		$pipe->open($dir . $pipespec['name'] . '-' . $pipespec['version'] . '.pipe', ZipArchive::CREATE);
		
		foreach ($pipespec['files'] as $file) {
			if (file_exists($dir . $file)) {
				$pipe->addFile($dir . $file);
				$pipe->renameName($dir . $file, $file);
			} else {
				$this->cli->error("File: " . $file . " couldn't be found. Continuing anyway.");
			}
		}
		
		// Generate a JSON representation of the pipespec
		file_put_contents($pipespec_file . 'json', json_encode($pipespec));
		
		// Add the .pipespec back in for later!
		$pipe->addFile($dir . $pipespec_file . 'json');
		$pipe->renameName($dir . $pipespec_file . 'json', basename($pipespec_file . 'json'));
		
		// We're done. Fab.
		$pipe->close();
		
		// Get rid of the JSON
		unlink($dir . $pipespec_file . 'json');	
	}
	
	/**
	 * Given a location to a .pipe, extract and
	 * return the temporary directory name
	 *
	 * @param string $pipe_location The pipe's location
	 * @return string
	 * @author Jamie Rumbelow
	 */
	static public function extract($pipe_location) {
		// Create new ZipArchive
		$pipe = new ZipArchive();
		
		// Generate temp name
		$tmp = dirname($pipe_location).'/temporary_pipe_extraction_'.md5(time());
		
		// Open, extract and close
		$pipe->open($pipe_location);
		$pipe->extractTo($tmp);
		$pipe->close();
		
		// Return the temp name
		return $tmp;
	}
	
	/**
	 * Decode and return a new object from a Pipe's raw
	 * binary string.
	 *
	 * @return Pipes_Pipe
	 * @author Jamie Rumbelow
	 **/
	static public function decode_from_string($string) {
		// Write a temporary file
		$name = tempnam('/tmp', 'pipes_');
		file_put_contents($name, $string);
		
		// Extract that bad boy
		$pipeloc = Pipes_Package::extract($name);
		
		// Get the .pipespec
		$specs = preg_grep("/(.+)\.pipespecjson$/", scandir($tmp));
		$spec = $tmp . '/' . current($specs);
		
		// Load it
		$pipespec = json_decode(file_get_contents($spec));
		
		// Create the Pipes_Pipe object
		$pipe = new Pipes_Pipe($pipespec);
		
		// Return it!
		return $pipe;
	}
}