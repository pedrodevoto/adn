<div class="span12">
	<form>
        <legend>Asignar un Segment a todos los Sites de múltiples Publishers</legend>
        <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

        <fieldset>
          <div class="control-group">
            <label for="xlInput">Asignarle a estos Publishers</label>
            <div class="controls">
              <textarea class="span7" id="publishersString" name="publishersString" rows="15" placeholder="separador por comas o enter"></textarea>
              <!-- <span class="help-inline">Error</span> -->
            </div>
          </div>
          
          <div class="control-group ">
                <label for="xlInput">este Segment: </label>
                <div class="controls">
                    <select class="input-xlarge" name="segmentId">
                        <option value="0" >Pexel</option>
                    </select>
                </div>
          </div>
          
            <div class="form-actions">
                <input id="submit" type="submit" class="btn btn-large btn-primary" value="Asignar Segment"
                onclick="if(!confirm('¿Asignar este Segmento a los Publisher?')){event.preventDefault()}">
            </div>
        </fieldset>
	</form>
</div>
