var url = "./queensland-fisheries-species-dec14.csv";

jQuery.getJSON(url, function(data){

    var section = document.querySelector('section');
    var output = "";

    console.log(data);
    
    // for (var i in data["result"]){
    //     var id = data["result"]['records'][i]['_id'];
    //     var display_date = data["result"]['records'][i]["Display Date"];
    //     var time = data["result"]['records'][i]["Times(s)"];
    //     var address = data["result"]['records'][i]["Display Address"];
    //     var suburb = data["result"]['records'][i]["Suburb"];
    //     var postcode = data["result"]['records'][i]["PCode"];
    //     var event_type = data["result"]['records'][i]["Event Type"];
    //     var display_type = data["result"]['records'][i]["Display Type"];

    //     output += "<div class = 'blocks'>";
    //     output += "<h2>"+id+"</h2>";
    //     output += "<p>"+display_date+"</p>";
    //     output += "<p>"+time+"</p>";
    //     output += "<p>"+address+"</p>";
    //     output += "<p>"+suburb+"</p>";
    //     output += "<p>"+postcode+"</p>";
    //     output += "<p>"+event_type+"</p>";
    //     output += "<p>"+display_type+"</p>";
    //     output += "</div>";
    // }
    //output += "</table>";
    section.innerHTML = output;

});

