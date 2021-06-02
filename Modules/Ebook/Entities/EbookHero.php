<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;
use Session;
class EbookHero extends Model
{
    protected $table = "heroes";

    protected $fillable = [
        'hero_ebook_id',
        'hero_name',
        'hero_slug',
        'hero_image',
        'hero_description',
        'ci_auth'
    ];

     /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table($request)
    {
        $ebook_id = Session::get('ebook_id');
        
        if(!is_null($ebook_id)){
            $query = $this->newQuery()
                ->Select(['heroes.id', 'heroes.hero_name','heroes.hero_slug','heroes.hero_description', 'heroes.created_at','heroes.updated_at','files.path as hero_image'])
                ->leftJoin('files', 'heroes.hero_image', '=', 'files.id')
                // ->where('ci_auth', $request->session()->get('ci_auth'))
                ->Where('ebook_id', $ebook_id)
                ->orwhereNull('ebook_id');
    
        }else{
            $query = $this->newQuery()
                ->Select(['heroes.id', 'heroes.hero_name','heroes.hero_slug','heroes.hero_description', 'heroes.created_at','heroes.updated_at','files.path as hero_image'])
                ->leftJoin('files', 'heroes.hero_image', '=', 'files.id')
                ->where('ci_auth', $request->session()->get('ci_auth'))
                ->whereNull('ebook_id')->get();

        }
        
          
        return new EbookTable($query);
    }

    public function edit($hero_id)
    {
        $query = $this->newQuery()
            ->addSelect(['heroes.id', 'heroes.hero_name','heroes.hero_slug','heroes.hero_description','heroes.hero_image'])
            ->where('heroes.id', $hero_id)
            ->first();
            // ->when($request->has('except'), function ($query) use ($request) {
            //     $query->whereNotIn('ti_id', explode(',', $request->except));
            // });
            
        return $query->attributesToArray();
    }

    
}