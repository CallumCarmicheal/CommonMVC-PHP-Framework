<?php
/**
 * User: Callum Carmicheal
 * Date: 25/09/2016
 * Time: 02:14
 */



namespace CommonMVC\MVC;


	class MVCErrorInformation {

		/**
		 * @var MVCContext $Context
		 */
		private $Context;
		public function __construct($Context) 		{ $this->Context = $Context; }

		/**
		 * @return MVCContext
		 */
		public function getContext()				{ return $this->Context; }
		public function setContext($Context) 		{ $this->Context = $Context; }
	}