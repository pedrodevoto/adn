<div class="span12">
	<form>
	    <legend>Create TestTag</legend>

	    <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

	    <fieldset>
			<div class="span8 control-group offset1">
				<label for="insertionOrderId">Insertion order:</label>
	        	<div class="controls">
					<input id="insertionOrderId" name="insertionOrderId" type="text" value="" />
					<input type="button" value="Buscar pixels" class="btn btn-mini" id="searchPixels"/>
	        	</div>
	      	</div>
	      	<div class="span8 control-group offset1">
				<label for="insertionOrder">Pixel:</label>
	        	<div class="controls">
					<select id="pixelId" name="pixelId" disabled="true">
						<option value="">Escribe el Insertion Order y pulsa en Buscar Pixels</option>
					</select>
	        	</div>
	      	</div>
	        <div class="span12 form-actions">
				<input type="submit" class="btn btn-primary" value="Crear TestTag" id="save-button" disabled="true">
	        </div>
	    </fieldset>
	</form>
</div>
<script>
	$("#searchPixels").click(function(e){
		$(this).val("Cargando...");
		$(this).addClass("disabled");
		$("#pixelId").prop('disabled', true);
		var insertionOrderId = $("#insertionOrderId").val();
		$.ajax({
		   	type: "GET",
		   	url: "@{Dashboard.getPixels}",
		   	data: "insertionOrderId="+insertionOrderId,
			success: function(data, textStatus){
			    var options = $("#pixelId");
			    options.empty();

			    $.each(data, function(i,item) {
			        options.append($("<option />").val(item.id).text(item.name + " ("+item.id+")"));
    			});
    			$("#searchPixels").val("Buscar pixels");
				$("#searchPixels").removeClass("disabled");
				$("#pixelId").prop('disabled', false);
				$("#save-button").prop('disabled', false);
			},
   			error: function(textStatus, errorThrown) {
    			$("#searchPixels").val("Buscar pixels");
				$("#searchPixels").removeClass("disabled");
				alert("Error obteniendo los pixels");
		   	}
		});
	
	});
</script>