<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;
use Session;

class EbookSkillTemp extends Model
{
    protected $table = "skills";

    protected $fillable = [
        'skill_ebook_id',
        'skill_name',
        'skill_slug',
        'skill_description',
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
                // ->where('ci_auth', $request->session()->get('ci_auth'))
                ->Where('ebook_id', $ebook_id)
                ->orwhereNull('ebook_id');
        }
        else{
            $query = $this->newQuery()
            ->where('ci_auth', $request->session()->get('ci_auth'))
            ->whereNull('ebook_id')->get();
        }
            
        return new EbookTable($query);
    }

    public function edit($skill_id)
    {
        $query = $this->newQuery()
            ->where('skills.id', $skill_id)
            ->first();
           
        return $query->attributesToArray();
    }
}