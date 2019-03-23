
jQuery(document).ready(function(){
	bpGroupLivechatHeartbeat();
	bpGroupLivechatLoadMessages();
	jQuery(document).everyTime(5000, function() {
		bpGroupLivechatLoadMessages();
		bpGroupLivechatHeartbeat();
	}, 0);
});

function bpGroupLivechatHeartbeat(i) {
	var groupInfo = document.getElementById('group-livechat-info');
	var groupId = groupInfo.dataset.groupid;
	jQuery.post(ajaxurl, { security: ajax_object.check_nonce, bp_group_livechat_online_query: "1", bp_group_livechat_group_id: groupId, action: 'bp_livechat_heartbeat' }, function(data) {
		jQuery('#livechat-users-online').html(data);
	});
}

function bpGroupLivechatsubmitNewMessage(){
	var groupInfo = document.getElementById('group-livechat-info');
	var groupId = groupInfo.dataset.groupid;
	var message_content = jQuery('#bp_group_livechat_textbox').val();
	jQuery.post(ajaxurl, { security: ajax_object.check_nonce, bp_group_livechat_new_message: "1", bp_group_livechat_group_id: groupId, bp_group_livechat_textbox: message_content, action: 'bp_livechat_new_message' }, function() {
		jQuery('#bp_group_livechat_textbox').val('');
		bpGroupLivechatLoadMessages();
	});
}

function bpGroupLivechatLoadMessages() {	
	var groupInfo = document.getElementById('group-livechat-info');
	var groupId = groupInfo.dataset.groupid;
	jQuery.post(ajaxurl, { security: ajax_object.check_nonce, bp_group_livechat_load_messages: "1", bp_group_livechat_group_id: groupId, action: 'bp_livechat_load_messages' }, function(data) {
		jQuery('#bp-livechat-chat-container').html(data);
	});
}
