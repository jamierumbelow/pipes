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

class Pipes_Command_Release {
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
		// Do we have a .pipe?
		if (!isset($this->args[0])) {
			$this->cli->error('Please specify a pipe (e.g. sudo pipes release example_pipe-1.0.0.pipe)');
			exit;
		}
		
		$pipe = $this->args[0];
		
		// Does our .pipe reference an actual valid pipe?
		$actual_pipe = Pipes_Package::decode_from_file($pipe);
		
		// Now, ask for the user's email and password
		Pipes_Cli::write("Please enter your http://pipesphp.org account details to push");
		
		$email = 	Pipes_Cli::read(			"Email:    ");
		$password = Pipes_Cli::read_silently(	"Password: ");
		
		// Great!
		$this->cli->newline();
		$this->cli->write('Uploading...');
		$this->cli->newline();
		
		// Make the API request
		$response = Pipes_Downloader::api_request('pipes', 'POST', array(
			'email' => $email,
			'password' => $password,
			'pipe' => '@'.$pipe
		));
		
		// Good or bad?
		if ($response->success) {
			$this->cli->success($response->message);
			exit;
		} else {
			$this->cli->error($response->message);
			exit;
		}
	}
}