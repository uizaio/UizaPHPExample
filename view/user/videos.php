<?php
    
    require_once '../../config/config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Videos Management</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <!--<link rel="stylesheet" type="text/css" href="../../stylesheet/bootstrap.min.css?v=072309092016" />-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="../../stylesheet/bootstrap-theme.min.css?v=072309092016"/>
        <link rel="stylesheet" type="text/css" href="../../stylesheet/font-awesome.min.css?v=072309092016" />        
        <link rel="stylesheet" type="text/css" href="../../stylesheet/main.css?v=072309092016" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/w2ui/1.4.3/w2ui.min.css" />
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/w2ui/1.4.3/w2ui.min.js"></script>
        <script src="../../javascript/main.js?v=201722062019"></script>
        <script src="../../javascript/video.js?v=2117062019"></script>
    </head>
    <body>
        <script type="text/javascript">
            $(document).ready(function(){
                var v = new Video();                
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
                            <a class="nav-item nav-link" href="#" id='update_account'>Account</a>
                            <a class="nav-item nav-link" href="#" id='logout'>Logout</a>
                            <a class="nav-item nav-link" href="#" id='login'>Login</a>
                            <a class="nav-item nav-link" href="#" id='sign_up'>Sign Up</a>
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
                      <a href="#">Dashboard</a>
                    </li>
                    <li>
                        <a href="/view/user/myvideos.php">Video management</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="panel panel-primary" style="margin-top: 10px;">
                    <div class="panel-heading"><a href="#video-panel" data-toggle="collapse">Video management</a></div>
                    <div class="panel-body" id="video-panel" class="collapse in">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <button id="video-btn" type="button" class="btn btn-success btn-sm" style="margin-top: 10px;width: 100%; margin-bottom: 10px;">Sync video</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="video-table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

