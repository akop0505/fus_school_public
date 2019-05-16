<div class="modal fade" id="avatarModal" style="display: none">
    <div class="modal-dialog">
      <div class="modal-content">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <h4 class="modal-title">Select Avatar</h4>
	</div>
	<div class="modal-body">
	  <p id='modal-body-message'>
	      <select id='userAvatar' class="image-picker show-html form-control" name='userAvatar'>
		<?php foreach($avatars as $avatar):?>
		    <option <?php if($avatar==$model->id) echo "selected"; ?> data-img-src="<?php echo $avatarLink.$avatar; ?>" value="<?php echo $avatar;?>"></option>
		<?php endforeach;?>
		</select>
	  </p>
	</div>
	<div class="modal-footer">
	  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	  <button type="button" id="selectAvatarOk" class="btn btn-primary" data-dismiss="modal">OK</button>
	</div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
