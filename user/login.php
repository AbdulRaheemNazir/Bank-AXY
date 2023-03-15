<?php

include "./config.php";

include_once("checkLogin.php");

$User_ID_Error = $User_Password_Error = $invalidMesg = "";
$allField = True;

if (isset($_POST['submit'])) {
    if ($_POST["User_ID"]=="") {
        $User_ID_Error = "User ID is required";
        $allField = FALSE;
    } 
      
    if ($_POST["Password"]==null) {
        $User_Password_Error = "Password is required";
        $allField = FALSE;
    }
    
    if ($allField ==  True) {
    
        $array_User = verifyUsers();
        print_r($array_User);

        if (!empty($array_User)) {            
            $User_ID = $array_User[0]["User_ID"];
            $Role = $array_User[0]["User_Role"];
            $Password = $array_User[0]["Password"];
        }
            if($Role == "Staff"){
                header("Location: ../user/StaffData/Dashboard.php?User_ID=".$array_User[0][0]); 
            }
            if($Role == "Admin"){
                header("Location: ../user/AdminData/Dashboard.php?User_ID=".$array_User[0][0]); 
            }
            if($Role == "Head Office"){
                header("Location: ../user/HeadOfficeData/Dashboard.php?User_ID=".$array_User[0][0]);                    
            }
    }
    else {
        $invalidMesg = "Invalid User ID or Password!";
        }   
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <!-- Favicons -->
    <link href="../assets/img/favicon-32x32.png" rel="icon">
    <link href="../assets/img/apple-icon-180x180.png" rel="apple-touch-icon">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">

    <!-- Extra CSS for external module -->
    <style>
        .swal-button {
            padding: 7px 19px;
            border-radius: 2px;
            background: linear-gradient(to right, #0032A0, #CC0000);
            font-size: 12px;
            color: white;
        }

        .swal-button:hover {
            opacity: 0.8;
            background-color: transparent;
        }
    </style>


</head>

<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="../assets/img/PageImage/loginImage.jpg" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img src="../assets/img/Logo.svg" alt="logo" class="logo">
                                <p><?php echo BANKNAME ?></p>                             
                            </div>
                            <p class="login-card-description">Sign into your account</p>

                            <!-- Login Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                                <?php if (isset($_GET['error'])) { ?>

                                        <p style="color: red;"> *<?php echo $_GET['error'] ?> ! </p>

                                <?php } ?>

                                <div class="form-group">
                                    <label for="username" class="sr-only">Username</label>
                                    <input type="text" name="User_ID" id="Username" class="form-control" placeholder="Username" required>
                                    <span class="text-danger"><h2><?php echo $User_ID_Error; ?></h2></span>
                                    <p id="alert1" style="color: red;"></p>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="Password" id="password" class="form-control" placeholder="***********" required>
                                    <span class="text-danger"><h2><?php echo $User_Password_Error; ?></h2></span>
                                </div>
                                <input name="submit" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                                <span class="text-danger"><h2><?php echo $invalidMesg; ?></h2></span>   
                            </form>

                            <nav class="login-card-footer-nav">
                                <a href="../pages/terms.php">Terms of use.</a>
                                <a href="../pages/privacypolicy.php">Privacy policy</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script src="../assets/js/showHidePass.js"></script>
    <script>
        <?php if (isset($_GET['error'])) { ?>
                swal({
                    title: "Account Alert!",
                    text: "<?php echo $_GET['error'] ?>",
                    icon: "error",
                });


        <?php } ?>
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=\'password\']').showHidePassword();

            // $('#OldPassword').showHidePassword();
        });
    </script>
</body>

</html>