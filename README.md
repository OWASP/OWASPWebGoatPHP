<img src="https://raw.githubusercontent.com/shivamdixit/WebGoatPHP/master/images/logo.png"  height="30"> WebGoatPHP
================

OWASP WebGoatPHP is a port of OWASP WebGoat to PHP and MySQL/SQLite databases. The goal is to create an interactive teaching environment for web application security by offering lessons in the form of challenges. In each challenge the user must exploit the vulnerability to demonstrate their understanding.

WebGoatPHP supports four different modes i.e single mode, workshop mode, contest mode and secure coding mode.

### Project Proposal
The proposal of the project can be found [here](http://shivamdixit.com/posts/gsoc-14-webgoatphp-proposal/)

### Screenshots
Single User Mode:

![WebgoatPHP Interface](https://raw.githubusercontent.com/shivamdixit/WebGoatPHP/master/challenges/single/WebGoatIntro/static/interface.png "WebgoatPHP Interface")

1. List of all the lessons and their categories
2. To refresh the list of lessons and categories (if a new lesson/category is added)
3. Content of the lesson
4. Reset the lesson to initial state
5. Get random hints of the lesson
6. This will show GET parameters
7. This will show the COOKIES
8. Get the plan of the lesson
9. This will show the solution of the lesson

Workshop Mode:

![Workshop Mode](https://raw.githubusercontent.com/shivamdixit/WebGoatPHP/master/images/workshop_dashboard.png "Workshop Mode")

### Installation
* Clone the git repo. `git clone https://github.com/shivamdixit/WebGoatPHP.git`
* Move it to your document root
* Import the database from SQL/webgoat.php
* Enter your database connection details in app/config/application.php (Line 52)
* Open the application from localhost
* Default username:password for single-user mode: `guest:guest`


### Contribute
* Fork the repo
* Create your branch
* Commit your changes
* Create a pull request

### Adding a lesson/challenge
Adding a new challenge is very simple. All the challenges must be present in 'challenges' directory and must extend class 'BaseLesson'. A template is provided in template/SampleLesson. The name of the directory must be same as the name of the class in index.php. Any static content like images, scripts etc. must be placed inside a sub-directory 'static' within the lesson directory.

There are few methods which your lesson need to implement like start(), getTitle(), getCategory(), reset() etc.

Once you have added the lesson click on "Refresh List" button at the top of the application to display your lesson in the list.

### Contributors
* Abbas Naderi
* Johanna Curiel
* Shivam Dixit
* Prasham Gupta (Logo)

### More Info
https://www.owasp.org/index.php/WebGoatPHP

### Contact
If you have any questions join the discussion on our [mailing list](https://lists.owasp.org/mailman/listinfo/owasp_webgoatphp) or write an email to: shivam.dixit[at]owasp.org
