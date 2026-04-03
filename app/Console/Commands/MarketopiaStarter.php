<?php

namespace App\Console\Commands;

use App\Jobs\TranslateStateName;
use App\Models\CarBrand;
use App\Models\Marketopia\MarketopiaBrowser;
use App\Models\Marketopia\MarketopiaContinent;
use App\Models\Marketopia\MarketopiaCountry;
use App\Models\Marketopia\MarketopiaOperatingSystem;
use App\Models\Marketopia\MarketopiaState;
use App\Models\Marketopia\MarketopiaSubContinent;
use Datlechin\GoogleTranslate\Facades\GoogleTranslate;
use GuzzleHttp\Promise\Promise;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class MarketopiaStarter extends Command
{
    protected $signature = 'marketopia:starter';
    protected $description = 'Create controllers with models for Marketopia with a progress bar and execute an optional SQL file';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        // Read Json file from public path
        $data = json_decode(File::get(public_path('mar/marketopia_cities.json')), true);
        // Initialize a progress bar
        $bar = $this->output->createProgressBar(count($data));

        // Dispatch jobs for each state
        foreach ($data as $value) {
            CarBrand::create($value);
            // Advance the progress bar
            $bar->advance();
        }

        // Finish the progress bar
        $bar->finish();

        // Output that the process is completed
        $this->info("\nMarketopia country data has been added successfully.");
    }

    // Function to handle translation and updating state name asynchronously
    /*

    public function m4()
    {

        $data = include public_path('brands.php');
        $dat = [];
        try {
            foreach ($data as $key => $value) {
                $dat = [$key, $value];

                $article_data = [
                    'en' => [
                        'name' => $value['name'],
                    ],
                    'ar' => [
                        'name' => $value['name_ar'],
                    ],
                ];
                MarketopiaCountry::find($value['id'])->update($article_data);
            }
        } catch (\Throwable $th) {
            dd($dat);
        }
    }
    public function handle()
    {

        # Read Json file from public path
        $data = json_decode(File::get(public_path('mar/richness_states.json')), true);
        # Start a progress bar
        $bar = $this->output->createProgressBar(count($data));
        # Loop through the data
        foreach ($data as $key => $value) {
                # Create a new instance of MarketopiaState
                $result = GoogleTranslate::withSource('en')
                ->withTarget('ar')
                ->translate($value['name']);
                MarketopiaState::find($value['id'])->update([
                    'ar' => [
                        'name' => $result->getAlternativeTranslations()[0],
                    ]
                ]);
            
            # Advance the progress
            $bar->advance();
        }

        # Finish the progress bar
        $bar->finish();

        # Output that the process is completed
        $this->info("\nMarketopia country data has been added successfully.");
    }
*/
}