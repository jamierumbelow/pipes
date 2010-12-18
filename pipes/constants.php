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

if (substr(PHP_OS, 0, 3) == 'WIN') {
    define('PIPES_IS_WINDOWS', TRUE);
} else {
	define('PIPES_IS_WINDOWS', FALSE);
}

define('PIPES_DIR', dirname(__FILE__) . '../');
define('PIPES_PACKAGE_DIR', pipes_figure_out_package_dir() . '/');