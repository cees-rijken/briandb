<?php
if($_SERVER['REQUEST_URI']=='/CHEESEMAKERS.php')
{
    die('Brian says no...');
}

$c[0]['name'] = 'Cees';
$c[0]['email'] = 'hallo@ceesrijken.nl';
$c[0]['pass'] = 'somepasswordyaknow';
$c[0]['permissions'] = array('CREATE','ALTER','DROP','SHOW','SELECT','FILELIST');

$c[2]['name'] = 'Klaas';
$c[2]['email'] = 'hallo@ceesrijken.nl';
$c[2]['pass'] = 'yaknowsomepassword';
$c[0]['permissions'] = array('CREATE', 'ALTER', 'DROP', 'SHOW', 'SELECT', 'FILELIST');