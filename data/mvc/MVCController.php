<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 21:11
 */

	namespace CommonMVC\MVC;

	class MVCController {
		/**
		 * @var bool
		 */
		protected $Enabled;

		/** @var bool */
		protected $IndexEnabled;

		/**
		 * @var bool
		 */
		protected $AuthRequired;

		/** @var string */
		protected $ControllerName;

		/** @var MVCContext */
		protected $Context;

		/**
		 * Sets if the current controller is disabled
		 * @param $Enabled bool State if the MVC Page has been disabled
		 */
		public function setState($Enabled) 	{ $this->Enabled = $Enabled; }
		public function setContext($ctx) 	{ $this->Context = $ctx; }

		public function getAuthRequired() 	{ return $this->AuthRequired; }
		public function getControllerName() { return $this->ControllerName; }
		public function getContext() 		{ return $this->Context; }

		public function isEnabled() 		{ return $this->Enabled; }
		public function isIndexEnabled() 	{ return $this->IndexEnabled; }
	}