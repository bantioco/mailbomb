<?php

require plugin_dir_path( __FILE__ ).'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class mailbombExcel
{
    private static $initiated = false;
	
	public static function init() 
    {
        if ( ! self::$initiated ) 
        {
			self::initHooks();
		}
	}
	
	/**
	 * Initializes WordPress hooks
	 */
    private static function initHooks() 
    {
        self::$initiated = true;
    }


    public function spreadsheetParseFile( $FILES )
	{
		$result = false;

        if( $FILES )
        {
            $csvfile = $FILES['mailbomb_import_users'];

            $csvFile = $csvfile['tmp_name'];
            $csvType = $csvfile['type'];

            $file_mimes = [
                'application/x-csv', 
                'text/x-csv', 
                'text/csv', 
                'application/csv'
            ];

            if( in_array( $csvType, $file_mimes ) )
            {
                $reader         = IOFactory::createReader('Csv');
                $spreadsheet    = $reader->load($csvFile);
                $sheetDatas     = $spreadsheet->getActiveSheet()->toArray();
                $emails         = [];

                if( $sheetDatas )
                {
                    foreach( $sheetDatas as $key => $data )
                    {
                        if( $key != 0 && $data ) 
                        {
                            $index 			= $key-1;
                            $emails[$index] = $data[0];
                        }
                    }
                    $result = $emails;
                }
            }
        }
		return $result;
    }
    

    public function spreadsheetGenerateFile( $fileFormat="Xlsx", $datas )
	{
        if( $datas )
        {
            $spreadsheet = new Spreadsheet();

            $spreadsheet->setActiveSheetIndex(0)
				->setCellValue("A1",'ID')
                ->setCellValue("B1",'Email')
                ->setCellValue("C1",'Date d\'inscription')
				->setCellValue("D1",'Nombre de newsletter reçu')
                ->setCellValue("E1",'Date de derniere newsletter reçu');
                
            $line = 2;

            foreach( $datas as $data )
            {
                $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$line",$data['id'])
                        ->setCellValue("B$line",$data['email'])
                        ->setCellValue("C$line",$data['created_at'])
                        ->setCellValue("D$line",$data['newsletter_sending'])
                        ->setCellValue("E$line",$data['last_newsletter_sending']);

                $line++;
            }

            foreach(range('A','E') as $columnID) 
            {
                $spreadsheet->getActiveSheet(0)->getColumnDimension($columnID)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->setTitle('Newsletter users');

            $spreadsheet->setActiveSheetIndex(0);

            $writer = IOFactory::createWriter( $spreadsheet, $fileFormat );
            $ext    = strtolower( $fileFormat );

            $writer->save(plugin_dir_path( __FILE__ )."tmp/newsletter_users.".$ext);

            return plugin_dir_url( __FILE__ ).'tmp/newsletter_users.'.$ext;
        }
        return false;
    }
}