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
					<table class="table table-hover table-bordered table-condensed" style="font-size:12px">
						<thead>
							<th width="15%">IO</th>
							<th width="20%">Line</th>
							<th width="31%">URL</th>
							<th width="10%">Delivery type</th>
							<th width="8%">Budget</th>
							<th width="8%">Cap</th>
							<th width="8%">Price</th>
						</thead>
						<tbody>
							<?php
							$i = 0;
							foreach ($insertion_orders as $insertion_order) {
							?>
								<tr class="warning io_toggle" ioname="<?=$insertion_order->description?> (<?=$insertion_order->id?>)">
									<td colspan="7" style="text-align:center;cursor:pointer">Hide/Show</td>
								</tr>
								<tr>
									<td colspan="2"><em class="pull-right">Edit All →</em></td>
									<td>
										<input type="url" class="form-control input-sm tab-url change-all" ioid="<?=$insertion_order->id?>" inputtype="url"  />
									</td>
									<td>
										<select class="form-control input-sm tab-delivery_units_type change-all" ioid="<?=$insertion_order->id?>" inputtype="delivery_units_type">
										<?php foreach(array('Imps', 'Cash') as $delivery_units): ?>
											<optgroup label="<?=$delivery_units?>">
											<?php foreach(array('ASAP', 'Even', 'Hourly Cap', 'Daily Cap', 'Weekly Cap', 'Monthly Cap') as $delivery_types):?>
												<option value="<?=$delivery_units.'-'.$delivery_types?>"><?=$delivery_types?></option>
											<?php endforeach;?>
											</optgroup>
										<?php endforeach;?>
										</select>
									</td>
									<td>
										<input type="number" step="any" min="0" class="form-control input-sm tab-budget change-all" ioid="<?=$insertion_order->id?>" inputtype="budget" />
									</td>
									<td>
										<input type="number" step="any" min="0" class="form-control input-sm tab-cap change-all" ioid="<?=$insertion_order->id?>" inputtype="cap" />
									</td>
									<td>
										<input type="number" step="any" min="0" class="form-control input-sm tab-price change-all" ioid="<?=$insertion_order->id?>" inputtype="price" />
									</td>
								</tr>
								<?php	
								$first = TRUE;
								foreach ($insertion_order->line_items as $line_item) {
									$active = $line_item->active?TRUE:FALSE;
									$delivery_unit = max($line_item->budget, $line_item->delivery_cap) ? 'Cash' : 'Imps';
									$budget = $delivery_unit == 'Cash' ? $line_item->budget : $line_item->imp_budget;
									$delivery_cap = $delivery_unit == 'Cash' ? $line_item->delivery_cap : $line_item->imp_delivery_cap;
									$delivery_type = $delivery_unit == 'Cash' ? $line_item->delivery_type : $line_item->imp_delivery_type;
									$delivery_type = $delivery_type ? $delivery_type : 'ASAP';
								?>
									<tr>
										<input type="hidden" name="line_item[<?=$i?>][id]" value="<?=$line_item->id?>" />
										<?php
										if ($first) { ?>
											<td style="vertical-align:middle" rowspan="<?=count($insertion_order->line_items)?>"><?=$insertion_order->description?> (<?=$insertion_order->id?>)</td>
										<?php
											}
										?>
										<td><?=($active?'<span class="activate-edit" io="'.$insertion_order->id.'" i="'.$i.'" line="'.$line_item->description.'">':'<em class="text-muted">')?><?=$line_item->description?> (<?=$line_item->id?>)<?=($active?'</span>':'</em>')?></td>
										<td><input type="url" class="form-control input-sm tab-url url-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][url]" placeholder="<?=$line_item->campaigns[0]->click_url_override?>" /></td>
										<td>
											<select class="form-control input-sm tab-delivery_units_type delivery_units_type-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][delivery_units_type]">
											<?php foreach(array('Imps', 'Cash') as $delivery_units): ?>
												<optgroup label="<?=$delivery_units?>">
												<?php foreach(array('ASAP', 'Even', 'Hourly Cap', 'Daily Cap', 'Weekly Cap', 'Monthly Cap') as $delivery_types) {?>
													<?php $selected = ($delivery_unit==$delivery_units and $delivery_type==$delivery_types)?'selected':''; ?>
													<option value="<?=$delivery_units.'-'.$delivery_types?>" <?=$selected?>><?=$delivery_types?></option>
													<?php }?>
												</optgroup>
											<?php endforeach;?>
											</select>
										</td>
										<td><input type="number" step="any" min="0" class="form-control input-sm tab-budget budget-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][budget]" placeholder="<?=$budget?>" /></td>
										<td><input type="number" step="any" min="0" class="form-control input-sm tab-cap cap-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][cap]" placeholder="<?=$delivery_cap?>" /></td>
										<td><input type="number" step="any" min="0" class="form-control input-sm tab-price price-io-<?=$insertion_order->id?>" name="line_item[<?=$i?>][amount]" placeholder="<?=$line_item->amount?>" /></td>
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
		<button id="lines_submit" class="btn btn-default">Update</button>
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
	$('.change-all').val('');
	$('.change-all').change(function() {
		$('.'+$(this).attr('inputtype')+'-io-'+$(this).attr('ioid')).val($(this).val());
	});
	$('.activate-edit').dblclick(function() {
		var input = '<input type="text" class="form-control input-sm tab-desc desc-io-'+$(this).attr('io')+'" name="line_item['+$(this).attr('i')+'][desc]" placeholder="'+$(this).text()+'" />';
		$(this).parent().html(input);
	});
	$('#lines_submit').click(function() {
		$('#updateLinesForm').ajaxSubmit({
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
		return false;
	})
	function setTab(direction) {
		switch (direction) {
			case 'vertical':
				var ti = 1000;
				var fields = ['desc', 'url', 'delivery_units_type', 'budget', 'cap', 'price'];
				$.each(fields, function(x,field) {
					$('.tab-'+field).each(function(i,o){$(o).attr('tabindex', ti);});
					ti++;
				})
				// $('.tab-url').each(function(i,o){$(o).attr('tabindex', i+1000);});
				// $('.tab-price').each(function(i,o){$(o).attr('tabindex', i+$('.tab-url').length+1000);});
				break;
			case 'horizontal':
				$('.tab-desc, .tab-url, .tab-delivery_units_type, .tab-budget, .tab-cap, .tab-price').each(function(i,o){$(o).attr('tabindex', i+1000);});
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