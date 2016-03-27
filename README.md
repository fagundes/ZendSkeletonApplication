ZffSprout
=======================
A minimalist and elegant Skeleton Application for ZF2, yet powerful starter application, see the list of included [features](#features).

Introduction
------------
This is a fork of the official [ZendSkeletonApplication](https://github.com/zendframework/ZendSkeletonApplication). The objective of Sprout is save your time and effort when you are creating a ZF2 Application from scratch.

Installation using Composer
---------------------------

The easiest way to create a new ZF2 project is to use [Composer](https://getcomposer.org/). If you don't have it already installed, then please install as per the [documentation](https://getcomposer.org/doc/00-intro.md).


Create your new ZF2 project:

    composer create-project -n -sdev fagundes/zff-sprout path/to/install dev-tree

### Installation using a tarball with a local Composer

If you don't have composer installed globally then another way to create a new ZF2 project is to download the tarball and install it:

1. Download the [tarball](https://github.com/fagundes/ZffSprout/tarball/master), extract it and then install the dependencies with a locally installed Composer:

        cd my/project/dir
        curl -#L https://github.com/fagundes/ZffSprout/tarball/master | tar xz --strip-components=1

2. Download composer into your project directory and install the dependencies:

        curl -s https://getcomposer.org/installer | php
        php composer.phar install

If you don't have access to curl, then install Composer into your project as per the [documentation](https://getcomposer.org/doc/00-intro.md).

Web server setup
----------------

### PHP CLI server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root
directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note:** The built-in CLI server is *for development only*.

### Vagrant server

This project supports a basic [Vagrant](http://docs.vagrantup.com/v2/getting-started/index.html) configuration with an inline shell provisioner to run the Skeleton Application in a [VirtualBox](https://www.virtualbox.org/wiki/Downloads).

1. Run vagrant up command

    vagrant up

2. Visit [http://localhost:8085](http://localhost:8085) in your browser

Look in [Vagrantfile](Vagrantfile) for configuration details.

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-app.localhost
        DocumentRoot /path/to/zf2-app/public
        <Directory /path/to/zf2-app/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
            <IfModule mod_authz_core.c>
            Require all granted
            </IfModule>
        </Directory>
    </VirtualHost>

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

    http {
        # ...
        include sites-enabled/*.conf;
    }


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zf2-app.localhost.conf`
it should look something like below:

    server {
        listen       80;
        server_name  zf2-app.localhost;
        root         /path/to/zf2-app/public;

        location / {
            index index.php;
            try_files $uri $uri/ @php;
        }

        location @php {
            # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_param  SCRIPT_FILENAME /path/to/zf2-app/public/index.php;
            include fastcgi_params;
        }
    }

Restart the nginx, now you should be ready to go!

Features
--------

1. Ready to environment-specific application configuration (futher [information](http://framework.zend.com/manual/current/en/tutorials/config.advanced.html) about it):

    - You can set the current application environment (Apache 2 example):

          SetEnv APP_ENV "development"

    - You can use anywhere the constant `APP_ENV` to retrieve the current environment

    - Update the file `config/application.config.php`, you can configure:
        - Default Enviroment (if none is set)
        - Default Modules (active in all enviroments)
        - Modules per Environment
        - Default Module Listener Options
        - Module Listener Options per Enviroment

2. Comes with [Robo](http://robo.li/) (task runner for PHP) and prepared with some useful commands:

    - Clear
        - [clear](#clear)
        - [clear:css](#clear-css)
        - [clear:font](#clear-font)
        - [clear:js](#clear-js)
        - [clear:img](#clear-image)
    - Dist
        - [dist](#dist)
        - [dist:css](#dist-css)
        - [dist:font](#dist-font)
        - [dist:js](#dist-js)
        - [dist:img](#dist-image)
    - Watch
        - [watch](#watch)
        - [watch:composer](#watch-composer)
        - [watch:css](#watch-css)
        - [watch:font](#watch-font)
        - [watch:js](#watch-js)
        - [watch:img](#watch-image)

 ### Clear

 Executes all clear commands at once. It will remove any files from dist subfolders (css, fonts, js and img).

 Usage:

        vendor/bin/robo clear

 #### Clear Css

 Delete all files from dist css folder, by default `public/dist/css` directory.

 Usage:

        vendor/bin/robo clear:css

 #### Clear Font

 Delete all files from dist fonts folder, by default `public/dist/fonts` directory.

 Usage:

        vendor/bin/robo clear:font

 #### Clear Js

 Delete all files from dist js folder, by default `public/dist/js` directory.

 Usage:

        vendor/bin/robo clear:js

 #### Clear Image

 Delete all files from dist image folder, by default `public/dist/img` directory.

 Usage:

        vendor/bin/robo clear:img

 ### Dist

 Executes all dist commands at once.

 Usage:

    vendor/bin/robo dist

 #### Dist Css

 Executes three steps commands:
    - Executes [clear:css](#clear-css) command.
    - Compiles `main.scss` and creates the files `zff-sprout.css` and `zff-sprout.min.css` in dist css folder (`public/dist/css`).
    - Compiles any css vendors and creates the files `zff-sprout-vendors.css` and `zff-sprout-vendors.min.css` also in dist css folder.

 Usage:

        vendor/bin/robo dist:css

 #### Dist Font

 Executes two steps commands:
    - Executes [clear:font](#clear-font) command.
    - Copy any font vendors to dist fonts folder (`public/dist/fonts`).

 Usage:

        vendor/bin/robo dist:font

 #### Dist Js

 Executes three steps commands:
    - Executes [clear:js](#clear-js) command.
    - Concat any js file in `public/js` and creates the files `zff-sprout.js` and `zff-sprout.min.js` in dist js folder (`public/dist/js`).
    - Concat any js vendors and creates the files `zff-sprout-vendors.js` and `zff-sprout-vendors.min.js` also in dist js folder.

 Usage:

        vendor/bin/robo dist:js

 #### Dist Js

 Executes two steps commands:
    - Executes [clear:two](#clear-two) command.
    - Copy any img file in `public/img` to dist images folder (`public/dist/img`)

 Usage:

        vendor/bin/robo dist:img

 ### Watch

  Executes all watch commands at once.

  Usage:

        vendor/bin/robo watch

 #### Watch Composer

 Monitors the `composer.json` file and when it changes `composer update` will be executed.

 Usage:

        vendor/bin/robo watch:composer

 #### Watch Css

  Monitors all files in `public/css` folder and when anyone changes the command [dist:css](#dist-css) will be executed.

  Usage:

         vendor/bin/robo watch:css

 #### Watch Font

  Monitors all files in `public/font` folder and when anyone changes the command [dist:font](#dist-font) will be executed.

  Usage:

         vendor/bin/robo watch:font

 #### Watch Js

  Monitors all files in `public/js` folder and when anyone changes the command [dist:css](#dist-js) will be executed.

  Usage:

         vendor/bin/robo watch:css

 #### Watch Img

  Monitors any file in `public/img` folder and when it changes the command [dist:img](#dist-img) will be executed.

  Usage:

         vendor/bin/robo watch:img
