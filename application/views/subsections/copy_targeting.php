<div class="col-md-6 col-md-offset-1">
	<?=form_open('ajax/copy_targeting', array('id'=>'copyTargetingForm', 'role'=>'form'))?>
	    <legend>Copy Targeting</legend>
	    <fieldset>
			<div class="row">
				<div class="col-lg-4">
					<div class="form-group">
	  	              <input type="number" class="form-control" name="from_line" id="from_line" placeholder="From line item" required > 
					</div>
				</div>
				<div class="col-lg-8">
					<div class="form-group">
						<input class="form-control" name="to_lines" id="to_lines" placeholder="To line items (comma separated)" required pattern="^\s*(\s*[0-9]+\s*,?)+\s*$" >
					</div>
				</div>
			</div>
			
			<p>
				<div class="checkbox">
			    	<label>
			    		<input type="checkbox" name="copyall" id="copyall"> Copy whole targeting
					</label>
				</div>
			</p>
			<div class="checkbox">
			    <label>
			    	<input type="checkbox" id="selectall"> Select all
			    </label>
			</div>
			
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="techno"> Techno
			    </label>
			</div>
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="geo"> Geography
			    </label>
			</div>
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="freq"> Frequency
			    </label>
			</div>
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="urls"> URLs
			    </label>
			</div>
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="channels"> Channels
			    </label>
			</div>
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="publishers"> Publishers
			    </label>
			</div>
			<div class="checkbox">
			    <label>
			    	<input class="option" type="checkbox" name="vurls"> Validated URLs
			    </label>
			</div>
			
			<input type="submit" class="btn btn-default" value="Copy Targeting">
		    
	    </fieldset>
	</form>
    <br />
    <br />
    <div id="response"></div>
</div>
<script>
$(document).ready(function() {
	$('#selectall').click(function() {
		$('.option').prop('checked', $(this).prop('checked'));
	})
	$('#copyall').click(function() {
		$('.option, #selectall').prop('disabled', $(this).prop('checked'));
	})
	$('#copyTargetingForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			var passed = false;
			$('.option, #copyall').each(function(i,e) {
				passed = $(e).prop('checked')?true:passed;
			});
			if (!passed) {
				alert('You must select at least one option');
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