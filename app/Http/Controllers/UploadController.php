<?php namespace App\Http\Controllers;

/*use App\Http\Controllers\Controller;
use App\Fileentry;
use Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;*/

use Input;
use Validator;
use Redirect;
use Request;
use Session;
use Response;
use Image;

class UploadController extends Controller {
	
	private $_destinationPath;
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth'); //protects whole controller
		$this->_destinationPath = public_path() . '/uploads/'; //where uploads are to be stored
	}
	
	/**
	 * Normal post code without progress
	 *
	 *
	 */
	/*public function postUpload()
	{
		
		$file = array('upload' => Input::file('upload'));
		  // setting up rules
		  $rules = array('upload' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
		  // doing the validation, passing post data, rules and the messages
		  $validator = Validator::make($file, $rules);
		  if ($validator->fails()) {
			// send back to the page with the input data and errors
			return Redirect::to('admin')->withInput()->withErrors($validator);
		  }
		  else {
			// checking file is valid.
			if (Input::file('upload')->isValid()) {
			  $destinationPath = 'uploads'; // upload path
			  $extension = Input::file('upload')->getClientOriginalExtension(); // getting image extension
			  //$fileName = rand(11111,99999).'.'.$extension; // renameing image
			  
			  $fileName = $file->getClientOriginalName().'.'.$extension; // getting file name

			  Input::file('upload')->move($destinationPath, $fileName); // uploading file to given path
			  // sending back with message
			  Session::flash('success', 'Upload successfully'); 
			  return Redirect::to('admin');
			}
			else {
			  // sending back with error message.
			  Session::flash('error', 'uploaded file is not valid');
			  return Redirect::to('admin');
			}
		  }
		
	}*/
	
	/**
	 * Post code with progress
	 *
	 * @return JSON response
	 */
	 
	public function postUpload()
	{
		$file = Input::file('upload');

		if($file) {
			
			
			$filename = $file->getClientOriginalName();

			$upload_success = Input::file('upload')->move($this->_destinationPath, $filename);

			if ($upload_success) {

				// resizing/crop an uploaded file
				//Image::make($destinationPath . $filename)->resize(100, 100)->save($this->_destinationPath . "100x100_" . $filename);

				return Response::json('success', 200);
			} else {
				return Response::json('error', 400);
			}
		}
	}
	
	/**
	 * This will get an array of all content types
	 *
	 * @return JSON response
	 */
	 
	public function getUploads()
	{
		$files = scandir($this->_destinationPath);
		unset($files[0],$files[1]);
		
		//start array from zero
		//$data = array_values($files);
		
		$data = array(
			'photos'=>array(),
			'videos'=>array()
		);

		$img_types = '/\.(gif|jpe?g|png)$/i';
		
		$i = 0;
		foreach ($files AS $f):
			if (preg_match_all($img_types, $f)) $data['photos'][] = $f;
			$i++;
		endforeach;
		
		$vid_types = '/\.(mp4|mpe?g|mov)$/i';

		$i = 0;
		foreach ($files AS $f):
			if (preg_match_all($vid_types, $f)) $data['videos'][] = $f;
			$i++;
		endforeach;
		
		return response()->json($data);
	}
	
	public function getPhotos()
	{
		$files = scandir($this->_destinationPath);
		unset($files[0],$files[1]);
				
		$img_types = '/\.(gif|jpe?g|png)$/i';
		
		$i = 0;
		foreach ($files AS $f):
			if (preg_match_all($img_types, $f)) $data[] = $f;
			$i++;
		endforeach;
		
		return response()->json($data);
	}
	
	public function getVideos()
	{
		$files = scandir($this->_destinationPath);
		unset($files[0],$files[1]);
				
		$img_types = '/\.(mp4|mpe?g|mov)$/i';

		$i = 0;
		foreach ($files AS $f):
			if (preg_match_all($img_types, $f)) $data[] = $f;
			$i++;
		endforeach;
		
		return response()->json($data);
	}
	
	public function postDelete()
	{
		$filename = Input::get('filename');
		//delete file
		unlink($this->_destinationPath.$filename);
		return Redirect::to("/admin");
	}
	
}