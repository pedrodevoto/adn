<div class="col-md-6 col-md-offset-1">
	<?=form_open('ajax/assign_manager', array('id'=>'assignManagerForm', 'role'=>'form'))?>
		<legend>Assign Manager</legend>
		<fieldset>	
			<div class="form-group">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default entity_type">
						<input type="radio" name="entity_type" id="advBtn" value="adv" /> <span>Advertisers</span>
					</label>
					<label class="btn btn-default entity_type">
						<input type="radio" name="entity_type" id="pubBtn" value="pub" /> <span>Publishers</span>
					</label>
				</div>  
			</div>

			<div class="form-group">
				<textarea class="form-control" id="entity_ids" name="entity_ids" rows="15" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" required ></textarea>
			</div>
			<div class="form-group">
				<select title="Account Manager" id="contact" class="selectpicker" name="contact">
				<?php foreach($contacts as $contact):?>
					<option value="<?=$contact->id?>"><?=$contact->name?> (<?=$contact->id?>)</option>
				<?php endforeach;?>
				</select> 
				<select title="Trafficker" id="trafficker" class="selectpicker" name="trafficker">
				<?php foreach($contacts as $contact):?>
					<option value="<?=$contact->id?>"><?=$contact->name?> (<?=$contact->id?>)</option>
				<?php endforeach;?>
				</select>
			</div>
			<button type="submit" class="btn btn-default">Assign</button>
		</fieldset>
	</form>
	<br />
	<br />
	<div id="response"></div>
</div>

<script>
$(document).ready(function() {
	$('.selectpicker').selectpicker().selectpicker('val', []);
	$('#trafficker').selectpicker('val', []);
	$('.entity_type').click(function() {
		$('#entity_ids').prop('placeholder', $(this).children().first().next().text() + ' (comma separated)');
	})
	$('#advBtn').click();
	$('#assignManagerForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			if (!$('#contact').val() && !$('#trafficker').val()) {
				alert('You must select either an account manager or a trafficker or both.');
				return false;
			}
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
