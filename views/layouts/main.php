<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Channel;
use app\models\DiscoverChannel;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\select2\Select2Asset;
use yii\helpers\Url;

AppAsset::register($this);
Select2Asset::register($this);
$discoverChannels = DiscoverChannel::find()->joinWith('channel')->where(['isActive' => 1, 'hasPhoto' => 1])->orderBy('sort asc')->limit(5)->all();
$urlMustSee = Url::toRoute(['site/channel', 'id' => Channel::CHANNEL_HOME_MUST_SEE]);
$urlLatest = Url::toRoute(['site/channel', 'id' => Channel::CHANNEL_HOME_LATEST]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<?= Html::csrfMetaTags() ?>
	<!-- start:charset -->
	<meta charset="<?= Yii::$app->charset ?>">
	<!-- end:charset -->

	<!-- start:latest IE rendering engine -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- end:latest IE rendering engine -->

	<!-- start:viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
	<!-- end:viewport -->

	<!-- start:page title -->
	<title><?= Html::encode($this->title, false) ?></title>
	<!-- end:page title -->

    <?php $this->head() ?>

	<!-- start:favicon -->
	<link rel="shortcut icon" href="/images/favicons/favicon.ico">
	<!-- end:favicon -->

	<!-- start:icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/16x16.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/images/favicons/96x96.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/images/favicons/192x192.png">
	<!-- end:icon -->

	<!-- start:apple touch image -->
	<link rel="apple-touch-icon" sizes="57x57" href="/images/favicons/57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/images/favicons/60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/images/favicons/72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/images/favicons/76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/images/favicons/114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/images/favicons/120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/images/favicons/144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/images/favicons/152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/180x180.png">
	<!-- end:apple touch image -->

	<!-- Start of fusfoo Zendesk Widget script -->
	<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var e=this.createElement("script");n&&(this.domain=n),e.id="js-iframe-async",e.src="https://assets.zendesk.com/embeddable_framework/main.js",this.t=+new Date,this.zendeskHost="fusfoo.zendesk.com",this.zEQueue=a,this.body.appendChild(e)},o.write('<body onload="document._l();">'),o.close()}();
/*]]>*/</script>
<!-- End of fusfoo Zendesk Widget script -->
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '1611960092454609'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=1611960092454609&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
</head>

<body class="<?= isset($this->params['class']) ? $this->params['class'] : ''; ?>">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-115491674-1', 'auto');
  ga('send', 'pageview');

</script>
<?php $this->beginBody() ?>

<!--[if lt IE 9]>
<p class="browsehappy">
	You are using an <strong>outdated</strong> browser.
	Please <a href="http://www.browsehappy.com/">upgrade your browser</a> to improve your experience.
</p>
<![endif]-->

<?= Breadcrumbs::widget([
	'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
])
?>
<?= $content ?>


<!-- start:footer -->
<footer id="footer">

	<!-- start:top -->
	<div class="top">

		<!-- start:cnt -->
		<div class="cnt">

			<!-- start:cols -->
			<div class="cols five clr">

				<!-- start:col -->
				<section class="col">

					<h4>Fusfoo</h4>

					<ul>
						<li>
							<a href="<?= Url::toRoute(['site/content', 'contentType' => 'about']); ?>">About Fusfoo</a>
						</li>
						<li>
							<a href="<?= Url::to(['site/resources']); ?>">Learn More</a>
						</li>
						<li>
							<a href="<?= Url::toRoute(['site/content', 'contentType' => 'contact']); ?>">Contact us</a>
						</li>
					</ul>

				</section>
				<!-- end:col -->

				<!-- start:col -->
				<section class="col">

					<h4>Discover</h4>

					<ul>
						<li>
							<a href="<?= $urlLatest ?>">Latest</a>
						</li>
						<li>
							<a href="<?= $urlMustSee ?>">Must See</a>
						</li>
						<?php foreach($discoverChannels as $one): ?>
							<li>
								<a href="<?= Url::to(['site/channel', 'id' => $one->channelId]); ?>"><?= $one->channel->name; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>

				</section>
				<!-- end:col -->

				<!-- start:col -->
				<section class="col">

					<h4>Connect</h4>

					<ul>
						<li>
							<a href="https://www.facebook.com/fusfoo/" target="_blank">Facebook</a>
						</li>
						<li>
							<a href="https://twitter.com/fusfoo/" target="_blank">Twitter</a>
						</li>
						<li>
							<a href="https://www.instagram.com/fusfoo/" target="_blank">Instagram</a>
						</li>
					</ul>

				</section>
				<!-- end:col -->

				<!-- start:col -->
				<section class="col">

					<h4>Help Center</h4>

					<ul>
						<li>
							<a href="https://fusfoo.zendesk.com/hc/en-us" target="_blank">Help Center</a>
						</li>
						<li>
							<a href="https://fusfoo.zendesk.com/hc/en-us/categories/203544187-Managing-your-Account" target="_blank">Fusfoo Basics</a>
						</li>
						<li>
							<a href="https://fusfoo.zendesk.com/hc/en-us/categories/203544207-Sharing-and-embedding" target="_blank">Video Tutorial</a>
						</li>
					</ul>

				</section>
				<!-- end:col -->

				<!-- start:col -->
				<section class="col">

					<h4>Fusfoo Media</h4>

					<p>
						111 East 14th Street #135<br>
						New York, NY 10003<br>
						info@fusfoo.com
					</p>

				</section>
				<!-- end:col -->

			</div>
			<!-- end:cols -->

		</div>
		<!-- end:cnt -->

	</div>
	<!-- end:top -->

	<!-- start:bottom -->
	<div class="bottom">

		<!-- start:cnt -->
		<div class="cnt">

			<ul class="clr">
				<li>
					&copy; Fusfoo, LLC. All rights reserved.
				</li>
				<li>
					<a href="<?= Url::toRoute(['site/content', 'contentType' => 'terms']); ?>">Terms of Use</a>
				</li>
				<li>
					<a href="<?= Url::toRoute(['site/content', 'contentType' => 'privacy-policy']); ?>">Privacy Policy</a>
				</li>
				<li>
					<a href="<?= Url::toRoute(['site/content', 'contentType' => 'dmca']); ?>">DMCA Policy</a>
				</li>
			</ul>

		</div>
		<!-- end:cnt -->

	</div>
	<!-- end:bottom -->

</footer>
<!-- end:footer -->

<!-- start:drawer -->
<?= $this->render('/site/drawer'); ?>
<!-- end:drawer -->

<?php
$style = "";
if(!Yii::$app->user->isGuest || Yii::$app->request->cookies->getValue('_learnMore', 0) > time()) $style = "display: none;";
else
{
	$cookies = Yii::$app->response->cookies;
	$cookies->add(new \yii\web\Cookie([
		'name' => '_learnMore',
		'value' => time() + 2592000,
		'expire' => time() + 2600000,
	]));
}

?>

<?php $this->endBody() ?>

<script src='//cdn.zarget.com/115426/213549.js'></script>

<!-- Twitter universal website tag code -->
<script>
!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
// Insert Twitter Pixel ID and Standard Event data below
twq('init','nw1kh');
twq('track','PageView');
</script>

<!-- End Twitter universal website tag code -->
</body>
</html>
<?php $this->endPage() ?>
