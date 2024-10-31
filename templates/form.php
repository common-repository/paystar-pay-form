<div class="payment-form">
	<form action="" method="post">
		<table width="100%">
			<tr>
				<td><?php _e('Your name', 'paystar-pay-form'); ?></td>
				<td>
					<input required autofocus type="text" name="payer_name" class="paystar-input" value="<?php if ($current_user->ID) echo esc_html($current_user->display_name); ?>"/>
					<span class="description-require">*</span>
				</td>
			</tr>

			<tr>
				<td><?php _e('Your email', 'paystar-pay-form'); ?></td>
				<td>
					<input required type="text" name="payer_email" dir="ltr" class="paystar-input" value="<?php if ($current_user->ID) echo esc_html($current_user->user_email); ?>"/>
					<span class="description-require">*</span>
				</td>
			</tr>

			<tr>
				<td><?php _e('Your mobile', 'paystar-pay-form'); ?></td>
				<td>
					<input required type="text" name="payer_mobile" dir="ltr" class="paystar-input"/>
					<span class="description-require">*</span>
				</td>
			</tr>

			<tr>
				<td><?php _e('Price', 'paystar-pay-form'); ?></td>
				<td>
					<input required type="number" min="10000" step="100" name="payer_price" dir="ltr" class="paystar-input"/>
					<span class="description-require">*</span>
					<br /><span class="description-field"><?php _e('Rial', 'paystar-pay-form'); ?></span>
				</td>
			</tr>

			<tr>
				<td><?php _e('Description Payment', 'paystar-pay-form'); ?></td>
				<td>
					<textarea required name="description_payment" class="paystar-input"></textarea>
					<span class="description-require">*</span>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<input type="submit" name="submit_payment_paystar" value="<?php _e('Pay', 'paystar-pay-form'); ?>" class="btn button paystar-submit"/>
				</td>
			</tr>
		</table>
	</form>
</div>