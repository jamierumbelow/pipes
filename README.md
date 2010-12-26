Pipes: Sexy PHP package management
==================================

Pipes is a new generation in PHP package management; a clear, easy to use and sophisticated command-line utility, a concise and succinct package format and all written nothing but glorious PHP. Pipes is quick and efficient, and makes installing third-party libraries a breeze.

Installing Pipes
----------------

Installing Pipes straight from Git is easy:

	git clone git@github.com:jamierumbelow/pipes.git pipes
	cd pipes/
	sudo php setup.php
	
You can also install the most recent version of Pipes via Pipes itself:

	sudo pipes install pipes
	
Basic Usage
-----------

You can install pipes from the pipes repository, a .pipe file or a URL.

	sudo pipes install example_pipe
	sudo pipes install ./example_pipe-1.0.0.pipe
	sudo pipes install http://example.com/example_pipe-1.0.0.pipe
	
Pipes installs the pipe into your PHP include path. Once installed, you can require the necessary files within the pipe. These files will be in a directory with the pipe name.

	<?php
	
	require 'example_pipe/example_pipe.php';
	$e = Example_Pipe;
	$e->some_method();

Advanced Usage
--------------
	
Using the pipes command you can search through and list the pipes:

	sudo pipes search example_
	sudo pipes list --remote # View all remote pipes 
	sudo pipes list --local # View all installed pipes
	
By default, Pipes will search through the default Pipes repository. You can setup other repositories by using the 'sources' command.

	sudo pipes sources add http://example.com/pipes-repository
	sudo pipes sources remove http://example.com/pipes-repository
	
You can uninstall pipes with the uninstall command

	sudo pipes uninstall example_pipe
	
Creating your own .pipe
-----------------------

Creating your own pipe is easy. Create a .pipespec file, with the name of your desired pipe, in the directory you wish to create your pipe. Your .pipespec file should return an array, and look something like this:

	<?php return array(
		'name'			=> 'example_pipe',
		'version'		=> '1.0.0',
		'description'	=> 'A quick example test pipe',
		'authors'		=> array('Jamie Rumbelow <jamie@jamierumbelow.net>'),
		'files'			=> array(
			'example_pipe.php'
		)
	);
	
*name* and *version* will be used in file names, so they need to be suitably named and only use basic characters. The *files* array should be a list of file names to compile into the pipe, with their paths relative to the .pipespec file. This file is a standard PHP file, so you could easily use something like **scandir()** to dynamically generate a list of files. Include any README and LICENSE files in the *files* array.

You can then build the pipe from the pipespec by using the *build* command, and then push up to the server with the *release* command.

	sudo pipes build example_pipe.pipespec
	sudo pipes release example_pipe-1.0.0.pipe