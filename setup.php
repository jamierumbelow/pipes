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
 * Figure out the ideal package directory
 *
 * @return string
 * @todo Make this a lot more robust
 * @author Jamie Rumbelow
 */
function __pipes_figure_out_package_dir() {
	$path = ini_get('include_path');
	
	// Windows is ridiculous... again...
	if (substr(PHP_OS, 0, 3) == 'WIN') {
		$paths = explode(';', $path);
	} else {
		$paths = explode(':', $path);
	}
	
	// Get rid of .
	array_shift($paths);
	
	// Get the first load directory
	return $paths[0];
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

// Get the current version
require_once 'pipes/version.php';

// Get the contents of the binary
$binary = file_get_contents('./bin/pipes');

// Copy over the binary
$f = fopen('/usr/bin/pipes', 'w');
fwrite($f, $binary);
fclose($f);
chmod('/usr/bin/pipes', 0755);

// Load the .pipespec to get a list of files
$pipespec = include('pipes.pipespec');

// Copy over the files manually
@mkdir(__pipes_figure_out_package_dir().'/pipes-'.$pipespec['version']);

foreach ($pipespec['files'] as $file) {
	proper_copy($file, __pipes_figure_out_package_dir() . '/pipes-'.$pipespec['version'] . '/' . $file);
}

// Symlink
symlink(__pipes_figure_out_package_dir() . '/' . $pipespec['name'].'-'.$pipespec['version'] . '/', __pipes_figure_out_package_dir() . '/' . $pipespec['name']);

// We're done!
echo("\033[0;32m" . "The 'pipes' command is now available. Thanks for installing Pipes!" . "\033[0m\n");