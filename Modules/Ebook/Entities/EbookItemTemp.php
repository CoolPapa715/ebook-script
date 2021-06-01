<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;
use Session;

class EbookItemTemp extends Model
{
    protected $table = "items";

    protected $fillable = [
        'item_ebook_id',
        'item_name',
        'item_slug',
        'item_image',
        'item_description',
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
                ->Select(['items.id', 'items.item_name','items.item_slug','items.item_description','items.item_image as item_image_id','items.created_at','items.updated_at','files.path as item_image'])
                ->leftJoin('files', 'items.item_image', '=', 'files.id')
                ->where('ci_auth', $request->session()->get('ci_auth'))
                ->orWhere('ebook_id', $ebook_id)->get();
        }else{
            $query = $this->newQuery()
                ->Select(['items.id', 'items.item_name','items.item_slug','items.item_description','items.item_image as item_image_id','items.created_at','items.updated_at','files.path as item_image'])
                ->leftJoin('files', 'items.item_image', '=', 'files.id')
                ->where('ci_auth', $request->session()->get('ci_auth'))
                ->whereNull('ebook_id')->get();
        }
        
            // ->when($request->has('except'), function ($query) use ($request) {
            //     $query->whereNotIn('ti_id', explode(',', $request->except));
            // });
          
        return new EbookTable($query);
    }

    public function edit($item_id)
    {
        $query = $this->newQuery()
            ->where('items.id', $item_id)
            ->first();

        return $query->attributesToArray();
    }
    

    
}