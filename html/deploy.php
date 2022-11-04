<?php

	// Read the JSON GITLAB file 
	$json = file_get_contents('/etc/fpm/gitlab.json');
	$json_data = json_decode($json,true);

	# If request doesn't contain the gitlab variable return an error.
	# Gitlab variable is meant to be an ID.
	if( !(isset($_POST['gitlab'])) )
	{
			echo '
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Error!</strong>GitLab Destination not Set.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';					
			exit();
	}
	else
	{
		# Run through all the gitlab entries and try to match the ID.
		$found_git = "false";
		foreach($json_data as $git)
		{
			# Get all details for the Gitlab to be used.
			if($git["id"] == $_POST['gitlab'])
			{
				$found_git="true";
				$token = $git["token"];
				$gitlab = $git["fqdn"];
				$project = $git["project"];
				$branch = $git["branch"];
				$format = $git["format"];
				$path = $git["path"];
				if ($path == ".")
					$path = "";
			}
		}
		# If the ID is not found return an error.
		if ($found_git == "false")
		{
			echo '
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Error!</strong> GitLab not found on list. Click <a href="settings.php">here</a> to add it..
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';						
			exit();
		}
	}
	# If request doesn't contain the policy name as a variable, return an error.
	# We assume that the policy name is going to match with the file name and 
	# the file extension will be either .json or .yaml depending on the format.
	if( !(isset($_POST['policy'])) )
	{
		echo '
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Error!</strong> Policy not set.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';				
		exit();
	}
	else
		$policy = $_POST['policy'].".". strtolower($format);

	# The policy_data indicate what changed are required to be done on the policy.
	# Without this we return an error. 
	if( !(isset($_POST['policy_data'])) )
	{
		echo '
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Error!</strong>Data are missing from the request..
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';			
		exit();
	}
	else
		$policy_data = $_POST['policy_data'];

	# Check if the support ID is sent as a parameter.
		if( !(isset($_POST['support_id'])) )
		$support_id = "-";
	else
		$support_id = $_POST['support_id'];

	# Check if the Gitlab comment is sent as a parameter.
		if( !(isset($_POST['comment'])) )
		$comment = "-";
	else
		$comment = base64_decode($_POST['comment']);
		


	function json_validate($string)
	{
			// decode the JSON data
			$result = json_decode($string);

			// switch and check possible JSON errors
			switch (json_last_error()) {
					case JSON_ERROR_NONE:
							$error = ''; // JSON is valid // No error has occurred
							break;
					case JSON_ERROR_DEPTH:
							$error = 'The maximum stack depth has been exceeded.';
							break;
					case JSON_ERROR_STATE_MISMATCH:
							$error = 'Invalid or malformed JSON.';
							break;
					case JSON_ERROR_CTRL_CHAR:
							$error = 'Control character error, possibly incorrectly encoded.';
							break;
					case JSON_ERROR_SYNTAX:
							$error = 'Syntax error, malformed JSON.';
							break;
					// PHP >= 5.3.3
					case JSON_ERROR_UTF8:
							$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
							break;
					// PHP >= 5.5.0
					case JSON_ERROR_RECURSION:
							$error = 'One or more recursive references in the value to be encoded.';
							break;
					// PHP >= 5.5.0
					case JSON_ERROR_INF_OR_NAN:
							$error = 'One or more NAN or INF values in the value to be encoded.';
							break;
					case JSON_ERROR_UNSUPPORTED_TYPE:
							$error = 'A value of a type that cannot be encoded was given.';
							break;
					default:
							$error = 'Unknown JSON error occured.';
							break;
			}

			if ($error !== '') {
					// throw the Exception or exit // or whatever :)
					exit($error);
			}

			// everything is OK
			return $result;
	}
	# This function will verify that the Gitlab repo exists and we are able to connect.
	function verify_project($project, $token, $gitlab) {
		$headers = array(
			'Content-Type: application/json',
			'Accept: application/json, text/javascript, */*; ',
			'PRIVATE-TOKEN: ' . $token
			);

			$url = $gitlab."/api/v4/projects";
			
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
			$curl_response = curl_exec($curl);
		
			if ($curl_response === false) {
				curl_close($curl);
				return -2;
			}
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($curl_response == 401) {
				curl_close($curl);
				return -3;
			} 

			curl_close($curl);
			$result = json_decode($curl_response, true);

			$project_found = False;

			foreach ($result as $repo)
			{
				if ($repo["path_with_namespace"] == $project)
				{
					$project_found = True;
					return $repo["id"];
				}	
			}
			if (!$project_found )
				return -1;
	}
	# This function will verify that the policy file exists and download from Gitlab in Base64 format.
	function get_policy($project, $token, $id, $gitlab, $path, $policy, $branch) {
		$headers = array(
			'Content-Type: application/json',
			'Accept: application/json, text/javascript, */*; ',
			'PRIVATE-TOKEN: ' . $token
			);
			if ($path=="")
				$url = $gitlab."/api/v4/projects/".$id."/repository/files/".urlencode($policy)."?ref=".$branch;
			else
				$url = $gitlab."/api/v4/projects/".$id."/repository/files/".urlencode($path."/".$policy)."?ref=".$branch;

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
			curl_close($curl);

			if ($httpcode == 200)
			{
				$result = json_decode($curl_response, true);
				return $result["content"];
			}
			else
				return -1;

	}
	# This function will upload the policy file from Gitlab in Base64 format.
	function update_policy($project, $token, $id, $gitlab, $path, $policy, $branch, $payload) {
		$headers = array(
			'Content-Type: application/json',
			'Accept: application/json, text/javascript, */*; ',
			'PRIVATE-TOKEN: ' . $token
			);
			if ($path == "")
				$url = $gitlab."/api/v4/projects/".$id."/repository/files/".urlencode($policy)."?ref=".$branch;
			else
				$url = $gitlab."/api/v4/projects/".$id."/repository/files/".urlencode($path."/".$policy)."?ref=".$branch;

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
			$curl_response = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			$result = json_decode($curl_response, true);

			if ($curl_response === false) {
				$info = curl_getinfo($curl);
				curl_close($curl);
				return -1;
			}
		
			curl_close($curl);
			return $result;
			
	
	}

	#### Verify that the Project exists and get ID
	$id = verify_project($project, $token, $gitlab);

	# if the result of the verify project is -3 then it is an authentication issue
	if ($id == -3)
	{
		echo '
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					Unable to authenticate user.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		exit();
	}
	# if the result of the verify project is -2 then it is an connectivity issue
	if ($id == -2)
	{
		echo '
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					Unable to connect to <strong>"'.$gitlab.'"</strong>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		exit();
	}
	# if the result of the verify project is -1 then the repo was not found
	if ($id == -1)
	{

		echo '
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				Project <b>"'.$project.'"</b> not found 
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		exit();
	}

	#### Verify that the Policy exists and get contents exists
	$policy_content = get_policy($project, $token,  $id, $gitlab, $path, $policy, $branch);

	# if the result of the verify project is -2 then it is an connectivity issue
	if ($policy_content == -2)
	{
		echo '
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					Unable to connect to <b>"'.$gitlab.'"</b>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		exit();
	}
	# if the result of the verify project is -1 then the pollicy was not found
	if ($policy_content == -1)
	{

		echo '
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			Policy <b>"'.$policy.'"</b> in branch "'.$branch.'" was not found 
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
		exit();
	}

	# store the policy to a file
	file_put_contents("policy",base64_decode($policy_content));
	
	# Run the python script to make the policy changes
	$run_python_script = 'python3 modify-nap.py ' . strtolower($format) . ' ' . $policy_data ;
	$command = escapeshellcmd($run_python_script);
	$output = shell_exec($command);
	
	# if the output of the script includes Success word then the script was successful.
	if(!(strpos($output, 'Success') !== false))
	{
		echo '
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
  		'.$output.'
  		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
	else
	{ 

		# the python script will have created a file called "policy_mod".
		$new_policy = base64_encode(file_get_contents('policy_mod'));
		# create the payload to send to Gitlab
		$payload = '{"encoding":"base64", "branch": "'.$branch.'", "content": "'.$new_policy.'", "commit_message": "'.$comment.'"}';

		# run function that will upload the updated file.
		$result = update_policy($project, $token, $id, $gitlab, $path, $policy, $branch, $payload);

		echo '
		<div class="alert alert-success alert-dismissible fade show" role="alert">
  		'.$output.'
  		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
	
	# Delete Temp files
	unlink('policy');
	unlink('policy_mod');

?>
