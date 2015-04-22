<?php

class Nav {
        
    private $page;
    private $isLoggedIn = false;


    public function __construct($isLoggedIn){
        
        $this->page = $_SERVER['PHP_SELF'];
        $this->isLoggedIn = $isLoggedIn;
        
    }

    public function render(){
        
        if($this->isLoggedIn == true)
        {
            //';if($page == '/manage.php'){ echo "Manage Music"; } else { echo 
            //nav header
        echo '
        <div class="container">
         <!-- Static navbar -->
         <nav class="navbar navbar-default">
           <div class="container-fluid">
             <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                 <span class="sr-only">Toggle navigation</span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="http://45.56.101.195"><span class="glyphicon glyphicon-music" aria-hidden="true"></span> Harmony</a>
             </div>
             <div id="navbar" class="navbar-collapse collapse">
               <ul class="nav navbar-nav">

                 <li '; if($this->page == "/manage.php"){echo 'class="active"';} echo '><a href="manage.php">Manage Music</a></li>
                <li '; if($this->page == "/create.php"){echo 'class="active"';} echo '><a href="create.php">Create Room</a></li>


               </ul>
               <ul class="nav navbar-nav navbar-right">
                <li><span class="navbar-text" style="color:grey;">Hey, ' . $_SESSION['Username']  . '!</span></li>
                 <li '; if($this->page == "/account.php"){echo 'class="active"';} echo '><a href="account.php">Account Settings</a></li>
                 <li><a href="logout.php">Log Out</a></li>

               </ul>
             </div><!--/.nav-collapse -->
           </div><!--/.container-fluid -->
         </nav>
         ';

        //login 
        echo
            '
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Sign in</h4>
                    </div> <!-- /.modal-header -->

                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="uLogin" placeholder="Login">
                                    <label for="uLogin" class="input-group-addon glyphicon glyphicon-user"></label>
                                </div>
                            </div> <!-- /.form-group -->

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="uPassword" placeholder="Password">
                                    <label for="uPassword" class="input-group-addon glyphicon glyphicon-lock"></label>
                                </div> <!-- /.input-group -->
                            </div> <!-- /.form-group -->
                        </form>

                    </div> <!-- /.modal-body -->

                    <div class="modal-footer">
                        <button class="form-control btn btn-primary">Ok</button>

                        <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100" style="width: 0%;">
                                <span class="sr-only">progress</span>
                            </div>
                        </div>
                    </div> <!-- /.modal-footer -->

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        ';
            
        
        }
        else{
           
        //nav header
        echo '
        <div class="container">
         <!-- Static navbar -->
         <nav class="navbar navbar-default">
           <div class="container-fluid">
             <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                 <span class="sr-only">Toggle navigation</span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="http://45.56.101.195"><span class="glyphicon glyphicon-music" aria-hidden="true"></span> Harmony</a>
             </div>
             <div id="navbar" class="navbar-collapse collapse">
               <ul class="nav navbar-nav">

                 <li class="active"><a href="#">Home</a></li>

               </ul>
               <ul class="nav navbar-nav navbar-right">

                 <li><a href="#" data-toggle="modal" data-target="#myModal">Sign In</a></li>
                 <li><a href="#" data-toggle="modal" data-target="#register">Register</a></li>

               </ul>
             </div><!--/.nav-collapse -->
           </div><!--/.container-fluid -->
         </nav>
         ';

        //login 
        echo
            '
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Sign In</h4>
                    </div> <!-- /.modal-header -->

                    <div class="modal-body">
                        <form role="form" method="post" action="login.php">

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="uLogin" name="Username" placeholder="Login">
                                    <label for="uLogin" class="input-group-addon glyphicon glyphicon-user"></label>
                                </div>
                            </div> <!-- /.form-group -->

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="uPassword" name="Password" placeholder="Password">
                                    <label for="uPassword" class="input-group-addon glyphicon glyphicon-lock"></label>
                                </div> <!-- /.input-group -->
                            </div> <!-- /.form-group -->
							<button class="form-control btn btn-primary" type="submit">Ok </button>
                        </form>

                    </div> <!-- /.modal-body -->

					
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        ';
            
        //register 
        echo
            '
            <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Register</h4>
                    </div> <!-- /.modal-header -->

                    <div class="modal-body">
                        <form role="form" method="post" action="register.php">
    
                        <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="Email" class="form-control" id="uEmail" placeholder="Email">
                                    <label for="uEmail" class="input-group-addon glyphicon glyphicon-envelope"></label>
                                </div>
                            </div> <!-- /.form-group -->
                            
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="Username" class="form-control" id="uLogin" placeholder="Username">
                                    <label for="uLogin" class="input-group-addon glyphicon glyphicon-user"></label>
                                </div>
                            </div> <!-- /.form-group -->

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" name="Password" class="form-control" id="uPassword" placeholder="Password">
                                    <label for="uPassword" class="input-group-addon glyphicon glyphicon-lock"></label>
                                </div> <!-- /.input-group -->
                            </div> <!-- /.form-group -->

                        <button type="submit" class="form-control btn btn-primary">Ok</button>
                        </form>
                    </div> <!-- /.modal-body -->

                    <div class="modal-footer">

                        <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100" style="width: 0%;">
                                <span class="sr-only">progress</span>
                            </div>
                        </div>
                    </div> <!-- /.modal-footer -->

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        ';
        }
        
    }
    }



?>
