<?php
function tangopbx_module_repo_parameters_callback($path)
{
	$config = FreePBX::Config();
	if (strpos($path, 'xml') !== false) {
		return array(
			'mirrorproxy_id' => $config->get('MIRROR_BRAND'),
			'mirrorproxy_ver' => $config->get('MIRROR_BRAND_VERSION'),
		);
	}
}
