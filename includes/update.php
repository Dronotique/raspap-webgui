<?php

include_once( 'includes/status_messages.php' );

function DisplayUpdate(){
  $status = new StatusMessages();
  
  if ( isset($_POST['UpdateWebUI']) && CSRFValidate() ) {
      exec( '(cd /var/www/html/ && sudo git pull)', $update_git );
      echo("<!--  $update_git -->");
  } 
  
  
  ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i><?php echo _("Update"); ?></div>
        <div class="panel-body">
          <p><?php $status->showMessages(); ?></p>
          <form role="form" action="?page=update" method="POST">
            <?php CSRFToken() ?>
            <div class="row">
                  <div class="form-group col-md-4">
                  totototototototo
            		<input type="submit" class="btn btn-outline btn-primary" name="UpdateWebUI" value="<?php echo _("Update Web UI"); ?>" />
            	</div>	
            </div>
            <div class="row">
                  <div class="form-group col-md-4">
                    <label for="updatefirmwarefile"><?php echo _("Firmware file"); ?></label>
                    <input type="file"  class="form-control" id="updatefirmwarefile" name="UpdateFirmwareFile">
                    <input type="submit" class="btn btn-outline btn-primary" name="UpdateFirmware" value="<?php echo _("Update Firmware"); ?>" />
                   </div>
            </div>
          </form>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
<?php 
}

