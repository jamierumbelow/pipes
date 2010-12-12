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
		$this->cli->success("INSTALLED");
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
				$this->cli->write("Install a pipe from a package name or URL", FALSE, 2);
		$this->cli->newline();
			$this->cli->write("Description:", FALSE, 1);
				$this->cli->write("The install command takes a package name or URL, tries to locate and retrieve it", FALSE, 2);
				$this->cli->write("and then installs it into the local packages directory.", FALSE, 2);
				$this->cli->newline();
				$this->cli->write("Passing a package name will cause Pipes to look through all the available sources", FALSE, 2);
				$this->cli->write("and try to find the package on those servers. If it finds a matching package, it ", FALSE, 2);
				$this->cli->write("will pull the package down into a temporary directory and install it.", FALSE, 2);
				$this->cli->newline();
				$this->cli->write("Passing a URL will cause Pipes to parse the URL before pulling it down. If you pass", FALSE, 2);
				$this->cli->write("a HTTP/S URL, Pipes will pull like normal, but Pipes also supports Git, Mercurial and", FALSE, 2);
				$this->cli->write("SVN as acceptable protocols for pipe distribution.", FALSE, 2);
	}
}