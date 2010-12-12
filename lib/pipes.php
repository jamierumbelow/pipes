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
	static $commands = array();
}