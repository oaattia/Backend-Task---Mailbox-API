# Mailbox api

# Install
Run composer
`composer install`
Run Migrations files
`php artisan migrate`

## Importing the files
To import the json file used, you can run the following command after migrating the files .
`php artisan db:seed`

This will import the json file to table `messages` . 

## Testing
`venor/bin/phpunit` to run test cases, tests can be found in folder `tests`