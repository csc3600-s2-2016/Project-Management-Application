# Project-Management-Application


CSC3600 Project Summary
-----------------------
Create a web application using the LAMP platform that allows users to create a project description, log time expended, and produce reports.

Specifically it should provide this functionality:
* Create tasks and sub-tasks
* reorganise task hierarchy
* rename tasks
* delete tasks that have no time expenditure
* Record time estimates for tasks
* Log time by project staff against tasks
* Report summaries by task; by staff; overall summaries.

Instructions for setting up your environment
--------------------------------------------
1. Clone the github repo eg `git clone https://github.com/csc3600-s2-2016/Project-Management-Application.git csc3600Project`
2. Copy/move csc3600.box from drive into this new directory eg `mv path/to/csc3600.box csc3600Project/`
3. cd into this directory and run 'vagrant up', eg: `cd csc3600Project && vagrant up`
4. SSH into csc3600 box and run the script 'setup.script' located in /var/www, eg:`vagrant ssh` and then `bash /var/www/setup.script`


You should now be able to view the project by opening up 192.168.33.10 in your browser.
