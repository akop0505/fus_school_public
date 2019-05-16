<?php

use app\assets\BotrAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $targetModel app\models\Post */

BotrAsset::register($this);
?>

<h1><?= $targetModel ?> - Video Upload</h1>
<form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label class="control-label">Select video</label>
		<input id="uploadFile" type="file" name="file"> 
		<div id="uploadBar" style="width: 480px; float: left; display: none; background: #FFF; margin: 5px 0;">
			<div id="uploadProgress" style="background: #46800d; width: 0; height: 18px;"></div>
		</div>
		<small id="uploadText"></small>
	</div>
	<div class="form-group">
	<button type="submit" id="uploadButton" class="btn btn-primary">Upload</button>
	</div>
</form>

<?php
$createURL = Url::to(['/profile/create-video']);
$suffix = Yii::$app->urlManager->suffix;
$this->registerJs(
	/** @lang JavaScript */
	<<<JSCLIP

		var filename;
		var data = {};
		data['id'] = "{$targetModel->id}";
		if(BotrUpload.resumeSupported()) data['resumable'] = 'resumable';
		$.get("{$createURL}", data, function(data) {
			if(data.status == "error")
			{
				$("#uploadForm").html("It is currently not possible to upload videos! Please check back later.");
				return;
			}
			// Attach a BotrUpload instance to the form.
			data.link.protocol = 'https';
			var upload = new BotrUpload(data.link, data.session_id, {
				"url": "/post/upload-video{$suffix}",
				params: {
					"r": "post/upload-video",
					"id": "{$targetModel->id}",
					"done": "1",
					"video": data.media.key
				}
			});
			upload.useForm($("#uploadFile").get(0));
			upload.pollInterval = 1000;
			// Create a pause button if resume is available
			var pauseButton;
			if(BotrUpload.resumeSupported())
			{
				pauseButton = $('<button class="btnActive" id="btnPause" style="display: none;">').text('Pause');
				$('#uploadButton').after(pauseButton);
				$("#btnPause").on('click', function() {
					var _this = $(this);
					if(_this.hasClass('btnActive'))
					{
						_this.removeClass('btnActive').text('Resume');
						upload.pause();
					}
					else
					{
						_this.addClass('btnActive').text('Pause');
						upload.start();
					}
					return false;
				});
			}
			else $("body").append(upload.getIframe());
			// When the upload starts, we hide the input, show the progress and disable the button.
			upload.onStart = function() {
				filename = $("#uploadFile").val().split(/[\/\\\\]/).pop();
				$("#uploadFile").css('display', 'none');
				$("#uploadBar").css('display', 'block');
				$("#uploadButton").attr('disabled', 'disabled').prop('disabled', true).hide();
				if(pauseButton) pauseButton.show();
			};

			// During upload, we update both the progress div and the text below it.
			upload.onProgress = function(bytes, total) {
				// Round to one decimal
				var pct = Math.floor(bytes * 1000 / total) / 10;
				$("#uploadProgress").animate({'width': pct + '%'}, 400);
				$("#uploadText").html('Uploading ' + filename + ' (' + pct + '%) ...');
			};
		}, 'json');

JSCLIP
	, $this::POS_READY, 'botr-init');
