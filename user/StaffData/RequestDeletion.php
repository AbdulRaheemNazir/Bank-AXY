<?php include "header.php";

include("GetDocuments.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Deletion Request</title>

<!-- Favicons -->
<link href="../../assets/img/favicon-32x32.png" rel="icon">
<link href="../../assets/img/apple-icon-180x180.png" rel="apple-touch-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">




<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<!--fontawesome-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="../../assets/css/UserDash.css">
<link rel="stylesheet" href="../../assets/css/StaffStyle.css">


<?php

$user = getDocuments();

$i = $_GET['File_Location'];

if (isset($_POST['submit'])) {

    $ownerID = GetUserID();
    $documentName = $user[$i]["Document_Name"];
    $documentCriticality = $user[$i]["Document_Criticality"];

    AuditTrail($ownerID, $documentName);
    $result = RequestDeletion($ownerID, $documentName, $documentCriticality);
    if ($result) {
        ?><script type="text/javascript">window.location.href = 'ViewDocuments.php';</script><?php
    }

} ?>

</head>

<body>

<div class="container-fluid px-lg-4 dark_bg light">
    <div class="row">
        <div class="col-md-12 mt-lg-4 mt-4">

        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title light mb-4 "></h5>


                    <?php if ($i == "Requested") { ?>
                                                                                <h3 class="h3 mb-4 light" style="text-align: center;">You have already requested deletion for this document</h3> 
                                                                                <div class="d-grid gap-2 mt-5 col-sm-4 mx-auto">

                                                                                <form action="ViewDocuments.php">
                                                                                <input type="submit" value="Go Back" class="btn btn-lg btn-block" style="margin-bottom: 10%;"/>
                                                                                </form>

                        <?php } else { ?>
                                                                                    <h3 class="h3 mb-4 light" style="text-align: center;">Any Documents deletion must be approved. You want to delete: <?php echo $user[$i]["Document_Name"] ?></h3> 


                                            <div id="Pay" class="d-grid gap-2 mt-5 col-sm-6 mx-auto">


                                                                                    <form method="post">

                                                                                <input type="submit" value="Request Deletion" name="submit" style="margin-bottom: 10%;" class="btn btn-lg btn-block">
                                                                                </form>

                        <?php } ?>

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
<script src="../UserData/js/profileInfo.js"></script>
<script src="../UserData/js/transfer.js"></script>


<script>
    $('#bar').click(function() {
        $(this).toggleClass('open');
        $('#page-content-wrapper ,#sidebar-wrapper').toggleClass('toggled');

    });
</script>
<?php
function AuditTrail($User_ID, $Document_Name)
{
    include("../../DB config.php");

    date_default_timezone_set('Europe/London');

    $stmt = $pdo->prepare('INSERT INTO Audit_Trail (User_ID, Document_Name, Audit_Date_Time, Audit_Action) VALUES (:User_ID, :Document_Name, :Audit_Date_Time, :Audit_Action)');

    $Action = "REQUESTED_Deletion";

    $dateString = date('d/m/Y H:i');
    $date = DateTime::createFromFormat('d/m/Y H:i', $dateString);
    $formattedDate = $date->format('M j Y g:iA');


    $stmt->bindParam(':User_ID', $User_ID, PDO::PARAM_STR);
    $stmt->bindParam(':Document_Name', $Document_Name, PDO::PARAM_STR);
    $stmt->bindParam(':Audit_Date_Time', $formattedDate, PDO::PARAM_STR);
    $stmt->bindParam(':Audit_Action', $Action, PDO::PARAM_STR);


    $stmt->execute();

}

function RequestDeletion($ownerID, $documentName, $documentCriticality)
{

    //sql for request deletion
    include("../../DB config.php");

    date_default_timezone_set('Europe/London');

    $stmt = $pdo->prepare('INSERT INTO Documents_Deletion_Request (Document_Name, Document_Criticality, Owner_ID, Request_Date_Time) VALUES (?,?,?,?)');

    $datetime = date('d/m/Y H:i');


    $stmt->bindParam(1, $documentName, PDO::PARAM_STR);
    $stmt->bindParam(2, $documentCriticality, PDO::PARAM_STR);
    $stmt->bindParam(3, $ownerID, PDO::PARAM_STR);
    $stmt->bindParam(4, $datetime, PDO::PARAM_STR);

    $result = $stmt->execute();
    return $result;
}

?>

</body>

</html>