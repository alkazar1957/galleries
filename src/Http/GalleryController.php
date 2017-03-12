<?php

namespace Alkazar\Gallery\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alkazar\Gallery\Models\Gallery;
use Alkazar\Gallery\Models\GalleryImage;
use Alkazar\UserRoles\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Auth;

class GalleryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'showGallery');
//        $this->middleware('web');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // View All Galleries
        $galleries = Gallery::all();

        // View Only Galleries Created By A User
//        $userGalleries = Gallery::where('created_by', Auth::user()->id)->get();
        return view('gallery::index')->with(['galleries' => $galleries]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        // Get the file
        $file = $request->file('file');

        // Set the file name
        $filename = uniqid() . $file->getClientOriginalName();

        // Move the file to storage
        $gallery_dir = 'gallery/' . $request->input('gallery_id');
        $file->move($gallery_dir.'/', $filename);

        // make a thumbnail directory
        if (!file_exists($gallery_dir . '/thumbs')){
            mkdir($gallery_dir . '/thumbs', 0755, true);
        }

        $thumb = Image::make($gallery_dir . '/' . $filename)->resize(240, 160)->save($gallery_dir . '/thumbs/' . $filename, 60);
        // $thumb = Image::make($gallery_dir . '/' . $filename)->resize(null, 120, function($constraing) { $constraing->aspectRatio(); })->save($gallery_dir . '/thumbs/' . $filename, 60);

        // Get the Gallery
        $gallery = Gallery::find($request->input('gallery_id'));

        // Save data to db
        $image = $gallery->images()->create([

            'gallery_id'    =>  $request->input('gallery_id'),
            'file_name'     =>  $filename,
            'file_size'     =>  $file->getClientSize(),
            'file_mime'     =>  $file->getClientMimeType(),
            'file_path'     =>  'gallery/' . $request->input('gallery_id') . '/' . $filename,
            'created_by'    =>  Auth::user()->id,

        ]);


        return $image;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveGallery(Request $request)
    {
        $gallery = new Gallery;
        $user = Auth::user();

        // Validate gallery data
        $validator = Validator::make($request->all(), [
            'gallery_name' => 'required|min:3'
        ]);

        if ($validator->fails()){
            return redirect('/galleries/')->withErrors($validator)->withInput();
        }

        $gallery->name = $request->input('gallery_name');
        $gallery->created_by = Auth::user()->id;
        $gallery->published = 1;

        $gallery->save();
        $newGallery = Gallery::where('name', $request->gallery_name)->first();

        $gallery_dir = ('gallery/' . $newGallery->id);

        if (!file_exists($gallery_dir)) {
            mkdir($gallery_dir, 0755, true);
        }   

        if (!file_exists($gallery_dir . '/thumbs')){
            mkdir($gallery_dir . '/thumbs', 0755, true);
        }


        Session::flash('flash_message', 'Gallery has been created');
        return redirect()->back()->with(['message' => 'Gallery Saved']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        
	    $user = \Alkazar\Gallery\Models\User::findOrFail(Auth::user()->id);

        return view('gallery::gallery-view')->with(['gallery' => $gallery, 'user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function editImage($gallery, $id, $direction)
    {
        $currentImage = GalleryImage::findOrFail($id);
        
        if(!$currentImage->created_by === Auth::user()->id){
            abort('403', 'You are not permitted to delete this Image.');
        }

        if(file_exists(public_path($currentImage->file_path))){
            $filename = $currentImage->file_name;
            $image = Image::make($currentImage->file_path)->rotate($direction)->save('gallery/' . $gallery . '/' . $filename, 60);
            $thumb = Image::make($currentImage->file_path)->resize(240, 160)->save('gallery/' . $gallery . '/thumbs/' . $filename, 60);
            // $image->rotate(-45);
        }
        return redirect()->route('showGallery', $gallery);
        return view('gallery::gallery-view')->with('gallery', $gallery);
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyImage($gallery, $id)
    {

        $currentGallery = Gallery::findOrFail($gallery);

        // $currentImage = $currentGallery->images()->where('id', $id)->get();
        $currentImage = GalleryImage::findOrFail($id);

        // dd($currentImage->created_by);

        if(!$currentImage->created_by === Auth::user()->id){
            abort('403', 'You are not permitted to delete this Image.');
        }

        if(file_exists(public_path($currentImage->file_path))){
            unlink(public_path($currentImage->file_path));
            unlink(public_path('gallery/' . $currentGallery->id . '/thumbs/' . $currentImage->file_name));
        }

        Session::flash('flash_message', 'Image/Thumb Deleted');
        $currentImage->delete();
        return redirect()->back();
        dd($currentImage);


    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentGallery = Gallery::findOrFail($id);

        if(!$currentGallery->created_by === Auth::user()->id){
            abort('403', 'You are not permitted to delete this Gallery.');
        }

        // Get the images in the gallery
        $images = $currentGallery->images()->get();

        // Delete the images
        foreach ($images as $image) {
            if(file_exists(public_path($image->file_path))){
                unlink(public_path($image->file_path));
                unlink(public_path('gallery/' . $currentGallery->id . '/thumbs/' . $image->file_name));
            }
        }
        // Remove the gallery directory
        if(file_exists(public_path('gallery/' . $currentGallery->id . '/thumbs'))){
            rmdir('gallery/' . $currentGallery->id . '/thumbs');
        }
        if(file_exists(public_path('gallery/' . $currentGallery->id))){
            rmdir('gallery/' . $currentGallery->id);
        }


        // $images->delete();

        $currentGallery->delete();

        return redirect()->back();
    }
}
