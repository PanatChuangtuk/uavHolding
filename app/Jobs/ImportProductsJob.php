<?php

namespace App\Jobs;

use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{SerializesModels, InteractsWithQueue};
use Illuminate\Support\Facades\Log;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $file;


    public function __construct($type, $file)
    {
        $this->type = $type;
        $this->file = $file;
    }

    public function handle()
    {
        $filePath = public_path('upload/' . $this->file);
        Excel::import(new ProductImport($this->type), $filePath);
        Log::info('Products imported successfully.');
    }
}
