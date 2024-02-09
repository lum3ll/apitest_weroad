## API TEST WEROAD

This is a Laravel API application for testing purpose. 
Laravel version: 10.10

For the requested test, I've created 4 model to create the base structure of the entire API application. The 4 base models are User, Role, Travel and Tour. For each of these models I've generated the specific migrations to structure the DB.

Here is the base structure of the 4 models:

User:
    'id',
    'email',
    'password',
    'roleId'

Role:
    'id',
    'name'

Tour:
    'id',
    'travelId',
    'name',
    'startingDate',
    'endingDate',
    'price'

Travel (*):
    'id',
    'slug',
    'name',
    'description',
    'numberOfDays',
    'moods'

(*): For this specific model, the attribute "moods" assumes that JSON casting will be used.

For the Authentication, I've used the base Sanctum package, creating a specific handling of Roles (admin and editor) to handle the different routes permission. I've created a specific middleware to handle the roles.

To create the base db structure and populate this with dummy data for the models, run the following command: 

php artisan migrate --seed

For API reference and documentation, I've used zircote/swagger-php package, and for the generation of Swagger documentation, i've used darkaonline/l5-swqagger package (configuration available at config/l5-swagger.php). To generate or update the swagger, run:

php artisan l5-swagger:generate

Documentation will be available in your local environment at the address:

http://localhost/api/documentation

In the root folder you'll find also the docker-compose.yml (for a local container configuration)






## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# apitest_weroad
