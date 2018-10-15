<?php 
function DisplayConnexionConfig() {
?>
<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Configure COnnexions"); ?></div>
        <div class="panel-body" id="xonomy_editor">
      		<form method="POST" action="?page=configurator" name="conf_form">
      			 <table class="table table-responsive table-striped">
                      <tr>
                        <th><?php echo _("Type"); ?></th>
                        <th><?php echo _("Value1"); ?></th>
                        <th><?php echo _("Value2"); ?></th>
                        <th><?php echo _(""); ?></th>
                        <th></th>
                      </tr>
                      <tr>
                		<td>
                			Serial port
                		</td>
                		<td>
                			USB-SERIAL CH340 (COM5)
                		</td>
                		<td>
                			57600
                		</td>
                		<td>
                			<input type="submit" name="delete_1" label="Delete" class="col-md-6 btn btn-warning"/>
                		</td>
                	</tr>
                	<tr>
                		<td>
                			Web
                		</td>
                		<td>
                			localhost
                		</td>
                		<td>
                			8080
                		</td>
                		<td>
                			<input type="submit" name="delete_2" label="Delete" class="col-md-6 btn btn-warning"/>
                		</td>
                	</tr>
                	<tr>
                		<td>
                			<select name="type"  class="form-control">
              					<option value="SERIAL">Serial Port</option>
              					<option value="WEB">Web</option>
              				</select>
                		</td>
                		<td>
                			<select name="serialPort" id="serialPort"  class="form-control">
              					<option value="USB-SERIAL CH340 (COM5)">[USB-SERIAL CH340 (COM5)]</option>
              					<option value="ELMO GMAS (COM7)">[ELMO GMAS (COM7)]</option>
              					<option value="USB-SERIAL CH340 (COM6)">[USB-SERIAL CH340 (COM6)]</option>
              					<option value="Lien série sur Bluetooth standard (COM4)">[Lien série sur Bluetooth standard (COM4)]</option>
              				</select>
              				<input type="text" name="host" id="host"  class="form-control" />
                		</td>
                		<td>
                			<select name="serialSpeed" id="serialSpeed"  class="form-control">
              					<option value="9600">9600</option>
              					<option value="19200">19200</option>
              					<option value="38400">38400</option>
              					<option value="57600">57600</option>
              					<option value="115200">115200</option>
              				</select>
              				<input type="text" name="hostPort" id="hostPort"  class="form-control" />
                		</td>
                		<td>
                			<input type="submit" name="add" label="Delete" class="col-md-6 btn btn-info"/>
                		</td>
                	</tr>
        	</form>
        </div>
      </div>
    </div>
</div>
<?php 
}
?>