loading = 0;
$(document).ready(function(){
    $('#sidebar-menu .submenu > a').click(function(){
      return false;
    });
    $("#addCate").on('click', function(){
        $this = $(this).parent();
        var url = $this.attr('action');
        var _token = $this.find("input[name='_token']").val();
        var parent_id = $this.find(".parent_id").val();
        var txtCateName = $this.find("input[name='txtCateName']").val();
        var alias = $this.find("input[name='alias']").val();
        var txtKeyword = $this.find("input[name='txtKeyword']").val();
        var Description = $this.find("textarea[name='Description']").val();
        var spi = $(".parent_id option:selected").attr('text');
        if(txtCateName == '' || txtCateName == ' '){
            alert('the name category no empty');
            return false;
        }
        if (/\s/.test(alias)) {
            alert('alias not allow containt white space, please replace to "-"');
            return false;
        }

        $.ajax({
            "url": url,
            "type": "POST",
            "cache": false,
            "data": {"spi":spi, "_token":_token, 'url':url, 'parent_id':parent_id, 'txtCateName':txtCateName, 'alias':alias, 'txtKeyword':txtKeyword, 'Description':Description},
            "success": function(data){
                if($.isNumeric(data)){
                    if(data == 1) alert('the name category no empty');
                    else if(data == 2) alert('alias not allow containt white space, please replace to "-"');
                    else if(data == 3) alert("the name category exists, please choose orther name");
                    else if(data == 4) alert("this alias exists, please choose orther alias");
                    else if(data == 5) alert('not recevice data');
                }else{
                    var str = data.split('<!---->');

                    if(parent_id == 0){
                        $('#list-cates tbody').prepend(str[1]);
                    }
                    else{
                        $('#id'+parent_id).after(str[1]);
                    }

                    var option = '<option text="'+spi+'--" value="'+str[0]+'">'+spi + '--'+txtCateName+'</option>';
                    $(option).insertAfter($(".parent_id option:selected"));
                }
            }
        });
       return false;
    });
    $('#name').keyup(delay(function (e) {
      var str = $(this).val();
      ChangeToSlug(str,'#alias');
    }, 1500));
    $('#createAlias').click(function(){
        var str = $('#name').val();
        ChangeToSlug(str,'#generalalias');
        return false;
    });
    
    $('#alias,#generalalias').keyup(function(){
      if(loading==0){
        loading = 1;
        checkURL($(this));
        var token = $('[name="_token"]').val();
        var idnews = $('.idnews').val();
        var url = $('.urlcheck').val();
        var alias = $(this).val();
        checkalias(token,alias,idnews,url);
      }
        
    });

    $(".multi-dropdown .dropdown-menu a").click(function() {
      $(this).parents(".multi-dropdown").find('.text-multed').text($(this).text());
      $(this).parents(".multi-dropdown").find('.text-multed').val($(this).text());
      $('body').click();
      return false;
    });
    $('.xlRemove').click(function(event) {
        $('.removeUrl').each(function(){
            var vl = $(this).val();
            if(vl != '' && vl.indexOf('/public/') > -1){
                var val = vl.split("/public/");
                $(this).val(val[1]);
            }
        });
    });
});
function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
function checkalias(token, alias,id,url){
  $.ajax({
    type:'POST',
    url:url,
    data:{
        "_token":token,
        "alias":alias,
        "id":id
    },
    success:function(data){
      if(data.status==0){
        $('.erroralias').removeClass('allow').addClass('deny').text('Đường dẫn này đã tồn tại, bạn hãy điền lại đường dẫn khác.');
      }else if(data.status==1){
        $('.erroralias').removeClass('deny').addClass('allow').text('Đường dẫn này hợp lệ vì không trùng với các bài khác.');
      }
      loading = 0;
    }
  });
}
function searchtable(table,input,stt) {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById(input);
  filter = input.value.toUpperCase();
  table = document.getElementById(table);
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[stt];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
function goBack() {
    window.history.back();
}
function checkURL(slug){
    var abc = $(slug).val();
    if(abc.indexOf(' ') != -1){
        alert("Đường dẫn không được chứa các khoản trắng");
        abc = abc.toLowerCase();
        abc = abc.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
        abc = abc.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
        abc = abc.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
        abc = abc.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
        abc = abc.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
        abc = abc.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
        abc = abc.replace(/(đ)/g, 'd');
        abc = abc.replace(/([^0-9a-z-\s])/g, '');
        abc = abc.replace(/(\s+)/g, '-');
        abc = abc.replace(/^-+/g, '');
        abc = abc.replace(/-+$/g, '');
        $(slug).val(abc);
        return false;
    }
}
function ChangeToSlug(str, slug)
{
    // Chuyển hết sang chữ thường
    str = str.toLowerCase();
    // xóa dấu
    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    str = str.replace(/(đ)/g, 'd');

    // Xóa ký tự đặc biệt
    str = str.replace(/([^0-9a-z-\s])/g, '');
    // Xóa khoảng trắng thay bằng ký tự -
    str = str.replace(/(\s+)/g, '-');
    // xóa phần dự - ở đầu
    str = str.replace(/^-+/g, '');
    // xóa phần dư - ở cuối
    str = str.replace(/-+$/g, '');
    // return
    var token = $('[name="_token"]').val();
    var idnews = $('.idnews').val();
    var url = $('.urlcheck').val();
    checkalias(token,str,idnews,url);
    $(slug).val(str);
}

