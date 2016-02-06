# KleijnWeb\SwaggerBundleTools
[![Build Status](https://travis-ci.org/kleijnweb/swagger-bundle-tools.svg?branch=master)](https://travis-ci.org/kleijnweb/swagger-bundle-tools)
[![Coverage Status](https://coveralls.io/repos/github/kleijnweb/swagger-bundle-tools/badge.svg?branch=master)](https://coveralls.io/github/kleijnweb/swagger-bundle-tools?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kleijnweb/swagger-bundle-tools/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kleijnweb/swagger-bundle-tools/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/kleijnweb/swagger-bundle-tools/v/stable)](https://packagist.org/packages/kleijnweb/swagger-bundle-tools)

Development tools for [kleijnweb/swagger-bundle](https://github.com/kleijnweb/swagger-bundle)

## Install and General Usage

You can use the bundle in your SwaggerBundle project by adding it to your AppKernel, or (preferred) install it globally.

Install using composer:

`composer global require kleijnweb/swagger-bundle-tools`). 

Change into the root directory of the desired SwaggerBundle project and invoke the `swagger-bundle-tools` script.

# Tools

## Amending Your Swagger Document
 
SwaggerBundle adds some standardized behavior, this should be reflected in your Swagger document. Instead of doing this manually, you can use the `document:amend` command.

## Generating Resource Classes
 
SwaggerBundle can generate classes for you based on your Swagger resource definitions. 
You can use the resulting classes as DTO-like objects for your services, or create Doctrine mapping config for them. Obviously this requires you to enable object serialization.
The resulting classes will have JMS\Serializer annotations by default, the use of which is optional, remove them if you're using the standard Symfony serializer.

See `resources:generate --help` for more details.

## License

KleijnWeb\SwaggerBundleTools is made available under the terms of the [LGPL, version 3.0](https://spdx.org/licenses/LGPL-3.0.html#licenseText).

