
<table class="table table-bordered table-striped table-violation" style=" font-size:12px">
	<colgroup>
		<col style="width: 150px">
		<col>
	</colgroup>

	<tbody>
		<tr>
			<td>Violation Type</td>
			<td><b>Illegal URL length</b></td>
		</tr>
		<tr>
			<td>Extension</td>
			<td><b>.<span id="filetype_url"><?php echo htmlspecialchars(base64_decode($req_violations['extension']), ENT_SUBSTITUTE);  ?></span></b></td>
		</tr>
		<tr>
			<td>Detected Length</td>
			<td style="color:red"><b><span id="url_detected_length"><?php echo ($req_violations['uri_len']);  ?></span></b></td>
		</tr>
		<tr>
			<td>Configured Length</td>
			<td>
				<b><div style="margin-top:3px; float:left"><?php echo '<span id="url_configured_length">'. $req_violations['uri_len_limit'].'</span>'; ?></div></b>
				<button type="button" class="btn btn_float_right btn-sm btn-dark learn_btn modal_open" data-bs-toggle="modal" data-bs-target="#violation_modal" aria-expanded="false" id="urllength" value="<?php echo $total_count;?>">Adjust Length </button>
			</td>
		</tr>
		<?php 
			if (array_key_exists("wildcard_entity",$req_violations))
			{echo '
				<tr>
					<td>Matched on Entity</td>
					<td><b>'.($req_violations['wildcard_entity']) .'</b></td>
				</tr>';
			}
		?>													
	</tbody>
</table>					
