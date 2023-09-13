<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Fb_Pixel_Capi
 * @subpackage Ech_Fb_Pixel_Capi/admin/partials
 */

?>
<div class="echPlg_wrap">
  <h1>FB Pixel and Conversation API General Settings</h1>
  <div class="form_container">
    <form method="post" id="fbpcapi_gen_settings_form">
    <?php 
        settings_fields( 'fbpcapi_gen_settings' );
        do_settings_sections( 'fbpcapi_gen_settings' );
    ?>
      <h2>General Settings</h2>
      <div class="form_row">
          <label>Pixel id: </label>
          <input type="text" name="ech_fbpcapi_pixel_id" value="<?= htmlspecialchars(get_option( 'ech_fbpcapi_pixel_id' ))?>" id="" />
      </div>
      <div class="form_row">
          <label>FB Access Token: </label>
          <input type="text" name="ech_fbpcapi_fb_access_token" value="<?= htmlspecialchars(get_option( 'ech_fbpcapi_fb_access_token' ))?>" id="" />
      </div>
      <div class="form_row">
          <button type="submit"> Save </button>
      </div>
    </form>
    <div class="statusMsg"></div>
  </div> <!-- form_container -->
</div>
