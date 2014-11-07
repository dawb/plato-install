#Plato Creative - composer script#

You must have composer and foundation installed for this script to run correctly.

##How it works:##

1. `composer install` - this will grab all required packages
2. Once all packages have downloaded it will then attempt to run the post-install scripts
3. The first function in he script checks to make sure you don't already have a themes folder (if you do it probably means you have already run install so scripts do not need to be run again.
4. The second function will download the [plato-base](https://github.com/PlatoCreative/plato-base.git) repo
5. The next will move the plato-base files/folders in the root directory
6. After that it will download foundation via `foundation new foundation`
7. Finally the last script will organise foundation into a more usable structure and also creates a few scss files that we ofter use here at Plato Creative.

####TODO####
- Make changing the theme name easier e.g. via command line...
- Add grunt to save time compressing files and running time consuming tasks
- At the end of the script possibly run a start function that triggers a few tasks e.g. compass watch, grunt tasks...

*This script has been specifically built for Plato Creative, feel free to customise it to suit your requirments.*


