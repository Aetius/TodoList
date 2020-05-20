ToDoList
========

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e2d7d17aeac4784bf767eea4ac45798)](https://app.codacy.com/manual/Aetius/TodoList?utm_source=github.com&utm_medium=referral&utm_content=Aetius/TodoList&utm_campaign=Badge_Grade_Dashboard)

The TodoList project was initially developed in Symfony 3.1 and upgraded in Symfony 4.4 (last LTS in May 2020). 
This application is designed to help people to plan theirs tasks. For this purpose, the application allows the user to 
subscribe and to add et to delete some tasks. 
As an admin, you will have access to all users, and you could delete the anonymous tasks (tasks created before the upgraded
application). 

###To install this application


#####Install the project : 
- Click on "clone or download" : If you choose 'Open in Desktop', you will upload these files directly from github, by 
GitHub Desktop (from example) If you choose to copy these files in .zip,

- Launch a composer install and you run your project locally,
 
- Configure the .env.local file, in the project's base (database, mailer...). 

#####To install the database : 
- Create the database : php bin/console d:d:c (if you have to install the test database, add --env test)

- Create the database with the migrations. Migrations have been made to add an anonymous user for tasks created before 
 the update. To start the upload : php bin/console d:m:m

- Then upload hautelook fixtures files with php bin/console h:f:l
These fixtures have been made to test the application in the case of the database is empty. Don't hesitate to modify 
fixtures to make another tests. 

- In case you already have a database configured, you can use the command (located in app\command) to add anonymous user, 
and add this user to the tasks already in your database. Then you can modify the fixtures to test your new database. 

 



