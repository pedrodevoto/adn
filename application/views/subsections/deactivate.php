<div class="span10 offset1">
	<?=form_open('ajax/deactivate', array('id'=>'deactivateForm'))?>
	    <legend>Deactivate</legend>

	    <fieldset>
		  <div class="span8 control-group offset1">
				  <div class="controls">
					  <div class="btn-group" data-toggle-name="entity_type" data-toggle="buttons-radio" >
					    <button type="button" value="line" class="btn entity_type" data-toggle="button" id="lineBtn">Line Items</button>
					    <button type="button" value="io" class="btn entity_type" data-toggle="button" id="ioBtn">Insertion Orders</button>
						<input type="hidden" name="entity_type" id="entity_type" value="line" />
					  </div>
				  </div>
			  </div>
			  
	          <div class="span8 control-group offset1">
	            <div class="controls">
				  <textarea class="span7" id="entity_ids" name="entity_ids" rows="15" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" ></textarea>
	            </div>
	          </div>
			  
		    <div class="span9 form-actions">
				<input type="submit" class="btn btn-large btn-primary" value="Deactivate">
		    </div>
			<div class="span9" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	$('.entity_type').click(function() {
		$('#entity_ids').prop('placeholder', $(this).text() + ' (comma separated)');
		$('#entity_type').val($(this).val());
	})
	$('#lineBtn').click();
	$('#deactivateForm').ajaxForm({
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
