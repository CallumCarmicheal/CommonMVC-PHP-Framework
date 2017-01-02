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

		/** @var string */
		protected $ControllerName;

		/** @var MVCContext */
		protected $Context;

		public function setContext($ctx) 	{ $this->Context = $ctx; }
		public function getControllerName() { return $this->ControllerName; }
		public function getContext() 		{ return $this->Context; }

		public function isEnabled() 		{ return $this->Enabled; }
	}