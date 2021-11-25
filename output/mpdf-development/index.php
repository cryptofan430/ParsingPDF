<?php

$test = 'test';

// include 'phpqrcode/qrlib.php';
// $text = "GEEKS FOR GEEKS";

// Generates QR Code and Stores it in directory given

// Displaying the stored QR code from directory
function str_token($string, $token, $count) {
    $token_str = strtok($string, $token);
    $dx = 0;
    do
    {
        $token_str = strtok($token);
        $dx ++;
    }
    while ($count> $dx);
    return $token_str;
}

function utf8_strrev($str){
    preg_match_all('/./us', $str, $ar);
    return join('', array_reverse($ar[0]));
}

function mb_strrev1($str){
    $r = '';
    for ($i = mb_strlen($str); $i >= 0; $i-= 8 ) {
        $r .= mb_substr($str, $i, 8);
    }

    return $r;
}

function mb_strrev ($string, $encoding = null)
{
    if ( is_null($encoding) ) {
        $encoding = mb_detect_encoding($string);
    }

    $length   = mb_strlen($string, $encoding);
    $reversed = '';

    while ( $length-->0 ) {
        $reversed .= mb_substr($string, $length, 1, $encoding);
    }

    return $reversed;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["content"])) {
        // exit();
    }
    else{

        $content = $_POST["content"];
        // echo($content);
        $ref = $_POST["file"];
        $type = $_POST["type"];
        $content= strstr($content, "identification (*) :");
        $id_num = str_token($content, ":", 1);

        $content= strstr($content, "Nom :");
        $lName = str_token($content, ":", 1);
        
        $e_lName = strtok($lName, " ");
        $a_lName= str_token($lName, " ", 1);

        // echo($text);

        $content= strstr($content, "nom :");
        $fName = str_token($content, ":", 1);
        
        $e_fName = strtok($fName, " ");
        $a_fName= str_token($fName, " ", 1);
        
        $content= strstr($content, "naissance :");
        $birth = str_token($content, ":", 1);
        
        $content= strstr($content, "Nationalit");
        $nation= str_token($content, ":", 1);
        $nation= strtok($nation, " ");
        
        $a_nation= str_token($content, ":", 1);
        $a_nation= str_token($content, " ", 2);
        
        $dose_type = "Vaccin vero cell inactivated antisarscov2 Sinopharm";

        $pos = strpos($content, "Ce pass est");
        $dose_date = substr($content, $pos-10, 10);
        
        $content= strstr($content, "Pass issue date");
        $content= substr($content, 15);
        $pass_id = strtok($content, " ");
        
        //

        require_once __DIR__ . '/vendor/autoload.php';
        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                __DIR__ . '/font',
            ]),
            'fontdata' => $fontData + [
                'muna' => [
                    'R' => 'alfont_com_MunaBold.ttf',
                    // 'B' => 'alfont_com_MunaBold.ttf',
                    'useOTL' => 0x80,
                    'useKashida' => 75,
                ],
                'arial' => [
                    'R' => 'arialbd.ttf',
                    'B' => 'arialbd.ttf',
                ]
            ],
            'default_font' => 'arial',
            'tempDir' => __DIR__ . '/tmp',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10.5
        
        ]);
        
    $html = "";
    switch( $type){
        case 1:
            $html = '
                <style>
                .nation{
                    background-image: url(images/nation/q.jpg);
                    background-repeat: no-repeat;
                    background-size: 52%;
                    margin-left: 9.7%;
                }
                
                img {
                    position: absolute;
                    left: 0;
                    top: 0;
                    z-index: -1;
                    width: 100%;
                }
                .left{
                    float: left;
                }
                .right{
                    float: right;
                    text-align: right;
                    direction: rtl;
                }
                .arab{
                    font-family: arial;
                    text-align: right !important;
                    direction: rtl;
                }
                .qr-code{
                    border: 0px solid black;
                    height: 250px;
                    margin-left: 710px;
                    margin-top: -890px;
                
                }
                .head{
                    width: 28%;
                    
                    color:blue;
                    
                    padding-top: 10px;
                    margin-left: 10px;
                    padding-bottom: 35px;
                }
                .card{
                    color:#3478bd;
                    font-size: 10px;
                    margin-bottom: -3px;
                    font-family: arial;
                    text-align: center;
                    margin-left: 0px;
                }
                .card2{
                    color:black;
                    float: left;
                    text-align: left;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card23{
                    color: black;
                    margin-right: 10px;
                    text-align: right;
                    font-size: 10px;
                }
                .card3{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    margin-top: 29px;
                    width: 120%;
                    font-size: 5px;
                }
                .card4{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card5{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 10px;
                    font-size: 10px;
                }
                
                </style>
            <div style="position: absolute; left:303px; top: 55px; bottom: 0;">
                <img src="images/qr/nat.png"
                    style="width: 130px; margin: 0;" />
            </div>

            <div class="nation">
                <div class="head">
                
                    <h1 class="card" >'.$id_num . '</h1>
                    <div style="float: left; width: 65%;">
                        <h1 class="card2"> <small>' . $e_lName. '</small> </h1>
                    </div>

                    <div style="float: right; width: 30%">
                        <h1 class="card23"><small class="arab">'. mb_strrev1( $a_lName ). '</small></h1>
                    </div>

                    <div style="clear: both; margin: 0pt; padding: 0pt; "></div>

                    <div style="float: left; width: 65%;">
                        <h1 class="card2"> <small>' . $e_fName. '</small> </h1>
                    </div>

                    <div style="float: right; width:30%">
                        <h1 class="card23"><small class="arab">'. mb_strrev1( $a_fName ). '</small></h1>
                    </div>

                    <h1 class="card">'. $birth .'</h1>
                    <div style="float: left; width: 65%;">
                        <h1 class="card2" style="font-size: 8.5px;"> <small>' . $nation. '</small> </h1>
                    </div>

                    <div style="float: right; width:30%">
                        <h1 class="card23"><small class="arab">'. mb_strrev1( $a_nation ). '</small></h1>
                    </div>

                    <h4 class="card3">'.$dose_type.'</h4>

                    <h1 class="card4"><span style="font-size: 6.5px;">Ref : COD - '. $ref .'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '. $dose_date.'</h1>
                    <h1 class="card5"><small>'. $pass_id.'</small></h1>
                    
                </div>
            </div>
            ';
            break;
        case 2:
            $mpdf = new \Mpdf\Mpdf([
                'fontDir' => array_merge($fontDirs, [
                    __DIR__ . '/font',
                ]),
                'fontdata' => $fontData + [
                    'muna' => [
                        'R' => 'alfont_com_MunaBold.ttf',
                        // 'B' => 'alfont_com_MunaBold.ttf',
                        'useOTL' => 0x80,
                        'useKashida' => 75,
                    ],
                    'arial' => [
                        'B' => 'arialbd.ttf',
                        'R' => 'arialbd.ttf',
                    ]
                ],
                'default_font' => 'arial',
                'tempDir' => __DIR__ . '/tmp',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 71.5
                 
            ]);
            $html = '
                <style>
                .padding{
                    margin-top: 0px;
                }
                .inter{
                    background-image: url(images/inter/q.jpg);
                    background-repeat: no-repeat;
                    background-size: 52%, 100%;
                    margin-left: 9.7%;
                    padding-bottom: 28px;
                }
                
                .nation{
                    background-image: url(images/nation/q.jpg);
                    background-repeat: no-repeat;
                    background-size: 52%;
                    margin-left: 9.7%;
                }
                
                img {
                    position: absolute;
                    left: 0;
                    top: 0;
                    z-index: -1;
                    width: 100%;
                }
                .left{
                    float: left;
                }
                .right{
                    float: right;
                    text-align: right;
                    direction: rtl;
                }
                .arab{
                    font-family: arial;
                    text-align: right !important;
                    direction: rtl;
                }
                .qr-code{
                    border: 0px solid black;
                    height: 250px;
                    margin-left: 710px;
                    margin-top: -890px;
                
                }
                .head1{
                    width: 28%;
                    
                    color:blue;
                    
                    padding-top: 10px;
                    margin-left: 10px;
                    padding-bottom: 35px;
                }
                .head2{
                    width: 28%;
                    
                    color:blue;
                    
                    padding-top: 10px;
                    margin-left: 10px;
                    padding-bottom: -25px;
                }
                .card{
                    color:#3478bd;
                    font-size: 10px;
                    margin-bottom: -3px;
                    font-family: arial;
                    text-align: center;
                    margin-left: 0px;
                }
                .card2{
                    color:black;
                    float: left;
                    text-align: left;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card12{
                    color:black;
                    margin-top: 9px;
                    margin-bottom: 7px;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card23{
                    color:black;
                    margin-right: 10px;
                    text-align: right;
                    font-size: 10px;
                }
                .card3{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    margin-top: 30px;
                    width: 120%;
                    font-size: 5px;
                }
                .card4{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card5{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                
                
                </style>
                <div style="position: absolute; left:300px; top: 288px; bottom: 0;">
                <img src="images/qr/inter.png"
                    style="width: 130px; height: 130px; margin: 0;" />
            </div>
            <div class="inter">
            
                <div class="head2">
                    <h1 class="card">'. $id_num.'</h1>
                    <h1 class="card12"><small>'. $e_lName.'&nbsp;&nbsp;&nbsp;' .$e_fName.'</small></h1>
                    <h1 class="card">'.$birth.'</h1>
                    <h1 class="card12"><small>'.$nation.'</small></h1>	
                    
                    <h4 class="card3" style="margin-top: 45px" >'.$dose_type.'</h4>
                    <h1 class="card4"><span style="font-size: 7px">Ref : COD - '.$ref.'</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$dose_date.'</h1>
                    <h1 class="card5" style="padding-top: 20" ><small>'.$pass_id.'</small></h1>
                    
                </div>
            </div>
            ';
            break;
        case 3:
            $html = '
                <style>
                
                .padding{
                    margin-top: 0px;
                }
                .inter{
                    background-image: url(images/inter/q.jpg);
                    background-repeat: no-repeat;
                    background-size: 52%, 100%;
                    margin-left: 9.7%;
                    padding-bottom: 28px;
                }
                
                .nation{
                    background-image: url(images/nation/q.jpg);
                    background-repeat: no-repeat;
                    background-size: 52%;
                    margin-left: 9.7%;
                }
                
                img {
                    position: absolute;
                    left: 0;
                    top: 0;
                    z-index: -1;
                    width: 100%;
                }
                .left{
                    float: left;
                }
                .right{
                    float: right;
                    text-align: right;
                    direction: rtl;
                }
                .arab{
                    font-family: arial;
                    text-align: right !important;
                    direction: rtl;
                }
                .qr-code{
                    border: 0px solid black;
                    height: 250px;
                    margin-left: 710px;
                    margin-top: -890px;
                
                }
                .head1{
                    width: 28%;
                    
                    color:blue;
                    
                    padding-top: 10px;
                    margin-left: 10px;
                    padding-bottom: 35px;
                }
                .head2{
                    width: 28%;
                    
                    color:blue;
                    
                    padding-top: 10px;
                    margin-left: 10px;
                    padding-bottom: -25px;
                }
                .card{
                    color:#3478bd;
                    font-size: 10px;
                    margin-bottom: -3px;
                    font-family: arial;
                    text-align: center;
                    margin-left: 0px;
                }
                .card2{
                    color:black;
                    float: left;
                    text-align: left;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card12{
                    color:black;
                    margin-top: 9px;
                    margin-bottom: 7px;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card23{
                    color:black;
                    margin-right: 10px;
                    text-align: right;
                    font-size: 10px;
                }
                .card3{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    margin-top: 29px;
                    width: 120%;
                    font-size: 5px;
                }
                .card4{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                .card5{
                    color:#3478bd;
                    text-align: center;
                    margin-left: 0px;
                    font-size: 10px;
                }
                
                
                </style>
            <div style="position: absolute; left:303px; top: 55px; bottom: 0;">
                    <img src="images/qr/nat.png"
                        style="width: 130px; height: 130px; margin: 0;" />
            </div>
            <div class="nation">
                <div class="head1">

                <h1 class="card" >'.$id_num . '</h1>
                <div style="float: left; width: 65%;">
                    <h1 class="card2"> <small>' . $e_lName. '</small> </h1>
                </div>

                <div style="float: right; width: 30%">
                    <h1 class="card23"><small class="arab">'. mb_strrev1( $a_lName ). '</small></h1>
                </div>

                <div style="clear: both; margin: 0pt; padding: 0pt; "></div>

                <div style="float: left; width: 65%;">
                    <h1 class="card2"> <small>' . $e_fName. '</small> </h1>
                </div>

                <div style="float: right; width:30%">
                    <h1 class="card23"><small class="arab">'. mb_strrev1( $a_fName ). '</small></h1>
                </div>

                <h1 class="card">'. $birth .'</h1>
                <div style="float: left; width: 65%;">
                    <h1 class="card2" style="font-size: 8.5px;"> <small>' . $nation. '</small> </h1>
                </div>

                <div style="float: right; width:30%">
                    <h1 class="card23"><small class="arab">'. mb_strrev1( $a_nation ). '</small></h1>
                </div>

                <h4 class="card3">'.$dose_type.'</h4>

                <h1 class="card4"><span style="font-size: 6.5px;">Ref : COD - '. $ref .'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '. $dose_date.'</h1>
                <h1 class="card5 "><small>&nbsp;&nbsp;'. $pass_id.'</small></h1>
                </div>
            </div>
            
            <div style="position: absolute; left:300px; top: 288px; bottom: 0;">
                        <img src="images/qr/inter.png"
                            style="width: 130px; height: 130px; margin: 0;" />
                    </div>
            <div class="inter">
            
                <div class="head2">
                    <h1 class="card">'. $id_num.'</h1>
                    <h1 class="card12"><small>'. $e_lName.'&nbsp;&nbsp;&nbsp;' .$e_fName.'</small></h1>
                    <h1 class="card">'.$birth.'</h1>
                    <h1 class="card12"><small>'.$nation.'</small></h1>	
                    
                    <h4 class="card3" style="margin-top: 45px" >'.$dose_type.'</h4>
                    <h1 class="card4"><span style="font-size: 7px">Ref : COD - '.$ref.'</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$dose_date.'</h1>
                    <h1 class="card5" style="padding-top: 20" ><small>'.$pass_id.'</small></h1>
                    
                </div>
            </div>
            ';
            // $mpdf->Image('', 100, 100, 210, 297, 'jpg', '', true, false);
            break;

    }
        
     
    //  $mpdf->Image('images/nation/q.jpg', 0, 0, 210, 297, 'jpg', '', true, false,true);

        $pagecount = $mpdf->SetSourceFile('template.pdf');
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        $mpdf->WriteHTML($html);
        
        // $mpdf->SetDisplayMode('fullpage');
        // $mpdf->list_indent_first_level = 0; 
        // //call watermark content and image
        // $mpdf->SetWatermarkText('etutorialspoint');
        // $mpdf->showWatermarkText = true;
        // $mpdf->watermarkTextAlpha = 0.1;

        $mpdf->Output();
        //output in browser
        // $mpdf->Output();	
    }

}

// header("Content-Type: text/html; charset=utf-8");
// ini_set("default_charset", 'utf-8');

// require_once('..\forceutf8\src\ForceUTF8\Encoding.php');
// use \ForceUTF8\Encoding;

?>


