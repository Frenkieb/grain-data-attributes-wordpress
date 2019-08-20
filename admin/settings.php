<?php
/**
 * Save the options.
 */

$gtm_id 						= get_option( 'grain_data_gtm_id', '' );
$gd_excluded_paths				= get_option( 'grain_data_excluded_paths', '' );
$gd_excluded_head				= get_option( 'grain_data_excluded_head', '' );
$gd_excluded_head_content		= get_option( 'grain_data_excluded_head_content', '' );
$gd_attributes_page_variables 	= get_option( 'grain_data_attributes_page_variables', array() );
$page_variables_config          = $this->get_page_variables_config();
?>
<div class="container-fluid wrap">
	<div class="row">
		<div class="col">
			<h1 class="wp-heading-inline">Grain Data Attributes</h1>
			<p>This plug-in helps you to easily add the data-attributes for the Grain Tracking Framework.</p>
		</div>
	</div>

	<form action="" method="post">
		<div class="row">
			<div class="col-2">
			</div>
		</div>
	</form>

	<div class="row mt-4" >
		<div class="col">
			<form name="pageVariableForm" action="" method="post">

				<h3>Google Tag Manager ID</h3>

				<div class="form-group">
					<label for="gtmID">Google Tag Manager ID</label>
					<input name="gtmID" type="text" class="form-control" id="gtmID" value="<?php echo $gtm_id; ?>">
				</div>

				<h3>Options</h3>

				<?php foreach ( $page_variables_config as $key => $options ) { ?>
					<?php if ( count( $options ) ) { ?>
				<h3><?php echo esc_html( ucfirst( $key ) ); ?></h3>
						<?php foreach ( $options as $value ) { ?>
				<div class="form-check">
					<input
						type="checkbox"
						class="form-control"
						name="<?php echo $value ?>"
						id="<?php echo $value ?>"
						<?php echo ( isset( $gd_attributes_page_variables[$key][$value] ) && $gd_attributes_page_variables[$key][$value] ) ? 'checked="checked"' : '' ; ?> />
					<label for="<?php echo $value ?>"><?php echo $value ?></label>
				</div>
						<?php } ?>
					<?php } ?>
				<?php }	?>

				<div>
					<h3>Excluded paths</h3>
					<input type="text" name="gd_excluded_paths" value="<?php echo $gd_excluded_paths; ?>" class="regular-text" />
					<p class="description">Voeg hier paden toe die niet naar de cookie wall moeten leiden. Voeg deze toe komma gescheiden, begin met een slash, eindig zonder slash, geen spaties. Bijv. /pad/pad-pad/,/pad</p>
				</div>

				<div>
					<input type="checkbox" id="gd_excluded_head" name="gd_excluded_head" value="1" <?php echo checked( $gd_excluded_head, 1 ); ?>>
					<label for="gd_excluded_head">In plaats hiervan druk dit af in &lt;head&gt;:</label>
					<div>
						<textarea name="gd_excluded_head_content" rows="10" cols="80" class="code"><?php echo $gd_excluded_head_content; ?></textarea>
					</div>
				</div>

				<input class="button btn btn-success" name="save_page_variables" type="submit" value="Save" />
			</form>
		</div>
	</div>
</div>
