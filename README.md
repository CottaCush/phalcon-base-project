# Phalcon Base Project

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

> Base project for bootstrapping phalcon projects

## Requirements
The minimum requirement by this project template that your Web server supports PHP 5.6

* [Phalcon 3.0.*](https://docs.phalconphp.com/en/latest/reference/install.html)
* [Composer](https://getcomposer.org/doc/00-intro.md#using-composer)

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer create-project --prefer-dist cottacush/phalcon-base-project new_project
~~~



## Virtual Host Setup

*Windows*
[Link 1](http://foundationphp.com/tutorials/apache_vhosts.php)
[Link 2](https://www.kristengrote.com/blog/articles/how-to-set-up-virtual-hosts-using-wamp)

*Mac*
[Link 1](http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-9-mavericks-osx-10-8-mountain-lion/)
[Link 2](http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-10-yosemite/)

*Debian Linux*
[Link 1](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts)
[Link 2](http://www.unixmen.com/setup-apache-virtual-hosts-on-ubuntu-15-04/)

Sample Virtual Host Config for Apache
```apache
<VirtualHost *:80>
    ServerAdmin admin@app.com
    DocumentRoot "<WebServer Root Dir>/phalcon-base-project/public"
    ServerName test.phalconbaseproject.com
    ServerAlias test.phalconbaseproject
    SetEnv APPLICATION_ENV test
    ErrorLog ${APACHE_LOG_DIR}/test.phalconbaseproject.error.log
    CustomLog ${APACHE_LOG_DIR}/test.phalconbaseproject.access.log common
    <Directory <WebServer Root Dir>/phalcon-base-project/public>
       AllowOverride all
       Options -MultiViews
       Require all granted
    </Directory>
</VirtualHost>
```

## Environment Variables
Make a copy of  `.env.sample` to `.env` in the env directory and replace values as appropriate.


## Install dependencies

`composer install`


## Setting OAuth2 Tables

Create a new database `app`

Run Migrations by running  ``./vendor/bin/phinx migrate``

Seed the database by running the following commands:

- Seed the Oauth Credentials  
`CLIENT_ID='<CLIENT_ID>' CLIENT_SECRET='<CLIENT_SECRET>' ./vendor/bin/phinx seed:run -s OauthSeeder -e development`



## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email <developers@cottacush.com> instead of using the issue tracker.

## Credits

- [Adeyemi Olaoye <yemi@cottacush.com>] [link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/cottacush/phalcon-base-project.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/cottacush/phalcon-base-project/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cottacush/phalcon-base-project.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cottacush/phalcon-base-project.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/cottacush/phalcon-base-project.svg?style=flat-square

[link-packagist]: https://poser.pugx.org/cottacush/phalcon-base-project/v/stable
[link-travis]: https://travis-ci.org/cottacush/phalcon-base-project
[link-scrutinizer]: https://scrutinizer-ci.com/g/cottacush/phalcon-base-project/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cottacush/phalcon-base-project
[link-downloads]: https://packagist.org/packages/cottacush/phalcon-base-project
[link-author]: https://github.com/yemexx1
[link-contributors]: ../../contributors