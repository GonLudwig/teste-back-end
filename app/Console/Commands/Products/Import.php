<?php

namespace App\Console\Commands\Products;

use Illuminate\Console\Command;

class Import extends Command
{
    protected $signature = 'products:import {--id=* : External IDs of the products to import}';

    protected $description = 'Import products from an external API';

    public function handle()
    {
        //
    }
}
