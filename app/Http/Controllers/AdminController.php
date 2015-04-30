<?php namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Media;

class AdminController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		//get all users and media to test models
		$users = User::all();
		$media = Media::all();
		
		//pass to view
		return view('home')
			->with('users',$users)
			->with('media',$media);
	}

}
