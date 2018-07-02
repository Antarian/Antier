Antier CMS
===

Just another PHP CMS or JS framework?


Docker compose
--------------

Docker compose build and start
```bash
docker-compose build
docker-compose up -d
```

Docker VM
```bash
docker-compose exec antier bash
```

Symfony
-------

Server start
```bash
php bin/console server:start *:8080
```

In browser
```
0.0.0.0:8080
```

Tests
```bash
./bin/phpunit
```

Generate keys for JWT
---------------------

```bash
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

In case first openssl command forces you to input password use following to get the private key decrypted
```bash
openssl rsa -in config/jwt/private.pem -out config/jwt/private2.pem
mv config/jwt/private.pem config/jwt/private.pem-back
mv config/jwt/private2.pem config/jwt/private.pem
```
