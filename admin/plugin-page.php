<?php
	//Get the value of the data_track_page_enabled
	$data_track_page_enabled = get_option( 'grain_data_track_page_enabled', 'False' );
	$gtm_id = get_option( 'grain_data_gtm_id', '' );
	$grain_data_attributes_page_variables = get_option( 'grain_data_attributes_page_variables', array() );
	$pageVariableOptions = unserialize( GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG );
?>
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<h1>Grain Data Attributes</h1>
			<p>
			This plug-in helps you to easily add the data-attributes for the Grain Tracking Framework.
		</div>
	</div>

	<form action="" method="post">
		<div class="row">
			<div class="col-2">
				<div class="form-group">
					<label for="gtmID">Google Tag Manager ID</label>
					<input name="gtmID" type="text" class="form-control" id="gtmID" value="<?php echo $gtm_id; ?>">
				</div>
			</div>
			<div class="col-2">
				<label for="gtmID">&nbsp;</label>
				<input
					name="save_gtm_id"
					type="submit"
					class="btn btn-success form-control"
					value="Save GTM ID" />
			</div>
		</div>
	</form>

	<div class="row">
		<div class="col">
			<h2>Page variables</h2>
			<button
				id="enablePageVariablesButton"
				style="display:none"
				class="btn btn-success">
				Enable page variables
			</button>
			<input
				id="disablePageVariablesButton"
				name="disable_page_variables"
				type="submit"
				style="display:none"
				class="btn btn-danger"
				value="Disable page variables" />
		</div>
	</div>

	<div class="row mt-4" id="pageVariableOptions" style="display:none">
		<div class="col">
			<h3>Options</h3>

			<form name="pageVariableForm" action="" method="post">
			<?php foreach ( $pageVariableOptions as $value ) { ?>
				<div class="form-check">
					<input
						type="checkbox"
						class="form-control"
						name="<?php echo $value ?>"
						id="<?php echo $value ?>"
						<?php echo ( isset( $grain_data_attributes_page_variables[$value] ) && $grain_data_attributes_page_variables[$value] === "on" ) ? 'checked="checked"' : '' ; ?> />
					<label for="<?php echo $value ?>"><?php echo $value ?></label>
				</div>
			<?php }	?>
				<input class="btn btn-success" name="save_page_variables" type="submit" value='Save options' />
			</form>
		</div>
	</div>
</div>

<script>
(function( $ ) {
	$( window ).load(function() {
		var dataTackPageEnabled = "<?php echo $data_track_page_enabled ?>";

		if (dataTackPageEnabled === 'True') {
			enablePageVariables();
		} else {
			disablePageVariables();
		}

		function enablePageVariables() {
			$('#disablePageVariablesButton').show();
			$('#enablePageVariablesButton').hide();
			$('#pageVariableOptions').show();
		}

		function disablePageVariables() {
			$('#enablePageVariablesButton').show();
			$('#disablePageVariablesButton').hide();
			$('#pageVariableOptions').hide();
		}

		$('#enablePageVariablesButton').on( 'click', enablePageVariables );
	});
})( jQuery );
</script>
