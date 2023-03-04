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

   if( !(isset($_GET['policy']))) 
	{
		header("Location: login.php"); 
		exit();
	}

   if(!isset($_GET["policy"]))
   {
     Header("Location: policies.php");
     exit();
   }
   else
   {
      $file_location = "config_files/".$_GET["policy"]."/policy-full-export.json";
      $file_location_original = "config_files/".$_GET["policy"]."/".$_GET["policy"];
   }

   // Read and decode the original policy file 
   $json = file_get_contents($file_location_original);
   $json_data_original = json_decode($json,true);
   
   //whitelist-ips
   if(array_key_exists("whitelist-ips", $json_data_original["policy"]))
      $whitelist_ips_original = "var whitelist_ips_original = " . json_encode($json_data_original["policy"]["whitelist-ips"])  . " ;";
   else
      $whitelist_ips_original = "var whitelist_ips_original = [] ;";	

   //signatures
   if(array_key_exists("signatures", $json_data_original["policy"]))
      $signatures_original = "var signatures_original = " . json_encode($json_data_original["policy"]["signatures"])  . " ;";
   else
      $signatures_original = "var signatures_original = [] ;";
 

   //signatures
   if(array_key_exists("signature-sets", $json_data_original["policy"]))
      $signature_sets_original = "var signature_sets_original = " . json_encode($json_data_original["policy"]["signature-sets"])  . " ;";
   else
      $signature_sets_original = "var signature_sets_original = [] ;";

   //server_technologies
  if(array_key_exists("server-technologies", $json_data_original["policy"]))
     $server_technologies_original = "var server_technologies_original = " . json_encode($json_data_original["policy"]["server-technologies"])  . " ;";
  else
     $server_technologies_original = "var server_technologies_original = [] ;";

  //dataguard
  if(array_key_exists("data-guard", $json_data_original["policy"]))
     $dataguard_raw_original = "var dataguard_original = " . json_encode($json_data_original["policy"]["data-guard"])  . " ;";
   else
     $dataguard_raw_original = "var dataguard_original = [] ;";
     
  //json_validation_files
  if(array_key_exists("json-validation-files", $json_data["policy"]))
     $json_validation_files_original = "var json_validation_files_original = " . json_encode($json_data["policy"]["json-validation-files"])  . " ;";
  else
     $json_validation_files_original = "var json_validation_files_original = [] ;";

 

   
   // Read the exported full policy 
   $json = file_get_contents($file_location);
     
   // Decode the exported full policy 
   $json_data = json_decode($json,true);
   
   //csrf-protection
   if($json_data["policy"]["csrf-protection"]["enabled"])
   {
      $csrf_protection = "<span class='green'>Enabled</span>";
      $csrf_protection_form = "enabled";
   }
   else
   {
      $csrf_protection = "<span class='red'>Disabled</span>";
      $csrf_protection_form = "disabled";
   }
   //enforcementMode
   if($json_data["policy"]["enforcementMode"]=="blocking")
      $enforcement_mode = "<span class='green'>Blocking</span>";
   else
      $enforcement_mode = "<span class='red'>Transparent</span>";
      
   //Bot Protection
   if($json_data["policy"]["bot-defense"]["settings"]["isEnabled"])
   {
      $bot_protection = "<span class='green'>Enabled</span>";
      $bot_protection_form = "enabled";
   }
   else
   {
      $bot_protection = "disabled";
      $bot_protection_form = "disabled";
   }
   //caseSensitiveHttpHeaders
   if($json_data["policy"]["bot-defense"]["settings"]["caseSensitiveHttpHeaders"])
   {
      $botcasesensitive = "<i class='fa fa-check-square-o fa-2x green'></i>";
   }
   else
      $botcasesensitive = "<i class='fa fa-times fa-2x black' ></i>";

   

   //enforcer-settings/httpOnlyAttribute
   if($json_data["policy"]["enforcer-settings"]["enforcerStateCookies"]["httpOnlyAttribute"])
      $httpOnlyAttribute = "<i class='fa fa-check-square-o fa-2x green'></i>";
   else
      $httpOnlyAttribute = "<i class='fa fa-times fa-2x black' ></i>";

   

   //minimumAccuracyForAutoAddedSignatures
	$minimumAccuracyForAutoAddedSignatures = $json_data["policy"]["signature-settings"]["minimumAccuracyForAutoAddedSignatures"];

   //caseInsensitive
   if($json_data["policy"]["caseInsensitive"])
      $caseInsensitive = "<i class='fa fa-check-square-o fa-2x green'></i>";
   else
      $caseInsensitive = "<i class='fa fa-times fa-2x black' ></i>";

   //applicationLanguage
   if(array_key_exists("applicationLanguage", $json_data["policy"]))
      $applicationLanguage = $json_data["policy"]["applicationLanguage"];
   else
      $applicationLanguage = "utf-8";

   //trustXff
   if($json_data["policy"]["general"]["trustXff"])
      $trustXff = "<i class='fa fa-check-square-o fa-2x green'></i>";
   else
      $trustXff = "<i class='fa fa-times fa-2x black' ></i>";
   
   //customXffHeaders
	if(array_key_exists("customXffHeaders", $json_data["policy"]["general"]))
      $customXffHeaders = $json_data["policy"]["customXffHeaders"];
   else
      $customXffHeaders = "Not Configured";  

   //maskCreditCardNumbersInRequest
   if($json_data["policy"]["general"]["maskCreditCardNumbersInRequest"])
      $maskCreditCardNumbersInRequest = "<i class='fa fa-check-square-o fa-2x green'></i>";
   else
      $maskCreditCardNumbersInRequest = "<i class='fa fa-times fa-2x black' ></i>";
	  
   //dataguard
	if($json_data["policy"]["data-guard"]["enabled"])
		$dataguard = '<span class="green">Enabled</span>';
 	else
		$dataguard = '<span class="red">Disabled</span>';

   //maskData
	if(array_key_exists("maskData", $json_data["policy"]["data-guard"]))
		if($json_data["policy"]["data-guard"]["maskData"])
			$maskData = "<i class='fa fa-check-square-o fa-2x green'></i>";
 		else
			$maskData = "<i class='fa fa-times fa-2x black' ></i>";
	else
    	$maskData = "N/A";	  
	
   //usSocialSecurityNumbers
	if(array_key_exists("usSocialSecurityNumbers", $json_data["policy"]["data-guard"]))
		if($json_data["policy"]["data-guard"]["maskData"])
			$usSocialSecurityNumbers = "<i class='fa fa-check-square-o fa-2x green'></i>";
 		else
			$usSocialSecurityNumbers = "<i class='fa fa-times fa-2x black' ></i>";
	else
    	$usSocialSecurityNumbers = "N/A";	 	  

	//creditCardNumbers
	if(array_key_exists("creditCardNumbers", $json_data["policy"]["data-guard"]))
		if($json_data["policy"]["data-guard"]["maskData"])
			$creditCardNumbers = "<i class='fa fa-check-square-o fa-2x green'></i>";
 		else
			$creditCardNumbers = "<i class='fa fa-times fa-2x black' ></i>";
	else
    	$creditCardNumbers = "N/A";	 	  

   //enforcementUrls
	if(array_key_exists("enforcementUrls", $json_data["policy"]["data-guard"]))
		if( sizeof($json_data["policy"]["data-guard"]["enforcementUrls"])==0)
			$enforcementUrls = "List Empty";
		else
			$enforcementUrls = implode($json_data["policy"]["data-guard"]["enforcementUrls"], "<br>");
	else
		$enforcementUrls = "Not Configured";	

   //server_technologies
	if(array_key_exists("server-technologies", $json_data["policy"]))
		$server_technologies = "var server_technologies = " . json_encode($json_data["policy"]["server-technologies"])  . " ;";
   else
	   $server_technologies = "var server_technologies = [] ;";

   //dataguard
   if(array_key_exists("data-guard", $json_data["policy"]))
      $dataguard_raw = "var dataguard = " . json_encode($json_data["policy"]["data-guard"])  . " ;";
 	else
      $dataguard_raw = "var dataguard = [] ;";


      
   //json_validation_files
   if(array_key_exists("json-validation-files", $json_data["policy"]))
	   $json_validation_files = "var json_validation_files = " . json_encode($json_data["policy"]["json-validation-files"])  . " ;";
	else
		$json_validation_files = "var json_validation_files = [] ;";

   //allowedResponseCodes
   $allowedResponseCodes = $json_data["policy"]["general"]["allowedResponseCodes"];
   $temp_allowedResponseCodes= "var temp_allowedResponseCodes = " . json_encode($allowedResponseCodes) . " ;";

	$string = json_encode($allowedResponseCodes); 
	$string = str_replace('[','[{"name":"',$string);
	$string = str_replace(',','"}, {"name":"',$string);
	$string = str_replace(']','"}]', $string);
	$allowed_responses = "var allowed_responses = " . $string . " ;";
	$blocking_settings = "var blocking_settings = " . json_encode($json_data["policy"]["blocking-settings"]["violations"]) . " ;";
	$evasion = "var evasion = " .  json_encode($json_data["policy"]["blocking-settings"]["evasions"]) . " ;";
	$compliance = "var compliance = " . json_encode($json_data["policy"]["blocking-settings"]["http-protocols"]) . " ;";
	$file_types = "var file_types = " . json_encode($json_data["policy"]["filetypes"])  . " ;";
	$csrf = "var csrf = " . json_encode($json_data["policy"]["csrf-urls"])  . " ;";
	$methods = "var methods = " . json_encode($json_data["policy"]["methods"]) . " ;";
	$cookies = "var cookies = " . json_encode($json_data["policy"]["cookies"])  . " ;";
	$sensitive_param = "var sensitive_param = " . json_encode($json_data["policy"]["sensitive-parameters"])  . " ;";
	$headers = "var headers = " . json_encode($json_data["policy"]["headers"])  . " ;";
	$signature_sets = "var signature_sets = " . json_encode($json_data["policy"]["signature-sets"])  . " ;";
	$url = "var url = " . json_encode($json_data["policy"]["urls"]). " ;";
	$bot_defense = "var bot_defense = " . json_encode($json_data["policy"]["bot-defense"]["mitigations"]["classes"])  . " ;";
	$response_pages = "var response_pages = " . json_encode($json_data["policy"]["response-pages"])  . " ;";	
	$json_profiles = "var json_profiles = " . json_encode($json_data["policy"]["json-profiles"]) . " ;"; 

	$xml_profiles = "var xml_profiles = " . json_encode($json_data["policy"]["xml-profiles"]) . " ;"; 

   $parameters = "var parameters = " . json_encode($json_data["policy"]["parameters"]) . " ;";
   
  
	if(array_key_exists("browsers", $json_data["policy"]["bot-defense"]["mitigations"]))
		$bot_defense_browsers = "var bot_defense_browsers = " . json_encode($json_data["policy"]["bot-defense"]["mitigations"]["browsers"])  . " ;";
	else
		$bot_defense_browsers = "var bot_defense_browsers = [] ;";	

   if(array_key_exists("anomalies", $json_data["policy"]["bot-defense"]["mitigations"]))
		$bot_defense_anomalies = "var bot_defense_anomalies = " . json_encode($json_data["policy"]["bot-defense"]["mitigations"]["anomalies"])  . " ;";
	else
		$bot_defense_anomalies = "var bot_defense_anomalies = [] ;";	

   if(array_key_exists("signatures", $json_data["policy"]["bot-defense"]["mitigations"]))
		$bot_defense_signatures = "var bot_defense_signatures = " . json_encode($json_data["policy"]["bot-defense"]["mitigations"]["signatures"])  . " ;";
	else
		$bot_defense_signatures = "var bot_defense_signatures = [] ;";	  
      
      
   if(array_key_exists("signature-requirements", $json_data["policy"]))
	{
		$signature_requirements = "var signature_requirements = " . json_encode($json_data["policy"]["signature-requirements"])  . " ;";
		$signature_requirements_display = False;
	}	
	else
	{
		$signature_requirements = "var signature_requirements = [] ;";
		$signature_requirements_display = True;
	}	

   

	if(array_key_exists("threat-campaigns", $json_data["policy"]))
	{
      $original_threat_campaigns =  "var original_threat_campaigns = " . json_encode($json_data["policy"]["threat-campaigns"])  . " ;";
      // Hack to add "All Threat Campaigns" as an entry
      $all_tc = array("name"=>"All Threat Campaigns", "enabled"=>true);
      array_push($json_data["policy"]["threat-campaigns"], $all_tc);
		$threat_campaigns = "var threat_campaigns = " . json_encode($json_data["policy"]["threat-campaigns"])  . " ;";
	}	
	else
	{
		$threat_campaigns = "var threat_campaigns = [{'name':'All Threat Campaigns', 'enabled':true}] ;";
		$original_threat_campaigns = "var original_threat_campaigns = [] ;";
	}	
	
	if(array_key_exists("signatures", $json_data["policy"]))
		$signatures = "var signatures = " . json_encode($json_data["policy"]["signatures"])  . " ;";
	else
		$signatures = "var signatures = [] ;";

	
	if(array_key_exists("whitelist-ips", $json_data["policy"]))
		$whitelist_ips = "var whitelist_ips = " . json_encode($json_data["policy"]["whitelist-ips"])  . " ;";
	else
		$whitelist_ips = "var whitelist_ips = [] ;";	


?>

<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="Kostas Skenderidis - skenderidis@gmail.com">
      <title>NAP Policy Editor</title>
      <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- Bootstrap core CSS -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/flags16.css" rel="stylesheet">
      <link href="css/flags32.css" rel="stylesheet">

      <!-- Custom styles for this template -->
      <link href="dashboard.css" rel="stylesheet">

   </head>
   <body>
      <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
         <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><img src="images/app-protect.svg" width=32/> &nbsp; NGINX App Protect</a>
         <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="navbar-nav">
            <div class="nav-item text-nowrap">
               <a class="nav-link px-3" href="https://docs.nginx.com/nginx-app-protect/">Sign out</a>
            </div>
         </div>
      </header>
      <div class="container-fluid">
         <div class="row">
            <nav id="sidebarMenu" class="col-md-1 col-lg-1 d-md-block bg-light sidebar collapse">
               <div class="position-sticky pt-3">
                  <ul class="nav flex-column">
                     <li class="nav-item" >
                        <a class="nav-link" href="#">
                        <span data-feather="file"></span>
                        Violations
                        </a>
                     </li>
							<li class="nav-item" style="background-color:#d2d8dc">
                        <a class="nav-link active" aria-current="page" href="policies.php">
                        <span data-feather="home"></span>
                        	Policies
                        </a>
                     </li>
							<li class="nav-item">
                        <a class="nav-link" aria-current="page" href="settings.php">
                        <span data-feather="home"></span>
                        	Settings
                        </a>
                     </li>
                  </ul>
               </div>
            </nav>
            <main class="col-md-11 ms-sm-auto col-lg-09 px-md-4">
               <div class="row align-items-center">
                  <div class="title"> NAP Policy: <b><?php echo $_GET['policy']; ?> </b>
                  <button class="btn btn-primary" id="sync_button" style="float:right" <?php if (!file_exists("config_files/".$_GET["policy"]."/sync")) echo "hidden"; ?> >Sync <i class="fa fa-refresh"></i> </button>
                  </div>
               </div>

               <div class="row">

                  <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#taboverview" type="button" role="tab" aria-controls="taboverview" aria-selected="true">Overview</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabevasion" type="button" role="tab" aria-controls="tabevasion" aria-selected="false">Settings</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabsignatures" type="button" role="tab" aria-controls="tabsignatures" aria-selected="false">Signatures</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabfiletypes" type="button" role="tab" aria-controls="tabfiletypes" aria-selected="false">File Types</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabparameters" type="button" role="tab" aria-controls="tabparameters" aria-selected="false">Parameters</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#taburls" type="button" role="tab" aria-controls="taburls" aria-selected="false">URLs</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabcookies" type="button" role="tab" aria-controls="tabcookies" aria-selected="false">Headers</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabprofiles" type="button" role="tab" aria-controls="tabprofiles" aria-selected="false">Profiles</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabbotdefense" type="button" role="tab" aria-controls="tabbotdefense" aria-selected="false">Bot</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabmethods" type="button" role="tab" aria-controls="tabmethods" aria-selected="false">Others</button>
                    </div>
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="taboverview" role="tabpanel" aria-labelledby="taboverview-tab">
                           
                        	<div class="row">

                           		<div class="col-5">
                                	<div class="panel">
                                    	<div class="title"> General Settings 
                                             <div class="btn-group" style="float:right">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="general_json" data-bs-toggle="modal" data-bs-target="#generalModal">Edit</button>
                                             </div>                                   
                                       </div>
                                    	<div class="line"></div>
                                    	<div class="content">
                                          <table id="general" class="table table-striped table-bordered" style="width:100%">
                                             <thead>
                                                   <tr>
                                                   <th>Settings</th>
                                                   <th>Value</th>
                                                   <th style="width: 15px; text-align: center;"> Edit </th>
                                                   </tr>
                                             </thead>
                                             <tbody>
                                                <tr>
                                                   <td>Enforcement Mode</td>
                                                   <td> <b><?php echo $enforcement_mode; ?><b></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Bot Protection</td>
                                                   <td><b><?php echo $bot_protection ?></b></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>CSRF Protection</td>
                                                   <td><b><?php echo $csrf_protection ?></b></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>                                                                                        
                                                <tr>
                                                   <td>Template</td>
                                                   <td><?php echo $json_data["policy"]["template"]["name"]; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Description</td>
                                                   <td><?php echo $json_data["policy"]["description"]; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Max Cookie Length</td>
                                                   <td><?php echo $json_data["policy"]["cookie-settings"]["maximumCookieHeaderLength"]; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Max Header Length</td>
                                                   <td><?php echo $json_data["policy"]["header-settings"]["maximumHttpHeaderLength"]; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Application Language</td>
                                                   <td><?php echo $applicationLanguage; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Trust XFF</td>
                                                   <td><?php echo $trustXff; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>XFF Headers</td>
                                                   <td><?php echo $customXffHeaders; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Mask Credit Card</td>
                                                   <td><?php echo $maskCreditCardNumbersInRequest; ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Case Insensitive</td>
                                                   <td><?php echo $caseInsensitive ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>
                                                <tr>
                                                   <td>Bot Case Sensitive Headers</td>
                                                   <td><?php echo $botcasesensitive ?></td>
                                                   <td class="edit_button"><i class="fa fa-edit"></i></td>
                                                </tr>                                                
                                             </tbody>
                                          </table>
                                    	</div>
                                 	</div>
                              	</div>

                            	<div class="col-7">
                                 	<div class="panel">
                                    	<div class="title"> Blocking Settings 
                                          <div class="btn-group" style="float:right">
                                             <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="blocking_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                          </div>
                                       </div>
                                    	<div class="line"></div>
                                    	<div class="content">
                                       		<table id="blocking_settings" class="table table-striped table-bordered" style="width:100%">
                                          		<thead>
                                                   <tr>
                                                      <th>Decription</th>
                                                      <th style="width: 45px; text-align: center;">Alarm</th>
                                                      <th style="width: 45px; text-align: center;">Block</th>
                                                      <th style="width: 15px; text-align: center;">Edit</th>
                                                   </tr>
                                          		</thead>
                                       		</table>
                                    	</div>
                                 	</div>
                              	</div>

                            	<div class="col-3" hidden>
                                 	<div class="panel">
                                    	<div class="title"> Blocking Settings </div>
                                    	<div class="line"></div>
                                    	<div class="content">
		 												 <?php echo '<pre>' . json_encode($json_data["policy"]["urls"], JSON_PRETTY_PRINT) . '</pre>'; ?>
                                    	</div>
                                 	</div>
                              	</div>

								  
								

                              
                           </div>

                        </div>
                        <div class="tab-pane fade" id="tabevasion" role="tabpanel" aria-labelledby="tabevasion-tab">
                           
                           <div class="row">

                              <div class="col-5">
                                 <div class="panel">
                                    <div class="title"> HTTP Compliance
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="compliance_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="compliance" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>HTTP Protocol Compliance</th>
                                                <th style="width:60px; text-align: center;">Enabled</th>
                                                <th style="width:35px; text-align: center;">Edit</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title"> Evasions 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="evasion_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="evasion" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>Evasion Technique Name</th>
                                                <th style="width:60px; text-align: center;">Enabled</th>
                                                <th style="width:35px; text-align: center;">Edit</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-3">
                                 <div class="panel">
                                       <div class="title"> Dataguard 
                                          <div class="btn-group" style="float:right">
                                             <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="dataguard_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                          </div>                                          
                                       </div>
                                       <div class="line"></div>
                                       <div class="content">
                                          <table id="dataguard" class="table table-striped table-bordered" style="width:100%">
                                             <thead>
                                                   <tr>
                                                   <th>Settings</th>
                                                   <th>Value</th>
                                                   </tr>
                                             </thead>
                                             <tbody>
                                                   <tr>
                                                   <td>Status</td>
                                                   <td> <b><?php echo $dataguard; ?><b></td>
                                                   </tr>
                                                   <tr>
                                                   <td>Enforcement Mode</td>
                                                   <td><?php echo $json_data["policy"]["data-guard"]["enforcementMode"]; ?></td>
                                                   </tr>
                                                   <tr>
                                                   <td>maskData</td>
                                                   <td><?php echo $maskData; ?></td>
                                                   </tr>                                            
                                                   <tr>
                                                   <td>usSocialSecurityNumbers</td>
                                                   <td><?php echo $usSocialSecurityNumbers; ?></td>
                                                   </tr>
                                                   <tr>
                                                   <td>creditCardNumbers</td>
                                                   <td><?php echo $creditCardNumbers; ?></td>
                                                   </tr>
                                                   <tr>
                                                   <td>enforcementUrls</td>
                                                   <td><?php echo $enforcementUrls; ?></td>
                                                   </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>                              
							  
                           </div>

                        </div>
                        <div class="tab-pane fade" id="tabsignatures" role="tabpanel" aria-labelledby="tabsignatures-tab">
   
                           <p class="title"> Accuracy for auto added signatures: <span class="green"><b><?php echo $minimumAccuracyForAutoAddedSignatures; ?></b></span></p>

                           <div class="row">
                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title"> Signature Sets 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="signature_sets_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="signature_sets" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                             <th style="width: 15px; text-align: center;"></th>
                                             <th>Signature Sets</th>
                                             <th style="width: 25px; text-align: center;">Alarm</th>
                                             <th style="width: 30px; text-align: center;">Block</th>
                                             <th style="width: 60px; text-align: center;">Type</th>
                                             <th colspan=2 style="width: 35px; text-align: center;">Edit</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>


                              <div class="col-6">

                                 <div class="panel">
                                    <div class="title"> Threat Campaigns
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="tc_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="threat_campaigns" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>Name</th>
                                                <th style="width:60px; text-align: center;">Enabled</th>
                                                <th style="width:35px; text-align: center;">Edit</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              

                                 <div class="panel">
                                       <div class="title">Individual Signatures  
                                          <div class="btn-group" style="float:right">
                                             <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="signatures_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                          </div>
                                       </div>
                                       <div class="line"></div>
                                       <div class="content">
                                          <table id="signatures" class="table table-striped table-bordered" style="width:100%">
                                             <thead>
                                                <tr>
                                                   <th style="width: 45px; text-align: center;">Enabled</th>
                                                   <th>Signature ID</th>
                                                   <th>Signature Name</th>
                                                   <th>Tag</th>
                                                   <th style="width: 35px; text-align: center;">Edit</th>
                                                </tr>
                                             </thead>
                                          </table>
                                    </div>
                                 </div>

                                 <div class="panel">
                                       <div class="title">Server Technologies
                                          <div class="btn-group" style="float:right">
                                             <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="server_technologies_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                          </div>
                                       </div>
                                       <div class="line"></div>
                                       <div class="content">
                                          <table id="server_technologies" class="table table-striped table-bordered" style="width:100%">
                                             <thead>
                                                <tr>
                                                   <th>Server Technology Name</th>
                                                </tr>
                                             </thead>
                                          </table>
                                    </div>
                                 </div>


                                 <div class="panel <?php if($signature_requirements_display) echo 'display_none';  ?>">
                                    <div class="title"> Signature Requirements </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="signature_requirements" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                             <th>Tag</th>
                                             <th style="text-align: center;">Max Revision Date</th>
                                             <th style="text-align: center;">Min Revision Date</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>


                              </div>
                        

                            </div>

                        </div>
                        <div class="tab-pane fade" id="tabfiletypes" role="tabpanel" aria-labelledby="tabfiletypes-tab">
                           
                           <div class="row">
                              <div class="col-12">
                                 <div class="panel">
                                    <div class="title"> File Types Allowed 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="file_types_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                    	<table id="file_type" class="table table-striped table-bordered" style="width:100%">
                                        	<thead>
                                             <tr>
                                                <th rowspan="2" style="width:100%">File Type</th>
                                                <th rowspan="2" style="width:60px; text-align:center;">Type</th>
                                                <th rowspan="2" style="width:60px; text-align:center;">Allowed </th>
                                                <th colspan="4" style="width:70px; text-align:center;">Enable Check <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Allowed URI Length for each File Type"></i></th>
                                                <th colspan="4" style="width:70px; text-align:center;">Configure Length <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Allowed URI Length for each File Type"></i></th>
                                                <th rowspan="2" style="width:110px; text-align:center;">Responses </th>
                                                <th rowspan="2"  colspan="2" style="width:15px; text-align: center;">Edit</th>
                                             </tr>
                                             <tr>
                                                <th style="width:50px; text-align:center;">URI</th>
                                                <th style="width:50px; text-align:center;">Query </th>
                                                <th style="width:50px; text-align:center;">Post </th>									
                                                <th style="width:50px; text-align:center;">Request </th>
                                                <th style="width:70px; text-align:center;">URI </th>
                                                <th style="width:70px; text-align:center;">Query </th>
                                                <th style="width:70px; text-align:center;">Post </th>
                                                <th style="width:70px; text-align:center;">Request </th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                 
                           </div>

                        </div>
                        <div class="tab-pane fade" id="tabparameters" role="tabpanel" aria-labelledby="tabparameters-tab">

                           <div class="row">
                              <div class="col-12">
                                 <div class="panel">
                                    <div class="title"> Parameters 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="parameters_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>																
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="parameters" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th rowspan=2 style="width: 15px; text-align: center;"></th>
                                             <th rowspan=2>Parameter Name</th>
                                             <th rowspan=2 style="width:55px; text-align:center;">Type</th>
                                             <th rowspan=2 style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="Is the parameter conifgured as Sensitive">Sensitive </th>
                                             <th colspan=3 style="width:70px; text-align:center;">Enable Check</th>
                                             <th colspan=3 style="width:70px; text-align:center;">Overrides</th>
                                             <th rowspan=2 colspan=2 style="text-align: center;">Edit</th>
                                          </tr>
                                          <tr>
                                             <th style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="If Attack Signatures have been enabled">Signatures</th>
                                             <th style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="If checking on Meta-characters has been enabled">MetaChar Value</th>
                                             <th style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="If checking on Meta-characters has been enabled">MetaChar Name</th>
                                             <th style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="How many Attack Signatures have been overriden">Signatures</th>
                                             <th style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="How many Meta-characters have been overriden">MetaChar Value</th>
                                             <th style="width:70px; text-align:center;" data-toggle="tooltip" data-original-title="How many Meta-characters have been overriden">MetaChar Name</th>
                                          </tr>                                          
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                                 
                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title"> Sensitive Parameters 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="sensitive_param_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="sensitive_param" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>Parameter Name</th>
                                                <th style="width:35px; text-align: center;">Edit</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                           </div>

                        </div>
                        <div class="tab-pane fade" id="taburls" role="tabpanel" aria-labelledby="taburls-tab">
                           <div class="row">
                              <div class="col-12">
                                 <div class="panel">
                                    <div class="title"> URLs 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="url_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="urls" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th rowspan=2 style="width:10px;"></th>
                                                <th rowspan=2 style="width:50px; text-align:center;" data-toggle="tooltip" data-original-title="HTTP Protocol used (HTTP/HTTPS)">Proto</th>
                                                <th rowspan=2 style="width:50px; text-align:center;" data-toggle="tooltip" data-original-title="Allowed Methods">Method</th>
                                                <th rowspan=2>URL</th>
                                                <th rowspan=2 style="width:50px; text-align:center;" data-toggle="tooltip" data-original-title="Is the URL allowed?">Allowed</th>
                                                <th colspan=2 style="width:75px; text-align:center;" data-toggle="tooltip" data-original-title="If Attack Signatures have been enabled">Enable Check</th>
                                                <th colspan=2 style="width:90px; text-align:center;" data-toggle="tooltip" data-original-title="How many Attack Signatures have been overriden">Overrides</th>
                                                <th rowspan=2 colspan=2 style="text-align: center;">Edit</th>
                                             </tr>
                                             <tr>
                                                <th style="width:75px; text-align:center;" data-toggle="tooltip" data-original-title="If Attack Signatures have been enabled">Signatures</th>
                                                <th style="width:75px; text-align:center;" data-toggle="tooltip" data-original-title="If Meta-character Check has been enabled">Metachar</th>
                                                <th style="width:90px; text-align:center;" data-toggle="tooltip" data-original-title="How many Attack Signatures have been overriden">Signatures</th>
                                                <th style="width:90px; text-align:center;" data-toggle="tooltip" data-original-title="How many Meta-characters have been overriden">Metachar</th>
                                             </tr>                                             
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>


                              <div class="col-8">
                                 <div class="panel">
                                    <div class="title"> CSRF URLs 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="csrf_url_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="csrf" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>URL</th>
                                                <th style="width:150px; text-align:center;">Enforcement Action</th>
                                                <th style="width:75px; text-align:center;">Method</th>
                                                <th style="width:130px; text-align:center;">Wildcard Order</th>
                                             </tr>                                             
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                            
                           </div>

                        </div>  
                        <div class="tab-pane fade" id="tabcookies" role="tabpanel" aria-labelledby="tabcookies-tab">

                           <div class="row">
                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title"> Cookies
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="cookies_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>                                       
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="cookies" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th style="width: 15px; text-align: center;"></th>
                                                <th> Name </th>
                                                <th style="width:45px; text-align:center;"> Type </th>
                                                <th style="width:90px; text-align: center;">Enforcement <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Whether the cookie is on Enforced or Allowed mode"></i></th>
                                                <th style="width:75px; text-align:center;">Signatures <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="If Attack Signatures have been enabled"></i></th>
                                                <th style="width:90px; text-align:center;"> Overrides <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="How many Attack Signatures have been overriden"></i> </th>
                                                <th colspan=2 style="text-align: center;"> Edit</th>
                                             </tr> 
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title"> Headers
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="headers_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>                                       
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="headers" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th style="width: 15px; text-align: center;"></th>
                                                <th>Name</th>
                                                <th style="width:45px; text-align:center;">Type</th>
                                                <th style="width:75px; text-align:center;" data-toggle="tooltip" data-original-title="If Attack Signatures have been enabled">Signatures</th>
                                                <th style="width:90px; text-align:center;">Overrides</th>
                                                <th colspan=2 style="text-align: center;"> Edit</th>
                                             </tr>
                                          </thead>
                                       </table>    
                                    </div>
                                 </div>
                              </div>

                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title"> Enforcer Settings 
                                          <div class="btn-group" style="float:right">
                                             <button type="button" class="btn btn-sm btn-outline-secondary" id="blocking_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                          </div>                                   
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="enforcer_settings" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                                <tr>
                                                <th colspan=2>Settings</th>
                                                <th>Value</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td rowspan=3 style="width:180px;vertical-align: middle;">Enforce state cookies</td>
                                                <td>httpOnlyAttribute</td>
                                                <td> <b><?php echo $httpOnlyAttribute; ?><b></td>
                                             </tr>
                                             <tr>
                                                <td>secureAttribute</td>
                                                <td> <b><?php echo $json_data["policy"]["enforcer-settings"]["enforcerStateCookies"]["secureAttribute"]; ?><b></td>
                                             </tr>
                                             <tr>
                                                <td>sameSiteAttribute</td>
                                                <td> <b><?php echo $json_data["policy"]["enforcer-settings"]["enforcerStateCookies"]["sameSiteAttribute"]; ?><b></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>                              
                              
                           </div>

                        </div>
                        <div class="tab-pane fade" id="tabmethods" role="tabpanel" aria-labelledby="tabmethods-tab">
                           
                           <div class="row">
                              <div class="col-3">
                                 <div class="panel">
                                    <div class="title"> HTTP Methods 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="methods_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="methods" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>Allowed HTTP Methods</th>
                                                <th style="width:35px; text-align: center;">Edit</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-3">
                                 <div class="panel">
                                    <div class="title"> HTTP Response Codes 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="response_codes_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="response" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th>HTTP Response Codes</th>
                                             <th style="width:35px; text-align: center;">Edit</th>
                                          </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           
                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title"> Response Pages 
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="response_pages_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="response_pages" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th style="width: 15px; text-align: center;"></th>
                                                <th>Response Page Type</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                                              
                           </div>

                           <div class="row">

                              <div class="col-6">
                                 <div class="panel">
                                    <div class="title">Whitelist IPs
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="whitelist_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>                                       
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="whitelist_ips" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th style="width:100px;">IP</th>
                                                <th style="width:120px;">Mask</th>
                                                <th style="width:60px; text-align:center;" data-toggle="tooltip" data-original-title="Never Block this IP address">Block </th>
                                                <th style="width:60px; text-align:center;" data-toggle="tooltip" data-original-title="Never Log for this IP address">Log </th>
                                                <th style="text-align:center;">Description </th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           </div>

                        </div>
                        <div class="tab-pane fade" id="tabbotdefense" role="tabpanel" aria-labelledby="tabbotdefense-tab">
                           
                           <div class="row">

                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title">Bot Defense Classes
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="bot_classes_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>                                       
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="bot_defense" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th>Class Name</th>
                                                <th style="width:90px; text-align:center;">Action</th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>	

                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title"> Bot Defense Browsers
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="bot_browsers_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="bot_defense_browsers" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th rowspan=2>Name</th>
                                             <th rowspan=2>Action</th>
                                             <th colspan=2 style="text-align: center;">Version</th>
                                          </tr>
                                          <tr>
                                             <th style="width:55px; text-align: center;">Min</th>
                                             <th style="width:55px; text-align: center;">Max</th>
                                          </tr>                                          
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>


                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title"> Bot Defense Signatures
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="bot_signatures_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="bot_defense_signatures" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th>Signatures</th>
                                             <th style="width:90px; text-align: center;">Action</th>
                                          </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>



                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title"> Bot Defense Anomalies
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="bot_anomalies_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="bot_defense_anomalies" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th>Anomalies</th>
                                             <th style="width:90px; text-align: center;">Action</th>
                                             <th style="width:90px; text-align: center;">ScoreThreshold</th>
                                          </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>


                           </div>

                        </div>                        
                        <div class="tab-pane fade" id="tabprofiles" role="tabpanel" aria-labelledby="tabprofiles-tab">
                           
                           <div class="row">

                              <div class="col-8">
                                 <div class="panel">
                                    <div class="title">JSON Profiles
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="json_profile_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>                                       
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="json_profiles" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th rowspan="2" style="width: 15px; text-align: center;"></th>
                                                <th rowspan="2">Name </th>
                                                <th rowspan="2">Description </th>
                                                <th colspan="2" style="width:70px; text-align:center;">Enable Check </th>
                                                <th colspan="2" style="width:70px; text-align:center;">Overrides </th>
                                                <th rowspan="2" colspan="2" style="width:35px; text-align: center;">Edit</th>
                                             </tr>
                                             <tr>
                                                <th style="width:70px; text-align:center;">Signatures </th>
                                                <th style="width:70px; text-align:center;">MetaChar </th>
                                                <th style="width:70px; text-align:center;">Signatures </th>
                                                <th style="width:70px; text-align:center;">MetaChar </th>
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title">JSON Validation Files
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="json_file_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>   
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="json_validation_files" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th>FileName</th>
                                             <th style="width:90px; text-align:center;">Content</th>
                                          </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                           </div>

                           <div class="row">

                              <div class="col-8">
                                 <div class="panel">
                                    <div class="title">XML Profiles
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="xml_profile_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>                                          
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="xml_profiles" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                             <tr>
                                                <th style="width: 15px; text-align: center;"></th>
                                                <th> Name </th>
                                                <th> Description</th>
                                                <th style="width:100px; text-align:center;">Signatures</th>
                                                <th style="width:100px; text-align:center;">Sig. Overrides </th>
                                                <th colspan="2" style="width:35px; text-align: center;">Edit</th>                                  
                                             </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-4">
                                 <div class="panel">
                                    <div class="title">Open API Files
                                       <div class="btn-group" style="float:right">
                                          <button type="button" class="btn btn-sm btn-outline-secondary btn-json" id="open_api_json" data-bs-toggle="modal" data-bs-target="#jsonModal">Edit</button>
                                       </div>   
                                    </div>
                                    <div class="line"></div>
                                    <div class="content">
                                       <table id="open_api_files" class="table table-striped table-bordered" style="width:100%">
                                          <thead>
                                          <tr>
                                             <th>Link</th>
                                          </tr>
                                          </thead>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                           </div>


                        </div>
                 
                     </div>
                  </div>
               </div>


            </main>
         </div>
      </div>
      
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
   </body>



<!-- Scrollable modal -->
<!-- Button trigger modal -->

   <!-- Modal -->
   <div class="modal fade" id="jsonModal" tabindex="-1" aria-labelledby="jsonModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="jsonModalLabel">JSON Configuration Settings</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="alert alert-primary d-flex align-items-center" role="alert">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                     <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                  </svg>
                  <div>
                     The JSON configuration on the right includes the NAP Template default values. 
                  </div>
               </div>
               <div class="alert alert-warning hidden" id="change_results">
                     <i class="fa fa-spinner fa-pulse fa-3x del_2" style="float:left; margin-right:10px "></i>
                     <h6 class="del_2"> Please wait.. It can take up to 30 seconds.</h6>  
               </div>               
               <input id="json_variable" type="text" hidden>
               <input id="policy_name" type="text" value="<?php echo $_GET['policy']; ?>" hidden>
               <div class="row">
                  <div class="col-md-6" >
                     <div id="json_title" style="font-size:16px">
               
            
                     </div>                     
                  </div>
                  <div class="col-md-6" >
                     <div id="json_title_original" style="font-size:16px">
                        <i>Inlcuding Template's Default values:</i>
                     </div>                         
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-6" >
                     <textarea disabled id="original_json_text" style="width: 100%; height: 768px; border-color: #084298;border-width: 2px; padding: 5px 5px;" class="disabled textarea">

                     </textarea>
                  </div>               
                  <div class="col-md-6" >
                     <textarea disabled id="json_text" style="width: 100%; height: 768px; border-color: #084298;border-width: 2px; padding: 5px 5px;" class="disabled textarea">

                     </textarea>
                  </div> 
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-dark btn_bottom_form" data-bs-dismiss="modal">Close</button>
               <button type="button" class="btn btn-success btn_bottom_form" id="btn-edit">Edit</button>
               <button type="button" class="btn btn-secondary btn_bottom_form" id="btn-submit" disabled>Save changes</button>
            </div>
         </div>
      </div>
   </div>


   <div class="modal fade" id="generalModal" tabindex="-1" aria-labelledby="generalModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Blocking Settings Edit </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body results" >

                  <form class="row g-3">
                     
                        <div class="col-md-4 blocking_form vars" >
                           <label class="form-label">Enforcement Mode</label>
                           <select id="form_enforcement_mode" name="form_enforcement_mode" class="form-select">
                              <option value="blocking" <?php if ($json_data["policy"]["enforcementMode"]=="blocking") echo "selected"; ?> >Blocking</option>
                              <option value="transparent" <?php if ($json_data["policy"]["enforcementMode"]=="transparent") echo "selected"; ?> >Transparent</option>
                           </select>
                        </div>
         
                        <div class="col-md-4 blocking_form vars" >
                           <label class="form-label">Bot Protection</label>
                           <select id="form_bot_protection" name="form_bot_protection" class="form-select">
                              <option value="enabled" <?php if ($bot_protection_form =="enabled") echo "selected"; ?> >Enabled</option>
                              <option value="disabled" <?php if ($bot_protection_form == "disabled") echo "selected"; ?> >Disabled</option>
                           </select>                                                   
                        </div>
            
                        <div class="col-md-4 blocking_form vars" >
                           <label class="form-label">CSRF Protection</label>
                           <select id="form_csrf_protection" name="form_csrf_protection" class="form-select">
                              <option value="enabled" <?php if ($csrf_protection_form =="enabled") echo "selected"; ?> >Enabled</option>
                              <option value="disabled" <?php if ($csrf_protection_form == "disabled") echo "selected"; ?> >Disabled</option>
                           </select>      
                        </div>
                        
                        <div class="col-md-6 blocking_form vars" >
                           <label class="form-label">Template</label>
                           <input type="text" class="form-control blocking_form" id="form_template" disabled value="<?php echo $json_data["policy"]["template"]["name"]; ?> ">
                        </div>
                        
                        <div class="col-md-6 blocking_form vars" >
                           <label class="form-label">Description</label>
                           <input type="text" class="form-control blocking_form" id="form_description" value="<?php echo $json_data["policy"]["description"]; ?> ">
                        </div>
                        

                        <div class="col-md-4 blocking_form vars" >
                           <label class="form-label">Max Cookie Length</label>
                           <input type="text" class="form-control blocking_form" id="form_cookie_length" value="<?php echo $json_data["policy"]["cookie-settings"]["maximumCookieHeaderLength"]; ?> ">
                        </div>
                        
                        <div class="col-md-4 blocking_form vars" >
                           <label class="form-label">Max Header Length</label>
                           <input type="text" class="form-control blocking_form" id="form_header_length" value="<?php echo $json_data["policy"]["header-settings"]["maximumHttpHeaderLength"]; ?> ">
                        </div>

                        <div class="col-md-4 blocking_form vars" >
                           <label class="form-label">Application Language</label>
                           <input type="text" class="form-control blocking_form" id="form_application_language" value="<?php echo $applicationLanguage; ?> ">
                        </div>
                       
                        <div class="col-md-9 blocking_form vars" >
                           <label class="form-label">XFF Headers</label>
                           <input type="text" class="form-control blocking_form" id="form_xff_headers" >
                        </div>   
                        <div class="col-md-3 blocking_form vars" >
                           <label class="form-label" style="width:100%;">Trust XFF</label>
                           <input class="checkbox_form" type="checkbox" id="form_trust_xff" <?php if ($json_data["policy"]["general"]["trustXff"]) echo "checked"; ?>>
                        </div> 

                        <div class="col-md-4 blocking_form vars form-check" style="text-align:center">
                           <label class="form-label" style="width:100%;">Mask Credit Card</label>
                           <input class="checkbox_form" type="checkbox" id="form_mask_credit_card" <?php if ($json_data["policy"]["maskCreditCardNumbersInRequest"]) echo "checked"; ?>>
                        </div>  
                        <div class="col-md-4 blocking_form vars form-check" style="text-align:center">
                           <label class="form-label" style="width:100%;">Case Insensitive Policy</label>
                           
                           <input class="checkbox_form" type="checkbox" id="form_case_insensitive" <?php if ($json_data["policy"]["bot-defense"]["settings"]["caseSensitiveHttpHeaders"]) echo "checked"; ?>>
                        </div>

                        <div class="col-md-4 blocking_form vars form-check" style="text-align:center">
                           <label class="form-label" style="width:100%;">Bot Case Sensitive Headers</label>
                           <input class="checkbox_form" type="checkbox" id="form_bot_case_sensitiveheaders" <?php if ($json_data["policy"]["bot-defense"]["settings"]["caseSensitiveHttpHeaders"]) echo "checked"; ?>>
                        </div>                        
                                       
                  </form>
                           

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-dark btn_bottom_form" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-secondary btn_bottom_form" id="btn-save" >Save changes</button>
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Blocking Settings Edit </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body results" >

                  <form class="row g-3">
                     
                        <div class="col-md-6 blocking_form vars" hidden>
                           <input type="text" class="form-control row" id="table_row">
                        </div>

                        <div class="col-md-6 blocking_form vars" >
                           <label class="form-label">Description</label>
                           <input type="text" class="form-control blocking_form" id="blocking_form_description" disabled>
                        </div>
            
                        <div class="col-md-2 blocking_form vars form-check" style="text-align:center">
                           <label class="form-label" for="violation_form_alarm" style="width:100%;">Alarm</label>
                           <input class="checkbox_form" type="checkbox" id="blocking_form_alarm">
                        </div>
                        
                        <div class="col-md-2 blocking_form vars form-check" style="text-align:center">
                           <label class="form-label" for="violation_form_block" style="width:100%;">Block</label>
                           <input class="checkbox_form" type="checkbox" id="blocking_form_block">
                        </div>

                  </form>
                           

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-dark btn_bottom_form" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-secondary btn_bottom_form" id="btn-save" >Save changes</button>
            </div>
         </div>
      </div>
   </div>


</html>



<script>
   $(document).ready(function(){
   $("#abc123").click(function(){
      //$("#addModal").show();
      
      var myModal = new bootstrap.Modal(document.getElementById('addModal'))
      myModal.show()

   });
   });
</script>

<script>

   $( "#btn-edit" ).click(function() {

      $("#original_json_text").removeAttr("disabled");
      $("#btn-submit").removeAttr("disabled");
   });
</script>




 <!-- Enable tooltips -->
  
<script>
   var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
   var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
   })
</script>   


<!-- General Table -->

<script>
   $(document).ready(function () {
      $('#general').DataTable(
      {
         "searching": false,
         "info": false,
         "paging":true,
         "ordering":false,
         "order": [],
         "pageLength": 25
      }
   );
   });
      
</script>


<!-- Blocking settings -->
<script>

	<?php echo $blocking_settings; ?>

	$(document).ready(function() {
		var table = $('#blocking_settings').DataTable( {
			"data": blocking_settings,
         "pageLength": 25,
			"createdRow": function( row, data, dataIndex ) {
  
				if ( data['alarm'] == true )
				  $('td', row).eq(1).html("<i class='fa fa-check-square-o fa-2x green'></i>");
				else 
				  $('td', row).eq(1).html("<i class='fa fa-minus-square fa-2x red' ></i>");
				if ( data['block'] == true )
				  $('td', row).eq(2).html("<i class='fa fa-check-square-o fa-2x green'></i>");
				else 
				  $('td', row).eq(2).html("<i class='fa fa-minus-square fa-2x red' ></i>");
				
            $('td', row).eq(3).html("<i class='fa fa-edit'></i>");  

			  },
			  "columns": [
				{ "className":'bold', "data":"description" },
				{ "className":'attacks', "data":"alarm"},
				{ "className":'attacks', "data":"block"},
				{ "className":'edit_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " },
				"order": [[2, 'desc']]
		} );	
         $('#blocking_settings tbody').on( 'click', '.edit_button', function () {

            var tr = $(this).closest('tr');
            var idx = table.row(tr).index();
            //console.log(row)
            var description = table.cell( idx, 0).data();
            var alarm = table.cell( idx, 1).data();
            var block = table.cell( idx, 2).data();

            var myModal = new bootstrap.Modal(document.getElementById('editModal'))
            myModal.show()
            $("#blocking_form_description").val(description);
            $("#table_row").val(idx);
            if (alarm == true)
               $("#blocking_form_alarm").attr("checked", "checked");
            if (block == true)
               $("#blocking_form_block").attr("checked", "checked");     
      });

	} );
</script>	

<!-- Evasion -->
<script>

	<?php echo $evasion; ?>
	
	$(document).ready(function() {
		var table = $('#evasion').DataTable( {
			"data": evasion,
			"searching": false,
			"info": false,
         
			"createdRow": function( row, data, dataIndex ) {
				if ( data['enabled'] == true )
				  $('td', row).eq(1).html("<i class='fa fa-check-square-o fa-2x green'></i>");
				else 
				  $('td', row).eq(1).html("<i class='fa fa-minus-square-o fa-2x red' ></i>");

            $('td', row).eq(2).html("<i class='fa fa-edit'></i>");  

			},
			  "columns": [
				{ "className": 'bold',"data": "description" },
				{ "className": 'attacks', "data": "enabled"},
				{"className": 'edit_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " },
				"order": [[0, 'desc']]
		} );	

	} );
</script>

<!-- Compliance -->
<script>
	<?php echo $compliance; ?>

	$(document).ready(function() {
		var table = $('#compliance').DataTable( {
			"data": compliance,
         "pageLength": 25,         
			"searching": false,
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
				if ( data['enabled'] == true )
				  $('td', row).eq(1).html("<i class='fa fa-check-square-o fa-2x green'></i>");
				else 
				  $('td', row).eq(1).html("<i class='fa fa-minus-square-o fa-2x red' ></i>");
            
           $('td', row).eq(2).html("<i class='fa fa-edit'></i>");                
			  },
			  "columns": [
				{"className": 'bold',"data": "description" },
				{"className": 'attacks', "data": "enabled"},
				{"className": 'edit_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " },
				"order": [[0, 'desc']]
		} );	

	} );
</script>

<!-- Threat campaigns -->
<script>

   <?php echo $threat_campaigns; ?>
   <?php echo $original_threat_campaigns; ?>

	$(document).ready(function() {
		var table = $('#threat_campaigns').DataTable( {
			"data": threat_campaigns,
			"searching": false,
         "order": [[0, 'asc']],
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
				if ( data['enabled'] == true )
				  $('td', row).eq(1).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(1).html("<i class='fa fa-minus-circle fa-2x red' ></i>");

            $('td', row).eq(2).html("<i class='fa fa-trash'></i> ");  
			},			
			"columns": [
				{"className": 'bold',"data": "name", "defaultContent":"N/A" },
				{"className": 'attacks',"data": "enabled" },
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	
      $('#threat_campaigns tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#threat_campaigns_save').removeAttr("disabled");
      });
	} );
</script>

<!-- signature-requirements -->
<script>

	<?php echo $signature_requirements; ?>
	
	$(document).ready(function() {
		var table = $('#signature_requirements').DataTable( {
			"data": signature_requirements,
			"searching": false,
			"paging":false,
			"info": false,
			"columns": [
			{ "className": 'bold',"data": "tag" },
			{ "className": 'attacks', "data": "maxRevisionDatetime", "defaultContent": "None"},
			{ "className": 'attacks', "data": "minRevisionDatetime", "defaultContent": "None"},
			],
			"autoWidth": false,
			"processing": true,
			"language": {"processing": "Waiting.... " },
			"order": [[1, 'desc']]
		} );	

	} );
</script>

<!-- Signatures -->
<script>
	<?php echo $signatures; ?>

	$(document).ready(function() {
		var table = $('#signatures').DataTable( {
			"data": signatures,
			"searching": false,
         "order": [[1, 'desc']],
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
				if ( data['enabled'] == true )
					$('td', row).eq(0).html("<i class='fa fa-check-square-o fa-2x green'></i>");
				else 
					$('td', row).eq(0).html("<i class='fa fa-minus-square fa-2x red' ></i>");
   
            $('td', row).eq(4).html("<i class='fa fa-trash' ></i> ");  
			},			
			"columns": [
				{"className": 'attacks', "data": "enabled","defaultContent": false},
				{"className": 'bold', "data": "signatureId","defaultContent": "N/A"},
				{"className": 'bold', "data": "name","defaultContent": "N/A"},
				{"className": 'bold', "data": "tag","defaultContent": "N/A"},
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );

      $('#signatures tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#signatures_save').removeAttr("disabled");

      });	
	} );
</script>	

<!-- Signature Sets -->
<script>

	function format_signature_sets ( d ) {
		var filter = "N/A";
		var systems = "N/A";
		var signatures = "N/A";
		var table_add = "";
		var line_add = "";
		if ("signatureSet" in d)
		{
			if ("filter" in d.signatureSet)
			{
				var filter = "";
				for(var j in d.signatureSet.filter){
					var sub_key = j;
					var sub_val = d.signatureSet.filter[j];
					if (sub_key == "attackType")
						filter = filter + sub_key+': <b> ' + sub_val.name + '</b><br>';
					else
						filter = filter + sub_key+': <b> ' + sub_val + '</b><br>';
				}				
			}
			if ("signatures" in d.signatureSet)
			{
				var signatures = "";
				for(var j in d.signatureSet.signatures){
					var sub_key = j;
					var sub_val = d.signatureSet.signatures[j];
					signatures = signatures + 'signatureId: <b> ' + sub_val.signatureId + '</b><br>';
				}				
			}
			if ("systems" in d.signatureSet)
			{
				var systems = "";
				for(var j in d.signatureSet.systems){
					var sub_key = j;
					var sub_val = d.signatureSet.systems[j];
					systems = systems + 'name: <b> ' + sub_val.name + '</b><br>';
				}				
			}
		}

		return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+
			'<tr>'+
				'<td style="width:150px; background-color:#eaecf0"><b>Filter:</b></td>'+
				'<td >'+filter+'</td>'+
			'</tr>'+ 
			'<tr>'+
				'<td style="width:150px; background-color:#eaecf0"><b>Systems:</b></td>'+
				'<td >'+systems+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:150px; background-color:#eaecf0"><b>Individual Signatures:</b></td>'+
				'<td >'+signatures+'</td>'+
			'</tr>'+
			table_add +
			'</table>';
	}


	<?php echo $signature_sets; ?>

	$(document).ready(function() {
		var table = $('#signature_sets').DataTable( {
			"data": signature_sets,
         "pageLength": 25,
         "order": [[3, 'desc']],
			"createdRow": function( row, data, dataIndex ) {
				if ( data['alarm'] == true )
				  $('td', row).eq(2).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(2).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ( data['block'] == true )
				  $('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red' ></i>");

            $('td', row).eq(5).html("<i class='fa fa-edit'></i> ");  
            $('td', row).eq(6).html("<i class='fa fa-trash' ></i> ");  

			  },
			  "columns": [
				{"className":'details-control',"orderable":false,"data":null,"defaultContent": ''},		
				{"className":'bold',"data": "name" },
				{"className":'attacks',"data": "alarm"},
				{"className":'attacks',"data": "block"},
				{"className":'attacks',"data": "signatureSet.type", "defaultContent": "default"},
				{"className":'edit_button',"orderable":false ,"data": null},
				{"className":'delete_button',"orderable":false ,"data": null}
				],

				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );
		$('#signature_sets tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_signature_sets(row.data()) ).show();
				tr.addClass('shown');
			}
		} );

      $('#signature_sets tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#signature_sets_save').removeAttr("disabled");
      });	

	} );
</script>

<!-- File Types -->
<script>

	<?php echo $file_types; ?>

	$(document).ready(function() {
		var table = $('#file_type').DataTable( {
			"data": file_types,
         "order": [[0, 'desc']],
			"createdRow": function( row, data, dataIndex ) {
				
            if ( data['allowed'] == true )
				{
               $('td', row).eq(2).html("<i class='fa fa-check-circle fa-2x green'></i>");
               if ( data['checkUrlLength'] == true )
                  $('td', row).eq(3).html("<i class='fa fa-check-square-o fa-2x green'></i>");
               else 
                  $('td', row).eq(3).html("<i class='fa fa-minus-square-o  fa-2x black' ></i>");
               if ( data['checkQueryStringLength'] == true )
                  $('td', row).eq(4).html("<i class='fa fa-check-square-o fa-2x green'></i>");
               else 
                  $('td', row).eq(4).html("<i class='fa fa-minus-square-o  fa-2x black' ></i>");
               if ( data['checkPostDataLength'] == true )
                  $('td', row).eq(5).html("<i class='fa fa-check-square-o fa-2x green'></i>");
               else 
                  $('td', row).eq(5).html("<i class='fa fa-minus-square-o  fa-2x black' ></i>");
               if ( data['checkRequestLength'] == true )
                  $('td', row).eq(6).html("<i class='fa fa-check-square-o fa-2x green'></i>");
               else 
                  $('td', row).eq(6).html("<i class='fa  fa-minus-square-o fa-2x black' ></i>");
               if ( data['responseCheck'] == true )
                  $('td', row).eq(11).html("<i class='fa fa-check-square-o fa-2x green'></i>");
               else 
                  $('td', row).eq(11).html("<i class='fa fa-minus-square-o  fa-2x black' ></i>");	  
               
            }

            else 
				{
               $('td', row).eq(2).html("<i class='fa fa-minus-circle fa-2x red' ></i>");         
            }



              $('td', row).eq(12).html("<i class='fa fa-edit'></i> ");  
              $('td', row).eq(13).html("<i class='fa fa-trash'></i> ");  

			  },
			  "columns": [
				{ "className": 'bold',"data": "name" },
				{ "className": 'attacks', "data": "type", "defaultContent": "explicit"},
				{ "className": 'attacks', "data": "allowed"},
				{ "className": 'attacks',"data": "checkUrlLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "checkQueryStringLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "checkPostDataLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "checkRequestLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "urlLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "queryStringLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "postDataLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "requestLength", "defaultContent": "N/A"},
				{ "className": 'attacks',"data": "responseCheck", "defaultContent": "N/A"},
            {"className": 'edit_button',"data": null, "orderable":false},
            {"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"order": [[0, 'asc']]
		} );	

      $('#file_type tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#file_type_save').removeAttr("disabled");
      });	
	} );
</script>

<!-- Parameters -->
<script>
   function format_parameter ( d ) {

      if ("contentProfile" in d)
         contentProfile = d.contentProfile.contentProfile.name
      
      var signatures = "None";
         if ("signatureOverrides" in d)
         {
            var signatures = "";
            for(var j in d.signatureOverrides){
               var sub_key = j;
               var sub_val = d.signatureOverrides[j];
               if (sub_key == "tag")
                  signatures = signatures + '"name" : <b> "' + sub_val.name + '" </b> - ' + '"Tag" : <b> "' + sub_val.tag + '"</b><br>';
               else
                  signatures = signatures + '"name" : <b> "' + sub_val.name + '" </b> - ' + '"SignatureID" : <b> "' + sub_val.signatureId + '"</b><br>';
            }				
         }
         var valueMetacharOverrides = "None";

         if ("valueMetacharOverrides" in d)
         {
            var valueMetacharOverrides = "";
            for(var j in d.valueMetacharOverrides){
               var sub_key = j;
               var sub_val = d.valueMetacharOverrides[j];
               valueMetacharOverrides = valueMetacharOverrides + '"MetaChar" : <b> "' + sub_val.metachar + '" </b> - ' + '"isAllowed" : <b> "' + sub_val.isAllowed + '"</b><br>';
            }				
         }

         var nameMetacharOverrides = "None";

         if ("nameMetacharOverrides" in d)
         {
            var nameMetacharOverrides = "";
            for(var j in d.nameMetacharOverrides){
               var sub_key = j;
               var sub_val = d.nameMetacharOverrides[j];
               nameMetacharOverrides = nameMetacharOverrides + '"MetaChar" : <b> "' + sub_val.metachar + '" </b> - ' + '"isAllowed" : <b> "' + sub_val.isAllowed + '"</b><br>';
            }				
         }

      skip =  ["checkMetachars", "attackSignaturesCheck", "name", "valueType", "contentProfile", "valueMetacharOverrides", "url", "signatureOverrides", "parameterEnumValues", "nameMetacharOverrides"];
		var table= "";
      for(var i in d){
			var key = i;
			var val = d[i];
         if(val === false)
            val = '<i class="fa fa-minus-square-o fa-2x red" ></i>';
         if(val === true)
            val = '<i class="fa fa-check-square-o fa-2x green"></i>';

         if (!skip.includes(key))
         {
            table = table + '<tr>'+
                        '<td style="width:250px; background-color:#eaecf0"><b>'+key+':</b></td>'+
                        '<td colspan=5>'+val+'</td>'+
                     '</tr>';

         }
      }

         return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+
                     '<tr>'+
                        '<td style="width:250px; background-color:#eaecf0"><b>Signature Overrides:</b></td>'+
                        '<td colspan=5>'+signatures+'</td>'+
                     '</tr>'+
                     '<tr>'+
                        '<td style="width:250px; background-color:#eaecf0"><b>Value MetaChr Overrides:</b></td>'+
                        '<td colspan=5>'+valueMetacharOverrides+'</td>'+
                     '</tr>'+
                     '<tr>'+
                        '<td style="width:250px; background-color:#eaecf0"><b>Name MetaChr Overrides:</b></td>'+
                        '<td colspan=5>'+nameMetacharOverrides+'</td>'+
                     '</tr>'+                     
                     table +
                  '</table>';
      }
	<?php echo $parameters; ?>

	$(document).ready(function() {
		var table = $('#parameters').DataTable( {
			"data": parameters,
			"createdRow": function( row, data, dataIndex ) {
				if ("nameMetacharOverrides" in data)
					$('td', row).eq(9).html(data.nameMetacharOverrides.length);
				else
					$('td', row).eq(9).html("0");
            if ("valueMetacharOverrides" in data)
					$('td', row).eq(8).html(data.valueMetacharOverrides.length);
				else
					$('td', row).eq(8).html("0");

				if ("signatureOverrides" in data)
					$('td', row).eq(7).html(data.signatureOverrides.length);
				else
					$('td', row).eq(7).html("0");			
            if ( data['checkMetachars'] == true )
				  $('td', row).eq(6).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(6).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ( data['attackSignaturesCheck'] == true )
				  $('td', row).eq(4).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(4).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ( data['metacharsOnParameterValueCheck'] == true)
				  $('td', row).eq(5).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(5).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ( data['sensitiveParameter'] == true )
				  $('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red' ></i>");

            $('td', row).eq(10).html("<i class='fa fa-edit'></i> ");  
            $('td', row).eq(11).html("<i class='fa fa-trash'></i> ");  
			  },
        
			  "columns": [
				{"className":'details-control',"orderable":false,"data":null,"defaultContent": ''},
				{ "className": 'bold',"data": "name","defaultContent": '' },
				{ "className": 'attacks',"data": "valueType","defaultContent": ''},
				{ "className": 'attacks',"data": "sensitiveParameter","defaultContent": ''},
				{ "className": 'attacks',"data": "attackSignaturesCheck","defaultContent": ''},
				{ "className": 'attacks',"data": "metacharsOnParameterValueCheck","defaultContent": ''},
				{ "className": 'attacks',"data": "checkMetachars","defaultContent": ''},
				{ "className": 'attacks',"data": null,"defaultContent": 0},
				{ "className": 'attacks',"data": null,"defaultContent": 0},
				{ "className": 'attacks',"data": null,"defaultContent": 0},
            {"className": 'edit_button',"data": null, "orderable":false},
            {"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " },
				"order": [[1, 'asc']]
		} );	

    $('#parameters tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format_parameter(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

    $('#parameters tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#parameters_save').removeAttr("disabled");
      });	

	} );
</script>

<!-- SENSITIVE PARAMS -->
<script>
	<?php echo $sensitive_param; ?>

	$(document).ready(function() {
		var table = $('#sensitive_param').DataTable( {
			"searching": true,
			"info": true,
         "order": [[0, 'desc']],
			"data": sensitive_param,
			"createdRow": function( row, data, dataIndex ) {
            $('td', row).eq(1).html("<i class='fa fa-trash' ></i> "); 

			},         
			"columns": [
				{ "className": 'bold',"data": "name" },
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );
      $('#sensitive_param tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#sensitive_param_save').removeAttr("disabled");
      });	      
	} );
</script>

<!-- Urls -->
<script>

	function format_url ( d ) {
		var contentprofiles = "N/A";
		var clickjackingProtection ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var disallowFileUploadOfExecutables ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var wildcardOrder = "N/A";
		var methodsOverrideOnUrlCheck = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var type = "N/A";
		var mandatoryBody = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var metacharacters = "N/A";
		var signatures = "N/A";

		if ("urlContentProfiles" in d)
		{
			var urlContentProfiles = "";
			for(var i in d.urlContentProfiles){
				var key = i;
				var contentprofile = "N/A";
				var val = d.urlContentProfiles[i];
				if ("ContentProfiles" in val)
					contentprofile = val.ContentProfiles;
				
				urlContentProfiles = urlContentProfiles + 'HeaderName: <b> ' +val.headerName + '</b>, ' + 'HeaderValue: <b> ' + val.headerValue + '</b>, '+ 'HeaderValue: <b> ' +val.headerValue + '</b>, '+ 'HeaderOrder: <b> ' +val.headerOrder + '</b>, '+ 'Type: <b> ' +val.type + '</b>, ContentProfile : <b> ' + contentprofile +', </b> <br>';
			}
		}

		if ("clickjackingProtection" in d)
			if (d.clickjackingProtection == true)
				clickjackingProtection = "<i class='fa fa-check-square-o fa-2x green'></i>";

			
		if ("disallowFileUploadOfExecutables" in d)
			if (d.disallowFileUploadOfExecutables == true)
				disallowFileUploadOfExecutables = "<i class='fa fa-check-square-o fa-2x green'></i>";

		if ("methodsOverrideOnUrlCheck" in d)
			if (d.methodsOverrideOnUrlCheck == true)
				methodsOverrideOnUrlCheck = "<i class='fa fa-check-square-o fa-2x green'></i>";
					
		if ("mandatoryBody" in d)
			if (d.mandatoryBody == true)
				mandatoryBody = "<i class='fa fa-check-square-o fa-2x green'></i>";
					
		
				
		if ("wildcardOrder" in d)
			wildcardOrder = d.wildcardOrder



		if ("signatureOverrides" in d)
		{
			var signatures = "";
			for(var j in d.signatureOverrides){
				var sub_key = j;
				var sub_val = d.signatureOverrides[j];
			// Chack about the tag
         //	if (sub_key == "tag")
			//		signatures = signatures + '"name" : <b> "' + sub_val.name + '" </b> - ' + '"Tag" : <b> "' + sub_val.tag + '"</b><br>';
			//	else
			//		signatures = signatures + '"name" : <b> "' + sub_val.name + '" </b> - ' + '"SignatureID" : <b> "' + sub_val.signatureId + '"</b><br>';
					signatures = signatures + 'SignatureID: <b> ' + sub_val.signatureId + '</b> - ' + 'Enabled: <b> ' + sub_val.enabled + '</b><br>';
			}				
		}

		if ("metacharOverrides" in d)
		{
			var metacharacters = "";
			for(var j in d.metacharOverrides){
				var sub_key = j;
				var sub_val = d.metacharOverrides[j];
				metacharacters = metacharacters + 'MetaChar: <b> ' + sub_val.metachar + '</b> - ' + 'isAllowed:<b> ' + sub_val.isAllowed + '</b><br>';
			}				
		}		


		return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>URL Content Profiles:</b></td>'+
				'<td >'+urlContentProfiles+'</td>'+
			'</tr>'+ 
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>Type:</b></td>'+
				'<td >'+type+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>Clickjacking Protection:</b></td>'+
				'<td >'+clickjackingProtection+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>Disallow FileUpload Of Executables:</b></td>'+
				'<td >'+disallowFileUploadOfExecutables+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>Mandatory Body:</b></td>'+
				'<td >'+mandatoryBody+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>Signature Overrides:</b></td>'+
				'<td >'+signatures+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0;"><b>Metachar Overrides:</b></td>'+
				'<td >'+metacharacters+'</td>'+
			'</tr></table>';
	}

	<?php echo $url; ?>

	$(document).ready(function() {
		var table = $('#urls').DataTable( {
			"data": url,
			"createdRow": function( row, data, dataIndex ) {
				if ("metacharOverrides" in data)
					$('td', row).eq(8).html(data.metacharOverrides.length);
				else
					$('td', row).eq(8).html("0");
				if ("signatureOverrides" in data)
					$('td', row).eq(7).html(data.signatureOverrides.length);
				else
					$('td', row).eq(7).html("0");
				if ( data['attackSignaturesCheck'] == true )
					$('td', row).eq(5).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
					$('td', row).eq(5).html("<i class='fa fa-times fa-2x black' ></i>");
				if ( data['metacharsOnUrlCheck'] == true )
				  $('td', row).eq(6).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(6).html("<i class='fa fa-times fa-2x black' ></i>");
				if ( data['isAllowed'] == true )
				  $('td', row).eq(4).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(4).html("<i class='fa fa fa-times fa-2x black' ></i>");

            $('td', row).eq(9).html("<i class='fa fa-edit'></i> ");  
            $('td', row).eq(10).html("<i class='fa fa-trash' ></i> ");  


			  },
			"columns": [
				{ "className":'details-control',"orderable": false,"data": null,"defaultContent": ''},
				{ "className": 'attacks',"data": "protocol"},
				{ "className": 'attacks',"data": "method"},
				{ "className": 'bold',"data": "name" },
				{ "className": 'attacks',"data": "isAllowed"},
				{ "className": 'attacks',"data": "attackSignaturesCheck"},
				{ "className": 'attacks',"data": "metacharsOnUrlCheck"},
				{ "className": 'attacks',"data": null,"defaultContent": ''},
				{ "className": 'attacks',"data": null,"defaultContent": ''},
				{"className": 'edit_button',"data": null, "orderable":false},
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"order": [[1, 'asc']]
		} );	
		
		$('#urls tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_url(row.data()) ).show();
				tr.addClass('shown');
			}
		} );
      $('#urls tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#urls_save').removeAttr("disabled");
      });    
	} );
</script>

<!--Cookies -->
<script>

	function format_cookie ( d ) {
		var wildcardOrder = "N/A";
		var accessibleOnlyThroughTheHttpProtocol ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var decodeValueAsBase64 ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var insertSameSiteAttribute = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var securedOverHttpsConnection = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var signatures = "None";

		if ("securedOverHttpsConnection" in d)
			if (d.securedOverHttpsConnection == true)
				securedOverHttpsConnection = "<i class='fa fa-check-circle fa-2x green'></i>";

			
		if ("insertSameSiteAttribute" in d)
			if (d.insertSameSiteAttribute == true)
				insertSameSiteAttribute = "<i class='fa fa-check-circle fa-2x green'></i>";

		if ("decodeValueAsBase64" in d)
			if (d.decodeValueAsBase64 == true)
				decodeValueAsBase64 = "<i class='fa fa-check-circle fa-2x green'></i>";
					
		if ("accessibleOnlyThroughTheHttpProtocol" in d)
			if (d.accessibleOnlyThroughTheHttpProtocol == true)
				accessibleOnlyThroughTheHttpProtocol = "<i class='fa fa-check-circle fa-2x green'></i>";
					
						
		if ("wildcardOrder" in d)
			wildcardOrder = d.wildcardOrder



		if ("signatureOverrides" in d)
		{
			var signatures = "";
			for(var j in d.signatureOverrides){
				var sub_key = j;
				var sub_val = d.signatureOverrides[j];
				// I need to check with TAGS if it can work
            //if (sub_key == "tag")
				//	signatures = signatures + '"name" : <b> "' + sub_val.name + '" </b> - ' + '"Tag" : <b> "' + sub_val.tag + '"</b><br>';
				//else
					signatures = signatures + 'SignatureID : <b> ' + sub_val.signatureId + ' </b> - ' + 'Enabled: <b> ' + sub_val.enabled + '</b><br>';
			}				
		}

		return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; "><b>HTTPOnly:</b></td>'+
				'<td >'+accessibleOnlyThroughTheHttpProtocol+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; "><b>DecodeValue As Base64:</b></td>'+
				'<td >'+decodeValueAsBase64+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; "><b>Insert SameSite:</b></td>'+
				'<td >'+insertSameSiteAttribute+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; "><b>Secured over HTTPS:</b></td>'+
				'<td >'+securedOverHttpsConnection+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; "><b>Signature Overrides:</b></td>'+
				'<td >'+signatures+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; "><b>wildcardOrder:</b></td>'+
				'<td >'+wildcardOrder+'</td>'+
			'</tr></table>';

		}


	<?php echo $cookies; ?>
 
	$(document).ready(function() {
		var table = $('#cookies').DataTable( {
		"data": cookies,
      "order": [[1, 'desc']],
		"createdRow": function( row, data, dataIndex ) {
			if ("signatureOverrides" in data)
					$('td', row).eq(5).html(data.signatureOverrides.length);
				else
					$('td', row).eq(5).html("0");
			if ( data['attackSignaturesCheck'] == true )
			  $('td', row).eq(4).html("<i class='fa fa-check-circle fa-2x green'></i>");
			else 
				$('td', row).eq(4).html("<i class='fa fa-times fa-2x' ></i>");

         $('td', row).eq(6).html("<i class='fa fa-edit'></i>");  
         $('td', row).eq(7).html("<i class='fa fa-trash' ></i> ");  
			  
		  },
		  "columns": [
            { "className":'details-control',"orderable":false,"data":null,"defaultContent": ''},
            { "className": 'bold',"data": "name" },
            { "className": 'attacks',"data": "type", "defaultContent": "explicit"},
            { "className": 'attacks',"data": "enforcementType", "defaultContent": "allow"},
            { "className": 'attacks',"data": "attackSignaturesCheck", "defaultContent": true},
            { "className": 'attacks',"data": "num_of_sign_overides", "defaultContent": 0},
				{ "className": 'edit_button',"data": null, "orderable":false},
				{ "className": 'delete_button',"data": null, "orderable":false}
			],
			"autoWidth": false,
			"processing": true,
			"language": {"processing": "Waiting.... " },
			"order": [[1, 'asc']]
		} );	

		$('#cookies tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_cookie(row.data()) ).show();
				tr.addClass('shown');
			}
		} );
      $('#cookies tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#cookies_save').removeAttr("disabled");
      });     

	} );
</script>

<!-- HEADERS -->
<script>

	function format_header ( d ) {
		var wildcardOrder = "N/A";
		var urlNormalization ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var percentDecoding ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var normalizationViolations = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var htmlNormalization = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var decodeValueAsBase64 = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var mandatory = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var signatures = "N/A";
		

		if ("urlNormalization" in d)
			if (d.urlNormalization == true)
				urlNormalization = "<i class='fa fa-check-square-o fa-2x green'></i>";

			
		if ("percentDecoding" in d)
			if (d.percentDecoding == true)
				percentDecoding = "<i class='fa fa-check-square-o fa-2x green'></i>";

		if ("normalizationViolations" in d)
			if (d.normalizationViolations == true)
				normalizationViolations = "<i class='fa fa-check-square-o fa-2x green'></i>";
					
		if ("htmlNormalization" in d)
			if (d.htmlNormalization == true)
				htmlNormalization = "<i class='fa fa-check-square-o fa-2x green'></i>";

		if ("mandatory" in d)
			if (d.htmlNormalization == true)
				htmlNormalization = "<i class='fa fa-check-square-o fa-2x green'></i>";				
						
		if ("wildcardOrder" in d)
			wildcardOrder = d.wildcardOrder



		if ("signatureOverrides" in d)
		{
			var signatures = "";
			for(var j in d.signatureOverrides){
				var sub_key = j;
				var sub_val = d.signatureOverrides[j];
			   
            // I need to check with TAGS if it can work
            //if (sub_key == "tag")
				//	signatures = signatures + '"name" : <b> "' + sub_val.name + '" </b> - ' + '"Tag" : <b> "' + sub_val.tag + '"</b><br>';
				//else
            signatures = signatures + 'SignatureID : <b> ' + sub_val.signatureId + ' </b> - ' + 'Enabled: <b> ' + sub_val.enabled + '</b><br>';
				}				
		}

		return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+
		'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>Signature Overrides:</b></td>'+
				'<td >'+signatures+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>mandatory:</b></td>'+
				'<td >'+mandatory+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>htmlNormalization:</b></td>'+
				'<td >'+htmlNormalization+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>normalizationViolations:</b></td>'+
				'<td >'+normalizationViolations+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>percentDecoding:</b></td>'+
				'<td >'+percentDecoding+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>urlNormalization:</b></td>'+
				'<td >'+urlNormalization+'</td>'+
			'</tr>'+			
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0"><b>wildcardOrder:</b></td>'+
				'<td >'+wildcardOrder+'</td>'+
			'</tr></table>';

		}	
 

	<?php echo $headers; ?>
 
	$(document).ready(function() {
		var table = $('#headers').DataTable( {
		"data": headers,
      "order": [[2, 'desc']],
		"createdRow": function( row, data, dataIndex ) {
			if ("signatureOverrides" in data)
					$('td', row).eq(4).html(data.signatureOverrides.length);
				else
					$('td', row).eq(4).html("0");         
			if ( data['checkSignatures'] == true )
			  $('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
			else 
			  $('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red' ></i>");

         $('td', row).eq(5).html("<i class='fa fa-edit'></i>");  
         $('td', row).eq(6).html("<i class='fa fa-trash'></i> ");  
	  
		  },
		  "columns": [
		    { "className": 'details-control', "orderable": false, "data": null, "defaultContent": ''},
			{ "className": 'bold',"data": "name", "defaultContent": '' },
			{ "className": 'attacks',"data": "type", "defaultContent": ''},
			{ "className": 'attacks',"data": "checkSignatures", "defaultContent": ''},
			{ "className": 'attacks',"data": "num_of_sign_overides", "defaultContent": 0},
			{"className": 'edit_button',"data": null, "orderable":false},
			{"className": 'delete_button',"data": null, "orderable":false}
			],
			"autoWidth": false,
			"processing": true,
			"language": {"processing": "Waiting.... " },
			"order": [[1, 'asc']]
		} );	

		$('#headers tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_header(row.data()) ).show();
				tr.addClass('shown');
			}
		} );
      $('#headers tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#headers_save').removeAttr("disabled");
      });   
	} );
</script>

<!-- JSON Profiles -->
<script>

	function format_json_profiles ( d ) {
		var handleJsonValuesAsParameters ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var hasValidationFiles = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var validationFiles = "N/A";
		var maximumArrayLength ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var maximumStructureDepth ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var maximumTotalLengthOfJSONData ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var maximumValueLength ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var tolerateJSONParsingWarnings ="<i class='fa fa-minus-square-o fa-2x red' ></i>";


		if ("handleJsonValuesAsParameters" in d)
			if (d.handleJsonValuesAsParameters == true)
				handleJsonValuesAsParameters = "<i class='fa fa-check-square-o fa-2x green'></i>";
		if ("hasValidationFiles" in d)
			if (d.hasValidationFiles == true)
				hasValidationFiles = "<i class='fa fa-check-square-o fa-2x green'></i>";
      if ("tolerateJSONParsingWarnings" in d.defenseAttributes)
			if (d.tolerateJSONParsingWarnings == true)
            tolerateJSONParsingWarnings = "<i class='fa fa-check-square-o fa-2x green'></i>";	

		if (d.validationFiles.length >11110)
		{

		}	

	
		return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+

         '<tr>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Maximum Array Length:</b></td>'+
				'<td >'+d.defenseAttributes.maximumArrayLength+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Tolerate JSON Parsing Warnings:</b></td>'+
				'<td >'+tolerateJSONParsingWarnings+'</td>'+            
			'</tr>'+ 
			'<tr>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Maximum Structure Depth:</b></td>'+
				'<td >'+d.defenseAttributes.maximumStructureDepth+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Handle JsonValues As Parameters:</b></td>'+
				'<td >'+handleJsonValuesAsParameters+'</td>'+            
			'</tr>'+ 
         '<tr>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Maximum Total Length Of JSON Data:</b></td>'+
				'<td >'+d.defenseAttributes.maximumTotalLengthOfJSONData+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Has Validation Files:</b></td>'+
				'<td >'+hasValidationFiles+'</td>'+            
			'</tr>'+ 
         '<tr>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold"><b>Maximum Value Length:</b></td>'+
				'<td >'+d.defenseAttributes.maximumValueLength+'</td>'+
			'</tr>'+ 
			'</table>';
	}


	<?php echo $json_profiles; ?>

	$(document).ready(function() {
		var table = $('#json_profiles').DataTable( {
			"data": json_profiles,
         "order": [[1, 'desc']],
			"createdRow": function( row, data, dataIndex ) {
				if ( data['attackSignaturesCheck'] == true )
					$('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				 	$('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red' ></i>");

				if ( data['metacharElementCheck'] == true )
					$('td', row).eq(4).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
					$('td', row).eq(4).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ("signatureOverrides" in data)
					$('td', row).eq(5).html(data.signatureOverrides.length);
				else
					$('td', row).eq(5).html("0");
				if ("metacharOverrides" in data)
					$('td', row).eq(6).html(data.metacharOverrides.length);
				else
					$('td', row).eq(6).html("0");
            $('td', row).eq(7).html("<i class='fa fa-edit'></i> ");  
            $('td', row).eq(8).html("<i class='fa fa-trash'></i> ");  
			  },
			  "columns": [
				{"className":'details-control',"orderable":false,"data":null,"defaultContent": ''},		
				{"className":'bold',"data": "name"},
				{"className":'bold',"data": "description"},
				{"className":'attacks',"data": "attackSignaturesCheck", "defaultContent": true },
				{"className":'attacks',"data": "metacharElementCheck", "defaultContent": true },
				{"className":'attacks',"data": "num_of_sig_overrides", "defaultContent": 0 },
				{"className":'attacks',"data": "num_of_meta_overrides", "defaultContent": 0 },
				{"className": 'edit_button',"data": null, "orderable":false},
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );
		$('#json_profiles tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_json_profiles(row.data()) ).show();
				tr.addClass('shown');
			}
		} );
      $('#json_profiles tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#json_profiles_save').removeAttr("disabled");
      });   
	} );
</script>

<!--JSON validation files -->
<script>

	<?php echo $json_validation_files; ?>

	$(document).ready(function() {
		var table = $('#json_validation_files').DataTable( {
			"data": json_validation_files,	
			"columns": [
				{"className": 'bold',"data": "fileName" },
				{"className": 'attacks',"data": "allowed", "defaultContent": "<a href='#'><i class='fa fa-search'></i></a>"},
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>

<!-- XML Profiles -->
<script>

	function format_xml_profiles ( d ) {
		var allowCDATA ="<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var allowDTDs = "<i class='fa fa-minus-square-o fa-2x red' ></i>";
		var allowExternalReferences ="<i class='fa fa-minus-square-o  fa-2x red' ></i>";
      var allowProcessingInstructions ="<i class='fa fa-minus-square-o  fa-2x red' ></i>";
		var tolerateCloseTagShorthand = "<i class='fa fa-minus-square-o  fa-2x red' ></i>";
		var tolerateLeadingWhiteSpace = "<i class='fa fa-minus-square-o  fa-2x red' ></i>";
		var tolerateNumericNames = "<i class='fa fa-minus-square-o  fa-2x red' ></i>";

      if ("allowCDATA" in d.defenseAttributes)
			if (d.defenseAttributes.allowCDATA == true)
            allowCDATA = "<i class='fa fa-check-square-o fa-2x green'></i>";
      if ("allowDTDs" in d.defenseAttributes)
			if (d.defenseAttributes.allowDTDs == true)
            allowDTDs = "<i class='fa fa-check-square-o fa-2x green'></i>";
      if ("allowExternalReferences" in d.defenseAttributes)
			if (d.defenseAttributes.allowExternalReferences == true)
            allowExternalReferences = "<i class='fa fa-check-square-o fa-2x green'></i>";
		if ("allowProcessingInstructions" in d.defenseAttributes)
			if (d.defenseAttributes.allowProcessingInstructions == true)
            allowProcessingInstructions = "<i class='fa fa-check-square-o fa-2x green'></i>";
      if ("tolerateCloseTagShorthand" in d.defenseAttributes)
			if (d.defenseAttributes.tolerateCloseTagShorthand == true)
            tolerateCloseTagShorthand = "<i class='fa fa-check-square-o fa-2x green'></i>";
      if ("tolerateLeadingWhiteSpace" in d.defenseAttributes)
			if (d.defenseAttributes.tolerateLeadingWhiteSpace == true)
            tolerateLeadingWhiteSpace = "<i class='fa fa-check-square-o fa-2x green'></i>";
      if ("tolerateNumericNames" in d.defenseAttributes)
			if (d.defenseAttributes.tolerateNumericNames == true)
            tolerateNumericNames = "<i class='fa fa-check-square-o fa-2x green'></i>";
	
		return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+

			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Attribute Value Length:</td>'+
				'<td >'+d.defenseAttributes.maximumAttributeValueLength+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold">Allow CDATA:</td>'+
				'<td >'+allowCDATA+'</td>'+
         '</tr>'+
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Attributes Per Element:</td>'+
				'<td >'+d.defenseAttributes.maximumAttributesPerElement+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold">Allow DTDs:</td>'+
				'<td >'+allowDTDs+'</td>'+            
			'</tr>'+         
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Children Per Element:</td>'+
				'<td >'+d.defenseAttributes.maximumChildrenPerElement+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold">Allow External References:</td>'+
				'<td >'+allowExternalReferences+'</td>'+
			'</tr>'+
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Document Depth:</td>'+
				'<td >'+d.defenseAttributes.maximumDocumentDepth+'</td>'+
				'<td style="width:250px; background-color:#eaecf0; font-weight:bold">Allow Processing Instructions:</td>'+
				'<td >'+allowProcessingInstructions+'</td>'+            
			'</tr>'+  
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Document Size:</td>'+
				'<td >'+d.defenseAttributes.maximumDocumentSize+'</td>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Tolerate Close Tag Shorthand:</td>'+
				'<td >'+tolerateCloseTagShorthand+'</td>'+            
			'</tr>'+
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Elements:</td>'+
				'<td >'+d.defenseAttributes.maximumElements+'</td>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Tolerate Leading WhiteSpace:</td>'+
				'<td >'+tolerateLeadingWhiteSpace+'</td>'+            
			'</tr>'+  
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum NS Declarations:</td>'+
				'<td >'+d.defenseAttributes.maximumNSDeclarations+'</td>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Tolerate Numeric Names:</td>'+
				'<td >'+tolerateNumericNames+'</td>'+            
			'</tr>'+
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Name Length:</td>'+
				'<td >'+d.defenseAttributes.maximumNameLength+'</td>'+
			'</tr>'+
			'<tr>'+
            '<td style="width:250px; background-color:#eaecf0; font-weight:bold">Maximum Namespace Length:</td>'+
				'<td >'+d.defenseAttributes.maximumNamespaceLength+'</td>'+
			'</tr>'+
			'</table>';
	}


	<?php echo $xml_profiles; ?>

	$(document).ready(function() {
		var table = $('#xml_profiles').DataTable( {
			"data": xml_profiles,
         "order": [[1, 'desc']],
			"createdRow": function( row, data, dataIndex ) {
				if ( data['attackSignaturesCheck'] == true )
					$('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				 	$('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ("signatureOverrides" in data)
					$('td', row).eq(4).html(data.signatureOverrides.length);
				else
					$('td', row).eq(4).html("0");

            $('td', row).eq(5).html("<i class='fa fa-edit'>"); 
            $('td', row).eq(6).html("<i class='fa fa-trash' ></i> "); 
         },
			  "columns": [
				{"className":'details-control',"orderable":false,"data":null,"defaultContent": ''},		
				{"className":'bold',"data": "name"},
				{"className":'bold',"data": "description", "defaultContent": '-' },
				{"className":'attacks',"data": "attackSignaturesCheck", "defaultContent": true },
				{"className":'attacks',"data": "num_of_sig_overrides", "defaultContent": 0 },
				{"className": 'edit_button',"data": null, "orderable":false},
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );
		$('#xml_profiles tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_xml_profiles(row.data()) ).show();
				tr.addClass('shown');
			}
		} );
      $('#xml_profiles tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#xml_profiles_save').removeAttr("disabled");
      });   
	} );
</script>


<!-- METHODS-->
<script>
	<?php echo $methods; ?>
	$(document).ready(function() {
		var table = $('#methods').DataTable( {
			"data": methods,
			"searching": false,
         "order": [[0, 'desc']],
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
            $('td', row).eq(1).html("<i class='fa fa-trash' ></i> ");  
			},	  
			"columns": [
				{ "className": 'bold',"data": "name" },
				{"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	
      $('#methods tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#methods_save').removeAttr("disabled");
      });       
	} );
</script>


<!-- Allowed Responses -->
<script>
	<?php echo $allowed_responses; ?>

	$(document).ready(function() {
		var table = $('#response').DataTable( {
			"data": allowed_responses,
			"searching": false,
         "order": [[0, 'desc']],
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
            $('td', row).eq(1).html("<i class='fa fa-trash' ></i> ");  
			},	         
			"columns": [
				{"className": 'bold', "data": "name"},
            {"className": 'delete_button',"data": null, "orderable":false}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );
      $('#response tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
         $('#response_save').removeAttr("disabled");
      });          
	} );
</script>	

<!-- CSRF -->
<script>

	<?php echo $csrf; ?>

	$(document).ready(function() {
		var table = $('#csrf').DataTable( {
			"data": csrf,
			"searching": false,
			"info": false,
			"columns": [
				{ "className":'bold',"data": "url" },
				{ "className":'attacks',"data": "method", "defaultContent": "N/A" },
				{ "className":'attacks',"data": "enforcementAction", "defaultContent": "N/A"},
				{ "className":'bold',"attacks": "wildcardOrder", "defaultContent": "N/A" }
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>


<!-- RESPONSE PAGES -->
<script>

   function format_response_pages ( d ) {

      skip =  ["responsePageType"];
      var table= "";
      for(var i in d){
         var key = i;
         var val = d[i];
         if(val === false)
            val = '<i class="fa fa-minus-square-o fa-2x red" ></i>';
         if(val === true)
            val = '<i class="fa fa-check-square-o fa-2x green"></i>';

         if (!skip.includes(key))
         {
            table = table + '<tr>'+
                        '<td style="width:250px; background-color:#eaecf0"><b>'+key+':</b></td>'+
                        '<td colspan=5>'+val+'</td>'+
                     '</tr>';

         }
      }

         return '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered subtable">'+
                     table +
                  '</table>';
      }
	<?php echo $response_pages; ?>

	$(document).ready(function() {
		var table = $('#response_pages').DataTable( {
			"data": response_pages,
         "order": [[1, 'desc']],
			"createdRow": function( row, data, dataIndex ) {
				if ( data['attackSignaturesCheck'] == true )
					$('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				 	$('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red' ></i>");
				if ("signatureOverrides" in data)
					$('td', row).eq(4).html(data.signatureOverrides.length);
				else
					$('td', row).eq(4).html("0");

            $('td', row).eq(5).html("<i class='fa fa-edit'>"); 
            $('td', row).eq(6).html("<i class='fa fa-trash' ></i> "); 
         },
			  "columns": [
				{"className":'details-control',"orderable":false,"data":null,"defaultContent": ''},		
				{"className":'bold',"data": "responsePageType"}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );
		$('#response_pages tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format_response_pages(row.data()) ).show();
				tr.addClass('shown');
			}
		} );
      $('#response_pages tbody').on( 'click', 'td.delete_button', function () {
         //var idx = table.row(this).index();
         //var data = table.cell( idx, 1).data();
         table.row(this).remove().draw( false );
      });   
	} );

</script>


<!-- Bot Defense -->
<script>

	<?php echo $bot_defense; ?>

	$(document).ready(function() {
		var table = $('#bot_defense').DataTable( {
			"data": bot_defense,
			"searching": false,
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
  
			if ( data['action'] == "block" )
				$('td', row).eq(1).html("<span class='red'><b>Block</span>");
			if ( data['action'] == "alarm" ) 
				$('td', row).eq(1).html("<span class='orange'><b>Alarm</b></span>");
			if ( data['action'] == "detect" ) 
				$('td', row).eq(1).html("<span class='green'><b>Detect Only</b></span>");
			if ( data['action'] == "ignore" ) 
				$('td', row).eq(1).html("<span class='blue'><b>Ignore</b></span>");
			},			
			"columns": [
				{ "className": 'bold',"data": "name" },
				{ "className": 'bold',"data": "action" }
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>

<!-- Whitelist IPs -->
<script>

	<?php echo $whitelist_ips; ?>

	$(document).ready(function() {
		var table = $('#whitelist_ips').DataTable( {
			"data": whitelist_ips,
			//"searching": false,
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
				if ( data['blockRequests'] == "always" )
				  $('td', row).eq(2).html("<span class='red'>Always</span>");
				if ( data['neverLogRequests'] == true )
					$('td', row).eq(3).html("<i class='fa fa-check-circle fa-2x green'></i>");
				else 
				  $('td', row).eq(3).html("<i class='fa fa-minus-circle fa-2x red ' ></i>");	
				if ( data['description'] == "" )
					$('td', row).eq(4).html("-");

			},
			"columns": [
				{ "className": 'bold',"data": "ipAddress" },
				{ "className": 'bold',"data": "ipMask" },
				{ "className": 'attacks',"data": "blockRequests" },
				{ "className": 'attacks',"data": "neverLogRequests", "defaultContent":false},
				{ "className": 'attacks',"data": "description", "defaultContent":"None" }
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>

<!-- ServerTechnologies -->
<script>

	<?php echo $server_technologies; ?>

	$(document).ready(function() {
		var table = $('#server_technologies').DataTable( {
			"data": server_technologies,
			//"searching": false,
			"info": false,
			"columns": [
				{ "className": 'bold',"data": "serverTechnologyName" }
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>

<!-- Bot Defense Browser-->
<script>

	<?php echo $bot_defense_browsers; ?>

	$(document).ready(function() {
		var table = $('#bot_defense_browsers').DataTable( {
			"data": bot_defense_browsers,
			"searching": false,
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
  
			if ( data['action'] == "block" )
				$('td', row).eq(1).html("<span class='red'><b>Block</span>");
			if ( data['action'] == "alarm" ) 
				$('td', row).eq(1).html("<span class='orange'><b>Alarm</b></span>");
			if ( data['action'] == "detect" ) 
				$('td', row).eq(1).html("<span class='green'><b>Detect Only</b></span>");
			if ( data['action'] == "ignore" ) 
				$('td', row).eq(1).html("<span class='blue'><b>Ignore</b></span>");
			},			
			"columns": [
				{ "className": 'bold',"data": "name" },
				{ "className": 'attacks',"data": "action" },
				{ "className": 'attacks',"data": "minVersion", "defaultContent":"-"},
				{ "className": 'attacks',"data": "maxVersion", "defaultContent":"-"}
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>

<!-- Bot Defense Signatures-->
<script>

	<?php echo $bot_defense_signatures; ?>

	$(document).ready(function() {
		var table = $('#bot_defense_signatures').DataTable( {
			"data": bot_defense_signatures,
			"searching": false,
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
  
			if ( data['action'] == "block" )
				$('td', row).eq(1).html("<span class='red'><b>Block</span>");
			if ( data['action'] == "alarm" ) 
				$('td', row).eq(1).html("<span class='orange'><b>Alarm</b></span>");
			if ( data['action'] == "detect" ) 
				$('td', row).eq(1).html("<span class='green'><b>Detect Only</b></span>");
			if ( data['action'] == "ignore" ) 
				$('td', row).eq(1).html("<span class='blue'><b>Ignore</b></span>");
			},			
			"columns": [
				{ "className": 'bold',"data": "name" },
				{ "className": 'attacks',"data": "action" }
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>

<!-- Bot Defense Anomalies-->
<script>

	<?php echo $bot_defense_anomalies; ?>

	$(document).ready(function() {
		var table = $('#bot_defense_anomalies').DataTable( {
			"data": bot_defense_anomalies,
			"searching": false,
			"info": false,
			"createdRow": function( row, data, dataIndex ) {
  
			if ( data['action'] == "block" )
				$('td', row).eq(1).html("<span class='red'><b>Block</span>");
			if ( data['action'] == "alarm" ) 
				$('td', row).eq(1).html("<span class='orange'><b>Alarm</b></span>");
			if ( data['action'] == "detect" ) 
				$('td', row).eq(1).html("<span class='green'><b>Detect Only</b></span>");
			if ( data['action'] == "ignore" ) 
				$('td', row).eq(1).html("<span class='blue'><b>Ignore</b></span>");
			},			
			"columns": [
				{ "className": 'bold',"data": "name" },
				{ "className": 'attacks',"data": "action" },
				{ "className": 'attacks',"data": "scoreThreshold", "defaultContent":"-" }
				],
				"autoWidth": false,
				"processing": true,
				"language": {"processing": "Waiting.... " }
		} );	

	} );
</script>


<!-- -------------  Modals/Offcanvas   ------------------- -->

<script>
  
   <?php echo $signature_sets_original; ?>
	<?php echo $temp_allowedResponseCodes; ?>
	<?php echo $dataguard_raw; ?>

   $( ".btn-json" ).click(function() {
     
      $("#original_json_text").val("");
      $("#json_text").val("");
      $("#original_json_text").attr("disabled","disabled");
      $("#btn-submit").attr("disabled","disabled");
      var str_original = "[]";
      var entity = $(this).attr('id');
      if (entity == "file_types_json")
      {
         var str = JSON.stringify(file_types, null, 3);
         var title = "FileTypes";         
      } 
      if (entity == "evasion_json")
      {
         var str = JSON.stringify(evasion, null, 3);
         var title = "Evasion-Techniques";         
      }      
      if (entity == "compliance_json")
      {
         var str = JSON.stringify(compliance, null, 3);
         var title = "HTTP-Compliance";         
      }
      if (entity == "json_profile_json")
      {
         var str = JSON.stringify(json_profiles, null, 3);
         var title = "JSON-Profiles";         
      }
      if (entity == "xml_profile_json")
      {
         var str = JSON.stringify(xml_profiles, null, 3);
         var title = "XML-Profiles";
      }
      if (entity == "url_json")
      {
         var str = JSON.stringify(url, null, 3);
         var title = "URLs";
      }

      if (entity == "tc_json")
      {
         var str = JSON.stringify(original_threat_campaigns, null, 3);
         var title = "Threat-Campaigns";
      }

      if (entity == "signature_sets_json")
      {
         var str = JSON.stringify(signature_sets, null, 3);
         var title = "Signature-Sets";
         var str_original = JSON.stringify(signature_sets_original, null, 3);
      }
      
      if (entity == "signatures_json")
      {
         var str = JSON.stringify(signatures, null, 3);
         var title = "Signatures";
      }     

      if (entity == "response_codes_json")
      {
         var str = JSON.stringify(temp_allowedResponseCodes, null, 3);
         var title = "Response-Codes";
      }   
      if (entity == "methods_json")
      {
         var str = JSON.stringify(methods, null, 3);
         var title = "Methods";
      }   
      if (entity == "parameters_json")
      {
         var str = JSON.stringify(parameters, null, 3);
         var title = "Parameters";
      }   
      if (entity == "sensitive_param_json")
      {
         var str = JSON.stringify(sensitive_param, null, 3);
         var title = "Sensitive Parameters";
      }  
      if (entity == "cookies_json")
      {
         var str = JSON.stringify(cookies, null, 3);
         var title = "Cookies";
      }
      if (entity == "headers_json")
      {
         var str = JSON.stringify(headers, null, 3);
         var title = "Headers";
      }  
      if (entity == "blocking_json")
      {
         var str = JSON.stringify(blocking_settings, null, 3);
         var title = "Violations";
      }
      if (entity == "whitelist_json")
      {
         var str = JSON.stringify(whitelist_ips, null, 3);
         var title = "Whitelist-IPs";
      }
      if (entity == "response_pages_json")
      {
         var str = JSON.stringify(response_pages, null, 3);
         var title = "Response-Pages";
      }
      if (entity == "bot_classes_json")
      {
         var str = JSON.stringify(bot_defense, null, 3);
         var title = "Bot-Defense-Classes";
      }
      if (entity == "bot_browsers_json")
      {
         var str = JSON.stringify(bot_defense_browsers, null, 3);
         var title = "Bot-Defense-Browsers";
      } 
      if (entity == "bot_signatures_json")
      {
         var str = JSON.stringify(bot_defense_signatures, null, 3);
         var title = "Bot-Defense-Signatures";
      } 
      if (entity == "bot_anomalies_json")
      {
         var str = JSON.stringify(bot_defense_anomalies, null, 3);
         var title = "Bot-Defense-Anomalies";
      }             
      if (entity == "json_file_json")
      {
         var str = JSON.stringify(json_validation_files, null, 3);
         var title = "JSON-Validation-Files";
      }           
      if (entity == "server_technologies_json")
      {
         var str = JSON.stringify(server_technologies, null, 3);
         var title = "Server-Technologies";
      }
      if (entity == "dataguard_json")
      {
         var str = JSON.stringify(dataguard, null, 3);
         var title = "Dataguard";
      }         

      $("#json_variable").val(title);
      $("#json_title").html("The JSON configuration for <b><u>" + title + "</b></u> is:");
      $("#json_text").val(str);
      $("#original_json_text").val(str_original);      
   
   });
</script>



<!-- Update config -->

<script>
   $("#btn-submit").click(function() {

      var payload = btoa($("#original_json_text").val())
      var type = $("#json_variable").val()
      var policy = $("#policy_name").val()

      $("#change_results").show();
//      append(" <h6> Started parsing NAP policy<span style='color:blue'><b>: " + policies[i].name + " </b></span></h6>");
      $.ajax({
         method: "POST",
         url: "save-config.php",
         data: {
            type:type,
            policy: policy,
            config: payload
         }
      })
         .done(function(msg) {
            if(msg.completed_successfully)
            {
               $("#change_results").html(" <h5> Parsing completed <span style='color:green'>successfully</span>.</h5> ");
               if(msg.warnings.length>0)
               {
                  $("#change_results").append("<h7 style='color:red'> <b>Warnings:</b> <span style='color:red'>"+JSON.stringify(msg.warnings, null, 2)+"</span>. </h7><br>");
                  $("#change_results").append("<h7 style='color:green'>The window will reload in 2 seconds</h7><br>");
                  
                  setTimeout(function() {
                     location.reload();
                  }, 2000); 
               }
               else
               {
                  $("#change_results").append("<h7 style='color:green'> <b>No Warnings</b></h7><br>");
                  $("#change_results").append("<h7 style='color:green'>The window will reload in 2 seconds</h7><br>");
                  
                  setTimeout(function() {
                     location.reload();
                  }, 2000); 
               }               
            }
            else
            {
               if("error_message" in msg )
               {
                  $("#change_results").append("<h6> Parsing error:<span style='color:red'> "+msg.error_message+"</span>. </h6>");
               }
            }           
         })
         .fail(function(jqXHR, textStatus, Status) {
            
         });
      });
</script>




<script>
   $("#sync_button").click(function() {

      var policy = $("#policy_name").val()

      $("#sync_button").html('Syncing... <i class="fa fa-spinner fa-pulse"></i> ');

      $.ajax({
         method: "POST",
         url: "sync-policy.php",
         data: {
            policy: policy
         }
      })
         .done(function(msg) {
            location.reload();   
         })
         .fail(function(jqXHR, textStatus, Status) {
            location.reload();   
         });
      });
</script>



