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

/**
 * Run paths through this function to make sure they are valid, and make sure
 * their format is consistent.
 *
 * @access  public
 * @param   string $path
 * @return  string|false
 * @author  Alexander Baldwin
 */
if(!function_exists('path')) {
	function path($path) {
		// The the path is not valid
		if(!is_string($path) || !is_string($path = realpath($path))) {
			return false;
		}
		// Replace any backslashes with obliques, as Windows can handle both as
		// directory separators. Remove any repeating obliques.
		$path = preg_replace('#//+#', '/', str_replace('\\', '/', $path));
		// Ensure the returning path ends in a trailing slash if it is a directory.
		return is_dir($path) ? rtrim($path, '/') . '/' : $path;
	}
}

/**
 * Figure out the ideal package directory
 *
 * @return string
 * @todo Make this a lot more robust
 * @author Jamie Rumbelow
 */
function pipes_figure_out_package_dir() {
	$path = ini_get('include_path');
	// Use PATH_SEPARATOR, so we don't have to worry about the host OS.
	$paths = explode(PATH_SEPARATOR, $path);
	// Get rid of CWD.
	array_shift($paths);
	// Get the first *valid* load directory, returning a consistently formatted
	// path.
	foreach($paths as $path) {
		$path = path($path);
		if(is_string($path) && is_dir($path)) {
			return $path;
		}
	}
	// If on the terrible off-chance we don't have an include directory (not gonna
	// happen), return the CWD.
	return path('.');
}

/**
 * copy() like real men
 *
 * @return void
 * @author Jamie Rumbelow
 */
function proper_copy($src, $dest) { 
	// Check the directory exists
	$dir = (substr(basename(dirname($src)), 0, 1) !== '.') ? basename(dirname($src)) : '';

	if (!file_exists(dirname($dest))) {
		mkdir(dirname($dest));
	}
	
	// Copy it over
	return copy($src, $dest);
}