<?php

namespace App\Services;

class PrintLayoutService  {

    private $layout;
    private $contentExtracted=array ();
    private $dataMap=array();
    
    /**
     * Class constructor.
     */
    public function __construct()
    {
    }

    public function ExtractContent():PrintLayoutService{

        if(!file_exists($this->layout)) return $this;

        $content= file_get_contents($this->layout);
        $content= $this->setVariablesContent($content);
        $this->contentExtracted = explode(PHP_EOL,$content);
        
        return $this;
    }

    /**
     * Set the value of layout
     *
     * @return  self
     */ 
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get the value of contentExtracted
     */ 
    public function getContentExtracted()
    {
        return $this->contentExtracted;
    }

    /**
     * Get the value of dataMap
     */ 
    public function getData()
    {
        return $this->dataMap;
    }

    /**
     * Set the value of dataMap
     *
     * @return  self
     */ 
    public function setData($dataMap)
    {
        $this->dataMap = $dataMap;

        return $this;
    }

    private function setVariablesContent(string $content) : string {

        foreach($this->dataMap as $key=>$value){
            if(strpos($content,"@".$key)!==false){
                $content= str_replace("@".$key,$value,$content);
            }
        }

        return $content;

    }
}