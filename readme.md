# Purdue Hackers - Membership Portal

## Synopsis

Purdue Hackers is community of students who collaborate, learn, and build kick-ass technical projects. This Membership Portal will act as a public manifest of our members, their information, and their event attendance.  

Students become a member of Purdue Hackers after attending any Purdue Hackers event/hackathon. Students lose their membership after 180 days of not attending an event. Students who lose their membership status on or after their senior year will be moved to alumni status.  

## Timeline
- [X] Setup Initial Project
- [X] Setup Deployment for Bluemix
- [X] Setup Deployment for MT
- [X] Login Functionality
- [X] Login With Any Associated Email Address
- [X] Registration Functionality
- [ ] Password Reset Functionality
- [ ] Member List
- [ ] Member Profile Page
- [ ] Member - Edit Profile
- [ ] Event List
- [ ] Event Profile Page
- [ ] Event - Manage Event
- [ ] Event Checkin Page
- [ ] Event - Attended Members List
- [ ] Member - Attended Events List

## Local Installation  

- [ ] Download and Install MAMP  
- [ ] Set MAMP Directory to Public  
- [ ] Create MySQL Database (named PHMembers) (use a GUI tool such as Sequel Pro)  
- [ ] Copy .env.example to .env  
- [ ] Fill in database information in .env  
- [ ] Download and Install Composer  
- [ ] Run `composer install` in the root directory (you might have to run `php composer.phar install` depending on how you installed composer)  
- [ ] Run `php artisan migrate` in the root directory  
- [ ] Run `php artisan key:generate` in the root directory  
- [ ] Run `mkdir -p storage/framework/sessions` and `mkdir -p storage/framework/views`  
- [ ] Run `chmod -R 777 storage`  

## Starting Local Servers

- [ ] Start MAMP Servers  

## Deploying to Bluemix

- [ ] `bluemix api https://api.ng.bluemix.net`
- [ ] `bluemix login -u harrischristiansen@mac.com -o harrischristiansen@mac.com -s dev`
- [ ] `cf push Purdue-Hackers-Members-Portal`

## Accessing Portal

- Locally: http://localhost:8888/  
- Bluemix: http://purdue-hackers-members-portal.mybluemix.net
- MT: http://members.purduehackers.com/

## Contributors

@harrischristiansen (http://www.harrischristiansen.com) (christih@purdue.edu)

## License

Copyright 2016 Harris Christiansen and Purdue Hackers - All Rights Reserved  
