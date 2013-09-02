<div class="span10 offset1">
	<?=form_open('ajax/assign_manager', array('id'=>'assignManagerForm'))?>
	    <legend>Assign Manager</legend>

	    <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

	    <fieldset>
		  <div class="span8 control-group offset1">
				  <div class="controls">
					  <div class="btn-group" data-toggle-name="entity_type" data-toggle="buttons-radio" >
					    <button type="button" value="adv" class="btn entity_type" data-toggle="button" id="advBtn">Advertisers</button>
					    <button type="button" value="pub" class="btn entity_type" data-toggle="button" id="pubBtn">Publishers</button>
						<input type="hidden" name="entity_type" id="entity_type" value="adv" />
					  </div>
				  </div>
			  </div>
			  
	          <div class="span8 control-group offset1">
	            <div class="controls">
				  <textarea class="span7" id="entity_ids" name="entity_ids" rows="15" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" required ></textarea>
	            </div>
	          </div>
  			<div class="span8 control-group offset1">
  				<select title="Account Manager" id="contact" class="selectpicker show-tick" name="contact" required>
					<?php foreach($contacts as $contact):?>
						<option value="<?=$contact->id?>"><?=$contact->name?> (<?=$contact->id?>)</option>
					<?php endforeach;?>
  				</select>
  			</div>

		    <div class="span9 form-actions">
				<input type="submit" class="btn btn-large btn-primary" value="Assign">
		    </div>
			<div class="span9" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	$('.selectpicker').selectpicker();
	$('.entity_type').click(function() {
		$('#entity_ids').prop('placeholder', $(this).text() + ' (comma separated)');
		$('#entity_type').val($(this).val());
	})
	$('#advBtn').click();
	$('#assignManagerForm').ajaxForm({
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
