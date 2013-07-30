<div class="span12">
	<form>
	    <legend>Excluir Publishers</legend>

	    <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

	    <fieldset>
	    	<div class="span9">
				 <div class="control-group">
		            <label for="lineIds">Line IDs:</label>
		            <div class="controls">
		              <input class="span7" name="lineIds" id="lineIds" placeholder="Separados por comas o enter" >
		            </div>
		          </div>
	          
		          <div class="control-group">
		            <label for="publisherIds">Publisher Ids:</label>
		            <div class="controls">
		              <textarea class="span7" id="publisherIds" name="publisherIds" rows="15" placeholder="Separados por comas o enter"></textarea>
		              <!-- <span class="help-inline">Error</span> -->
		            </div>
		          </div>
	        </div>
		    <div class="span9 form-actions">
				<input type="submit" class="btn btn-primary" name="exclude" value="Excluir">
				<input type="submit" class="btn btn-info" name="include" value="Incluir" style="margin-left:20px;">
		    </div>
	    </fieldset>
	</form>
</div>