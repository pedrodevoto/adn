<div class="col-md-6 col-md-offset-1">
	<?=form_open('ajax/exclude_publishers', array('id'=>'excludePubForm', 'role'=>'form'))?>
		<legend>Exclude Publishers / Advertisers</legend>
		<fieldset>
			<div class="form-group">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default entity_type0">
						<input type="radio" name="entity_type0" id="advBtn" value="adv" /> <span>Advertisers</span>
					</label>
					<label class="btn btn-default entity_type0">
						<input type="radio" name="entity_type0" id="pubBtn" value="pub" /> <span>Publishers</span>
					</label>
				</div>  
			</div>
		
			<div class="form-group">
				<input type="text" class="form-control" name="lines" id="lines" placeholder="Advertiser line items (comma separated)" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" >
			</div>
		
			<div class="form-group">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default entity_type">
						<input type="radio" name="entity_type" id="entityBtn" value="entity" /> <span>Publishers</span>
					</label>
					<label class="btn btn-default entity_type">
						<input type="radio" name="entity_type" id="lineBtn" value="line" /> <span>Line items</span>
					</label>
				</div>  
			</div>

			<div class="form-group">
			    <textarea class="form-control" id="entity_ids" name="entity_ids" rows="15" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" required ></textarea>
			</div>

			<input type="submit" class="btn btn-default" name="exclude" value="Exclude">
			<input type="submit" class="btn btn-default" name="include" value="Include" style="margin-left:20px;">
		</fieldset>
	</form>
	<br />
	<br />
	<div id="response"></div>
</div>
<script>
$(document).ready(function() {
	$('.entity_type0').click(function() {
		$('#lines').prop('placeholder', $(this).children().first().next().text() + ' line items (comma separated)');
		if ($(this).children().first().val()=='pub') {
			$('#entityBtn').next().text('Advertisers');
		}
		else {
			$('#entityBtn').next().text('Publishers');
		}
		$('.entity_type.active').click();
	})
	$('.entity_type').click(function() {
		$('#entity_ids').prop('placeholder', $(this).children().first().next().text() + ' (comma separated)');
	});
	$('#entityBtn, #advBtn').click();
	$('#excludePubForm').ajaxForm({
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