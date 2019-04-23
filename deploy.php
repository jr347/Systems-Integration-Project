#!/usr/bin/php
<?php

//**************************************************************************************************************
// Description:     This deploy.php file contains the file path and the target machine to scp the compressed
//                  files to. This static data is used by a separate executable file that is triggered based 
//                  on values passed. 
//**************************************************************************************************************

$filename = "/tmp/text_folder.tar.gz";
$target = "kamran@192.168.43.125:/home/kamran/Desktop";
echo "$filename $target";

?>
