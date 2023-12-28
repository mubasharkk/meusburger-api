# Task Manager REST API

<strike>HIDDEN</strike>

## Notes:
* I have spent around 16+ hours looking into the [Spryker documentation](https://docs.spryker.com/docs/scos/dev/glue-api-guides/202311.0/glue-api-guides.html).
* I have used the latest [spryker-B2C](https://github.com/mubasharkk/meusburger-api) (my own repo) repository with docker. 

They have updated the architecture with new [Decoupled Glue API](https://docs.spryker.com/docs/scos/dev/glue-api-guides/202311.0/decoupled-glue-api.html) but all the related documentation haven't been updated and therefore the implementation and [How Tos](https://docs.spryker.com/docs/scos/dev/tutorials-and-howtos/introduction-tutorials/tutorial-hello-world-spryker-commerce-os.html) are outdated. 
There isn't any other source to learn with Sprker, unfortunately. 
Moreover, the documentation is also a bigger to look into all aspect of it. 

Eg. [Glue Api](https://docs.spryker.com/docs/scos/dev/glue-api-guides/202311.0/decoupled-glue-api.html#authentication-servers) has recently updated in `July 21,2023` and [Zed Rest API](https://docs.spryker.com/docs/scos/dev/tutorials-and-howtos/advanced-tutorials/tutorial-zed-rest-api.html) was last updated on `June 16, 2021`.

## Description

* Created REST API with CRUD operations handling Task management. 
* This is just to simply demonstration of some basic skills buliding REST API. 
* Not all the requirements are implemented. 
  * User module integration is skipped. 
  * API Security is skipped, it can be implemented with User Module + Passport
* Functional tests are implemented.
* Tasks are searchable with title and description.
* [Postman collection](https://github.com/mubasharkk/meusburger-api/blob/main/Meusburger.postman_collection.json) is added. 

## Files to assess:

* [src/](https://github.com/mubasharkk/meusburger-api/blob/main/src/)
* [src/Entity/Task.php](https://github.com/mubasharkk/meusburger-api/blob/main/src/Entity/Task.php)

## Setup & Starting Docker

**Important:** !! Docker must be installed !!

Clone the repository and run the following command:

```
docker-compose up -d --build

```

To run DB migrations

```
docker exec -it meusburger-api-php-1 php bin/console doctrine:schema:update --force
```

---

## API Documentation

**Root Url + Prefix:** `http://localhost/api`

[Postman Collection](https://github.com/mubasharkk/meusburger-api/blob/main/Meusburger.postman_collection.json)

| Endpoint  | Params | Description  |
|---|---|---|
|  `GET /tasks` | <pre><strong>page</strong> : Current Page<br><strong>limit</strong> : Per page count<br><strong>search</strong> : Title/Description search keyword<br></pre>  | List all tasks   |   
|  `POST /tasks` |  `title`: String <br/>`description`: string<br/>`status`: string [<b>in:</b> done, in-progress, to-do, completed) <br/>`due_date`: string[format: `Y-m-D`] | Create a new task   | 
|  `PUT /tasks/{id}` | `id`: Task ID,<br/><i>... same as POST params</i>  | Updated Task details   |  
|  `DELETE /tasks/{id}` | `id`: Task ID  | Delete Task   |  
---

### Running tests

```
docker exec -it meusburger-api-php-1 php bin/phpunit
```
