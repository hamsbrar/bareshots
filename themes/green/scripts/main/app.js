// app related funcs goes here
function execute(function_name) {
    window[function_name]();
}

function load_menu_data(data) {

    $("#main-header-items").hide();

    // draw main menu first
    Object.keys(data.items).forEach(function(key) {

        let html = '';
        let mobile_menu_html = '';

        if(!data.items[key]) {

            html = '<div id="menu_item_'+key+'" class="main-header-item header-item-hover b1 h3" onclick="load_page(\''+key+'\', \'\');">'+key+'</div>';

            mobile_menu_html = '<div class="mobile-nav-item-centered" onclick="$(\'#app_mobile_navigation\').slideUp(200);load_page(\''+key+'\', \'\');">'+key+'</div>';

            $("#main-header-items").append(html);

        } else {

            mobile_menu_html = '<div class="mobile-nav-item-centered" onclick="$(\'#app_mobile_navigation\').slideUp(200);load_page(\''+key+'\', \'\');">'+key+'</div>';

            html = `
            <div id="menu_item_`+key+`" class="main-header-item header-item-hover b1 h3 header-item-dropdown">
                `+key+` <span class="material-icons" style="line-height: 0.7;position: absolute;">expand_more</span>
                <div id="drop_content_`+key+`"class="header-item-dropdown-content">
                    
                </div>
            </div>
            `;

            $("#main-header-items").append(html);

            Object.keys(data.items[key].items).forEach(function(inside_key) {

                let inside_html = '';
        
                inside_html = '<div id="sub_menu_item_'+key+'_'+inside_key+'" class="sub-header-item header-item-hover b1 h2" onclick="load_page(\''+key+'\', \''+inside_key+'\')">'+inside_key+'</div>';

                $("#drop_content_"+key).append(inside_html);
            });
        }

        $("#app_mobile_navigation").append(mobile_menu_html)

    });

    $("#main-header-items").fadeIn();
}

function load_social_data(data) {
    
    $("#app_mobile_navigation").append('<div id="social_media_btns_holder" class="mobile-nav-item-centered" onclick="$(\'#app_mobile_navigation\').slideUp(200);"></div>')

    Object.keys(data).forEach(function(key) {

        if(data[key]) {
            $("#main-header-items").append('<a href="'+data[key]+'" target="_blank" class="main-header-item header-item-hover" style="margin:10px 0px 10px 10px!important;padding:15px 5px 15px 5px!important;"><img style="width:14px" src="'+graphics_url+'/social/'+js_header_icons+'/'+key+'.png'+'"></img></a>');

            $("#social_media_btns_holder").append('<a href="'+data[key]+'" target="_blank" class="main-header-item header-item-hover" style="float:none!important"><img style="width:16px" src="'+graphics_url+'/social/filled/'+key+'.png'+'"></img></a>')

            $("#footer_social_btns").append('<a href="'+data[key]+'" target="_blank" style="padding:0px 5px"><img style="width:12px" src="'+graphics_url+'/social/'+js_footer_icons+'/'+key+'.png'+'"></img></a>')
        }
    });

    $("#app_mobile_navigation").append('<div class="mobile-nav-item-centered" onclick="$(\'#app_mobile_navigation\').slideUp(200);">X</div>')
}

function load_footer_data(data) {
    $("#footer_reserved_msg").html('<div class="h1 b1 slim">'+data.rights_reserved_messge+'</div>');
    $("#footer_developer_link").html('<a target="_blank" class="h1 b1 slim" href="'+data.developer_link+'">'+data.developer_message+'</a>');
}

function activate_sub_menu(item) {
    $("#sub-menu-items").data('parent-el', item);
}

function load_page(route_1, route_2) {

    console.log(route_1, route_2);

    let history = '/' + route_1;

    if(route_2.trim().length > 0)

    history = history + '/' + route_2;

    sys_history(history);

    $(".main-header-item, .sub-header-item").removeClass('active');
    $("#menu_item_" + route_1).addClass('active');
    $("#sub_menu_item_" + route_1 + '_' + route_2).addClass('active');

    $.ajax(
    {
        type: 'POST',

        url: url + '/ajax/page',
    
        dataType: 'JSON',

        data: {route_1: route_1, route_2: route_2},

        cache: false,

        beforeSend: function ()
        {
            sys_beforeSend();
            
            $("#app_content").html('<div class="full" style="min-height:200px;positon:relative;"><div class="display-middle"><span class="preloader running big"><span></span></span></div></div>')
        },

        success: function (response)
        {
            sys_handle(response, $('#app_content'));
        },

        complete: function (xxhr, status)
        {
            sys_handlexxhr(xxhr, status);
        }
    });
}
