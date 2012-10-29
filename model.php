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

uses('model');

/* Ensure that your configuration constant (see setup.php) is defined, even if
 * it's null.
 */
if(!defined('SAMPLE_DB')) define('SAMPLE_DB', null);

class Sample extends Model
{
	/* See the description in __construct(): this array contains the list of
	 * keys in the array passed to getInstance() and the constructor which
	 * are treated as database connection URIs and whose connection instances
	 * are stored in properties of the same name.
	 *
	 * Any keys named here, if set, must have values which are URIs with schemes
	 * supporting the IDatabase interface (see lib/db.php for more information).
	 */
	protected $databases = array('db');

	/* When you define a new model class, you must override the getInstance()
	 * static method so that YourClass::getInstance() will do the right
	 * thing.
	 */
	protected function getInstance($args = null)
	{
		/* $args['class'] is always set to the name of the class to create an
		 * instance of by the time control flow reaches Model::getInstance().
		 *
		 * If a descendant sets $args['class'] explicitly, we should leave the
		 * value as-is.
		 *
		 * You can use this mechanism to create instances of one of a number of
		 * classes depending upon the parameters passed (for example, the
		 * database connection URI scheme).
		 */
		if(!isset($args['class']))
		{
			$args['class'] = 'Sample';
		}
		/* If the database wasn't specified explicitly in the arguments,
		 * use the configuration constant.
		 *
		 * If you change the properties you use for database connections,
		 * (see $this->databases) you should adjust this logic accordingly.
		 */
		if(!isset($args['db']))
		{
			$args['db'] = SAMPLE_DB;
		}
		/* Model classes are singletons. By default, attempting to obtain an
		 * instance with a different URI in $args['db'] will result in a new
		 * instance being created, keyed to that URI. If you wish, you can
		 * have finer-grained control over this process by explicitly
		 * setting $args['instanceKey'].
		 */
		if(!isset($args['instanceKey']))
		{
			/* Example:—
			 *
			 * Allow callers to optionally specify an 'instance number' to
			 * permit multiple instances to be created using the same database
			 * connection.
			 *
			 * Note that 'instanceNumber' is only used here for illustration;
			 * there is nothing which requires you follow this pattern at all,
			 * let alone use this argument.
			 */
			if(!isset($args['instanceNumber']))
			{
				$args['instanceNumber'] = 0;
			}
			$args['instanceKey'] = $args['db'] . '-' . $args['instanceNumber'];
		}
		/* Pass the arguments to Model::getInstance() to actually create the
		 * instance and handle the singleton logic.
		 */ 
		return parent::getInstance($args);
	}
	
	public function __construct($args)
	{
		/* The constructor is passed any arguments passed to Model::getInstance()
		 *
		 * $this->databases is iterated, and if any members of this array
		 * match keys within $args, then it shall be treated as a database
		 * connection URI and the resulting connection instance will be
		 * stored in a property of the same name. By default,
		 * $this->databases is just array('db'), which means $args['db']
		 * (if set) will be used to establish a connection stored in
		 * $this->db.
		 */
		 parent::__construct($args);
	}
	
	/* A typical model method */
	public function obtain($id)
	{
		return $this->db->row('SELECT * FROM {things} WHERE "id" = ?', $id);
	}
}
