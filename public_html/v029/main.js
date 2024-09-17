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
    // console.log("loading.value to " + val, loading.value);
    if(loading.value) $('.wploading').removeClass('hidden');
    else $('.wploading').addClass('hidden');
});
var nav_def = {
    title: '',
    canBack: false,
    canSearch: false,
    canRefesh: false,
    showlogo: false
};
var token = null;
var settings = null;
var varibles = {
    padding_bottom: 70,
    last_item_click: null,
    address_geo_location: false,
    lat: null,
    lng: null,
    cosoquanhday: [],
    phonetemp: null,
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
        child: 'listgame',
        getvongquay_page: 0,

    },
    account: {
        child: 'accountnotlogin',
    }
};
var current_action = '';
var current_length = '';
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
            div: '#dscoso',
        }],
        canSearch: true,
        tencoso: '',
        diachi_id: '',
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
                title: 'Tin Tức'
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
                title: 'Games'
            },
            div: '#listgame',
        }],
        canRefesh: true,
    },
    account: {
        method : 'POST',
        loaded: false,
        history: [{
            nav : {
                canRefesh: true,
                title: 'Tài Khoản'
            },
            div: '#accountlogin',
        }],
        canRefesh: false,
    },
}
// name + action 
var idDevice = null;
var user = null;
var os = 'web';
var domainchat = document.head.querySelector('meta[name="domainchat"]').content;


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
    getData('home',{saveRes: 1, page: 1, os});
    var heightif = 0;
    window.addEventListener('message', function (e) {
        // console.log('addEventListener message',e.data);
        // chi ap dung cho iframe
        if($('#singlespin').hasClass('active')) loading.value = false;
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
        let header = $(this).data('header');
        let requireLogin = $(this).attr('data-requireLogin');
        // console.log(requireLogin);
        if(requireLogin && !user) {
            $('.tabfooter[data-tab="account"]').click();
            show_alert('Bạn phải đăng nhập');
            return false;
        }
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
            
            let div = $('.tabcontent[data-tab="home"] .tabchild.main').attr('id');
            if(!loaded) {
                vargoobal[tab].canSearch = $(this).attr('data-canSearch');
                vargoobal[tab].canBack = $(this).attr('data-canBack');
                vargoobal[tab].canRefesh = $(this).attr('data-canRefesh');
                if(tab == 'account' && !user) vargoobal[tab].canRefesh = false;
            }
            
            if(vargoobal[tab].canBack) $('.backbtn').removeClass('hidden'); else $('.backbtn').addClass('hidden');
            if(vargoobal[tab].canRefesh) $('.refreshbtn').removeClass('hidden'); else $('.refreshbtn').addClass('hidden');
            if(vargoobal[tab].canSearch) $('.searchbtn').removeClass('hidden'); else $('.searchbtn').addClass('hidden');
            if(title) $('h1').text(title);

            vargoobal[tab].loaded = true;
            // console.log('footer', loaded, current_tab, tab);
            current_tab = tab;
        } else {
            if(current_tab == 'account' && !user) {
                
            } else {
                let histemp = [];
                histemp[0] = vargoobal[current_tab].history[0];
                vargoobal[current_tab].history = histemp;
                $('.tabcontent[data-tab="'+current_tab+'"] .tabchild').removeClass('active');
                $('.tabcontent[data-tab="'+current_tab+'"] .tabchild.main').addClass('active');
                setNav(histemp[0].nav);
            }
        }
        $('body').removeClass('head1').removeClass('head2');
        if(header){
            header = parseInt(header);
            $('body').addClass('head' + header);
        }else{
            $('body').addClass('head1');
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
    $('body').on('click','.action',function(e){
        let confirm = $(this).data('confirm');
        let msgconfirm = $(this).data('msgconfirm') ? $(this).data('msgconfirm') : 'Bạn có chắc chắn muốn thực hiện thao tác này không ?';
        let $this = $(this);
        if(confirm) {
            swal({
                text: msgconfirm,
                buttons: ['Không', 'Có']
            }).then((willDelete) => {
                if (willDelete) {
                    doAction($this);
                }
            });
        } else {
            doAction($this);
        }
        
        return false;
    });
    $(window).scroll(function(){
        var top = $(this).scrollTop() + window.innerHeight + 50;
        let namechild = current_tabChild[current_tab].child;
        let hdiv = $('.loadmore').offset().top;
        // console.log(top, hdiv);
        if (!loading.value && !current_tabChild[current_tab][namechild + '_maxpage'] && current_tabChild[current_tab][namechild + '_loadmore']) {
            if (top >= hdiv) {
                let name = '';
                let body = {}
                if(!current_tabChild[current_tab][namechild + '_page']) current_tabChild[current_tab][namechild + '_page'] = 1;
                if(current_tab == 'home'){
                    name = 'morehome';
                    current_action = 'morehome';
                    body = {
                        id: getIds(),
                        tencoso: '',
                        diachi_id: 0,
                        os,
                        page: current_tabChild[current_tab][namechild + '_page'] + 1,
                        loadmore: true
                    };
                    let child = current_tabChild[current_tab].child;
                    if(child == 'searchcoso') {
                        body.diachi_id = vargoobal.home.diachi_id;
                        body.tencoso = vargoobal.home.tencoso;
                    }
                } else if (current_tab == 'news'){
                    name = 'getnews';
                    body = {
                        id: getIds(),
                        page: current_tabChild[current_tab][namechild + '_page'] + 1,
                        loadmore: true,
                        os,
                    };
                } else if (current_tab == 'spin'){
                    let child = current_tabChild[current_tab].child;
                    if(child == 'searchcoso') {
                        name = 'getvongquay';
                        body = {
                            id: getIds(),
                            page: current_tabChild[current_tab][namechild + '_page'] + 1,
                            loadmore: true,
                            os,
                        };
                    }
                } 
                getData(name, body, {}).then((res) => {
                    // console.log('morehome', res.cosos);
                    if(res.status == 'success') {
                        // loadscript(name);
                    } else {
                        console.log('error more');
                        show_alert(res.msg ? res.msg : 'Lấy thông tin thất bại')
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

    var year60 = new Date();
    year60.setFullYear(year60.getFullYear() - 60);
    
    var year15 = new Date();
    year15.setFullYear(year15.getFullYear() - 15);
    
    console.log(year15, year60);

    $('.date').datepicker($.extend({}, $.datepicker.regional['vn'], {
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        yearRange: `${year60.getFullYear()}:${year15.getFullYear()}`
    }));

    $('textarea, input').on('focus',function(){
        if(os != 'web') xfocus();
    });
    $('textarea, input').on('blur',function(){
        if(os != 'web') xblur();
    });
        
    let chat = gup('chat');
    if(chat) {
        console.log('chat1');
        setTimeout(() => {
            doAction(null, {
                nameaction: 'chat_coso',
                id: 5,
                title: 'chat test',
                phone: "0909979367",
                codelogin: "28B02T"
            })
        }, 1000);
    }
    setTimeout(() => {
        showAlertCode();
    }, randomInteger(10000,20000));

    $('.btnviewmore').on('click', function(){
        // $(this).toggle('viewfull');
        $('#wpquydinhquay').toggleClass('viewfull');
    });
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
    if(!isString(str)) return false;
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

// function
function showAlertCode() {
    // clearInterval(intervalAlert);
    if(!$('.alertCode').hasClass('active') && current_tab == 'home'){
        let par = {
            noloading: true, 
            userID: user ? user.id : null, 
            getAllCS: 1,
            os: getMobileOS()
        };
        if(current_tabChild.home.child == 'detailcoso') {
            par.getAllCS = 0;
        }
        let seconds = randomInteger(10,60);
        if(par.getAllCS == 0) seconds *= 3;
        current_action = 'get-moi-lay-code';
        getData('get-moi-lay-code',par,{}).then((res) => {
            if(res.status == 'success') {
                // if(nameaction == 'logout') logout();
                if(res.status == 'success'){
                    let namecs = par.getAllCS && res.coso ? res.coso.name : $('.maintitle').text();
                    $('.alertCode').addClass('active');
                    $('.nameUsserAlert').text(res.user.firstname);
                    $('.alertCode .img').css('background-image', `url(${res.coso.image})`);
                    $('.phoneUsserAlert').text(res.user.phone);
                    $('.nameCSAlert').text(namecs);
                    $('.timeAlert').text(secondsToHms(seconds * 3));
                    setTimeout(() => {
                        $('.alertCode').removeClass('active');
                    }, 5000);
                    setTimeout(() => {
                        showAlertCode();
                    }, seconds * 1000);
                }
                // console.log('get-moi-lay-code',seconds, res);
            }else{
                // show_alert(res.msg ? res.msg : 'Lỗi kết nối');
            }
            return false;
        });
    }
}
function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " giờ, " : " giờ, ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " phút, " : " phút, ") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " giây" : " giây") : "";
    return hDisplay + mDisplay + sDisplay; 
}
function randomInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
function changeStateUrl(data){
    data = {
        type: 'chi-tiet-co-so',
        title: 'Campapro - Lấy Mã Khuyến Mãi',
        slug: null,
        ...data,
    }
    console.log('changeStateUrl',data.slug);
    let domain = window.location.hostname;
    let slug = data.slug ? data.slug : '';
    $('title').text(data.title == "Massage" ? "Campapro - Lấy Mã Khuyến Mãi" : data.title);
    window.history.pushState(data.type, data.title, `/${slug}`);
    // if(data.slug){}
}
function setNav(navtemp = null){
    let his = vargoobal[current_tab].history ? vargoobal[current_tab].history : [];
    let temp = vargoobal[current_tab];
    let child = current_tabChild[current_tab].child;

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
    if(current_action == 'back' && '#' + child == his[0].div) {
        navtemp = his.length >= 1 ? his[0].nav : {};
    } else if(current_action == 'back') {
        navtemp = his.length > 1 && his[his.length - 1] ? his[his.length - 1].nav : {};
    }
    // console.log('navtemp',current_action,navtemp);
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
    if(navtemp && !navtemp.fulliframe) $('body').removeClass('fulliframe'); else $('body').addClass('fulliframe');
    if(navtemp && navtemp.canSearch) {
        let nameaction = "searchcoso";
        if(current_action == 'lichsupoint') nameaction = 'searchlspoint';
        $('.searchbtn').attr('data-nameaction', nameaction).removeClass('hidden'); 
        // console.log('current_action', current_action);
    } else $('.searchbtn').addClass('hidden');
    if(navtemp && navtemp.title) $('h1').text(navtemp.title);
    if(check) {
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
    
    let url = location.pathname.split('/').slice(1);
    temp.slug = url && url[0] ? url[0] : null;
    temp.title = document.title;
    checkShowLogoNav(navtemp.showlogo);
    return temp;
}
function checkShowLogoNav(checkshow = true){
    // if(typeof checkshow !== "undefined") checkshow = true;
    // let countLogo = parseInt($('.header1 .collefthead a').not(".hidden").length);
    // console.log('countLogo', countLogo);
    // if(countLogo > 1 || !checkshow){
    if(current_tabChild[current_tab].child == 'dscoso' || checkshow){
        $('.colmidhead').addClass('hasLogo');
    }else{
        $('.colmidhead').removeClass('hasLogo');
    }
}
function change_screen(item = null) {
    // for change div active inside in child tab
    if ( item || $(item).length ) {
        // console.log('change_screen', current_tab,item);
        let div = $('.tabcontent[data-tab="' + current_tab + '"]');
        div.find('.tabchild').removeClass('active');
        // $('h1').text();
        div.find(item).addClass('active');
        current_tabChild[current_tab].child = div.find('.tabchild.active').attr('id');
    } else {
        show_alert('Không tìm thấy màn hình '+current_tab+' -> '+item);
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
    if(!isString(name) || !name) return false;
    if(!params.noloading) loading.value = true;
    // token = 111;
    if( token ) headers.Authorization = `Bearer ${token}`;
    // console.log('getData start');
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
                if(last_res.status == 'success' && current_action == 'refresh' && name !== 'user' && name !== 'getvongquay') {
                    let child = current_tabChild[current_tab].child;
                    $('#' + child).empty();
                }
            }
            if(data.tonaptien) {
                $('.tabfooter[data-tab="account"]').click();
                $('#actionhdnt').click();
            }
            if(name) loadscript(name);
            loading.value = false;
            // console.log('getData end');
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
    let run = false;
    current_action = nameaction;
    // console.log('single', nameaction, id);

    if(nameaction == 'addfavourite') {
        if( !token ) {
            show_alert('Bạn phải đăng nhập để thực hiện thao tác này');
            return false;
        }
        par.id = id;
        par.os = os;
        run = true;
    }
    else if(nameaction == 'login') {
        let phone = $('#grlogin [name="phone"]').val();
        let password = $('#grlogin [name="pass"]').val();
        if(!phone || !password){
            show_alert('Số điện thoại và mật khẩu không được trống');
            return false;
        } else if(!isPhonenumber(phone)){
            show_alert('Số điện thoại không đúng định dạng');
            return false;
        }
        par.phone = phone;
        par.password = password;
        par.nofid = idDevice;
        par.os = os;
        varibles.phonetemp = phone;
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
            par.nofid = idDevice;
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
            show_alert('Các thông tin đăng ký không được trống');
            return false;
        } else if(!isPhonenumber(par.phone)){
            show_alert('Số điện thoại không đúng định dạng');
            return false;
        } else if(!sosanhpass(par.password, par.password2)) {
            show_alert('Mật khẩu xác nhận không chính xác')
            return false;
        }
        varibles.phonetemp = par.phone;
        run = true;
    }
    else if(nameaction == 'resentcode') {
        if(varibles.phonetemp) {
            par.phone = varibles.phonetemp;
            run = true;
        } else {
            show_alert("Không nhận được số điện thoại cần gửi")
        }
    }
    else if(nameaction == 'confirm') {
        let divpa = $('#grotp');
        par.code = divpa.find('[name="otp"]').val();
        if(!par.code) {
            show_alert('Thông tin OTP không được trống');
            return false;
        }
        run = true;
    }
    else if(nameaction == 'resetpass') {
        let divpa = $('#grforget');
        par.phone = divpa.find('[name="phone"]').val();
        if(!par.phone) {
            show_alert('Số điện thoại không được trống');
            return false;
        } else if(!isPhonenumber(par.phone)){
            show_alert('Số điện thoại không đúng định dạng');
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
            show_alert('Thông tin OTP và mật khẩu không được trống');
            return false;
        } else if(!sosanhpass(par.password, par.password2)) {
            show_alert('Mật khẩu xác nhận không chính xác')
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
            show_alert('Thông tin không được trống');
            return false;
        }
        run = true;
    }
    else if(nameaction == 'listcode') {
        par.page = 1;
        if(current_tabChild[current_tab].listcode_loaded) $('#listcode').empty();
        run = true;
    }
    else if(nameaction == 'showqrcode') {
        let text = item.data('code');
        if(text) {
            $('#' + id).find('.sku').text(text);
            $('#' + id).find('.noidung').empty().qrcode({text, size: 330});
            $('#' + id).addClass('active');
        } else {
            show_alert("Không nhận được thông tin mã để hiển thị!", {
                icon: "error",
                buttons: ["Đóng", null],
            })
        }
        console.log(text, nameaction);
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
            show_alert('Bạn phải chọn lý do hết hạn');
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
            show_alert('Số ktv và số sao không được trống');
        } else if(!par.code){
            show_alert('Mã code đang bị trống');
        } else {
            run = true;
        }
    }
    else if(nameaction == 'detailcoso') {
        let slug = item ? item.data('slug') : respon.slug;
        par.id = id;
        run = true;
    }
    else if(nameaction == 'detaildeal') {
        let slug = item ? item.data('slug') : respon.slug;
        par.id = id;
        run = true;
    }
    else if(nameaction == 'detailcombo') {
        let slug = item ? item.data('slug') : respon.slug;
        par.id = id;
        run = true;
    }
    else if(nameaction == 'slug') {
        let slug = item ? item.data('slug') : respon.slug;
        par.slug = slug;
        run = true;
    }
    else if(nameaction == 'getGiovang') {
        run = true;
    }
    else if(nameaction == 'chat_coso') {
        // par.id = id;
        if(id){
            if(!token) {
                alert('Bạn phải đăng nhập để nói chuyện với tiệm nhé');
                $('.tabfooter[data-tab="account"]').click();
            }else{
                loading.value = true;
                $('.logobar').addClass('hidden');
                let title = item ? item.data('title') : respon.title;
                let codelogin = !respon ? user.codelogin : respon.codelogin;
                let phone = !respon ? user.phone : respon.phone;
                // let divid = item.data('divid');
                let divid = current_tab == 'home' ? 'chatcoso' : 'chatcoso2';

                current_tabChild[current_tab].child = "#" + id;
                let src = `${domainchat}/chat/room/${id}?webapp=1&code=${codelogin}&phone=${phone}`;
                let iframe = `<iframe id="iframe_chatcoso" src="${src}" style="height: calc( 100% - 0px ); width: 100%;" frameborder="0"></iframe>`;
                $('#'+divid).empty().html(iframe);
                let iframe_chatcoso = 'iframe_chatcoso';
                // let src = `${domainchat}/chat/room/${id}?code=${user.codelogin}&phone=${user.phone}`;
                // alert(src);
                // window.open(src, '_blank').focus();
                
                console.log('src', src);
                // let src = 'https://laravel8.local/chat/room/'+id+'?webapp=1&'+uniqId();
                // console.log('iframe_chatcoso', iframe_chatcoso, token);
                // $('#'+iframe_chatcoso).attr('src',src);
                // document.getElementById(iframe_chatcoso).src = document.getElementById('iframe_chatcoso').src;
                
                setTimeout(() => {
                    var iframe = document.getElementById(iframe_chatcoso).contentWindow;
                    var message = {
                        type: "webapp",
                        value: {
                            token,
                            user,
                        }
                    };
                    iframe.postMessage(JSON.stringify(message), '*');
                    change_screen("#" + divid);
                    setNav({canBack: true, canSearch: false, canRefesh: false, fulliframe: true,title,showlogo: false});
                    loading.value = false;
                }, 300);
                $('body').scrollTop(0);
                // loading.value = false;
            }
        }
    }
    else if(nameaction == 'single') {
        let iditem = item.data('iditem');
        let slug = item.data('slug');
        if(id){
            if(iditem) run = true; // get bai viet = id
            par.id = iditem;
            let title = item.data('title');
            current_tabChild[current_tab].child = "#" + id;
            change_screen("#" + id);
            setNav({canBack: true, canSearch: false, canRefesh: false, showlogo:false, title});
            changeStateUrl({title, slug});
            // console.log('single', title, vargoobal);
        }
    }
    else if(nameaction == 'single_news') {
        if(id){
            let title = item.data('title');
            let slug = item.data('slug');
            let codehtml = item.data('html');
            current_tabChild[current_tab].child = "#" + id;
            $('#'+id).empty().html(codehtml);
            $('#'+id+' link').remove();
            change_screen("#" + id);
            setNav({canBack: true, canSearch: false, canRefesh: false,showlogo:false, title});
            changeStateUrl({title, slug});
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
        vargoobal.home.diachi_id = $('#diachi_id option:selected').val();
        vargoobal.home.tencoso = $('#tencoso').val();
        par.diachi_id = vargoobal.home.diachi_id;
        par.tencoso = vargoobal.home.tencoso;
        run = true;
    }
    else if(nameaction == 'getnews') {
        par.page = 1;
        run = true;
    }
    else if(nameaction == 'listgame') {
        run = true;
    }
    else if(nameaction == 'getvongquay') {
        if(!token) {
            show_alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            par.page = 1;
            console.log('doi luot quay');
            run = true;
        }
    }
    else if(nameaction == 'doiluotquay') {
        if(!token) {
            show_alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            run = true;
        }
    }
    else if(nameaction == 'single_spin') {
        if(!token) {
            show_alert('Bạn phải đăng nhập để thực hiện thao tác này');
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
        if(divitem.length){
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
    else if(nameaction == 'getCurAddress') {
        getCurAddress();
    }
    else if(nameaction == 'findnear') {
        if(varibles.lat && varibles.lng) {
            // console.log(11);
            par.lat = varibles.lat;
            par.long = varibles.lng;
            par.os = os;
            run = true;
        }
    }
    else if(nameaction == 'getcode') {
        let getcode = item.data('getcode');
        let type = item.data('type') ? item.data('type') : 'code';
        if(!getcode) {
            show_alert('Cơ sở này không thể lấy mã');
            return false;
        } else if( !token ) {
            show_alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            if(type == 'deal') par.deal_id = id;
            else if(type == 'combo') par.combo_id = id;
            else par.coso_id = id;
            par.type = type;
            par.nguon = os;
            run = true;
        }
    }
    else if(nameaction == 'checkPlay') {
        let game_id = item.data('id');
        // let type = item.data('type') ? item.data('type') : 'code';
        if( !token ) {
            show_alert('Bạn phải đăng nhập để thực hiện thao tác này');
            $('.tabfooter[data-tab="account"]').click();
        } else {
            par.game_id = game_id;
            par.nguon = os;
            run = true;
        }
    }
    else if(nameaction == 'lichsupoint') {
        // if(!$('#listlspoint').hasClass('active')) {
        //     let title = item.data('title');
        //     current_tabChild[current_tab].child = "#" + id;
        //     change_screen("#" + id);
        //     setNav({canBack: true, canSearch: true, canRefesh: false,title});
        // }
        par.page = 1;
        par.ngaybatdau = $('#ngaybatdaulsp').val();
        par.ngayketthuc = $('#ngayketthuclsp').val();
        par.tensearch = $('#tenlspointsearch').val();
        if(current_tabChild[current_tab].listlspoint_loaded) $('#listlspoint').empty();
        run = true;
    }
    else if(nameaction == 'searchlspoint') {
        $('#searchlspoint').addClass('active');
    }
    else if(nameaction == 'act_lichsupoint') {
        nameaction = 'lichsupoint';
        par.page = 1;
        vargoobal.account.ngaybatdaulspoint = $('#ngaybatdaulsp').val();
        vargoobal.account.ngayketthuclspoint = $('#ngayketthuclsp').val();
        vargoobal.account.tenlspointsearch = $('#tenlspointsearch').val();
        par.ngaybatdau = vargoobal.account.ngaybatdaulspoint;
        par.ngayketthuc = vargoobal.account.ngayketthuclspoint;
        par.tensearch = vargoobal.account.tenlspointsearch;
        run = true;
    }
    else if(nameaction == 'buyvip') {
        if(user.money >= 50){
            par.months = item.data('month');
            run = true;
        }else{
            let SKU_POINT = settings.SKU_POINT;
            let moneyvip = settings.moneyvip;
            let tygia = settings.tygia;
            let money1thang = parseInt(moneyvip[0]) / tygia;
            show_alert(`Bạn không đủ ${money1thang}${SKU_POINT} tối thiểu để nâng cấp Vip`);
        }
    }
    else if(nameaction == 'save_device') {
        if(respon.device){
            idDevice = respon.device;
            par.device = respon.device;
        }
        os = "web_" + getMobileOS();
        if(respon.token){
            par.token = respon.token;
        }
        if(respon.hasNotch) $('body').addClass("hasNotch");
        run = true;
        // console.log(par);
        // alert(os);
        // alert('save_device action: ' +par.device);
    }
    else if(nameaction == 'refresh') {
        par.page = 1;
        let child = current_tabChild[current_tab].child;
        nameaction = child;
        if(user && nameaction == 'accountlogin') nameaction = 'user';
        if(user && nameaction == 'listgame') nameaction = 'listgame';
        if(user && nameaction == 'listspin') nameaction = 'listspin';
        if(user && nameaction == 'getvongquay') nameaction = 'getvongquay';
        // console.log('refresh', nameaction, child, par);

        if(!nameaction){
            show_alert('Không tìm thấy thao tác cần thực hiện');
        } else {
            current_tabChild[current_tab][child + '_page'] = 0;
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
            show_alert('Tên, email, địa chỉ, ngày sinh không được trống');
            return false;
        } else if(!isEmail(par.email)){
            show_alert('Email không đúng định dạng');
            return false;
        }
        if(par.password && !sosanhpass(par.password, par.password2)) {
            show_alert('Mật khẩu xác nhận không chính xác')
            return false;
        }
        run = true;
    }
    else if(nameaction == 'nguoigioithieu') {
        // alert('login action ', getCookie('idDevice'));
        show_alert (user.nguoigioithieu ? `Người giới thiệu của bạn là: ${user.nguoigioithieu}` : 'Bạn không có người giới thiệu');
    }
    else if(nameaction == 'view_danhgiacode') {
        let getcode = item.data('getcode');
        let type = item.data('type') ? item.data('type') : 'code';
        $('.tabfooter[data-tab="account"]').click();
        $('.action[data-nameaction="listcode"]').click();
    }
    else if(nameaction == 'back') {
        let his = vargoobal[current_tab].history;
        if(his && his[0] && his[1]){
            his = removeLastArr(his);
            let index = his.length ? his.length - 1 : 0 ;
            vargoobal[current_tab].history = his;
            let div = his[index].div;
            let item = his[index].nav;
            change_screen(div);
            setNav(item);
            changeStateUrl({title: item.title, slug: item.slug});
            console.log('back item',index, div, item);

            // getSelectorCurDiv().empty();
            $('.tabchild.modal.active').removeClass('active');
        } else {
            show_alert('Lỗi chức năng, bạn hãy tải lại ứng dụng nhé');
        }
        return false;
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
                // if(nameaction == 'logout') logout();
            }else{
                show_alert(res.msg ? res.msg : 'Lỗi kết nối');
                // console.log('res getData', res);
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
        if(!html) html += `<p class="col-md-12">Không tìm thấy cơ sở nào với tiêu chí của bạn</p>`;
        getSelectorCurDiv().empty();
        getSelectorCurDiv().append(html + `<div class="clearfix"></div>`);
        $("input.rating").rating({
            filled: 'fa fa-star',
            empty: 'fa fa-star-o xam'
        });
    } else if(name == 'home') {
        if(last_res.settings) settings = last_res.settings;
        
        let phone = settings.sdt.replace(/\./g, "");
        $('#linksp_fb').attr('href',settings.fanpage);
        $('#linksp_zalo').attr('href',settings.sdtzalo);
        $('#linksp_hotline, .linkhotline').attr('href','tel:'+phone);
        $('.linkhotline').text(settings.sdt);
        
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
            let motinslider = settings.motinslider;
            html += '<div class="slider owl-theme owl-carousel">';
            settings.slider.map((item, index) => {
                let link = '';
                // console.log(index, motinslider[index]);
                if(motinslider[index]) link += `<a data-nameaction="single" data-iditem="${motinslider[index]}" data-id="singlenews_home" data-title="Đang tải tin..." class="action" href="#">`;
                let img = `${link}<img src="${item}" >`;
                if(motinslider[index]) img += `</a>`;
                html += img;
            });
            html += '</div><div class="" style="background: #f5f5f5; height: 10px;"></div>';
        }
        
        if(last_res && isObject(last_res) && last_res.giovangs.length){
            html += '<div class="col-md-12"><a class="btn btn-primary btn-block action mb-10px" data-nameaction="getGiovang">Giờ Vàng Hôm Nay</a></div>';
        }
        if(last_res && isObject(last_res) && last_res.deals.length){
            html += '<div class="wpdeals"><div class="wptitle-img col-md-12"><h3 class="hot">Hot Deal </h3><img src="/images/icon-deal-2.png" alt="deal"> <div class="clearfix"></div></div>';
            html += '<div class="owldeals owl-carousel owl-carousel">';
            
            last_res.deals.map((item, index) => {
                html += getHtml(item,'deal');
            });
            html += '</div></div>';
        }
        if(last_res && isObject(last_res) && last_res.combos.length){
            html += '<div class="wpcombos"><div class="title-home col-md-12">Combo</div>';
            html += '<div class="owlcombos owl-carousel owl-carousel">';
            last_res.combos.map((item, index) => {
                html += getHtml(item,'combo');
            });
            html += '</div></div>';
        }
        html += '<div class="wplistcoso">';
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
        html += '<div class="clearfix"></div></div>';
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
            dots: true,
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
        if($('.owldeals').length){
            $('.owldeals').owlCarousel({
                loop:false,
                dots: true,
                nav: false,
                margin:10,
                navText: ['', ''],
                autoplay:false,
                stagePadding: 70,
                // center:true,
                responsive:{
                    100:{
                        items:2,
                        stagePadding: 0,
                    },
                    414:{
                        items:2,
                        stagePadding: 40,
                    },
                    480:{
                        items:3,
                        stagePadding: 70,
                    },
                }
            });
            $('.deal').each(function(){
                let date = $(this).find('.countdown').data('ketthuc_at');
                if(date){
                    $(this).find('.countdown').countdown(`${date}`, function(event) {
                        $(this).html(event.strftime('<div class="item"><span>%Hh</span></div> <div class="item"><span>%Mm</span></div> <div class="item"><span>%Ss</span></div>'));
                        // $(this).html(event.strftime('<div class="item"><span>%d</span><p>Ngày<p></div> <div class="item"><span>%H</span><p>Giờ<p></div> <div class="item"><span>%M</span><p>Phút<p></div> <div class="item"><span>%S</span><p>Giây<p></div>'));
                    }).on('finish.countdown', function(event) {
                        console.log('đã xong countdown');
                        $(this).parents('.deal').addClass('hethan');
                    }).on('stop.countdown', function(event) {
                        console.log('stop countdown');
                        $(this).parents('.deal').addClass('hethan');
                    });
                }
            });
            let wpar = $('.wp-price:first').width();
            $(".animated-progress span").each(function () {
                $(this).css({width: $(this).attr("data-progress")});
            });
        }
        if($('.owlcombos').length){
            $('.owlcombos').owlCarousel({
                loop:false,
                dots: true,
                nav: false,
                margin:10,
                navText: ['', ''],
                autoplay:false,
                stagePadding: 10,
                // center:true,
                responsive:{
                    100:{
                        items:2,
                    },
                    414:{
                        items:2,
                    },
                    480:{
                        items:3,
                    },
                }
            });
        }
        
        $("input.rating").rating({
            filled: 'fa fa-star',
            empty: 'fa fa-star-o xam'
        });
        if(last_res.data.danhgiacode){
            $('#candanhagia').addClass('active');
        }
        
        let url = location.pathname.split('/').slice(1);
        let slug = url && url[0] ? url[0] : null;
        if(slug){
            doAction(null, {
                nameaction: 'slug',
                slug,
            });
        }detaildeal
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
        getSelectorCurDiv().find('.wplistcoso').append(html + `<div class="clearfix"></div>`);

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
            console.log('register', last_res.check_auto);
            if(last_res.check_auto == 0) change_gr('#grotp');
            else change_gr('#grlogin');
        }
        show_alert(last_res.msg ? last_res.msg : 'Bạn hãy nhập mã xác nhận')
    } else if(name == 'confirm') {
        if(last_res.status == 'success') {
            user = last_res.user;
            token = last_res.token;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            change_gr('#grlogin');
            loadinfoaccount(user);
        }
        show_alert(last_res.msg ? last_res.msg : 'Bạn đã xác nhận và đăng nhập thành công');
    } else if(name == 'login') {
        if(last_res.status == 'success'){
            token = last_res.token;
            user = last_res.user;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
            vargoobal.account.canRefesh = true;
            $('.refreshbtn').removeClass('hidden');
            $(`.tabcontent[data-tab="${current_tab}"] .tabchild`).removeClass('active');
            $(`.tabcontent[data-tab="${current_tab}"] #accountlogin`).addClass('active');
        }
        if(last_res.change_screen == 'otp'){
            change_gr('#grotp');
        }
        show_alert(last_res.msg);
    } else if(name == 'resetpass') {
        if(last_res.status == 'success') {
            change_gr('#grotpChangePass');
        }else show_alert(last_res.msg ? last_res.msg : 'Bạn đã xác nhận và đăng nhập thành công');
    } else if(name == 'confirmresetpass') {
        if(last_res.status == 'success') {
            change_gr('#grlogin');
            user = last_res.user;
            token = last_res.token;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
        }
        show_alert(last_res.msg ? last_res.msg : 'Bạn đã xác nhận và đăng nhập thành công');
    } else if(name == 'dklamcoso') {
        if(last_res.status == 'success') {
            change_gr('#grlogin');
            $('[name="namecoso"], [name="phonecoso"], [name="addresscoso"]').val('');
        }
        show_alert(last_res.msg ? last_res.msg : 'Bạn đã đăng ký thành công');
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
            if(last_res && isObject(last_res) && last_res.list && last_res.list.data && isArray(last_res.list.data) && last_res.list.data.length > 0 ){
                last_res.list.data.map((item, index) => {
                    html += getHtml(item, 'listcode');
                    saveId(item.id);
                });
                if(last_res.list.current_page == 1) getSelectorCurDiv().empty();
            } else if(last_res && isObject(last_res) && last_res.list && last_res.list.data && isObject(last_res.list.data) && Object.keys(last_res.list.data).length > 0 ){
                for (const key in last_res.list.data) {
                    html += getHtml(last_res.list.data[key], 'listcode');
                    saveId(last_res.list.data[key].id);
                }
                if(last_res.list.current_page == 1) getSelectorCurDiv().empty();
            } else{
                getSelectorCurDiv().empty();
                html += '<p class="col-md-12">Chưa có mã khuyến mãi nào gần đây</p>';
            }
            getSelectorCurDiv().append(html);
            current_tabChild[current_tab].child = name;
            
        } else show_alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'listfavourite') {
        if(last_res.status == 'success') {
            change_screen('#' + name);
            setNav({canBack: true, canSearch: false, canRefesh: true,title: 'Cơ Sở Yêu Thích'});
            user.dsyeuthich = last_res.favouriteIDs;
            
            if(last_res && isObject(last_res) && last_res.favourite.data && last_res.favourite.data.length){
                last_res.favourite.data.map((item, index) => {
                    html += getHtml(item);
                    saveId(item.id);
                });
            } else{
                getSelectorCurDiv().empty();
                html += '<p class="col-md-12">Chưa có cơ sở yêu thích nào</p>';
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
        } else show_alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'edituser') {
        if(last_res.status == 'success'){
            user = last_res.user;
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
            show_alert('Đã lưu thông tin thành công');
            // $('.backbtn').click();
        } else show_alert(last_res.msg);
    } else if(name == 'detailcoso') {
        if(last_res.status == 'success'){
            
            let divcoso = current_tab == 'home' ? 'detailcoso' : 'detailcoso2';
            let banggiave = current_tab == 'home' ? 'banggiave' : 'banggiave2';
            change_screen('#' + divcoso);
            let item = last_res.data;
            let data = {
                title: last_res.data.name,
                slug: last_res.data.alias
            }
            changeStateUrl(data);
            setNav({canBack: true, canSearch: false, canRefesh: false,showlogo:false, title: last_res.data.name, slug: last_res.data.alias});
            
            html += '<div class="slidersing owl-theme owl-carousel">';
            last_res.images.map((item2, index) => {
                html += `<div class="imgsing" style="background-image: url(${item2.linkimg})" ></div>`;
            });
            html += '</div>';
            html += '<div class="infosing"><div class="col-md-12">';
            html += `<h2 class="namesing">${item.name}</h2>`;
            html += `<div class="mb-10px">
                <span class="star mr-15px"><input type="hidden" data-filled="fa fa-star" data-empty="fa fa-star-o" class="rating" disabled="disabled" value="${item.star}"/></span>
                <a href="#" class="like action" data-nameaction="addfavourite" data-id="${item.id}">
                    <i class="fa red ${checklike(item.id) ? 'fa-heart' : 'fa-heart-o'}"></i>
                    <span class="red numberlike">${item.solike + item.likecongthem}</span> <span class="black">lượt thích</span>
                </a></div>
                `;
            let price_html = '';
            if(item.giamkhac) price_html += `<ins>${item.giamkhac}</ins>`;
            else{
                if(item.giatruockm) price_html += `<del>${numberWithCommas(item.giatruockm)}</del>`;
                price_html += `<ins>${numberWithCommas(item.giachinhthuc)}</ins>`;
            }
            
            html += `<div><span class="price" style="margin-right: 15px;">
                    ${price_html}
                </span>`;

            if(item.banggia) html += `<a href="#" class="btn btn-primary btn-xs action" data-nameaction="modal" data-id="${banggiave}">Xem Bảng Giá <i class="fa fa-long-arrow-right"></i></a>`;
            html += '</div></div><div class="clearfix"></div></div>';

            html += `<div class="beakline"></div>
                <div class="listitem"><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=${item.tdlat},${item.tdlong}"><i class="fa fa-map-marker ico"></i> ${item.diachi}</a> </div>
                <div class="listitem"><i class="fa fa-clock-o ogran ico"></i> ${item.giomocua}</div>
                <div class="listitem"><a href="tel:${item.phone}"><i class="fa fa-phone gren ico"></i> ${item.phone}</a></div>
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
            let idbanggia = current_tab == 'home' ? '#ndbanggia' : '#ndbanggia2';
            $(idbanggia).html(banggiahtml);
            if(os == 'web' && user && user.can_chat && last_res.data.hoptac && last_res.data.chat ) {
                html += `<a class="action btnchat vibration-icon" data-nameaction="chat_coso" data-divid="chatcoso" data-id="${item.id}" data-title="${item.name}" href="#"><i class="fa fa-comments"></i> <div class="animated infinite zoomIn kenit-alo-circle"></div> <div class="animated infinite pulse kenit-alo-circle-fill"></div></a>`;
            }
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
            $('body').scrollTop(0);
            
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'detailcombo') {
        if(last_res.status == 'success'){
            // let divcoso = current_tab == 'home' ? 'detailcoso' : 'detailcoso2';
            // let banggiave = current_tab == 'home' ? 'banggiave' : 'banggiave2';
            let SKU_POINT = settings.SKU_POINT;
            change_screen('#detailcombo');
            let item = last_res.data;
            let coso = item.coso;
            let data = {
                title: last_res.data.name,
                slug: last_res.data.alias
            }
            changeStateUrl(data);
            setNav({canBack: true, canSearch: false, canRefesh: false,showlogo:false, title: last_res.data.name, slug: last_res.data.alias});
            
            html += '<div class="slidersing owl-theme owl-carousel">';
            last_res.images.map((item2, index) => {
                html += `<div class="imgsing" style="background-image: url(${item2.linkimg})" ></div>`;
            });
            html += '</div>';
            html += '<div class="infosing"><div class="col-md-12">';
            html += `<h2 class="namesing">${item.name}</h2>`;
            html += `<span class="btn btn-xs btn-danger mb-5px mr-5px" >Combo ${item.sove} vé</span>`;
            html += `<span class="btn btn-xs btn-default mb-5px" >Cơ sở: ${coso.name}</span>`;
            html += `<div class="mb-10px">
                <span class="star mr-15px"><input type="hidden" data-filled="fa fa-star" data-empty="fa fa-star-o" class="rating" disabled="disabled" value="${coso.star}"/></span>
                <a href="#" class="like action" data-nameaction="addfavourite" data-id="${coso.id}">
                    <i class="fa red ${checklike(item.id) ? 'fa-heart' : 'fa-heart-o'}"></i>
                    <span class="red numberlike">${coso.solike + coso.likecongthem}</span> <span class="black">lượt thích</span>
                </a></div>
                `;
            html += `<div><span class="price" style="margin-right: 15px;">
                    <del>${numberWithCommas(item.giatruockm) + SKU_POINT}</del>
                    <ins>${numberWithCommas(item.giachinhthuc) + SKU_POINT}</ins>
                </span>`;

            // if(item.banggia) html += `<a href="#" class="btn btn-primary btn-xs action" data-nameaction="modal" data-id="${banggiave}">Xem Bảng Giá <i class="fa fa-long-arrow-right"></i></a>`;
            html += '</div></div><div class="clearfix"></div></div>';

            html += `<div class="beakline"></div>
                <div class="listitem"><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=${coso.tdlat},${coso.tdlong}"><i class="fa fa-map-marker ico"></i> ${coso.diachi}</a> </div>
                <div class="listitem"><i class="fa fa-clock-o ogran ico"></i> ${coso.giomocua}</div>
                <div class="listitem"><a href="tel:${coso.phone}"><i class="fa fa-phone gren ico"></i> ${coso.phone}</a></div>
                `;
            if(coso.thongtincoban.length && coso.thongtincoban[0].name) {
                html += `<div class="beakline"></div>`;
                html += `<h2 class="titlesing col-md-12">Thông Tin Cơ Bản</h2>`;
                coso.thongtincoban.map((item2, index) => {
                    if(item2.name && item2.diengiai) html += `<div class="listitem ttcoban"><span class="name">${item2.name}</span> <span>${item2.diengiai}</span></div>`;
                });
            }
            if(item.note) {
                html += `<div class="beakline"></div>`;
                html += `<div class="content">${item.note}</div>`;
            }
            html += `<div class="col-md-12 wpbtnbook">${showBtnGetcode(coso, 'combo', item)}</div>`;

            if(os == 'web' && user && user.can_chat && coso.hoptac && coso.chat) {
                html += `<a class="action btnchat vibration-icon" data-nameaction="chat_coso" data-divid="chatcoso" data-id="${coso.id}" data-title="${coso.name}" href="#"><i class="fa fa-comments"></i> <div class="animated infinite zoomIn kenit-alo-circle"></div> <div class="animated infinite pulse kenit-alo-circle-fill"></div></a>`;
            }
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
            $('body').scrollTop(0);
            
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'detaildeal') {
        if(last_res.status == 'success'){
            let divcoso = current_tab == 'home' ? 'detaildeal' : 'detaildeal2';
            change_screen('#' + divcoso);
            let item = last_res.data;
            let data = {
                title: last_res.data.title,
                slug: last_res.data.alias
            }
            // changeStateUrl(data);
            setNav({canBack: true, canSearch: false, canRefesh: false,showlogo:false, title: last_res.data.name, slug: last_res.data.sku});
            
            html += '<div class="slidersing owl-theme owl-carousel">';
            html += `<div class="imgsing" style="background-image: url(${item.link_image})" ></div>`;
            if(last_res.data.orther_info && last_res.data.orther_info.album){
                console.log(last_res.data.orther_info.album);
                let album = last_res.data.orther_info.album;
                // last_res.data.orther_info.album.map((item2, index) => {
                //     html += `<div class="imgsing" style="background-image: url(${item2.item2})" ></div>`;
                // });
                for (var key of Object.keys(album)) {
                    html += `<div class="imgsing" style="background-image: url(${album[key]})" ></div>`;
                    // console.log(key + " -> " + album[key])
                }
            }
            html += '</div>';
            html += '<div class="infosing"><div class="col-md-12">';
            html += `<h2 class="namesing">${item.title}</h2>`;
            html += `<div class="diachi mute mb-10px">${item.coso.diachi}</div>`;
            html += `<div class="countdown countdownsing mb-5px"  data-ketthuc_at="${item.ketthuc_at}"></div>`;
            
            let can = item.quality_getted < item.quality_limit ? true : false;
            let per = percentage(item.quality_getted, item.quality_limit);
            
            html += `
            <div class="animated-progress progress-blue single">
                <span class="txt">${item.quality_getted > 0 ? `Đã lấy <span class="txtsl" data-id="${item.id}">` + item.quality_getted + '</span>/' + item.quality_limit: 'Đang hot'}</span>
                <span class="progress" data-progress="${per}%"></span>
            </div>`;
            // if(can) html += `<div class="text-success mb-5px txtsl">Số lượng deal <span class="quality_getted">${item.quality_getted}</span>/${item.quality_limit}</div>`;
            // else html += `<div class="text-danger mb-5px">${item.quality_limit} deal đã hết</div>`;
            html += `<div><span class="price" style="margin-right: 15px;">
                <del>${item.giatruockm ? numberWithCommas(item.giatruockm) : ''}</del>
                <ins>${item.giachinhthuc ? numberWithCommas(item.giachinhthuc) : '0đ'}</ins>
            </span>`;

            html += '</div></div><div class="clearfix"></div></div>';
            html += `<div class="beakline"></div>`;
                
            if(item.content) {
                html += `<div class="content">${item.content}</div>`;
            }
            html += `<div class="col-md-12 wpbtnbook">${showBtnGetcode(item, 'deal')}</div>`;

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
            let date = $('.countdownsing').data('ketthuc_at');
            if(date){
                $('.countdownsing').countdown(`${date}`, function(event) {
                    $(this).html(event.strftime('<div class="item"><span>%Hh</span></div> <div class="item"><span>%Mm</span></div> <div class="item"><span>%Ss</span></div>'));
                    // $(this).html(event.strftime('<div class="item"><span>%d</span><p>Ngày<p></div> <div class="item"><span>%H</span><p>Giờ<p></div> <div class="item"><span>%M</span><p>Phút<p></div> <div class="item"><span>%S</span><p>Giây<p></div>'));
                }).on('finish.countdown', function(event) {
                    // console.log('đã xong countdown');
                    $(this).parents('.deal').addClass('hethan');
                    $('.btn[data-type="deal"]').removeClass('action');
                    $('.txtsl').text(`Deal đã hết hạn`);
                }).on('stop.countdown', function(event) {
                    // console.log('stop countdown');
                    $(this).parents('.deal').addClass('hethan');
                    $('.btn[data-type="deal"]').removeClass('action');
                    $('.txtsl').text(`Deal đã hết hạn`);
                });
            }
            // console.log(date);
            $(".animated-progress.single span").each(function () {
                $(this).css({width: $(this).attr("data-progress")});
            });
            $('body').scrollTop(0);
            
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'slug') {
        if(last_res.status == 'success'){
            if(last_res.type == 'coso') {
                let divcoso = current_tab == 'home' ? 'detailcoso' : 'detailcoso2';
                let banggiave = current_tab == 'home' ? 'banggiave' : 'banggiave2';
                change_screen('#' + divcoso);
                let item = last_res.data;
                let data = {
                    title: last_res.data.name,
                    slug: last_res.data.sku
                }
                changeStateUrl(data);
                setNav({canBack: true, canSearch: false, canRefesh: false,title: last_res.data.name});
                
                html += '<div class="slidersing owl-theme owl-carousel">';
                last_res.images.map((item2, index) => {
                    html += `<div class="imgsing" style="background-image: url(${item2.linkimg})" ></div>`;
                });
                html += '</div>';
                html += '<div class="infosing"><div class="col-md-12">';
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

                if(item.banggia) html += `<a href="#" class="btn btn-primary btn-xs action" data-nameaction="modal" data-id="${banggiave}">Xem Bảng Giá <i class="fa fa-long-arrow-right"></i></a>`;
                html += '</div></div><div class="clearfix"></div></div>';

                html += `<div class="beakline"></div>
                    <div class="listitem"><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=${item.tdlat},${item.tdlong}"><i class="fa fa-map-marker ico"></i> ${item.diachi}</a> </div>
                    <div class="listitem"><i class="fa fa-clock-o ogran ico"></i> ${item.giomocua}</div>
                    <div class="listitem"><a href="tel:${item.phone}"><i class="fa fa-phone gren ico"></i> ${item.phone}</a></div>
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
                let idbanggia = current_tab == 'home' ? '#ndbanggia' : '#ndbanggia2';
                $(idbanggia).html(banggiahtml);
                if(os == 'web' && user && user.can_chat && last_res.data.hoptac && last_res.data.chat ) {
                    html += `<a class="action btnchat vibration-icon" data-nameaction="chat_coso" data-divid="chatcoso" data-id="${item.id}" data-title="${item.name}" href="#"><i class="fa fa-comments"></i> <div class="animated infinite zoomIn kenit-alo-circle"></div> <div class="animated infinite pulse kenit-alo-circle-fill"></div></a>`;
                }
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
                $('body').scrollTop(0);
            } else if(last_res.type == 'news') {
                let id = 'singlenews_home';
                current_tabChild[current_tab].child = "#" + id;
                change_screen("#" + id);
                setNav({canBack: true, canSearch: false, canRefesh: false,title: last_res.data.name});
                
                $('h1').text(last_res.data.name);
                let data = {
                    title: last_res.data.name,
                    slug: last_res.data.alias
                }
                changeStateUrl(data);
                getSelectorCurDiv().empty().append(last_res.data.content);
                // console.log(namechild);
            } else if(last_res.type == 'deal') {
                console.log('deal');
            }
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'getcode') {
        if(last_res.status == 'success') {
            let div = current_tab == 'home' ? '#successbook' : '#successbook2';
            if(last_res.type == 'deal'){
                $('.quality_getted, .animated-progress .txtsl[data-id="'+last_res.code.id_target+'"]').text(last_res.data2.quality_getted);
            } else if(last_res.type == 'combo'){
            
            }
            $(div + ' .code').text(last_res.code.code);
            $(div + ' .hansudung').text(last_res.code.hethan);
            $(div + ' .txt').text(last_res.msg);
            $(div).addClass('active');

        } else {
            show_alert(last_res.msg);
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
                getSelectorCurDiv().empty();
                html += '<p class="col-md-12">Chưa có bài viết nào</p>';
            }
            getSelectorCurDiv().append(html);
            // console.log(namechild);
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'single') {
        if(last_res.status == 'success') {
            $('h1').text(last_res.data.name);
            let data = {
                title: last_res.data.name,
                slug: last_res.data.sku
            }
            changeStateUrl(data);
            getSelectorCurDiv().empty().append(last_res.data.content);
            // console.log(namechild);
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'listgame') {
        if(last_res.status == 'success') {
            
            setNav({canBack: false, canSearch: false, canRefesh: true, showlogo: false, title: "Game"});
            
            // console.log('listgame', last_res);
            // current_tabChild[current_tab][namechild + '_loadmore'] = false;
            // current_tabChild[current_tab][namechild + '_page'] += 1;
            // let page = current_tabChild[current_tab][namechild + '_page'];
            // if(last_res.list.current_page >= last_res.list.last_page) {
            //     current_tabChild[current_tab][namechild + '_maxpage'] = true;
            // }
            let html = `<div class="col-md-12 pt-15px"><a href="#" class="action itemgame" data-nameaction="getvongquay" data-id="getvongquay" data-title="Vòng Quay May Mắn">
                <img src="/images/banner-vong-quay.jpg" alt="vòng quay">
            </a></div>`;
            if(last_res && isObject(last_res) && last_res.list && last_res.list.length > 0){
                // console.log('listgame 2', last_res);
                
                last_res.list.map((item, index) => {
                    html += getHtml(item,'game');
                    saveId(item.id);
                });
            } else{
                $('#listgame').empty();
            }
            // console.log('html', html);

            $('#listgame').append(html);
            // console.log(namechild);
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'checkPlay') {
        let link = last_res.link_game;
        let txt = last_res.game.thele;
        let options = {};
        options.buttons = ['Không Chơi','Chơi Ngay'];
        // console.log('checkPlay', link);
        show_alert(txt, options, () => {
            window.open(link, '_blank').focus();
        });
    } else if(name == 'getvongquay') {
        if(last_res.status == 'success') {
            
            change_screen('#getvongquay');
            // let item = last_res.data;
            // let data = {
            //     title: last_res.data.name,
            //     slug: last_res.data.alias
            // }
            // changeStateUrl(data);
            setNav({canBack: true, canSearch: false, canRefesh: true, showlogo: false, title: "Vòng Quay May Mắn"});
            
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
            let btnMore = '';
            if(current_tabChild[current_tab][namechild + '_page'] == 0) $('#quydinhquay, #listquay').empty();
            $('#quydinhquay').append(restr).append(btnMore);
            
            // console.log('getvongquay', last_res);
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
                $('#listquay').empty();
                html += '<p class="col-md-12">Chưa có vòng quay nào</p>';
            }
            $('#listquay').empty().append(html);
            // console.log(namechild);
        } else {
            show_alert(last_res.msg);
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
            show_alert(last_res.msg);
        }
    } else if(name == 'lydohethan') {
        if(last_res.status == 'success') {
            $('#lydohethan').removeClass('active');
            $('.refreshbtn').click();
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'danhgiacode') {
        if(last_res.status == 'success') {
            $('#danhgiacode').removeClass('active');
            $('.refreshbtn').click();
        } else {
            show_alert(last_res.msg);
        }
    } else if(name == 'user') {
        if(last_res.status == 'success'){
            token = last_res.token;
            user = last_res.user;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            loadinfoaccount(user);
        }
    } else if(name == 'findnear') {
        if(last_res.status == 'success'){
            varibles.cosoquanhday = last_res.cosos;
            initMap($('#acf-map'));
        }
    } else if(name == 'logout') {
        if(last_res.status == 'success'){
            logout();
        }
    } else if(name == 'resentcode') {
        show_alert(last_res.msg);
    } else if(name == 'lichsupoint') {
        if(last_res.status == 'success') {
            change_screen('#' + name);
            setNav({canBack: true, canSearch: true, canRefesh: false,title: 'Lịch Sử Giao Dịch'});
            
            if(last_res && isObject(last_res) && last_res.points.data && last_res.points.data.length){
                last_res.points.data.map((item, index) => {
                    html += getHtml(item, 'lspoint');
                });
            } else{
                getSelectorCurDiv().empty();
                html += '<p class="col-md-12">Chưa có lịch sử giao dịch nào</p>';
            }
            current_tabChild[current_tab][namechild + '_page'] = 1;
            current_tabChild[current_tab][namechild + '_loadmore'] = true;
            current_tabChild[current_tab][namechild + '_page'] = last_res.points.current_page;
            current_tabChild[current_tab][namechild + '_loaded'] = true;
            if(last_res.points.last_page <= last_res.points.current_page) {
                current_tabChild[current_tab][namechild + '_maxpage'] = true;
            }
            if(last_res.points.current_page == 1) getSelectorCurDiv().empty();
            getSelectorCurDiv().append(html);
            current_tabChild[current_tab].child = name;

        } else show_alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'buyvip') {
        if(last_res.status == 'success') {
            user = last_res.user;
            loadinfoaccount(last_res.user);
        }
        show_alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'save_device') {
        if(last_res.status == 'success') {
            if(last_res.user){
                user = last_res.user;
                token = last_res.token;
                user.idDevice = last_res.idDevice;
                localStorage.setItem('token', token);
                localStorage.setItem('user', JSON.stringify(user));
                loadinfoaccount(user);
                vargoobal.account.canRefesh = true;
                // $('.refreshbtn').removeClass('hidden');
                $(`.tabcontent[data-tab="account"] .tabchild`).removeClass('active');
                $(`.tabcontent[data-tab="account"] #accountlogin`).addClass('active');
            }
            if(last_res.token) token = last_res.token;
            // show_alert(last_res.msg ? last_res.msg : 'Lưu thiết bị thành công');
        } else show_alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    } else if(name == 'getGiovang') {
        if(last_res.status == 'success') {
            // current_tabChild[current_tab][namechild + '_loadmore'] = true;
            // current_tabChild[current_tab][namechild + '_page'] += 1;
            // let page = current_tabChild[current_tab][namechild + '_page'];
            // if(last_res.data.current_page >= last_res.data.last_page) {
            //     current_tabChild[current_tab][namechild + '_maxpage'] = true;
            // }
            if(last_res && isObject(last_res) && last_res.data.data && last_res.data.data.length > 0){
                last_res.data.data.map((item, index) => {
                    html += getHtml(item,'giovang');
                    // saveId(item.id);
                });
            } else{
                getSelectorCurDiv().empty();
                html += '<p class="col-md-12">Chưa có chương trình giờ vàng nào</p>';
            }
        } else show_alert(last_res.msg ? last_res.msg : 'Lỗi truy vấn thông tin');
    }
    
}
function checklike(id) {
    let check = false;
    if(!user) return false;
    if(!user.dsyeuthich) return false;
    if(IsJsonString(user.dsyeuthich)) user.dsyeuthich = JSON.parse(user.dsyeuthich);
    
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
function showBtnGetcode(item = null, type='coso', item2 = null) {
    if (!item) return '';
    if(type == 'coso') return `<a href="${item.getcode ? '#' : 'tel:' + item.phone}" class="btn btn-primary btn-block ${item.getcode ? 'action' : ''}" ${item.getcode ? 'data-nameaction="getcode" data-confirm="confirm"' : ''} data-id="${item.id}" data-getcode="${item.getcode}">
        <span>${item.getcode ? 'Lấy Mã Khuyến Mãi' : item.phone}</span>
    </a>`;
    if(type == 'deal'){
        let can = item.quality_getted < item.quality_limit ? true : false;
        return `<a href="#" class="btn ${ can ? 'btn-primary action' : 'btn-default'} btn-block" data-nameaction="getcode" data-type="deal" data-confirm="confirm" data-getcode="1" data-id="${item.id}">
            <span>${ can ? 'Lấy Mã Deal' : 'Đã Hết'}</span>
        </a>`;
    }
    if(type == 'combo') return `<a href="${item.getcode ? '#' : 'tel:' + item.phone}" class="btn btn-primary btn-block ${item.getcode ? 'action' : ''}" ${item.getcode ? 'data-nameaction="getcode" data-confirm="confirm"' : ''} data-id="${item2.id}" data-type="combo" data-getcode="${item.getcode}">
        <span>${item.getcode ? 'Mua Combo' : item.phone}</span>
    </a>`;
}
function show_alert(txt, arrj = {}, willdeleteFunc = (willDelete) => {}){
    if(!txt) return;
    let attr = {}
    attr.text = txt;
    if(arrj.buttons) attr.buttons = arrj.buttons; else attr.buttons = ["Đóng", null];
    
    // console.log('show_alert',attr, arrj);
    swal(attr).then((willDelete) => {
        if (willDelete) {
            willdeleteFunc();
        } else {
          
        }
    });
}
function getHtml(item = null, type = 'coso'){
    if(!item) return '';
    let html = '';
    if(type == 'coso') {
        let chat = '';
        if(os == 'web' && user && user.can_chat && item.hoptac && item.chat ) {
            chat += `<a class="action btnchat_item" data-nameaction="chat_coso" data-divid="chatcoso" data-id="${item.id}" data-title="${item.name}" href="#"><i class="fa fa-comments"></i> </a>`;
        }
        let price_html = '';
        if(item.giamkhac) price_html += `<ins>${item.giamkhac}</ins>`;
        else{
            if(item.giatruockm) price_html += `<del>${numberWithCommas(item.giatruockm)}</del>`;
            price_html += `<ins>${numberWithCommas(item.giachinhthuc)}</ins>`;
        }
        html = `<div class="col-md-12 mb-15px"><div class="coso">
            <a href="#" class="action wpimg" data-nameaction="detailcoso" data-title="${item.name}" data-id="${item.id}">
                <div class="img" style="background-image: url(${item.image})"></div>
            </a>
            <div class="mainitemcoso">
                <a href="#" class="action" data-nameaction="detailcoso" data-title="${item.name}" data-id="${item.id}">
                    <h3 class="name">${item.name}</h3>
                    <div class="star">
                        <input type="hidden" data-filled="fa fa-star" data-empty="fa fa-star-o" class="rating" disabled="disabled" value="${item.star}"/>
                    </div>
                    <div class="price">${price_html}</div>
                    <div class="diachi mb-10px">${item.diachi}</div>
                </a>
                ${chat}
                <a href="#" class="like action" data-nameaction="addfavourite" data-id="${item.id}">
                    <i class="fa ${checklike(item.id) ? 'fa-heart' : 'fa-heart-o'}"></i>
                    <p class="numberlike">${item.solike + item.likecongthem}</p>
                </a>
            </div>
            ${showBtnGetcode(item)}
            <div class="clearfix"></div>
        </div></div>`;
    }
    if(type == 'listcode') {
        let bgtop = '';
        let note = '';
        let btntype = '';
        if(item.type === 4){
            btntype += `<span class="btn btn-xs btn-success btntype">Combo</span>`;
            // btntype += `<span class="btn btn-xs btn-danger detailcode">Xem chi tiết</span>`;
            // note = ` (mã combo)`;
        }else if(item.type === 5){
            btntype += `<span class="btn btn-xs btn-success btntype">Lượt Dùng Combo</span>`;
            // note = ` (mã combo)`;
        }else if(item.type === 3){
            btntype += `<span class="btn btn-xs btn-danger btntype">Deal</span>`;
            // note = ` (mã combo)`;
        }
        if (item.danhgia) {
            bgtop += `<a href="#" data-id="danhgiacode" data-nameaction="danhgiacode" data-code="${item.code}" class="bgoverflow action">Nhấp vào đây để cho ý kiến về dịch vụ đã sử dụng ${note}</a>`;
        } else if (item.star > 0) {
            bgtop += `<div class="bgoverflow">Cảm ơn bạn đã đánh giá</div>`;
        } else if (item.ykienhethan && item.star === 0) {
            bgtop += `<a href="#" class="bgoverflow pink action" data-id="lydohethan" data-code="${item.code}" data-nameaction="lydohethan">Mã khuyến mãi đã hết hạn. Hãy cho chúng tôi biết lý do nhé  ${note}!</a>`;
        } else {
            bgtop += `<a href="#" class="bgoverflow transparent action" data-id="showqrcode" data-code="${item.code}" data-nameaction="showqrcode"></a>`;
        }
        html += `<div class="col-md-12 mb-15px">${bgtop}<div class="coso code type${item.type}">
            ${btntype}
            <a href="#" class="action" data-name="detailcoso">
                <div class="wpimg">
                    <div class="img" style="background-image: url(${item.anhcoso})"></div>
                </div>
                <div class="rightnd mainitemcoso">
                    <h3 class="code">${item.code}</h3>
                    <h3 class="name">${item.tencoso}</h3>
                    <div class="diachi">${item.dccoso}</div>`;
        // html += `<div class="dasdMycode mb-5px">Đã sử dụng</div>`;
        
        console.log('coode', item);
        if(item.status === 0) {
            html += `<div class="hancodeMycode">Hạn sử dụng: ${item.hethan}</div>`;
            if(item.type == 0) html += `<div class="hancodeMycode mb-5px">Khi hết hạn bạn vui lòng tạo mã mới</div>`;
            else if(item.type == 4){
                html += `<div class="red mb-5px">Đã sử dụng ${item.orther_info.dalay_combo}/${item.orther_info.sove_combo}</div>`;
            }
        } else if(item.status === 10) {
            html += `<div class="dasdMycode mb-5px">Đã sử dụng</div>`;
        } else if(item.status === 9) {
            html += `<div class="hethan mb-5px">Đã hết hạn</div>`;
        }
        html += `</div><div class="clearfix"></div></a>
        </div></div>`;
    }
    if(type == 'news') {
        vargoobal[current_tab].loaded = true;
        let bgtop = '';
        html += `<div class="col-md-12 mb-15px"><div class="news">
            <a href="#" data-slug="${item.alias}" class="action" data-title="${item.name}" data-nameaction="single_news" data-id="singlenews" data-iditem="${item.id}" data-html='${item.content}'>
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
        if (item.status === 10) {
            let statusc = {
                mau : '#e9fcdb',
                title : 'Trúng thưởng',
                sub : 'Đã sử dụng',
            };
            if(!item.checkhethan && !item.nhangiai) {
                statusc.mau = '#f9e4c5';
                statusc.title = 'Chờ nhận giải';
                statusc.sub = 'Chờ nhận giải (hạn SD: '+item.thoihannhangiai+')';
            }
            if(!item.nhangiai && !item.checknhangiai) {
                statusc.mau = '#fcdbe3';
                statusc.title = 'Hết hạn nhận giải';
                statusc.sub = 'Hạn nhận ' + item.thoihannhangiai;
            }
            html += `<div class="vongquay trunggiai" style="background-color: ${statusc.mau};">
                <b class="name">${item.sku}</b>
                <span class="trangthai">${ item.checknhangiai ? 'Trúng thưởng' : 'Hết hạn sử dụng'}</span>
                <p>${statusc.sub} : ${item.tengiai}</p>
                <div class="clearfix"></div>
            </div>`;
        } if (item.thoihanquay && item.checkhethan) {
            html += `<div class="vongquay" style="background-color: #fcdbe3;">
            <div href="#" class="" data-title="${item.name}" data-nameaction="single_news" data-id="singlespin" >
                <b class="name">${item.sku}</b>
                <span class="trangthai">Hết hạn quay</span>
                <p>Thời hạn quay: ${item.thoihanquay}</p>
                <div class="clearfix"></div>
            </div></div>`;
        } else if (item.status === 0) {
            html += `<div class="vongquay">
            <a href="#" class="action block" data-note="Quay trên webview" data-bill="${item.sku}" data-title="Vòng Quay May Mắn" data-nameaction="single_spin" data-id="singlespin" >
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
    if(type == 'lspoint') {
        vargoobal[current_tab].loaded = true;
        html += `<div class="col-md-12 mb-15px">`;
        let SKU_POINT = settings.SKU_POINT;
        let bg = item.point > 0 ? '#d2fbcf' : '#fbcfe3';
        let color = item.point > 0 ? '#3a7d21' : '#ff0000';
        let char = item.point > 0 ? '+' : '';
        html += `<div class="lspoint" style="background-color: ${bg}; color: ${color}">
        <div href="#" class="" data-title="${item.name}" data-nameaction="single_news" data-id="singlespin" >
            <div>
                <b class="name pull-left">${char}${convertnumber(item.point)}${SKU_POINT}</b>
                <span class="pull-right ngay">${item.ngay}</span>
                <div class="clearfix"></div>
            </div>
            <p>${item.lydo}</p>`;

        if(item.point < 0) html += `<p>Số ${SKU_POINT} còn lại: ${convertnumber(item.point_cur)}</p>`;
        html += `<div class="clearfix"></div>
        </div></div>`;
        html += `</div>`;
    }
    if(type == 'giovang') {
        let bgtop = '';
        if (item.tinhtrang === 'xong') {
            bgtop += `<a href="#" class="bgoverflow pink">Đã hết hạn</a>`;
        } else if (item.tinhtrang === 'het') {
            bgtop += `<a href="#" class="bgoverflow pink" >Đã hết vé</a>`;
        }
        html += `<div class="col-md-12 mb-15px">${bgtop}<div class="coso code ${item.type != 1 ? 'hot' : ''}">
            <a href="#" class="action" data-name="detailcoso">
                <div class="wpimg">
                    <div class="img" style="background-image: url(${item.anhcoso})"></div>
                </div>
                <div class="rightnd mainitemcoso">
                    <h3 class="name">${item.tencoso}</h3>
                    <div class="diachi">${item.dccoso}</div>`;
        // if (item.status === 0) {
        //     html += `<div class="hancodeMycode">Hạn sử dụng: ${item.hethan}</div>
        //             <div class="hancodeMycode mb-5px">Khi hết hạn bạn vui lòng tạo mã mới</div>
        //     `;
        // } else if(item.status === 10) {
        //     html += `<div class="dasdMycode mb-5px">Đã sử dụng</div>`;
        // } else if(item.status === 9) {
        //     html += `<div class="hethan mb-5px">Đã hết hạn</div>`;
        // }
        html += `<div class="mb-5px">Click để lấy vé ngay</div>`;
        html += `</div><div class="clearfix"></div></a>
        </div></div>`;
    }
    if(type == 'deal') {
        let per = percentage(item.quality_getted, item.quality_limit);
        let del = item.giatruockm ? `<del>${numberWithCommas(item.giatruockm)}</del>` : '';
        html = `<div class="deal">
            <a href="#" class="action wpimg" data-nameaction="detaildeal" data-title="${item.name}" data-id="${item.id}">
                <div class="img" style="background-image: url(${item.link_image})"></div>
            </a>
            <div class="mainitemdeal">
                <div class="countdown" data-ketthuc_at="${item.ketthuc_at}"></div>
                <a href="#" class="action" data-nameaction="detaildeal" data-title="${item.name}" data-id="${item.id}">
                    <p class="namecoso">${item.coso ? item.coso.name : 'Không xác định'}</p>
                    <h3 class="name">${item.title}</h3>
                    <div class="wp-price">
                        <div class="price">
                            ${del}
                            <ins>${item.giachinhthuc ? numberWithCommas(item.giachinhthuc) : '0đ'}</ins>
                        </div>
                        <span class="btn btn-xs btn-danger pull-right">Lấy Deal</span>
                        <div class="clearfix"></div>
                    </div>
                    <div class="animated-progress progress-blue">
                        <span class="txt">${item.quality_getted > 0 ? 'Đã lấy <span class="txtsl" data-id="${item.id}">' + item.quality_getted + '</span>': 'Đang hot'}</span>
                        <span class="progress" data-progress="${per}%"></span>
                    </div>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>`;
    }
    if(type == 'combo') {
        let SKU_POINT = settings.SKU_POINT;
        let del = item.giatruockm ? `<del>${numberWithCommas(item.giatruockm)+SKU_POINT}</del>` : '';
        html = `<div class="combo">
            <a href="#" class="action wpimg" data-nameaction="detailcombo" data-title="${item.name}" data-note='${item.note}' data-id="${item.id}">
                <div class="img" style="background-image: url(${item.link_image})"></div>
            </a>
            <div class="mainitemcombo">
                <a href="#" class="action" data-nameaction="detailcombo" data-title="${item.name}" data-id="${item.id}">
                    <p class="namecoso">${item.coso ? item.coso.name : 'Không xác định'}</p>
                    <h3 class="name">${item.name}</h3>
                    <div class="wp-price">
                        <div class="price">
                            ${del}
                            <ins>${item.giachinhthuc ? numberWithCommas(item.giachinhthuc)+SKU_POINT : '0'+SKU_POINT}</ins>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <span class="btn btn-xs btn-danger discount">-${Math.round(100 - percentage(item.giachinhthuc, item.giatruockm))}%</span>
                    <div class="animated-progress progress-blue">
                        <span class="txt">Tiết kiệm ${numberWithCommas(item.giatruockm - item.giachinhthuc) + SKU_POINT}</span>
                    </div>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>`;
    }
    if(type == 'game') {
        // let SKU_POINT = settings.SKU_POINT;
        html = `<div class="col-md-12 pt-15px"><a href="#" target="_blank" class="action itemgame" data-nameaction="checkPlay" data-id="${item.id}">
            <img src="${item.img}" alt="${item.name}">
            <span class="btn btn-xs btn-circle btn-danger">Dành Cho Vip Member</span>
        </a></div>`;
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
    // console.log(user);
    $('body').addClass('logged-in');
    $('#accountlogin .sopoint').text(numberWithCommas(user.money));
    $('#accountlogin .nameaccount').text(user.firstname);
    $('#accountlogin .sdtaccount').text(user.phone);
    $('#accountlogin .capaccount').text(user.capdo);
    $('#accountlogin .diemaccount').text(numberWithCommas(user.tongdiem));
    $('#accountlogin .hanaccount').text(user.endactive);
    $('.accountnotlogin').removeClass('active');
    $('#accountlogin').addClass('active');
    current_tabChild.account.child = 'accountlogin';

    let idcs = '#chinhsuathongtin';
    $(`${idcs} [name="name"]`).val(user.firstname);
    $(`${idcs} [name="address"]`).val(user.address);
    $(`${idcs} [name="ngaysinh"]`).val(user.ngaysinh);
    $(`${idcs} [name="email"]`).val(user.email);

    if(settings.huongdannaptien) $('#huongdannaptien').html(settings.huongdannaptien);
    if(settings.quyenloitv) $('#quyenloitv').html(settings.quyenloitv);
    $('#huongdannaptien link, #quyenloitv link').remove();
    // if(settings.huongdannaptien) $('.huongdannaptien').html(settings.huongdannaptien);
    
    $(`.tabcontent[data-tab="${name}"] .tabchild`).removeClass('active');
    $(`.tabcontent[data-tab="${name}"] #accountlogin`).addClass('active');
    $('.requireLogin').removeAttr('requireLogin');

    let moneyvip = settings.moneyvip;
    let tygia = settings.tygia;
    let SKU_POINT = settings.SKU_POINT;
    let money1thang = parseInt(moneyvip[1]) / tygia;
    let money6thang = parseInt(moneyvip[6]) / tygia;
    let money12thang = parseInt(moneyvip[12]) / tygia;
    let tk6 = money1thang * 6 - money6thang;
    let tk12 = money1thang * 12 - money12thang;
    $('.SKU_POINT').text(SKU_POINT);
    $('.money1thang').text(money1thang);
    $('.money6thang').text(money6thang);
    $('.money12thang').text(money12thang);
    $('.tk6').text(tk6);
    $('.tk12').text(tk12);

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
    $('body').removeClass('logged-in');
    $('#accountlogin').removeClass('active');
    $('.tabcontent .accountnotlogin').addClass('active');
    $('.refreshbtn').addClass('hidden');
    current_tabChild.account.child = 'accountnotlogin';
    $('.requireLogin').attr('requireLogin', 1);
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

// map
async function getCurAddress() {
       
    var is_chrome = /chrom(e|ium)/.test( navigator.userAgent.toLowerCase() );
    var is_ssl    = 'https:' == document.location.protocol;
    if( is_chrome && ! is_ssl ){
        return false;
    }
    if (!navigator.geolocation) {
        show_alert('Trình duyệt của bạn không hỗ trợ lấy vị trí hiện tại');
    }
    loading.value = true;

    /* HTML5 Geolocation */
    navigator.geolocation.getCurrentPosition(
        function( position ){
            // loading.value = false;
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var google_map_pos = new google.maps.LatLng( lat, lng );

            /* Use Geocoder to get address */
            var google_maps_geocoder = new google.maps.Geocoder();
            google_maps_geocoder.geocode(
                { 'latLng': google_map_pos },
                function( results, status ) {
                    // console.log(results);
                    if ( status == google.maps.GeocoderStatus.OK && results[0] ) {
                        varibles.lat = lat;
                        varibles.lng = lng;
                        varibles.address_geo_location = results[0].formatted_address;
                        doAction(null, {nameaction: 'findnear'})
                    }
                }
            );
        },
        function(error){ // fail cb
            console.log('checklocation error',error);
            if(error.code == 1){
                swal({
                    text: 'Hãy bật định vị để tìm massage quanh đây',
                    buttons: ['Đóng', null]
                    // buttons: ['Đóng', 'Bật Định Vị']
                }).then((willDelete) => {
                    if (willDelete) {
                        // navigator.geolocation.getCurrentPosition(success, error, options);
                        // getCurAddress();
                    }
                });
            }else{
                show_alert('Bị lỗi trong quá trình lấy vị trí');
            }
            loading.value = false;
        }
    );
    // e.preventDefault();
}
function initMap( $el ) {
    var mapArgs = {
        zoom        : $el.data('zoom') || 16,
        mapTypeId   : google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map( $el[0], mapArgs );

    map.markers = [];
    // console.log(varibles.cosoquanhday);
    varibles.cosoquanhday.map( item => {
        initMarker( item, map );
    });
    centerMap( map );
    return map;
}
function initMarker( $marker, map ) {

    // Get position from marker.
    var lat = $marker.tdlat;
    var lng = $marker.tdlong;
    var latLng = {
        lat: parseFloat( lat ),
        lng: parseFloat( lng )
    };

    // Create marker instance.
    var marker = new google.maps.Marker({
        position : latLng,
        map: map
    });

    // Append to reference for later use.
    map.markers.push( marker );

    // If marker contains HTML, add it to an infoWindow.
    var infowindow = new google.maps.InfoWindow({
        content: $marker.diachi + ""
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open( map, marker );
    });
}
function centerMap( map ) {

    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
        bounds.extend({
            lat: marker.position.lat(),
            lng: marker.position.lng()
        });
    });

    // Case: Single marker.
    if( map.markers.length == 1 ){
        map.setCenter( bounds.getCenter() );

    // Case: Multiple markers.
    } else{
        map.fitBounds( bounds );
    }
}

// function
function getMobileOS(){
    const ua = navigator.userAgent
    if (/android/i.test(ua)) {
      return "android";
    }
    else if((/iPad|iPhone|iPod/.test(ua)) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)){
      return "ios";
    }
    return "web";
}
function gup( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}
function uniqId() {
  return Math.round(new Date().getTime() + (Math.random() * 100));
}
function removeLastArr(arr){
    if(arr.constructor !== Array) return false;
    const x = arr.pop();
    var arr2 = [];
    arr.map(item => {
        arr2[arr2.length] = item
    });
    return arr2;
}
function xfocus() {
    setTimeout(function() {
      height_old = window.innerHeight;
      window.addEventListener('resize', xresize);
    //   document.getElementById('status').innerHTML = 'opened'; // do something instead this
      $('body').addClass('kbopen');
    }, 500);
}
function xresize() {
    height_new = window.innerHeight;
    var diff = Math.abs(height_old - height_new);
    var perc = Math.round((diff / height_old) * 100);
    if (perc > 50) xblur();
}
function xblur() {
    window.removeEventListener('resize', xresize);
    $('body').removeClass('kbopen');
    // document.getElementById('status').innerHTML = 'closed'; // do something instead this
}
function percentage(partialValue, totalValue) {
    if(partialValue) partialValue = parseInt(partialValue.toString().replace(/[^\d.-]/g, ''));
    if(totalValue) totalValue = parseInt(totalValue.toString().replace(/[^\d.-]/g, ''));
    // console.log(partialValue, totalValue);
    return Math.round((100 * partialValue) / totalValue);
}