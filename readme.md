# Getting Started
1. Clone or Download the the github repo
2. Open the file "config\database.php" and "config\cmvc.php"
3. Edit the settings to specify your current database

That is your framework setup
- Please take a look at the docs (TODO)
- Please take a look at the examples (TODO)

# Storing files
The folder is in a virtual path state so you need to set a files/assets directory
open the .htaccess file and either add or change the "assets" folder to your own
needs.

Everything else is taken care for you.

# Todo
Support for multiple projects?

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
