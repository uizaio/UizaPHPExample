function MyVideo() {
  this.service = getService();
  this.init();
};

MyVideo.prototype.service;

MyVideo.prototype.constructor = MyVideo;
MyVideo.prototype.jwt;

MyVideo.prototype.init = function () {
  var self = this;
  this.checkToken();
};

MyVideo.prototype.checkToken = function(){
  this.jwt = getCookie('jwt');
  var self = this;
  $.post(self.service + "/controller/ValidateToken.php", JSON.stringify({ jwt: self.jwt }))
          .fail(function(result) {
            window.location = self.service + "/view/index.php";
          }).done(function(result){
            self.renderVideoTable();
          });
};

MyVideo.prototype.renderVideoTable = function(){
  var self = this;
  w2utils.settings['dataType'] = 'JSON';
  $("#my-video-table").w2grid({
    name: "my-video-table",
    method: "POST",
    postData: {total: 20, jwt: self.jwt},
    url: self.service + "/controller/ListMyVideo.php",    
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
      },
      {
        field: "expireDate", caption: "Expire in", size: "5%"
      },
      {
        field: "vuid", caption: "Video user Id", hidden: true
      }
    ],
    menu: [
      {id: 1, text: "Play!", icon: "fa fa-play"},
      {id: 2, text: "Get Token", icon: "fa fa-files-o"}
    ],
    onMenuClick: function(event){
      var menu = event.menuItem;
      switch(menu.id){
        case 1:
          var recid = event.recid;
          var request = {};
          request.id = recid;
          request.jwt = self.jwt;
          $.ajax({
            type: "POST",
            url: self.service + "/controller/GetVideoInfo.php",
            data: JSON.stringify(request),
            dataType: "json",
            success: function (records, textStatus, jqXHR) {
              var result = records.res;
              self._previewWindow(result); 
            }
          });                   
          break;
        case 2:
          var recid = event.recid;
          var record = w2ui['my-video-table'].get(recid);
          var request = {};
          request.id = record.vuid;
          request.jwt = self.jwt;
          $.ajax({
            type: "POST",
            url: self.service + "/controller/GetVideoToken.php",
            data: JSON.stringify(request),
            dataType: "json",
            success: function (records, textStatus, jqXHR) {
              var result = records.res;
              self._tokenWindow(result); 
            }
          });  
          break;
      }
    }
  });
};

MyVideo.prototype._previewWindow = function(video){
  this.destroyW2uiObject(w2ui.layout_preview);
  $().w2layout({
    name: "layout_preview",
    padding: 0,
    panels: [
      {
        type:"main",
        size: "950",
        content:"<div id='uiza-player'></div>"
      }      
    ]
  });
  w2popup.close();
  w2popup.open({
    title: "Video preview",
    body: "<div id='popup_preview'></div>",
    modal: true,
    width: 1150,
    height: 520,
    showClose: true,
    onOpen:function(event){
      event.onComplete = function(){
        $("#w2ui-popup #popup_preview").w2render("layout_preview");
        UZ.Player.init(
          "#uiza-player",
          {
            api:"trungprod015-api.uiza.co",
            appId: "52d2872e914e46acaa00c854fae1c537",
            playerVersion: 4,
            entityId: video[0].entityId,
            width: "100%",
            height: "100%"
          }
        );
      };
    }
  });
};

MyVideo.prototype._tokenWindow = function(token){
  this.destroyW2uiObject(w2ui.layout_token);
  $().w2layout({
    name: "layout_token",
    padding: 0,
    panels: [
      {
        type:"main",
        size: "950",
        content:"<div id='uiza-token'><input type='text' id='token' style='margin-top: 10px;width: 100%;width:100%;' readonly></div>"
      }      
    ]
  });
  w2popup.close();
  w2popup.open({
    title: "Video token",
    body: "<div id='popup_token'></div>",
    modal: true,
    width: 500,
    height: 100,
    showClose: true,
    onOpen:function(event){
      event.onComplete = function(){
        $("#w2ui-popup #popup_token").w2render("layout_token"); 
        $("#token").val(token[0].token);
      };
    }
  });
};

MyVideo.prototype.destroyW2uiObject = function(ow2ui){
    if(typeof ow2ui !== "undefined"){
        ow2ui.destroy();
    }
};