<div class="span10 offset1">
	<?=form_open_multipart('ajax/upload_creatives', array('id'=>'creativesUploadForm'))?>
	    <legend>Creative Upload</legend>

	    <fieldset>
			<div class="span8 control-group offset1">
	        	<div class="controls">
					<input id="advertiser" name="advertiser" type="number" min="0" placeholder="Advertiser *" value="<?=$advertiser?>" required />
	        	</div>
	      	</div>
	      	<div class="span4 control-group offset1">
	        	<div class="controls">
					<input id="line" name="line" type="number" min="0" placeholder="Line item" value="<?=$line?>" />
	        	</div>
	      	</div>
	  	  	<div class="span4 control-group offset1">
	        	<div class="controls">
					<input id="url" name="url" type="url" placeholder="URL" />
	        	</div>
	      	</div>
	  	  	<div class="span4 control-group offset1">
	        	<div class="controls">
					<select class="selectpicker show-tick" title="Offer Type" name="offertype" id="offertype" required>
					<?php foreach ($offer_types as $offer_type):?>
						<optgroup label="<?=$offer_type->description?>" />
						<?php foreach ($offer_type->sub_offer_types as $sub_offer_type):?>
							<option value="<?=$sub_offer_type->id?>"><?=$sub_offer_type->description?></option>
						<?php endforeach;?>
						</optgroup>
					<?php endforeach; ?>		
					</select>
	        	</div>
	      	</div>
	      	<div class="span4 control-group offset1">
	        	<div class="controls">
					<input id="prefix" name="prefix" type="text" placeholder="Prefix" />
	        	</div>
	      	</div>
	  		<div class="span4 control-group offset1">
	        	<div class="controls">
					<input id="clicktag" name="clicktag" type="text" placeholder="clickTag" value="clickTag" />
	        	</div>
	      	</div>
			<div class="span4 control-group offset1">
	        	<div class="controls">
					<input id="suffix" name="suffix" type="text" placeholder="Suffix"/>
	        	</div>
	      	</div>
      	
	  	    <div class="span4 control-group offset1">
				<select title="Themes" class="selectpicker" multiple data-selected-text-format="count" name="themes[]" id="themes">
				<?php foreach ($creative_themes as $creative_theme):?>
					<optgroup label="<?=$creative_theme->category?>" />
					<?php foreach ($creative_theme->sub_categories as $sub_category):?>
						<option value="<?=$sub_category->id?>"><?=$sub_category->tag?></option>
					<?php endforeach;?>
					</optgroup>
				<?php endforeach; ?>
				</select>
			</div>
      	
	  	    <div class="span4 control-group offset1">
	        	<select title="Specs" class="selectpicker" multiple data-selected-text-format="count" name="specs[]" id="specs">
				<?php foreach ($creative_specs as $creative_spec):?>
					<optgroup label="<?=$creative_spec->category?>" />
					<?php foreach ($creative_spec->sub_categories as $sub_category):?>
						<option value="<?=$sub_category->id?>"><?=$sub_category->tag?></option>
					<?php endforeach;?>
					</optgroup>
				<?php endforeach; ?>
				</select>
			</div>
      	
	      	<div class="span4 control-group offset1">
					<select class="selectpicker show-tick" title="Language" id="language" name="language" required>
						<?php foreach($languages as $language):?>
							<option value="<?=$language->id?>"><?=$language->name?></option>
						<?php endforeach;?>
					</select>
	      	</div>
      	
      	
	  		<div class="span8 control-group offset1">
				<label for="zip">Zip archive:</label>
	        	<div class="controls span8">
					<input id="zip" name="zip" type="file" required />
	        	</div>
	      	</div>
	        <div class="span10 form-actions">
				<input type="submit" class="btn btn-primary" value="Upload" id="upload">
	        </div>
			<div class="span10" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	// $('#creativesUploadForm').validate();
	$('#creativesUploadForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$('#upload').prop('disabled', true);
			$('#response').removeClass('alert-success alert-error').addClass('alert').html('Loading...');
		},
		success: function(responseText, statusText, xhr, $form) {
			$('#upload').prop('disabled', false);
			$('#response').addClass('alert-success').html(responseText);
		},
		error: function() {
			$('#upload').prop('disabled', false);
			$('#response').addClass('alert-error').html('Error.');
		}
	});
	$('.selectpicker').selectpicker();
})
</script>