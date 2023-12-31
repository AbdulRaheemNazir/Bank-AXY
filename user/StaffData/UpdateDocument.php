<?php include "header.php";
include "GetDocuments.php";

$uname = $_SESSION['User_ID'];
$user = getDocuments();
$errorMessage = "";
$i = $_GET['File_Location'];
$DuplicateNameError = "";
$NameConventionError = "";


?>
<script>
document.addEventListener('contextmenu', event => event.preventDefault());
</script>
<!DOCTYPE html>
<html lang="en">

<head>


    <title>Update Documents</title>


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">



    <link rel="stylesheet" href="../../assets/css/UserDash.css">
    <link rel="stylesheet" href="../../assets/css/StaffStyle.css">



<?php

$allField = true;


if (isset($_POST['submit'])) {
    $name = $_POST["DocumentName"];
    $Duplicate = checkDuplicate($name);
    $nameConvention = checkNameConvention($name);


    if ($_POST["DocumentName"] == "") {

        $allField = false;
    }

    if ($_POST["DocumentType"] == null) {

        $allField = false;
    }

    if ($_POST["DocumentCriticality"] == null) {

        $allField = false;
    }

    if (!$Duplicate) {
        $UploadMessage = "File not updated";
        $DuplicateNameError = "The Document name submitted was a duplicate name.";
        $allField = false;
    }

    if (!$nameConvention) {
        $UploadMessage = "File not updated";
        $NameError = "The Document name does not match the agreed convention. Please refer back to the guide.";
        $UploadSuccess = false;
    }

    if ($allField == true) {

        $type = $_POST["DocumentType"];
        $criticality = $_POST["DocumentCriticality"];
        $Document_ID = $_GET['Document_ID'];

        $result = updateDocument($name, $type, $criticality, $Document_ID);
        if ($result) {
            echo ("<script>location.href = 'ViewDocuments.php?updated=true';</script>");
        }
    }

}


?>
</head>

<body>

    <div class="container-fluid px-lg-4 dark_bg light">
        <div class="row">
            <div class="col-md-12 mt-lg-4 mt-4">
                <div class="d-sm-flex align-items-center mb-4" style="justify-content:center;">
                    <h1 class="h3 mb-0 light" style="text-align: center;">Update Document here:</h1>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title light mb-4 "></h5>
                                
<table class="styled-table">
    <thead>
        <tr>
            <th>Document Name</th>
            <th>Document Type</th>
            <th>Document Criticality</th>
            <th>Owner ID</th>
            <th>Creation Date & Time</th>          
        </tr>
    </thead>
    <tbody>

        <tr class="active-row">
            <td><?php echo $user[$i]['Document_Name'] ?></td>
            <td><?php echo $user[$i]['Document_Type'] ?></td>
            <td><?php echo $user[$i]['Document_Criticality'] ?></td>
            <td><?php echo $user[$i]['Owner_ID'] ?></td>
            <td><?php echo $user[$i]['Creation_Date_Time'] ?></td>


        </tr>
    </tbody>
</table>

<form class="styled-table" Method="POST">
<div class="form-group">
    <label for="exampleInputEmail1">Document Name</label>
    <input type="text" class="form-control" name="DocumentName" value="<?php echo $user[$i]['Document_Name'] ?>">
    <?php if ($DuplicateNameError != "") { ?><p class="error-message"><br><?php echo $DuplicateNameError ?></p><?php } ?>
    <?php if ($NameConventionError != "") { ?><p class="error-message"><br><?php echo $NameConventionError ?></p><?php } ?>
  </div>  
  
  <div class="form-group">
    <label for="exampleInputEmail1">Document Type</label>
    <select class="form-control" name="DocumentType"    >
        <option selected="selected"><?php echo $user[$i]['Document_Type'] ?></option>
        <option value="<?php echo LOANS_DEP ?>">Loans</option>
      <option value="<?php echo MORTGAGES_DEP ?>">Mortgages</option>
      <option value="<?php echo ADMIN_DEP ?>">Administration</option>
      <option value="<?php echo ACCOUNTS_DEP ?>">Accounts</option>
    </select>
  </div>  
  <div class="form-group">
    <label for="exampleInputEmail1">Document Criticality</label>
    <select class="form-control" name="DocumentCriticality">
    <option selected="selected"><?php echo $user[$i]['Document_Criticality'] ?></option>
      <option value="<?php echo CRIT_HIGH ?>">High</option>
      <option value="<?php echo CRIT_MEDIUM ?>">Medium</option>
      <option value="<?php echo CRIT_LOW ?>">Low</option>
    </select>
  </div>  
  <div id="UpdateButton" class="d-grid col-sm-5 mx-auto">
  <input name="submit" type="submit" value="Update" style="margin-top: 5%; margin-bottom: 5%;" class="btn1 btn-lg btn-block"></input>
</div>
</form>
<div id="backButton" class="d-grid col-sm-3 mx-auto">
                                        <button onclick="document.location='ViewDocuments.php'" style="margin-top: 20%; margin-bottom: 25%;" class="btn1 btn-lg btn-block">Back</button>

                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>



                </div>


            </div>

        </div>

        <div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog loadingModal modal-lg">
                <div class="modal-content" style="width: 50px; height:50px; background: transparent;">
                    <span class="fas fa-spinner fa-pulse fa-3x" style="color:white"></span>
                </div>
            </div>
        </div>

    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



    <script>
        $('#bar').click(function() {
            $(this).toggleClass('open');
            $('#page-content-wrapper ,#sidebar-wrapper').toggleClass('toggled');

        });
    </script>
    <?php
    function updateDocument($name, $type, $criticality, $Document_ID)
    {
        include("../../DB config.php");

        $sql = "UPDATE Documents SET Document_Name=?, Document_Type=?, Document_Criticality=? WHERE Document_ID=?";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$name, $type, $criticality, $Document_ID]);

        if (!$result) {
            $error = $stmt->errorInfo();
            $errorMessage = "SQL error: " . $error[2];
        }
        return $result;

    }

    function checkDuplicate($name)
    {
        include("../../DB config.php");

        $sql = "SELECT Document_Name FROM dbo.Documents WHERE Document_Name = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $result = $stmt->execute();
        if ($result) {
            $row = $stmt->fetch();
            if ($row) {
                $row['Document_Name'];
                return true;
            } else {
                return false;
            }
        }
    }

    function checkNameConvention($name)
    {
        $document_regex = "/^([A-Za-z])+-([A-Za-z])+-([0-9])+(['.pdf'])+$/";
        $nameCheck = preg_match($document_regex, $name);

        return $nameCheck;
    }

    ?>

</body>

</html>