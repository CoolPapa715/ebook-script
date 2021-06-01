<?php

namespace Modules\Ebook\Admin\Table;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Ui\AdminTable;

class EbookTable extends AdminTable
{
    /**
     * Raw columns that will not be escaped.
     *
     * @var array
     */
    protected $rawColumns = ['bookcover','password','item_image','item_description','skill_description','hero_description','hero_image','episode_description'];

    /**
     * Make table response for the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function make()
    {
        return $this->newTable()
            ->editColumn('episode_description', function ($episode) {
                $description = $episode->episode_description;
                // $description = '<br>Each</br> <b>abc</b>';
                // $new = strip_tags($description, '<br>');
                return view('ebook::admin.ebooks.include.table.description', compact('description'));
            })
            ->editColumn('hero_description', function ($hero) {
                $description = $hero->hero_description;
                // $description = '<br>Each</br> <b>abc</b>';
                // $new = strip_tags($description, '<br>');
                return view('ebook::admin.ebooks.include.table.description', compact('description'));
            })
            ->editColumn('hero_image', function ($hero) {
            
                
                if(!is_null($hero->hero_image))
                    $path = Storage::url($hero->hero_image);
                else
                    $path = $hero->hero_image;
                
                return view('ebook::admin.ebooks.include.table.bookcover', compact('path'));
            })
            ->editColumn('skill_description', function ($skill) {
                $description = $skill->skill_description;
                // $description = '<br>Each</br> <b>abc</b>';
                // $new = strip_tags($description, '<br>');
                return view('ebook::admin.ebooks.include.table.description', compact('description'));
            })
            ->editColumn('item_description', function ($item) {
                $description = $item->item_description;
                // $description = '<br>Each</br> <b>abc</b>';
                // $new = strip_tags($description, '<br>');
                return view('ebook::admin.ebooks.include.table.description', compact('description'));
            })
            ->editColumn('item_image', function ($item) {
            
                
                if(!is_null($item->item_image))
                    $path = Storage::url($item->item_image);
                else
                    $path = $item->item_image;
                
                return view('ebook::admin.ebooks.include.table.bookcover', compact('path'));
            })
            ->editColumn('bookcover', function ($ebook) {
                $path = optional($ebook->book_cover)->path;
                return view('ebook::admin.ebooks.include.table.bookcover', compact('path'));
            })
            ->editColumn('password', function ($ebook) {
                if($ebook->password!=''){return '<i class="fas fa-lock"></i>';}else{return '-';}
            })
            ->editColumn('is_featured', function ($ebook) {
                if($ebook->is_featured==1){return 'Yes';}else{return 'No';}
            })
            ->editColumn('is_private', function ($ebook) {
                if($ebook->is_private==1){return 'Yes';}else{return 'No';}
            });
    }
}
