<?php

namespace Modules\Ebook\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Modules\Category\Entities\Category;
use Modules\Author\Entities\Author;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Emails\Welcome;
use Modules\User\Contracts\Authentication;
use Modules\Ebook\Entities\Ebook;
use Modules\Ebook\Events\EbookViewed;
use Modules\Ebook\Filters\EbookFilter;
use Modules\Ebook\Events\ShowingEbookList;
use Modules\Ebook\Http\Middleware\SetEbookSortOption;
use Modules\Ebook\Http\Requests\StoreEbookRequest;
use Modules\Ebook\Http\Requests\UpdateEbookRequest;

use Modules\Ebook\Entities\EbookEpisodes;
use Modules\Ebook\Entities\EbookEpisodesHasItems;
use Modules\Ebook\Entities\EbookEpisodesHasSkills;
use Modules\Ebook\Entities\EbookEpisodesHasCodewords;
use Modules\Ebook\Entities\EbookEpisodesHasNextEpisodes;

use Modules\Ebook\Entities\EbookHero;
use Modules\Ebook\Entities\EbookHeroHasCodewords;
use Modules\Ebook\Entities\EbookHeroHasItems;
use Modules\Ebook\Entities\EbookHeroHasSkills;
use Modules\Ebook\Entities\EbookGame;
use Modules\Ebook\Entities\EbookGameEpisodeSteps;
use Modules\Ebook\Entities\EbookGameHasCodewords;
use Modules\Ebook\Entities\EbookGameHasItems;
use Modules\Ebook\Entities\EbookGameHasSkills;

use Modules\Files\Entities\Files;

use DB;
use Illuminate\Support\Facades\Log;
use Response;

class EbookController extends Controller
{
    /**
     * The Authentication instance.
     *
     * @var \Modules\User\Contracts\Authentication
     */
    protected $auth;

    /**
     * @param \Modules\User\Contracts\Authentication $auth
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
        $this->middleware(SetEbookSortOption::class)->only('index');

    }
    /**
     * Display a listing of the resource.
     *
     * @param \Modules\Ebook\Entities\Ebook $model
     * @param \Modules\Ebook\Filters\EbookFilter $ebookFilter
     * @return \Illuminate\Http\Response
     */
    public function index(Ebook $model, EbookFilter $ebookFilter)
    {
        $ebookIds = [];

        if (request()->has('query')) {
            $model = $model->search(request('query'));
            $ebookIds = $model->keys();
        }

        $query = $model->filter($ebookFilter);

        if (request()->has('category')) {
            $ebookIds = (clone $query)->select('ebooks.id')->resetOrders()->pluck('id');
        }

        $ebooks = $query->paginate(9)
            ->appends(request()->query());

        if (request()->wantsJson()) {
            return response()->json($ebooks);
        }

        event(new ShowingEbookList($ebooks));
        
        return view('public.ebooks.index', compact('ebooks', 'ebookIds'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!setting('enable_ebook_upload'))
        {
              return redirect()->route('home');
        }
        if(!auth()->user())
        {
            return redirect()->route('login');
        }
        return view('public.account.ebooks.create',['categories' => Category::treeList(),'authors' => Author::list()]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Ebook\Http\Requests\StoreEbookRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEbookRequest $request)
    {
        $user_id=auth()->user()->id;
        if (setting('auto_approve_ebook') && setting('auto_approve_user')) {
           $is_active=1;
        }else{
            $is_active=0;
        }
       
        $data=[
            'title'=>$request->title,
            'description'=>strip_tags($request->description),
            'short_description'=>strip_tags($request->short_description),
            'publication_year'=>$request->publication_year,
            'publisher'=>$request->publisher,
            'price'=>$request->price,
            'buy_url'=>$request->buy_url,
            'isbn'=>$request->isbn,
            'file_type'=>$request->file_type,
            'file_url'=>$request->file_url,
            'embed_code'=>$request->embed_code,
            'categories'=>$request->categories,
            'is_private'=>$request->is_private,
            'is_active'=>$is_active,
            'password'=>$request->password_protected,
            'user_id'=>$user_id,
            'is_featured'=>0,
        ];
        
        $ebook=Ebook::create($data);
        if($request->hasFile('book_cover'))
        {
            $file_image = $request->file('book_cover');
            $path_image = Storage::putFile('media', $file_image);
            $book_cover=Files::create([
                'user_id' => $user_id,
                'disk' => config('filesystems.default'),
                'filename' => $file_image->getClientOriginalName(),
                'path' => $path_image,
                'extension' => $file_image->guessClientExtension() ?? '',
                'mime' => $file_image->getClientMimeType(),
                'size' => $file_image->getSize(),
            ]);
            DB::table('entity_files')->insert([
                'files_id' => $book_cover->id,
                'entity_type'=>'Modules\Ebook\Entities\Ebook',
                'entity_id'=>$ebook->id,
                'zone'=>'book_cover',
                'created_at'=>$book_cover->created_at,
                'updated_at'=>$book_cover->updated_at,
            ]);
        }
        if($request->hasFile('book_file'))
        {
            $file_pdf = $request->file('book_file');
            $path_pdf = Storage::putFile('media', $file_pdf);
            $book_file=Files::create([
                'user_id' => $user_id,
                'disk' => config('filesystems.default'),
                'filename' => $file_pdf->getClientOriginalName(),
                'path' => $path_pdf,
                'extension' => $file_pdf->guessClientExtension() ?? '',
                'mime' => $file_pdf->getClientMimeType(),
                'size' => $file_pdf->getSize(),
            ]);
            DB::table('entity_files')->insert([
                'files_id' => $book_file->id,
                'entity_type'=>'Modules\Ebook\Entities\Ebook',
                'entity_id'=>$ebook->id,
                'zone'=>'book_file',
                'created_at'=>$book_file->created_at,
                'updated_at'=>$book_file->updated_at,
            ]);
        }
        if($request->hasFile('audio_book_files'))
        {
            $audioFiles = $request->file('audio_book_files');
            foreach($audioFiles as $afile){
                
                $path_audio = Storage::putFile('media', $afile);
                $book_file=Files::create([
                    'user_id' => $user_id,
                    'disk' => config('filesystems.default'),
                    'filename' => $afile->getClientOriginalName(),
                    'path' => $path_audio,
                    'extension' => $afile->guessClientExtension() ?? '',
                    'mime' => $afile->getClientMimeType(),
                    'size' => $afile->getSize(),
                ]);
                DB::table('entity_files')->insert([
                    'files_id' => $book_file->id,
                    'entity_type'=>'Modules\Ebook\Entities\Ebook',
                    'entity_id'=>$ebook->id,
                    'zone'=>'audio_book_files',
                    'created_at'=>$book_file->created_at,
                    'updated_at'=>$book_file->updated_at,
                ]);
            }
        }
       
        return back()->withSuccess(clean(trans('cynoebook::ebook.upload_success')));
    }
    
    /**
     * Show the form for edit resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if(!auth()->user())
        {
            return abort(404);
        }
        $ebook = Ebook::with([
            'files'
        ])->withoutGlobalScope('active')->where(['slug'=>$slug,'user_id'=>auth()->user()->id])->firstOrFail();
        $ebook->password_protected=$ebook->password;
        return view('public.account.ebooks.edit',['categories' => Category::treeList(),'ebook'=> $ebook,'authors' => Author::list()]);
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,UpdateEbookRequest $request)
    {
        $user_id=auth()->user()->id;
        $ebook = Ebook::withoutGlobalScope('active')->where(['user_id'=>$user_id])->findOrFail($id);
        
        $ebook->title=$request->title;
        $ebook->description=strip_tags($request->description);
        $ebook->short_description=strip_tags($request->short_description);
        $ebook->publication_year=$request->publication_year;
        $ebook->publisher=$request->publisher;
        $ebook->price=$request->price;
        $ebook->buy_url=$request->buy_url;
        $ebook->isbn=$request->isbn;
        $ebook->file_type=$request->file_type;
        $ebook->file_url=$request->file_url;
        $ebook->embed_code=$request->embed_code;
        //$ebook->categories()->sync($request->categories);
        $ebook->is_private=$request->is_private;
        $ebook->password=$request->password_protected;
        $ebook->save();
        
        if($request->hasFile('book_cover'))
        {
            $file_image = $request->file('book_cover');
            $path_image = Storage::putFile('media', $file_image);
            $book_cover=Files::create([
                'user_id' => $user_id,
                'disk' => config('filesystems.default'),
                'filename' => $file_image->getClientOriginalName(),
                'path' => $path_image,
                'extension' => $file_image->guessClientExtension() ?? '',
                'mime' => $file_image->getClientMimeType(),
                'size' => $file_image->getSize(),
            ]);
            $ebook->files()->wherePivot('zone', 'book_cover')->detach();
            DB::table('entity_files')->insert([
                [
                    'files_id' => $book_cover->id,
                    'entity_type'=>'Modules\Ebook\Entities\Ebook',
                    'entity_id'=>$ebook->id,
                    'zone'=>'book_cover',
                    'created_at'=>$book_cover->created_at,
                    'updated_at'=>$book_cover->updated_at,
                ]
            ]);
        }
        if($request->hasFile('book_file'))
        {
            $file_pdf = $request->file('book_file');
            $path_pdf = Storage::putFile('media', $file_pdf);
            $book_file=Files::create([
                'user_id' => $user_id,
                'disk' => config('filesystems.default'),
                'filename' => $file_pdf->getClientOriginalName(),
                'path' => $path_pdf,
                'extension' => $file_pdf->guessClientExtension() ?? '',
                'mime' => $file_pdf->getClientMimeType(),
                'size' => $file_pdf->getSize(),
            ]);
            $ebook->files()->wherePivot('zone', 'book_file')->detach();
            DB::table('entity_files')->insert([
                [
                    'files_id' => $book_file->id,
                    'entity_type'=>'Modules\Ebook\Entities\Ebook',
                    'entity_id'=>$ebook->id,
                    'zone'=>'book_file',
                    'created_at'=>$book_file->created_at,
                    'updated_at'=>$book_file->updated_at,
                ]
            ]);
        }
        
        if($request->hasFile('audio_book_files'))
        {
            DB::table('entity_files')->where('zone',  'audio_book_files')->where('entity_id',  $ebook->id)->delete();
            $audioFiles = $request->file('audio_book_files');
            foreach($audioFiles as $afile){
                //dd($afile->guessClientExtension());
                $path_audio = Storage::putFile('media', $afile);
                $book_file=Files::create([
                    'user_id' => $user_id,
                    'disk' => config('filesystems.default'),
                    'filename' => $afile->getClientOriginalName(),
                    'path' => $path_audio,
                    'extension' => $afile->guessClientExtension() ?? '',
                    'mime' => $afile->getClientMimeType(),
                    'size' => $afile->getSize(),
                ]);
                DB::table('entity_files')->insert([
                    'files_id' => $book_file->id,
                    'entity_type'=>'Modules\Ebook\Entities\Ebook',
                    'entity_id'=>$ebook->id,
                    'zone'=>'audio_book_files',
                    'created_at'=>$book_file->created_at,
                    'updated_at'=>$book_file->updated_at,
                ]);
            }
        }
           
        
        return back()->withSuccess(clean(trans('cynoebook::ebook.upload_success')));
        
    }
    
    /**
     * Show the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $ebook = Ebook::findBySlug($slug);
        $ebookgameStatus = "Not played";
        $reviews = $this->getReviews($ebook);
        $cats=$ebook->categories->pluck('slug')->toArray();
        
        $relatedEbooks=Ebook::ForCard()->where('id','!=',$ebook->id)->whereHas('categories', function ($categoryQuery) use ($cats) {
            $categoryQuery->whereIn('slug', $cats);
        })->limit(10)->get();
       
        if (setting('reviews_enabled')) {
            $ebook->load('reviews:ebook_id,rating');
        }

        event(new EbookViewed($ebook));
        
        $unlock=1;
        if($ebook->password!='')
        {
            $unlock=0;
        }
       
        if(request()->session()->get('unlock'))
        {
            $passw=Crypt::decryptString(request()->session()->get('unlock'));
            if($ebook->password==$passw)
            {
                $unlock=1;
                $ebook->password='';
            }
          
        }
        $availableFiles=$this->getBookFile($ebook);
        $ebookgameStatus = "You can't play. please login.";
        if(!is_null(auth()->user())){
            
            // dd($ebook);
            $ebookgame = EbookGame::join('status', 'games.status_id', '=', 'status.id')
                                    ->where('ebook_id',$ebook->id)
                                    ->where('user_id', auth()->user()->id)->first();
            if(!is_null($ebookgame)){
                $ebookgame = $ebookgame->attributesToArray();
                $ebookgameStatus =  $ebookgame['name'];
            }else{
                $ebookgameStatus = "Not played";
            }
            
        }
        

        return view('public.ebooks.show', compact('ebook','reviews','relatedEbooks','unlock','availableFiles','ebookgameStatus'));
         
    }
    
    public function getBookFile($ebook) {
        
        if(is_null($ebook->file_type))  {
            if(!is_null($ebook->file_url)) $ebook->file_type='external_link';
            if($ebook->book_file->exists) $ebook->file_type='upload';
        }
        
        $mainFile=[];
        
        if($ebook->file_type=='upload' && $ebook->book_file->exists ){
            $extension=$ebook->book_file->extension;
            $url=$ebook->book_file->path;
            $type='upload';
            $filename=pathinfo($ebook->book_file->filename, PATHINFO_FILENAME);
            $viewer=$this->getFileViewer($extension);
            $mainFile[]=['extension'=>$extension,'url'=>$url,'type'=>$type,'filename'=>$filename,'viewer'=>$viewer,'file'=>$ebook->book_file];
            
        }
        if($ebook->file_type=='external_link' && !is_null($ebook->file_url)) {
            $extension = get_file_extension($ebook->file_url);
            $url=$ebook->file_url;
            $type='external';
            $filename=pathinfo($ebook->file_url, PATHINFO_FILENAME);
            $viewer=$this->getFileViewer($extension);
            $mainFile[]=['extension'=>$extension,'url'=>$url,'type'=>$type,'filename'=>$filename,'viewer'=>$viewer,'file'=>''];
        }
        if($ebook->file_type=='embed_code'){
            
            $mainFile[]=['extension'=>'','url'=>'','type'=>'embed','filename'=>'','viewer'=>'embed','file'=>''];
        }
        if($ebook->file_type=='audio' && $ebook->audio_book_files->isNotEmpty()){
            foreach($ebook->audio_book_files as $file){
                $extension=$file->extension;
                $filename=pathinfo($file->filename, PATHINFO_FILENAME);
                $viewer=$this->getFileViewer($extension);
                $url=$file->path;
                $mainFile[]=['extension'=>$extension,'url'=>$url,'type'=>'audio-upload','filename'=>$filename,'viewer'=>$viewer,'file'=>$file];
            }
            
        }
        return $mainFile;
    }
    
    public function getFileViewer($extension) {
        $gooleVExtension = ['docx','doc','txt','pptx','ppt','xls','xlsx'];
        $viewer='';
        if(in_array($extension,$gooleVExtension)) {
            $viewer='gview';
        }
        if($extension=='pdf') {
            $viewer='pdf';
        }
        if($extension=='epub') {
            $viewer='epub';
        }
        if($extension=='mpga' || $extension=='wav' || $extension=='mp3') {
            $viewer='audio';
        }
        return $viewer;
    }
    
    public function pdfviewer($slug) {
        $ebook = Ebook::findBySlug($slug);
        $b64Doc='';
        $fileURL='';
        if ($ebook->book_file->exists){
            $fileURL = $ebook->book_file->path;
            $fileExt = $ebook->book_file->extension;
        }
        if (!is_null($ebook->file_url)){
            $fileURL = $ebook->file_url;
            $fileExt = get_file_extension($ebook->file_url);
            $b64Doc = base64_encode(file_get_contents($fileURL));
        }
        $returnData=view('public.ebooks.viewer.pdfviewer',compact('ebook','fileURL','b64Doc'))->render();
        return Response::json(['success' => true,'html'=>$returnData]);
    }
    
    public function epubReader($slug)
    {
        $ebook = Ebook::findBySlug($slug);
        $file_url='';
        if ($ebook->book_file->exists){
            $file_url = $ebook->book_file->path;
        }
        return view('public.ebooks.viewer.epub', compact('ebook','file_url'));
    }
    
    public function unlock($slug) {
        $ebook = Ebook::findBySlug($slug);
        
        
        if(request()->has('unlockpassword'))
        {
            
            $request=request()->all();
            if($ebook->password!=$request['unlockpassword'])
            {
                return redirect()->route('ebooks.show',$ebook->slug)->withErrors(['unlockpassword'=>clean(trans('ebook::messages.please_enter_valid_password'))]);
            }
            
            request()->session()->put('unlock',Crypt::encryptString($request['unlockpassword']));
            
            return redirect()->route('ebooks.show',$ebook->slug);
        }
        return redirect()->route('ebooks.show',$ebook->slug)->withErrors(['unlockpassword'=>trans(clean('Ebook::messages.please_enter_password_to_view_this_ebook'))]);
    }
    
    
    /**
     * Get reviews for the given ebook.
     *
     * @param \Modules\Ebook\Entities\Ebook $ebook
     * @return \Illuminate\Support\Collection
     */
    private function getReviews($ebook)
    {
        if (! setting('reviews_enabled')) {
            return collect();
        }

        return $ebook->reviews()->orderBy('id','desc')->paginate(5, ['*'], 'reviews');
    }
    
    public function download($slug,$fileID=0)
    {
        
        $ebook = Ebook::with([
            'files'
        ])->withoutGlobalScope('active')->where('slug', $slug)->firstOrFail();
        
        if($fileID){
            $id=id_decode($fileID);
            $files = Files::where('id', $id)->firstOrFail();
           
            if($files)
            {
                $files->increment('download');
                $ebook->increment('download');
                
                activity('file')
                    ->performedOn($files)
                    ->causedBy(auth()->user())
                    ->withProperties(['subject' => $files,'causer'=>auth()->user()])
                    ->log('download');
                
                $headers = [
                  'Content-Type' => $files->mime,
                ];
                $path=$files->path;
                $temp = tempnam(sys_get_temp_dir(), $files->filename);
                copy($path, $temp);
                return response()->download($temp, $files->filename, $headers);
            }
        }
        
        if(is_null($ebook->file_type))  {
            if(!is_null($ebook->file_url)) $ebook->file_type='external_link';
            
        }
        
        if($ebook->file_type=='external_link')
        {
            $filename = basename($ebook->file_url);
            $temp = tempnam(sys_get_temp_dir(), $filename);
            copy($ebook->file_url, $temp);
            $ebook->increment('download');
            return response()->download($temp, $filename);
            
        }
            return back();
        
        
    }
    
    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($slug)
    {
        
        $ebook = Ebook::with([
            'files'
            ])->withoutGlobalScope('active')->where('slug', $slug)->firstOrFail();
        if($ebook)
        {
            if(auth()->user())
            {
                if(auth()->user()->id==$ebook->user_id)
                {
                    $ebook->delete();
                    return back()->withSuccess(clean(trans('ebook::messages.ebook_removed')));
                }
            }
        }
        return back()->withErrors(clean(trans('ebook::messages.ebook_removed_error')));
        
    }

    public function gameRunNow(Request $request)
    {

        $game_id = "";
        
        $gameBaseInfo['ebook_id']        = $request->input('ebook_id');
        $gameBaseInfo['ebook_title']     = $request->input('ebook_title');
        $gameBaseInfo['next_episode_id'] = $request->input('next_episode_id');
        $gameBaseInfo['is_final']        = $request->input('is_final');
        $gameToken                       = $request->input('gameToken');
        $gamehasItems      = [];
        $gamehasSkills     = [];
        $gamehasCodewords  = [];
        $gameInfo          = [];
        $increment_flag = false;
        
        
        
        
        $ebookgame = EbookGame::where('ebook_id',$gameBaseInfo['ebook_id'])
                    ->where('user_id',auth()->user()->id)->first();
                    
        if(!is_null($gameBaseInfo['is_final'])&&!is_null($ebookgame))                     
        {
            $ebookgame_info = $ebookgame->attributesToArray();
            $game_id = $ebookgame_info['id'];
            EbookGame::where('id',$game_id)
                    ->delete();
            EbookGameHasItems::where('game_id',$game_id)
                    ->delete();
            EbookGameHasSkills::where('game_id',$game_id)
                    ->delete();
            EbookGameHasCodewords::where('game_id',$game_id)
                    ->delete();
            $ebookgame = null;
        }

         
        if(!is_null($ebookgame))
        {  
            if($gameToken == request()->session()->get('gameToken')){

                $increment_flag = true;
            }
            $gameToken = Str::random(32);
            request()->session()->put('gameToken',$gameToken);

           $gameEpisodeInfo = $this -> nextGameResume($gameBaseInfo,$ebookgame,$increment_flag);
           
        }else{
            
            $gameToken = Str::random(32);
            request()->session()->put('gameToken',$gameToken);

            $gameEpisodeInfo = $this -> newGameStart($gameBaseInfo,$ebookgame);
            
        };

        EbookGameHasItems::where('value','<',0)
                ->update(['value' => 0]);
        EbookGameHasSkills::where('value','<',0)
                ->update(['value' => 0]);
        EbookGameHasCodewords::where('value','<',0)
                ->update(['value' => 0]);

        $Items      =  EbookGameHasItems::select('games_has_items.item_id','items.item_name','items.item_slug','games_has_items.value','files.path')
        ->join('items', 'games_has_items.item_id', '=', 'items.id')
        ->leftjoin('files', 'items.item_image', '=', 'files.id')
        ->where('games_has_items.game_id', $gameEpisodeInfo['game_id'])->get();
       
        foreach($Items as $key => $value){
        array_push($gamehasItems,$value->attributesToArray()); 
        }

        $Skills     =  EbookGameHasSkills::select('games_has_skills.skill_id','skills.skill_name','skills.skill_slug','games_has_skills.value')
                ->join('skills', 'games_has_skills.skill_id', '=', 'skills.id')
                ->where('games_has_skills.game_id', $gameEpisodeInfo['game_id'])->get();
                
   
        foreach($Skills as $key => $value){
        array_push($gamehasSkills,$value->attributesToArray()); 
        }

        $Codewords  =  EbookGameHasCodewords::select('games_has_codewords.codeword_id','codewords.codeword_name','codewords.codeword_slug','games_has_codewords.value')
                ->join('codewords', 'games_has_codewords.codeword_id', '=', 'codewords.id')
                ->where('games_has_codewords.game_id', $gameEpisodeInfo['game_id'])->get();

        foreach($Codewords as $key => $value){
        array_push($gamehasCodewords,$value->attributesToArray()); 
        }

        // $gameBaseInfo['current_episode'] = $gameEpisodeInfo['current_episode'];
        // $gameBaseInfo['next_episodes']  = $gameEpisodeInfo['next_episodes'];
        $gameInfo['ebook_id'] = $gameBaseInfo['ebook_id'];
        $gameInfo['ebook_title'] = $gameBaseInfo['ebook_title'];
        $gameInfo['current_episode'] = $gameEpisodeInfo['current_episode'];
        $gameInfo['next_episodes'] = $gameEpisodeInfo['next_episodes'];
        $gameInfo['gamehasItems'] = $gamehasItems;
        $gameInfo['gamehasSkills'] = $gamehasSkills;
        $gameInfo['gamehasCodewords'] = $gamehasCodewords;
        $gameInfo['gameToken'] = $gameToken;
        
        return view('public.ebooks.gameEbook', $gameInfo);
    }
    
    public function gameFinish(Request $request)
    {
        $user_id = auth()->user()->id;
        $ebook_id = $request->input('ebook_id');
        $ebookgame = EbookGame::where('ebook_id',$ebook_id)
                                ->where('user_id',$user_id)->first();
                             
        if(!is_null($ebookgame))
        {
            $ebookgame_info = $ebookgame->attributesToArray();
            $game_id = $ebookgame_info['id'];
            EbookGame::where('id',$game_id)
            ->update([
                'status_id' => 2
            ]);
            
        }
        
        return redirect()->route('ebooks.index');
       
    }

    public function nextGameResume($gameBaseInfo,$ebookgame,$increment_flag)
    {
        $hero_info          = [];
        $current_episode    = [];
        $next_episodes      = [];
        $gamehasItems       = [];
        $gamehasSkills      = [];
        $gamehasCodewords   = [];

        

        $ebookgame_info = $ebookgame->attributesToArray();
        $game_id = $ebookgame_info['id'];

        if(!is_null($gameBaseInfo['next_episode_id'])) //next game resume
        {
            $episode = EbookEpisodes::where('id', $gameBaseInfo['next_episode_id'])->first();
        
            if(!is_null($episode))
            {
                $current_episode = $episode->attributesToArray();
                $links  =  EbookEpisodesHasNextEpisodes::where('episode_id', $current_episode['id'])->get();

                foreach($links as $key => $value){
                    array_push($next_episodes,$value->attributesToArray()); 
                }   
                EbookGameEpisodeSteps::where('game_id',$game_id)->
                update([
                       'episode_id' => $current_episode['id']
                    
                ]);
                if($increment_flag){
                    $episodesHasItems  =  EbookEpisodesHasItems::where('episode_id', $current_episode['id'])->get();  
                    foreach($episodesHasItems as $key => $value){
                        EbookGameHasItems::where('item_id',$value['item_id'])
                                            ->increment('value', $value['value']);
                    } 
                    $episodesHasSkills  =  EbookEpisodesHasSkills::where('episode_id', $current_episode['id'])->get();  
                    foreach($episodesHasSkills as $key => $value){
                        EbookGameHasSkills::where('skill_id',$value['skill_id'])
                                            ->increment('value', $value['value']);
                    }   
                    $episodesHasCodewords  =  EbookEpisodesHasCodewords::where('episode_id', $current_episode['id'])->get();  
                    foreach($episodesHasCodewords as $key => $value){
                        EbookGameHasCodewords::where('codeword_id',$value['codeword_id'])
                                            ->increment('value', $value['value']);
                    }
                }
                
            }
        }
        else    //saved game resume
        {
            $episode = EbookGameEpisodeSteps::select('episodes.id','ebook_id','number','description','has_death','has_fight','has_dice','is_first','is_last','has_death')
                    ->join('episodes', 'game_episodes_steps.episode_id', '=', 'episodes.id')
                    ->where('game_id', $game_id)->first();
    
            if(!is_null($episode)){
                $current_episode = $episode->attributesToArray();
                $links  =  EbookGameEpisodeSteps::select('game_episodes_steps.episode_id','episodes_has_next_episodes.next_episode_id','episodes_has_next_episodes.text','episodes_has_next_episodes.is_even')
                        ->join('episodes_has_next_episodes', 'game_episodes_steps.episode_id', '=', 'episodes_has_next_episodes.episode_id')
                        ->where('game_id', $game_id)->get();
            
                foreach($links as $key => $value){
                    array_push($next_episodes,$value->attributesToArray()); 
                } 
            }
        }   
        
            
       
        $gameEpisodeInfo['current_episode']   =  $current_episode;
        $gameEpisodeInfo['next_episodes']     =  $next_episodes;
        $gameEpisodeInfo['game_id']     =  $game_id;
    
        
        return $gameEpisodeInfo;
    }

    public function newGameStart($gameBaseInfo,$ebookgame)
    {
        $hero_info          = [];
        $current_episode    = [];
        $next_episodes      = [];
        $gamehasItems       = [];
        $gamehasSkills      = [];
        $gamehasCodewords   = []; 
        
        $hero = EbookHero::where('heroes.ebook_id', $gameBaseInfo['ebook_id'])->first();
        $episode = EbookEpisodes::where('ebook_id', $gameBaseInfo['ebook_id'])
                                                ->orderBy('is_first', 'DESC')
                                                ->orderBy('number', 'ASC')->first();
    
     
        if(is_null($hero))
        {
            $game_id=EbookGame::create([
                'user_id' => auth()->user()->id,
                'ebook_id' =>$gameBaseInfo['ebook_id'],
                'status_id' => 1
            ])->id;
        }
        else
        {    

            $hero_info = $hero->attributesToArray();
            $hero_id = $hero_info['id'];

            $game_id=EbookGame::create([
                'hero_id' => $hero_info['id'],
                'user_id' => auth()->user()->id,
                'ebook_id' =>$gameBaseInfo['ebook_id'],
                'status_id' => 1
            ])->id;

            $heroHasItems   =  EbookHeroHasItems::groupBy('heroes_has_items.item_id')->select('heroes_has_items.item_id','items.item_name','items.item_slug',DB::raw("SUM(heroes_has_items.value) as value"),'files.path')
                                            ->join('items', 'heroes_has_items.item_id', '=', 'items.id')
                                            ->leftjoin('files', 'items.item_image', '=', 'files.id')
                                            ->where('hero_id', $hero_id)
                                            ->get();
                                         
            foreach($heroHasItems as $key => $value){
                $value = $value->attributesToArray();
                
                EbookGameHasItems::create([
                    'game_id'  => $game_id,
                    'item_id' => $value['item_id'],
                    'value'    => $value['value']
                ]);
              
            }

            $heroHasSkills     =  EbookHeroHasSkills::groupBy('heroes_has_skills.skill_id')->select('heroes_has_skills.skill_id','skills.skill_name','skills.skill_slug',DB::raw("SUM(heroes_has_skills.value)as value"))
                                                ->join('skills', 'heroes_has_skills.skill_id', '=', 'skills.id')
                                                ->where('hero_id', $hero_id)
                                                ->get();

            foreach($heroHasSkills as $key => $value){
                $value = $value->attributesToArray();

                EbookGameHasSkills::create([
                    'game_id'  => $game_id,
                    'skill_id' => $value['skill_id'],
                    'value'    => $value['value']
                ]);
                
            }
            
            $heroHasCodewords  =  EbookHeroHasCodewords::groupBy('heroes_has_codewords.codeword_id') ->select('heroes_has_codewords.codeword_id','codewords.codeword_name','codewords.codeword_slug',DB::raw("SUM(heroes_has_codewords.value)as value"))
                                                ->join('codewords', 'heroes_has_codewords.codeword_id', '=', 'codewords.id')
                                                ->where('hero_id', $hero_id)->get();
                                                
            foreach($heroHasCodewords as $key => $value){
                $value = $value->attributesToArray();
                
                EbookGameHasCodewords::create([
                    'game_id'  => $game_id,
                    'codeword_id' => $value['codeword_id'],
                    'value'    => $value['value']
                ]);
              
            }
                                                        
            if(!is_null($episode))
            {
    
    
                $current_episode = $episode->attributesToArray();
                
                $links  =  EbookEpisodesHasNextEpisodes::where('episode_id', $current_episode['id'])->get();
    
                foreach($links as $key => $value)
                {
                    array_push($next_episodes,$value->attributesToArray()); 
                }   
                
                $episodesHasItems  =  EbookEpisodesHasItems::where('episode_id', $current_episode['id'])->get();  
                foreach($episodesHasItems as $key => $value){
                    EbookGameHasItems::where('item_id',$value['item_id'])
                                        ->increment('value', $value['value']);
                } 

                $episodesHasSkills  =  EbookEpisodesHasSkills::where('episode_id', $current_episode['id'])->get();  
                foreach($episodesHasSkills as $key => $value){
                    EbookGameHasSkills::where('skill_id',$value['skill_id'])
                                        ->increment('value', $value['value']);
                }   

                $episodesHasCodewords  =  EbookEpisodesHasCodewords::where('episode_id', $current_episode['id'])->get();  
                foreach($episodesHasCodewords as $key => $value){
                    EbookGameHasCodewords::where('codeword_id',$value['codeword_id'])
                                        ->increment('value', $value['value']);
                }

                EbookGameEpisodeSteps::create([
                    'game_id' => $game_id,
                    'episode_id' => $current_episode['id']
                    
                ]);
            }
            
           
        }

        
        $gameEpisodeInfo['current_episode']   =  $current_episode;
        $gameEpisodeInfo['next_episodes']     =  $next_episodes;
        $gameEpisodeInfo['game_id']     =  $game_id;
        
        return $gameEpisodeInfo;
    }
}

