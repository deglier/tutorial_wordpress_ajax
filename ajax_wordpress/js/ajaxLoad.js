// habilita o uso do $
jQuery(function($){

    // variaveis
    var page = 1;
    var loading = true;
    var $window = $(window);
    var $content = $('#content');

    // funcção que busca os posts
    var load_posts = function() {
        $.ajax({
            type: "GET",
            data: {action: 'load_posts', numPosts: 8, page: page},
            dataType: "html",
            url: './wp-admin/admin-ajax.php',
            beforeSend: function() {
                $content.append('<div id="temp_load" style="text-align: center"><img src="./wp-content/themes/ajax_wordpress/images/ajax-loader.gif"></div>');
            },
            success: function(data) {
                // transforma a data em objeto
                var $data = $(data);
                // verifica se existe dados
                if($data.length) {
                    // oculta o conteudo
                    $data.hide();

                    // insere no conteudo a data
                    $content.append($data);

                    // da o fadeIn no conteudo e oculta a div #temp_load
                    $data.fadeIn(500, function(){
                        $('#temp_load').remove();
                        loading = false;
                    })
                } else {
                    $('#temp_load').remove();
                }
            }
        });
    }

    $window.scroll(function(){
        // armazena a posição do nosso conteudo
        var content_offeset = $content.offset();

        if(!loading && ($window.scrollTop() + $window.height()) > ($content.scrollTop() + content_offeset.top)) {
            loading = true;
            page++;
            load_posts();
        }
    });

    // carrega o post
    load_posts();
});