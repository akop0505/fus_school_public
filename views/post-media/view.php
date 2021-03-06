<?php

use app\assets\DropZoneAsset;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\PostMedia */
/* @var $post app\models\Post */
/* @var $fileSize array */
/* @var $mediaDir string */

DropZoneAsset::register($this);
$uploadPath = Url::toRoute(['/post-media/upload']);
$removePath = Url::toRoute(['/post-media/delete-one']);
?>
<div class="post-media-view">

	<?= Breadcrumbs::widget([
		'homeLink' => false,
		'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	]) ?>

	<form action="<?= $uploadPath; ?>" class="dropzone" id="dropzoneMedia" enctype="multipart/form-data">
		<div class="fallback">
			<input name="file" type="file" multiple />
		</div>
		<input name="postId" id="postId" type="hidden" value="<?= $post->id; ?>" />
		<input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>">
	</form>
	<br>
	<div class="form-group">
		<button type="button" id="uploadMedia" class="btn btn-primary btn-md">Upload</button>
		<a href="<?= Yii::$app->request->referrer ?>"><?= Yii::t('app', 'Back') ?></a>
	</div>
</div>
<?php

$jsExistingMedia = '';
$sortMax = 0;
if(!empty($postMedia))
{
	foreach($postMedia as $i => $media)
	{
		if($media->sort > $sortMax) $sortMax = $media->sort;
		$jsExistingMedia .= '
			mockFile = {name: "' . $media->filename . '", size: ' . $fileSize[$i] . ', accepted: true, sortHelper: '. $media->sort .'};
			myDropzone.emit("addedfile", mockFile);
			myDropzone.emit("thumbnail", mockFile, "' . $mediaDir . $post->getThumbFilename($media->filename) . '");
			myDropzone.emit("complete", mockFile);
			myDropzone.files.push(mockFile);';
	}
}
$this->registerJs(<<<JSCLIP
	Dropzone.autoDiscover = false;

	var maxSortCurrent = $sortMax;
	var myDropzone = new Dropzone("#dropzoneMedia",
	{
		maxFilesize: 2, // MB,
		autoProcessQueue: false,
		addRemoveLinks: true,
		acceptedFiles: '.jpg,.png',
		dictDefaultMessage: 'Drag picture.',
		dictRemoveFile: 'Remove',
		dictInvalidFileType: 'Incorrect file type',
		
		dictFileTooBig: 'File to big ({{filesize}}MB). Max: {{maxFilesize}}MB.',
  		init: function () {
			this.on("removedfile", function(file) {
				if(file.status != Dropzone.QUEUED && file.accepted === true && file.status != Dropzone.ERROR)
				{
					$.ajax({
						type: 'POST',
						url: '$removePath',
						data: {
							filename: file.name,
							postId: $('#postId').val()
						}
					});
				}
			});
			this.on("addedfile", function(file) {
				file.sortHelper = this.files.length;
			});
			this.on('sending', function(file, xhr, formData) {
				if(file.sortHelper <= maxSortCurrent) file.sortHelper = ++maxSortCurrent;
				else maxSortCurrent = file.sortHelper;
				formData.append('fileSort', maxSortCurrent);
        	});
        	this.on('success', function(file) {
        		myDropzone.processQueue();
        	});
		}
	});

	var mockFile;
	$jsExistingMedia

	$('#uploadMedia').click(function(){
		 myDropzone.processQueue();
	});


JSCLIP
, $this::POS_READY, 'redactor-init');
?>
