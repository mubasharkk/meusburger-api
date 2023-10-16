# FFW Backend Developer Test

<strike>HIDDEN</strike>

## Notes:
* Assuming the scale of the task is smaller
  * I have used a `api-platform/core`.
* As I haven't worked with symfony for some time already. I have tried to keep it simple. 
* I could have created a entity repository and controllers, but they would be doing the exact same thing with addition code.
* As I haven't worked with Symfony for quite sometime so there was a bigger learning curve as it has changed a lot.
* **Time Consumed:** ~ 04:45
* I have added only 2 simple test cases to avoid extra time consumption.


## Files to assess:

* [src/Entity/Note.php](https://github.com/mubasharkk/ffw-task/blob/main/src/Entity/Note.php)

## Setup & Starting Docker

**Important:** !! Docker must be installed !!

Clone the repository and run the following command:

```
docker-compose up -d --build

```

To tun DB migrations

```
docker exec -it ffw-task-php-1 php bin/console doctrine:schema:update --force
```


---

## API Documentation

[https://localhost/api](https://localhost/api)

### Running tests

```
docker exec -it ffw-task-php-1 php bin/phpunit
```
