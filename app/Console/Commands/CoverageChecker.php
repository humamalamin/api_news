<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Code coverage of this class is not necessary
 *
 * @codeCoverageIgnore
 */
class CoverageChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:coverage-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check code coverage report from phpunit';

    protected $files;

    protected $coverageFile = 'coverage.txt';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $coverageMin = getenv('COVERAGE_MIN');

        if (!$this->files->exists($this->coverageFile)) {
            $this->error('Coverage file not found.');
        }

        // parse coverage file
        $coverageFile = $this->files->get($this->coverageFile);
        $coverageFile = trim($coverageFile);

        $explode = array_map(function ($txt) {
            return trim(preg_replace('/\s+/', ' ', $txt));
        }, explode("\n", $coverageFile));

        $txtlineCoverage = last($explode);
        if (strpos($txtlineCoverage, '100.00%')) {
            $coverageResult = '100';
        } else {
            $lineCoverage = [];
            preg_match("/\b(?<!\.)(?!0+(?:\.0+)?%)(?:\d|[1-9]\d|100)(?:(?<!100)\.\d+)/", $txtlineCoverage, $lineCoverage);
            $coverageResult = $lineCoverage[0];
        }

        if ($coverageResult < $coverageMin) {
            $this->error('Code coverage results ('. $coverageResult . '%) are below the specified standard (' . $coverageMin . '%)');
        } else {
            $this->info('Code coverage results ' . $coverageResult . '%');
        }
    }
}
