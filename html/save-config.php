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
   
	if( !(isset($_POST['policy'])) )
	{
         header("HTTP/1.1 500 Policy Not Defined");					
			exit();
	}
	else
	{
			$policy_name = $_POST['policy'];
	}
	if( !(isset($_POST['type'])) )
	{
         header("HTTP/1.1 500 Type not Defined");					
			exit();
	}
	else
	{
			$type = $_POST['type'];
	}
	if( !(isset($_POST['config'])) )
	{
         header("HTTP/1.1 500 Payload not Defined");					
			exit();
	}
	else
	{
			$payload = $_POST['config'];
	}

  
   $file= ("config_files/".$policy_name."/".$policy_name);
   $policy= file_get_contents($file);

   $policy_data = json_decode($policy, true);
   $new_data = json_decode(base64_decode($payload), true);
   


   if ($type == "Evasion-Techniques")
   {
      $policy_data["policy"]["blocking-settings"]["evasions"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "HTTP-Compliance")
   {
      $policy_data["policy"]["blocking-settings"]["http-protocols"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Violations")
   {
      $policy_data["policy"]["blocking-settings"]["violations"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Headers")
   {
      $policy_data["policy"]["headers"] = json_decode(base64_decode($payload),true);
   }   
   if ($type == "Cookies")
   {
      $policy_data["policy"]["cookies"] = json_decode(base64_decode($payload),true);
   }      
   if ($type == "Sensitive Parameters")
   {
      $policy_data["policy"]["sensitive-parameters"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Parameters")
   {
      $policy_data["policy"]["parameters"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Methods")
   {
      $policy_data["policy"]["methods"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Signatures")
   {
      $policy_data["policy"]["signatures"] = json_decode(base64_decode($payload),true);
   }      
   if ($type == "Signature-Sets")
   {
      $policy_data["policy"]["signature-sets"] = json_decode(base64_decode($payload),true);
   }  
   if ($type == "Threat-Campaigns")
   {
      $policy_data["policy"]["threat-campaigns"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "XML-Profiles")
   {
      $policy_data["policy"]["xml-profiles"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "JSON-Profiles")
   {
      $policy_data["policy"]["json-profiles"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "URLs")
   {
      $policy_data["policy"]["urls"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "FileTypes")
   {
      $policy_data["policy"]["filetypes"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Response-Codes")
   {
      $policy_data["policy"]["general"]["allowedResponseCodes"] = json_decode(base64_decode($payload),true);
   }   
   if ($type == "Whitelist-IPs")
   {
      $policy_data["policy"]["whitelist-ips"] = json_decode(base64_decode($payload),true);
   }   
   if ($type == "Response-Pages")
   {
      $policy_data["policy"]["response-pages"] = json_decode(base64_decode($payload),true);
   }   

   if ($type == "Bot-Defense-Browsers")
   {
      $policy_data["policy"]["bot-defense"]["mitigations"]["browsers"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Bot-Defense-Classes")
   {
      $policy_data["policy"]["bot-defense"]["mitigations"]["classes"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Bot-Defense-Signatures")
   {
      $policy_data["policy"]["bot-defense"]["mitigations"]["signatures"] = json_decode(base64_decode($payload),true);
   }
   if ($type == "Bot-Defense-Anomalies")
   {
      $policy_data["policy"]["bot-defense"]["mitigations"]["anomalies"] = json_decode(base64_decode($payload),true);
   }      
   if ($type == "JSON-Validation-Files")
   {
      $policy_data["policy"]["json-validation-files"]= json_decode(base64_decode($payload),true);
   }      
   if ($type == "Server-Technologies")
   {
      $policy_data["policy"]["server-technologies"]= json_decode(base64_decode($payload),true);
   }    
   if ($type == "Dataguard")
   {
      $policy_data["policy"]["data-guard"]= json_decode(base64_decode($payload),true);
   }  
   //   print_r(json_encode($policy_data));

   $file= ("config_files/".$policy_name."/".$policy_name);
   $file_sync= ("config_files/".$policy_name."/sync");
   file_put_contents($file,json_encode($policy_data,JSON_PRETTY_PRINT));
   file_put_contents($file_sync,"yes");


   $policy_location = getcwd()."/config_files/".$policy_name."/".$policy_name;
   $policy_output = getcwd()."/config_files/".$policy_name."/policy-full-export.json";
	$convert_command = '/opt/app_protect/bin/convert-policy -i '.$policy_location.' -o '.$policy_output.' --full-export';
	
   $command = escapeshellcmd($convert_command);
	$output = shell_exec($command);

   header("Content-Type: application/json");
	echo $output;

?>

