# Mustache support for Slim, backward compatible with PHP 5.2

[Slim](http://www.github.com/codeguy/Slim) is a micro PHP 5 framework that helps you quickly write simple yet powerful RESTful web applications. Its [1.x branch](http://github.com/codeguy/Slim/tree/1.6.7) is backward compatible with PHP 5.2.

This repository contains a custom Mustache view for Slim 1.x, offering full support for [Mustache.php](http://github.com/bobthecow/mustache.php).

## Where it differs from the "official" repo

Mustache support for Slim is provided by a single class. It is based on the [Mustache view](https://github.com/codeguy/Slim-Extras/blob/develop/Views/Mustache.php) contained in [Slim-Extras](http://github.com/codeguy/Slim-Extras), but is customized in a number of ways.

Functional differences:

- This version of the class is compatible with PHP 5.2 and does not use namespaces.
- The class allows an object to be used as view data, thus enabling the use of [Mustache lambdas](http://mustache.github.com/mustache.5.html) in PHP 5.2. The version in the official repo only supports arrays.

Implementation details:

- The class requires Slim 1.x and won't work with Slim 2.x (use Slim-Extras for that). It extends `Slim_View`, not `\Slim\View`.
- The class is called `View_Mustache`, not `Mustache`. It has been renamed to avoid conflicts in the absence of namespaces.

Otherwise, it is used in the exact same way as the class provided with [Slim-Extras](http://github.com/codeguy/Slim-Extras). See there for usage notes.

## Version

The current version is based on [Mustache.php @ b12fdd0](https://github.com/codeguy/Slim-Extras/blob/b12fdd069062a0d30d1584aad3aa5bd76c275c5e/Views/Mustache.php) in Slim-Extras 2.0.3-develop, last updated on [19 Jan 2013](https://github.com/codeguy/Slim-Extras/commits/develop/Views/Mustache.php). It was tested with [Slim 1.6.7](http://github.com/codeguy/Slim/tree/1.6.7).

## Related

**Slim**

[Primary Slim repo](http://www.github.com/codeguy/Slim)  
[Primary Slim-Extras repo](http://github.com/codeguy/Slim-Extras)  
[Slim website](http://www.slimframework.com/)

**Mustache**

[Primary Mustache.php repo](http://github.com/bobthecow/mustache.php)  
[Mustache.php wiki](http://github.com/bobthecow/mustache.php/wiki)  
[Mustache website](http://mustache.github.com/)

## Open Source License

The resources in this repository are released under the MIT public license.

<http://www.slimframework.com/license>
