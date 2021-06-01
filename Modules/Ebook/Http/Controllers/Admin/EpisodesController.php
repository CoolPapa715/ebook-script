<?php

namespace Modules\Ebook\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Ebook\Entities\Ebook;
use Modules\Ebook\Entities\EbookItemTemp;
use Modules\Ebook\Entities\EbookSkillTemp;
use Modules\Ebook\Entities\EbookCodewordTemp;

use Modules\Ebook\Entities\EbookHero;
use Modules\Ebook\Entities\EbookHeroHasCodewords;
use Modules\Ebook\Entities\EbookHeroHasItems;
use Modules\Ebook\Entities\EbookHeroHasSkills;

use Modules\Ebook\Entities\EbookEpisodes;
use Modules\Ebook\Entities\EbookEpisodesHasCodewords;
use Modules\Ebook\Entities\EbookEpisodesHasItems;
use Modules\Ebook\Entities\EbookEpisodesHasNextEpisodes;
use Modules\Ebook\Entities\EbookEpisodesHasSkills;
use Session;

class EpisodesController extends Controller
{

    protected $itemModel     = EbookItemTemp::class;
    protected $skillModel    = EbookSkillTemp::class;
    protected $codewordModel = EbookCodewordTemp::class;
    protected $heroModel     = EbookHero::class;
    protected $episodeModel  = EbookEpisodes::class;

    protected function getCurrentModel($table_name)
    {
        $current_model = "";

        switch ($table_name) {
            case "items":
                $current_model = new $this->itemModel;
                break;
            case "skills":
                $current_model = new $this->skillModel;
                break;
            case "codewords":
                $current_model = new $this->codewordModel;
                break;
            case "heroes":
                $current_model = new $this->heroModel;
                break;
            case "episodes":
                $current_model = new $this->episodeModel;
                break;
                
            
        }
        return $current_model;
    }

    protected function getEntity($id, $table_name)
    {

        return  $this->getCurrentModel($table_name)
            ->with($this->relations())
            ->withoutGlobalScope('active')
            ->findOrFail($id);
    }
    
    protected function getAllEntity($request,$table_name)
    {
        $ebook_id = Session::get('ebook_id');
        
        if(!is_null($ebook_id)){
            return $this->getCurrentModel($table_name)
            ->where('ebook_id', $ebook_id)
            ->get();
            
        }
        else{
            return $this->getCurrentModel($table_name)
            ->where('ci_auth', $request->session()->get('ci_auth'))
            ->get();
        }
        
    }
   
    protected function showEntity($request,$resourceName)
    {
        if ($request->has('query')) {
            return $this->getCurrentModel($resourceName)
                ->search($request->get('query'))
                ->query()
                ->limit($request->get('limit', 10))
                ->get();
        }

        if ($request->has('table')) {
              return $this->getCurrentModel($resourceName)->table($request);
        }
    }

    protected function deleteEntity($ids,$resourceName)
    {
        $delete_id = explode(',', $ids);
        foreach($delete_id as $id)
        {
            $entity=$this->getEntity($id,$resourceName);
            activity($resourceName)
                ->performedOn($entity)
                ->causedBy(auth()->user())
                ->withProperties(['subject' => $entity,'causer'=>auth()->user()])
                ->log('deleted');
        }
        $this->getCurrentModel($resourceName)
            ->whereIn('id', explode(',', $ids))
            ->delete();
    }

    private function relations()
    {
        
        return collect($this->with ?? [])->mapWithKeys(function ($relation) {
            return [$relation => function ($query) {
                return $query->withoutGlobalScope('active');
            }];
        })->all();
    }
    
    public  function get_items(Request $request)
    {
        $resourceName = 'items';
        $items = $this -> getAllEntity($request,$resourceName);
        return response()->json($items);
    }

    public function items_create(Request $request)
    {
        $item_id         = $request->post('item_id');
        $item_name          = is_null($request->post('item_name'))?"":$request->post('item_name');
        $item_slug          = is_null($request->post('item_slug'))?"":$request->post('item_slug');
        $item_image         = $request->post('item_image');
        $item_description   = is_null($request->post('item_description'))?"":$request->post('item_description');
        $ci_auth = $request->session()->get('ci_auth');

        if(is_null($item_id)||$item_id ==""){
            $item_id =  EbookItemTemp::create([
                'item_name' => $item_name,
                'item_slug' => $item_slug,
                'item_image' => $item_image,
                'item_description' => $item_description,
                'ci_auth' => $ci_auth
            ])->id;
        }
        else{
            EbookItemTemp::where('id',$item_id)->
            update([
                'item_name' => $item_name,
                'item_slug' => $item_slug,
                'item_image' => $item_image,
                'item_description' => $item_description,
            ]);
        }    
       
        echo "success";
        return;
               
    }

    public function items_show(Request $request)
    {
        $resourceName = 'items';
        return $this->showEntity($request,$resourceName);
        
    }

    public function items_destroy($ids)
    {
        
        $resourceName = "items";  
        return $this->deleteEntity($ids,$resourceName);

    }
    
    public function item_edit(Request $request)
    {
        $item_id          = $request->post('item_id');
        $resourceName="items";
        $item_Info = $this->getCurrentModel($resourceName)->edit($item_id);
        echo json_encode($item_Info);
        return;
    }

    public  function get_skills(Request $request)
    {
        $resourceName = 'skills';
        $items = $this -> getAllEntity($request,$resourceName);
        return response()->json($items);
    }

    public function skills_create(Request $request)
    {
        $skill_id         = $request->post('skill_id');
        $skill_name          = is_null($request->post('skill_name'))?"":$request->post('skill_name');
        $skill_slug          = is_null($request->post('skill_slug'))?"":$request->post('skill_slug');
        $skill_description   = is_null($request->post('skill_description'))?"":$request->post('skill_description');
        $ci_auth = $request->session()->get('ci_auth');
        
        if(is_null($skill_id)||$skill_id ==""){
            $skill_id =  EbookSkillTemp::create([
                'skill_name' => $skill_name,
                'skill_slug' => $skill_slug,
                'skill_description' => $skill_description,
                'ci_auth' => $ci_auth
            ])->id;
        }
        else{
            EbookSkillTemp::where('id',$skill_id)->
            update([
                'skill_name' => $skill_name,
                'skill_slug' => $skill_slug,
                'skill_description' => $skill_description,
            ]);
        }    
       
        echo "success";
        return;
        
    }

    public function skills_show(Request $request)
    {
        $resourceName = 'skills';
        return $this->showEntity($request,$resourceName);
        
    }

    public function skills_destroy($ids)
    {
   
        $resourceName = "skills";  
        return $this->deleteEntity($ids,$resourceName);

    }

    // public function skill_edit(Request $request)
    // {
    //     $skill_id          = $request->post('skill_id');
    //     $resourceName="skills";
    //     $skill_Info = $this->getCurrentModel($resourceName)->edit($skill_id);
    //     echo json_encode($skill_Info);
    //     return;
    // }

    public  function get_codewords(Request $request)
    {
        $resourceName = 'codewords';
        $items = $this -> getAllEntity($request,$resourceName);
        return response()->json($items);
    }

    public function codewords_create(Request $request)
    {
        $codeword_id         = $request->post('codeword_id');
        $codeword_name          = is_null($request->post('codeword_name'))?"":$request->post('codeword_name');
        $codeword_slug          = is_null($request->post('codeword_slug'))?"":$request->post('codeword_slug');
        $ci_auth = $request->session()->get('ci_auth');

        if(is_null($codeword_id)||$codeword_id ==""){
            $codeword_id =  EbookCodewordTemp::create([
                'codeword_name' => $codeword_name,
                'codeword_slug' => $codeword_slug,
                'ci_auth' => $ci_auth
            ])->id;
        }
        else{
            EbookCodewordTemp::where('id',$codeword_id)->
            update([
                'codeword_name' => $codeword_name,
                'codeword_slug' => $codeword_slug,
            ]);
        }    
       
        echo "success";
        return;
    }

    public function codewords_show(Request $request)
    {
        $resourceName = 'codewords';
        return $this->showEntity($request,$resourceName);
        
    }

    public function codewords_destroy($ids)
    {
        
        $resourceName = "codewords";  
        return $this->deleteEntity($ids,$resourceName);

    }

    // public function codeword_edit(Request $request)
    // {
    //     $codeword_id          = $request->post('codeword_id');
    //     $resourceName="skills";
    //     $codeword_Info = $this->getCurrentModel($resourceName)->edit($codeword_id);
    //     echo json_encode($codeword_Info);
    //     return;
    // }

    public function heroes_create(Request $request)
    {
        $hero_id            = $request->post('hero_id');
        $hero_name          = is_null($request->post('hero_name'))?"":$request->post('hero_name');
        $hero_slug          = is_null($request->post('hero_slug'))?"":$request->post('hero_slug');
        $hero_image         = $request->post('hero_image');
        $hero_description   = is_null($request->post('hero_description'))?"":$request->post('hero_description');
        
        $ci_auth = $request->session()->get('ci_auth');

        if(is_null($hero_id)||$hero_id ==""){
            $hero_id = EbookHero::create([
                'hero_name' => $hero_name,
                'hero_slug' => $hero_slug,
                'hero_image' => $hero_image,
                'hero_description' => $hero_description,
                'ci_auth' => $ci_auth
            ])->id;
        }
        else{
            EbookHero::where('id',$hero_id)->
            update([
                'hero_name' => $hero_name,
                'hero_slug' => $hero_slug,
                'hero_image' => $hero_image,
                'hero_description' => $hero_description,
   
            ]);
            EbookHeroHasItems::where('hero_id',$hero_id)->delete();
            EbookHeroHasSkills::where('hero_id',$hero_id)->delete();
            EbookHeroHasCodewords::where('hero_id',$hero_id)->delete();
        }    

        $item_array      = json_decode($request->post('item_json'), true);
        $skill_array     = json_decode($request->post('skill_json'), true); 
        $codeword_array  = json_decode($request->post('codeword_json'), true);

        foreach($item_array as $key => $value)
        {
        
            EbookHeroHasItems::create([
                'hero_id'  => $hero_id,
                'item_id'  => $value['item_id'],
                'value'    => $value['value'],
                'ci_auth'  => $ci_auth
            ]);
        }

        foreach($skill_array as $key => $value)
        {
            EbookHeroHasSkills::create([
                'hero_id'  => $hero_id,
                'skill_id' => $value['skill_id'],
                'value'    => $value['value'],
                'ci_auth'  => $ci_auth
            ]);
        }

        foreach($codeword_array as $key => $value)
        {
            EbookHeroHasCodewords::create([
                'hero_id'  => $hero_id,
                'codeword_id' => $value['codeword_id'],
                'value'    => $value['value'],
                'ci_auth'  => $ci_auth
            ]);
        }
                            
        echo  "success";
        return;
        

    }

    public function heroes_show(Request $request)
    {  
        $resourceName = 'heroes';
        return $this->showEntity($request,$resourceName);
        
    }

    public function heroes_destroy($ids)
    {

        $resourceName="heroes";  
        return $this->deleteEntity($ids,$resourceName);
    }

    public function hero_edit(Request $request)
    {
        $hero_id          = $request->post('hero_id');
        $resourceName="heroes";
        $hero_baseInfo = $this->getCurrentModel($resourceName)->edit($hero_id);
      
        $hero_ItemInfo = array();
        $result = EbookHeroHasItems::select('item_id','value','item_name')
                        ->join('items', 'heroes_has_items.item_id', '=', 'items.id')
                        ->where('hero_id', $hero_id)->get();
                      
        foreach($result as $key => $value){
            array_push($hero_ItemInfo,$value->attributesToArray()); 
        }
        $hero_SkillInfo = array();
        $result = EbookHeroHasSkills::select('skill_id','value','skill_name')
                        ->join('skills', 'heroes_has_skills.skill_id', '=', 'skills.id')
                        ->where('hero_id', $hero_id)->get();
                      
        foreach($result as $key => $value){
            array_push($hero_SkillInfo,$value->attributesToArray()); 
        }
        $hero_CodewordInfo = array();
        $result = EbookHeroHasCodewords::select('codeword_id','value','codeword_name')
                        ->join('codewords', 'heroes_has_codewords.codeword_id', '=', 'codewords.id')
                        ->where('hero_id', $hero_id)->get();
                      
        foreach($result as $key => $value){
            array_push($hero_CodewordInfo,$value->attributesToArray()); 
        }
        
        $hero_info = [ 'hero_baseInfo' => $hero_baseInfo, 'hero_ItemInfo' => $hero_ItemInfo, 'hero_SkillInfo' => $hero_SkillInfo, 'hero_CodewordInfo' => $hero_CodewordInfo ];
        echo json_encode($hero_info);
        return;
    }

    public function episodes_create(Request $request)
    {
        $episode_id            = $request->post('episode_id');
        $episode_number        = $request->post('episode_number');
        $episode_description   = is_null($request->post('episode_description'))?"":$request->post('episode_description');
        $episode_death         = $request->post('episode_death');
        $episode_with_fight    = $request->post('episode_with_fight');
        $episode_dice          = $request->post('episode_dice');
        $episode_final         = $request->post('episode_final');
        $episode_is_first      = $request->post('episode_is_first');
 
        $ci_auth = $request->session()->get('ci_auth');

        if(is_null($episode_id)||$episode_id ==""){
            $episode_id = EbookEpisodes::create([
                'number' => $episode_number,
                'description' => $episode_description,
                'has_death' => $episode_death,
                'has_fight' => $episode_with_fight,
                'has_dice' => $episode_dice,
                'is_first' => $episode_is_first,
                'is_last' => $episode_final,
                'ci_auth' => $ci_auth
            ])->id;
        }
        else{

            EbookEpisodes::where('id',$episode_id)->
            update([
                'number' => $episode_number,
                'description' => $episode_description,
                'has_death' => $episode_death,
                'has_fight' => $episode_with_fight,
                'has_dice' => $episode_dice,
                'is_first' => $episode_is_first,
                'is_last' => $episode_final
            ]);

            EbookEpisodesHasCodewords::where('episode_id',$episode_id)->delete();
            EbookEpisodesHasItems::where('episode_id',$episode_id)->delete();
            EbookEpisodesHasSkills::where('episode_id',$episode_id)->delete();
            EbookEpisodesHasNextEpisodes::where('episode_id',$episode_id)->delete();
        }    

        $item_array      = json_decode($request->post('item_json'), true);
        $skill_array     = json_decode($request->post('skill_json'), true); 
        $codeword_array  = json_decode($request->post('codeword_json'), true);
        $link_array      = json_decode($request->post('nextlink_json'), true);

       
        foreach($item_array as $key => $value)
        {
         
            EbookEpisodesHasItems::create([
                'episode_id'  => $episode_id,
                'item_id'  => $value['item_id'],
                'value'    => $value['value'],
                'ci_auth'  => $ci_auth
            ]);
        }

        foreach($skill_array as $key => $value)
        {
            EbookEpisodesHasSkills::create([
                'episode_id'  => $episode_id,
                'skill_id' => $value['skill_id'],
                'value'    => $value['value'],
                'ci_auth'  => $ci_auth
            ]);
        }

        foreach($codeword_array as $key => $value)
        {
            EbookEpisodesHasCodewords::create([
                'episode_id'  => $episode_id,
                'codeword_id' => $value['codeword_id'],
                'value'    => $value['value'],
                'ci_auth'  => $ci_auth
            ]);
        }
        if($episode_dice){
            foreach($link_array as $key => $value)
            {
                EbookEpisodesHasNextEpisodes::create([
                    'episode_id'      => $episode_id,
                    'next_episode_id' => $value['episode_id'],
                    'text'     => $value['text'],
                    'is_even'     => $value['is_even'],
                    'ci_auth'  => $ci_auth
                ]);
            }
        }else{
            foreach($link_array as $key => $value)
            {
                EbookEpisodesHasNextEpisodes::create([
                    'episode_id'      => $episode_id,
                    'next_episode_id' => $value['episode_id'],
                    'text'     => $value['text'],
                    'ci_auth'  => $ci_auth
                ]);
            }
        }
                            
        echo  "success";
        return;
        

    }

    public function episodes_show(Request $request)
    {
        $resourceName = 'episodes';
        return $this->showEntity($request,$resourceName);
        
    }

    public function episodes_destroy($ids)
    {

        $resourceName="episodes";  
        return $this->deleteEntity($ids,$resourceName);
    }

    public function episode_edit(Request $request)
    {
        $episode_id            = $request->post('episode_id');
        $resourceName="episodes";
        $episode_baseInfo = $this->getCurrentModel($resourceName)->edit($episode_id);
      
        $episode_ItemInfo = array();
        $result = EbookEpisodesHasItems::select('item_id','value','item_name')
                        ->join('items', 'episodes_has_items.item_id', '=', 'items.id')
                        ->where('episode_id', $episode_id)->get();
                      
        foreach($result as $key => $value){
            array_push($episode_ItemInfo,$value->attributesToArray()); 
        }

        $episode_SkillInfo = array();
        $result = EbookEpisodesHasSkills::select('skill_id','value','skill_name')
                        ->join('skills', 'episodes_has_skills.skill_id', '=', 'skills.id')
                        ->where('episode_id', $episode_id)->get();
                      
        foreach($result as $key => $value){
            array_push($episode_SkillInfo,$value->attributesToArray()); 
        }
        
        $episode_CodewordInfo = array();
        $result = EbookEpisodesHasCodewords::select('codeword_id','value','codeword_name')
                        ->join('codewords', 'episodes_has_codewords.codeword_id', '=', 'codewords.id')
                        ->where('episode_id', $episode_id)->get();
                      
        foreach($result as $key => $value){
            array_push($episode_CodewordInfo,$value->attributesToArray()); 
        }

        $episode_nextLinkInfo = array();
        $result = EbookEpisodesHasNextEpisodes::select('next_episode_id','text','number')
                        ->join('episodes', 'episodes_has_next_episodes.next_episode_id', '=', 'episodes.id')
                        ->where('episode_id', $episode_id)->get();
                      
        foreach($result as $key => $value){
            array_push($episode_nextLinkInfo,$value->attributesToArray()); 
        }
        
        $episode_info = [ 'episode_baseInfo' => $episode_baseInfo, 'episode_ItemInfo' => $episode_ItemInfo, 'episode_SkillInfo' => $episode_SkillInfo, 'episode_CodewordInfo' => $episode_CodewordInfo,'episode_nextLinkInfo' => $episode_nextLinkInfo ];
       
        echo json_encode($episode_info);
        return;
    }

    public  function get_episodesNum(Request $request)
    {
        $resourceName = 'episodes';
        $episodes = $this -> getAllEntity($request,$resourceName);
        return response()->json($episodes);
    }
}
