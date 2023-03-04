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

	# This function will verify that the policy file exists and download from Git in Base64 format.
	function get_policy($project, $token, $id, $git_fqdn, $path, $policy, $branch) {
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

			if ($curl_response === false) {
				$info = curl_getinfo($curl);
				curl_close($curl);
				return -2;
			}

			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

         if ($curl_response == 401) {
				curl_close($curl);
				return -3;
			} 
			curl_close($curl);

			if ($httpcode == 200)
			{
				$result = json_decode($curl_response, true);
				return $result["content"];
			}
			else
				return -1;

	}

  
	#### Verify that the Project exists and get ID
	$result = get_policy($project, $token, $id, $git_fqdn, $path, $policy_name, $branch);
   
	if ($result == -2)
	{
      header("HTTP/1.1 500 Unable to connect");
		exit();
	}
	if ($result == -3)
	{
      header("HTTP/1.1 500 Unable to authenticate");
		exit();
	}
	if ($result == -1)
	{
      header("HTTP/1.1 500 Unable to find project");
		exit();
	}

   if (!is_dir("config_files/".$policy_name))
      mkdir("config_files/".$policy_name);

   
   $file= ("config_files/".$policy_name."/".$policy_name);
   $file_info= ("config_files/".$policy_name."/info.json");

   $policy_data = json_decode(base64_decode($result), true);
   $nap_name = $policy_data["policy"]["name"];
   if (array_key_exists("enforcementMode", $policy_data["policy"]))
      $enforcement = $policy_data["policy"]["enforcementMode"];
   else
      $enforcement = "blocking";

   if ($path=="")
      $url = $git_fqdn."/".$project."/".$path."?ref=".$branch;
   else
      $url = $git_fqdn."/".$project."?ref=".$branch;


   $info = '{"name":"'.$policy_name.'","nap_name":"'.$nap_name.'","enforcement":"'.$enforcement.'","git":"'.$url.'"}';

   file_put_contents($file,base64_decode($result));
   file_put_contents($file_info,$info);

   $policy_location = getcwd()."/config_files/".$policy_name."/".$policy_name;
   $policy_output = getcwd()."/config_files/".$policy_name."/policy-full-export.json";
	$convert_command = '/opt/app_protect/bin/convert-policy -i '.$policy_location.' -o '.$policy_output.' --full-export';
	
   $command = escapeshellcmd($convert_command);
	$output = shell_exec($command);

   header("Content-Type: application/json");
	echo $output;


?>

