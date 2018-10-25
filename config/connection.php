<?php

	return [
		'main'	=> env('DB_CONNECTION', 'mysql'), //main program
		'AOI'	=> env('DB_CONNECTION1', 'sqlsrv'), //default connections for AOI;
		'bigs'  => env('DB_CONNECTION2', 'sqlsrv1'), //default connections for bigs
	];