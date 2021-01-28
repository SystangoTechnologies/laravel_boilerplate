## Installation

1. Install the laravel in your localhost using composer create-project laravel/laravel "6.x".
2. Once laravel is installed successfully, one must specify the DB name, username and pwd in .env file.
3. From laravel 6.x, we have use composer require laravel/ui:^1.0 --dev in order to get the views either in vue, bootstrap or react.
3. Create a new artisan command, using php artisan make:command unleashed.
4. This will create a new command script under the folder: app/Console/Commands/unleashed.php
5. Sample unleashed.php script is present in the root folder.
6. Update the signature, description as per your needs.
7. Under the handle function, we are calling the various other artisan commands for generating migrations,models,DB table and inserting dummy users in the DB table.
8. For getting the results we need to trigger the command php artisan unleashed (our new custom command), that will start with downloading the ui package for laravel for scaffolding authentication features with login and registration.
9. After that command creates migration for user_types DB table as well as creates a new table in the DB.
10. Then command updates the existing user table with a new column of user_type by creating a new migration.

## Usage

TODO: Write usage instructions