<?php 
class Water 
{ 
    protected $groundImage; 
    protected $groundImageWidth; 
    protected $groundImageHeight; 
    protected $groundImageHandle; 
    protected $waterType; 
    protected $waterPosType; 
    protected $waterPosX; 
    protected $waterPosY; 
    protected $waterWidth; 
    protected $waterHeight; 
    protected $waterImage; 
    protected $waterImageType; 
    protected $waterImageHandle; 
    protected $waterImageAlpha; 
    protected $waterText; 
    protected $waterTextColor; 
    protected $waterTextSize; 
    protected $waterTextFont; 
    protected $errorMsg;

    function __construct($groundImage = "", $waterPosType = 0,$alpha) 
    { 
        if(false == file_exists($groundImage)) 
        { 
            $this->throwError("groundImage404"); 
        } 
        
        $this->checkGD(); 
        $this->groundImage   = &$groundImage;         
        $this->waterPosType = &$waterPosType; 
        $this->waterImageAlpha = $alpha;
        $this->setGroundImageInfo(); 
    }

    function checkGD() 
    { 
        if(false == function_exists("gd_info")) 
        { 
            $this->throwError("NonGD"); 
        } 
    }

    function setGroundImageInfo() 
    { 
        $groundImageType   = getimagesize($this->groundImage); 
        $this->groundImageWidth = $groundImageType[0]; 
        $this->groundImageHeight = $groundImageType[1]; 
        $this->groundImageType   = $groundImageType[2]; 
        if($this->groundImageWidth < 150 or $this->groundImageHeight < 150) 
        { 
           // $this->throwError("TooSmall"); 
		return false;
        } 
    }

    function setWaterTextInfo($waterText = "mailzsm@126.com",$waterTextColor = "#000000", $waterTextSize = "5", $waterTextFont = "simhei.ttf") 
    { 
        $this->waterType     = 0; 
        if(strlen($waterTextColor) == 7) 
        { 
            $this->waterTextColor = &$waterTextColor; 
        } 
        else 
        { 
            $this->throwError("WrongColor"); 
        } 
        $this->waterText       =   &$waterText; 
        $this->waterTextSize   =   &$waterTextSize; 
        $this->waterTextFont   =   &$waterTextFont; 
        $waterTextInfo = imagettfbbox(ceil($this->waterTextSize*1.2), 1, $this->waterTextFont, $this->waterText); 
        $this->waterWidth    = $waterTextInfo[4] - $waterTextInfo[6]; 
        $this->waterHeight   = $waterTextInfo[1] - $waterTextInfo[7]; 
        unset($waterTextInfo); 
    }

    function setWaterImageInfo($waterImage = "logo.gif") 
    { 
    
        if(file_exists($waterImage)) 
        { 
            
        	$this->waterType       =   1; 
            $this->waterImage      =   &$waterImage; 
            $waterImageInfo        =   getimagesize($this->waterImage); 
            $this->waterWidth      =   $waterImageInfo[0]; 
            $this->waterHeight     =   $waterImageInfo[1]; 
            $this->waterImageType =   $waterImageInfo[2]; 
            unset($waterImageInfo); 
        } 
        else 
        { 
            $this->throwError("waterImage404"); 
        } 
    }

    function setWaterPos() 
    { 
        switch($this->waterPosType) 
        { 
            case 0://随机 
                $this->waterPosX = rand(0,($this->groundImageWidth - $this->waterWidth)); 
                $this->waterPosY = rand(0,($this->groundImageHeight - $this->waterHeight)); 
                break; 
            case 1://1为顶端居左 
                $this->waterPosX = 0; 
                $this->waterPosY = 0; 
                break; 
            case 2://2为顶端居中 
                $this->waterPosX = ($this->groundImageWidth - $this->waterWidth) / 2; 
                $this->waterPosY = 0; 
                break; 
            case 3://3为顶端居右 
                $this->waterPosX = $this->groundImageWidth - $this->waterWidth; 
                $this->waterPosY = 0; 
                break; 
            case 4://4为中部居左 
                $this->waterPosX = 0; 
                $this->waterPosY = ($this->groundImageHeight - $this->waterHeight) / 2; 
                break; 
            case 5://5为中部居中 
                $this->waterPosX = ($this->groundImageWidth - $this->waterWidth) / 2; 
                $this->waterPosY = ($this->groundImageHeight - $this->waterHeight) / 2; 
                break; 
            case 6://6为中部居右 
                $this->waterPosX = $this->groundImageWidth - $this->waterWidth; 
                $this->waterPosY = ($this->groundImageHeight - $this->waterHeight) / 2; 
                break; 
            case 7://7为底端居左 
                $this->waterPosX = 0; 
                $this->waterPosY = $this->groundImageHeight - $this->waterHeight * rand(115,125) / 100; 
                break; 
            case 8://8为底端居中 
                $this->waterPosX = ($this->groundImageWidth - $this->waterWidth) / 2; 
                $this->waterPosY = $this->groundImageHeight - $this->waterHeight * rand(115,125) / 100; 
                break; 
            case 9://9为底端居右 
                $this->waterPosX = $this->groundImageWidth - $this->waterWidth; 
                $this->waterPosY = $this->groundImageHeight - $this->waterHeight * rand(115,120) / 100; 
                break; 
            default://随机 
                $this->waterPosX = rand(0,($this->groundImageWidth - $this->waterWidth)); 
                $this->waterPosY = rand(0,($this->groundImageHeight - $this->waterHeight));      
        } 
    }

    function setGroundImageHandle() 
    { 
        switch($this->groundImageType) 
        { 
            case 1: 
                $this->groundImageHandle = imagecreatefromgif($this->groundImage); 
                break; 
            case 2: 
                $this->groundImageHandle = imagecreatefromjpeg($this->groundImage); 
                break; 
            case 3: 
                $this->groundImageHandle = imagecreatefrompng($this->groundImage); 
                break; 
            default: 
                $this->throwError("NonType"); 
        } 
    }

    function setWaterImageHandle() 
    { 
        switch($this->waterImageType) 
        { 
            case 1: 
                $this->waterImageHandle = imagecreatefromgif($this->waterImage); 
                break; 
            case 2: 
                $this->waterImageHandle = imagecreatefromjpeg($this->waterImage); 
                break; 
            case 3: 
                $this->waterImageHandle = imagecreatefrompng($this->waterImage); 
                break; 
            default: 
                $this->throwError("NonType"); 
        } 
    }

    function putWateredImage($extFileName) 
    { 
        switch($this->groundImageType) 
        { 
            case 1: 
                imagegif($this->groundImageHandle, $extFileName.$this->groundImage); 
                break; 
            case 2: 
                imagejpeg($this->groundImageHandle, $extFileName.$this->groundImage); 
                break; 
            case 3: 
                imagepng($this->groundImageHandle, $extFileName.$this->groundImage); 
                break; 
            default: 
                $this->throwError("NonType"); 
        }         
    }

    function destroyHandle() 
    { 
        imagedestroy($this->groundImageHandle); 
        if(isset($this->waterImageHandle)) 
        { 
            imagedestroy($this->waterImageHandle); 
        } 
    }

    function makeWater($extFileName = "") 
    { 
        $this->setGroundImageHandle(); 
        $this->setWaterPos(); 
        imagealphablending($this->groundImageHandle, true); 
        if($this->waterType == 0) 
        { 
        	
            imagettftext($this->groundImageHandle, $this->waterTextSize, 0, $this->waterPosX, $this->waterHeight + $this->waterPosY, imagecolorallocate($this->groundImageHandle, hexdec(substr($this->waterTextColor,1,2)), hexdec(substr($this->waterTextColor,3,2)), hexdec(substr($this->waterTextColor,5,2))), $this->waterTextFont, $this->waterText); 
        } 
        else 
        { 
        	
            $this->setWaterImageHandle();
            
            imagecopymerge($this->groundImageHandle,$this->waterImageHandle , $this->waterPosX, $this->waterPosY, 0, 0, $this->waterWidth,$this->waterHeight,$this->waterImageAlpha); 
        } 
        //@unlink($this->groundImage); 
        $this->putWateredImage($extFileName); 
        $this->destroyHandle(); 
    }

    function throwError($errType) 
    { 
        switch($errType) 
        { 
            case "TooSmall": 
                $this->errorMsg = "要打水印图片太小"; 
                break; 
            case "groundImage404": 
                $this->errorMsg = "要打水印图片不存在"; 
                break; 
            case "waterImage404": 
                $this->errorMsg = "水印图片不存在"; 
                break; 
            case "NonGD": 
                $this->errorMsg = "没有安装GD库"; 
                break; 
            case "NonType": 
                $this->errorMsg = "不支持的文件格式"; 
                break; 
            case "WrongColor": 
                $this->errorMsg = "错误的颜色格式"; 
                break; 
            default: 
                $this->errorMsg = "未知错误"; 
        } 
             
        die($this->errorMsg); 
        exit(); 
    } 
}
