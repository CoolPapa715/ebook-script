<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;

class EbookHeroHasItems extends Model
{
    protected $table = "heroes_has_items";

    protected $fillable = [
        'hero_id',
        'item_id',
        'value',
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
        $query = $this->newQuery()
            ->where('ci_auth', $request->session()->get('ci_auth'));
            // ->addSelect(['items.id', 'items.item_name','items.item_slug','items.item_description', 'items.item_user_id','items.created_at','items.updated_at','files.path as item_image']);
            // ->when($request->has('except'), function ($query) use ($request) {
            //     $query->whereNotIn('ti_id', explode(',', $request->except));
            // });
          
        return new EbookTable($query);
    }

   
}