( function() {
    // Blog Category Filter
    $( '#selected-category' ).change( function(e) {
        e.preventDefault();
        var selected_cat = $( '#selected-category' ).val();
        let ppr = $( '#bpn_data_values' ).attr( 'data-ppr' );
        if( selected_cat == 0 ){
            $( '.recent-article .recent-cat-name' ).html( 'All' );
        }else{
            $( '.recent-article .recent-cat-name' ).html( selected_cat );
        }

        $( '#blog-section .main-blog-content' ).css( 'opacity', '0.3' );
        $( '#blog-section .spinner' ).css( 'display', 'block' );
        $.ajax({
            url: bpn_ajax_obj.ajaxurl,
            cache: false,
            type: 'POST',
            dataType : "json",
            data: {
                action: 'bpn_filter',
                selected_cat: selected_cat,
                posts_per_page: ppr,
                async: false,
            },
        })
        .done( function( response ) {
            console.log(response);
            $( '#blog-section .main-blog-content' ).css( 'opacity', '1' );
            $( '#blog-section .spinner' ).css( 'display', 'none' );
            if ( 'success' == response.message ) {
                $( '#blog-section .main-blog-content' ).html( response.html );
            }
        })
        .fail(function() {
        })
        e.stopImmediatePropagation();
    });

    // Pagination filter
    $( document ).on( "click", "#blog-section .pagination a", function( e ){
        e.preventDefault();
        let ppr = $( '#bpn_data_values' ).attr( 'data-ppr' );
        var pagination = $( this ).attr( 'href' );

        // Getting page current number
        var regExp = /\/page\/([0-9]+)/;
        var matches = regExp.exec( pagination );

        if( $( '#selected-category' ).val() ){
            var selected_cat = $( '#selected-category' ).val();
            $("html,body").animate({scrollTop:470},1000);
        }else{
            var selected_cat = $( '#hiden-cat-slug' ).attr( 'cat-slug' );
            $("html,body").animate({scrollTop:160},1000);
        }

        $( '#blog-section .main-blog-content ').css( 'opacity', '0.3' );
        $( '#blog-section .spinner' ).css( 'display', 'block' );
        $.ajax({
            url: bpn_ajax_obj.ajaxurl,
            cache: false,
            type: 'POST',
            dataType : "json",
            data: {
                action: 'bpn_filter',
                selected_cat: selected_cat,
                selected_paged: matches[1],
                posts_per_page: ppr,
                async: false,
            },
        })
        .done( function( response ) {
            $( '#blog-section .main-blog-content' ).css( 'opacity', '1' );
            $( '#blog-section .spinner' ).css( 'display', 'none' );
            $( '#blog-section .main-blog-content' ).html( response.html );
        })
        .fail(function() {
        })
        e.stopImmediatePropagation();
    });
}($) );
