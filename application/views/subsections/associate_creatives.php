<div class="span12">
<form>        
	<legend>Asociar/Desasociar Creativos</legend>

        <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

        <fieldset>
          <div class="control-group">
            <label for="xlInput">Line ID:</label>
            <div class="controls">
              <input class="span2" name="lineId" value="">
            </div>
          </div>
          
          <div class="control-group">
            <label for="xlInput">Creative IDs:</label>
            <div class="controls">
              <textarea class="span7" id="creativesString" name="creativesString" rows="15" placeholder="separador por comas o enter"></textarea>
              <!-- <span class="help-inline">Error</span> -->
            </div>
          </div>
          
            <div class="form-actions">
                <input id="submit" type="submit" class="btn btn-large btn-primary" name="action_asociar" value="Asociar Creativos" 
                onclick="if(!confirm('¿Confirmas Asociar los creativos?')){event.preventDefault()}">
                 <input id="submit" type="submit" class="btn btn-large btn-primary" name="action_desasociar" value="Desasociar Creativos" 
                onclick="if(!confirm('¿Confirmas DESAsociar los creativos?')){event.preventDefault()}">
            </div>
        </fieldset>
	</form>
</div>

