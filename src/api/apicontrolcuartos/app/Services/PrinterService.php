<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;

final class PrinterService 
{
    private $connector=null;
    /** @var Printer */
    private $printer=null;
    /** @var PrintLayoutTranslator */
    private $layoutSelected=null;
    private $layoutCommands=[
        "!feed",
        "!cut",
        "!justification_left",
        "!justification_center",
        "!justification_right",
        "!justification_reset",
        "!setModeDoubleHeight",
        '!setMode0',
        '!setMode136',
        '!setMode137',
        '!setMode128',
        '!setMode129',
        "!resetMode",
        '!TextSize1',
        '!TextSize2',
        '!TextSize3',
        '!TextSize4',
        '!TextSize41',
        '!TextSize18',
        '!FontA',
        '!FontB',
        '!FontC',
    ];
    
    public function print(){
        if(empty($this->layoutSelected)) throw new Exception("layout not loaded");
        if(empty($this->layoutSelected->getContentExtracted())) return false;

        try {
            $this->printer->initialize();
            foreach ($this->layoutSelected->getContentExtracted() as $value) {
                if($this->isCommand($value)) {
                    $this->applyCommand($value);
                    continue;
                }
                if($this->isCodeBar($value)){
                    $value=$this->getValueCodeBar($value);
                    $this->printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
                    $this->printer->setBarcodeHeight(52);
                    $this->printer->setBarcodeWidth(4);
                    $this->printer->barcode($value,$this->printer::BARCODE_CODE39);
                    continue;
                }
                $this->printer->text($value);
            }
        } catch (\Throwable $th) {
            throw $th;
        }finally{
            try {
                $this->printer->close();
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return true;
    }

    public function prepare() : PrinterService{
        
        $PrinterHost=DB::table('configuraciones')->where('variable','PRINTER_HOST')->select('valor')->first();
        $printerSmbName= DB::table('configuraciones')->where('variable','PRINTER_SMB_NAME')->select('valor')->first();

        if(!isset($PrinterHost->valor) || !isset($printerSmbName->valor)) throw new Exception("printer host or printer smb name is nos configured");

        $profile = CapabilityProfile::load("default");
        $this->connector = new WindowsPrintConnector("smb://".$PrinterHost->valor."/".$printerSmbName->valor);
        $this->printer = new Printer($this->connector, $profile);
        
        return $this;
    }

    public function setLayout(PrintLayoutService $layout) : PrinterService{
        //revisamos si tiene contenido el layout
        if(empty($layout->getContentExtracted())) throw new Exception("plantilla para impresion vacia o no existe");

        $this->layoutSelected=$layout;

        return $this;
    }

    protected function CutPaper(){
        $this->printer->cut();
    }

    protected function closePrinter(){
        $this->printer->close();
    }

    protected function isCommand(string $text){
        return in_array($text,$this->layoutCommands);
    }

    protected function isCodeBar(string $text){
        return str_contains($text,"[codigo_barras");
    }

    protected function getValueCodeBar(string $text){
        $actualValue=str_replace("[codigo_barras","",$text);
        $actualValue=str_replace("]","",$actualValue);
        return trim($actualValue);
    }

	public function applyCommand(string $text)
	{
		if(!$this->isCommand($text)) return false;
        switch ($text) {
            case '!feed':
                $this->printer->feed();
                break;
            case '!cut':
                $this->printer->cut();
                break;
            case '!justification_left':
                $this->printer->setJustification(Printer::JUSTIFY_LEFT);
                break;
            case '!justification_center':
                    $this->printer->setJustification(Printer::JUSTIFY_CENTER);
                    break;
            case '!justification_right':
                    $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
                    break;
            case '!justification_reset':
                    $this->printer->setJustification();
                    break;
            case '!setModeDoubleHeight':
                    $this->printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
                    break;
            case '!setMode0':
                    $this->printer->selectPrintMode(0);
                    break;
            case '!setMode136':
                   $this->printer->selectPrintMode(136);
                   break;
            case '!setMode137':
                    $this->printer->selectPrintMode(137);
                    break;
            case '!setMode128':
                    $this->printer->selectPrintMode(128);
                    break;
            case '!setMode129':
                    $this->printer->selectPrintMode(129);
                    break;
            case '!TextSize1':
                    $this->printer->setTextSize(1,1);
                    break;
            case '!TextSize2':
                    $this->printer->setTextSize(2,2);
                    break;
            case '!TextSize3':
                    $this->printer->setTextSize(3,3);
                    break;
            case '!TextSize4':
                    $this->printer->setTextSize(4,4);
                    break;
            case '!TextSize41':
                    $this->printer->setTextSize(4,1);
                    break;
            case '!TextSize18':
                    $this->printer->setTextSize(1,8);
                    break;
            case '!resetMode':
                    $this->printer->selectPrintMode();
                    break;
            case '!FontA':
                    $this->printer->setFont($this->printer::FONT_A);
                    break;
            case '!FontB':
                    $this->printer->setFont($this->printer::FONT_B);
                    break;
            case '!FontC':
                    $this->printer->setFont($this->printer::FONT_C);
                    break;
            default:
                # code...
                break;
        }
        return true;
	}
}
