<?php
class CronjobCommand extends CConsoleCommand{
    
    public function actionCreateFile(){
        
        $new_file   = 'hai.txt';
        $dir        = "/var/www/html/doothan/cron/" . $new_file;
        $f          = fopen($dir, 'w');
        
        fclose($f);
    }
}