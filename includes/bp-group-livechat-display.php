<?php
if ( !defined( 'ABSPATH' ) ) exit;

global $bp;
if ( !$livechat_display ) die;
?>
<script type="text/javascript"><?php require( dirname( __FILE__ ) . '/jquery-timers-1.2.js' );?></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	bpGroupLivechatHeartbeat();
	bpGroupLivechatLoadMessages();
	jQuery(document).everyTime(5000, function() {
		bpGroupLivechatLoadMessages();
		bpGroupLivechatHeartbeat();
	}, 0);
});

function bpGroupLivechatHeartbeat(i) {
	jQuery.post(ajaxurl, { _ajax_nonce: "<?php echo wp_create_nonce( 'groups_livechat_heartbeat_' . $bp->groups->current_group->id ); ?>", bp_group_livechat_online_query: "1", bp_group_livechat_group_id: "<?php echo $bp->groups->current_group->id; ?>", action: 'bp_livechat_heartbeat' }, function(data) {
		jQuery('#livechat-users-online').html(data);
	});
}

function bpGroupLivechatsubmitNewMessage(){
	var message_content = jQuery('#bp_group_livechat_textbox').val();
	jQuery.post(ajaxurl, { _ajax_nonce: "<?php echo wp_create_nonce( 'groups_livechat_new_message_' . $bp->groups->current_group->id ); ?>", bp_group_livechat_new_message: "1", bp_group_livechat_group_id: "<?php echo $bp->groups->current_group->id; ?>", bp_group_livechat_textbox: message_content, action: 'bp_livechat_new_message' }, function() {
		jQuery('#bp_group_livechat_textbox').val('');
		bpGroupLivechatLoadMessages();
	});
}

function bpGroupLivechatLoadMessages() {	
	jQuery.post(ajaxurl, { _ajax_nonce: "<?php echo wp_create_nonce( 'groups_livechat_load_messages_' . $bp->groups->current_group->id ); ?>", bp_group_livechat_load_messages: "1", bp_group_livechat_group_id: "<?php echo $bp->groups->current_group->id; ?>", action: 'bp_livechat_load_messages' }, function(data) {
		jQuery('#bp-livechat-chat-container').html(data);
	});
}
</script>

<div id="livechat-chat-box" style="width:70%;float:left;">
	<div id="bp-livechat-chat-container" style="border:1px solid silver;height:400px;margin-top:5px;margin-right:5px;padding-left:3px;overflow:scroll;">
	</div>
	<form>
	<input id="bp_group_livechat_textbox" name="bp_group_livechat_textbox" type="text" size="45"/>
	<input type="submit" value="Say" onClick="bpGroupLivechatsubmitNewMessage();return false;"/>
	</form>
</div>

<div id="livechat-users-online-container" style="width:30%;float:right;">
	<h5><?php echo sanitize_text_field( __( 'Users in the Chat Room', 'bp-group-livechat' ) ); ?></h5>
	<ul id="livechat-users-online" class="item-list-chat" role="main">
	</ul>
</div>