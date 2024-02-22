# Contract Management Backend Development Setup
## System Requirement
    • PHP version: 7.3
    • PHP extensions: pdo, openssl, mbstring, xml, curl, ldap, intl
    • Composer version: 1.10
    • MySQL: minimum support v8.0
    • Node version: 14.17.3

## Server Configuration (PHP):
    • memory_limit: -1
    • max_execution_time: 300
    • post_max_size: 30M
    • upload_max_filesize: 30M

## Installation Instruction Guide:
    • Clone the repository (Contract_Management_Backend).
    • Make sure docker desktop is running 
    • Run docker-compose up -d
    • Run docker execc -it contract_management bash 
    • Run composer install to install PHP dependencies.
    • Run cp .env.example .env and update with your configurations.
    • Run php artisan key:generate to generate the application key.
    • Run php artisan passport:keys

## Application Configuration:
    • Env File Configuration:
        o Update Database configuration
        o Update Mail configuration
        o Update Storage configuration
### Running the Application
 **Use this command to create the Model from database table** 

`php artisan infyom:api_scaffold User --fromTable --tableName=users --skip=scaffold_controller,scaffold_requests,scaffold_routes,views`

 **Use this command to rollback the created Models**

`php artisan infyom:rollback User api_scaffold`
