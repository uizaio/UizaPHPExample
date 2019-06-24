<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>UizaDemo My videos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="../../stylesheet/bootstrap-theme.min.css?v=072309092016"/>
        <link rel="stylesheet" type="text/css" href="../../stylesheet/font-awesome.min.css?v=072309092016" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/w2ui/1.4.3/w2ui.min.css" />
        <link rel="stylesheet" type="text/css" href="../../stylesheet/main.css?v=072309092016" />
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/w2ui/1.4.3/w2ui.min.js"></script>
        <script src='https://sdk.uiza.io/uizaplayer.js'></script>
        <script src="../../javascript/main.js?v=201722062019"></script>
        <script src="../../javascript/myvideo.js?v=201722062019"></script>
    </head>
    <body>
        <script type="text/javascript">
            $(document).ready(function(){
                var mv = new MyVideo();                
            });
        </script>
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
                    <a class="navbar-brand" href="#">Navbar</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <a class="nav-item nav-link" href="/view/user/PlayVideoDemo.php" id='player-demo-link'>Demo Player</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <ul>
                    <li>
                        <a href="/view/user/videos.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="#">Video management</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10">
                <div class="panel panel-primary" style="margin-top: 10px;width: 98%;">
                    <div class="panel-heading"><a href="#video-panel" data-toggle="collapse">My Video</a></div>
                    <div class="panel-body" id="video-panel" class="collapse in">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="my-video-table"></div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </body>
</html>
