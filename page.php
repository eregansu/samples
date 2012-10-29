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

/* Ensure our model class is available */
require_once(dirname(__FILE__) . '/model.php');

class SamplePage extends Page
{
	/* The name of the model class. If unset, $this->model
	 * will be remain null; otherwise, an instance of that
	 * class will be obtained.
	 */
	protected $modelClass = 'SampleModel';
	
	protected function getObject()
	{
		/* Invoke the parent method; if it returns anything other
		 * than boolean true, return that value immediately.
		 */
		$r = parent::getObject();
		if($r !== true)
		{
			return $r;
		}
		/* If we haven't already been supplied with an object... */
		if($this->object === null)
		{
			/* Retrieve the ID of an object from the request */
			$id = $this->request->consume();
			/* Ask the model to obtain the object */
			$this->object = $this->model->obtain($id);
			/* If it failed, throw an error */
			if($this->object === null)
			{
				return $this->error(Error::OBJECT_NOT_FOUND);
			}
		}
		/* Return true to indicate that processing of this request
		 * should proceed. If false is returned instead, it indicates
		 * that all processing and output for this request has been
		 * completed.
		 */
		return true;
	}
	
	protected assignTemplate()
	{
		parent::assignTemplate();
		/* Substitute any additional template variables */
		$this->vars['section'] = 'sample';
	}
	
}