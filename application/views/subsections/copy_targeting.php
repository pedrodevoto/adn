<div class="span10 offset1">
	<?=form_open('ajax/copy_targeting', array('id'=>'copyTargetingForm'))?>
	    <legend>Copy Targeting</legend>

	    <fieldset>
			 <div class="span8 control-group offset1">
	            <div class="controls">
	              <input type="number" class="span2" name="from_line" id="from_line" placeholder="From line item" required > <input class="span5" name="to_lines" id="to_lines" placeholder="To line items (comma separated)" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" >
	            </div>
	          </div>

		    <div class="span9 form-actions">
				<input type="submit" class="btn btn-primary" name="exclude" value="Copy Targeting">
		    </div>
			<div class="span9" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	$('#copyTargetingForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$(':submit').prop('disabled', true);
			$('#response').removeClass('alert-success alert-error').addClass('alert').html('Loading...');
		},
		success: function(responseText, statusText, xhr, $form) {
			$(':submit').prop('disabled', false);
			$('#response').addClass('alert-success').html(responseText);
		},
		error: function() {
			$(':submit').prop('disabled', false);
			$('#response').addClass('alert-error').html('Error.');
		}
	});
})
</script>