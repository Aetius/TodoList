#Contributions to the application ToDoList

Thanks for your contribution in this project. All contributions are welcome will be fully credited. 
All contributions have to be made via Pull Request on Github. 

##a)	Bugs : 
If you find a bug, please inform us : it will help to improve the application. 
To report a bug, use the issue functionality on Github : 
-	Use the title to describe the problem
-	Use the description to explain the bug, and the way to reproduce it. 

If you find a security problem, please act the same way, and contact us as soon as possible. Our team will fix it quickly. 

##b)	Proposing a change
To suggest a new functionality or a change in a functionality, please use a pull request. 
To do that, you will have to :
-	Fork the project (click on the ‘Fork’ button); 
-	Clone the project in your OS, to create a new ToDoList directory; 
-	Test the project; 
-	Create a new branch for your change. The branch format have to be : functionality_component; 
Any branch have to start to master branch.
-	Make your modification, and test them; 
-	Create a pull request in your project;
-	Click on “compare & pull request” to add your branch to the origin project. You have to explain the new functionality in it. 
-	Resolve the conflicts if there is any, then valid your pull request; 
-	Our team will send you a feedback (questions about your branch, validation, denial or other). 

##c)	Testing
All new functionalities have to be tested (unit or functional test). Before each commit, we advise you to start a test with phpunit to verify that your new modification doesn’t break anything. 
Before a pull request to the origin project, you have to test the new branch. 
#####Reminder : 
-	Unit tests are used to test a method, a service; 
-	Functional tests are used to test a route, that include the controller and all the service within. 

####-	Test validation : 
Don’t hesitate to consult the tests\test-coverage file to valid that the coverage rate is efficient for your functionality. In the other end, don’t try to have a coverage rate to 100% : it’s not relevant. 
To start tests : print php bin/phpunit in your console. 

##d)	Standards : 
####-	Generality : 
This project follows Symfony standards, as they are described in bests practices (https://symfony.com/doc/current/best_practices.html). 
You have to conform to :  
-	 PSR-1 (https://www.php-fig.org/psr/psr-1/) 
-	 PSR-2 (https://www.php-fig.org/psr/psr-2/) 
-	 PSR-4 (https://www.php-fig.org/psr/psr-4/)
To be sure that these rules are followed, we advise to use PHP CS Fixer (https://cs.symfony.com/). This bundle will verify that your code is up to standard. 

If you want more information about the conventions : https://symfony.com/doc/current/contributing/code/standards.html

#####Reminder about naming convention : 
- camelCase for variables, functions, methods, methods arguments ;
- snake_case for configuration parameters, twig variables ;
- UpperCamelCase for class names and PHP files ; 
- Use « Abstract » prefix for abstract classes ; 
- Use « Interface » suffix for interfaces ; 
- Use « Trait » suffix for traits ; 
- Use « Exception » suffix for exceptions ; 
- Use of type-hinting in documentation is required ( int, bool, float). 

####-	Documentation : 
All classes have to be documented by PHPDoc. This documentation is without line break, except when annotation subject is different than annotation before. 

####-	Tests
All functional tests are grouped : for exemple, if you test that the /foo route is accessible with an authorization, but also that this same route is not accessible without authorization, these two test methods have to be grouped, and an annotation will show which method is tested. 


