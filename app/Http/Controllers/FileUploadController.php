<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Jobs\ProcessCSVFile;
use Illuminate\Http\Request;
use DateTime;
use Log;

class FileUploadController extends Controller
{
    public function index()
    {
        $uploads = FileUpload::orderBy('uploaded_at', 'desc')->get();

        if (!empty($uploads)) {

            foreach ($uploads as $uploadFile) {

                $uploadDatetime = $uploadFile['uploaded_at'];

                $uploaded_at = new DateTime($uploadDatetime);
                $current_date = new DateTime();

                $interval = $uploaded_at->diff($current_date);

                $parts = array();

                if ($interval->y)
                    $parts[] = $interval->format('%y years');
                if ($interval->m)
                    $parts[] = $interval->format('%m months');
                if ($interval->d)
                    $parts[] = $interval->format('%d days');
                if ($interval->h)
                    $parts[] = $interval->format('%h hours');
                if ($interval->i)
                    $parts[] = $interval->format('%i minutes');
                if ($interval->s)
                    $parts[] = $interval->format('%s seconds');

                $formatted_interval = implode(', ', $parts);

                $uploadFile['uploaded_at'] = $uploadFile['uploaded_at'] . '(' . $formatted_interval . ')';
            }

            return view('index', ['uploads' => $uploads]);
        }
    }

    public function store(Request $request)
    {
        $file = $request->file('csvfile');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads', $filename);

        $upload = new FileUpload;
        $upload->filename = $filename;
        $upload->uploaded_at = now();
        $upload->save();

        // TODO: Dispatch job to process the file in the background

        ProcessCSVFile::dispatch($filename);

        return redirect('/')->with('status', 'File uploaded! Processing will be done soon.');
    }

}