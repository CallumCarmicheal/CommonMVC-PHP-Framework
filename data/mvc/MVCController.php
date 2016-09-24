<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 21:11
 */

	namespace CommonMVC\MVC;

	class MVCController {
		protected $Enabled;
		protected $AuthRequired;
		protected $ControllerName;
		protected $Context;

		/**
		 * Sets if the current controller is disabled
		 * @param $Enabled bool State if the MVC Page has been disabled
		 */
		public function setState($Enabled) 	{ $this->Enabled = $Enabled; }
		public function setMvcContext($ctx) { $this->Context = $ctx; }

		public function getAuthRequired() 	{ return $this->AuthRequired; }
		public function getEnabled() 		{ return $this->Enabled; }
		public function getControllerName() { return $this->ControllerName; }
	}