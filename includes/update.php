<?php

include_once( 'includes/status_messages.php' );

function DisplayUpdate(){
  $status = new StatusMessages();
  
  if ( isset($_POST['UpdateWebUI']) && CSRFValidate() ) {
      exec( '(cd /var/www/html/ && sudo git pull 2>&1)', $update_git );
      $last_line = end($update_git);
      $status->addMessage($last_line, 'info');
  }
  
  if ( isset($_POST['UpdateFirmware']) && CSRFValidate() ) {
      if($_FILES['UpdateFirmware']['error'] != ""){
          $status->addMessage($_FILES['UpdateFirmware']['error'] , 'danger');
      }else{
          //error_reporting(-1);
          $uploaddir = '/var/www/uploads/';
          $uploadfile = $uploaddir . basename($_FILES['UpdateFirmwareFile']['name']);
          if (move_uploaded_file($_FILES['UpdateFirmwareFile']['tmp_name'], $uploadfile)) {
              $status->addMessage('File uploaded - You need to restart the module', 'info');
          } else {
              $status->addMessage('File uploaded error', 'danger');
          }
      }
  }
  
  
  
  ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-upload fa-fw"></i><?php echo _("Update"); ?></div>
        <div class="panel-body">
          <p><?php $status->showMessages(); ?></p>
          <form role="form" action="?page=update" method="POST" enctype="multipart/form-data">
            <?php CSRFToken() ?>
            <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
            <div class="row">
                  <div class="form-group col-md-4">
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

