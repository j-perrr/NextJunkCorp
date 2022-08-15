<?php

    $filenameee =  $_FILES['file']['name'];
    $fileName = $_FILES['file']['tmp_name'];
    // if($_POST){
    //     for($i = 0; $i < count($_FILES['file']['name']); $i++)
    //     {
    //         $filenameee[] =  $_FILES['file']['name'][$i];
    //         $fileName[] = $_FILES['file']['tmp_name'][$i]; 
    //     }
    // }
    // $files = $filenameee;
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['tel'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $serviceDate = date('Y-m-d', strtotime($_POST['serviceDate']));
    
    $message ="NOMBRE DE CLIENTE = ". $name . "\r\n  DIRECCION DE CLIENTE = " . $address . "\r\n  NUMERO CELULAR DE CLIENTE = " . $phone . "\r\n  CORREO ELECTRONICO DE CLIENTE= " . $email . "\r\n DESCRIPCION DE CLIENTE=" . $description . "\r\n  FECHA DE SERVICIO = " . $serviceDate ; 
    
    $subject ="CLIENTE NECESITA UN TRABAJO HECHO";
    $fromname ="ALERTAS DE NEXT JUNK";
    $fromemail = 'alerta-de-trabajo@nextjunk.us';  //if u dont have an email create one on your cpanel

    $mailto = 'contact@cocotech.business';  //the email which u want to recv this email


    // for($i = 0; $i < count($_FILES['file']['name']); $i++)
    // {
    //     $content[$i] = file_get_contents($fileName);
    //     $content[$i] = chunk_split(base64_encode($content[$i]));
    // }

    $content = file_get_contents($fileName);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (RFC)
    $eol = "\r\n";

    // main header (multipart mandatory)
    $headers = "From: ".$fromname." <".$fromemail.">" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;

    // message
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
    $body .= $message . $eol;

    // attachment

    // for($x=0;$x<count($files);$x++){
    //     $body .= "--" . $separator . $eol;
    //     $body .= "Content-Type: application/octet-stream; name=\"" . $files[$x] . "\"" . $eol;
    //     $body .= "Content-Transfer-Encoding: base64" . $eol;
    //     $body .= "Content-Disposition: attachment" . $eol;
    //     $body .= $content . $eol;
    //     $body .= "--" . $separator . "--";
    // }

    // for($i = 0; $i < count($_FILES['file']['name']); $i++)
    // {
    //     $body .= "--" . $separator . $eol;
    //     $body .= "Content-Type: application/octet-stream; name=\"" . $filenameee[$i] . "\"" . $eol;
    //     $body .= "Content-Transfer-Encoding: base64" . $eol;
    //     $body .= "Content-Disposition: attachment" . $eol;
    //     $body .= $content . $eol;
    //     $body .= "--" . $separator . "--";
    // }
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/octet-stream; name=\"" . $filenameee . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment" . $eol;
    $body .= $content . $eol;
    $body .= "--" . $separator . "--";


    //SEND Mail
    if (mail($mailto, $subject, $body, $headers)) {
        echo "EMAIL HAS BEEN SUCCESFULLY SENT! \n WE WILL GET BACK TO YOU SHORTLY"; // do what you want after sending the email
        
        
    } else {
        echo "mail send ... ERROR!";
        print_r( error_get_last() );
    }
