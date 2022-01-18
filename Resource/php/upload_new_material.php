<?php

include('./learners_crib_resource.php');

$Return['status'] = 0;
$Return['message'] = 'Access Denied';
$Set = 0;

if (isset($_SESSION['resource_centre_user_id'])) {
    $User = $_SESSSION['resource_centre_user_id'];
    $User = pack('H*', $User);
    $Set = 1;
} elseif (isset($_COOKIE['resource_centre_user_id'])) {
    $_SESSION['resource_centre_user_id'] = $_COOKIE['resource_centre_user_id'];
    $User = $_COOKIE['resource_centre_user_id'];
    $User = pack('H*', $User);
    $Set = 1;
}

if (isset($_POST['Material_Name']) && $Set == 1) {

    $Material_Name = mysqli_real_escape_string($LearnersCribResource, $_POST['Material_Name']);
    $Course_Title = mysqli_real_escape_string($LearnersCribResource, $_POST['Course_Title']);
    $Course_Code = mysqli_real_escape_string($LearnersCribResource, $_POST['Course_Code']);
    $Material_Description = mysqli_real_escape_string($LearnersCribResource, $_POST['Material_Description']);
    $Course_Level = mysqli_real_escape_string($LearnersCribResource, $_POST['Course_Level']);
    $Material_File = $_FILES['Material_File'];

    $Find_User = "SELECT * FROM users WHERE Id = $User ";
    $Found_User = mysqli_query($LearnersCribResource, $Find_User);


    if (mysqli_num_rows($Found_User) == 1) {

        $Material_Path_Name = explode(' ', $Material_Name);
        $Material_Path_Name = implode('_', $Material_Path_Name);

        $Material_Path_Extension = explode('.', $Material_File);
        $Material_Path_Extension = $Material_Path_Extension[(sizeof($Material_Path_Extension) - 1)];

        $Material_Path_New_Full = $Material_Path_Name . '.' . $Material_Path_Extension;

        if (!file_exists($Material_Path_Extension)) {
            mkdir($Material_Path_Extension);
        }

        if (move_uploaded_file($Material_File['tmp_name'], '../' . $Material_Path_Extension . '/' . $Material_Path_New_Full)) {
        } else {
            $Return['message'] = 'We Had An Error With Your File. Please Try Again Or Change The File';
        }
    } else {
        $Return['message'] = 'User Does Not Exist';
    }
}

echo json_encode($Return);
