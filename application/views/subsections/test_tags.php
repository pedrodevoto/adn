<div class="span10 offset1">
	<?=form_open('ajax/create_test_tag', array('id'=>'createTestTagForm'))?>
	    <legend>Create Test Tag</legend>

	    <fieldset>
			<div class="span8 control-group offset1">
	        	<div class="controls">
					<input id="io" name="io" type="number" min="0" placeholder="Insertion Order" />
					<input type="button" value="Search" class="btn " id="searchPixels" />
	        	</div>
	      	</div>
	      	<div class="span8 control-group offset1">
	        	<div class="controls">
					<select title="Search Insertion Order" id="pixel" class="selectpicker show-tick" name="pixel">
						<option></option>
					</select>
	        	</div>
	      	</div>
			<div class="span8 control-group offset1">
				<select title="Test Tag Size" id="size" class="selectpicker show-tick" name="size">
					<option value="120x240">120x240</option>
					<option value="120x600">120x600</option>
					<option value="160x600">160x600</option>
					<option value="234x60">234x60</option>
					<option value="300x250" selected>300x250</option>
					<option value="468x60">468x60</option>
					<option value="728x90">728x90</option>
				</select>
			</div>
	        <div class="span10 form-actions">
				<input type="submit" class="btn btn-primary" value="Crear Test Tag" id="save-button" disabled="true">
	        </div>
			<div class="span10" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	$('.selectpicker').selectpicker();
	$("#searchPixels").click(function(e){
		$(this).val("Cargando...");
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
			$(':submit').prop('disabled', true);
			$('#response').removeClass('alert-success alert-error').addClass('alert').html('Loading...');
		},
		dataType: 'json',
		success: function(responseText, statusText, xhr, $form) {
			if (responseText.error) {
				$('#response').addClass('alert-error').html(responseText.error);
			}
			else {
				var text = 'Done<br />';
				text += ' <button class="btn btn-mini" id="upload-creats">Upload creatives</button>';
				var test_tag_url = responseText.link_to_tag+'/'+$('#size').val();
				text += ' <a href="'+test_tag_url+'">Download Test Tag again</a>';
				$('#response').addClass('alert-success').html(text);
				$('#upload-creats').click(function() {
					window.location.href = responseText.link_to_creats;
					return false;
				})
				window.location.href = test_tag_url;
			}
			$(':submit').prop('disabled', false);
		},
		error: function() {
			$(':submit').prop('disabled', false);
			$('#response').addClass('alert-error').html('Error.');
		}
	});
});
</script>