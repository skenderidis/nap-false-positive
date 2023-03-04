<?php if (sizeof($req_violations['cookie']['cookie_value']) =="0") $req_violations['cookie']['cookie_value']="IA=="; ; ?>

<table class="table table-bordered table-striped table-violation" style=" font-size:12px">
<colgroup>
		<col style="width: 150px">
		<col>
	</colgroup>

	<tbody>
		<tr>
			<td>Violation Type</td>
			<td><b>Attack Signature Detected</b></td>
		</tr>	
		<tr>
			<td>Signature ID</td>
			<td>
				<b><div style="margin-top:3px; float:left"><?php echo '<span id="sig_id_'.$total_count.'">'.$sig_violations["sig_id"].'</span>'; ?></div></b>
				<button type="button" class="btn btn_float_right btn-sm btn-dark learn_btn modal_open" data-bs-toggle="modal" data-bs-target="#violation_modal" aria-expanded="false" id="attack_sig_global" value="<?php echo $total_count;?>">Disable Signature </button>
			</td>
		</tr>
		<tr>
			<td>Context</td>
			<td><b>Cookie</b></td>
		</tr>
		<tr>
			<td>Violation</td>
			<td><b><?php 	
					$temp_attack = base64_decode($sig_violations['kw_data']['buffer']);  
					$temp_start = substr($temp_attack, 0, $sig_violations['kw_data']['offset'] );
					$temp_violation = substr($temp_attack, $sig_violations['kw_data']['offset'], $sig_violations['kw_data']['length'] );
					$temp_end = substr($temp_attack, $sig_violations['kw_data']['offset']+$sig_violations['kw_data']['length'] );
					echo htmlspecialchars($temp_start,ENT_SUBSTITUTE). "<span style='color:red; border:1px solid red;'>" . htmlspecialchars($temp_violation,ENT_SUBSTITUTE) . "</span>" . htmlspecialchars($temp_end,ENT_SUBSTITUTE);
					?></b></td>
		</tr>
		<tr>
			<td>Cookie Name</td>
			<td><b><?php echo '<span id="cookie_'.$total_count.'">'.htmlspecialchars(base64_decode($req_violations['cookie']['cookie_name']),ENT_SUBSTITUTE).'</span>'; ?></b>
				<button type="button" class="btn btn_float_right btn-sm btn-dark learn_btn modal_open" data-bs-toggle="modal" data-bs-target="#violation_modal" aria-expanded="false" id="attack_sig_cookie" value="<?php echo $total_count;?>">Disable Signature on Cookie</button>
			</td>
		</tr>
		<tr>
			<td>Cookie Value</td>
			<td><b><?php echo htmlspecialchars(base64_decode($req_violations['cookie']['cookie_value']),ENT_SUBSTITUTE);  ?></b></td>
		</tr>		
		<?php 
			if (array_key_exists("cookie_pattern",$req_violations['cookie']))
			{echo '
				<tr>
					<td>Matched on Entity</td>
					<td><b>'.($req_violations['cookie']['cookie_pattern']) .'</b></td>
				</tr>';
			}
		?>			
	</tbody>

											
</table>					

<?php $total_count++; ?>
