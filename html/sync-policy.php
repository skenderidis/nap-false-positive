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

	if ($type=="gitlab")
	{ 
		# create the payload to send to Gitlab
		$payload = '{"encoding":"base64", "branch": "'.$branch.'", "content": "'.$new_policy.'", "commit_message": "'.$comment.'"}';

		# run function that will upload the updated file.
		$result = update_policy_gitlab($git_fqdn, $project, $token, $id, $path, $policy, $branch, $payload);

      if ($result == "Success")
      {
         echo '
         <div class="alert alert-success alert-dismissible fade show" role="alert">
         '.$output.'
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
      }
      else
      {
         echo '
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
         '.$result.'
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';

      }

	}
	
	if ($type=="gitea")
	{ 
		# create the payload to send to Gitlab
		$payload = '{"branch": "'.$branch.'", "content": "'.$new_policy.'", "sha": "'.$policy_content["msg"]["sha"].'", "commit_message": "'.$comment.'"}';

		# run function that will upload the updated file.
		$result = update_policy_gitea($git_fqdn, $project, $token, $path, $policy, $branch, $payload);


      if ($result == "Success")
      {
         echo '
         <div class="alert alert-success alert-dismissible fade show" role="alert">
         '.$output.'
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
      }
      else
      {
         echo '
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
         '.$result.'
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';

      }

	}


  
   $file= "config_files/".$policy_name."/sync";
   unlink($file);
   sleep (4);
?>

