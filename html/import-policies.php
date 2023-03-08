<?php

   session_start();

   if (!isset($_SESSION['auth']))
   {
      header("Location: login.php"); 
      exit();
   }
   if (!$_SESSION["auth"])
   {
      header("Location: login.php"); 
      exit();
   }

	if( !(isset($_POST['name'])) )
	{
         header("HTTP/1.1 500 Name Not Found");					
			exit();
	}
	else
	{
			$policy_name = $_POST['name'];
	}
	if( !(isset($_POST['uuid'])) )
	{
      header("HTTP/1.1 500 ID Not Found");				
      exit();
	}
	else
		$uuid = $_POST['uuid'];
	

   $json = file_get_contents('/etc/fpm/git.json');
   $json_data = json_decode($json,true);
   $found_git="false";
   foreach($json_data as $git)
   {
      # Get all details for the Gitlab to be used.
      if($git["uuid"] == $uuid)
      {
         $found_git="true";
         $token = $git["token"];
         $git_fqdn = $git["fqdn"];
         $project = $git["project"];
         $branch = $git["branch"];
         $format = $git["format"];
         $path = $git["path"];
         $id = $git["id"];
         $type = $git["type"];
         if ($path == ".")
            $path = "";
      }
   }
   # If the ID is not found return an error.
   if ($found_git == "false")
   {
      header("HTTP/1.1 500 Git Not Found on the list");				
      exit();
   }

	# This function will download the policy from Gitlab in Base64 format.
	function get_policy_gitlab($git_fqdn, $project, $token, $id, $path, $policy, $branch) {
		$headers = array(
			'Content-Type: application/json',
			'Accept: application/json, text/javascript, */*; ',
			'PRIVATE-TOKEN: ' . $token
			);
			if ($path=="")
				$url = $git_fqdn."/api/v4/projects/".$id."/repository/files/".urlencode($policy)."?ref=".$branch;
			else
				$url = $git_fqdn."/api/v4/projects/".$id."/repository/files/".urlencode($path."/".$policy)."?ref=".$branch;

         $curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
			$curl_response = curl_exec($curl);
         $result  = array("status" => 0, "msg" => "-");
         
         if (curl_errno($curl))
         {
            $result["status"]=0;
            $result["msg"]=curl_error($curl);
            return $result;
         }

			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
        
			if ($httpcode == 200)
			{
				$policy = json_decode($curl_response, true);
            $result["status"]=1;
            $result["msg"]=$policy;
            return $result;
			}
			else
         {
            $result["status"]=0;
            $result["msg"]= $httpcode . " HTTP code received while getting the policy '".$policy. "' from " .$project."/".$path;
            return $result;
         }

	}
  
	# Download the file from Gitea in Base64 format.
	function get_policy_gitea($git_fqdn, $project, $token, $path, $policy, $branch) {
		$headers = array(
			'Content-Type: application/json',
			'Accept: application/json, text/javascript, */*; ',
			'Authorization: token ' . $token
			);
			if ($path=="")
				$url = $git_fqdn."/api/v1/repos/".$project."/contents/".urlencode($policy)."?ref=".$branch;
			else
				$url = $git_fqdn."/api/v1/repos/".$project."/contents/".urlencode($path."/".$policy)."?ref=".$branch;

         $curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
			$curl_response = curl_exec($curl);
         $result  = array("status" => 0, "msg" => "-");
         
         if (curl_errno($curl))
         {
            $result["status"]=0;
            $result["msg"]=curl_error($curl);
            return $result;
         }

			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
        
			if ($httpcode == 200)
			{
				$policy = json_decode($curl_response, true);
            $result["status"]=1;
            $result["msg"]=$policy;
            return $result;
			}
			else
         {
            $result["status"]=0;
            $result["msg"]= $httpcode . " HTTP code received while getting the policy '".$policy. "' from " .$project."/".$path;
            return $result;
         }

	}   

   if ($type == "gitlab")
   {
      $result = get_policy_gitlab($git_fqdn, $project, $token, $id,  $path, $policy_name, $branch);
      
      if ($result["status"] == 0)
      {
         header("HTTP/1.1 500 Get Policy Gitlab Error");
         echo '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
               <b>Failed!</b> '.$result["msg"].'
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';         
         exit();
      }
      $downloaded_policy=base64_decode($result["msg"]["content"]);
   }
   if ($type == "gitea")
   {
      $result = get_policy_gitea($git_fqdn, $project, $token, $path, $policy_name, $branch);
      
      if ($result["status"] == 0)
      {
         header("HTTP/1.1 500 Get Policy Gitea Error");
         echo '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
               <b>Failed!</b> '.$result["msg"].'
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';         
         exit();
      }
      $downloaded_policy=base64_decode($result["msg"]["content"]);     
   }


   # Create a directory with the name of the policy-file in case it doesnt exists. 
   # if it exists it will overwrite all the files
   if (!is_dir("config_files/".$policy_name))
      mkdir("config_files/".$policy_name);

   # setup 2 variables with the name of the policy and the info.json
   $file= ("config_files/".$policy_name."/".$policy_name);
   $file_info= ("config_files/".$policy_name."/info.json");

   #save policy to file
   file_put_contents($file,$downloaded_policy);

   #check if the policy is JSON/YAML. If YAML, convert to JSON
   if (strpos($policy_name, "yaml") > 0 || strpos($policy_name, "yml") > 0 )
   {
      $yaml = 1;
      $convert_2_json = 'python3 yaml2json '.$file;
      $command = escapeshellcmd($convert_command);
      $output = shell_exec($command);
      if ($output!=success)
      {
         header("HTTP/1.1 500 Parsing Error YML2JSON");
         echo '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
               <b>Failed!</b> '.$output.'
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';         
         exit();         
      }
   }   
   else
      $yaml = 0;
   

   
   $policy_data = json_decode($downloaded_policy, true);

   $nap_name = $policy_data["policy"]["name"];
   if (array_key_exists("enforcementMode", $policy_data["policy"]))
      $enforcement = $policy_data["policy"]["enforcementMode"];
   else
      $enforcement = "blocking";

   if ($path=="")
      $url = $git_fqdn."/".$project."/".$path."?ref=".$branch;
   else
      $url = $git_fqdn."/".$project."?ref=".$branch;


   $info = '{"name":"'.$policy_name.'","nap_name":"'.$nap_name.'","enforcement":"'.$enforcement.'","type":"'.$type.'","format":"'.$format.'","uuid":"'.$uuid.'","git":"'.$url.'"}';

   
   file_put_contents($file_info,$info);

   $policy_location = getcwd()."/config_files/".$policy_name."/".$policy_name;
   $policy_output = getcwd()."/config_files/".$policy_name."/policy-full-export.json";
	$convert_command = '/opt/app_protect/bin/convert-policy -i '.$policy_location.' -o '.$policy_output.' --full-export';
	
   $command = escapeshellcmd($convert_command);
	$output = shell_exec($command);

   header("Content-Type: application/json");
	echo $output;


?>

