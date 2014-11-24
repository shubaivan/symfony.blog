$(document).on('submit', '#comment_add', function(e){
    e.preventDefault();

    var data = $(this).serialize();

    $(this).find('input[type=text]').val("");
    $(this).find('textarea').val("");

    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: data
    }).done(function(data){
        data = JSON.parse(data);

        switch(data.code){
            case 200: {
                html = "";
                for(var i = data.data.length - 1; i >= 0 ; i--){
                    html += "<li>" +
                                "<div>" +
                                    "<div>" + data.data[i].author +  "</div>" +
                                    "<div>" + data.data[i].text +  "</div>" +
                                    "<div>" + data.data[i].createdAt +  "</div>" +
                                "</div>" +
                            "</li>";
                }
            }
                break;
            case 404: {
                html = "<li>Сталась якась помилка</li>";
            }
        }

        $('#category_list').html(html);
    });
});