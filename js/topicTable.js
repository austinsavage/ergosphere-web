$(document).ready(function() {

	$.ajax({ 
	   type: "GET",
	   dataType: "json",
	   url: "api/topics",
	   success: function(data){        
	     
	     var build = "";

	     $.each(data.topics, function(index,value) {

	     	build += "<div class='col-md-4'>";
          	build += "<h2>" + value.topic_name + "</h2>";
          	build += "<p>" + value.topic_description + "</p>";
          	build += "<p><a class='btn btn-default' href='view-topic.html?id=" + value.topic_id + "' role='button'>Study Now! »</a></p>";
        	build += "</div>";

	     });

	     var html = $("div#topics").html();

	     $("div#topics").html(build + html);

	   }
	});
});