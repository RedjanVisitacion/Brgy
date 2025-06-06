<?php 



include_once '../connection.php';


try{

  if(isset($_REQUEST['residence_id'])){
    $residence_id = $con->real_escape_string(trim($_REQUEST['residence_id']));
    $archive_status = 'NO';
    $residence_status = 'ACTIVE';
    $date_archive = date("m/d/Y h:i A");

    $sql_archive_residence_information = "UPDATE `residence_status` SET `archive` = ?, `date_unarchive` = ?,  `status` = ? WHERE `residence_id` = ?";
    $stmt_archive_residence_information = $con->prepare($sql_archive_residence_information) or die($con->error);
    $stmt_archive_residence_information->bind_param('ssss',$archive_status,$date_archive,$residence_status,$residence_id);
    $stmt_archive_residence_information->execute();
    $stmt_archive_residence_information->close();


    $sql_check_resident = "SELECT first_name, last_name FROM residence_information WHERE residence_id = ?";
    $stmt_check_resident = $con->prepare($sql_check_resident) or die ($con->error);
    $stmt_check_resident->bind_param('s',$residence_id);
    $stmt_check_resident->execute();
    $result_check_resident = $stmt_check_resident->get_result();
    $row_resident_check = $result_check_resident->fetch_assoc();
    $first_name = $row_resident_check['first_name'];
    $last_name = $row_resident_check['last_name'];

    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UNDELETED RESIDENT - '.' ' .$residence_id.' | '  .' - '.$first_name .' '. $last_name;
    $status_activity_log = 'delete';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();



    



  }
  

}catch(Exception $e){
  echo $e->getMessage();
}


?>