# sample-bank-site
its a simple bank site written in php with mysql database and basic bank stuff eg. loans, iban creation, work managment etc etc
# setup tutorial
This tutorial assumes you're using xampp in order to manage your database, as well as run the PHP sites!!!

1) Install Xampp and make sure you can open PHP sites using it.
2) Run Apache and MySQL servers and import proj_bank_v2.sql into a database named proj_bank. You can do it either using terminal database manager, or PHPMyAdmin site. Here's a nifty tutorial how to create a database https://www.geeksforgeeks.org/how-to-create-a-new-database-in-phpmyadmin/ . Although you don't have to create any tables yourself, the sql file will manage everything for you.
3) Locate xampp folder on your harddrive. By default it should be located on the root of your disk (eg. C:/xammpp on Windows, or /opt/lampp/ on Linux).
4) Go int htdocs folder inside xampp and create a folder for the project.
5) Copy all the files into your designated project folder.
6) Go to http://localhost/{name_of_your_projcet_folder}
7) Shazam!!! You're all set. Now let your imagination run wild. Take a loan or get a job :)

# Functionalities
This project allows you to:
* create your own account in a bank, as well as login into already created one.
* It automatically generates a unique iban, user id, and your very own pin.
* You can take loans and even plan in how many payments do you wish to pay it off. It will also automatically calculate how much you will have to pay each time and it will tell you expected time of payoff, but be carreful you won't be able to loan any money if your registered jobs won't be enough to pay it off.
* In order to take loan add a job, select it's name and how much do you make off of it. You can also tick a box if it's temporary.
* You can also create another account under your name and the jobs and loans are shared.
