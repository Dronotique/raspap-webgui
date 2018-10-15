<?php 
function DisplayConnexionConfig() {
    $xmlConfFile = simplexml_load_file("configuration.xml");
    $availableCnx = $xmlConfFile->children('connexions', TRUE);
    
    echo ($availableCnx->asXML());
    
    $currentConnexions = array ();
    $i=0;
    foreach ($availableCnx->children() as $cnx) {
        echo(1);
        if($cnx->xpath('type') == "MAVLINK_SERIAL"){
            echo(2);
            $currentConnexions[$i] = array ("type" => $cnx->children('type'), "port" => $cnx->children('attributes/serial_port_com'), "speed" => $cnx->children('attributes/serial_speed_com'));
        }else if($cnx->xpath('type') == "MAVLINK_UDP"){
            echo(3);
            $currentConnexions[$i] = array ("type" => $cnx->children('type'), "host" => $cnx->children('attributes/host'), "port" => $cnx->children('attributes/port'));
        }else{
            echo(4);
            $currentConnexions[$i] = $cnx->children();
        }
        $i++;
    }
    echo(5);
    if(isset($_POST["delete"])){
        $cameraList = $xmlConfFile->children('cameras');
        $serialList = $xmlConfFile->children('availableSerial');
       //On réécrit le fichier 
        $newXmlConf = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><configurator/>");
        $root = $newXmlConf->children('/configurator');
        $root.addChild("cameras").addChild($cameraList);
        $root.addChild("availableSerial").addChild($serialList);
        $connexions = $root.addChild("connexions");
        
        for($i=0; $i < sizeof($currentConnexions);$i++){
            
            if($_POST["post"]!=$i){
                $newCnx = $connexions->addChild("connexions");
                $newCnx->addChild("type", $currentConnexions[$i]["type"]);
                if($currentConnexions[$i]["type"] == "MAVLINK_SERIAL"){
                    $attrs = $newCnx->addChild("attributes", "", "xsi:type=\"connexionConfAttrSerial\"");
                    $attrs->addChild("serial_port_com",  $currentConnexions[$i]["port"]);
                    $attrs->addChild("serial_speed_com",  $currentConnexions[$i]["speed"]);
                }else if($currentConnexions[$i]["type"] == "MAVLINK_UDP"){
                    $attrs = $newCnx->addChild("attributes", "", "xsi:type=\"connexionConfAttrWeb\"");
                    $attrs->addChild("host",  $currentConnexions[$i]["host"]);
                    $attrs->addChild("port",  $currentConnexions[$i]["port"]);
                }
            }
        }
        
        $newXmlConf->asXml(configuration2.xml);
    }
?>
<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Configure Connexions"); ?></div>
        <div class="panel-body" id="xonomy_editor">
      			<table class="table table-responsive table-striped">
                      <tr>
                        <th><?php echo _("Type"); ?></th>
                        <th><?php echo _("Value1"); ?></th>
                        <th><?php echo _("Value2"); ?></th>
                        <th><?php echo _(""); ?></th>
                        <th></th>
                      </tr>
<?php 
for($i=0; $i < sizeof($currentConnexions);$i++){
?>
                      <tr>
                		<td>
<?php 
if($currentConnexions[$i]["type"] == "MAVLINK_SERIAL"){
   echo("Serial Port");
}else if($currentConnexions[$i]["type"] == "MAVLINK_UDP"){
    echo("Web");
}
?>
                		</td>
                		<td>
                			<?php echo($currentConnexions[$i][1]); ?>	
                		</td>
                		<td>
                			<?php echo($currentConnexions[$i][2]); ?>
                		</td>
                		<td>
                			<form method="POST" action="?page=configurator" name="conf_form1">
      							<input type="hidden" value"1" name="post"/>
                				<input type="submit" name="delete" label="Delete" class="col-md-6 btn btn-warning"/>
                			</form>
                		</td>
                	</tr>
<?php 
}
?>
	               	<tr>
                		<form method="POST" action="?page=configurator" name="conf_form_add">
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
                    			<input type="submit" name="add" label="Add" class="col-md-6 btn btn-info"/>
                    		</td>
                    	</form>
                  	</tr>
        	</form>
        </div>
      </div>
    </div>
</div>
<?php 
}
?>