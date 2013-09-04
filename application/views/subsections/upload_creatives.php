<div class="col-md-6 col-md-offset-1">
	<?=form_open_multipart('ajax/upload_creatives', array('id'=>'creativesUploadForm', 'role'=>'form'))?>
		<legend>Creative Upload</legend>
		<fieldset>	
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
					<input id="advertiser" class="form-control" name="advertiser" type="number" min="0" placeholder="Advertiser *" value="<?=$advertiser?>" required />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<input id="line" class="form-control" name="line" type="number" min="0" placeholder="Line item" value="<?=$line?>" />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<input id="url" class="form-control" name="url" type="url" placeholder="URL" />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<select class="selectpicker" title="Offer Type" name="offertype" id="offertype" required>
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
			</div>
		
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<input id="prefix" class="form-control" name="prefix" type="text" placeholder="Prefix" />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<input id="suffix" class="form-control" name="suffix" type="text" placeholder="Suffix"/>
					</div>
				</div>
			</div>
		
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<input id="clicktag" class="form-control" name="clicktag" type="text" placeholder="clickTag" value="clickTag" />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
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
				</div>
			</div>
		

			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
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
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<select class="selectpicker" title="Language" id="language" name="language" required>
						<?php foreach($languages as $language):?>
							<option value="<?=$language->id?>"><?=$language->name?></option>
						<?php endforeach;?>
						</select>
					</div>
				</div>
			</div>
		
		
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label for="exampleInputFile">File input</label>
						<input id='zip' name='zip' type="file" required>
						<p class="help-block">Zip archive or individual creative</p>
				  </div>
				</div>
			</div>
		
			<button type="submit" class="btn btn-default">Upload</button>
		</fieldset>
	</form>
	<br />
	<br />
	<div id="response"></div>
</div>
<script>
$(document).ready(function() {
	// $('#creativesUploadForm').validate();
	$('#creativesUploadForm').ajaxForm({
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
	$('.selectpicker').selectpicker();
})
</script>