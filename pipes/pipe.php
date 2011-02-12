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

class Pipes_Pipe {
	public $name 		= '';
	public $version 	= '';
	public $full_name 	= '';
	public $description = '';
	
	public $files 		= array();
	
	/**
	 * Pipes_Pipe is a simple object that represents
	 * a single pipe. The constructor takes a pipespec
	 * and normalises it.
	 *
	 * @param object $pipespec 
	 * @author Jamie Rumbelow
	 */
	public function __construct($pipespec) {
		$this->name 		= $pipespec->name;
		$this->version 		= $pipespec->version;
		$this->full_name 	= $pipespec->name . '-' . $pipespec->version;
		$this->files		= $pipespec->files;
		
		foreach ($pipespec as $key => $value) {
			$this->$key = $value;
		}
	}
}