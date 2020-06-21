function checkRadio(radio) {
    console.log(radio.value);
    if(radio.value == 'title')  {
        $('#text').removeAttr('disabled');
        $("#textarea").attr("disabled","disabled");
    } else {
        $("#text").attr("disabled","disabled");
        $('#textarea').removeAttr('disabled');
    }
}