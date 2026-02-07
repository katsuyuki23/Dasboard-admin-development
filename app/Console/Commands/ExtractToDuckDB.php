<?php
// app/Console/Commands/ExtractToDuckDB.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DuckDBService;

class ExtractToDuckDB extends Command
{
    protected $signature = 'duckdb:extract';
    protected $description = 'Extract data from MySQL to DuckDB Local Analytics';
    
    protected $duckDB;
    
    public function __construct(DuckDBService $duckDB)
    {
        parent::__construct();
        $this->duckDB = $duckDB;
    }
    
    public function handle()
    {
        $this->info("Starting ETL process to DuckDB...");
        
        try {
            $this->duckDB->runETL();
            $this->info("DuckDB ETL process completed successfully!");
        } catch (\Exception $e) {
            $this->error("ETL failed: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
