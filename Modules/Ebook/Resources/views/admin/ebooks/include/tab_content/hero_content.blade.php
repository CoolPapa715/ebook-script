@component('ebook::admin.ebooks.include.table.table')
    @slot('title', clean(trans('ebook::ebooks.form.heroes_table.title')))
    @slot('buttons', ['create'])
    @slot('resource', 'heroes')
    @slot('name', clean(trans('ebook::ebooks.form.heroes_table.title')))

    @slot('thead')
        <tr>
            @include('admin::include.table.select-all',["name"=>trans('ebook::ebooks.form.heroes_table.title')])
            <th>{{ clean(trans('ebook::ebooks.form.heroes_table.image')) }}</th>
            <th>{{ clean(trans('ebook::ebooks.form.heroes_table.name')) }}</th>
            <th>{{ clean(trans('ebook::ebooks.form.heroes_table.slug')) }}</th>
            <th>{{ clean(trans('ebook::ebooks.form.heroes_table.description')) }}</th>
            <th data-sort>{{ clean(trans('admin::admin.table.created')) }}</th>
        </tr>
    @endslot
@endcomponent

<div class="modal fade episode_modal" id="heroes_create_modal" role="dialog" style="overflow-x: hidden !important; overflow-y: auto !important;">
    <div class="modal-dialog modal-lg">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='display:block'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Hero</h4>
        </div>
        
        <div class="modal-body">
            <div class="card">
                <div class="card-header" style="display:block">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Hero</h4>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="hero_id" class="form-control">

                    {{ Form::text('hero_name',clean(trans('ebook::attributes.item_name')), $errors, $ebook ) }}
                    {{ Form::text('hero_slug',clean(trans('ebook::attributes.item_slug')), $errors, $ebook ) }}
                    
                
                    @include('files::admin.image_picker.single', [
                        'title' => clean(trans('ebook::attributes.item_image')),
                        'inputName' => 'files[hero_img]',
                        'file' => optional($ebook->hero_image),
                    ])
                    
                    {{ Form::wysiwyg('hero_description', clean(trans('ebook::attributes.description')), $errors, $ebook, ['rows' => 2,]) }}

                </div>
                <div class="card-header" style="display:block">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Items</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class='col-md-12' >
                            <table id='hero_item_table' class='record_table'> 
                            </table>
                        </div>
                        
                    </div>
                    <br>
                    <div class='row'>
                        <div class='col-md-6'>
                            <select class='form-control item_select' data-url='admin/ebooks/get_items'>
                               <option></option>
                            </select>
                        </div>
                        <div class='col-md-3'>
        
                            <input type='number' class='form-control' min='1'>
                        </div>
                        <div class='col-md-3'>
                            <a class='btn btn-sm btn-primary record_add' style='color: white' data-type='hero_item'>+</a>
                        </div>
                    </div>
                </div>
                <div class="card-header" style="display:block">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Skills</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class='col-md-12' >
                            <table id='hero_skill_table' class='record_table'>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class='row'>
                        <div class='col-md-6'>
                            <select class='form-control skill_select' data-url='admin/ebooks/get_skills'>
                               <option></option>
                            </select>
                        </div>
                        <div class='col-md-3'>
                            <input type='number' class='form-control' min='1'>
                        </div>
                        <div class='col-md-3'>
                            <a class='btn btn-sm btn-primary record_add' style='color: white' data-type='hero_skill'>+</a>
                        </div>
                    </div>
                </div>
                <div class="card-header" style="display:block">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Codewords</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class='col-md-12' >
                            <table id='hero_codeword_table' class='record_table'>
                            </table>
                        </div>
                        
                    </div>
                    <br>
                    <div class='row'>
                        <div class='col-md-6'>
                            <select class='form-control codeword_select' data-url='admin/ebooks/get_codewords'>
                               <option></option>
                            </select>
                        </div>
                        <div class='col-md-3'>
                            <input type='number' class='form-control' min='1'>
                        </div>
                        <div class='col-md-3'>
                            <a class='btn btn-sm btn-primary record_add' style="color: white" data-type='hero_codeword'>+</a>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete m-2" id="hero_save">save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      </form>
    </div>
</div>
