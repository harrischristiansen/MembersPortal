# Purdue Hackers - Membership Portal

## Synopsis

Purdue Hackers is community of students who collaborate, learn, and build kick-ass technical projects. This Membership Portal will act as a public manifest of our members, their information, and their event attendance.  

Students become a member of Purdue Hackers after attending any Purdue Hackers event/hackathon. Students lose their membership after 180 days of not attending an event. Students who lose their membership status on or after their senior year will be moved to alumni status.  

## Installation  

- [ ] Download and Install MAMP  
- [ ] Set MAMP Directory to Site/backend/Public  
- [ ] Create MySQL Database (named PHMembers) (use a GUI tool such as Sequel Pro)  
- [ ] Copy Site/backend/.env.example to Site/backend/.env  
- [ ] Fill in database information in .env (adding DB_PORT if necessary)  
- [ ] Download and Install Composer  
- [ ] Run `composer install` in the Site/backend directory (you might have to run `php composer.phar install` depending on how you installed composer)  
- [ ] Run `php artisan migrate` in the Site/backend directory  
- [ ] Run `php artisan key:generate` in the Site/backend directory  
- [ ] Run `mkdir -p Site/backend/storage/framework/sessions` and `mkdir -p Site/backend/storage/framework/views`  
- [ ] Run `chmod -R 777 Site/backend/storage`  

## Starting Servers

- [ ] Start MAMP Servers  

## Accessing Portal

- [ ] Open localhost:8888/  

## Contributors

@harrischristiansen (http://www.harrischristiansen.com)

## License

Copyright 2016 Harris Christiansen and Purdue Hackers - All Rights Reserved  


