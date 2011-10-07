<?php 
/*
Copyright 2010 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

require_once("ui.inc");
require_once("utils.inc");

$gArchive = "All";
$gLabel = getParam('l', latestLabel($gArchive));
$gTitle = "Interesting Stats";
$gSlice = getParam('s', 'All');
?>
<!doctype html>
<html>
<head>
<title><?php echo genTitle($gTitle) ?></title>
<meta charset="UTF-8">

<?php echo headfirst() ?>
<link type="text/css" rel="stylesheet" href="style.css" />
<style>
.chart { 
	border: 1px solid #BBB; 
	padding: 4px; 
	margin-bottom: 40px; }
</style>
</head>

<body>

<?php echo uiHeader($gTitle); ?>

<h1>Interesting stats</h1>

<p>
Got a stat you'd like to see?
<a href="http://code.google.com/p/httparchive/issues/entry?summary=New+Interesting+Stat&comment=Here%27s%20an%20interesting%20stat%20I%27d%20like%20to%20see%3A" target="_blank">Suggest it!</a>
<span style="margin-left: 20px;">New feature: <a href="compare.php">Compare two runs</a></span>
</p>

<div style="float: left; margin-right: 20px;">
<form>
	<label>Choose a run:</label>
	<?php echo selectArchiveLabel($gArchive, $gLabel); ?>
</form>
</div>

<form>
	<label>Choose URLs:</label>
<?php
echo selectSlice($gSlice, "onchange='document.location=\"?a=$archive&l=$gLabel&s=\"+escape(this.options[this.selectedIndex].value)'");
?>
</form>

<div id=interesting style="margin-top: 40px;">
<?php
$gbHTML = true;
require_once("interesting-images.js");
?>
</div>


<?php echo uiFooter() ?>

</body>

</html>

