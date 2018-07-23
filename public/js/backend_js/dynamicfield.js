    $(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="field_wrapper" style="margin-left:180px"><input type="text" name="sku[]" id="sku" placeholder="SKU" style="width:120px; margin-right:3px; margin-top:2px;"/><input type="text" name="size[]" id="size" placeholder="Size" style="width:120px; margin-right:3px; margin-top:2px;"/><input type="text" name="price[]" id="price" placeholder="Price" style="width:120px; margin-right:3px; margin-top:2px;"/><input type="text" name="stock[]" id="stock" placeholder="Stock" style="width:120px; margin-right:3px; margin-top:2px;"/><a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
	});

    $(".deleteRecord").click(function(){
        var id=$(this).attr('rel');
        var deleteFunction=$(this).attr('rell');
        swal({

            title:"Are you sure ?",
            text:"You won't be able to revert this!",
            type:"waring",
            showCancelButton:true,
            confirmButtonColor:'#3085d6',
            cancelButtonColor:'#d33',
            confirmButtonText:'Yes, delete it!',
            cancelButtontext:'No, cancel!',
            confirmButtonClass:'btn btn-success',
            cancelButtonClass:'btn btn-danger',
            buttonStyling:false,
            reverseButton:true
        },
        function(){
            window.location.href="/admin/"+deleteFunction+"/"+id;
        });
    });
        