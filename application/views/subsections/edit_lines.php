<div class="row">
	<div class="col-md-6 col-md-offset-1">
		<?=form_open('ajax/get_line_items', array('id'=>'getLinesForm'))?>
		    <legend>Edit Lines</legend>
		    <fieldset>
				<div class="form-group">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default entity_type">
							<input type="radio" name="entity_type" id="advBtn" value="adv" /> <span>Advertiser</span>
						</label>
						<label class="btn btn-default entity_type">
							<input type="radio" name="entity_type" id="ioBtn" value="io" /> <span>Insertion Order</span>
						</label>
					</div>  
				</div>
			
				<div class="form-group">
	            	<input type="number" class="form-control" name="entity_id" id="entity_id" placeholder="Advertiser ID" required /> 
				</div>

			    <input type="submit" class="btn btn-default" value="Get Line Items" />
		    </fieldset>
		</form>
		<br />
		<br />
		<div id="response"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12" id="table"></div>
</div>
<script>
$(document).ready(function() {
	$('.entity_type').click(function() {
		$('#entity_id').prop('placeholder', $(this).children().first().next().text() + ' ID');
	})
	$('#advBtn').click();
	$('#getLinesForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$('fieldset').prop('disabled', true);
			$('#table').html('');
			$('#response').removeClass().addClass('alert alert-warning').html('Loading...').show();
		},
		success: function(responseText, statusText, xhr, $form) {
			$('fieldset').prop('disabled', false);
			if (responseText.indexOf('err')==0) {
				$('#response').removeClass().addClass('alert alert-danger').html('Error.');
			}
			else {
				$('#response').hide();
				$('#table').html(responseText);
				scrollTo(0, $('#table').position().top);
			}
		},
		error: function() {
			$('fieldset').prop('disabled', false);
			$('#response').removeClass().addClass('alert alert-danger').html('Error.');
		}
	});
})
</script>