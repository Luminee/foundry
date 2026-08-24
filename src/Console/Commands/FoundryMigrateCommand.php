<?php

namespace Luminee\Foundry\Console\Commands;

use Illuminate\Console\Command;

class FoundryMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'foundry:migrate 
                            {vendor : Vendor name, e.g. luminee/watchdog}
                            {--print : Print migration SQL content}
                            {--run : Execute migrations directly}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Foundry package migrations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $vendorName = $this->argument('vendor');
        $vendor = vendor($vendorName);

        if (!$vendor) {
            $this->error("Vendor [$vendorName] not found.");
            return;
        }

        $migrationsDir = $vendor->base_path . '/src/Database/migrations';

        if (!is_dir($migrationsDir)) {
            $this->warn("No migrations directory found for [$vendorName].");
            return;
        }

        $files = scandir($migrationsDir);

        if ($this->option('run')) {
            $this->call('migrate', ['--path' => 'vendor/' . $vendorName . '/src/Database/migrations']);
            return;
        }

        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            if ($this->option('print')) {
                $this->comment("=== $file ===");
                $this->line(file_get_contents($migrationsDir . '/' . $file));
            } else {
                $this->line("  <info>$file</info>");
            }
        }

        if (!$this->option('print')) {
            $this->line('');
            $this->info('Use --print to preview SQL, --run to execute.');
        }
    }
}