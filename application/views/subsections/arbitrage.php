<div class="col-md-6 col-md-offset-1">
	<?=form_open('ajax/arbitrage', array('id'=>'arbitrageForm', 'role'=>'form'))?>
	    <legend>Arbitrage</legend>
	    <fieldset>
			<div class="form-group">
				<input class="form-control" name="adv_lines" id="adv_lines" placeholder="Advertiser line items * (comma separated)" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" >
			</div>
			
			<input type="submit" class="btn btn-default" name="exclude" value="Arbitrage">
	    </fieldset>
	</form>
	<br />
	<br />
	<div id="response"></div>
</div>
<script>
$(document).ready(function() {
	$('#arbitrageForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$('fieldset').prop('disabled', true);
			$('#response').removeClass().addClass('alert alert-warning').html('Loading...');
		},
		success: function(responseText, statusText, xhr, $form) {
			$('fieldset').prop('disabled', false);
			$('#response').removeClass().addClass('alert alert-success').html(responseText);
		},
		error: function() {
			$('fieldset').prop('disabled', false);
			$('#response').removeClass().addClass('alert alert-danger').html('Error.');
		}
	});
})
</script>