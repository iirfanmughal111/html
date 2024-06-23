<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\GameGuide;

class CommonController extends Controller
{
	
	
	public function __construct()
    {
	    
    }
/*===============================================
      OPEN CONFIRM BOX TO COMPLETE THE REPROT 
==============================================*/	
    public function confirmModal(Request $request)
	{
	  
		$roleIdArr = Config::get('constant.role_id');
		$confirm_message =$request->confirm_message;
		$confirm_message_1 =$request->confirm_message_1;
		$leftButtonName =$request->leftButtonName;
		$leftButtonId =$request->leftButtonId;
		$leftButtonCls =$request->leftButtonCls;
		$id = $request->id;
		if ($request->ajax()) {
		return view('modal.confirmModal', compact('id','confirm_message','confirm_message_1','leftButtonName','leftButtonId','leftButtonCls'));
		} 

	}
	
	/** Create Slug
     * @param $title
     * @param $model
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public static function createSlug($title,$model,$id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        if($model == 'gameGuide')
            $allSlugs = CommonController::getRelatedGameGuideSlugs($slug, $id);
        else
            $allSlugs = CommonController::getRelatedGameSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    /*Check if no other have same slug*/
    public static function getRelatedGameSlugs($slug, $id = 0)
    {
        return Game::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
	

    /*Check if Game guide slug*/
    public static function getRelatedGameGuideSlugs($slug, $id = 0)
    {
        return GameGuide::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
    /*End*/

	
}