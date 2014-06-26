WebGoatPHP
==========
OWASP WebGoatPHP is a port of OWASP WebGoat to PHP and MySQL/SQLite databases. The goal is to create an interactive teaching environment for web application security by offering lessons in the form of challenges. In each challenge the user must exploit the vulnerability to demonstrate their understanding.

WebGoatPHP supports four different modes i.e single mode, workshop mode, contest mode and secure coding mode.

###Project Proposal
The proposal of the project can be found [here](http://shivamdixit.com/posts/gsoc-14-webgoatphp-proposal/)

###Screenshots
![WebgoatPHP Interface](https://raw.githubusercontent.com/shivamdixit/WebGoatPHP/master/challenges/WebGoatIntro/static/interface.png "WebgoatPHP Interface")

1. List of all the lessons and their categories
2. To refresh the list of lessons and categories (if a new lesson/category is added)
3. Content of the lesson
4. Reset the lesson to inital state
5. Get random hints of the lesson
6. This will show GET parameters
7. This will show the COOKIES
8. Get the plan of the lesson
9. This will show the solution of the lesson

###Installation
* Clone the git repo. `git clone https://github.com/shivamdixit/WebGoatPHP.git`
* Move it to your document root
* Import the database from SQL/webgoat.php
* Enter your database connection details in app/config/application.php (Line 52)
* Open the application from localhost

###Contribute

* Fork the repo
* Create your branch
* Commit your changes
* Create a pull request

If you have any questions write an email to: shivam.dixit@owasp.org
