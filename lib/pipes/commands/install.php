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

class Pipes_Command_Install {
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
			$this->cli->error("Package name or URL needed");
		}
		
		// Package name or URL
		$package_name_or_url = $this->args[0];
		
		// Is this a URL?
		if (filter_var($package_name_or_url, FILTER_VALIDATE_URL)) {
			$this->install_from_url($package_name_or_url);
			exit;
		}
		
		// Is it a .pipe?
		$pathinfo = pathinfo($package_name_or_url);
		
		if ($pathinfo['extension'] == 'pipe') {
			$this->install_from_local_pipe($package_name_or_url);
			exit;
		}
		
		// It's a remote package...
		$this->install_from_package_name($package_name_or_url);
		
		// $this->cli->success("Installed package " . $this->args[0]);
	}
	
	/**
	 * Install a pipe from a local .pipe file
	 *
	 * @param string $pipe_file The path to the pipe file
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function install_from_local_pipe($pipe_file) {
		$pipe_location = realpath(dirname($pipe_file)) . '/' . $pipe_file;
		
		// Check we can access the file
		if (!file_exists($pipe_location)) {
			$this->cli->error("Invalid .pipe file");
			exit;
		}
		
		// Let's not be paranoid, let's just assume that the pipe
		// is absolutely fine. Install it into the package directory!
		$this->install_pipe($pipe_location);
	}
	
	/**
	 * Install a pipe
	 *
	 * @param string $pipe_location The absolute path to the .pipe
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function install_pipe($pipe_location) {
		$pathinfo = pathinfo($pipe_location);
		
		// Copy it over
		if (copy($pipe_location, PIPES_PACKAGE_DIR . $pathinfo['basename'])) {
			$pipe_name = basename($pipe_location, '.' . $pathinfo['extension']);
			
			// Make the directory
			mkdir(PIPES_PACKAGE_DIR . $pipe_name);
			
			// Extract that mofo
			$pipe = new ZipArchive();
			$pipe->open(PIPES_PACKAGE_DIR . $pathinfo['basename']);
			$pipe->extractTo(PIPES_PACKAGE_DIR . $pipe_name . '/');
			$pipe->close();
			
			// We're done! Fab
			return TRUE;
		} else {
			$this->cli->error("Whoa. Bad permissions on ".PIPES_PACKAGE_DIR." dude, bad permissions...");
			exit;
		}
	}
	
	/**
	 * Write the help message
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 */
	public function help() {
		$this->cli->newline();
			$this->cli->write("Flags:", FALSE, 1);
				$this->cli->write(str_pad("-d", 5) . "Install any missing dependencies (default TRUE)", FALSE, 2);
				$this->cli->write(str_pad("-l", 5) . "Download to working directory, don't install", FALSE, 2);
				$this->cli->write(str_pad("-v", 5) . "Enable verbose output", FALSE, 2);
				$this->cli->write(str_pad("-q", 5) . "Be very, very quiet", FALSE, 2);
		$this->cli->newline();
			$this->cli->write("Summary:", FALSE, 1);
				$this->cli->write("Install a pipe from a package name, .pipe or URL", FALSE, 2);
		$this->cli->newline();
			$this->cli->write("Description:", FALSE, 1);
				$this->cli->write("The install command takes a package name, .pipe file or URL, tries to locate and retrieve it", FALSE, 2);
				$this->cli->write("and then installs it into the local packages directory.", FALSE, 2);
				$this->cli->newline();
				$this->cli->write("Passing a package name will cause Pipes to look through all the available sources", FALSE, 2);
				$this->cli->write("and try to find the package on those servers. If it finds a matching package, it ", FALSE, 2);
				$this->cli->write("will pull the package down into a temporary directory and install it.", FALSE, 2);
				$this->cli->newline();
				$this->cli->write("Passing a .pipe file will install the pipe directly", FALSE, 2);
				$this->cli->newline();
				$this->cli->write("Passing a URL will cause Pipes to parse the URL before pulling it down. If you pass", FALSE, 2);
				$this->cli->write("a HTTP/S URL, Pipes will pull like normal, but Pipes also supports Git, Mercurial and", FALSE, 2);
				$this->cli->write("SVN as acceptable protocols for pipe distribution.", FALSE, 2);
	}
}