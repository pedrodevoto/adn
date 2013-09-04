<div class="col-md-6 col-md-offset-1">
	<?=form_open('ajax/create_test_tag', array('id'=>'createTestTagForm', 'role'=>'form'))?>
		<legend>Create Test Tag</legend>
		<fieldset>
			<div class="row">
				<div class="col-lg-5">
					<div class="form-group">
						<input id="io" class="form-control" name="io" type="number" min="0" placeholder="Insertion Order" />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<input type="button" value="Search" class="btn btn-default" id="searchPixels" />
					</div>
				</div>
			</div>
		
			<div class="form-group">
				<select title="Search Insertion Order" id="pixel" class="selectpicker" name="pixel">
					<option></option>
				</select>
			</div>
		
			<div class="form-group">
				<select title="Test Tag Size" id="size" class="selectpicker" name="size">
					<option value="120x240">120x240</option>
					<option value="120x600">120x600</option>
					<option value="160x600">160x600</option>
					<option value="234x60">234x60</option>
					<option value="300x250" selected>300x250</option>
					<option value="468x60">468x60</option>
					<option value="728x90">728x90</option>
				</select>
			</div>
			<button type="submit" id="save-button" class="btn btn-default" disabled>Create Test Tag</button>
		</fieldset>
	</form>
    <br />
    <br />
    <div id="response"></div>

</div>
<script>
$(document).ready(function() {
	$('.selectpicker').selectpicker();
	$("#pixel").prop('disabled',true).selectpicker('refresh');
	$("#searchPixels").click(function(e){
		$(this).val("Searching...");
		$(this).addClass("disabled");
		$("#pixel").prop('disabled',true).selectpicker('refresh');
		var ioID = $("#io").val();
		$.ajax({
		   	type: "GET",
			dataType: 'json',
		   	url: "<?=site_url('ajax/get_pixels')?>/"+ioID,
			success: function(data, textStatus){
				
			    var options = $("#pixel");
			    options.empty();
				
			    $.each(data, function(i,item) {
			        options.append($("<option />").val(item.id).text(item.name + " ("+item.id+")"));
    			});
				$("#pixel").prop('disabled',false).selectpicker('refresh');
				
				$("#searchPixels").removeClass("disabled").val("Search");
				$("#save-button").prop('disabled', false);
			},
   			error: function(textStatus, errorThrown) {
				$("#searchPixels").removeClass("disabled").val("Search");
				alert("Could not get pixels");
		   	}
		});
	
	});
	$('#createTestTagForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$('fieldset').prop('disabled', true);
			$('#response').removeClass().addClass('alert alert-warning').html('Loading...');
		},
		dataType: 'json',
		success: function(responseText, statusText, xhr, $form) {
			if (responseText.error) {
				$('#response').removeClass().addClass('alert alert-danger').html(responseText.error);
			}
			else {
				var text = 'Done<br />';
				text += ' <button class="btn btn-default btn-xs" id="upload-creats">Upload creatives</button>';
				var test_tag_url = responseText.link_to_tag+'/'+$('#size').val();
				text += ' <a href="'+test_tag_url+'">Download Test Tag again</a>';
				$('#response').removeClass().addClass('alert alert-success').html(text);
				$('#upload-creats').click(function() {
					window.location.href = responseText.link_to_creats;
					return false;
				})
				window.location.href = test_tag_url;
			}
			$('fieldset').prop('disabled', false);
		},
		error: function() {
			$('fieldset').prop('disabled', false);
			$('#response').removeClass().addClass('alert alert-danger').html('Error.');
		}
	});
});
</script>