<?php 

/*
 * Define routes
*/

$config['route']['*']  = array(

		// Main page
		'/'		=>			'Main.index',
		'/getfloor' => 'DeviceControl.getFloor',
		'/getdevice' => 'DeviceControl.getDevice',
		'/getroom' => 'DeviceControl.getRoom',
		'/deletedevice' => 'DeviceControl.deleteDevice',
		'/scripts/delete' => 'DeviceControl.deleteScript',
		'/updatedevice' => 'DeviceControl.cuDevice',
		'/floor/@id' => 'DeviceControl.floor(@id)',
		'/floors' => 'DeviceControl.floors',
		'/scripts' => 'DeviceControl.scripts',
		'/cron' => 'Cron.runnable',
		'/room/@id' => 'DeviceControl.room(@id)',
		'/ajax/device' => 'DeviceControl.deviceAjax',
		'/ajax/script' => 'DeviceControl.ajaxScript',
		'/ajax/floor' => 'DeviceControl.ajaxFloor',
		'/ajax/room' => 'DeviceControl.ajaxRoom',
		'/ajax/floors/sort' => 'DeviceControl.ajaxFloorsSort',
		'/automation' => 'DeviceControl.scripts',
		'/automation/getscript' => 'DeviceControl.getScript',
		'/logs' => 'DeviceLogs.getList',
		'/logs/list' => 'DeviceLogs.jsonList',
		'/logs/run' => 'DeviceLogs.runEntry',

		'/macros' => 'MacroHandler.index',
		'/macros/ajax' => 'MacroHandler.ajaxMacro',
		'/macros/get' => 'MacroHandler.getMacro',
		'/macros/delete' => 'MacroHandler.deleteMacro',

		'/scripthandler' => 'MacroHandler.scriptHandler',



		// Boxee routes.
		'/boxee' => 'BoxeeBox.index',
		'/boxee/pair' => 'BoxeeBox.pair',
		'/boxee/command' => 'BoxeeBox.command',
		'/boxee/ajax/device' => 'BoxeeBox.ajaxBoxee',
		'/boxee/ajax/dirlisting' => 'BoxeeBox.dirlisting',
		'/boxee/files' => 'BoxeeBox.files',
		'/boxee/imdb' => 'BoxeeBox.imdbapi',
		'/boxee/selectmedia/@media' => 'BoxeeBox.setUseMedia(@media)',
		'/boxee/selectboxee' => 'BoxeeBox.setUseBoxee',
		'/boxee/remote' => 'BoxeeBox.remote',


		'/install/step1' => 'Base.installDB',
		'/install/step2' => 'Base.installWriteConfig',



		// Link to sample unit testing.
		'/unittests' => 'tests/Tests.index',


		// Development tools

		// Useful for developing , not for production.
		'/dev/testroutes' => 		'/reks/tester/Routes.index',

		// Unit test the whole framework.
		'/dev/unittest-framework' =>  '/reks/tests/Main.index',



		// Error handlers
		'500'		=>		'Errors.internalServerError',
		'404'		=>		'Errors.pageNotFound',


);
