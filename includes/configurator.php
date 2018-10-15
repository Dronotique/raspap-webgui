<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Configure Auth"); ?></div>
        <div class="panel-body" id="xonomy_editor">
      		<form method="POST" action="?page=configurator" name="conf_form">
      			<div id="connexion1">
      				Serial port [USB-SERIAL CH340 (COM5)] 57600 <input type="submit" name="delete_1"/>
      			</div>
      			<div id="connexion2">
      				Web [localhost] 8080 <input type="submit" name="delete_2"/>
      			</div>
      			<div id="new_connexion">
      				<select name="type">
      					<option value="SERIAL">Serial Port</option>
      					<option value="WEB">Web</option>
      				</select>
      				<div id="serial">
      					<select name="serialPort">
          					<option value="USB-SERIAL CH340 (COM5)">[USB-SERIAL CH340 (COM5)]</option>
          					<option value="ELMO GMAS (COM7)">[ELMO GMAS (COM7)]</option>
          					<option value="USB-SERIAL CH340 (COM6)">[USB-SERIAL CH340 (COM6)]</option>
          					<option value="Lien série sur Bluetooth standard (COM4)">[Lien série sur Bluetooth standard (COM4)]</option>
          				</select>	
          				<select name="serialSpeed">
          					<option value="9600">9600</option>
          					<option value="19200">19200</option>
          					<option value="38400">38400</option>
          					<option value="57600">57600</option>
          					<option value="115200">115200</option>
          				</select>
      				</div>
      				<div id="web">
      					<input type="text" name="host"/>
      					<input type="text" name="port"/>
      				</div>
      			</div>
        	</form>
        </div>
      </div>
    </div>
</div>