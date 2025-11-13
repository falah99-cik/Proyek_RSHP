<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAssetsFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat folder assets (css, js, images) di dalam folder public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paths = [
            public_path('assets'),
            public_path('assets/css'),
            public_path('assets/js'),
            public_path('assets/images'),
        ];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("📁 Folder dibuat: {$path}");
            } else {
                $this->line("✅ Folder sudah ada: {$path}");
            }
        }

        $this->info('🎉 Semua folder assets berhasil dibuat!');
    }
}
