
## API Test WeRoad

This Laravel API application is designed for testing purposes, built with Laravel 10.10. It includes a basic setup with authentication, role management, and RESTful endpoints for managing travels and tours.
## Models

The application is structured around four primary models: User, Role, Travel, and Tour, with corresponding migrations to define the database structure.

Base Structure
    
    User:
        id
        email
        password
        roleId

    Role:
        id
        name

    Tour:
        id
        travelId
        name
        startingDate
        endingDate
        price

    Travel:
        id
        slug
        name
        description
        numberOfDays
        moods (Uses JSON casting)
## Authentication

Authentication is powered by Laravel Sanctum, with a custom implementation for role management (admin and editor). A dedicated middleware has been created to manage route permissions based on roles.
## Run locally

To run this project locally, you can use the docker-compose.yml (for a local container configuration). Once created main container, you can launch the services with the command.

```bash
  ./vendor/bin/sail up -d
```

When all service are up, you can proceed with migration and seeding.
## Migrations and Seeding

To set up the database and populate it with sample data, execute the following command:

```bash
  php artisan migrate --seed
```

## Documentation

API documentation is generated using the zircote/swagger-php package, with darkaonline/l5-swagger for Swagger UI integration. To generate or update the Swagger documentation run:

```bash
  php artisan l5-swagger:generate
```

To access to the documentation locally, visit:

```bash
  http://localhost/api/documentation
```