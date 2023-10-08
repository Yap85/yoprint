<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductRecord;
use App\Models\FileUpload;
use Log;

class ProcessCSVFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;

    /**
     * Create a new job instance.
     *
     * @param string $filename
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $records = $this->parseCSV($this->filename);
        $upsertRecord = $this->upsertRecords($records);

        if ($upsertRecord == 1) {
            $this->updateStatus($this->filename);
        }
    }

    /**
     * Clean and parse the given CSV file.
     *
     * @param string $filename
     * @return array
     */
    protected function parseCSV($filename)
    {
        $path = storage_path('app/uploads/' . $filename);
        $data = file_get_contents($path);
        $cleaned_data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        $rows = array_map("str_getcsv", explode("\n", $cleaned_data));

        // Removing the header row (assuming first row is header)
        array_shift($rows);

        return $rows;
    }

    /**
     * Upsert the given records into the database.
     *
     * @param array $records
     * @return void
     */
    protected function upsertRecords($records)
    {

        foreach ($records as $record) {
            ProductRecord::updateOrInsert(
                ['UNIQUE_KEY' => (int) $record[0]],
                [
                    'PRODUCT_TITLE' => $record[1],
                    'PRODUCT_DESCRIPTION' => $record[2],
                    'STYLE#' => $record[3],
                    'AVAILABLE_SIZES' => $record[4],
                    'BRAND_LOGO_IMAGE' => $record[5],
                    'THUMBNAIL_IMAGE' => $record[6],
                    'COLOR_SWATCH_IMAGE' => $record[7],
                    'PRODUCT_IMAGE' => $record[8],
                    'SPEC_SHEET' => $record[9],
                    'PRICE_TEXT' => $record[10],
                    'SUGGESTED_PRICE' => (float) $record[11],
                    'CATEGORY_NAME' => $record[12],
                    'SUBCATEGORY_NAME' => $record[13],
                    'COLOR_NAME' => $record[14],
                    'COLOR_SQUARE_IMAGE' => $record[15],
                    'COLOR_PRODUCT_IMAGE' => $record[16],
                    'COLOR_PRODUCT_IMAGE_THUMBNAIL' => $record[17],
                    'SIZE' => $record[18],
                    'QTY' => (int) $record[19],
                    'PIECE_WEIGHT' => (float) $record[20],
                    'PIECE_PRICE' => (float) $record[21],
                    'DOZENS_PRICE' => (float) $record[22],
                    'CASE_PRICE' => (float) $record[23],
                    'PRICE_GROUP' => $record[24],
                    'CASE_SIZE' => $record[25],
                    'INVENTORY_KEY' => (int) $record[26],
                    'SIZE_INDEX' => (int) $record[27],
                    'SANMAR_MAINFRAME_COLOR' => (int) $record[28],
                    'MILL' => (int) $record[29],
                    'PRODUCT_STATUS' => $record[30],
                    'COMPANION_STYLES' => $record[31],
                    'MSRP' => (float) $record[32],
                    'MAP_PRICING' => $record[33],
                    'FRONT_MODEL_IMAGE_URL' => $record[34],
                    'BACK_MODEL_IMAGE' => $record[35],
                    'FRONT_FLAT_IMAGE' => $record[36],
                    'BACK_FLAT_IMAGE' => $record[37],
                    'PRODUCT_MEASUREMENTS' => $record[38],
                    'PMS_COLOR' => $record[39],
                    'GTIN' => floatval($record[40]),
                ]
            );
        }

        return 1;

    }

    public function updateStatus($filename)
    {
        FileUpload::where('filename', $filename)->update(['status' => 'completed']);
    }


}