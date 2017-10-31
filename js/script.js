function getMatchInfo() {
	var key = "EC70849F4F195F151784D729025C9960";
	var id = $("#input").val();

	url = "https://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/V001/?match_id="+id+"&key="+key+"";
	    $.ajax({
	    	method: "POST",
	    	url: "ajax.php",
	    	data: {
	    	id: id,
	    	url: url },
	    	success: function(data) {
	 			$("#match").html(data);
	    	}
	    });
	//});
}

function addComment(id) {
	var name = $("#name").val();
	var comment = $("#comment").val();
	$.ajax({
		method: "POST",
		url: "comments.php",
		data: {
		id: id, 
		comment: comment, 
		name: name },
		success: function(data){
			$("#comments").html(data);
		}	
	});
}

$(document).ready(function(){
    $("#matchList").DataTable({
    	"order":[[0, "desc"]]
    });
});