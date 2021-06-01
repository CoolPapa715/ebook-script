@component('ebook::admin.ebooks.include.table.table')
@slot('title', clean(trans('ebook::ebooks.form.items_table.title')))
@slot('buttons', ['create'])
@slot('resource', 'items')
@slot('name', clean(trans('ebook::ebooks.form.items_table.title')))

@slot('thead')
    <tr>
        @include('admin::include.table.select-all',["name"=>trans('ebook::ebooks.form.items_table.title')])
        <th>{{ clean(trans('ebook::ebooks.form.items_table.image')) }}</th>
        <th>{{ clean(trans('ebook::ebooks.form.items_table.name')) }}</th>
        <th>{{ clean(trans('ebook::ebooks.form.items_table.slug')) }}</th>
        <th>{{ clean(trans('ebook::ebooks.form.items_table.description')) }}</th>

        <th hidden></th>
        <th data-sort>{{ clean(trans('admin::admin.table.created')) }}</th>
    </tr>
@endslot
@endcomponent

@component('ebook::admin.ebooks.include.table.table')
@slot('title', clean(trans('ebook::ebooks.form.skills_table.title')))
@slot('buttons', ['create'])
@slot('resource', 'skills')
@slot('name', clean(trans('ebook::ebooks.form.skills_table.title')))

@slot('thead')
    <tr>
        @include('admin::include.table.select-all',["name"=>trans('ebook::ebooks.form.skills_table.slug')])
        <th>{{ clean(trans('ebook::ebooks.form.skills_table.name')) }}</th>
        <th>{{ clean(trans('ebook::ebooks.form.skills_table.slug')) }}</th>
        <th>{{ clean(trans('ebook::ebooks.form.skills_table.description')) }}</th>
        <th data-sort>{{ clean(trans('admin::admin.table.created')) }}</th>
    </tr>
@endslot
@endcomponent

@component('ebook::admin.ebooks.include.table.table')
@slot('title', clean(trans('ebook::ebooks.form.codewords_table.title')))
@slot('buttons', ['create'])
@slot('resource', 'codewords')
@slot('name', clean(trans('ebook::ebooks.form.codewords_table.title')))

@slot('thead')
    <tr>
        @include('admin::include.table.select-all',["name"=>trans('ebook::ebooks.form.codewords_table.name')])
        <th>{{ clean(trans('ebook::ebooks.form.codewords_table.name')) }}</th>
        <th>{{ clean(trans('ebook::ebooks.form.codewords_table.slug')) }}</th>
        <th data-sort>{{ clean(trans('admin::admin.table.created')) }}</th>
    </tr>
@endslot
@endcomponent

<div class="modal fade" id="items_create_modal" role="dialog">
    <div class="modal-dialog modal-lg">
       <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='display:block'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Items</h4>
        </div>
        <div class="modal-body">
                <input type="hidden" id="item_id" class="form-control">
                {{ Form::text('item_name',clean(trans('ebook::attributes.item_name')), $errors, $ebook ) }}
                {{ Form::text('item_slug',clean(trans('ebook::attributes.item_slug')), $errors, $ebook ) }}
                
               
                @include('files::admin.image_picker.single', [
                    'title' => clean(trans('ebook::attributes.item_image')),
                    'inputName' => 'files[item_img]',
                    'file' => optional($ebook->item_image),
                ])
                
                {{ Form::wysiwyg('item_description', clean(trans('ebook::attributes.description')), $errors, $ebook, ['rows' => 2,]) }}
               
            
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete m-2" id="item_save">save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      </form>
    </div>
</div>

<div class="modal fade" id="skills_create_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='display:block'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit SKills</h4>
        </div>
        <div class="modal-body">
                <input type="hidden" id="skill_id" class="form-control">
                {{ Form::text('skill_name',clean(trans('ebook::attributes.item_name')), $errors, $ebook ) }}
                {{ Form::text('skill_slug',clean(trans('ebook::attributes.item_slug')), $errors, $ebook ) }}
                {{ Form::wysiwyg('skill_description', clean(trans('ebook::attributes.description')), $errors, $ebook, ['rows' => 2,]) }}
               
            
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete m-2" id="skill_save">save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      </form>
    </div>
</div>

<div class="modal fade" id="codewords_create_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='display:block'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit CodeWords</h4>
        </div>
        <div class="modal-body">
                <input type="hidden" id="codeword_id" class="form-control">
                {{ Form::text('codeword_name',clean(trans('ebook::attributes.item_name')), $errors, $ebook ) }}
                {{ Form::text('codeword_slug',clean(trans('ebook::attributes.item_slug')), $errors, $ebook ) }}
            
        </div>
        
        <div class="modal-footer"> 
          <button type="button" class="btn btn-primary btn-delete m-2" id="codeword_save">save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      </form>
    </div>
</div>
