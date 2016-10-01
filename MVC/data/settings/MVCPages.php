<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 04:54
 */


namespace CommonMVC\Settings;
	use CommonMVC\MVC\MVCHandler;
	use CommonMVC\MVC\MVCPage;

	class MVCPages {

		private static $_PagesSetup = false;
		private static $_MVCHandler;

		public static function SetupPages() {
			if(self::$_PagesSetup) return;

			$pages = array();

			/*
			 * Here you want to set up the pages that will be used for the MVC
			 **/
			$pages[] = new MVCPage();
			$defaultPage = null;


			/* The mvc pages */ {

				// Default page
				// Virtual: Home, MVC: Home
				$defaultPage = new MVCPage("Home", "Home", false, true);

				// Virtual: Server, MVC: Server
				$pages[] = new MVCPage("Server", "Server", true, true);
			}

			MVCHandler::registerMVCPage($defaultPage);
			MVCHandler::setDefaultPage($defaultPage);
			MVCHandler::registerMVCArray($pages);

			self::$_PagesSetup = true;
		}
	}