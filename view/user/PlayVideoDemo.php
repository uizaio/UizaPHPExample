<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Uiza Player Demo</title>
        <link rel="stylesheet" type="text/css" href="../../stylesheet/bootstrap.min.css?v=072309092016" />
        <link rel="stylesheet" type="text/css" href="../../stylesheet/bootstrap-theme.min.css?v=072309092016"/>
        <link rel="stylesheet" type="text/css" href="../../stylesheet/font-awesome.min.css?v=072309092016" />
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src='https://sdk.uiza.io/uizaplayer.js'></script>
        <script src="../../javascript/main.js?v=201722062019"></script>
    </head>
    <body>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="margin-top: 10px;width: 98%;margin-left: 20px;">
                    <div class="panel-heading"><a href="#video-panel" data-toggle="collapse">Uiza Player demo</a></div>
                    <div class="panel-body" id="video-panel" class="collapse in">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2">
                                Token to play:
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="token">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="play-btn">Play</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="player-container">                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#play-btn").click(function(){
                    var service = getService();
                    var token = $("#token").val();
                    if(token !== ""){
                        var request = {};
                        request.token = token;
                        $.ajax({
                            type: "POST",
                            url: service + "/controller/CheckLinkPlay.php",
                            data: JSON.stringify(request),
                            dataType: "json",
                            success: function (records, textStatus, jqXHR) {
                              var result = records.res;
                              if(result.length > 0){
                                    $("#player-container").empty();
                                    $("#player-container").append("<div id='player' style='height: 500px;'></div>");
                                    var id = result[0].entityId;                                    
                                    UZ.Player.init(
                                        "#player",
                                        {
                                            api: apiUrl,
                                            appId: appId,
                                            playerVersion: 3,
                                            entityId: id,
                                            width: "100%",
                                            height: "100%"
                                        }
                                    );
                              }
                            },                            
                            error: function(){
                                alert("Invalid token!");
                            }
                        });
                    }                    
                });                
            });
        </script>
    </body>
</html>
