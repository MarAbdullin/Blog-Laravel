$(document).ready(function () {
    
    //preview загружаемой картинки
    $('#img').on('click', function(){
        $('.image').append('<img id="img-preview" src=""/>');
        
        //отображение preview
        $(document).on('change', '#img', function(){
            let input = $(this)[0];
            if(input.files && input.files[0]){
                if(input.files[0].type.match('image.*')){
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $('#img-preview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    console.log('Загружаемый файл не картинка');
                }
            
            } else {
                console.log('Файл не загрузился');
            }
    });
    });

    //закрытие preview
    $('.image').on('click', function(){
        $('#img').val('');
        $('.image').empty();
    });


});

