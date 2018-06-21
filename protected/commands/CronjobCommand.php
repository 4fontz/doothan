<?php
class CronjobCommand extends CConsoleCommand{
    
    public function actionCreateFile(){
        
        $new_file   = date('Y-m-d H:i:s') . '.txt';
        $dir        = "/var/www/html/doothan/cron/" . $new_file;
        $f          = fopen($dir, 'w');
        
        fclose($f);
    }
}