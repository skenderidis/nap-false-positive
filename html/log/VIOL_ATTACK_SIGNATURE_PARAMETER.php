<?php //if (!(is_array($req_violations['parameter_data']['value']))) $req_violations['parameter_data']['value']="IA=="; ?>

<table class="table table-bordered table-striped table-violation" style=" font-size:12px">
<colgroup>
		<col style="width: 150px">
		<col>
	</colgroup>

	<tbody >
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
			<td><b>Parameter</b></td>
		</tr>
		<tr>
			<td>Violation</td>
			<td>
				<b><?php 
					$temp_attack = base64_decode($sig_violations['kw_data']['buffer']);  
					$temp_start = substr($temp_attack, 0, $sig_violations['kw_data']['offset'] );
					$temp_violation = substr($temp_attack, $sig_violations['kw_data']['offset'], $sig_violations['kw_data']['length'] );
					$temp_end = substr($temp_attack, $sig_violations['kw_data']['offset']+$sig_violations['kw_data']['length'] );
					echo htmlspecialchars($temp_start,ENT_SUBSTITUTE). "<span style='color:red; border:1px solid red;'>" . htmlspecialchars($temp_violation,ENT_SUBSTITUTE) . "</span>" . htmlspecialchars($temp_end,ENT_SUBSTITUTE);
					?>
				</b>
			</td>
		</tr>
		<tr>
			<td>Parameter Name</td>
			<td><b><?php echo '<span id="parameter_'.$total_count.'">'.htmlspecialchars(base64_decode($req_violations['parameter_data']['name']),ENT_SUBSTITUTE).'</span>'; ?></b>
			<button type="button" class="btn btn_float_right btn-sm btn-dark learn_btn modal_open" data-bs-toggle="modal" data-bs-target="#violation_modal" aria-expanded="false" id="attack_sig_parameter" value="<?php echo $total_count;?>">Disable Signature on Parameter</button>

			</td>
		</tr>
		<tr>
			<td>Parameter Value</td>
			<td><b><?php echo htmlspecialchars(base64_decode($req_violations['parameter_data']['value']),ENT_SUBSTITUTE);  ?></b></td>
		</tr>		
		<tr>
			<td>Enforcement Level</td>
			<td><b><?php echo $req_violations['parameter_data']['enforcement_level'];  ?></b></td>
		</tr>
		<?php 
			if (array_key_exists("param_name_pattern",$req_violations['parameter_data']))
			{echo '
				<tr>
					<td>Matched on Entity</td>
					<td><b>'.($req_violations['parameter_data']['param_name_pattern']) .'</b></td>
				</tr>';
			}
		?>	
		
	</tbody>
							
</table>					

<?php $total_count++; ?>
