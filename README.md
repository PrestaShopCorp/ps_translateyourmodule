# Translate your Module

## About

With this module we can export all the translatable sentences and words in a XLSX file.
Also, we can import a XLSX file to import all the translations we want.

## Features

* Export the translatable strings
* Export the translatable and translated sentences
* Import new translations
* Export the translation's folder in a ZIP

## Composer

Build this project :
```sh
$ composer install
```

## PHP stan HowTo

To use PHP Stan locally, run the following commands :
```sh
$ docker run -tid --rm -v ps-volume:/var/www/html --name temp-ps prestashop/prestashop:1.7
$ docker run --rm --volumes-from temp-ps -v $PWD:/$MODULE_LOCAL_PATH/ps_translateyourmodule
 -e _PS_ROOT_DIR_=/var/www/html --workdir=/$MODULE_LOCAL_PATH/ps_translateyourmodule phpstan/phpstan:0.11.19 analyse --configuration=/$MODULE_LOCAL_PATH/ps_translateyo
urmodule/tests/phpstan/phpstan.neon
```

`MODULE_LOCAL_PATH` is the path to your module.

## Contributing

PrestaShop modules are open source extensions to the PrestaShop e-commerce solution. Everyone is welcome and even encouraged to contribute with their own improvements.

### Requirements

Contributors **must** follow the following rules:

* **Make your Pull Request on the "dev" branch**, NOT the "master" branch.
* Do not update the module's version number.
* Follow [the coding standards][1].

### Process in details

Contributors wishing to edit a module's files should follow the following process:

1. Create your GitHub account, if you do not have one already.
2. Fork this project to your GitHub account.
3. Clone your fork to your local machine in the ```/modules``` directory of your PrestaShop installation.
4. Create a branch in your local clone of the module for your changes.
5. Change the files in your branch. Be sure to follow the [coding standards][1]!
6. Push your changed branch to your fork in your GitHub account.
7. Create a pull request for your changes **on the _'dev'_ branch** of the module's project. Be sure to follow the [contribution guidelines][2] in your pull request. If you need help to make a pull request, read the [GitHub help page about creating pull requests][3].
8. Wait for one of the core developers either to include your change in the codebase, or to comment on possible improvements you should make to your code.

That's it: you have contributed to this open source project! Congratulations!

## License

This module is released under the [Academic Free License 3.0][AFL-3.0]

[1]: https://devdocs.prestashop.com/1.7/development/coding-standards/
[2]: https://devdocs.prestashop.com/1.7/contribute/contribution-guidelines/
[3]: https://help.github.com/articles/using-pull-requests
[AFL-3.0]: https://opensource.org/licenses/AFL-3.0