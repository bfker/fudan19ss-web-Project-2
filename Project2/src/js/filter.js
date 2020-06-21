function getSelectVal(){
    var countryCodeISO = $("#country").val();

    if(countryCodeISO === 0) return;
    else {
        var url = 'filter.php';
        $.ajax({
            url: url,
            type: 'post',
            data:{countryCodeISO,countryCodeISO},
            dataType:'json',
            success: function (res) {
                var city = $("#city");
                $("option",city).remove();
                console.log(res.code);
                if(res.code == 200) {
                    city.append(" <option value=\"0\">Filter by City</option>");
                    var select = res.select;
                    for(var i = 0; i < select.length; i++) {
                        var option = select[i];
                        console.log(option);
                        city.append(option);
                    }
                }
                else city.append(" <option value=\"0\">Filter by City</option>");
            }
        })
    }
}
