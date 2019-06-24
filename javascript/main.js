/**
 * Find service link
 * @returns {String}
 */
var appId = "appId";
var apiUrl = "url";

function getService(){
    var protocol = location.protocol;
    var hostname = location.hostname;
    var port = location.port ? ":" + location.port : "";
    return protocol + "//" + hostname + port;
//    return "/";
};


function sendRequest(type, url, request, dataType, successFunction){
    $.ajax({
        type: type,
        url: url,
        data: JSON.stringify(request),
        dataType: dataType,
        beforeSend: function(){
            $(".waiting_modal").show("slow");
        },
        error: function(){
            $(".waiting_modal").hide("slow");
        },
        success: function(data, textStatus, jqXHR){
            $(".waiting_modal").hide("slow");
             var records = data.res;
            successFunction(records, textStatus, jqXHR);
        }
    });
};

function getCookie(cname){
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' '){
          c = c.substring(1);
      }

      if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
      }
  }
  return "";
}