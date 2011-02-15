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

// Get the contents of the binary file.
$binary = file_get_contents('./bin/pipes');
// What if PHP isn't installed in "C:/Program Files/PHP"? WELL SCREW IT, YOU
// THINK OF A BETTER INSTALLATION PATH ON WINDOWS.
$exec = '/usr/bin/pipes';
// If we're on Windows, extra installation steps are required.
if(PIPES_IS_WINDOWS) {
	// Firstly, remove the first line of the binary that specifies which binary to
	// use on *nix systems.
	$binary = substr($binary, strpos($binary, "\n") + 1);
	// Next, determine the current users home directory. This will return the path
	// to the logged in user, regardless of running as Administrator. This will be
	// the pipes installation folder.
	$installpath = path(trim(shell_exec('echo %USERPROFILE%'))) . 'pipes/';
	$exec = $installpath . 'pipes.php';
	// Now, create a Batch file to act as our executable wrapper, it is a
	// workaround to map "pipes args" to "php pipes.php args". YOU NEED TO RUN
	// THIS AS <del>ROOT</del><del>ADMIN</del><ins>RIGHT-CLICK COMMAND-PROMPT IN
	// THE START MENU AND SELECT "RUN AS ADMINISTRATOR", THEN CLICK YES</ins>.
	$b = fopen('C:/WINDOWS/pipes.bat', 'w');
	fwrite($b, 'php "' . $exec . '" %*');
	fclose($b);
	chmod('C:/WINDOWS/pipes.bat', 0755);
	// Create the Pipes installation directory before we try to create the binary
	// there. Set the permissions to "hey, come screw with me, I won't fight
	// back", otherwise we won't be able to write to it later, since we are in...
	// What do you call root on Windows? :\
	mkdir($installpath, 0777, true);
}
// Copy over the binary.
$f = fopen($exec, 'w');
fwrite($f, $binary);
fclose($f);
chmod($exec, 0755);

/**
 * Executables installed, move on to copying core files over.
 */

// Load the .pipespec to get a list of files
$pipespec = include('pipes.pipespec');
$pipe_package_dir = PIPES_PACKAGE_DIR . 'pipes-' . $pipespec['version'] . '/';

// Copy over the files manually
@mkdir($pipe_package_dir, 0755, true);

foreach ($pipespec['files'] as $file) {
	proper_copy($file, $pipe_package_dir . $file);
}

// Symlink
@symlink(PIPES_PACKAGE_DIR . '/' . $pipespec['name'].'-'.$pipespec['version'] . '/', PIPES_PACKAGE_DIR . '/' . $pipespec['name']);

// We're done!
echo("\033[0;32m" . "The 'pipes' command is now available. Thanks for installing Pipes!" . "\033[0m\n");
