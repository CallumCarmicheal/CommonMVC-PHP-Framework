# CommonMVC 
This is a hackable MVC framework that does not bind you to any rules except one.

You will be required to have a certain Structure for the controllers and define
the folder locations, except that it is a simple implementation.

# Getting Started
1. Clone or Download the the github repo
2. Look through the config folder to edit the framework to your specification
3. Open the website
4. Move around folders if you want
5. Hack away at the inner functions 
6. Make it your own
 
The framework is mostly documentated inside the code rather than a dedicated
website (YET). 

# Storing files
The folder is in a virtual path state so you need to set a files/assets directory
open the .htaccess file and either add or change the "assets" folder to your own
needs.

Everything else is taken care for you.

To access these files correctly please define CMVC_ROOT_URL with a path to your project and then use the global \url($path) function to get absolute paths to your files.

# Routes and Controllers
There will be no routes but that will be replaced with local file structure:

	Structure:
		Request: $path/$controller/$action
		File: {MVC_PROJECT_NAMESPACE}/controllers/$path/$controller &+ Controller.php
		Namespace: {MVC_PROJECT_NAMESPACE}\Controllers\$path
		Class: $controller &+ Controller.php
		Method: $action
		Returns: MVCResult


	Example: 
		Request: Home/Hello/World
		File: TestProject/controllers/Home/HelloController.php
		Namespace: TestProjectNamespace\Controllers\Home
		Class: HelloController
		Method: World
		Returns: MVCResult

# Please Note(s)
The controller names are case-insensitive so you're controller can be named however you like
as long as the controller is spelt correctly.