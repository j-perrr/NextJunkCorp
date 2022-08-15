<?php
if($_POST && isset($_FILES['file']))
{
	$recipient_email 	= "contact@nextjunk.us
    "; //recepient
	$from_email 		= "alerta-de-trabajo@nextjunk.us"; //from email using site domain.
	$subject			= "ALERTA DE TRABAJO"; //email subject line
	
	$sender_name = filter_var($_POST["name"]); //capture sender name
	$sender_email = filter_var($_POST["email"]); //capture sender email
	$sender_message = filter_var($_POST["description"]); //capture message
    $sender_address =  filter_var($_POST['address']); //capture address
    $sender_phone =  filter_var($_POST['tel']); //capture phone number
    $serviceDate = date('Y-m-d', strtotime($_POST['serviceDate'])); //capture date of service
	$attachments = $_FILES['file'];

    $message ="NOMBRE DE CLIENTE = ". $sender_name . "\r\n\n  DIRECCION DE CLIENTE = " . $sender_address . "\r\n\n  NUMERO CELULAR DE CLIENTE = " . $sender_phone . "\r\n\n  CORREO ELECTRONICO DE CLIENTE= " . $sender_email . "\r\n\n SITUACION=" . $sender_message . "\r\n\n  FECHA DE SERVICIO = " . $serviceDate ; 
	
	// //php validation
    // if(strlen($sender_name)<4){
    //     die('Name is too short or empty');
    // }
	// if (!filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
	//   die('Invalid email');
	// }
    // if(strlen($sender_message)<4){
    //     die('Too short message! Please enter something');
    // }
	
	$file_count = count($attachments['name']); //count total files attached
	$boundary = md5("sanwebe.com"); 
			
	if($file_count > 0){ //if attachment exists
		//header
        $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "From:".$from_email."\r\n"; 
        $headers .= "Reply-To: ".$sender_email."" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
        
        //message text
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
        $body .= chunk_split(base64_encode($message)); 

		//attachments
		for ($x = 0; $x < $file_count; $x++){		
			if(!empty($attachments['name'][$x])){
				
				if($attachments['error'][$x]>0) //exit script and output error if we encounter any
				{
					$mymsg = array( 
					1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
					2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", 
					3=>"The uploaded file was only partially uploaded", 
					4=>"No file was uploaded", 
					6=>"Missing a temporary folder" ); 
					die($mymsg[$attachments['error'][$x]]); 
				}
				
				//get file info
				$file_name = $attachments['name'][$x];
				$file_size = $attachments['size'][$x];
				$file_type = $attachments['type'][$x];
				
				//read file 
				$handle = fopen($attachments['tmp_name'][$x], "r");
				$content = fread($handle, $file_size);
				fclose($handle);
				$encoded_content = chunk_split(base64_encode($content)); //split into smaller chunks (RFC 2045)
				
				$body .= "--$boundary\r\n";
				$body .="Content-Type: $file_type; name=\"$file_name\"\r\n";
				$body .="Content-Disposition: attachment; filename=\"$file_name\"\r\n";
				$body .="Content-Transfer-Encoding: base64\r\n";
				$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n"; 
				$body .= $encoded_content; 
			}
		}

	}else{ //send plain email otherwise
       $headers = "From:".$from_email."\r\n".
        "Reply-To: ".$sender_email. "\n" .
        "X-Mailer: PHP/" . phpversion();
        $body = $message;
	}
		
	 $sentMail = @mail($recipient_email, $subject, $body, $headers);
	if($sentMail) //output success or failure messages
	{       
		die('THANK YOU FOR COMPLETING OUR JUNK REMOVAL FORM. WE WILL GET BACK TO YOU SHORTLY.');
	}else{
		die('Could not send mail! Please check your PHP mail configuration.');  
	}
}
?>