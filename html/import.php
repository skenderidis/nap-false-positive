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


	// Read the JSON git file 
	$json = file_get_contents('/etc/fpm/git.json');
	$json_data = json_decode($json,true);

	# If request doesn't contain the git variable return an error.
	# git variable is meant to be an ID.
	if( !(isset($_POST['git_uuid'])) )
	{
			echo '
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Error!</strong>Git Destination not Set.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';					
			exit();
	}
	else
	{
		# Run through all the git entries and try to match the ID.
		$found_git = "false";
		foreach($json_data as $key)
		{
			# Get all details for the git to be used.
			if($key["uuid"] == $_POST['git_uuid'])
			{
				$found_git="true";
				$token = $key["token"];
				$git_fqdn = $key["fqdn"];
				$project = $key["project"];
				$branch = $key["branch"];
				$format = $key["format"];
				$path = $key["path"];
				$id = $key["id"];
				$uuid = $key["uuid"];
				if ($path == ".")
					$path = "";
			}
		}
		# If the ID is not found return an error.
		if ($found_git == "false")
		{
			echo '
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Error!</strong>Git not found on list. Click <a href="settings.php">here</a> to add it..
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';						
			exit();
		}
	}

   function get_policies($project, $token, $id, $git_fqdn, $path, $branch, $uuid) {
		$headers = array(
			'Content-Type: application/json',
			'Accept: application/json, text/javascript, */*; ',
			'PRIVATE-TOKEN: ' . $token
			);

			$url = $git_fqdn."/api/v4/projects/".$id."/repository/tree/?path=".urlencode($path)."&per_page=100&ref=".$branch;
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl,CURLOPT_TIMEOUT,5);
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
				$found = "Folder Not Found";
            $list=[];
				foreach ($result as $key)
				{
					if ($key["type"] == "blob" && (strpos($key['name'], ".json") !== false || strpos($key['name'], ".json") !== false))
					{
                  $list[] = ['name' => $key['name'], 'id' => $key['id'], 'uuid' => $uuid];
               }
				}
				return $list;
			}
			else
				return $httpcode." - Failed!!";

	}

   $list = get_policies($project, $token, $id, $git_fqdn, $path, $branch, $uuid);
   $policies = "var policies = " . json_encode($list)  . ";";

?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
      <meta name="generator" content="Hugo 0.84.0">
      <title>NAP Policy Review</title>
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


               <div class="row">
                  <div class="col-8">
                     <div class="panel">
                        <div class="title"> NAP Policies </div>
                        <div class="line"></div>
                        <div class="content">
                           <table id="policies" class="table table-striped table-bordered" style="width:100%; font-size:12px">
                              <thead>
                                 <tr>
                                 <th>Name</th>
                                 <th style="width: 24px; text-align:center;">Edit</th>
                                 </tr>
                              </thead>
                           </table>
                        </div>

                        <div class="row" style="text-align:right">

                           <button type="button" class="btn btn-success btn" id="analyze" style="float:right">Analyze</button>
                     </div>

                     </div>
                  </div>
                     
                  <div class="col-4 hidden" id="status_tab" >
                     <div class="panel">
                        <div class="title"> Import Status </div>
                        <div class="line"></div>
                        <div class="content">
                           <div class="results">
						
                           </div>
                           <i class='fa fa-spinner fa-pulse fa-3x del_2'></i>
                           <h5 class='del_2'> Please wait.. It can take up to 30-40 seconds per policy.</h5>                        
   
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

</html>

<script>
      $(document).ready(function () {
        $('#general').DataTable(
			{
				"searching": false,
				"info": false,
				"paging":false,
				"ordering":false,

			}
		);
      });
      
   </script>

<script>

   <?php echo $policies; ?>
   
	$(document).ready(function() {
		var table = $('#policies').DataTable( {
            "data": policies,
				"autoWidth": false,
				"processing": true,
				"order": [[0, 'desc']],
            "createdRow": function( row, data, dataIndex ) {
               $('td', row).eq(1).html("<i class='fa fa-trash' ></i> ");                 
            },
            "columnDefs": [
            {target: 2,visible: false,searchable: false,},
            {target: 3,visible: false,searchable: false,}

            ],            
            "columns": [
            { "className": 'bold',"data": "name" },
				{ "className": 'delete_button',"data": null, "orderable":false},
            { "className": 'bold',"data": "id" },
            { "className": 'bold',"data": "uuid" }
			],            
		} );	
      $('#policies tbody').on('click', '.delete_button', function() {
         var idx = table.row(this).index();
         table.row(this).remove().draw(false);
      });
	} );
</script>

<script>
   $("#analyze").click(function() {
      var table = $('#policies').DataTable();
      console.log(table);
      var payload = "["
      var i;
      for (i = 0; i < table.rows().count(); i++) {
         
         var name = table.cell(i, 0).data();
         var id = table.cell(i, 2).data();
         var uuid = table.cell(i, 3).data();
         

         if (i > 0) {
            payload = payload + ', ';
         }
         payload = payload + '{"name":"' + name + '","id":"' + id + '","uuid":"' + uuid + '"}';
        
      }
      payload = payload + "]"
      $("#status_tab").removeClass("hidden");
      var policies = JSON.parse(payload);
      var i = 0;
      doLoop(policies);

      function doLoop(policies) {
         //exit condition
         if (i >= table.rows().count()) {
            window.location.replace("policies.php");
            return;
         }
         $(".results").append(" <h6> Started parsing NAP policy<span style='color:blue'><b>: " + policies[i].name + " </b></span></h6>");
         $.ajax({
            method: "POST",
            url: "import-policies.php",
            data: {
               name: policies[i].name,
               uuid: policies[i].uuid,
               id: policies[i].id
            }
         })
         .done(function(msg) {
            if(msg.completed_successfully)
            {
               $(".results").append(" <h6> Parsing completed <span style='color:green'>successfully</span>. <span style='font-size:14px'>File size: <b>("+msg.file_size+")</b> </span></h6> ");
               if(msg.warnings.length>0)
               {
                  $(".results").append("<h7 style='color:red'> <b>Warnings:</b> <span style='color:red'>"+JSON.stringify(msg.warnings, null, 2)+"</span>. </h7><br>");
               }
               else
               {
                  $(".results").append("<h7 style='color:green'> <b>No Warnings</b></h7><br>");
               }               
            }
            else
            {
               if("error_message" in msg )
               {
                  $(".results").append("<h6> Parsing error:<span style='color:red'> "+msg.error_message+"</span>. </h6>");
               }
            }
            i++; 
            doLoop(policies);
         })
         .fail(function(jqXHR, textStatus, Status) {
            $(".results").append(" <h6>Parsing failed for policy <span style='color:red'><b>: " + policies[i].name + "</b></span></h6>");
            i++; 
            doLoop(policies);
         });
         }
      });
</script>