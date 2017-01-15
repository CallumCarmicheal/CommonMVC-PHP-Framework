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