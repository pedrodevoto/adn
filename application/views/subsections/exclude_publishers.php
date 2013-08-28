<div class="span10 offset1">
	<?=form_open('ajax/exclude_publishers', array('id'=>'excludePubForm'))?>
	    <legend>Excluir Publishers</legend>

	    <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

	    <fieldset>
		  <div class="span8 control-group offset1">
			  <div class="controls">
				  <div class="btn-group" data-toggle-name="entity_type0" data-toggle="buttons-radio" >
				    <button type="button" value="adv" class="btn entity_type0" data-toggle="button" id="advBtn0">Advertisers</button>
				    <button type="button" value="pub" class="btn entity_type0" data-toggle="button" id="pubBtn0">Publishers</button>
					<input type="hidden" name="entity_type0" id="entity_type0" value="adv" />
				  </div>
			  </div>
		  </div>
			 <div class="span8 control-group offset1">
	            <div class="controls">
	              <input class="span7" name="lines" id="lines" placeholder="Advertiser line items (comma separated)" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" >
	            </div>
	          </div>
			  <div class="span8 control-group offset1">
				  <div class="controls">
					  <div class="btn-group" data-toggle-name="entity_type" data-toggle="buttons-radio" >
					    <button type="button" value="entity" class="btn entity_type" data-toggle="button" id="entityBtn">Publishers</button>
					    <button type="button" value="line" class="btn entity_type" data-toggle="button" id="lineBtn">Line items</button>
						<input type="hidden" name="entity_type" id="entity_type" value="pub" />
					  </div>
				  </div>
			  </div>
			  
	          <div class="span8 control-group offset1">
	            <div class="controls">
				  <textarea class="span7" id="entity_ids" name="entity_ids" rows="15" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" ></textarea>
	              <!-- <span class="help-inline">Error</span> -->
	            </div>
	          </div>
		    <div class="span9 form-actions">
				<input type="submit" class="btn btn-primary" name="exclude" value="Excluir">
				<input type="submit" class="btn btn-info" name="include" value="Incluir" style="margin-left:20px;">
		    </div>
			<div class="span9" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	$('.entity_type0').click(function() {
		$('#lines').prop('placeholder', $(this).text() + ' (comma separated)');
		$('#entity_type0').val($(this).val());
		if ($(this).prop('id')=='pubBtn0') {
			$('#entityBtn').text('Advertisers');
		}
		else {
			$('#entityBtn').text('Publishers');
		}
		$('.entity_type.active').click();
	})
	$('.entity_type').click(function() {
		$('#entity_ids').prop('placeholder', $(this).text() + ' (comma separated)');
		$('#entity_type').val($(this).val());
	})
	$('#entityBtn, #advBtn0').click();
	$('#excludePubForm').ajaxForm({
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