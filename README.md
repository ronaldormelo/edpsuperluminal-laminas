EdpSuperluminal
===============
Version 1.0.0

Introduction
------------
EdpSuperluminal is a module for Laminas framework (ZF3) that caches the Zend classes used by your
application into a single file. Including this file greatly reduces the
execution time of your application, as the calls to the standard autoloader are
almost entirely eliminated.

**Warning:** After installing and enabling this module, it may seem as though
your ZF2 application is running faster than the speed of light (superluminal).
Don't panic. This is just an illusion and no laws of physics are being violated.

For best results, use this module in conjunction with APC.

Installation
------------

- Clone this module into your `module/` directory and rename to `EdpSuperluminal` 
- Enable `EdpSuperluminal` in `config/modules.config.php`
- Add the following line in `composer.json` for autoload
```php
{
  "autoload": {
    "psr-4": {
      "EdpSuperluminal\\": "module/EdpSuperluminal/src/",
    }
  }
}
```
- Run `composer dump-autoload` to autoload the new module
- Add the following line in `public/index.php` immediately after the `chdir()`
  call:

```php
<?php
chdir(dirname(__DIR__));
define('ZF_CLASS_CACHE', 'data/cache/classes.php.cache'); if (file_exists(ZF_CLASS_CACHE)) require_once ZF_CLASS_CACHE;
```

- In your browser, go to http://yourapp/?EDPSUPERLUMINAL_CACHE to build the initial
  class. You should do this for any page that is (a) dependency heavy, and/or
  (b) every page with a different dependency graph. Each call will append to
  the cache with any newly discovered classes. **REQUESTS WHERE YOU ARE
  BUILDING THE CACHE WILL BE SLOW, THIS IS NORMAL.**

- Once the cache is built, you can disable and delete EdpSuperluminal, leaving
  line you added to `public/index.php`.