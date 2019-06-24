function Video() {
  this.service = getService();
  this.init();
};

Video.prototype.service;

Video.prototype.constructor = Video;
Video.prototype.jwt;

Video.prototype.init = function () {  
  var self = this;
  this.checkToken();
  $("#video-btn").click(function(){
    self.syncVideo();
  });
  
};

Video.prototype.checkToken = function(){
  this.jwt = getCookie('jwt');
  console.log(this.jwt);
  var self = this;
  $.post(self.service + "/controller/ValidateToken.php", JSON.stringify({ jwt: self.jwt }))
          .fail(function(result) {
            window.location = self.service + "/view/index.php";
          }).done(function(result){
            self.renderVideoTable();
          });
};

Video.prototype.syncVideo = function(){
  var self = this;
  var request = {};
  request.jwt = this.jwt;
  $.ajax({
    type: "POST",
    url: self.service + "/controller/SyncVideo.php",
    data: JSON.stringify(request),
    dataType: "json",
    success: function (records, textStatus, jqXHR) {
      console.log(records);
    }
  });
};

Video.prototype.renderVideoTable = function(){
  var self = this;
  //make w2grid send json instead of url-encoded
  w2utils.settings['dataType'] = 'JSON';
  $("#video-table").w2grid({
    name: "video-table",
    method: "POST",
    postData: {total: 20, jwt: self.jwt},
    url: self.service + "/controller/ListVideo.php",    
    show:{
      header: true,
      footer: true,
      lineNumbers: false,
      toolbar: true
    },
    limit: 50,
    columns:[
      { 
        field: 'videoName', caption: 'Video Name', size: '7%', editable: { type: 'list', items: self.courses, showAll: true }, frozen: true
      },
      {
        field: "videoId", caption: "Video Id", hidden: true
      }
    ],
    menu: [
      {id: 1, text: "Rent it!", icon: "fa fa-facebook-square"},
    ],
    onMenuClick: function(event){
      var menu = event.menuItem;
      switch(menu.id){
        case 1:
          var recid = event.recid;
          self.rentWindow(recid);
//          var request = {};
//          request.id = recid;
          
//          $.ajax({
//            type: "POST",
//            url: self.service + "/controller/RentVideo.php",
//            data: JSON.stringify(request),
//            dataType: "json",
//            success: function (records, textStatus, jqXHR) {
//              console.log(records);
//            }
//          });
          break;
      }
    }
  });
};

Video.prototype.rentWindow = function(recid) {
  var self = this;
  this.destroyW2uiObject(w2ui.layout_rent);
  $().w2layout({
    name: "layout_rent",
    padding: 0,
    panels: [
      {
        type:"main",
        size: "300",
        content:"<div id='rent-video'><div id='rent_content' style='overflow:hidden;'></div></div>"
      }      
    ]
  });
  w2popup.close();
  w2popup.open({
    title: "Video preview",
    body: "<div id='popup_rent'></div>",
    modal: true,
    width: 600,
    height: 150,
    showClose: true,
    onOpen:function(event){
      event.onComplete = function(){
        $("#w2ui-popup #popup_rent").w2render("layout_rent");
        var html = new Array();
        html.push("<div class='row' style='margin-top:5px;'>");
            html.push("<div class='col-md-3 col-md-offset-1'>Expire time:</div>");
            html.push("<div class='col-md-3'><input type='text' id='expire-time' style='width: 100%;'/></div>");
            html.push("<div class='col-md-2'><button id='rent-btn' type='button' class='btn btn-danger btn-sm'>Rent</button></div>");
        html.push("</div>");
        $("#rent_content").append(html.join(""));
        $('#expire-time').w2field('date', { format: 'dd/mm/yyyy'});
        $("#rent-btn").click(function(){
          var expire = $('#expire-time').data("w2field").el.value;
          var d = expire.split("/");
          expire = d[2] + "-" + d[1] + "-" + d[0]; 
          var request = {};
          request.jwt = self.jwt;
          request.id = recid;
          request.expire = expire;
          $.ajax({
            type: "POST",
            url: self.service + "/controller/RentVideo.php",
            data: JSON.stringify(request),
            dataType: "json",
            success: function (records, textStatus, jqXHR) {
              w2popup.close();
            }
          });
        });
      };
    }
  });
};

Video.prototype.destroyW2uiObject = function(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};