<div class="span12">
	<form>
        <legend>Asignar account manager a múltiples Publishers</legend>
        
		<!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

        <fieldset>
           
          <div class="control-group">
            <label for="xlInput">IDs de Publishers a asignarle</label>
            <div class="controls">
              <textarea class="span7" id="publishersString" name="publishersString" rows="15" placeholder="separador por comas o enter"></textarea>
              <!-- <span class="help-inline">Error</span> -->
            </div>
          </div>
           <div class="control-group">
                <label for="xlInput">Account manager ID: </label>
                <div class="controls">
                    <select class="input-large" name="contactId">
                        <option value="0">Contacto</option>
                    </select>
					<!-- <span class="help-inline">Error</span> -->
                </div>
            </div>
          
            <div class="form-actions">
                <input id="submit" type="submit" class="btn btn-large btn-primary" value="Asignar Manager" 
                onclick="if(!confirm('¿Asignar los publisher al Manager?')){event.preventDefault()}">
            </div>
        </fieldset>
	</form>
</div>
