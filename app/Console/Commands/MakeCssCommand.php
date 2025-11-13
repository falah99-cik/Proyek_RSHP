<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCssCommand extends Command
{
    /**
     * Nama dan signature dari command artisan.
     *
     * Contoh penggunaan:
     * php artisan make:css main
     */
    protected $signature = 'make:css {name : Nama file CSS (tanpa .css)}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Membuat file CSS di folder public/assets/css';

    /**
     * Jalankan perintah.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = public_path("assets/css/{$name}.css");

        // Pastikan foldernya ada
        if (!File::exists(public_path('assets/css'))) {
            File::makeDirectory(public_path('assets/css'), 0755, true);
        }

        // Jika file sudah ada
        if (File::exists($path)) {
            $this->error("❌ File {$name}.css sudah ada!");
            return Command::FAILURE;
        }

        // Buat file CSS dengan isi awal default
        File::put($path, "/* {$name}.css - dibuat otomatis oleh Artisan */\n\nbody {\n    font-family: 'Poppins', sans-serif;\n    margin: 0;\n    padding: 0;\n}");

        $this->info("✅ File CSS berhasil dibuat di: public/assets/css/{$name}.css");
        return Command::SUCCESS;
    }
}
