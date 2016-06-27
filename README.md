# Members Tracking Portal

## Synopsis

This Members Tracking Portal is developed for the use of Clubs, Organizations, and such who are looking for a tool to keep a record of their members, their information, and what events they attend.  

This Members Tracking Portal was originally developed for Purdue Hackers. Purdue Hackers is a community of students who collaborate, learn, and build kick-ass technical projects. This project was developed to function as a public manifest of the members of Purdue Hackers, store their information, and record their attendance at events.  

## Features

- [X] Member - Register/Login
- [X] Member - Modify Account Info
- [X] Member - Change Password
- [ ] Member - Forgot/Reset Password
- [X] Member - Add Personal Location
- [X] Member - Delete Personal Location
- [ ] Member - Automatic Status Changes (Member/Inactive/Alumni)
- [X] Members - View Members List
- [X] Members - View Member Profile
- [ ] Members - View Locations on Map
- [X] Events - View Events List
- [X] Events - View Event Info
- [X] Admin - Members - Modify
- [ ] Admin - Members - Create Account For (Provide only name/email, creates account and sends email to new member)
- [X] Admin - Events - Create
- [X] Admin - Events - Modify
- [X] Admin - Events - Delete
- [X] Admin - Events - Checkin Members
- [ ] Admin - Events - Delete Checkin (incase of accidental checkin)

## Local MAMP Installation - Mac  

- [ ] Download and Install [MAMP](https://www.mamp.info/en/)  
- [ ] Set MAMP Directory to `./htdocs/Public`  
- [ ] Create MySQL Database (use a GUI tool such as [Sequel Pro](http://www.sequelpro.com))  
- [ ] Execute `cp .env.example .env` in the `./htdocs` directory  
- [ ] Open `./htdocs/.env` - Fill in your database information and ORG/ADMIN Information
- [ ] Download and Install [Composer](https://getcomposer.org/)  
- [ ] Execute `composer install` in the `./htdocs` directory (`php composer.phar install` if using the composer.phar file)  
- [ ] Execute `php artisan migrate` in the `./htdocs` directory  
- [ ] Execute `php artisan key:generate` in the `./htdocs` directory  
- [ ] Execute `mkdir -p storage/framework/sessions` and `mkdir -p storage/framework/views` in the `./htdocs` directory  
- [ ] Execute `chmod -R 757 storage` in the `./htdocs` directory  
- [ ] Start MAMP Servers  

## Deploying to Bluemix

- [ ] Modify `./manifest.yml`
- [ ] Execute `bluemix api https://api.ng.bluemix.net`
- [ ] Exceute `bluemix login -u {{EMAIL}} -o {{EMAIL}} -s dev`
- [ ] Execute `cf push {{NAME}}`

## Accessing Portal

- Locally: http://localhost:8888/  
- Bluemix: http://{{NAME}}.mybluemix.net  

## Contributors

@harrischristiansen (http://www.harrischristiansen.com) (christih@purdue.edu)  

## License

MIT License  

Copyright (c) 2016 Harris Christiansen  

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:  

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.  

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.  