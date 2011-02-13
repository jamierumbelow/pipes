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

// Get a few necessities
require_once 'pipes/helpers.php';
require_once 'pipes/constants.php';
require_once 'pipes/version.php';

/**

	Windows Support To-do (Setup Only)
	==================================
	
	- Create a BAT executable that calls "php <pathToPipesExecutable> arguments".
	- Remove the "#/usr/bin/php" first line, else this will get send as output to the user.
	- Save the batch file to a directory in the Windows system path variable.
	-- Unfortunately, the only gaurantees are "C:\WINDOWS" or "C:\WINDOWS\system32", and I very much doubt PHP will have permissions to write there.
	-- The alternative is to install to the PHP installation directory, though I currently do not know of any way to determine this via PHP.

/**/


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
@mkdir(PIPES_PACKAGE_DIR.'/pipes-'.$pipespec['version']);

foreach ($pipespec['files'] as $file) {
	proper_copy($file, PIPES_PACKAGE_DIR . '/pipes-'.$pipespec['version'] . '/' . $file);
}

// Symlink
@symlink(PIPES_PACKAGE_DIR . '/' . $pipespec['name'].'-'.$pipespec['version'] . '/', PIPES_PACKAGE_DIR . '/' . $pipespec['name']);

// We're done!
echo("\033[0;32m" . "The 'pipes' command is now available. Thanks for installing Pipes!" . "\033[0m\n");