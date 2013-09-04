<div class="col-md-6 col-md-offset-1">
	<?=form_open('ajax/deactivate', array('id'=>'deactivateForm'))?>
	    <legend>Deactivate</legend>
	    <fieldset>
			<div class="form-group">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default entity_type">
						<input type="radio" name="entity_type" id="lineBtn" value="line" /> <span>Line Items</span>
					</label>
					<label class="btn btn-default entity_type">
						<input type="radio" name="entity_type" id="ioBtn" value="io" /> <span>Insertion Orders</span>
					</label>
				</div>  
			</div>
			
			<div class="form-group">
				<textarea class="form-control" id="entity_ids" name="entity_ids" rows="15" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" required ></textarea>
			</div>
			
			<button type="submit" class="btn btn-default">Deactivate</button>
	    </fieldset>
	</form>
	<br />
	<br />
	<div id="response"></div>
</div>
<script>
$(document).ready(function() {
	$('.entity_type').click(function() {
		$('#entity_ids').prop('placeholder', $(this).children().first().next().text() + ' (comma separated)');
	})
	$('#lineBtn').click();
	$('#deactivateForm').ajaxForm({
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
