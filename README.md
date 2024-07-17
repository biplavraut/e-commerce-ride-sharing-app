# Installation Process
- clone the project
- go to project directory and execute following commands:
    - `composer install`
    - `npm install`
    - `cp .env.example .env`
    - `php artisan key:generate`
    - `php artisan storage:link`
    - setup .env file with your database connection
    
### Default email and password
##### Super Admin
**url**: https://{project-root-url}/admin

**email**: sunbi.mac@gmail.com

**password**: admin1

##### Vendor
**url**: https://{project-root-url}/vendor