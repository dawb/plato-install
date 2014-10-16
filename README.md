plato-install
=============

A super simple composer install with custom scripts

How it works:

1. `composer install` this will grab all all required packages.
2. Once all packages have downloaded it will then attempted to run all scripts
3. The first script actually double checks if you want to run all other scripts
4. The second script will download the plato-base git repo
5. The third will move the plato-base files/folders in the root directory
6. Fourth script will download foundation via `foundation new foundation`
7. Finally the last script will organise foundation into a more usable structure and also creates a few scss files that we ofter use.

This script has been specifically built for plato and how we begin projects.
