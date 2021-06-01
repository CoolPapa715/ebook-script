<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;

class EbookEpisodesHasCodewords extends Model
{
    protected $table = "episodes_has_codewords";

    protected $fillable = [
        'episode_id',
        'codeword_id',
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
                    
        return new EbookTable($query);
    }

        
}