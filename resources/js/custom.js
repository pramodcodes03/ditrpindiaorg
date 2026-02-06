
/* admin --->manage institute */
function getStateId(stateId){ $.post('include/classes/ajax.php',{action:'get_institute_list', state_id:stateId}, function(data){ $("#institute_id").html(data);}); }