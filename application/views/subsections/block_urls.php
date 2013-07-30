<div class="span12">
	<form>
        <legend>Bloquear URLs</legend>

        <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

        <fieldset>
          <div class="control-group">
            <label for="xlInput">Line IDs:</label>
            <div class="controls">
              <input class="span7" name="lineIds" value="" placeholder="Separados por comas o enter">
            </div>
          </div>
          
          <div class="control-group">
            <label for="xlInput">URLs:</label>
            <div class="controls">
              <textarea class="span7" id="creativesString" name="urls" rows="15" placeholder="Separados por comas o enter"></textarea>
              <!-- <span class="help-inline">Error</span> -->
            </div>
          </div>
          
          <div class="control-group ">
            <label for="xlInput">Eliminar anteriores </label>
            <div class="controls">
              <input type="checkbox" name="deletePrevious" value="deletePrevious"></input>
            </div>
          </div>
          
            <div class="form-actions">
                <input id="submit" type="submit" class="btn btn-large btn-primary" name="action_bloquear" value="Bloquear URLs" 
                onclick="if(!confirm('¿Confirmas bloquear las URLs?')){event.preventDefault()}">
            </div>
        </fieldset>
	</form>
</div>
