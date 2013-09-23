---
layout: layout
theme_dir: jekyll-theme

author: Kévin Gomez
title: gaas
description: Geocoder As A Service
project_url: https://github.com/K-Phoen/gaas
---

Geocoder as a Service [![Build Status](https://travis-ci.org/K-Phoen/gaas.png?branch=master)](https://travis-ci.org/K-Phoen/gaas)
=====================

The power of [Geocoder](http://geocoder-php.org/Geocoder/) exposed in an API.

Installation
============

Clone this repository and install the project's dependencies:

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

You're done! You can run the application using the PHP built-in webserver:

    php -S 0.0.0.0:4000 -t web/

Open `http://localhost:4000/` in your browser to see the website running.


Tests
=====

First, install the application as described in section [Installation](#installation).

Then run the testsuite:

    ./vendor/bin/phpunit


License
=======

Released under the MIT License. See the bundled LICENSE file for details.
