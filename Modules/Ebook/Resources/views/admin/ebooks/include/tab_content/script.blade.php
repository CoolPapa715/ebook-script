@push('scripts')
<style>
    .hide_column{display: none}
</style>
<script>
    var item_option = [];
    var skill_option = [];
    var codeword_option = [];
    var episode_option = [];
    var start_flag = true;

    function get_item_option()
    {
        var server_url =  $('.item_select').attr('data-url');
        $.ajax({
            url:document.baseURI+server_url,//"{{route('admin.ebooks.items_create')}}",
            type: 'POST',  
            dataType:'json',
            success: function (data) {   // success callback function
                item_option = [];
                $('.item_select').html("");
                $.each(data,function(index,json){
                    item_option.push({id: json.id, text:json.item_name});
                  
                });
                $('.item_select').select2({data:item_option});

            },
                
            });
    }

    function get_skill_option()
    {
        var server_url =  $('.skill_select').attr('data-url');
        $.ajax({

            url:document.baseURI+server_url,//"{{route('admin.ebooks.items_create')}}",
            type: 'POST',  
            dataType:'json',
            success: function (data) {   // success callback function
                skill_option = [];
                $('.skill_select').html("");
                $.each(data,function(index,json){
                    skill_option.push({id: json.id, text:json.skill_name});
                    // $('#skill_select').append('<option value="'+json.id+'"> '+json.skill_name+' </option>')
                });
                $('.skill_select').select2({data:skill_option});
                
            },
                
        });
    }

    function get_codeword_option()
    {
        var server_url =  $('.codeword_select').attr('data-url');

        $.ajax({
            url:document.baseURI+server_url,//"{{route('admin.ebooks.items_create')}}",
            type: 'POST',  
            dataType:'json',
            success: function (data) {   // success callback function
            
                $('.codeword_select').html("");
                codeword_option = [];
                $.each(data,function(index,json){
                    codeword_option.push({id: json.id, text:json.codeword_name});
                    //$('#codeword_select').append('<option value="'+json.id+'"> '+json.codeword_name+' </option>')
                });
                $('.codeword_select').select2({data:codeword_option});
            },
                
        });
    }

    function get_episode_option(){
        var server_url =  $('.episode_select').attr('data-url');

        $.ajax({
            url:document.baseURI+server_url,//"{{route('admin.ebooks.items_create')}}",
            type: 'POST',  
            dataType:'json',
            success: function (data) {   // success callback function
            
                $('.episode_select').html("");
                episode_option = [{id:'',text:''}];
                $.each(data,function(index,json){
                    episode_option.push({id: json.id, text:json.number});
                    //$('#codeword_select').append('<option value="'+json.id+'"> '+json.codeword_name+' </option>')
                });
                $('.episode_select').select2({placeholder: "Select a next episode",allowClear: true,data:episode_option});
            },
                
        });
    }

    function modal_clean() 
    {
        $(".form-control").val("");
        $(".record_table").empty();
        $(".image-holder-wrapper").html('<div class="image-holder placeholder cursor-auto"><i class="fas fa-camera-retro"></i><input type="hidden" name="undefined"></div>')
        $(".custom-control-input").prop('checked',false);
        tinymce.get("hero_description").setContent("");
        tinymce.get("episode_description").setContent("");
        tinymce.get("item_description").setContent("");
        tinymce.get("skill_description").setContent("");
        check_dice();
    }

    function record_delete(btn)
    {
        $(btn).parent().parent().remove();
    }

    function record_edit(btn)
    {

        if($(btn).hasClass("on"))
        {
            $(btn).html("Edit");
            $(btn).removeClass('on');
            $(btn).parent().parent().find('input').attr('readonly','true');
            $(btn).parent().parent().find('.name_input').css('display','block');
            $(btn).parent().parent().find("select").next(".select2-container").hide();
        }else{
            $(btn).html("Save");
            $(btn).addClass('on');
            $(btn).parent().parent().find('input').removeAttr('readonly');
            $(btn).parent().parent().find('.name_input').css('display','none');
            $(btn).parent().parent().find("select").next(".select2-container").show();
                   
        }
    }

    function draw_table(data_type,id,text,value,table_id){
       
        var rowCount = $(table_id).find("tr").length;
        var html ="";
        if(data_type !="episode_link"){
            html = '<tr class="tr_'+data_type+'">'+
                        '<td style="width: 50%;">'+
                            '<input type="hidden" class="form-control id_input"   value="'+id+'" readonly>'+
                            '<input type="text" class="form-control name_input"   value="'+text+'" readonly> '+
                            '<div  style="width:100%"><select class="form-control" id="'+data_type+'_select_'+(rowCount+1)+'"><option></option></select></div></td>'+
                        '<td style="width: 20%;"><input type="number"  class="form-control value_input" value="'+value+'" readonly></td>'+
                        '<td><a class="btn btn-sm btn-primary record_edit" style="color: white" data-type="'+data_type+'" onClick="record_edit(this)">Edit</a>&nbsp;'+
                        '<a class="btn btn-sm btn-primary record_delete" style="color: white" onClick="record_delete(this)">Delete</a></td>'+
                    '</tr>';
        }else{
            html = '<tr class="tr_'+data_type+'">'+
                        '<td style="width: 40%;">'+
                            '<input type="text"  class="form-control value_input" value="'+value+'" readonly>'+
                            '</td>'+
                        '<td style="width: 30%;">'+
                            '<input type="hidden" class="form-control id_input"   value="'+id+'" readonly>'+
                            '<input type="text" class="form-control name_input"   value="'+text+'" readonly> '+
                            '<div  style="width:100%"><select class="form-control" id="'+data_type+'_select_'+(rowCount+1)+'"><option></option></select></div>'+
                        '</td>'+
                        '<td><a class="btn btn-sm btn-primary record_edit" style="color: white" data-type="'+data_type+'" onClick="record_edit(this)">Edit</a>&nbsp;'+
                        '<a class="btn btn-sm btn-primary record_delete" style="color: white" onClick="record_delete(this)">Delete</a></td>'+
                    '</tr>';
        }
        

        $(table_id).append(html);

        var select_id = "#"+data_type+"_select_"+(rowCount+1);
        var option_data =[];
          
        switch (data_type) {
            case 'hero_item':
                option_data = item_option;
                break;
            case 'hero_skill':
                option_data = skill_option;
                break;
            case 'hero_codeword':
                option_data = codeword_option;
                break;
            case 'episode_item':
                option_data = item_option;
                break;
            case 'episode_skill':
                option_data = skill_option;
                break;
            case 'episode_codeword':
                option_data = codeword_option;
                break;
            case 'episode_link':
                option_data = episode_option;
                break;
        
        }
   
        $(select_id).select2({data:option_data, val:id});
        $(select_id).val(id).trigger('change');

        $(select_id).change(function(){
            var value = $(this).val();
            $(select_id).parent().parent().find('.id_input').val(value);
            $(select_id).parent().parent().find('.name_input').val($(select_id).find("option:selected").text());
        });
        $(select_id).next(".select2-container").hide();
    }

    $('.record_add').click(function()
    {
        var data_type = $(this).attr("data-type");
        var id = $(this).parent().parent().find('select').val();
        var value = $(this).parent().parent().find('input').val();
        var text =$(this).parent().parent().find('select option:selected').text();
    
        var table_id = "#"+data_type+"_table";

        if(value ==''){
            $(this).parent().parent().find('input').focus();
            return;
        }
        if(id ==null)
        {
            $(this).parent().parent().find('select').select2('open');
            return;
        }

        draw_table(data_type,id,text,value,table_id);
       
    })

    $(".modal_show").click(function()
    {
        modal_clean();
        get_item_option();
        get_skill_option();
        get_codeword_option();
        get_episode_option();
    });

    $("#hero_save").click(function()
    {
        var hero_id = $("#hero_id").val();
        var hero_name = $("#hero_name").val();
        var hero_slug = $("#hero_slug").val();
        var hero_image = $("[name='files[hero_img]']").val();
        var hero_description = tinymce.get("hero_description").getContent();
        
        if(hero_name =="")
        {
        
            error_alert('The name field is required.');
            return;
        }

        if(hero_description =="")
        {

            error_alert('The description field is required.');
            return;
        }
        
        swal({
            title: CI.langs['admin::admin.save.confirmation'],
            text: CI.langs['admin::admin.save.confirmation_message'],
            type: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : CI.langs['admin::admin.save.btn_cancel'],
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : CI.langs['admin::admin.save.btn_save'],
                    className : 'btn btn-success'
                }
            }
        }).then((willSave) => {
            if (willSave) {

                var hero_item_array = [];
                var hero_skill_array = [];
                var hero_codeword_array = [];

                $(".tr_hero_item").each(function(){
                    var id = $(this).find(".id_input").val();
                    var value = $(this).find(".value_input").val();
                    hero_item_array.push({'item_id': id, 'value': value});
                });
                $(".tr_hero_skill").each(function(){
                    var id = $(this).find(".id_input").val();
                    var value = $(this).find(".value_input").val();
                    hero_skill_array.push({'skill_id': id, 'value': value});
                });
                $(".tr_hero_codeword").each(function(){
                    var id = $(this).find(".id_input").val();
                    var value = $(this).find(".value_input").val();
                    hero_codeword_array.push({'codeword_id': id, 'value': value});
                });

                $.ajax({
                    url:"{{route('admin.ebooks.heroes_create')}}",
                    type: 'POST',  
                    data: { 
                            hero_id: hero_id,
                            hero_name:hero_name,
                            hero_slug:hero_slug,
                            hero_image:hero_image,
                            hero_description:hero_description,
                            item_json:JSON.stringify(hero_item_array),
                            skill_json:JSON.stringify(hero_skill_array),
                            codeword_json:JSON.stringify(hero_codeword_array) 
                    }, 
                    
                    success: function (msg) {   // success callback function
                        if(msg == 'success'){
                            success("Hero added successfully", 2000);
                            $("#heroes_create_modal").modal('hide');
                            $("#DataTables_Table_0").DataTable().draw();
                            modal_clean();
                        }
                    },
                });
            }
        });
        
    })

    $('#DataTables_Table_0').on('click','td:not(:first-child,.noclickable)',function() 
    {
        if(start_flag){
            start_flag = false;
            
        }else{
            return;
        }
        modal_clean();
        get_item_option();
        get_skill_option();
        get_codeword_option();

        var hero_id = $(this).parent().find(":checkbox").val();
        var img_src = $(this).parent().find("img").attr("src");
         $('#hero_id').val(hero_id);

        $.ajax({
            url:"{{route('admin.ebooks.hero_edit')}}",
            type: 'POST',  
            data: { hero_id: hero_id }, 
            dataType:'json',
            success: function (json) {   // success callback function

                $('#hero_name').val(json['hero_baseInfo']['hero_name']);
                $('#hero_slug').val(json['hero_baseInfo']['hero_slug']);
                tinymce.get("hero_description").setContent(json['hero_baseInfo']['hero_description']);
                var image = "";

                if((img_src=== undefined)||(img_src =="")){
                    image = '<div class="image-holder placeholder cursor-auto"><i class="fas fa-camera-retro"></i><input type="hidden" name="undefined"></div>';
                }else{
                    image = '<img src="'+img_src+'" data-nsfw-filter-status="sfw" style="visibility: visible;">'+
                            '<button type="button" class="btn remove-image"></button>'+
                            '<input type="hidden" name="files[hero_img]" value="'+json['hero_baseInfo']['hero_image']+'">';
                }
                
                $("#heroes_create_modal").find(".image-holder").html(image);
               
             
                for(var i =0; i<json['hero_ItemInfo'].length;i++){
                    var hero_ItemInfo = json['hero_ItemInfo'][i];
                    var data_type = 'hero_item';
                    var id        = hero_ItemInfo.item_id;
                    var text      = hero_ItemInfo.item_name;
                    var value     = hero_ItemInfo.value;
                    var table_id  = "#"+data_type+"_table";
                    draw_table(data_type,id,text,value,table_id);
                }
                for(var i =0; i<json['hero_SkillInfo'].length;i++){
                    var hero_SkillInfo = json['hero_SkillInfo'][i];
                    var data_type = 'hero_skill';
                    var id        = hero_SkillInfo.skill_id;
                    var text      = hero_SkillInfo.skill_name;
                    var value     = hero_SkillInfo.value;
                    var table_id  = "#"+data_type+"_table";
                    draw_table(data_type,id,text,value,table_id);
                }
                for(var i =0; i<json['hero_CodewordInfo'].length;i++){
                    var hero_CodewordInfo = json['hero_CodewordInfo'][i];
                    var data_type = 'hero_codeword';
                    var id        = hero_CodewordInfo.codeword_id;
                    var text      = hero_CodewordInfo.codeword_name;
                    var value     = hero_CodewordInfo.value;
                    var table_id  = "#"+data_type+"_table";
                    draw_table(data_type,id,text,value,table_id);
                }

           
                $("#heroes_create_modal").modal("show");
                
            },
        });
    });

    $("#episode_save").click(function()
    {
        var episode_id          = $("#episode_id").val();
        var episode_number      = $("#episode_number").val();
        var episode_description = tinymce.get("episode_description").getContent();

        var episode_odd_linkID    = $("#episode_odd_linkID").val();
        var episode_even_linkID   = $("#episode_even_linkID").val();
        var episode_odd_linkText    = $("#episode_odd_linkText").val();
        var episode_even_linkText   = $("#episode_even_linkText").val();
        
        var episode_death       = $("#death_episode").is(':checked')? 1 : 0;
        var episode_with_fight  = $("#episode_with_fight").is(':checked')? 1 : 0;
        var episode_dice        = $("#dice").is(':checked')? 1 : 0;
        var episode_final       = $("#final_episode").is(':checked')? 1 : 0;
        var episode_is_first    = $("#is_first").is(':checked')? 1 : 0;
        if(episode_number =="")
        {
            error_alert('The number field is required.');
            return;
        }

        if(episode_description =="")
        {
            error_alert('The description field is required.');
            return;
        }
        if(episode_dice){
            if((episode_even_linkID=="")||(episode_odd_linkID==""))
            {
                error_alert('The link fields are required.');
                return;
            }
        }
        swal({
            title: CI.langs['admin::admin.save.confirmation'],
            text: CI.langs['admin::admin.save.confirmation_message'],
            type: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : CI.langs['admin::admin.save.btn_cancel'],
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : CI.langs['admin::admin.save.btn_save'],
                    className : 'btn btn-success'
                }
            }
        }).then((willSave) => {
            if (willSave) {
                var episode_item_array = [];
                var episode_skill_array = [];
                var episode_codeword_array = [];
                var episode_nextlink_array = [];
 
                 $(".tr_episode_item").each(function(){
                    var id = $(this).find(".id_input").val();
                    var value = $(this).find(".value_input").val();
                    episode_item_array.push({'item_id': id, 'value': value});
                });
                $(".tr_episode_skill").each(function(){
                    var id = $(this).find(".id_input").val();
                    var value = $(this).find(".value_input").val();
                    episode_skill_array.push({'skill_id': id, 'value': value});
                });
                $(".tr_episode_codeword").each(function(){
                    var id = $(this).find(".id_input").val();
                    var value = $(this).find(".value_input").val();
                    episode_codeword_array.push({'codeword_id': id, 'value': value});
                });

                if(episode_dice){
                    episode_nextlink_array.push({'episode_id': episode_even_linkID, 'text': episode_even_linkText,'is_even':1},
                                                {'episode_id': episode_odd_linkID, 'text': episode_odd_linkText,'is_even':0});
                }else{
                    $(".tr_episode_link").each(function(){
                        var id = $(this).find(".id_input").val();
                        var value = $(this).find(".value_input").val();
                        episode_nextlink_array.push({'episode_id': id, 'text': value});
                    });
                }
                
               

                $.ajax({
                    url:"{{route('admin.ebooks.episodes_create')}}",
                    type: 'POST',  
                    data: { 
                            episode_id: episode_id,
                            episode_number:episode_number,
                            episode_description:episode_description,
                            episode_death:episode_death,
                            episode_with_fight:episode_with_fight,
                            episode_dice:episode_dice,
                            episode_final:episode_final,
                            episode_is_first:episode_is_first,

                            item_json:JSON.stringify(episode_item_array),
                            skill_json:JSON.stringify(episode_skill_array),
                            codeword_json:JSON.stringify(episode_codeword_array),
                            nextlink_json:JSON.stringify(episode_nextlink_array) 
                    }, 
                    
                    success: function (msg) {   // success callback function
                        if(msg == 'success'){
                            success("Episode added successfully", 2000);
                            $("#episodes_create_modal").modal('hide');
                            $("#DataTables_Table_4").DataTable().draw();
                            modal_clean();
                        }
                    },
                });
            }
        });
        
    })

    $('#DataTables_Table_4').on('click','td:not(:first-child,.noclickable)',function() 
    {
        if(start_flag){
            start_flag = false;
            
        }else{
            return;
        }
        modal_clean();
        get_item_option();
        get_skill_option();
        get_codeword_option();
        get_episode_option();

        var episode_id = $(this).parent().find(":checkbox").val();

        $('#episode_id').val(episode_id);
        $("#death_episode").prop('checked',0);
        
        $.ajax({
            url:"{{route('admin.ebooks.episode_edit')}}",
            type: 'POST',  
            data: {  episode_id: episode_id }, 
            dataType:'json',
            success: function (json) {   // success callback function

                $('#episode_number').val(json['episode_baseInfo']['number']);
                tinymce.get("episode_description").setContent(json['episode_baseInfo']['description']);
                
                $("#death_episode").prop('checked',json['episode_baseInfo']['has_death']);
                $("#episode_with_fight").prop('checked',json['episode_baseInfo']['has_fight']);
                $("#dice").prop('checked',json['episode_baseInfo']['has_dice']);
                $("#final_episode").prop('checked',json['episode_baseInfo']['is_last']);
                $("#is_first").prop('checked',json['episode_baseInfo']['is_first']);
             
                for(var i =0; i<json['episode_ItemInfo'].length;i++){
                    var episode_ItemInfo = json['episode_ItemInfo'][i];
                    var data_type = 'episode_item';
                    var id        = episode_ItemInfo.item_id;
                    var text      = episode_ItemInfo.item_name;
                    var value     = episode_ItemInfo.value;
                    var table_id  = "#"+data_type+"_table";
                    draw_table(data_type,id,text,value,table_id);
                }
                for(var i =0; i<json['episode_SkillInfo'].length;i++){
                    var episode_SkillInfo = json['episode_SkillInfo'][i];
                    var data_type = 'episode_skill';
                    var id        = episode_SkillInfo.skill_id;
                    var text      = episode_SkillInfo.skill_name;
                    var value     = episode_SkillInfo.value;
                    var table_id  = "#"+data_type+"_table";
                    draw_table(data_type,id,text,value,table_id);
                }
                for(var i =0; i<json['episode_CodewordInfo'].length;i++){
                    var episode_CodewordInfo = json['episode_CodewordInfo'][i];
                    var data_type = 'episode_codeword';
                    var id        = episode_CodewordInfo.codeword_id;
                    var text      = episode_CodewordInfo.codeword_name;
                    var value     = episode_CodewordInfo.value;
                    var table_id  = "#"+data_type+"_table";
                    draw_table(data_type,id,text,value,table_id);
                }
                if(json['episode_baseInfo']['has_dice']){
                    if(json['episode_nextLinkInfo'].length !=0){
                        $("#episode_even_linkText").val(json['episode_nextLinkInfo'][0].text);
                        $("#episode_even_linkID").val(json['episode_nextLinkInfo'][0].next_episode_id).trigger('change');
                        $("#episode_odd_linkText").val(json['episode_nextLinkInfo'][1].text);
                        $("#episode_odd_linkID").val(json['episode_nextLinkInfo'][1].next_episode_id).trigger('change');
                    }
                    
                }else{
                    for(var i =0; i<json['episode_nextLinkInfo'].length;i++){
                        var episode_nextLinkInfo = json['episode_nextLinkInfo'][i];
                        var data_type = 'episode_link';
                        var id        = episode_nextLinkInfo.next_episode_id;
                        var text      = episode_nextLinkInfo.number;
                        var value     = episode_nextLinkInfo.text;
                        var table_id  = "#"+data_type+"_table";
                        draw_table(data_type,id,text,value,table_id);
                    }                   
                }

                check_dice();
                $("#episodes_create_modal").modal("show");
               
            },
        });

       
    });

    $("#item_save").click(function(){
        var item_id   = $("#item_id").val();
        var item_name = $("#item_name").val();
        var item_slug = $("#item_slug").val();
        var item_description = tinymce.get("item_description").getContent();
        var item_image = $("[name='files[item_img]']").val();
        
        if(item_name =="")
        {
            error_alert('The name field is required.');
            return;
        }

        if(item_description =="")
        {
            error_alert('The description field is required.');
            return;
        }

        swal({
            title: CI.langs['admin::admin.save.confirmation'],
            text: CI.langs['admin::admin.save.confirmation_message'],
            type: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : CI.langs['admin::admin.save.btn_cancel'],
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : CI.langs['admin::admin.save.btn_save'],
                    className : 'btn btn-success'
                }
            }
        }).then((willSave) => {
            if (willSave) {
              
                $.ajax({
                    url:"{{route('admin.ebooks.items_create')}}",
                    type: 'POST',  
                    data: { 
                            item_id:item_id,
                            item_name: item_name,
                            item_slug:item_slug,
                            item_image:item_image,
                            item_description:item_description }, 
                    
                    success: function (msg) {   // success callback function
                        if(msg == 'success'){
                            success("Item added successfully", 2000);
                            $("#items_create_modal").modal('hide');
                            $("#DataTables_Table_1").DataTable().draw();
                        }
                    },
                        
                });
            }
        });
    })

    $('#DataTables_Table_1').on('click','td:not(:first-child,.noclickable)',function() 
    {
        if(start_flag){
            start_flag = false;
            
        }else{
            return;
        }
        modal_clean();
  
        var item_id = $(this).parent().find(":checkbox").val();
        var item_name = $(this).parent().find("td:eq(2)").html();
        var item_slug = $(this).parent().find("td:eq(3)").html();
        var item_description = $(this).parent().find("td:eq(4)").html();
        var item_image_id = $(this).parent().find("td:eq(5)").html();
        var img_src = $(this).parent().find("img").attr("src");
       
        $('#item_id').val(item_id);
        $('#item_name').val(item_name);
        $('#item_slug').val(item_slug);

        tinymce.get("item_description").setContent(item_description);

        var image = "";

        if((img_src=== undefined)||(img_src =="")){
            image = '<div class="image-holder placeholder cursor-auto"><i class="fas fa-camera-retro"></i><input type="hidden" name="undefined"></div>';
        }else{
            image = '<img src="'+img_src+'" data-nsfw-filter-status="sfw" style="visibility: visible;">'+
                    '<button type="button" class="btn remove-image"></button>'+
                    '<input type="hidden" name="files[item_img]" value="'+parseInt(item_image_id)+'">';
        }
        
        $("#items_create_modal").find(".image-holder").html(image);

        $("#items_create_modal").modal("show");

       
    });

    $("#skill_save").click(function(){
        var skill_id = $("#skill_id").val();
        var skill_name = $("#skill_name").val();
        var skill_slug = $("#skill_slug").val();
        var skill_description = tinymce.get("skill_description").getContent();
        if(skill_name =="")
        {
        
            error_alert('The name field is required.');
            return;
        }

        if(skill_description =="")
        {

            error_alert('The description field is required.');
            return;
        }
        swal({
            title: CI.langs['admin::admin.save.confirmation'],
            text: CI.langs['admin::admin.save.confirmation_message'],
            type: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : CI.langs['admin::admin.save.btn_cancel'],
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : CI.langs['admin::admin.save.btn_save'],
                    className : 'btn btn-success'
                }
            }
        }).then((willSave) => {
            if (willSave) {
                                
                $.ajax({
                    url:"{{route('admin.ebooks.skills_create')}}",
                    type: 'POST',  
                    data: { 
                            skill_id:skill_id,
                            skill_name: skill_name,
                            skill_slug:skill_slug,
                            skill_description:skill_description
                        }, 
                    
                    success: function (msg) {   // success callback function
                        if(msg == 'success'){
                            success("Skill added successfully", 2000);
                            $("#skills_create_modal").modal('hide');
                            $("#DataTables_Table_2").DataTable().draw();
                        }
                    },
                    
                });
            }
        });
    })

    $('#DataTables_Table_2').on('click','td:not(:first-child,.noclickable)',function() 
    {
        if(start_flag){
            start_flag = false;
            
        }else{
            return;
        }
        modal_clean();
  
        var skill_id = $(this).parent().find(":checkbox").val();
        var skill_name = $(this).parent().find("td:eq(1)").html();
        var skill_slug = $(this).parent().find("td:eq(2)").html();
        var skill_description = $(this).parent().find("td:eq(3)").html();
       
        $('#skill_id').val(skill_id);
        $('#skill_name').val(skill_name);
        $('#skill_slug').val(skill_slug);

        tinymce.get("skill_description").setContent(skill_description);

        $("#skills_create_modal").modal("show");
      
    });

    $("#codeword_save").click(function(){
        var codeword_id   = $("#codeword_id").val();
        var codeword_name = $("#codeword_name").val();
        var codeword_slug = $("#codeword_slug").val();
        if(codeword_name =="")
        {
        
            error_alert('The name field is required.');
            return;
        }

        swal({
            title: CI.langs['admin::admin.save.confirmation'],
            text: CI.langs['admin::admin.save.confirmation_message'],
            type: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : CI.langs['admin::admin.save.btn_cancel'],
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : CI.langs['admin::admin.save.btn_save'],
                    className : 'btn btn-success'
                }
            }
        }).then((willSave) => {
            if (willSave) {
                
                $.ajax({
                    url:"{{route('admin.ebooks.codewords_create')}}",
                    type: 'POST',  
                    data: { 
                            codeword_id:codeword_id,
                            codeword_name: codeword_name,
                            codeword_slug:codeword_slug }, 
                    
                    
                    success: function (msg) {   // success callback function
                        if(msg == 'success'){
                            success("Codeword added successfully", 2000);
                            $("#codewords_create_modal").modal('hide');
                            $("#DataTables_Table_3").DataTable().draw();
                        }
                    },
                });
            }
        });
    })

    $('#DataTables_Table_3').on('click','td:not(:first-child,.noclickable)',function() 
    {
        if(start_flag){
            start_flag = false;
            
        }else{
            return;
        }
        modal_clean();
  
        var codeword_id = $(this).parent().find(":checkbox").val();
        var codeword_name = $(this).parent().find("td:eq(1)").html();
        var codeword_slug = $(this).parent().find("td:eq(2)").html();
       
        $('#codeword_id').val(codeword_id);
        $('#codeword_name').val(codeword_name);
        $('#codeword_slug').val(codeword_slug);
        $("#codewords_create_modal").modal("show");

       
    });

    $(".modal").on("hidden.bs.modal", function () {
        
        start_flag = true;
        
    });

    $("#dice").click(function(){
        check_dice();
    })
    
    function error_alert(msg){
        swal({
            title: msg,
            text: CI.langs['admin::admin.save.confirmation_message'],
            type: 'warning',
            buttons:{
                confirm: {
                    text : " OK ",
                    className : 'btn btn-success'
                }
            }
        })
    }
    
    function check_dice(){
       var flage = $("#dice").is(':checked')? 1 : 0;
       if(flage){
           $(".dice_uncheck").css('display','none');
           $(".dice_check").css('display','flex');
       }else{
           $(".dice_uncheck").css('display','flex');
           $(".dice_check").css('display','none');
       }
    }
</script>
@endpush