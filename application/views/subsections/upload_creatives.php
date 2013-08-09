<div class="span12">
	<?=form_open_multipart('ajax/upload_creatives', array('id'=>'creativesUploadForm'))?>
	    <legend>Creative Upload</legend>

	    <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

	    <fieldset>
			<div class="span8 control-group offset1">
				<label for="advertiser">Advertiser ID:</label>
	        	<div class="controls">
					<input class="required" id="advertiser" name="advertiser" type="number" value="" />
	        	</div>
	      	</div>
	      	<div class="span4 control-group offset1">
				<label for="line">Line ID:</label>
	        	<div class="controls">
					<input id="line" name="line" type="number" value="" />
	        	</div>
	      	</div>
	  	  	<div class="span4 control-group offset1">
				<label for="url">URL:</label>
	        	<div class="controls">
					<input id="url" name="url" type="url" value=""/>
	        	</div>
	      	</div>
	  	  	<div class="span4 control-group offset1">
				<label for="offertype">Offer type:</label>
	        	<div class="controls">
					<select class="selectpicker show-tick" title="Offer Type" name="offertype" id="offertype">
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
				<label for="prefix">Prefix:</label>
	        	<div class="controls">
					<input id="prefix" name="prefix" type="text" value=""/>
	        	</div>
	      	</div>
	  		<div class="span4 control-group offset1">
				<label for="clicktag">Click tag:</label>
	        	<div class="controls">
					<input id="clicktag" name="clicktag" type="text" value="clickTAG" />
	        	</div>
	      	</div>
			<div class="span4 control-group offset1">
				<label for="suffix">Suffix:</label>
	        	<div class="controls">
					<input id="suffix" name="suffix" type="text" value=""/>
	        	</div>
	      	</div>
      	
	  	    <div class="span4 control-group offset1">
				<label for="themes[]">Creative Themes:</label>
				<select class="selectpicker" multiple data-selected-text-format="count" name="themes[]" id="themes">
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
				<label for="specs[]">Creative Specification:</label>
	        	<select class="selectpicker" multiple data-selected-text-format="count" name="specs[]" id="specs">
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
				<label for="language">Language:</label>
	        	<div class="controls">
					<select class="selectpicker show-tick" title="Language" id="language" name="language">
						<?php foreach($languages as $language):?>
							<option value="<?=$language->id?>"><?=$language->name?></option>
						<?php endforeach;?>
					</select>
	        	</div>
	      	</div>
      	
      	
	  		<div class="span8 control-group offset1">
				<label for="zip">Archivo zip:</label>
	        	<div class="controls span8">
					<input id="zip" name="zip" type="file" class="" />
	        	</div>
	      	</div>
	        <div class="span12 form-actions">
				<input type="submit" class="btn btn-primary" value="Guardar" id="upload">
	        </div>
			<div class="span12" id="response">
			</div>
	    </fieldset>
	</form>
</div>
<script>
$(document).ready(function() {
	$('#creativesUploadForm').validate();
	$('#creativesUploadForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$('#upload').prop('disabled', true);
			$('#response').html('loading...');
		},
		success: function(responseText, statusText, xhr, $form) {
			$('#upload').prop('disabled', false);
			$('#response').html(responseText);
		}
	});
	$('.selectpicker').selectpicker();
})
</script>