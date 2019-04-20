<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !$livechat_display ) die;

?>


<div id="livechat-chat-box">
	<div id="bp-livechat-chat-container" >
	</div>
	<form>
	<input type="hidden" id="group-livechat-info" data-groupid="<?php echo bp_get_group_id(); ?>"/>
	<input id="bp_group_livechat_textbox" name="bp_group_livechat_textbox" type="text" size="45"/>
	<input type="submit" value="Say" onClick="bpGroupLivechatsubmitNewMessage();return false;"/>
	</form>
</div>

<div id="livechat-users-online-container" >
	<h5><?php echo sanitize_text_field( __( 'Users in the Chat Room', 'bp-group-livechat' ) ); ?></h5>
	<ul id="livechat-users-online" class="item-list-chat" role="main">
	</ul>
</div>
