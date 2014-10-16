<?php
namespace ComposerScript;

use Composer\Script\Event;

class Installer
{
  
    public static function postUpdateCheck(Event $event)
    {
      $composer = $event->getComposer();
      
      // stream for terminal input/output
      $io = $event->getIO();
      if ($io->askConfirmation("Are you sure you want to install without running 'composer install --no-scripts' (This should never be run in production)?  ", false)) {
          // ok, continue on to composer install
          return true;
      }
    
      echo "All packages have been downloaded but no scripts have been run. *Scripts should never be run in production.*\n";
      
      // exit composer and terminate installation process
      exit;
      
    }
    
    public static function postUpdate(Event $event)
    {
      $composer = $event->getComposer();
      
      // move contents of plato-base to root
      rename('plato-base/mysite', 'mysite');
      rename('plato-base/assets', 'assets');
      rename('plato-base/themes', 'themes');
      rename('plato-base/.htaccess', '.htaccess');
      rename('plato-base/.gitignore', '.gitignore');
      rename('plato-base/favicon.ico', 'favicon.ico');
      
      // remove plato-base folder as we no longer need it.
      rmdir_recursive("plato-base");
          
      echo "Files copied from plato-base to root. plato-base has now been removed.\n";
    
    }

    public static function postPackageThemeCleanUp(Event $event){
      
      $composer = $event->getComposer();
      
      // once all files have moved or deleted we need to start organising the themes directory
      recurse_copy("themes/default/foundation", "themes/default");
      recurse_copy("themes/default/bower_components", "themes/default/js");
      
      // files have been moved so lets delete some stuff that we no longer need
      rmdir_recursive("themes/default/foundation"); // no longer required
      rmdir_recursive("themes/default/bower_components"); // no longer required
      rmdir_recursive("themes/default/.git"); // no git thanks!
      unlink("themes/default/.gitignore");
      unlink("themes/default/bower.json");
      unlink("themes/default/.bowerrc");
      unlink("themes/default/humans.txt");
      unlink("themes/default/README.md");
      unlink("themes/default/robots.txt");
      
      // now we need to create some files to save even more time ;)
      fopen("themes/default/scss/_layout.scss", "w");
      fopen("themes/default/scss/editor.scss", "w");
      fopen("themes/default/scss/typography.scss", "w");
      
      echo "Theme has now been organised, woop woop!.\n";
      
    }
    
}

/*
 * Recursive function to copy folders/files
*/
function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

/*
 * Recursive function to delete folders/files
*/
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}