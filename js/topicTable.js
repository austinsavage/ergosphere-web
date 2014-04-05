$(document).ready(function() {
	$.ajax({ 
	   type: "GET",
	   dataType: "json",
	   url: "api/topics",
	   success: function(data){        
	     
	     var build = "";

	     $.each(data.topics, function(index,value) {

	     	build += "<tr><td>" + value.topic_name + "</td><td>"
	     			 + value.topic_description + "</td></tr>\n";

	     });

	     $("#topic_table tbody").html(build);

	   }
	});
});