BLOG BUNDLE
============
This is a Symfony Bundle which provides a simple admin panel and a set of services that can be consumed by a frontend bundle (i.e. AppBundle).

Installation
------------
1) Add the dependency to your project and install the bundle:
```
composer require prevueltas/blog-bundle
```

2) Update your database using your preferred method (DoctrineMigrations, `php app/console doctrine:schema:update --force`, etc.)

3) Update your security settings to restrict the access to the path: ^/admin

Assets
------
The assets are provided in Resources/public so that you only have to run `php app/console assets:install web` to publish them.
However, if you need to build them (which requires npm and gulp) then go to **Resources/gulp** and run:

```
npm install
gulp
(gulp watch)
```
