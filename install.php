<?php

/* Copyright 2012 Mo McRoberts.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/* A file named 'install.php' within a module will be loaded automatically
 * by the Eregansu Installer as part of the configuration of an application.
 *
 * The file must define a class named <modulename>ModuleInstall, where
 * <modulename> matches the directory name used by the module.
 *
 * For example, if you have:
 *
 *   app/widgets/install.php
 *
 * ...then you would define a class named WidgetsModuleInstall.
 *
 * The class must be a descendent of ModuleInstaller (defined in
 * eregansu/install/module.php).
 */
 
class SampleModuleInstall extends ModuleInstaller
{
	public function __construct($installer, $name, $path)
	{
		/* This method is invoked, passing an instance of the Eregansu
		 * Installer class, the name of this module, and the full path
		 * to its directory, including trailing slash.
		 *
		 * It should not be normally necessary to override this method.
		 *
		 * The inherited constructor sets $this->installer, $this->name,
		 * and $this->path based upon the parameters supplied.
		 */
		parent::__construct($installer, $name, $path);
	}

	/* canBeSoleWebModule() is invoked to determine whether this module
	 * is a web application and so can be accessed via HTTP at the root
	 * of the application if no other web applications are defined.
	 *
	 * If this module provides auxiliary functionality, or does not
	 * expose any routes via HTTP, it should return false (the default).
	 *	 
	 */	
	public function canBeSoleWebModule()
	{
		return false;
	}

	/* canCoexistWithSoleWebModule() is invoked if canBeSoleWebModule()
	 * returns false: it indicates that whatever this module does, it
	 * can coexist peacefully with a web application module which has been
	 * designated the sole web module for this application.
	 *
	 * If this module defines no HTTP routes, or provides purely auxiliary
	 * functionality, then this method can return true. Returning false
	 * here (the default) indicates that this module and other web modules
	 * must all be assigned specific routes (/module1, /module2, etc.).
	 */
	public function canCoexistWithSoleWebModule()
	{
		return false;
	}
	
	/* writeAppConfig() is invoked to ask the module to write any entries
	 * to appconfig.php. A resource representing the open file handle is
	 * passed as the $file parameter. $isSoleWebModule will be true if
	 * this module has been selected as the sole web module.
	 * $chosenSoleWebModule will contain the name of the selected sole web
	 * module, or null if no module is the sole web module.	
	 */
	public function writeAppConfig($file, $isSoleWebModule = false, $chosenSoleWebModule = null)
	{
		/* Use $this->writeWebRoute() to write the configuration needed to route
		 * to your application class (see app.php) via HTTP.
		 */
		$this->writeWebRoute($file, $isSoleWebModule);
		/* However, you can override the name of the file, class, and even
		 * module name if needed. By default, the filename is app.php, the
		 * class name is <modulename>App, and the module name is the name
		 * of this module.
		 */
		$this->writeWebRoute($file, $isSoleWebModule, 'webapp.php', 'SampleWebApp', 'othermodule');
		/* If you have a database schema management class, you should add it
		 * here (defaults to <modulename>Schema within schema.php).
		 *
		 * See samples/schema.php for information on how to write a
		 * schema class.
		 */
		$this->writeModuleSchema($file);
		/* Alternatively, you can override the defaults: */
		$this->writeModuleSchema($file, 'MySchemaClass', 'database-schema.php');
		/* You can also write directly to appconfig.php if needed: */
		fwrite($file, "define('SAMPLE_CONSTANT', 'test');\n");
	}
	
	/* writeInstanceConfig() is invoked to ask the module to write any
	 * placeholders to the per-instance configuration file, config.php.
	 */
	public function writeInstanceConfig($file)
	{
		/* Use $this->writePlaceholderDBIri() in order to write a placeholder
		 * database connection constant to the file. The minimum required is:
		 */
		$this->writePlaceholderDBIri($file);
		/* Which will write a commented-out constant named MODULENAME_DB with a
		 * a sample mysql connection string. You can override the default constant,
		 * database name, scheme and query string:
		 */
		$this->writePlaceholderDBIri($file, 'TEST_DATABASE', 'testdb', 'pgsql', 'autoconnect=1&reconnectquietly=0');
		/* You can also write directly to appconfig.php too: */
		fwrite($file, "/* Define this to enable frobbing of the widgets: */\n");
		fwrite($file, "/* define('FROB_WIDGETS', true); */\n");
	}

	/* createLinks() is invoked to create any symbolic links which might be
	 * needed to use this module. By default, it simply calls
	 * $this->linkTemplates() which checks if <module-path>/templates exists,
	 * and if so, creates a link to it from public/templates/<module-name>.
	 *
	 * You shouldn't normally need to override createLinks(), but you may
	 * wish to if you have multiple template directories, for example.
	 */
	 
	public function createLinks()
	{
		parent::createLinks();
		/* Link another template directory. The below will create a link
	     * from public/templates/sample-extra to <module-path>/extra-templates
		 */
		$this->linkTemplates('extra-templates', 'sample-extra');
	}

}

/* If for some reason you need to have an installer class with a different
 * name, you can specify it by returning it from this file.
 */

/* return 'AlternativeInstallerClass'; */
