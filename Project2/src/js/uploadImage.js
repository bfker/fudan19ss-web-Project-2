function uploadImg() {
    console.log("hello");
    var formData = new FormData($('form')[0]);
    formData.append('file',$(':file')[0].files[0]);
    $.ajax({
        url:'uploadImage.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success:function(data){
            console.log(data)
            var srcPath = data;
            console.log();
            $('.picDis img').attr('src', '..'+srcPath);
        }
    })
}

function changePicture() {
    var reader = new FileReader();
    f = document.getElementById('file').files[0];
    reader.readAsDataURL(f);
    reader.onload = function(e) {
        $("#upload-box").css("display", "none");
        document.getElementById('img').src = this.result;
        $("#img").css("display", "block");
    };
}
