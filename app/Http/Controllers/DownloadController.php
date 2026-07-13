<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class DownloadController extends Controller
{
    public function download(string $id, ?string $filename = null)
    {
        $directory = storage_path('app/private/vizziodocs/' . $id);
        
        if (!File::exists($directory)) {
            abort(404, 'File not found or expired.');
        }

        if ($filename && File::exists($directory . '/' . $filename)) {
            $filePath = $directory . '/' . $filename;
            $fileName = $filename;
        } else {
            $files = File::files($directory);
            
            if (count($files) === 0) {
                abort(404, 'No file found in this session.');
            }
            
            $filePath = $files[0]->getRealPath();
            $fileName = $files[0]->getFilename();
        }

        // Format filename: originalname_VizzioDocs.ext
        $pathInfo = pathinfo($fileName);
        $downloadName = $pathInfo['filename'] . '_VizzioDocs';
        if (isset($pathInfo['extension'])) {
            $downloadName .= '.' . $pathInfo['extension'];
        }

        // Download and delete the file immediately after sending
        return response()->download($filePath, $downloadName)->deleteFileAfterSend(true);
    }
}
