<?php

	if( !(isset($_POST['policy'])) )
	{
         header("HTTP/1.1 500 Policy Not Defined");					
			exit();
	}
	else
	{
			$policy_name = $_POST['policy'];
	}

  
   $file= "config_files/".$policy_name."/sync";
   unlink($file);
   sleep (4);
?>

