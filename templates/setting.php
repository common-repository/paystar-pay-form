<div class="wrap">
	<h2><?php _e('PayStar Setting', 'paystar-pay-form'); ?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options');?>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="terminal" />
		<table class="form-table">
			<tr><th colspan="2"><h3><?php _e('PayStar Payment Gateway Setting', 'paystar-pay-form'); ?></h4></th></tr>
			<tr>
				<td width="20%"><?php _e('Terminal Number', 'paystar-pay-form'); ?></td>
				<td>
					<input required type="text" dir="ltr" name="terminal" value="<?php echo esc_html(get_option('terminal')); ?>"/>
					<br /><span style="font-size: 10px"><?php _e('Please Enter Your PayStar Terminal', 'paystar-pay-form'); ?></span>
				</td>
			</tr>
			<tr><td><p class="submit"><input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'paystar-pay-form'); ?>" /></p></td></tr>
		</table>
	</form>
</div>