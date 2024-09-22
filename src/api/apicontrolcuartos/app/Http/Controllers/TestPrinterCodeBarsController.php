<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

final class TestPrinterCodeBarsController extends Controller
{


    public function TestPrinterCodeBar(){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);
        $PrinterHost=DB::table('configuraciones')->where('variable','PRINTER_HOST')->select('valor')->first();
        $printerSmbName= DB::table('configuraciones')->where('variable','PRINTER_SMB_NAME')->select('valor')->first();

        if(!isset($PrinterHost->valor) || !isset($printerSmbName->valor)) throw new Exception("printer host or printer smb name is nos configured");

        $profile = CapabilityProfile::load("simple"); 
        $connector = new WindowsPrintConnector("smb://".$PrinterHost->valor."/".$printerSmbName->valor);
        $printer = new Printer($connector);
        
        /* Height and width */
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Height and bar width\n");
        $printer->selectPrintMode();
        $heights = array(1, 2, 4, 8, 16, 32);
        $widths = array(1, 2, 3, 4, 5, 6, 7, 8);
        $printer -> text("Default look\n");
        $printer->barcode("ABC", Printer::BARCODE_CODE39);
        
        foreach($heights as $height) {
            $printer -> text("\nHeight $height\n");
            $printer->setBarcodeHeight($height);
            $printer->barcode("ABC", Printer::BARCODE_CODE39);
        }
        foreach($widths as $width) {
            $printer -> text("\nWidth $width\n");
            $printer->setBarcodeWidth($width);
            $printer->barcode("ABC", Printer::BARCODE_CODE39);
        }
        $printer->feed();
        // Set to something sensible for the rest of the examples
        $printer->setBarcodeHeight(40);
        $printer->setBarcodeWidth(2);
        
        /* Text position */
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Text position\n");
        $printer->selectPrintMode();
        $hri = array (
            Printer::BARCODE_TEXT_NONE => "No text",
            Printer::BARCODE_TEXT_ABOVE => "Above",
            Printer::BARCODE_TEXT_BELOW => "Below",
            Printer::BARCODE_TEXT_ABOVE | Printer::BARCODE_TEXT_BELOW => "Both"
        );
        foreach ($hri as $position => $caption) {
            $printer->text($caption . "\n");
            $printer->setBarcodeTextPosition($position);
            $printer->barcode("012345678901", Printer::BARCODE_JAN13);
            $printer->feed();
        }
        
        /* Barcode types */
        $standards = array (
                Printer::BARCODE_UPCA => array (
                        "title" => "UPC-A",
                        "caption" => "Fixed-length numeric product barcodes.",
                        "example" => array (
                                array (
                                        "caption" => "12 char numeric including (wrong) check digit.",
                                        "content" => "012345678901"
                                ),
                                array (
                                        "caption" => "Send 11 chars to add check digit automatically.",
                                        "content" => "01234567890"
                                )
                        )
                ),
                Printer::BARCODE_UPCE => array (
                        "title" => "UPC-E",
                        "caption" => "Fixed-length numeric compact product barcodes.",
                        "example" => array (
                                array (
                                        "caption" => "6 char numeric - auto check digit & NSC",
                                        "content" => "123456"
                                ),
                                array (
                                        "caption" => "7 char numeric - auto check digit",
                                        "content" => "0123456"
                                ),
                                array (
                                        "caption" => "8 char numeric",
                                        "content" => "01234567"
                                ),
                                array (
                                        "caption" => "11 char numeric - auto check digit",
                                        "content" => "01234567890"
                                ),
                                array (
                                        "caption" => "12 char numeric including (wrong) check digit",
                                        "content" => "012345678901"
                                )
                        )
                ),
                Printer::BARCODE_JAN13 => array (
                        "title" => "JAN13/EAN13",
                        "caption" => "Fixed-length numeric barcodes.",
                        "example" => array (
                                array (
                                        "caption" => "12 char numeric - auto check digit",
                                        "content" => "012345678901"
                                ),
                                array (
                                        "caption" => "13 char numeric including (wrong) check digit",
                                        "content" => "0123456789012"
                                )
                        )
                ),
                Printer::BARCODE_JAN8 => array (
                        "title" => "JAN8/EAN8",
                        "caption" => "Fixed-length numeric barcodes.",
                        "example" => array (
                                array (
                                        "caption" => "7 char numeric - auto check digit",
                                        "content" => "0123456"
                                ),
                                array (
                                        "caption" => "8 char numeric including (wrong) check digit",
                                        "content" => "01234567"
                                )
                        )
                ),
                Printer::BARCODE_CODE39 => array (
                        "title" => "Code39",
                        "caption" => "Variable length alphanumeric w/ some special chars.",
                        "example" => array (
                                array (
                                        "caption" => "Text, numbers, spaces",
                                        "content" => "ABC 012"
                                ),
                                array (
                                        "caption" => "Special characters",
                                        "content" => "$%+-./"
                                ),
                                array (
                                        "caption" => "Extra char (*) Used as start/stop",
                                        "content" => "*TEXT*"
                                )
                        )
                ),
                Printer::BARCODE_ITF => array (
                        "title" => "ITF",
                        "caption" => "Variable length numeric w/even number of digits,\nas they are encoded in pairs.",
                        "example" => array (
                                array (
                                        "caption" => "Numeric- even number of digits",
                                        "content" => "0123456789"
                                )
                        )
                ),
                Printer::BARCODE_CODABAR => array (
                        "title" => "Codabar",
                        "caption" => "Varaible length numeric with some allowable\nextra characters. ABCD/abcd must be used as\nstart/stop characters (one at the start, one\nat the end) to distinguish between barcode\napplications.",
                        "example" => array (
                                array (
                                        "caption" => "Numeric w/ A A start/stop. ",
                                        "content" => "A012345A"
                                ),
                                array (
                                        "caption" => "Extra allowable characters",
                                        "content" => "A012$+-./:A"
                                )
                        )
                ),
                Printer::BARCODE_CODE93 => array (
                        "title" => "Code93",
                        "caption" => "Variable length- any ASCII is available",
                        "example" => array (
                                array (
                                        "caption" => "Text",
                                        "content" => "012abcd"
                                )
                        )
                ),
                Printer::BARCODE_CODE128 => array (
                        "title" => "Code128",
                        "caption" => "Variable length- any ASCII is available",
                        "example" => array (
                                array (
                                        "caption" => "Code set A uppercase & symbols",
                                        "content" => "{A" . "012ABCD"
                                ),
                                array (
                                        "caption" => "Code set B general text",
                                        "content" => "{B" . "012ABCDabcd"
                                ),
                                array (
                                        "caption" => "Code set C compact numbers\n Sending chr(21) chr(32) chr(43)",
                                        "content" => "{C" . chr(21) . chr(32) . chr(43)
                                )
                        )
                )
        );
        $printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
        foreach ($standards as $type => $standard) {
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
            $printer->text($standard ["title"] . "\n");
            $printer->selectPrintMode();
            $printer->text($standard ["caption"] . "\n\n");
            foreach ($standard ["example"] as $id => $barcode) {
                $printer->setEmphasis(true);
                $printer->text($barcode ["caption"] . "\n");
                $printer->setEmphasis(false);
                $printer->text("Content: " . $barcode ["content"] . "\n");
                $printer->barcode($barcode ["content"], $type);
                $printer->feed();
            }
        }
        $printer->cut();
        $printer->close();
    }


    public function TestPrinterTextSize()
    {
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $PrinterHost=DB::table('configuraciones')->where('variable','PRINTER_HOST')->select('valor')->first();
        $printerSmbName= DB::table('configuraciones')->where('variable','PRINTER_SMB_NAME')->select('valor')->first();

        if(!isset($PrinterHost->valor) || !isset($printerSmbName->valor)) throw new Exception("printer host or printer smb name is nos configured");

        //$profile = CapabilityProfile::load("simple"); 
        $connector = new WindowsPrintConnector("smb://".$PrinterHost->valor."/".$printerSmbName->valor);
        $printer = new Printer($connector);
        /* Initialize */
        $printer->initialize();

        /* Text of various (in-proportion) sizes */
        $this->title($printer, "Change height & width\n");
        for ($i = 1; $i <= 8; $i++) {
            $printer->setTextSize($i, $i);
            $printer->text($i);
        }
        $printer->text("\n");

        /* Width changing only */
        $this->title($printer, "Change width only (height=4):\n");
        for ($i = 1; $i <= 8; $i++) {
            $printer->setTextSize($i, 4);
            $printer->text($i);
        }
        $printer->text("\n");

        /* Height changing only */
        $this->title($printer, "Change height only (width=4):\n");
        for ($i = 1; $i <= 8; $i++) {
            $printer->setTextSize(4, $i);
            $printer->text($i);
        }
        $printer->text("\n");

        /* Very narrow text */
        $this->title($printer, "Very narrow text:\n");
        $printer->setTextSize(1, 8);
        $printer->text("The quick brown fox jumps over the lazy dog.\n");

        /* Very flat text */
        $this->title($printer, "Very wide text:\n");
        $printer->setTextSize(4, 1);
        $printer->text("Hello world!\n");

        /* Very large text */
        $this->title($printer, "Largest possible text:\n");
        $printer->setTextSize(8, 8);
        $printer->text("Hello\nworld!\n");

        $printer->cut();
        $printer->close();
    }


    public function demoExample(){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $PrinterHost = DB::table('configuraciones')->where('variable', 'PRINTER_HOST')->select('valor')->first();
        $printerSmbName = DB::table('configuraciones')->where('variable', 'PRINTER_SMB_NAME')->select('valor')->first();

        if (!isset($PrinterHost->valor) || !isset($printerSmbName->valor)) throw new Exception("printer host or printer smb name is nos configured");

        //$profile = CapabilityProfile::load("simple"); 
        $connector = new WindowsPrintConnector("smb://" . $PrinterHost->valor . "/" . $printerSmbName->valor);
        $printer = new Printer($connector);

        /* Initialize */
        $printer->initialize();

        /* Text */
        $printer->text("Hello world\n");
        $printer->cut();

        /* Line feeds */
        $printer->text("ABC");
        $printer->feed(7);
        $printer->text("DEF");
        $printer->feedReverse(3);
        $printer->text("GHI");
        $printer->feed();
        $printer->cut();

        /* Font modes */
        $modes = array(
            Printer::MODE_FONT_B,
            Printer::MODE_EMPHASIZED,
            Printer::MODE_DOUBLE_HEIGHT,
            Printer::MODE_DOUBLE_WIDTH,
            Printer::MODE_UNDERLINE
        );
        for ($i = 0; $i < pow(2, count($modes)); $i++) {
            $bits = str_pad(decbin($i), count($modes), "0", STR_PAD_LEFT);
            $mode = 0;
            for ($j = 0; $j < strlen($bits); $j++) {
                if (substr($bits, $j, 1) == "1") {
                    $mode |= $modes[$j];
                }
            }
            $printer->selectPrintMode($mode);
            $printer->text($mode);
            $printer->feed();
            $printer->text("ABCDEFGHIJabcdefghijk\n");
        }
        $printer->selectPrintMode(); // Reset
        $printer->cut();

        /* Underline */
        for ($i = 0; $i < 3; $i++) {
            $printer->setUnderline($i);
            $printer->text("The quick brown fox jumps over the lazy dog\n");
        }
        $printer->setUnderline(0); // Reset
        $printer->cut();

        /* Cuts */
        $printer->text("Partial cut\n(not available on all printers)\n");
        $printer->cut(Printer::CUT_PARTIAL);
        $printer->text("Full cut\n");
        $printer->cut(Printer::CUT_FULL);

        /* Emphasis */
        for ($i = 0; $i < 2; $i++) {
            $printer->setEmphasis($i == 1);
            $printer->text("The quick brown fox jumps over the lazy dog\n");
        }
        $printer->setEmphasis(false); // Reset
        $printer->cut();

        /* Double-strike (looks basically the same as emphasis) */
        for ($i = 0; $i < 2; $i++) {
            $printer->setDoubleStrike($i == 1);
            $printer->text("The quick brown fox jumps over the lazy dog\n");
        }
        $printer->setDoubleStrike(false);
        $printer->cut();

        /* Fonts (many printers do not have a 'Font C') */
        $fonts = array(
            Printer::FONT_A,
            Printer::FONT_B,
            Printer::FONT_C
        );
        for ($i = 0; $i < count($fonts); $i++) {
            $printer->setFont($fonts[$i]);
            $printer->text("The quick brown fox jumps over the lazy dog\n");
        }
        $printer->setFont(); // Reset
        $printer->cut();

        /* Justification */
        $justification = array(
            Printer::JUSTIFY_LEFT,
            Printer::JUSTIFY_CENTER,
            Printer::JUSTIFY_RIGHT
        );
        for ($i = 0; $i < count($justification); $i++) {
            $printer->setJustification($justification[$i]);
            $printer->text("A man a plan a canal panama\n");
        }
        $printer->setJustification(); // Reset
        $printer->cut();

        /* Barcodes - see barcode.php for more detail */
        $printer->setBarcodeHeight(80);
        $printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
        $printer->barcode("9876");
        $printer->feed();
        $printer->cut();

        /* Graphics - this demo will not work on some non-Epson printers */
        try {
            $logo = EscposImage::load("resources/escpos-php.png", false);
            $imgModes = array(
                Printer::IMG_DEFAULT,
                Printer::IMG_DOUBLE_WIDTH,
                Printer::IMG_DOUBLE_HEIGHT,
                Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT
            );
            foreach ($imgModes as $mode) {
                $printer->graphics($logo, $mode);
            }
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();

        /* Bit image */
        try {
            $logo = EscposImage::load("resources/escpos-php.png", false);
            $imgModes = array(
                Printer::IMG_DEFAULT,
                Printer::IMG_DOUBLE_WIDTH,
                Printer::IMG_DOUBLE_HEIGHT,
                Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT
            );
            foreach ($imgModes as $mode) {
                $printer->bitImage($logo, $mode);
            }
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();

        /* QR Code - see also the more in-depth demo at qr-code.php */
        $testStr = "Testing 123";
        $models = array(
            Printer::QR_MODEL_1 => "QR Model 1",
            Printer::QR_MODEL_2 => "QR Model 2 (default)",
            Printer::QR_MICRO => "Micro QR code\n(not supported on all printers)"
        );
        foreach ($models as $model => $name) {
            $printer->qrCode($testStr, Printer::QR_ECLEVEL_L, 3, $model);
            $printer->text("$name\n");
            $printer->feed();
        }
        $printer->cut();

        /* Pulse */
        $printer->pulse();

        /* Always close the printer! On some PrintConnectors, no actual
        * data is sent until the printer is closed. */
        $printer->close();
    }

    private function title(Printer $printer, $text)
    {
        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->text("\n" . $text);
        $printer->selectPrintMode(); // Reset
    }
}
