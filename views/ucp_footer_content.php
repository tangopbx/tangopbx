<?php
$f = FreePBX::create();
$brand = $f->Config->get('DASHBOARD_FREEPBX_BRAND');
$path = $f->Config->get('BRAND_CSS_CUSTOM');
$link = $f->Config->get('BRAND_IMAGE_FREEPBX_LINK_FOOT');
$dirname = dirname($path, 2);
$footerImage = '/admin/' . $dirname . '/images/oem-footer.png';
$copyright = sprintf('<a href = "%s" height="65">' . _('Copyright &copy; 2013-%s %s') . '</a>',$link, $year, $brand);
?>

<div id="footer-message">
	<?php echo $copyright ?><br/>
</div>
