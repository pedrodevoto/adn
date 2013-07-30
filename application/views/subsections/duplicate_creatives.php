<div class="span12">
<form>
	<legend>Duplicar un Creativo</legend>

        <!-- <div class="alert alert-error" style="margin: 20px">Verifica los datos ingresados</div> -->

        <fieldset>
          <div class="control-group">
            <label for="xlInput">Creative ID:</label>
            <div class="controls">
              <input class="span2" name="creativesString" value="">
            </div>
          </div>
          
           <div class="control-group">
            <label for="xlInput">Multiplicar </label>
            <div class="controls">
              <input class="span1" name="multiply" value="1"> veces
            </div>
          </div>
          
            <div class="form-actions">
                <input id="submit" type="submit" class="btn btn-large btn-primary" value="Multiplicar Creativo"
                onclick="if(!confirm('¿Quieres duplicar este Creativo?')){event.preventDefault()}">
            </div>
        </fieldset>
	</form>
</div>
