<?php
function test($a)
{
   echo "test".
      xdebug_call_file();
   
}

$ret = test(array('Dereck'));
phpinfo();
?>