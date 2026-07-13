<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_locks', function (Blueprint $table) {
            $table->id();
            $table->string('tool_slug')->unique();
            $table->string('tool_name');
            $table->string('tool_route')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });

        // Insert all 28 tools as unlocked by default
        $tools = [
            ['merge', 'Gabungkan PDF', 'merge.index'],
            ['compress', 'Kompres PDF', 'compress.index'],
            ['jpg-to-pdf', 'JPG ke PDF', 'jpg-to-pdf.index'],
            ['png-to-pdf', 'PNG ke PDF', 'png-to-pdf.index'],
            ['word-to-pdf', 'Word ke PDF', 'word-to-pdf.index'],
            ['excel-to-pdf', 'Excel ke PDF', 'excel-to-pdf.index'],
            ['pdf-to-jpg', 'PDF ke JPG', 'pdf-to-jpg.index'],
            ['pdf-to-txt', 'PDF ke TXT', 'pdf-to-txt.index'],
            ['pdf-to-markdown', 'PDF ke Markdown', 'pdf-to-markdown.index'],
            ['pdf-to-excel', 'PDF ke Excel', 'pdf-to-excel.index'],
            ['pdf-to-pptx', 'PDF ke PPTX', 'pdf-to-pptx.index'],
            ['pdf-to-pdfa', 'PDF ke PDF/A', 'pdf-to-pdfa.index'],
            ['pdf-to-word', 'PDF ke Word', 'pdf-to-word.index'],
            ['split', 'Pisahkan PDF', 'split.index'],
            ['crop', 'Potong PDF', 'crop.index'],
            ['rotate', 'Putar PDF', 'rotate.index'],
            ['remove-pages', 'Hapus Halaman', 'remove-pages.index'],
            ['extract-pages', 'Ekstrak Halaman', 'extract-pages.index'],
            ['organize-pdf', 'Atur Halaman', 'organize-pdf.index'],
            ['optimize-pdf', 'Optimasi PDF', 'optimize-pdf.index'],
            ['scan-to-pdf', 'Scan ke PDF', 'scan-to-pdf.index'],
            ['html-to-pdf', 'HTML ke PDF', 'html-to-pdf.index'],
            ['pptx-to-pdf', 'PPTX ke PDF', 'pptx-to-pdf.index'],
            ['protect-pdf', 'Proteksi PDF', 'protect-pdf.index'],
            ['unlock-pdf', 'Buka Kunci PDF', 'unlock-pdf.index'],
            ['watermark-pdf', 'Watermark PDF', 'watermark-pdf.index'],
            ['page-numbers', 'Nomor Halaman', 'page-numbers.index'],
            ['repair-pdf', 'Perbaiki PDF', 'repair-pdf.index'],
        ];

        foreach ($tools as $tool) {
            DB::table('tool_locks')->insert([
                'tool_slug' => $tool[0],
                'tool_name' => $tool[1],
                'tool_route' => $tool[2],
                'is_locked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_locks');
    }
};
