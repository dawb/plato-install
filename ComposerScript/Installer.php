<?php
namespace ComposerScript;

use Composer\Script\Event;

class Installer
{
  
    public static function postUpdateCheck(Event $event)
    {
      $composer = $event->getComposer();
      
      // if the themes folder does exists then obviously we shouldn't be running comoser install with scripts, as they've already been run.
      if(file_exists("themes")){
        
        echo "No scripts have been trigged as we detected the themes directory.\nComposer install has completed but with no scripts.";
        exit;
      
      }else{
        
        echo "No theme directory was found, composer will run all scripts...";
        
        return true;
        
      }
      
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
      
      $theme = "default";
      
      // once all files have moved or deleted we need to start organising the themes directory
      recurse_copy("themes/".$theme."/ff", "themes/default");
      recurse_copy("themes/".$theme."/bower_components", "themes/".$theme."/js");
      recurse_copy("themes/".$theme."/js/foundation", "themes/".$theme."/foundation"); // foundation does not need to goes into the js folder :/
      
      // files have been moved so lets delete some stuff that we no longer need
      rmdir_recursive("themes/".$theme."/ff"); // no longer required
      rmdir_recursive("themes/".$theme."/bower_components"); // no longer required
      rmdir_recursive("themes/".$theme."/.git"); // no git thanks!
      unlink("themes/".$theme."/.gitignore");
      unlink("themes/".$theme."/bower.json");
      unlink("themes/".$theme."/.bowerrc");
      unlink("themes/".$theme."/humans.txt");
      unlink("themes/".$theme."/README.md");
      unlink("themes/".$theme."/robots.txt");
      
      // now we need to create some files to save even more time ;)
      fopen("themes/".$theme."/scss/_layout.scss", "w");
      fopen("themes/".$theme."/scss/editor.scss", "w");
      fopen("themes/".$theme."/scss/typography.scss", "w");
            
      echo "Theme has now been organised. Boom!\n";
      
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
        if (is_dir("$dir/$file")){
          rmdir_recursive("$dir/$file");
        }else{
          chmod("$dir/$file", 0777);
          unlink("$dir/$file");
        } 
    }
    rmdir($dir);
}