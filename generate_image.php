<?php 
ini_set('max_execution_time', 300);
ini_set('display_errors',1);
require_once("include/config_inc.php");
$switchBool=true;

$passedImageNameStr= isset($_GET['image']) ? $_GET['image'] : '';
$txtImageDirectory='images/images_data_as_txt/';
$generateImageDirectory='images/products/';

if (is_dir($txtImageDirectory)) {
    if($switchBool){
        $lastpos = strrpos($passedImageNameStr, ".");
        if ($lastpos !== false) {
            $FileNameStr=substr($passedImageNameStr,0,$lastpos) ;
            $fileExtensionStr=substr($passedImageNameStr,$lastpos+1) ;
            $findTxtFile=$txtImageDirectory.$FileNameStr.'.txt';
            //echo $findTxtFile;
            if(file_exists($findTxtFile)){
                $getFileContents=file_get_contents($findTxtFile);
                $uncompressed = gzuncompress($getFileContents);
                $decodeImageBlob=base64_decode($uncompressed);
                if (is_dir($generateImageDirectory)) {
                    $generateFilePath=$generateImageDirectory.$passedImageNameStr;
                    $generateImageBool=file_put_contents($generateFilePath, $decodeImageBlob);
                    if($generateImageBool){
                        echo 'Generated Image: '.$generateImageDirectory.$passedImageNameStr;
                    }else{
                         echo 'Failed to generate: '.$passedImageNameStr;
                    }
                }else{
                     echo 'No '.$generateImageDirectory.' such directory exists!';
                }
            }else{
                echo 'No txt file found for the '.$passedImageNameStr;
            }
        }else{
             echo 'Error';
        }
    }else{
        $findTxtFile=$txtImageDirectory.$passedImageNameStr.'.txt';
        if(file_exists($findTxtFile)){
            $getFileContents=file_get_contents($findTxtFile);
            $uncompressed = gzuncompress($getFileContents);
            $decodeImageBlob=base64_decode($uncompressed);
            if (is_dir($generateImageDirectory)) {
                $generateFilePath=$generateImageDirectory.$passedImageNameStr;
                $generateImageBool=file_put_contents($generateFilePath, $decodeImageBlob);
                if($generateImageBool){
                    echo 'Generated Image: '.$generateImageDirectory.$passedImageNameStr;
                }else{
                     echo 'Failed to generate: '.$passedImageNameStr;
                }
            }else{
                 echo 'No '.$generateImageDirectory.' such directory exists!';
            }
        }else{
            echo 'No txt file found for the '.$passedImageNameStr;
        }
    }
}else{
    echo 'No '.$txtImageDirectory.' such directory exists!';
}
?>