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
 * @todo Windows support
 **/

// Use this instead of PHP_OS, as it will determine the host OS, rather than the
// OS this installation of PHP was compiled for.
defined('PIPES_IS_WINDOWS') || define(
	'PIPES_IS_WINDOWS',
	strpos(strtolower(php_uname('s')), 'window') !== FALSE
);

define('PIPES_DIR', dirname(__FILE__) . '/../');
define('PIPES_PACKAGE_DIR', pipes_figure_out_package_dir() . '/');