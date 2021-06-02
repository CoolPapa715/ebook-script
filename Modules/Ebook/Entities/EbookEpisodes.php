<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;
use Session;

class EbookEpisodes extends Model
{
    protected $table = "episodes";

    protected $fillable = [
        'ebook_id',
        'number',
        'description',
        'has_death',
        'has_fight',
        'has_dice',
        'is_first',
        'is_last',
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
            ->Select(['episodes.id', 'episodes.number as episode_number','episodes.description as episode_description','episodes.created_at'])
            // ->where('ci_auth', $request->session()->get('ci_auth'))
            ->Where('ebook_id', $ebook_id)
            ->orwhereNull('ebook_id');
        }else{
            $query = $this->newQuery()
            ->Select(['episodes.id', 'episodes.number as episode_number','episodes.description as episode_description','episodes.created_at'])
            ->where('ci_auth', $request->session()->get('ci_auth'))
            ->whereNull('ebook_id')->get();
        }
        
          
        return new EbookTable($query);
    }

    public function edit($episode_id)
    {
        $query = $this->newQuery()
            // ->leftJoin('files', 'heroes.hero_image', '=', 'files.id')
            ->where('episodes.id', $episode_id)
            ->first();

        return $query->attributesToArray();
    }

    
}