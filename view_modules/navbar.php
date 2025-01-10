 <!-- Navigation Bar -->
 <nav class="navbar navbar-dark bg-dark shadow-lg text-white">

    <!-- main div -->
    <div class="container">

         <!-- Site title -->
         <a class="navbar-brand" href="index.php">
             <img src="files/logo.png" alt="" width="25" height="25" class="d-inline-block align-text-top">
             <span id="menuTitle">PSYCHOACOUSTICS-WEB</span>
         </a>

         <!-- user Login and Settings Buttons -->
         <div class="d-flex align-items-center">

             <?php
                //if the user is not logged show the login buttons
                if (!isUserLogged()) {
                ?>
                 <div class="text-center me-4">
                     <a class="settings navbar-text d-flex flex-column align-items-center" href="register.php" style="text-decoration: none;">
                         <i class="material-icons text-white" style="font-size: 30px;">app_registration</i> <!-- Sign Up icon -->
                         <small class="d-block text-white" style="font-size: 0.75rem;">Sign Up</small>
                     </a>
                 </div>

                 <div class="text-center me-4">
                     <a class="settings navbar-text d-flex flex-column align-items-center" href="login.php" style="text-decoration: none;">
                         <i class="material-icons text-white" style="font-size: 30px;">login</i> <!-- Log In icon -->
                         <small class="d-block text-white" style="font-size: 0.75rem;">Log In</small>
                     </a>
                 </div>

             <?php
                    //if the user is logged show the welcome message and the 'your test' and 'logout' buttons
                } else { ?>

                 <div class="d-none d-md-block me-5"> <!-- Add a margin to the right -->
                     <label id="menuWelcome" class='text-white navbar-text d-none d-md-inline'>
                         Welcome <?php echo $_SESSION['loggedUser']['username'];
                                    echo '   #' . $_SESSION['loggedUser']['id']; ?>
                     </label>
                 </div>

                 <div class="text-center me-4" style="display: inline-block;">
                     <a class="settings navbar-text d-flex flex-column align-items-center" href="yourTests.php" style="text-decoration: none;">
                         <i class="material-icons text-white" style="font-size: 30px;">format_list_bulleted</i>
                         <small class="d-block text-white" style="font-size: 0.75rem;">Your Tests</small>
                     </a>
                 </div>

                 <div class="text-center me-4" style="display: inline-block;">
                     <a class="settings navbar-text d-flex flex-column align-items-center" href="php/logout.php" style="text-decoration: none;">
                         <i class="material-icons text-white" style="font-size: 30px;">exit_to_app</i>
                         <small class="d-block text-white" style="font-size: 0.75rem; margin-top: 0;">Logout</small>
                     </a>
                 </div>

                 <div class="text-center" style="display: inline-block;">
                     <a class="settings navbar-text d-flex flex-column align-items-center" href="userSettings.php" style="text-decoration: none;">
                         <i class="material-icons rotate text-white" style="font-size: 30px;">settings</i>
                         <small class="d-block text-white" style="font-size: 0.75rem; margin-top: 0;">Settings</small>
                     </a>
                 </div>

             <?php } ?>

         </div>
     </div>
 </nav>