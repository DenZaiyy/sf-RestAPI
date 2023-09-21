# Simple API REST make with Symfony
It's a short project for learn how to make a simple api rest using symfony

&nbsp;
I make it just with one example like project for know how to : 
- **GET** results (all)
- **GET** specific by id
- **POST** new
- **UPDATE** by specific id
- **DELETE** by specific id

## Dependencies
For using this skeleton symfony project for rest-api, we need to import some dependencies
```composer
composer require jms/serializer-bundle
composer require friendsofsymfony/rest-bundle
composer require symfony/maker-bundle
composer require symfony/orm-pack
```

During the installation of the packages, it will ask you to execute the recipes, type **"y"** to confirm

## Starting server
For start the server for make any tests, using this command
```composer
symfony serve -d
```

## Testings api
To test any endpoints, i using [Postman](https://www.postman.com/)

- GET all : **host**/api/**projects** with **GET** method
- GET with ID : **host**/api/**project/{ID}** with **GET** method
- POST : **host**/api/**project** with **?name={name}&description={description}** in uri or using form input data (using **POST** method)
- UPDATE : **host**/api/**project/{ID}** with **?name={name}&description={description}** in uri or using form input data* (using **PUT** or **PATCH** method)
- DELETE : **host**/api/**project/{ID}** with **DELETE** method
