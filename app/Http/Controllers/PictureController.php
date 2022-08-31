<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Croppa;

use FileUpload;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Facades\File;

class PictureController extends Controller
{
    public $folder = '/uploads/';

    public function index()
    {
        // get all pictures
        $pictures = Picture::all();
        
        $pictures->map(function ($picture) {
            // $picture['size'] = Storage::disk('local')->get(public_path($picture['url']));;
            $picture['thumbnailUrl'] = Croppa::url($picture['url'], 80, 80, ['resize']);
            $picture['deleteType'] = 'DELETE';
            $picture['deleteUrl'] = route('pictures.destroy', $picture->id);
            return $picture;
        });
        
        
        // show all pictures
        return response()->json(['files' => $pictures]);
    }

    public function store(Request $request)
    {

        $path = public_path($this->folder);
        if (!File::exists($path)) {
            File::makeDirectory($path);
        };

        $validator = new FileUpload\Validator\Simple('2M', ['image/png', 'image/jpg', 'image/jpeg']);

        $pathresolver = new FileUpload\PathResolver\Simple($path);

        // The machine's filesystem
        $filesystem = new FileUpload\FileSystem\Simple();

        // FileUploader itself
        $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);
        $slugGenerator = new FileUpload\FileNameGenerator\Slug();

        // Adding it all together. Note that you can use multiple validators or none at all
        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->addValidator($validator);
        $fileupload->setFileNameGenerator($slugGenerator);

        // Doing the deed
        list($files, $headers) = $fileupload->processAll();

        // Outputting it, for example like this
        foreach ($headers as $header => $value) {
            header($header . ': ' . $value);
        }

        foreach ($files as $file) {
            //Remember to check if the upload was completed
            if ($file->completed) {

                // set some data
                $filename = $file->getFilename();
                $url = $this->folder . $filename;

                // save data
                $picture = Picture::create([
                    'name' => $filename,
                    'url' => $this->folder . $filename,
                ]);

                // prepare response
                $data[] = [
                    'size' => $file->size,
                    'name' => $filename,
                    'url' => $url,
                    'thumbnailUrl' => Croppa::url($url, 80, 80, ['resize']),
                    'deleteType' => 'DELETE',
                    'deleteUrl' => route('pictures.destroy', $picture->id),
                ];

                // output uploaded file response
                return response()->json(['files' => $data]);
            }
        }
        // errors, no uploaded file
        return response()->json(['files' => $files]);
    }

    public function destroy(Picture $picture)
    {
        Croppa::delete($picture->url);
        $picture->delete();
        return response()->json([$picture->url]);
    }
}
