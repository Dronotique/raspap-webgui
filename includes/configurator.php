<?php 
function DisplayConnexionConfig() {
    $xmlConfFile = simplexml_load_file("configuration.xml");
    $availableCnx = $xmlConfFile->children('connexions', TRUE);

    $currentCameras = array ();
    $i=0;
    foreach ($xmlConfFile->cameras->cameras as $camera) {
        $currentCameras[$i] = $camera;
        $i++;
    }
    
    $currentSerial = array ();
    $i=0;
    foreach ($xmlConfFile->availableSerial->availableSerial as $serial) {
        $currentSerial[$i] = $serial;
        $i++;
    }
    
    $currentConnexions = array ();
    $i=0;
    foreach ($xmlConfFile->connexions->connexions as $cnx) {
        if($cnx->type == "MAVLINK_SERIAL"){
            $currentConnexions[$i] = array ("type" => $cnx->type, "port" => $cnx->attributes->serial_port_com, "speed" => $cnx->attributes->serial_speed_com);
        }else if($cnx->type == "MAVLINK_UDP"){
            $currentConnexions[$i] = array ("type" => $cnx->type, "host" => $cnx->attributes->host, "port" => $cnx->attributes->port);
        }else{
            $currentConnexions[$i] = $cnx->children();
        }
        $i++;
    }
    if(isset($_POST["deleteCnx"])){
        $cameraList = $xmlConfFile->children('cameras');
        $serialList = $xmlConfFile->children('availableSerial');
       //On réécrit le fichier 
        $newXmlConf = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><configurator/>");
        //$root = $newXmlConf->children('/configurator');
        $xmlCameras = $newXmlConf->addChild("cameras");
        for ($i=0; $i < sizeof($currentCameras);$i++){
            $xmlCameras->addChild("cameras", $currentCameras[$i]);
        }
        
        $xmlSerials = $newXmlConf->addChild("availableSerial");
        for ($i=0; $i < sizeof($currentSerial);$i++){
            $xmlSerials->addChild("availableSerial", $currentSerial[$i]);
        }

        $connexions = $newXmlConf->addChild("connexions");
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
<?php 
if($currentConnexions[$i]["type"] == "MAVLINK_SERIAL"){
    echo("<td>Serial Port</td><td>".$currentConnexions[$i]['port'] . "</td><td>" . $currentConnexions[$i]['speed'] . "</td>");
}else if($currentConnexions[$i]["type"] == "MAVLINK_UDP"){
    echo("<td>Web</td><td>".$currentConnexions[$i]['host'] . "</td><td>" . $currentConnexions[$i]['port'] . "</td>");
}
?>
                		<td>
                			<form method="POST" action="?page=configurator" name="conf_form1">
      							<input type="hidden" value"1" name="post"/>
                				<input type="submit" name="deleteCnx" label="Delete" class="col-md-6 btn btn-warning"/>
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
<?php 
                            for($i=0; $i < sizeof($currentSerial);$i++){
                                echo("<option value=\"" . $i . "\">" . $currentSerial[$i] . "</option>");
                            }
?>
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