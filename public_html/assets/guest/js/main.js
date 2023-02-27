let loading = {
    aInternal: 10,
    aListener: function(val) {},
    set value(val) {
      this.aInternal = val;
      this.aListener(val);
    },
    get value() {
      return this.aInternal;
    },
    registerListener: function(listener) {
      this.aListener = listener;
    }
}
loading.registerListener(function(val) {
    // console.log("loading.value to " + val);
    if(loading.value) $('.wploading').removeClass('hidden');
    else $('.wploading').addClass('hidden');
});
var nav_def = {
    title: '',
    canBack: false,
    canSearch: false,
    canRefesh: false,
};
var token = null;
var user = null;
var settings = null;
var varibles = {
    padding_bottom: 70,
    last_item_click: null,
    
};
var current_tab = 'home';
var current_tabChild = {
    home: {
        child: 'dscoso',
        dscoso_ids: [],
        dscoso_page: 0,
        dscoso_maxpage: false,
        dscoso_tencoso: '',
        dscoso_diachi_id: 0,
        dscoso_loadmore: true,
    },
    map: {
        child: 'map',
    },
    news: {
        child: 'listnews',
    },
    spin: {
        child: 'listspin',
    },
    account: {
        child: 'accountnotlogin',
    }
};
var current_action = '';
var last_res = null; // last response query get data
var vargoobal = {
    home: {
        method : 'POST', // POST or GET for api main
        loaded: false, // loaded or not
        curent_page: 0, // number page
        max_page: 0, // number max page
        empty: false, // empty div before append html
        saveRes: true, // save response after get data
        saveLocal: false, // save to localstorage after get data
        appendHtml: false, // append html after return, not type Json
        history: [{
            nav : {
                canSearch: true,
                title: 'Massage',
            },
            div: '#home',
        }],
        canSearch: true,
    },
    getsetting: {
        method : 'GET',
        loaded: false,
    },
    news: {
        method : 'POST',
        loaded: false,
        history: [{
            nav : {
                canRefesh: false,
            },
            div: '#listnews',
        }],
        canRefesh: false,
    },
    spin: {
        method : 'POST',
        loaded: false,
        history: [{
            nav : {
                canRefesh: true,
            },
            div: '#getvongquay',
        }],
        canRefesh: true,
    },
    account: {
        method : 'POST',
        loaded: false,
        history: [{
            nav : {
                canRefesh: true,
            },
            div: '#accountlogin',
        }],
        canRefesh: false,
    },
}
// name + action 

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();  
    $('.autoopen').modal('show');
    token = localStorage.getItem('token');
    settings = localStorage.getItem('settings');
    if(settings){
        settings = JSON.parse(settings);
    }
    user = localStorage.getItem('user');
    if(user){
        user = JSON.parse(user);
    }
    $('#stars li').on('mouseover', function(){
        var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
       
        // Now highlight all the stars that's not after the current hovered star
        $(this).parent().children('li.star').each(function(e){
        if (e < onStar) {
            $(this).addClass('hover');
        }
        else {
            $(this).removeClass('hover');
        }
        });
        
    }).on('mouseout', function(){
        $(this).parent().children('li.star').each(function(e){
        $(this).removeClass('hover');
        });
    });
    /* 2. Action to perform on click */
    $('#stars li').on('click', function(){
        var onStar = parseInt($(this).data('value'), 10); // The star currently selected
        var stars = $(this).parent().children('li.star');
        
        for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass('selected');
        }
        
        for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass('selected');
        }
        
        // JUST RESPONSE (Not needed)
        var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
        var msg = "";
        if (ratingValue > 1) {
            msg = "Cảm ơn bạn, bạn đã đánh giá " + ratingValue + " sao.";
        }
        else {
            msg = "Chúng tôi sẽ cải thiện dịch vụ, bạn đã đánh giá " + ratingValue + " sao.";
        }
        $('.stardgcode').val(ratingValue);
        // responseMessage(msg);
    });
    getData('home',{saveRes: 1, page: 1});
    $('body').on('click', '.refreshbtn', function(){
        getData(vargoobal[current_tab]);
    });
    
    var heightif = 0;
    window.addEventListener('message', function (e) {
        // console.log('addEventListener message',e.data);
        loading.value = false;
        if(e && IsJsonString(e.data)){
            const data = JSON.parse(e.data);
            if(data.heightspin) {
                const height = data.heightspin ? data.heightspin : 0;
                if(heightif != height) heightif = height;
                // $('#iframespin').css('height', heightif+'px');
            }else if(data.status){
                last_res = data;
                doAction(null, {nameaction: 'single_spin_back'})
            }
            
        } else if(e.data == 'scrollafterchoolse') {
            // console.log('scrollafterchoolse');
        }
    });

    $('footer').on('click', 'a', function(e){
        let title = $(this).data('title');
        let tab = $(this).data('tab');
        let loaded = vargoobal[tab] && vargoobal[tab].loaded ? true : false;
        
        if(current_tab != tab){
            
            // console.log(current_tab);
            if(!vargoobal[tab]){
                vargoobal[tab] = {};
                vargoobal[tab].method = 'POST';
                vargoobal[tab].loaded = false;
                vargoobal[tab] = {
                    ...vargoobal[tab],
                    ...nav_def,
                };
            }
            if(!vargoobal[tab].title) vargoobal[tab].title = title;
            let his = vargoobal[tab];
            $('footer li').removeClass('active');
            $(this).parents('li').addClass('active');
            $('.tabcontent').removeClass('active');
            $('.tabcontent[data-tab="'+tab+'"]').addClass('active');
            let child = $('.tabcontent[data-tab="'+tab+'"] .tabchild.active').data('nametab');
            if(!current_tabChild[tab]) current_tabChild[tab] = {};
            if(!current_tabChild[tab].child) current_tabChild[tab].child = child;
            
            let canSearch = !loaded ? $(this).data('canSearch') : vargoobal[tab].canSearch;
            let canBack = !loaded ? $(this).data('canBack') : vargoobal[tab].canBack;
            let canRefesh = !loaded ? $(this).data('canRefesh') : vargoobal[tab].canRefesh;
            let div = $('.tabcontent[data-tab="home"] .tabchild.main').attr('id');
            
            if(canBack) $('.backbtn').removeClass('hidden'); else $('.backbtn').addClass('hidden');
            if(canRefesh) $('.refreshbtn').removeClass('hidden'); else $('.refreshbtn').addClass('hidden');
            if(canSearch) $('.searchbtn').removeClass('hidden'); else $('.searchbtn').addClass('hidden');
            if(title) $('h1').text(title);

            // if(current_tab == 'spin') {
            //     $('#iframespin').attr('src','https://spin.netsa.vn/view-spin/n68n4lv59c');
            //     console.log('iframespin');
            // }
            vargoobal[tab].loaded = true;
            console.log('footer', loaded, canBack, current_tab, tab);
            current_tab = tab;
        }

        if($(this).hasClass('action') && !loaded) doAction($(this));
        return false;
    });

    // account
    $('body').on('click','.change_gr',function(){
        let gr = $(this).data('gr');
        change_gr('#' + gr);
        return false;
    });

    // action
    $('body').on('click','.action',async function(e){
        doAction($(this));
        return false;
    });
    $(window).scroll(function(){
        var top = $(this).scrollTop() + window.innerHeight - 55;
        let namechild = current_tabChild[current_tab].child;
        let hdiv = $('.loadmore').offset().top;
        if (!loading.value && !current_tabChild[current_tab][namechild + '_maxpage'] && current_tabChild[current_tab][namechild + '_loadmore']) {
            // console.log(top, hdiv);
            if (top >= hdiv) {
                let name = 'morehome';
                let body = {}
                if(!current_tabChild[current_tab][namechild + '_page']) current_tabChild[current_tab][namechild + '_page'] = 1;
                if(current_tab == 'home'){
                    name = 'morehome';
                    body = {
                        id: getIds(),
                        tencoso: '',
                        diachi_id: 0,
                        page: current_tabChild[current_tab][namechild + '_page'] + 1,
                        loadmore: true
                    };
                } else if (current_tab == 'news'){
                    name = 'getnews';
                    body = {
                        id: getIds(),
                        page: current_tabChild[current_tab][namechild + '_page'] + 1,
                        loadmore: true
                    };
                } else if (current_tab == 'spin'){
                    name = 'getvongquay';
                    body = {
                        id: getIds(),
                        page: current_tabChild[current_tab][namechild + '_page'] + 1,
                        loadmore: true
                    };
                } 
                getData(name, body, {}).then((res) => {
                    // console.log('morehome', res.cosos);
                    if(res.status == 'success') {
                        loadscript(name);
                    } else {
                        console.log('error more');
                        alert(res.msg ? res.msg : 'Lấy thông tin thất bại')
                    }
                });
            }
        }
    });
    jQuery(function ($) {
        $.datepicker.regional["vi-VN"] =
        {
            closeText: "Đóng",
            prevText: "Trước",
            nextText: "Sau",
            currentText: "Hôm nay",
            monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
            monthNamesShort: ["Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
            dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
            dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
            dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
            weekHeader: "Tuần",
            dateFormat: "dd/mm/yy",
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ""
        };

        $.datepicker.setDefaults($.datepicker.regional["vi-VN"]);
    });

    $('.date').datepicker($.extend({}, $.datepicker.regional['vn'], {
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    }));
});

function isEmail(email) {
    var re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function isPhonenumber(inputtxt) {
    var phoneno = /^\d{10}$/;
    if (inputtxt.match(phoneno)) {
      return true;
    } else {
      return false;
    }
}
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
function isObject(val) {
    if (val === null) { return false;}
    return ( (typeof val === 'function') || (typeof val === 'object') );
}
function isString(str){
    if (typeof str === 'string' || str instanceof String) return true;
    return false;
}
function isArray(arr){
    return Array.isArray(arr);
}
function convertnumber(str) {
    return String(str).replace(/(.)(?=(\d{3})+$)/g, '$1,');
}

// func
function setNav(navtemp = null){
    let his = vargoobal[current_tab].history ? vargoobal[current_tab].history : [];
    let temp = vargoobal[current_tab];
    if(!temp.history){
        let childmain = $('.tabcontent.active .tabchild.main');
        temp.history = [];
        temp.history[0] = {
            nav: {
                ...nav_def,
            },
            div: '#' + $('#tabcontent.active .tabchild.main').attr('id'),
        };
        if($('footer .active a').data('canBack')) temp.history[0].nav.canBack = true;
        if($('footer .active a').data('canRefesh')) temp.history[0].nav.canRefesh = true;
        if($('footer .active a').data('canSearch')) temp.history[0].nav.canSearch = true;
    }
    let div = '#'+$('.tabcontent.active .tabchild.active').attr('id');
    let divold = his.length > 0 && his[his.length - 1] ? his[his.length - 1].div : null;
    
    if(!navtemp) navtemp = nav_def;
    if(current_action == 'back') {
        navtemp = his.length > 1 && his[his.length - 2] ? his[his.length - 2].nav : {};
    }
    console.log('navtemp',navtemp);
    vargoobal[current_tab] = {
        ...vargoobal[current_tab],
        ...navtemp,
    };
    let check = current_action !== 'refresh' && div !== divold ? true : false;
    if(check) {
       vargoobal = {
            ...vargoobal,
            [current_tab]: {
                ...vargoobal[current_tab],
                history : {
                    ...vargoobal[current_tab].history,
                    nav: {
                        ...vargoobal[current_tab].history.nav,
                        ...navtemp
                    }
                }
            }
        };
    }
    
    
    // console.log('setNav', navtemp, vargoobal[current_tab].history, div, divold, check);

    if(navtemp && navtemp.canBack) $('.backbtn').removeClass('hidden'); else $('.backbtn').addClass('hidden');
    if(navtemp && navtemp.canRefesh) $('.refreshbtn').removeClass('hidden'); else $('.refreshbtn').addClass('hidden');
    if(navtemp && navtemp.canSearch) $('.searchbtn').removeClass('hidden'); else $('.searchbtn').addClass('hidden');
    if(navtemp && navtemp.title) $('h1').text(navtemp.title);
    if(check) {
        // console.log(his, divold, div);
        his[his.length] = {
            nav: {
                canBack: $('.backbtn').hasClass('hidden') ? false : true,
                canRefesh: $('.refreshbtn').hasClass('hidden') ? false : true,
                canSearch: $('.searchbtn').hasClass('hidden') ? false : true,
                title: $('h1').text(),
            },
            div,
        }
        vargoobal[current_tab].history = his;
    }
    return temp;
}
function change_screen(item = null) {
    // for change div active inside in child tab
    if ( item || $(item).length ) {
        // console.log('change_screen', current_tab,item);
        let div = $('.tabcontent[data-tab="' + current_tab + '"]');
        $('h1').text();
        div.find('.tabchild').removeClass('active');
        div.find(item).addClass('active');
        current_tabChild[current_tab].child = div.find('.tabchild.active').attr('id');
    } else {
        alert('Không tìm thấy màn hình '+current_tab+' -> '+item);
    }
    return false;
}
function change_gr(item = null) {
    // for change div active inside in child tab
    if ( item ) { 
        let div = getSelectorCurDiv();
        div.find('.gr').removeClass('active');
        div.find(item).addClass('active');
    }
    return false;
}
function getSelectorCurDiv(){
    return $(`.tabcontent.active .tabchild.active`);
    // return `.tabcontent[data-tab="${current_tab}"] .tabchild.active`;
}
function getData(name, params = {}, headers = {}) {
    if(!isString(name)) return false;
    loading.value = true;
    // token = 111;
    if( token ) headers.Authorization = `Bearer ${token}`;
    // console.log(headers);
    if(vargoobal[name]){
        params.method = vargoobal[name].method ? vargoobal[name].method : 'post';
    }
    return $.ajax({
        "url": "/api/getData/" + name,
        "type": "POST",
        "cache": false,
        headers,
        // beforeSend: function(request) {
        //     if( token ) request.setRequestHeader("Authorization", `Bearer ${token}`);
        // },
        "data": params,
        "success": function(data){
            loading.value = false;
            last_res = data.res ? data.res : data;
            if(vargoobal[name] && !vargoobal[name].loaded){
                vargoobal[name].loaded = true;
            }
            // console.log('getData ' + name, data);
            if(params.empty && !params.loadmore) getSelectorCurDiv().empty();
            if(params.appendHtml) {
                getSelectorCurDiv().append(data);
            }
            if(params.saveRes) {
                vargoobal[name].res = data.res ? data.res : data;
                // console.log(vargoobal[name]);
            }
            if(data.tatuscode != 200) {
                if(data.tatuscode == 401 || data.tatuscode == 402 || data.tatuscode == 403) {
                    // console.log();
                }
            }else {
                if(last_res.status == 'success' && current_action == 'refresh') {
                    let child = current_tabChild[current_tab].child;
                    $('#' + child).empty();
                }
            }
            if(name) loadscript(name);
            return data;
        },
        error: function(data){
            console.log('error getData', data);
            loading.value = false;
        }
    });
    return false;
}
async function doAction(item = null, respon = null){
    if(item) varibles.last_item_click = item;
    let nameaction = item ? item.data('nameaction') : respon.nameaction;
    let id = item ? item.data('id') : respon.id;
    let iditem = item ? item.data('iditem') : respon.iditem;
    let div = $(`.tabcontent.active .tabchild.active`);
    let par = {};
    current_action = nameaction;
    let run = false;
    // console.log('single', nameaction, id);

    if(nameaction == 'addfavourite') {
        if( !token ) {
            alert('Bạn phải đăng nhập để thực hiện thao tác này');
            return false;
        }
        par.id = id;
        run = true;
    }
    else if(nameaction == 'login') {
        let phone = $('#grlogin [name="phone"]').val();
        let password = $('#grlogin [name="pass"]').val();
        if(!phone || !password){
            alert('Số điện thoại và mật khẩu không được trống');
            return false;
        } else if(!isPhonenumber(phone)){
            alert('Số điện thoại không đúng định dạng');
            return false;
        }
        par.phone = phone;
        par.password = password;
        par.nofid = null;
        run = true;
    }
    else if(nameaction == 'logout') {
        await swal({
            title: "Đăng Xuất",
            text: "Bạn có muốn đăng xuất không ?",
            icon: "warning",
            buttons: ["Bỏ Qua","Đăng Xuất"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                run = true;
            }
        });
    }
    else if(nameaction == 'register') {
        let divpa = $('#grdktv');
        par.name = divpa.find('[name="name"]').val();
        par.sdtgioithieu = divpa.find('[name="sdtgioithieu"]').val();
        par.phone = divpa.find('[name="phone"]').val();
        par.password = divpa.find('[name="pass1"]').val();
        par.password2 = divpa.find('[name="pass2"]').val();
        if(!par.name || !par.phone || !par.password || !par.password2) {
            alert('Các thông tin đăng ký không được trống');
            return false;
        } else if(!isPhonenumber(par.phone)){
            alert('Số điện thoại không đúng định dạng');
            return false;
        } else if(!sosanhpass(par.password, par.password2)) {
            alert('Mật khẩu xác nhận không chính xác')
            return false;
        }
        run = true;
    }
    else if(nameaction == 'confirm') {
        let divpa = $('#grotp');
        par.code = divpa.find('[name="otp"]').val();
        if(!par.code) {
            alert('Thông tin OTP không được trống');
            return false;
        }
        run = true;
    }
    else if(nameaction == 'resetpass') {
        let divpa = $('#grforget');
        par.phone = divpa.find('[name="phone"]').val();
        if(!par.phone) {
            alert('Số điện thoại không được trống');
            return false;
        } else if(!isPhonenumber(par.phone)){
            alert('Số điện thoại không đúng định dạng');
            return false;
        }
        run = true;
    }
    else if(nameaction == 'confirmresetpass') {
        let divpa = $('#grotpChangePass');
        par.code = divpa.find('[name="grotpreset"]').val();
        par.password = divpa.find('[name="pass1"]').val();
        par.password2 = divpa.find('[name="pass2"]').val();
        if(!par.code || !par.password || !par.password2) {
            alert('Thông tin OTP và mật khẩu không được trống');
            return false;
        } else if(!sosanhpass(par.password, par.password2)) {
            alert('Mật khẩu xác nhận không chính xác')
            return false;
        }
        run = true;
    }
    else if(nameaction == 'dklamcoso') {
        let login = item.data('login');
        let divpa = login ? $('#dklamcoso') : $('#grdkcs');
        par.name = divpa.find('[name="namecoso"]').val();
        par.phone = divpa.find('[name="phonecoso"]').val();
        par.diachi = divpa.find('[name="addresscoso"]').val();
        if(!par.name || !par.phone || !par.diachi) {
            alert('Thông tin không được trống');
            return false;
        }
        run = true;
    }
    else if(nameaction == 'listcode') {
        par.page = 1;
        if(current_tabChild[current_tab].listcode_loaded) $('#listcode').empty();
        run = true;
    }
    else if(nameaction == 'listfavourite') {
        par.page = 1;
        if(current_tabChild[current_tab].listfavourite_loaded) $('#listfavourite').empty();
        run = true;
    }
    else if(nameaction == 'lydohethan') {
        let code = item.data('code');
        $('#lydohethan [name="code"]').val(code);
        $('#lydohethan .sku').text(code);
        $('#lydohethan').addClass('active');
    }
    else if(nameaction == 'act_lydohethan') {
        nameaction = 'lydohethan';
        par.code = $('#lydohethan [name="code"]').val();
        par.note = $('#lydohethan [name="note"]').val();
        par.lydohh_id = $('#lydohethan [name="lydohh_id"] option:selected').val();
        if(!par.lydohh_id){
            alert('Bạn phải chọn lý do hết hạn');
        } else {
            run = true;
        }
    }
    else if(nameaction == 'danhgiacode') {
        let code = item.data('code');
        $('#danhgiacode [name="code"]').val(code);
        $('#danhgiacode .sku').text(code);
        $('#danhgiacode').addClass('active');
    }
    else if(nameaction == 'act_danhgiacode') {
        nameaction = 'danhgiacode';
        par.code = $('#danhgiacode [name="code"]').val();
        par.note = $('#danhgiacode [name="note"]').val();
        par.tenktv = $('#danhgiacode [name="tenktv"]').val();
        par.star = $('#danhgiacode [name="star"]').val();
        if(!par.tenktv || !par.star){
            alert('Số ktv và số sao không được trống');
        } else if(!par.code){
            alert('Mã code đang bị trống');
        } else {
            run = true;
        }
    }
    
    else if(nameaction == 'detailcoso') {
        par.id = id;
        run = true;
    }
    else if(nameaction == 'single') {
        if(id){
            let title = item.data('title');
            current_tabChild[current_tab].child = "#" + id;
            change_screen("#" + id);
            setNav({canBack: true, canSearch: false, canRefesh: false,title});
            // console.log('single', title, vargoobal);
        }
    }
    else if(nameaction == 'single_news') {
        if(id){
            let title = item.data('title');
            let codehtml = item.data('html');
            current_tabChild[current_tab].child = "#" + id;
            $('#'+id).empty().html(codehtml);
            $('#'+id+' link').remove();
            change_screen("#" + id);
            setNav({canBack: true, canSearch: false, canRefesh: false,title});
            // console.log('single_news', title, vargoobal);
        }
    }
    else if(nameaction == 'searchcoso') {
        if(!$('#searchcoso').hasClass('active')) {
            let title = item.data('title');
            current_tabChild[current_tab].child = "#" + id;
            change_screen("#" + id);
            setNav({canBack: true, canSearch: true, canRefesh: false,title});
        }
        $('#modalsearchcoso').addClass('active');
    }
    else if(nameaction == 'act_searchcoso') {
        nameaction = 'home';
        par.diachi_id = $('#diachi_id option:selected').val();
        par.tencoso = $('#tencoso').val();
        run = true;
    }
    else if(nameaction == 'getnews') {
        par.page = 1;
        run = true;
    }
    else if(nameaction == 'getvongquay') {
        if(!token) {
            alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            par.page = 1;
            run = true;
        }
    }
    else if(nameaction == 'doiluotquay') {
        if(!token) {
            alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            run = true;
        }
    }
    else if(nameaction == 'single_spin') {
        if(!token) {
            alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            let bill = item.data('bill');
            let note = item.data('note');
            let title = item.data('title');
            // console.log('single_spin', bill, note);
            loading.value = true;
            change_screen("#" + id);
            setNav({canBack: true,title});
            let fulllink = `${settings.linkvongquay}?name=${user.firstname}&phone=${user.phone}&bill=${bill}&note=${note}`;
            $('#iframespin').attr('src',fulllink);
        }
    }
    else if(nameaction == 'single_spin_back') {
        let trunggiai = last_res.trunggiai;
        let bill = trunggiai.bill;
        let divitem = $('#spin_item_' + bill);
        if(divitem.lenght){
            let html = '';
            if (trunggiai.status ) {
                let thoigian = moment().add(1, 'months').format("DD/MM/YYYY HH:mm");
                html += `<div class="vongquay trunggiai" style="background-color: '#fcdbe3'; ">
                    <b class="name">${trunggiai.bill}</b>
                    <span class="trangthai">Trúng thưởng</span>
                    <p>Hạn nhận  ${thoigian} : ${trunggiai.giaithuong_name}</p>
                    <div class="clearfix"></div>
                </div>`;
            } else {
                html += `<div class="vongquay" style="background-color: #fcdbe3;">
                    <b class="name">${trunggiai.bill}</b>
                    <span class="trangthai">Trật</span>
                    <div class="clearfix"></div>
                </div>`;
            }
            if(html) divitem.empty().html(html);
        }
        console.log('single_spin_back', bill, trunggiai);
    }

    else if(nameaction == 'getcode') {
        let getcode = item.data('getcode');
        if(!getcode) {
            alert('Cơ sở này không thể lấy mã');
            return false;
        } else if( !token ) {
            alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            par.coso_id = id;
            run = true;
        }
    }
    else if(nameaction == 'back') {
        let his = vargoobal[current_tab].history;
        if(his && his[0] && his[1]){
            let index = his.length - 2;
            let div = his[index].div;
            change_screen(div);
            setNav(his[index].nav);
            // getSelectorCurDiv().empty();
            $('.tabchild.modal.active').removeClass('active');
            // console.log(his);
        } else {
            alert('Lỗi chức năng, bạn hãy tải lại ứng dụng nhé');
        }
    }
    else if(nameaction == 'refresh') {
        par.page = 1;
        let child = current_tabChild[current_tab].child;
        nameaction = child;
        if(nameaction == 'accountlogin') nameaction = 'user';
        // console.log('refresh', nameaction);

        if(!nameaction){
            alert('Không tìm thấy thao tác cần thực hiện');
        } else {
            vargoobal[current_tab].loaded = false;
            run = true;
        }
    }
    else if(nameaction == 'edituser') {
        let divpa = $('#chinhsuathongtin');
        par.name = divpa.find('[name="name"]').val();
        par.email = divpa.find('[name="email"]').val();
        par.address = divpa.find('[name="address"]').val();
        par.ngaysinh = divpa.find('[name="ngaysinh"]').val();
        par.password = divpa.find('[name="pass1"]').val();
        par.password2 = divpa.find('[name="pass2"]').val();
        // console.log(par);
        if(!par.name || !par.email || !par.address || !par.ngaysinh) {
            alert('Tên, email, địa chỉ, ngày sinh không được trống');
            return false;
        } else if(!isEmail(par.email)){
            alert('Email không đúng định dạng');
            return false;
        }
        if(par.password && !sosanhpass(par.password, par.password2)) {
            alert('Mật khẩu xác nhận không chính xác')
            return false;
        }
        run = true;
    }
    else if(nameaction == 'nguoigioithieu') {
        alert (user.nguoigioithieu ? `Người giới thiệu của bạn là: ${user.nguoigioithieu}` : 'Bạn không có người giới thiệu');
    }
    else if(nameaction == 'modal') {
        $('#' + id).addClass('active');
    }
    else if(nameaction == 'closemodal') {
        item.parents('.modal').removeClass('active');
    }
    if(run){
        // console.log('res getData');
        getData(nameaction,par,{}).then((res) => {
            if(res.status == 'success') {
                if(nameaction == 'logout') logout();
            }else{
                alert(res.msg ? res.msg : 'Lỗi kết nối');
                console.log('res getData', res);
            }
            return false;
        });
    }
    return false;
}
function loadscript(name = 'home'){
    // console.log('loadscript', name, current_action, last_res);
    let namechild = current_tabChild[current_tab].child;
    let html = '';
    
    if (current_action == 'act_searchcoso') {
        if(last_res && isObject(last_res) && last_res.data && last_res.data.cosos){
            last_res.data.cosos.map((item, index) => {
                html += getHtml(item);
                saveId(item.id);
            });
        } else if(last_res && isObject(last_res) && last_res.cosos){
            last_res.cosos.map((item, index) => {
                html += getHtml(item);
                saveId(item.id);
            });
            current_tabChild[current_tab][namechild + '_page'] += 1;
            if(last_res.cosos.length < 15) {
                current_tabChild[current_tab][namechild + '_maxpage'] = true;
            }
        }
        current_tabChild[current_tab][namechild + '_loadmore'] = true;
        $('#modalsearchcoso').removeClass('active');
        current_tabChild[current_tab][namechild + '_page'] = 1;
        // console.log('act_searchcoso', last_res);
        getSelectorCurDiv().append(html);
        $("input.rating").rating({
            filled: 'fa fa-star',
            empty: 'fa fa-star-o xam'
        });
    } else if (name == 'home') {
        if(last_res.settings) settings = last_res.settings;
        if(last_res.diachis) varibles.diachis = last_res.diachis;
        if(last_res.giovangs) varibles.giovangs = last_res.giovangs;
        if(last_res.data.user) {
            user = last_res.data.user;
            if(!user.dsyeuthich) user.dsyeuthich = [];
            // user.dsyeuthich = user.dsyeuthich && !isArray(user.dsyeuthich) ? JSON.parse(user.dsyeuthich) : [];
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
        }
        if(last_res.data.token) {
            token = last_res.data.token;
            localStorage.setItem('token', token);
        }
        if(settings.slider){
            html += '<div class="slider owl-theme owl-carousel">';
            settings.slider.map((item, index) => {
                html += `<img src="${item}" >`;
            });
            html += '</div>';
        }
        if(last_res && isObject(last_res) && last_res.data.cosohot){
            html += '<h3 class="title-home col-md-12">Cơ Sở Hot</h3>';
            last_res.data.cosohot.map((item, index) => {
                html += getHtml(item);
                saveId(item.id);
            });
        }
        if(last_res && isObject(last_res) && last_res.data.cosos){
            html += '<h3 class="title-home col-md-12">Cơ Sở Đáng Đi</h3>';
            last_res.data.cosos.map((item, index) => {
                html += getHtml(item);
                saveId(item.id);
            });
        }
        current_tabChild[current_tab][namechild + '_page'] = 1;
        // console.log(last_res);
        getSelectorCurDiv().append(html);
        setNav({canSearch: true});

        let diachis = '';
        last_res.diachis.map((item2, index) => {
            diachis += `<option value="${item2.id}">${item2.name}</option>`;
        });
        $('#diachi_id').append(diachis);

        let lydo = '';
        settings.lydohh.map((item2, index) => {
            lydo += `<option value="${item2.id}">${item2.name}</option>`;
        });
        $('#lydohh_id').append(lydo);

        $('.slider').owlCarousel({
            loop:true,
            dots: false,
            nav: false,
            margin:15,
            navText: ['', ''],
            autoplay:true,
            // center:true,
            responsive:{
                100:{
                    items:1,
                }
            }
        });
        $("input.rating").rating({
            filled: 'fa fa-star',
            empty: 'fa fa-star-o xam'
        });
        // getData('getsetting',{json: 1, saveRes: 1});
    } else if(name == 'morehome') {
        current_tabChild[current_tab][namechild + '_page'] += 1;
        if(last_res.cosos.length < 15) {
            current_tabChild[current_tab][namechild + '_maxpage'] = true;
        }
        if(last_res && isObject(last_res) && last_res.cosos){
            last_res.cosos.map((item, index) => {
                html += getHtml(item);
                saveId(item.id);
            });
        }
        getSelectorCurDiv().append(html);

        $("input.rating").rating({
            filled: 'fa fa-star',
            empty: 'fa fa-star-o xam'
        });
        // getData('getsetting',{json: 1, saveRes: 1});
    } else if(name == 'addfavourite' ) {
        let cur = parseInt(varibles.last_item_click.find('.numberlike').text());
        if (last_res.code === 'LIKE') {
            cur++;
            varibles.last_item_click.find('i').attr('class','fa fa-heart');
        } else {
            cur--;
            varibles.last_item_click.find('i').attr('class','fa fa-heart-o');
        }
        varibles.last_item_click.find('p').text(cur);
    } else if(name == 'register') {
        if(last_res.status == 'success') {
            console.log('register');
            change_gr('#grotp');
        }
        alert(last_res.msg ? last_res.msg : 'Bạn hãy nhập mã xác nhận')
    } else if(name == 'confirm') {
        if(last_res.status == 'success') {
            user = last_res.user;
            token = last_res.token;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            change_gr('#grlogin');
            loadinfoaccount(user);
        }
        alert(last_res.msg ? last_res.msg : 'Bạn đã xác nhận và đăng nhập thành công');
    } else if(name == 'login') {
        if(last_res.status == 'success'){
            token = last_res.token;
            user = last_res.user;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
            $(`.tabcontent[data-tab="${current_tab}"] .tabchild`).removeClass('active');
            $(`.tabcontent[data-tab="${current_tab}"] #accountlogin`).addClass('active');
        }
        alert(last_res.msg);
    } else if(name == 'resetpass') {
        if(last_res.status == 'success') {
            change_gr('#grotpChangePass');
        }else alert(last_res.msg ? last_res.msg : 'Bạn đã xác nhận và đăng nhập thành công');
    } else if(name == 'confirmresetpass') {
        if(last_res.status == 'success') {
            change_gr('#grlogin');
            user = last_res.user;
            token = last_res.token;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
        }
        alert(last_res.msg ? last_res.msg : 'Bạn đã xác nhận và đăng nhập thành công');
    } else if(name == 'dklamcoso') {
        if(last_res.status == 'success') {
            change_gr('#grlogin');
            $('[name="namecoso"], [name="phonecoso"], [name="addresscoso"]').val('');
        }
        alert(last_res.msg ? last_res.msg : 'Bạn đã đăng ký thành công');
    } else if(name == 'listcode') {
        if(last_res.status == 'success') {
            change_screen('#' + name);
            setNav({canBack: true, canSearch: false, canRefesh: true,title: 'Danh Sách Mã'});
            
            // console.log('listcode', last_res);
            current_tabChild[current_tab].child = name;
            current_tabChild[current_tab][namechild + '_loadmore'] = true;
            current_tabChild[current_tab][namechild + '_page'] = last_res.list.current_page;
            current_tabChild[current_tab][namechild + '_loaded'] = true;
            if(last_res.list.last_page <= last_res.list.current_page) {
                current_tabChild[current_tab][namechild + '_maxpage'] = true;
            }
            if(last_res && isObject(last_res) && last_res.list && last_res.list.data && last_res.list.data.length > 0){
                last_res.list.data.map((item, index) => {
                    html += getHtml(item, 'listcode');
                    saveId(item.id);
                });
            } else{
                html += '<p class="col-md-12">Chưa có mã khuyến mãi nào gần đây</p>';
            }
            getSelectorCurDiv().append(html);
            current_tabChild[current_tab].child = name;
            
        } else alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'listfavourite') {
        if(last_res.status == 'success') {
            change_screen('#' + name);
            setNav({canBack: true, canSearch: false, canRefesh: true,title: 'Cơ Sở Yêu Thích'});
            user.dsyeuthich = last_res.favouriteIDs;
            
            if(last_res && isObject(last_res) && last_res.favourite.data){
                last_res.favourite.data.map((item, index) => {
                    html += getHtml(item);
                    saveId(item.id);
                });
            }
            current_tabChild[current_tab][namechild + '_page'] = 1;
            current_tabChild[current_tab][namechild + '_loadmore'] = true;
            current_tabChild[current_tab][namechild + '_page'] = last_res.favourite.current_page;
            current_tabChild[current_tab][namechild + '_loaded'] = true;
            if(last_res.favourite.last_page <= last_res.favourite.current_page) {
                current_tabChild[current_tab][namechild + '_maxpage'] = true;
            }
            getSelectorCurDiv().append(html);
            current_tabChild[current_tab].child = name;

            $("input.rating").rating({
                filled: 'fa fa-star',
                empty: 'fa fa-star-o xam'
            });
        } else alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'edituser') {
        if(last_res.status == 'success'){
            user = last_res.user;
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
            $('.backbtn').click();
        } else alert(last_res.msg);
    } else if(name == 'detailcoso') {
        if(last_res.status == 'success'){
            change_screen('#' + name);
            let item = last_res.data;
            setNav({canBack: true, canSearch: false, canRefesh: false,title: last_res.data.name});
            
            html += '<div class="slidersing owl-theme owl-carousel">';
            last_res.images.map((item2, index) => {
                html += `<div class="imgsing" style="background-image: url(${item2.linkimg})" ></div>`;
            });
            html += '</div>';
            html += '<div class="col-md-12 infosing">';
            html += `<h2 class="namesing">${item.name}</h2>`;
            html += `<div class="mb-10px">
                <span class="star mr-15px"><input type="hidden" data-filled="fa fa-star" data-empty="fa fa-star-o" class="rating" disabled="disabled" value="${item.star}"/></span>
                <a href="#" class="like action" data-nameaction="addfavourite" data-id="${item.id}">
                    <i class="fa red ${checklike(item.id) ? 'fa-heart' : 'fa-heart-o'}"></i>
                    <span class="red numberlike">${item.solike + item.likecongthem}</span> <span class="black">lượt thích</span>
                </a></div>
                `;
            html += `<div><span class="price" style="margin-right: 15px;">
                    <del>${numberWithCommas(item.giatruockm)}</del>
                    <ins>${numberWithCommas(item.giachinhthuc)}</ins>
                </span>`;

            if(item.banggia) html += `<a href="#" class="btn btn-primary btn-xs action" data-nameaction="modal" data-id="banggiave">Xem Bảng Giá <i class="fa fa-long-arrow-right"></i></a>`;
            html += '</div></div>';

            html += `<div class="beakline"></div>
                <div class="listitem"><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=${item.tdlat},${item.tdlong}"><i class="fa fa-tag ico"></i> ${item.diachi}</a> </div>
                <div class="listitem"><i class="fa fa-clock-o ogran ico"></i> ${item.giomocua}</div>
                <div class="listitem"><i class="fa fa-phone gren ico"></i> ${item.phone}</div>
                `;
            if(item.thongtincoban.length && item.thongtincoban[0].name) {
                html += `<div class="beakline"></div>`;
                html += `<h2 class="titlesing col-md-12">Thông Tin Cơ Bản</h2>`;
                item.thongtincoban.map((item2, index) => {
                    if(item2.name && item2.diengiai) html += `<div class="listitem ttcoban"><span class="name">${item2.name}</span> <span>${item2.diengiai}</span></div>`;
                });
            }
            if(item.noidung) {
                html += `<div class="beakline"></div>`;
                html += `<div class="content">${item.noidung}</div>`;
            }
            html += `<div class="col-md-12 wpbtnbook">${showBtnGetcode(item)}</div>`;

            let banggiahtml = '';
            item.banggia.map((item2, index) => {
                banggiahtml += `<div><b class="pull-left">${item2.name}</b> <span class="pull-right price">
                    <del>${numberWithCommas(item2.gia1)}</del>
                    <ins>${numberWithCommas(item2.gia2)}</ins>
                </span><div class="clearfix"></div></div>
                <p>${item2.diengiai}</p><hr/>
                `;
            });
            $('#ndbanggia').html(banggiahtml);
            
            getSelectorCurDiv().html(html);
            $('.slidersing').owlCarousel({
                loop:true,
                dots: false,
                nav: false,
                margin:15,
                navText: ['', ''],
                autoplay:true,
                // center:true,
                responsive:{
                    100:{
                        items:1,
                    }
                }
            });
            $("input.rating").rating({
                filled: 'fa fa-star',
                empty: 'fa fa-star-o xam'
            });
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'getcode') {
        if(last_res.status == 'success') {
            $('#successbook .code').text(last_res.code.code);
            $('#successbook .hansudung').text(last_res.code.hethan);
            $('#successbook .txt').text(last_res.msg);
            $('#successbook').addClass('active');
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'getnews') {
        if(last_res.status == 'success') {
            current_tabChild[current_tab][namechild + '_loadmore'] = true;
            current_tabChild[current_tab][namechild + '_page'] += 1;
            let page = current_tabChild[current_tab][namechild + '_page'];
            if(last_res.data.current_page >= last_res.data.last_page) {
                current_tabChild[current_tab][namechild + '_maxpage'] = true;
            }
            if(last_res && isObject(last_res) && last_res.data.data && last_res.data.data.length > 0){
                last_res.data.data.map((item, index) => {
                    html += getHtml(item,'news');
                    saveId(item.id);
                });
            } else{
                html += '<p class="col-md-12">Chưa có bài viết nào</p>';
            }
            getSelectorCurDiv().append(html);
            console.log(namechild);
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'getvongquay') {
        if(last_res.status == 'success') {
            if(!vargoobal[current_tab].loaded) {
                let quydinhquay = settings.quydinhquay;
                let thoihanquay = parseInt(convertnumber(settings.thoihanquay));
                let diemquay = parseInt(convertnumber(settings.diemquay));
                let diemthuong = parseInt(convertnumber(settings.diemthuong));
                let tongdiem = parseInt(convertnumber(user.tongdiem));
                var parts = quydinhquay.match(/[^\r\n]+/g);
                let restr = parts.map((item, index) => {
                    let str = parts[index].replace('[TP]', tongdiem);
                    str = str.replace('[TQ]', thoihanquay);
                    str = str.replace('[PQ]', diemquay);
                    str = str.replace('[PDI]', diemthuong);
                    return '<p>' + str + '</p>';
                });
                $('#quydinhquay').append(restr);
                vargoobal[current_tab].loaded = true;
            }
            
            current_tabChild[current_tab][namechild + '_loadmore'] = true;
            current_tabChild[current_tab][namechild + '_page'] += 1;
            let page = current_tabChild[current_tab][namechild + '_page'];
            if(last_res.list.current_page >= last_res.list.last_page) {
                current_tabChild[current_tab][namechild + '_maxpage'] = true;
            }
            if(last_res && isObject(last_res) && last_res.list.data && last_res.list.data.length > 0){
                last_res.list.data.map((item, index) => {
                    html += getHtml(item,'spin');
                    saveId(item.id);
                });
            } else{
                html += '<p class="col-md-12">Chưa có vòng quay nào</p>';
            }
            $('#listquay').append(html);
            console.log(namechild);
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'doiluotquay') {
        if(last_res.status == 'success') {
            if(last_res.vongquay) {
                let html = getHtml(last_res.vongquay, 'spin');
                $('#listquay').prepend(html);
            }
            // getSelectorCurDiv().append(html);
            // console.log(namechild);
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'lydohethan') {
        if(last_res.status == 'success') {
            $('#lydohethan').removeClass('active');
            $('.refreshbtn').click();
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'danhgiacode') {
        if(last_res.status == 'success') {
            $('#danhgiacode').removeClass('active');
            $('.refreshbtn').click();
        } else {
            alert(last_res.msg);
        }
    } else if(name == 'user') {
        if(last_res.status == 'success'){
            token = last_res.token;
            user = last_res.user;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
        }
    }
    
}
function checklike(id) {
    let check = false;
    user && user.dsyeuthich && user.dsyeuthich.map((item, index) => {
      if (item === id) {
        check = true;
        return true;
      }
    });
    return check;
}
function showlog(varible){
    let type = varible.type ? varible.type : 'error';
    let title = "%c "+type+" %s";
    let css = '';
    if(type == 'error'){
        css = 'background: red; color: #fff';
    } else if(type == 'success') {
        css = 'background: green; color: #fff';
    } else css = 'background: #f0f0f0; color: #222';
    let value = varible.value ? "%c" +varible.value : null;
    if (typeof value === 'string' || value instanceof String) console.log(title, css, value);
    else{
        css = [
            'color: green',
            'background: yellow',
        ].join(';');
        console.log(title, css, JSON.stringify(value, null, 4));
    }
}
function numberWithCommas(x) {
    if(!x) return '0';
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function showBtnGetcode(item = null) {
    if (!item) return '';
    return `<a href="${item.getcode ? '#' : 'tel:' + item.phone}" class="btn btn-primary btn-block ${item.getcode ? 'action' : ''}" ${item.getcode ? 'data-nameaction="getcode"' : ''} data-id="${item.id}" data-getcode="${item.getcode}">
        <span>${item.getcode ? 'Lấy Mã Khuyến Mãi' : item.phone}</span>
    </a>`;
}
function getHtml(item = null, type = 'coso'){
    if(!item) return '';
    let html = '';
    if(type == 'coso') {
        html = `<div class="col-md-12 mb-15px"><div class="coso">
                    <a href="#" class="action wpimg" data-nameaction="detailcoso" data-title="${item.name}" data-id="${item.id}">
                        <div class="img" style="background-image: url(${item.image})"></div>
                    </a>
                <div>
                    <a href="#" class="action" data-nameaction="detailcoso" data-title="${item.name}" data-id="${item.id}">
                        <h3 class="name">${item.name}</h3>
                        <div class="star">
                            <input type="hidden" data-filled="fa fa-star" data-empty="fa fa-star-o" class="rating" disabled="disabled" value="${item.star}"/>
                        </div>
                        <div class="price">
                            <del>${numberWithCommas(item.giatruockm)}</del>
                            <ins>${numberWithCommas(item.giachinhthuc)}</ins>
                        </div>
                        <div class="diachi mb-10px">${item.diachi}</div>
                    </a>
                    ${showBtnGetcode(item)}
                    
                    <a href="#" class="like action" data-nameaction="addfavourite" data-id="${item.id}">
                        <i class="fa ${checklike(item.id) ? 'fa-heart' : 'fa-heart-o'}"></i>
                        <p class="numberlike">${item.solike + item.likecongthem}</p>
                    </a>
                </div>
            </div></div>`;
    }
    if(type == 'listcode') {
        let bgtop = '';
        if (item.danhgia) {
            bgtop += `<a href="#" data-id="danhgiacode" data-nameaction="danhgiacode" data-code="${item.code}" class="bgoverflow action">Nhấp vào đây để cho ý kiến về dịch vụ đã sử dụng</a>`;
        } else if (item.ykienhethan && item.star === 0) {
            bgtop += `<a href="#" class="bgoverflow pink action" data-id="lydohethan" data-code="${item.code}" data-nameaction="lydohethan">Mã khuyến mãi đã hết hạn. Hãy cho chúng tôi biết lý do nhé !</a>`;
        } else if (item.star > 0) {
            bgtop += `<div class="bgoverflow">Cảm ơn bạn đã đánh giá</div>`;
        }
        html += `<div class="col-md-12 mb-15px">${bgtop}<div class="coso code">
            <a href="#" class="action" data-name="detailcoso">
                <div class="wpimg">
                    <div class="img" style="background-image: url(${item.anhcoso})"></div>
                </div>
                <div class="rightnd">
                    <h3 class="code">${item.code}</h3>
                    <h3 class="name">${item.tencoso}</h3>
                    <div class="diachi">${item.dccoso}</div>`;
        // html += `<div class="dasdMycode mb-5px">Đã sử dụng</div>`;
        
        if(item.status === 0) {
            html += `<div class="hancodeMycode">Hạn sử dụng: ${item.hethan}</div>
                    <div class="hancodeMycode mb-5px">Khi hết hạn bạn vui lòng tạo mã mới</div>
            `;
        } else if(item.status === 10) {
            html += `<div class="dasdMycode mb-5px">Đã sử dụng</div>`;
        } else if(item.status === 9) {
            html += `<div class="hethan mb-5px">Đã hết hạn</div>`;
        }
        html += `</div></a>
        </div></div>`;
    }
    if(type == 'news') {
        vargoobal[current_tab].loaded = true;
        let bgtop = '';
        html += `<div class="col-md-12 mb-15px"><div class="news">
            <a href="#" class="action" data-title="${item.name}" data-nameaction="single_news" data-id="singlenews" data-iditem="${item.id}" data-html='${item.content}'>
                <img src="${item.image}" />
                <h3 class="name">${item.name}</h3>
                <p>${item.intro}</p>
                <div class="clearfix"></div>
            </a>`;
        html += `</div></div>`;
    }
    if(type == 'spin') {
        vargoobal[current_tab].loaded = true;
        html += `<div class="col-md-12 mb-15px" id="spin_item_${item.sku}">`;
        if (item.thoihanquay && item.checkhethan) {
            html += `<div class="vongquay" style="background-color: #fcdbe3;">
            <div href="#" class="" data-title="${item.name}" data-nameaction="single_news" data-id="singlespin" >
                <b class="name">${item.sku}</b>
                <span class="trangthai">Hết hạn</span>
                <p>Thời hạn quay: ${item.thoihanquay}</p>
                <div class="clearfix"></div>
            </div></div>`;
        } else if (item.status === 10) {
            html += `<div class="vongquay trunggiai" style="background-color: ${item.checknhangiai ? '#e9fcdb' : '#fcdbe3'};">
                <b class="name">${item.sku}</b>
                <span class="trangthai">Trúng thưởng</span>
                <p>${item.nhangiai
                    ? 'Đã nhận '
                    : ( item.checknhangiai
                    ? 'Hạn nhận ' + item.thoihannhangiai
                    : 'Hết hạn nhận' )
                  } : ${item.tengiai}</p>
                <div class="clearfix"></div>
            </div>`;
        } else if (item.status === 0) {
            html += `<div class="vongquay">
            <a href="#" class="action" data-note="Quay trên webview" data-bill="${item.sku}" data-title="Vòng Quay May Mắn" data-nameaction="single_spin" data-id="singlespin" >
                <b class="name">${item.sku}</b>
                <span class="trangthai">Chưa quay</span>
                <p>Thời hạn: ${item.thoihanquay}</p>
                <div class="clearfix"></div>
            </a></div>`;
        } else if (item.status === 1) {
            html += `<div class="vongquay" style="background-color: #fcdbe3;">
                <b class="name">${item.sku}</b>
                <span class="trangthai">Trật</span>
                <div class="clearfix"></div>
            </div>`;
        }
        
        html += `</div>`;
    }
    return html;
}
function getIds(){
    let namechild = current_tabChild[current_tab].child;
    if(!current_tabChild[current_tab][namechild + '_ids']) {
        current_tabChild[current_tab][namechild + '_ids'] = [];
    }
    // console.log('getIds', current_tabChild[current_tab]);
    return current_tabChild[current_tab][namechild + '_ids'];
}
function saveId(value, action = 'add'){
    let namechild = current_tabChild[current_tab].child;
    if(!current_tabChild[current_tab][namechild + '_ids']) {
        current_tabChild[current_tab][namechild + '_ids'] = [];
    }
    let ids = current_tabChild[current_tab][namechild + '_ids'];
    if(action == 'add' && value){
        if(ids.indexOf(value) == -1) ids[ids.length] = value;
    } else if(action == 'reset') ids = [];
    // console.log(ids);
    current_tabChild[current_tab][namechild + '_ids'] = ids;
    return ids
}

// account 
function loadinfoaccount(user) {
    if(!user) return;
    $('#accountlogin .nameaccount').text(user.firstname);
    $('#accountlogin .sdtaccount').text(user.phone);
    $('#accountlogin .capaccount').text(user.level);
    $('#accountlogin .diemaccount').text(numberWithCommas(user.tongdiem));
    $('#accountlogin .hanaccount').text(user.endactive);
    $('.accountnotlogin').removeClass('active');
    $('#accountlogin').addClass('active');

    let idcs = '#chinhsuathongtin';
    $(`${idcs} [name="name"]`).val(user.firstname);
    $(`${idcs} [name="address"]`).val(user.address);
    $(`${idcs} [name="ngaysinh"]`).val(user.ngaysinh);
    $(`${idcs} [name="email"]`).val(user.email);

    $('#linksp_fb').attr('href',settings.fanpage);
    $('#linksp_zalo').attr('href',settings.sdtzalo);
    $('#linksp_hotline').attr('href','tel:'+settings.sdt);

    if(settings.huongdannaptien) $('#huongdannaptien').html(settings.huongdannaptien);
    if(settings.quyenloitv) $('#quyenloitv').html(settings.quyenloitv);
    $('#huongdannaptien link, #quyenloitv link').remove();
    // if(settings.huongdannaptien) $('.huongdannaptien').html(settings.huongdannaptien);
    
    $(`.tabcontent[data-tab="${name}"] .tabchild`).removeClass('active');
    $(`.tabcontent[data-tab="${name}"] #accountlogin`).addClass('active');
    updateTabChild();
}
function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}
function checkPass(pass) {
  if (pass.length > 5) {
    return true;
  } else {
    return false;
  }
}
function sosanhpass(pass1, pass2) {
  var ok = true;
  if (pass1 != pass2) {
    ok = false;
  }
  return ok;
}
function logout(){
    user = null;
    token = null;
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    $('.tabcontent .tabchild').removeClass('active');
    $('.tabcontent .accountnotlogin').addClass('active');
    updateTabChild();
    return false;
}
function updateTabChild(){
    $('footer a').each(function(){
        let name = $(this).data('name');
        if(!current_tabChild[name]){
            current_tabChild[name] = {};
        }
        let child = $(`.tabcontent[data-tab="${name}"] .tabchild.active`).data('nametab');
        current_tabChild[name].child = child;
    });
}
