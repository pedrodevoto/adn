<div class="span10 offset1">
	<?=form_open('ajax/get_line_items', array('id'=>'getLinesForm'))?>
	    <legend>Edit Lines</legend>

	    <fieldset>
			 <div class="span8 control-group offset1">
	            <div class="controls">
	              <input type="number" class="span2" name="adv" id="adv" placeholder="Advertiser ID" required > 
	            </div>
	          </div>

		    <div class="span9 form-actions">
				<input type="submit" class="btn btn-primary" value="Get Line Items" />
		    </div>
			<div class="span9" id="response">
			</div>

	    </fieldset>
	</form>
</div>
<div class="span12" id="table">
</div>
<script>
$(document).ready(function() {
	$('#getLinesForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$(':submit').prop('disabled', true);
			$('#table').html('');
			$('#response').removeClass('alert-success alert-error').addClass('alert').html('Loading...').show();
		},
		success: function(responseText, statusText, xhr, $form) {
			$(':submit').prop('disabled', false);
			if (responseText.indexOf('err')==0) {
				$('#response').addClass('alert-error').html(responseText);
			}
			else {
				$('#response').hide();
				$('#table').html(responseText);
				scrollTo(0, $('#table').position().top);
			}
		},
		error: function() {
			$(':submit').prop('disabled', false);
			$('#response').addClass('alert-error').html('Error.');
		}
	});
})
</script>