<?php

namespace App\Http\Controllers;

use App\Download;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class DownloadController extends Controller
{
    
    protected $imageHelper;

    public function __construct(ImageHelper $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $path = $request->file->store('uploads', 'public');
        
        $this->imageHelper->handleUploadedImage($path);        

        $download = Download::create([
            'title' => $request->title,
            'path' => $path,
            'mime' => $request->file->getMimeType(),
            'size' => $request->file->getSize(),
        ]);

        return [
            'id' => $download->id,
            'path' => Storage::url($download->path)
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Download $download)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function destroy(Download $download)
    {
        if(! \Storage::disk('public')->delete($download->path)) {
            return;
        }
        
        if($download->delete()) {
            return ['result' => true];
        }
    }
}
