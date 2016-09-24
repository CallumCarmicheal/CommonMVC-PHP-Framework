#I am currently designing the flow of the MVC.
This means everything is not set in stone

# Routes and Controllers
There will be no routes but that will be replaced with local file structure:

	Structure:
		Request: $path/$controller/$action
		File: data/controllers/$path/$controller &+ Controller.php
		Namespace: CommonMVC\Controllers\$path
		Class: $controller &+ Controller.php
		Method: $action
		Returns: MVCResult


	Example: 
		Request: Home/Hello/World
		File: data/controllers/Home/HelloController.php
		Namespace: CommonMVC\Controllers\Home
		Class: HelloController
		Method: World
		Returns: MVCResult

# Please Note(s)
Please note every first letter will be capitalised
	Example: home/hello world/hi
	Output: Home/Hello world/Hi

So please make sure your Namespace, Class, Method names are capitalised
The folders do not require a capital letter.