<?php
/*
  Copyright FreePBX, Sangoma technologies 2019
  Retrived 26-Aug-2019
  Retrieved from https://git.freepbx.org/projects/FREEPBX/repos/framework/browse/amp_conf/htdocs/admin/views/footer_content.php
  Used under GPLv3 License
*/
global $amp_conf;
$html = '';
$version     = get_framework_version();
if (file_exists('/etc/schmooze/pbx-version')) {
	$pbxversion = trim(file_get_contents('/etc/schmooze/pbx-version'));
	if (!empty($pbxversion)) {
		$version = $pbxversion;
	}
}

//If we have a MIRROR_BRAND_VERSION and it's not latest, display that instead
if (!empty($amp_conf['MIRROR_BRAND_VERSION']) && $amp_conf['MIRROR_BRAND_VERSION'] !== 'latest') {
	$version = trim($amp_conf['MIRROR_BRAND_VERSION']);
}

$version = $version ? $version : getversion();
$version_tag = '?load_version=' . urlencode($version);
if ($amp_conf['FORCE_JS_CSS_IMG_DOWNLOAD']) {
	$this_time_append    = '.' . time();
	$version_tag         .= $this_time_append;
} else {
	$this_time_append = '';
}


// Brandable logos in footer
//fpbx logo
$html .= '<div class="col-md-4">';
$html .= '
	<a target="_blank" href="'
	. $amp_conf['BRAND_IMAGE_FREEPBX_LINK_FOOT']
	. '" >'
	. '<img id="footer_logo1" style="float:right;" src="/admin/modules/tangopbx/assets/images/oem-footer.png" alt="' . $amp_conf['BRAND_FREEPBX_ALT_FOOT'] . '"/>
	</a><br/>
	';

//text

$html .= '</div><div class="col-md-4">';
$html .= '<div id="footer_text">';
$html .= sprintf(_('All trademarks, service marks, trade names, trade dress, product names and logos appearing on the site are the property of their respective owners.')) . br();
$html .= sprintf(_('%s %s is licensed under the %s'), $amp_conf['BRAND_TITLE'], $version, '<a href="http://www.gnu.org/copyleft/gpl.html" target="_blank"> GPL</a>') . br();
$html .= 'Copyright&copy; 2007-' . date('Y', time());

//module license
if (!empty($active_modules[$module_name]['license'])) {
	$html .= br() . sprintf(
		_('Current module licensed under %s'),
		trim($active_modules[$module_name]['license'])
	);
}

//benchmarking
if (isset($amp_conf['DEVEL']) && $amp_conf['DEVEL']) {
	$benchmark_time = number_format(microtime_float() - $benchmark_starttime, 4);
	$html .= '<br><span id="benchmark_time">Page loaded in ' . $benchmark_time . 's</span>';
}
$html .= '</div>';


$html .= '</div><div class="col-md-4">';
$html .= '
	<a target="_blank" href="'
	. $amp_conf['BRAND_IMAGE_FREEPBX_LINK_FOOT']
	. '" >'
	. '<img id="footer_logo1" style="float:left;" src="/admin/modules/tangopbx/assets/images/oem-footer.png" alt="' . $amp_conf['BRAND_FREEPBX_ALT_FOOT'] . '"/>
	</a><br/>';

$html .= '</div>';

echo $html;
