<?php

require 'class.phpmailer.php';

$mail = new PHPMailer(); // create a new object


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
} 



$sql = "SELECT EMAIL_ID, firstname, lastName FROM MDLUSER"; // replace Query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    $email=$row["EMAIL_ID"];
    $firstName=$row["firstname"];
    $lastName=$row["lastName"];
    
    
    $sql_sent = "SELECT * FROM ALREADY_MAIL_SENT where EMAIL_ID='$email'"; // replace with new table details
    
    $result_already_sent = $conn->query($sql_sent);
    
    //check if already sent email id
    if ($result_already_sent->num_rows > 0) {
        echo "alredy sent to ".$email;
                }
       else
        {
         
        $nameofUser=$firstName." ".$lastName; //replace with original value
       
        $message = "Greetings!<br><br><br>
        Thank you for registering with Bitwise.<br><br><br>
        Your account has been created with the following details:<br>
        Name: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; $nameofUser<br>
        Username: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; $email<br><br><br>


        <img src='https://ci3.googleusercontent.com/proxy/RWVid23ZX8l6J-VhsrU5eSIgx5xbgQv1cansf3B9lemBaF4ysdBjnCZrwP47EHJfjofQuAsWWs_C8vZqrgwHkEPTCFrsDekAm4M2d6du=s0-d-e1-ft#http://ece.bitwiseglobal.com/pix/Bitwise_logo_signature.png'/><br>
        
        <font color='#000000'><b><a href='http://www.bitwiseglobal.com/'> bitwiseglobal.com </a></b></font>
        <br>
        <br>
        <a href='https://www.linkedin.com/company/bitwise-inc'> LinkedIn </a> and  <a href='https://twitter.com/BitWise_Updates'>  Twitter </a><br><br>
        <br>
        <br>
        
       <font color='#1f497d'><b> Please Note- You are required to carry the print out of this email notification and provide the same during the time of exam.</b></font>"
        ;

    
  // echo $message;  

//write mail sending code here 


        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "nate.ganesh@gmail.com";
        $mail->Password = "DremerStyle@1988";
        $mail->SetFrom("noreply@ece.bitwiseglobal.net","Bitwise Recruitment Team");
        $mail->Subject = "Successful Registration - Bitwise";
        $mail->Body = $message;
        $mail->AddAddress($email);

        if(!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
                echo "Message has been sent : $email\n";
                	$sql_insertInto = "INSERT INTO ALREADY_MAIL_SENT (EMAIL_ID)  VALUES ('$email')"; //replace with new table details
                    if ($conn->query($sql_insertInto ) === TRUE) {
                                  echo "New record created successfully : ".$email."\r\n ";
                    } else {
                                  echo "Error: " . $sql . "<br>" . $conn->error;
                   }
                
        }



    $email="";
    $message="";
    $nameofUser="";
    $mail->ClearAllRecipients();
    }
}
} else {
echo "0 results";
}
$conn->close();