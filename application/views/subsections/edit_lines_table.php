<div class="row">
	<div class="col-lg-12">
		<div class="form-group">
			<button value="vertical" class="btn btn-xs btn-info tab-btn">Tab ↓</button> <button value="horizontal" class="btn btn-xs btn-info tab-btn">Tab →</button>
			<button value="expand" class="btn btn-xs btn-info toggle-btn">Expand all</button> <button value="collapse" class="btn btn-xs btn-info toggle-btn">Collapse all</button>
		</div>
	</div>
</div>
<?=form_open('ajax/update_line_items', array('id'=>'updateLinesForm'))?>
	<fieldset>
		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<table class="table table-hover table-bordered table-condensed">
						<thead>
							<th width="15%">IO</th>
							<th width="30%">Line</th>
							<th width="45%">URL</th>
							<th width="10%">Price</th>
						</thead>
						<tbody>
							<?php
							$i = 0;
							foreach ($insertion_orders as $insertion_order) {
							?>
								<tr class="warning io_toggle" ioname="<?=$insertion_order->description?> (<?=$insertion_order->id?>)">
									<td colspan="4" style="text-align:center;cursor:pointer">Hide/Show</td>
								</tr>
								<tr>
									<td colspan="2"><em class="pull-right">Edit All →</em></td>
									<td>
										<input type="url" class="form-control input-sm tab-url change-all" ioid="<?=$insertion_order->id?>" inputtype="url"  />
									</td>
									<td>
										<input type="number" step="any" min="0" class="form-control input-sm tab-price change-all" ioid="<?=$insertion_order->id?>" inputtype="price" />
									</td>
								</tr>
								<?php	
								$first = TRUE;
								foreach ($insertion_order->line_items as $line_item) {
									$active = $line_item->active?TRUE:FALSE;
								?>
									<tr>
										<input type="hidden" name="line_item[<?=$i?>][id]" value="<?=$line_item->id?>" />
										<?php
										if ($first) { ?>
											<td style="vertical-align:middle" rowspan="<?=count($insertion_order->line_items)?>"><?=$insertion_order->description?> (<?=$insertion_order->id?>)</td>
										<?php
											}
										?>
										<td><?=($active?'':'<em class="text-muted">')?><?=$line_item->description?> (<?=$line_item->id?>)<?=($active?'':'</em>')?></td>
										<td><input type="url" class="form-control input-sm tab-url url-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][url]" placeholder="<?=$line_item->campaigns[0]->click_url_override?>" /></td>
										<td><input type="number" step="any" min="0" class="form-control input-sm col-lg-3 tab-price price-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][amount]" placeholder="<?=$line_item->amount?>" /></td>
									</tr>
									<?php
									$first = FALSE;
									$i++;
								}
							}
							?>
						</tbody>
					</table>
				</div> <!-- form-group -->
			</div>
		</div> <!-- row -->
		<input id="lines_submit" type="submit" class="btn btn-default" value="Update">
	</fieldset>
</form>
<br />
<br />
<div id="lines_response"></div>

<script>
$(document).ready(function() {
	$('.io_toggle').click(function(){
		$(this).nextUntil('tr.io_toggle').slideToggle();
		$(this).children().first().text($(this).children().first().text()=="Hide/Show"?"Hide/Show ("+$(this).attr("ioname")+")":"Hide/Show")
	});
	$('.toggle-btn').click(function() {
		switch ($(this).val()) {
			case 'expand':
				$('.io_toggle').children().first().text("Hide/Show").parent().nextUntil('tr.io_toggle').show();
				break;
			case 'collapse':
				$('.io_toggle').each(function(i, e) {
					$(e).nextUntil('tr.io_toggle').hide();
					$(e).children().first().text("Hide/Show ("+$(e).attr("ioname")+")")
				});
				break;
		}
	})
	$('.change-all').change(function() {
		$('.'+$(this).attr('inputtype')+'-io-'+$(this).attr('ioid')).val($(this).val());
	});
	$('#updateLinesForm').ajaxForm({
		beforeSubmit: function(arr, $form, options) {
			$('fieldset').prop('disabled', true);
			$('#lines_response').removeClass().addClass('alert alert-warning').html('Loading...');
		},
		success: function(responseText, statusText, xhr, $form) {
			$('fieldset').prop('disabled', false);
			$('#lines_response').removeClass().addClass('alert alert-success').html(responseText);
		},
		error: function() {
			$('fieldset').prop('disabled', false);
			$('#lines_response').removeClass().addClass('alert alert-error').html('Error.');
		}
	});
	function setTab(direction) {
		switch (direction) {
			case 'vertical':
				$('.tab-url').each(function(i,o){$(o).attr('tabindex', i+1000);});
				$('.tab-price').each(function(i,o){$(o).attr('tabindex', i+$('.tab-url').length+1000);});
				break;
			case 'horizontal':
				$('.tab-url, .tab-price').each(function(i,o){$(o).attr('tabindex', i+1000);});
				break;
		}
	}
	$('.tab-btn').click(function() {
		setTab($(this).val());
	})
	setTab('vertical');
	if ($('.io_toggle').size()>1) {
		$('.io_toggle').click();
	}
});
</script>