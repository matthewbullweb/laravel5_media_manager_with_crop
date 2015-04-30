<?php namespace App\Http\Controllers;

use Input;
use Validator;

class CropController extends Controller {
	
	private $_destinationPath;
	
	public function __construct()
	{
		$this->middleware('auth');
		$this->_destinationPath = public_path() . '/uploads/'; //where uploads are to be stored
	}
	
	public function getCrop()
	{
		return view('crop')
		->with('destinationPath', $this->_destinationPath)
		->with('filename', Input::get('filename'));
	}
	
	public function postCrop()
	{
		$post = Input::all();
		
		if( isset($post['x1']) ) {
			
			$v = Validator::make($post, [
				'x1' => 'required|min:1',
				'y1' => 'required|min:1',
				'x2' => 'required|min:1',
				'y2' => 'required|min:1',
				'w' => 'required|min:1',
				'h' => 'required|min:1',
			]);
			
			if ($v->fails())
			{
				//$messages = $v->messages();
				//dd($messages);
				return redirect()->back()->withErrors($v->errors());
			}
			$targ_w = $_POST['w'];//720;
			$targ_h = $_POST['h'];//455;
			$jpeg_quality = 100;
		
			$info = pathinfo( $post['filename'] );
			$name = $info['filename'];
			$ext  = strtolower($info['extension']);
			$src = $this->_destinationPath.$post['filename'];
			
			$dst_r = ImageCreateTrueColor( $_POST['w'], $_POST['h'] );

			switch ($ext) {
				case 'jpg':
				case 'jpeg':
					$img_r = imagecreatefromjpeg($src);
					break;
				case 'gif':
					imagecolortransparent($dst_r, imagecolorallocate($dst_r, 0, 0, 0));
					$img_r = imagecreatefromgif($src);
					break;
				case 'png':
					imagecolortransparent($dst_r, imagecolorallocate($dst_r, 0, 0, 0));
					imagealphablending($dst_r, false);
					imagesavealpha($dst_r, true);
					$img_r = imagecreatefrompng($src);
					break;
				default:
					imagedestroy($dst_r);
			}

			$new_filename = $this->_destinationPath.$name.'_crop.'.$ext;

			//crop
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x1'],$_POST['y1'],
				$targ_w,$targ_h,$_POST['w'],$_POST['h']);

			switch ($ext) {
				case 'jpg':
				case 'jpeg':
					$success = imagejpeg($dst_r, $new_filename, $jpeg_quality);
					break;
				case 'gif':
					$success = imagegif($dst_r, $new_filename);
					break;
				case 'png':
					$success = imagepng($dst_r, $new_filename);
					break;
			}

			// Free up memory (imagedestroy does not delete files):
			imagedestroy($dst_r);
			
			return redirect('admin')->withSuccess("Image Cropped");
		}
		
	}
		
}