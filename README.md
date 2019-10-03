# Тестовое задание для PHP-разработчика

Реализовать сервис «Стена сообщений»

Что в репе
--------------

Одностраничное приложение (SPA) на VueJs. Api на Laravel 6. Docker чтобы все это запустить.

Установка
--------------

**Docker Nginx + Php7.3 + PostgreSQL**

Скачайте репозиторий. Создайте а затем запустите конейнеры.

    $ docker-compose build
    $ docker-compose up -d

Поднимутся 3 конейнера и `docker ps` покажет что похожее на это:

    CONTAINER ID        IMAGE                       COMMAND                  CREATED             STATUS              PORTS                                              NAMES
    9f75efc75062        test-task_nginx     "nginx"                  22 hours ago        Up 5 seconds        0.0.0.0:80->80/tcp, 443/tcp                        test-task_nginx_1_513e22f288fa
    87687e10fa76        test-task_php-fpm   "docker-php-entrypoi…"   22 hours ago        Up 6 seconds        9000/tcp                                           test-task_php-fpm_1_e05f988560f9
    28aa6023e254        postgres:9.6.10-alpine      "docker-entrypoint.s…"   22 hours ago        Up 4 seconds        0.0.0.0:15432->5432/tcp, 0.0.0.0:32771->5432/tcp   test-task_db_1_dbc2b3576aa8
    
Задйте в php-fpm котейнер `docker-compose exec -u www-data php-fpm bash`:
    
    $ cp .env.example .env
    $ composer install
    $ chmod -R 777 bootstrap/cache
    $ php artisan migrate --seed
    $ composer phpunit
    $ composer phpfix
    $ composer phpstan
    
Теперь в можно зайти браузером в `http://localhost:8089`.

Для теста 2 пользователя админ и неадмин:

    `not.admin@test.com / secret`
    `admin@admin.com / secret`
