# Environment Indicator module for Craft CMS 3.x

Displays a label of the current environment, eg. Local, Staging or Production.

## Requirements

This module requires Craft CMS 3.0.0 or later.

## Installation

To install the module, follow these instructions.

First, you'll need to add the contents of the `app.php` file to your `config/app.php` (or just copy it there if it does not exist). This ensures that your module will get loaded for each request. The file might look something like this:
```
return [
    'modules' => [
        'environment-indicator-module' => [
            'class' => \y7k\environmentindicatormodule\EnvironmentIndicatorModule::class,
        ],
    ],
    'bootstrap' => ['environment-indicator-module'],
];
```

After you have added this, you will need to do:

    composer dump-autoload

 …from the project’s root directory, to rebuild the Composer autoload map. This will happen automatically any time you do a `composer install` or `composer update` as well.

## Environment Indicator Overview

-Insert text here-

## Using Environment Indicator

-Insert text here-

## Environment Indicator Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Y7K](y7k.com)
