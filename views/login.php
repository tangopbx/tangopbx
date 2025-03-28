<?php if ($errors) { ?>
	<span class="obe_error">
		<?php echo _('Please correct the following errors:') ?>
		<?php echo ul($errors); ?>
	</span>
<?php } ?>
<div id="login_form">
	<form id="loginform" method="post" role="form">
		<h3><?php echo _('To get started, please enter your credentials:') ?></h3>
		<div class="form-group">
			<input type="text" name="username" class="form-control" value="" placeholder="username" autocomplete="off">
		</div>
		<div class="form-group">
			<input type="password" name="password" class="form-control" value="" placeholder="password" autocomplete="off">
		</div>
	</form>
</div>
<div id="login_icon_holder">
	<div class="login_item_title">
		<a href="#" class="login_item" id="login_admin" style="background-image: url(assets/tangopbx/images/pbx-admin.png);"></a>
		<span class="login_item_text" >
			<?php echo _('PBX Admin') ?>
		</span>
	</div>
	<div class="login_item_title">
		<a href="/ucp" class="login_item" id="login_ari" style="background-image: url(assets/tangopbx/images/user-control.png);" />&nbsp;</a>
		<span class="login_item_text" >
			<?php echo _('User Control Panel') ?>
		</span>
	</div>
	<div class="login_item_title">
		<a href="<?php echo $amp_conf['BRAND_IMAGE_FREEPBX_LINK_LEFT']; ?>" class="login_item" id="login_support" style="background-image: url(assets/tangopbx/images/support.png);" />&nbsp;</a>
		<span class="login_item_text" >
			<?php echo _('Get Support') ?>
		</span>
	</div>
	<br />
	<br />
	<br />
	<br />
	<br />
	<div id="key" style="color: white;font-size:small">
		<?php echo session_id(); ?>
	</div>
</div>
<script type="text/javascript" src="assets/js/views/login.js"></script>
