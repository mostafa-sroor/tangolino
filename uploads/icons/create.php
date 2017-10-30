<?php
use yii\easyii\modules\catalog\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\helpers\Image;
use yii\widgets\ActiveForm;
use yii\easyii\modules\look\assets\TagsAsset;

$this->title = Yii::t('easyii', 'Create a tagged photo');
$this->registerAssetBundle(TagsAsset::className());
$asset = TagsAsset::register($this);
?>
<style>

    .alert {
        padding: 15px;
    }

    .alert-dismissable .close, .alert-dismissible .close {
        color: inherit;
        position: relative;
        right: -1px;
        top: -2px;
    }

    .close {
        color: #000;
        float: right;
        font-size: 21px;
        font-weight: bold;
        line-height: 1;
        opacity: 0.2;
        text-shadow: 0 1px 0 #fff;
    }
</style>
<script type="text/javascript">
    var current_item_id = '';
    var current_title = '';
    var current_checkout_link = '';
    $(document).ready(function () {


        $("#floating_block").click(function (e) {
            $('.container-fluid').prepend('<div class="alert alert-warning alert-dismissable">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
                'you should upload new photo before tagging it...</div>');
        });
        $("#imageMap").click(function (e) {
            if (current_checkout_link != '' && current_title != '') {
                var image_left = $(this).offset().left;
                var click_left = e.pageX;
                var left_distance = click_left - image_left;

                var image_top = $(this).offset().top;
                var click_top = e.pageY;
                var top_distance = click_top - image_top;

                var mapper_width = $('#mapper').width();
                var imagemap_width = $('#imageMap').width();

                var mapper_height = $('#mapper').height();
                var imagemap_height = $('#imageMap').height();

                if ((top_distance + mapper_height > imagemap_height) && (left_distance + mapper_width > imagemap_width)) {
                    $('#mapper').css("left", (click_left - mapper_width - image_left  ) + "px")
                        .css("top", (click_top - mapper_height - image_top  ) + "px")
                        .css("width", "100px")
                        .css("height", "100px")
                        .show();
                }
                else if (left_distance + mapper_width > imagemap_width) {
                    $('#mapper').css("left", (click_left - mapper_width - image_left  ) + "px")
                        .css("top", top_distance)
                        .css("width", "100px")
                        .css("height", "100px")
                        .show();

                }
                else if (top_distance + mapper_height > imagemap_height) {
                    $('#mapper').css("left", left_distance)
                        .css("top", (click_top - mapper_height - image_top  ) + "px")
                        .css("width", "100px")
                        .css("height", "100px")
                        .show();
                }
                else {
                    $('#mapper').css("left", left_distance)
                        .css("top", top_distance + "px")
                        .css("width", "100px")
                        .css("height", "100px")
                        .show();
                }

                $("#mapper").resizable({containment: "parent"});
                $("#mapper").draggable({containment: "parent"});

                addTag();
            }
            else {
                $('.playground').prepend('<div class="alert alert-warning alert-dismissable">' +
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
                    'you should select product before tagging it ...</div>');
            }
        });
    });

    function onmouseovertag(id) {
        console.log("over");
        if ($("#" + id).find(".openDialog").length == 0) {
            $("#" + id).find(".tagged_box").css("display", "block");
            $("#" + id).find(".tagged_title").css("display", "block");
            clicktag(id);
        }
    }

    function onmouseouttag(id) {
        console.log("out");
        if ($("#" + id).find(".openDialog").length == 0) {
            $("#" + id).find(".tagged_box").css("display", "none");
            $("#" + id).css("border", "none");
            $("#" + id).find(".tagged_title").css("display", "none");
        }
    }


    function clicktag(id) {
        $("#" + id).find(".tagged_box").html("<img src='<?php echo  Yii::getAlias('@web').Image::thumb('/uploads/icons/del.png') ;?>'  onmouseover='onmouseovertag(" + id + ")' onmouseout='onmouseouttag(" + id + ")'  style='z-index:100099201;position:absolute' onclick='deleteTag(this)'>");

        var img_scope_top = $("#imageMap").offset().top + $("#imageMap").height() - $(this).find(".tagged_box").height();
        var img_scope_left = $("#imageMap").offset().left + $("#imageMap").width() - $(this).find(".tagged_box").width();
        $("#" + id).draggable({containment: [$("#imageMap").offset().left, $("#imageMap").offset().top, img_scope_left, img_scope_top]});
    }


    function addTag() {

        if (current_checkout_link != '' && current_title != '') {
            var str = '';
            str = "<a href='" + current_checkout_link + "'>" + current_title + "</a>"
            var position = $('#imageMap').position();

            var pos_x = position.left + "px";
            var pos_y = position.top + "px";
            var pos_width = $('#imageMap').width() + "px";
            var pos_height = $('#imageMap').height() + "px";
            var id = makeid();
            $('#planetmap').append('<div class="tagged" id=' + id + '   style="width:' + pos_width + ';height:' +
                pos_height + ';left:' + pos_x + ';top:' + pos_y + ';" >' +
                '<img src="<?php echo  Yii::getAlias('@web').Image::thumb('/uploads/icons/mark.png') ;?>" onclick="deleteTag(this)" onmouseover="onmouseovertag(' + id + ')" onmouseout="onmouseouttag(' + id + ')" style="z-index:100099200;position:absolute" ><a  href="javascript:void(0)" title="' + current_title + '"  id="' + current_item_id + '" alt="' + current_title + '" ><div class="tagged_box"   style="display:none;" ></div></a><div class="tagged_title"  style="top:' + (pos_height + 5) + ';display:none;" >' +
                str + '</div></div>');

            $("#mapper").hide();
            $("#title").val('');
            $("#form_panel").hide();
        }

        current_checkout_link = '';
        current_title = '';
        current_item_id = '';
    }

    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return "'" + text + "'";
    }

    function openDialog() {
        $("#form_panel").fadeIn("slow");
    }

    function showTags() {
        $(".tagged_box").css("display", "block");
        $(".tagged").css("border", "5px solid #EEE");
        $(".tagged_title").css("display", "block");
    }

    function hideTags() {
        $(".tagged_box").css("display", "none");
        $(".tagged").css("border", "none");
        $(".tagged_title").css("display", "none");
    }

    function editTag(obj) {
        $(obj).parent().parent().draggable('disable');
        $(obj).parent().parent().removeAttr('class');
        $(obj).parent().parent().addClass('tagged');
        $(obj).parent().parent().css("border", "none");
        $(obj).parent().css("display", "none");
        $(obj).parent().parent().find(".tagged_title").css("display", "none");
        $(obj).parent().html('');
    }


    function deleteTag(obj) {
        $(obj).parent().remove();
    }
    ;

</script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<style>
    .btn-file {
        display: block;
        margin-left: auto;
        margin-right: auto;
        overflow: hidden;
        position: relative;
        text-align: right;
        width: 50%;
        text-align: center !important;
    }

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>
<style>

    .loader {
        bottom: 0;
        height: 175px;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 200px;
        width: 175px;
        z-index: 19000000;
    }

    .loader .dot {
        bottom: 0;
        height: 100%;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 0;
        width: 87.5px;
    }

    .loader .dot::before {
        border-radius: 100%;
        content: "";
        height: 87.5px;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        transform: scale(0);
        width: 87.5px;
    }

    .loader .dot:nth-child(7n+1) {
        transform: rotate(45deg);
    }

    .loader .dot:nth-child(7n+1)::before {
        animation: 0.8s linear 0.1s normal none infinite running load;
        background: #00ff80 none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+2) {
        transform: rotate(90deg);
    }

    .loader .dot:nth-child(7n+2)::before {
        animation: 0.8s linear 0.2s normal none infinite running load;
        background: #00ffea none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+3) {
        transform: rotate(135deg);
    }

    .loader .dot:nth-child(7n+3)::before {
        animation: 0.8s linear 0.3s normal none infinite running load;
        background: #00aaff none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+4) {
        transform: rotate(180deg);
    }

    .loader .dot:nth-child(7n+4)::before {
        animation: 0.8s linear 0.4s normal none infinite running load;
        background: #0040ff none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+5) {
        transform: rotate(225deg);
    }

    .loader .dot:nth-child(7n+5)::before {
        animation: 0.8s linear 0.5s normal none infinite running load;
        background: #2a00ff none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+6) {
        transform: rotate(270deg);
    }

    .loader .dot:nth-child(7n+6)::before {
        animation: 0.8s linear 0.6s normal none infinite running load;
        background: #9500ff none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+7) {
        transform: rotate(315deg);
    }

    .loader .dot:nth-child(7n+7)::before {
        animation: 0.8s linear 0.7s normal none infinite running load;
        background: magenta none repeat scroll 0 0;
    }

    .loader .dot:nth-child(7n+8) {
        transform: rotate(360deg);
    }

    .loader .dot:nth-child(7n+8)::before {
        animation: 0.8s linear 0.8s normal none infinite running load;
        background: #ff0095 none repeat scroll 0 0;
    }

    .loader .lading {
        background-image: url("../images/loading.gif");
        background-position: 50% 50%;
        background-repeat: no-repeat;
        bottom: -40px;
        height: 20px;
        left: 0;
        position: absolute;
        right: 0;
        width: 180px;
    }

    @keyframes load {
        100% {
            opacity: 0;
            transform: scale(1);
        }
    }

    @keyframes load {
        100% {
            opacity: 0;
            transform: scale(1);
        }
    }

    #products {
        height: 700px;
        margin-right: 0;
        overflow-y: scroll;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        font-weight: 400;
        color: #454545;
        line-height: 18px;
        text-transform: capitalize;
        background-color: #fff;
    }

    h1, h2, h3, h4, h5, h6 {
        color: #444;
    }

    /* default font size */
    .fa {
        font-size: 14px;
    }

    .fa-stack {
        width: 13px;
    }

    .fa-stack .fa {
        font-size: 15px;
    }

    /* Override the bootstrap defaults */
    h1 {
        font-size: 33px;
    }

    h2 {
        font-size: 27px;
    }

    h3 {
        font-size: 21px;
    }

    h4 {
        font-size: 15px;
    }

    h5 {
        font-size: 12px;
    }

    h6 {
        font-size: 10.2px;
    }

    a {
        color: #23a1d1;
    }

    a:hover {
        text-decoration: none;
    }

    legend {
        font-size: 18px;
        padding: 7px 0px
    }

    label {
        font-size: 12px;
        font-weight: normal;
    }

    select.form-control, textarea.form-control, input[type="text"].form-control, input[type="password"].form-control, input[type="datetime"].form-control, input[type="datetime-local"].form-control, input[type="date"].form-control, input[type="month"].form-control, input[type="time"].form-control, input[type="week"].form-control, input[type="number"].form-control, input[type="email"].form-control, input[type="url"].form-control, input[type="search"].form-control, input[type="tel"].form-control, input[type="color"].form-control {
        font-size: 13px;
    }

    .input-group input, .input-group select, .input-group .dropdown-menu, .input-group .popover {
        font-size: 12px;
    }

    .input-group .input-group-addon {
        font-size: 12px;
        height: 30px;
    }

    /* Fix some bootstrap issues */
    span.hidden-xs, span.hidden-sm, span.hidden-md, span.hidden-lg {
        display: inline;
    }

    .nav-tabs {
        margin-bottom: 15px;
    }

    div.required .control-label:before {
        content: '* ';
        color: #F00;
        font-weight: bold;
    }

    /* Gradent to all drop down menus */
    .dropdown-menu li > a:hover {
        text-decoration: none;
        color: #fff;
        background-color: #27b6af;
    }

    /* top */
    header {
        border-top: 5px solid #ff9cac;
        /*box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
        -moz-box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
        -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,.125);*/
    }

    .header_inner {
        float: left;
        margin: 25px 0;
        width: 100%;
    }

    .header_left {
        float: left;
        margin-top: 29px;
        width: 448px;
        border-left: 1px solid #e9e9e9;
    }

    #top-links .list-inline {
        margin-bottom: 0;
    }

    .header_right1, .header_right2 {
        float: right;
    }

    .header_right {
        float: right;
        width: 448px;
        margin-top: 29px;
    }

    .header_left_top {
        clear: both;
        float: left;
        padding: 0 0 10px 16px;
    }

    .header_right_bottom button span {
        text-transform: capitalize;
    }

    .header_right_top {
        float: right;
        padding-bottom: 4px;
        border-bottom: 1px solid #e9e9e9;
    }

    .header_center {
        float: left;
        text-align: center;
        width: 304px;
    }

    .header_right_bottom {
        clear: both;
        float: right;
        padding: 4px 16px 0 0;
    }

    .contact_no {
        font-weight: 400;
        text-transform: capitalize;
        padding-left: 20px;
        background: url(../image/megnor/call.png) no-repeat scroll 0px -27px transparent;
        color: #808080;
    }

    #top-links.pull-right {
        border-top: 1px solid #e9e9e9;
        clear: both;
        float: left;
        margin-top: 0;
        padding: 6px 0 0 16px;
    }

    #top {
        background-color: #EEEEEE;
        border-bottom: 1px solid #e2e2e2;
        padding: 4px 0px 3px 0;
        margin: 0 0 20px 0;
        min-height: 40px;
    }

    #top .container {
        padding: 0 20px;
    }

    #top #currency .currency-select {
        text-align: left;
    }

    #top #currency .currency-select:hover {
        text-shadow: none;
        color: #ffffff;
        background-color: #999999;
    }

    .contact_no span {
        margin-left: 10px;
    }

    #top-links ul li {
        padding: 0 16px 0 0;
        background: url(../image/megnor/call.png) no-repeat scroll right 8px transparent;
    }

    #top-links ul li:first-child {
        background-position: right 10px;
    }

    #top-links ul li:last-child {
        padding: 0;
        background: none;
    }

    #top-links .dropdown-menu.dropdown-menu-right.myaccount-menu > li {
        padding: 0;
        background: none;
    }

    #top-links .dropdown-menu.dropdown-menu-right.myaccount-menu > li a {
        padding: 3px 8px;
    }

    #currency .dropdown-menu {
        padding: 8px;
    }

    #currency button {
        padding: 0px 0px 0px 0px;
        padding: 0;
    }

    #currency {
        margin-left: 12px;
    }

    #language button {
        padding: 0px 0px 0px 0px;
        border-right: medium none;
    }

    #currency strong {
        font-weight: 400;
        color: #808080;
    }

    header .fa.fa-caret-down {
        font-size: 12px;
        margin: 0 0 0 3px;
    }

    #language img {
        margin: 0 0 2px;
    }

    #language .dropdown-menu > li > a {
        padding: 3px 8px;
    }

    #currency .dropdown-menu .btn {
        font-size: 13px;
        padding: 0px;
        text-transform: capitalize;
    }

    #language .dropdown-menu > li > a:hover, #currency button:hover, #currency button:hover strong {
        color: #c3c3c3;
    }

    #top .btn-link, #top-links li, #top-links a {
        color: #808080;
        text-decoration: none;
    }

    #top-links li {
        padding: 0;
    }

    #top-links a .fa {
        padding: 5px 5px;
    }

    #top .btn-link:hover, #top-links a:hover {
        color: #c3c3c3;
    }

    #top-links .dropdown-menu a {
        text-shadow: none;
    }

    #top-links .dropdown-menu a:hover {
        color: #c3c3c3;
    }

    #top .btn-link strong {
        font-size: 14px;
        font-weight: 400;
    }

    #top-links {
        padding-top: 6px;
    }

    #top-links a + a {
        margin-left: 15px;
    }

    /* logo */
    .header-logo {
        float: left;
    }

    .header-search {
        float: left;
    }

    .header-cart {
        float: right;
    }

    #logo {
        margin: 0 0 10px 0;
        display: inline-block;
    }

    /* search */
    #search {
        margin-bottom: 0px;
        margin-right: 10px;
    }

    .search {
        position: relative;
        float: left;
    }

    #search .input-lg {
        background: none repeat scroll 0 0 transparent;
        border: medium none;
        height: 25px;
        padding: 0px;
        position: relative;
        width: 200px;
        border-radius: 0;
        transition: all 1s ease 0s;
        -moz-transition: all 1s ease 0s;
        -ms-transition: all 1s ease 0s;
        -webkit-transition: all 1s ease 0s;
        -o-transition: all 1s ease 0s;
        box-shadow: none;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        text-transform: capitalize;
    }

    #search .input-group-btn {
        width: auto;
    }

    /*#search:hover .input-lg{width:190px;}*/
    #search .input-lg:focus, #search .input-lg:active {
        width: 200px;
    }

    #search .btn-lg {
        padding: 12px;
        background: url(../image/megnor/search.png) no-repeat scroll 5px 3px transparent;
        border: medium none;
    }

    /* cart */
    #cart {
        border-left: 1px solid #e9e9e9;
        float: right;
        padding: 0 0 21px 11px;
    }

    #cart > .btn {
        border: medium none;
        background: url(../image/megnor/sprite.png) no-repeat scroll 0px -273px transparent;
        padding: 18px 13px;
    }

    #cart.open > .btn {
        box-shadow: none;
        text-shadow: none;
    }

    #cart-total {
        color: #808080;
        font-size: 12px;
        font-weight: 700;
        left: 0;
        margin: 0 auto;
        position: absolute;
        right: 0;
        text-align: center;
        top: 45px;
    }

    #cart.open > .btn:hover {
        color: #444;
    }

    #cart .dropdown-menu {
        background: #fff;
        z-index: 1001;
    }

    #cart .dropdown-menu {
        width: 350px;
        padding: 10px;
        top: 97%;
    }

    #cart .button-container {
        display: inline-block;
        text-align: right;
        width: 100%;
    }

    #cart .dropdown-menu table {
        margin-bottom: 10px;
    }

    #cart .dropdown-menu table td {
        border: none;
        background: none;
    }

    #cart .dropdown-menu li > div {
    }

    #cart .dropdown-menu li p {
        margin-bottom: 0;
    }

    /*Mega Menu Start*/

    #menu ul {
        float: left;
        list-style: outside none none;
        margin: 0;
        padding: 0;
    }

    #menu > ul > li {
        float: left;
        position: relative;
        z-index: 20;
    }

    #res-menu {
        display: none;
    }

    #menu .categoryinner > ul {
        float: left;
    }

    #menu > ul > li > div {
        left: 0;
        min-width: 590px;
        padding: 30px;
        z-index: 12345;
        background: none repeat scroll 0 0 #fff;
        display: none;
        position: absolute;
        text-align: left;
        top: 68px;
        box-shadow: 0 0 4px 3px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 0 4px 3px rgba(0, 0, 0, 0.25);
        -webkit-box-shadow: 0 0 4px 3px rgba(0, 0, 0, 0.25);
    }

    #menu > ul > li.hiden_menu div {
        min-width: 180px;
    }

    #menu > ul > li:hover > div {
        display: block;
    }

    #menu .categoryinner ul > ul {
        float: left;
    }

    #menu ul {
        float: none;
        list-style: outside none none;
        margin: 0;
        padding: 0;
        display: inline-block;
    }

    .main-navigation ul:last-child .categorycolumn {
        margin-right: 0px;
    }

    .categorycolumn {
        float: left;
        margin-bottom: 0;
        margin-right: 25px;
        vertical-align: top;
    }

    a.submenu1, .level0 .level0 a {
        display: block;
        position: relative;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        border-bottom: 2px solid #e4e3e3;
        color: #22272a;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
        padding: 0 0 15px 8px;
        text-transform: uppercase;
    }

    a:hover.submenu1 {
        color: #1a1a1a;
    }

    #menu .cate_inner_bg > ul > li a {
        padding: 5px 0 5px 8px;
        color: #454545;
        font-size: 12px;
        font-weight: 400;
        display: block;
    }

    #menu .cate_inner_bg > ul > li a:hover {
        background: none repeat scroll 0 0 #f4f4f4;
        color: #22272a;
    }

    #menu > ul > li ul > li:hover > a {
        transition-duration: 500ms;
        -moz-transition-duration: 500ms;
        -o-transition-duration: 500ms;
        -webkit-transition-duration: 500ms;
        -ms-transition-duration: 500ms;
        color: #e85e5e;
    }

    .categorycolumn ul {
        float: left;
    }

    .cate_inner_bg ul {
        padding-top: 7px !important;
    }

    .categoryinner ul {
        padding: 0 !important;
    }

    .cate_inner_bg li {
        padding: 0;
    }

    #menu > ul > li ul > li ul > li {
        padding-right: 0px !important;
    }

    #menu > ul > li ul > li ul > li:hover {
    }

    #menu > ul > li ul > li > a {
        font-weight: 400;
        color: #a27c66;
        line-height: 18px;
        padding: 4px 6px;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
    }

    .submenu2 {
        border-bottom: 0 none;
        font-size: 11px;
        font-weight: 400;
        margin-left: 0;
        text-transform: uppercase;
    }

    #menu .hiden_menu .categoryinner > ul {
        display: none;
    }

    /*Mega Menu End*/

    /* menu */

    .nav-container {
        border-top: medium none;
    }

    .nav-container.fixed {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999;
        display: block;
    }

    #cart.fixed {
        position: fixed;
        right: 50px;
        top: 7px !important;
        z-index: 1040;
        border-left: medium none;
    }

    .nav-container.fixed, #cart.fixed {
        animation: 0.9s ease 0s normal both 1 running fixedAnim;
        -moz-animation: 0.9s ease 0s normal both 1 running fixedAnim;
        -o-animation: 0.9s ease 0s normal both 1 running fixedAnim;
        -webkit-animation: 0.9s ease 0s normal both 1 running fixedAnim;
    }

    #cart.fixed > .btn {
        background: url(../image/megnor/cart1.png) no-repeat scroll 0px 18px transparent;
    }

    #cart.fixed #cart-total {
        color: #fff;
        left: 17px;
        top: 7px;
    }

    #cart.fixed .dropdown-menu {
        top: 107%;
    }

    .nav-inner-container {
        background-color: #27b6af;
    }

    .nav-inner {
        text-align: center;
    }

    nav .container {
        padding: 0;
    }

    .nav-responsive {
        display: none;
    }

    .responsive-menu,
    .main-menu {
        background: #27b6af;
        height: 68px;
        margin-bottom: 0px;
        /*overflow:hidden; HIDE CATEGORIES THOSE ARE OUT OF MANU.  */
    }

    .main-menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .main-menu > ul > li {
        position: relative;
        float: left;
        z-index: 20;
        background-color: #434571;
    }

    .main-menu > ul > li:hover {
    }

    .nav-responsive span,
    .main-menu > ul > li > a {
        font-size: 13px;
        color: #fff;
        display: block;
        padding: 25px 20px;
        margin-bottom: 0px;
        z-index: 6;
        position: relative;
        font-weight: 700;
        text-transform: uppercase;
    }

    .main-menu a:hover {
        text-decoration: none
    }

    .main-menu > ul > li:hover > a {
        background: #434571;
        color: #ff9cac;
    }

    .main-menu > ul > li > ul, .responsive-menu .main-navigation {
        display: none;
        background: #fff;
        position: absolute;
        z-index: 5;
        padding: 5px;
    }

    .main-menu > ul > li:hover > ul {
        display: block;
    }

    .main-menu > ul > li ul > li > ul {
        display: none;
        background: #15BCF0;
        position: absolute;
        z-index: 5;
        padding: 5px;
        margin-left: -13px;
    }

    .main-menu > ul > li ul > li:hover > ul {
        display: block;
        top: 0px;
        left: 173px;
    }

    .main-menu > ul > li > ul > ul {
        /*display: table-cell;*/
    }

    .main-menu > ul > li ul + ul {
        /*padding-left: 20px;*/
    }

    .main-menu > ul > li ul > li > a, .responsive-menu .main-navigation li a {
        padding: 8px;
        color: #22272a;
        display: block;
        white-space: nowrap;
        text-align: left;
    }

    .main-menu > ul > li ul > li > a {
        min-width: 160px;
    }

    .main-menu > ul > li ul > li > a:hover, .responsive-menu .main-navigation li a:hover {
        color: #c3c3c3;
        background-color: #fff;
    }

    .main-menu > ul > li ul > li > a.activSub {
        background-image: url(../image/megnor/cat_arrow_hover.png);
        background-repeat: no-repeat;
        background-position: right center;
    }

    .main-menu > ul > li > ul > ul > li > a {
        color: #FFFFFF;
    }

    #slideshow0 .owl-wrapper .owl-item img {
        /*width:100%;*/
    }

    .category_img .img-thumbnail {
        border: medium none;
        margin-bottom: 10px;
        padding: 0;
        max-width: none;
    }

    .product-list .product-block {
        border-bottom: 2px solid #ececec;
    }

    .product-thumb .productlist_details > h4 a {
        color: #22272a;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .product-thumb .productlist_details > h4 {
        margin: 0;
        padding: 33px 0;
    }

    .product-list .product-block .list_left {
        border-right: 1px solid #e4e3e3;
        float: left;
        padding-right: 40px;
        width: 59%;
    }

    .product-list .product-block .list_right {
        float: left;
        width: 40%;
        overflow: hidden;
        padding-left: 40px;
    }

    .productlist_details {
        margin-left: 316px;
    }

    .productlist_details .button_group {
        list-style: outside none none;
        margin: 0;
        padding: 0;
    }

    @media (min-width: 768px) {
        #menu .dropdown:hover .dropdown-menu {
            display: block;
        }
    }

    @media (max-width: 767px) {
        #menu {
            border-radius: 0px;
        }

        #menu div.dropdown-inner > ul.list-unstyled {
            display: block;
        }

        #menu div.dropdown-menu {
            margin-left: 0 !important;
            padding-bottom: 10px;
            background-color: rgba(0, 0, 0, 0.1);
        }

        #menu .dropdown-inner {
            display: block;
        }

        #menu .dropdown-inner a {
            width: 100%;
            color: #fff;
        }

        #menu .dropdown-menu a:hover,
        #menu .dropdown-menu ul li a:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        #menu .see-all {
            margin-top: 0;
            border: none;
            border-radius: 0;
            color: #fff;
        }
    }

    /* 1-col layout */

    #column-left {
        width: 25.65%;
    }

    #column-right {
        width: 25.65%;
    }

    .layout-1 #content {
    }

    /* 2-col layout */

    .layout-2.left-col #column-left {
        width: 25.65%;
    }

    .layout-2.right-col #column-right {
        width: 25.65%;
    }

    .layout-2 #content {
        /*width:74.35%;*/
    }

    .layout-2.left-col #content {
    }

    .layout-2.right-col #content {
    }

    /* 3-col layout */
    .layout-3 #column-left {
        width: 25.65%;
    }

    .layout-3 #column-right {
        width: 25.65%;
    }

    .layout-3 #content {
        /*width:48.70%;*/
    }

    /*!* content *!*/
    /*#content {*/
    /*min-height: 600px;*/
    /*margin-bottom:20px;*/
    /*}*/

    /*CMS Block Start */

    .cms_left_banner {
        float: left;
        margin-right: 10px;
        overflow: hidden;
        background: none repeat scroll 0 0 #363757;
    }

    .cms_right_banner {
        float: right;
        margin-left: 10px;
        position: relative;
        background: none repeat scroll 0 0 #363757;
    }

    .cms_banner {
        overflow: hidden;
        margin-bottom: 30px;
    }

    .cms_left_banner img, .cms_right_banner img {
        transition: all 0.5s ease 0s;
        -moz-transition: all 0.5s ease 0s;
        -ms-transition: all 0.5s ease 0s;
        -o-transition: all 0.5s ease 0s;
        -webkit-transition: all 0.5s ease 0s;
    }

    .cms_left_banner:hover img {
        opacity: 0.7;
        -moz-opacity: 0.7;
        -webkit-opacity: 0.7;
        -khtml-opacity: 0.7;
        transform: scale(1.08);
        -moz-transform: scale(1.08);
        -o-transform: scale(1.08);
        -webkit-transform: scale(1.08);
        -ms-transform: scale(1.08);
    }

    .cms_banner .banner {
        width: 50%;
        float: left;
    }

    .static_category {
        float: left;
        margin: 30px 0;
        position: relative;
        width: 100%;
    }

    .image_hover {
        border: 1px solid #ffced6;
        height: 124px;
        left: 0;
        margin: 15px;
        position: absolute;
        width: 540px;
        opacity: 1;
        transition: all 0.5s ease 0s;
        -moz-opacity: 1;
        -moz-transition: all 0.5s ease 0s;
        -ms-transition: all 0.5s ease 0s;
        -khtml-opacity: 1;
        -o-transition: all 0.5s ease 0s;
        -webkit-opacity: 1;
        -webkit-transition: all 0.5s ease 0s;
    }

    .cms_right_banner:hover .image_hover {
        transition: all 0.5s ease 0s;
        -moz-transition: all 0.5s ease 0s;
        -ms-transition: all 0.5s ease 0s;
        -o-transition: all 0.5s ease 0s;
        -webkit-transition: all 0.5s ease 0s;
        transform: scale(1.035, 1.15);
        -moz-transform: scale(1.035, 1.15);
        -ms-transform: scale(1.035, 1.15);
        -o-transform: scale(1.035, 1.15);
        -webkit-transform: scale(1.035, 1.15);
        opacity: 0;
        -moz-opacity: 0;
        -khtml-opacity: 0;
        -webkit-opacity: 0;
    }

    .static_left {
        float: left;
        width: 20%;
    }

    .static_left .category-title {
        background: none repeat scroll 0 0 #eee;
        height: 237px;
        margin: 0 10px 0 0;
        padding: 0;
        color: #22272a;
        font-size: 22px;
        font-weight: 700;
        text-decoration: none;
    }

    .static_left .category-title h5 {
        color: #434571;
        font-size: 17px;
        font-weight: 700;
        text-decoration: none;
        padding: 67px 20px 0 40px;
        margin-bottom: 15px;
        margin-top: 0px;
        text-transform: uppercase;
        line-height: 23px;
    }

    .cate-block {
        margin: 0 10px;
        position: relative;
        border: 1px solid #eee;
    }

    .cate-block > a:hover {
        color: #22272a;
    }

    .cate-banner > img {
        height: auto;
        max-width: 100%;
    }

    .banner-hover {
        background: none repeat scroll 0 0 #eee;
        bottom: 0px;
        left: 0px;
        position: absolute;
        max-width: 218px;
        transition: all 0.3s ease 0s;
        -moz-transition: all 0.3s ease 0s;
        -ms-transition: all 0.3s ease 0s;
        -webkit-transition: all 0.3s ease 0s;
        -o-transition: all 0.3s ease 0s;
        width: 100%;
    }

    .banner-hover span {
        float: left;
        font-size: 13px;
        font-weight: 700;
        line-height: 13px;
        margin: 11px;
        text-transform: uppercase;
        color: #434571;
    }

    .cate-block .banner-hover .description {
        clear: both;
        float: left;
        font-size: 10px;
        height: 0;
        line-height: 16px;
        margin: 0 12px;
        color: #9c9c9c;
        font-weight: 400;
        opacity: 0;
        transition: all 0.3s ease 0s;
        -moz-opacity: 0;
        -moz-transition: all 0.3s ease 0s;
        -ms-transition: all 0.3s ease 0s;
        -khtml-opacity: 0;
        -o-transition: all 0.3s ease 0s;
        -webkit-opacity: 0;
        -webkit-transition: all 0.3s ease 0s;
    }

    .cate-block:hover .banner-hover {
        height: auto;
    }

    .cate-block:hover .banner-hover {
        background: none repeat scroll 0 0 #fff;
        opacity: 0.9;
        transition: all 0.3s ease 0s;
        -khtml-opacity: 0.9;
        -o-transition: all 0.3s ease 0s;
        -moz-opacity: 0.9;
        -moz-transition: all 0.3s ease 0s;
        -ms-transition: all 0.3s ease 0s;
        -webkit-opacity: 0.9;
        -webkit-transition: all 0.3s ease 0s;
    }

    .cate-block:hover .banner-hover .description {
        height: 51px;
        margin-bottom: 10px;
        opacity: 1;
        transition: all 0.3s ease 0s;
        -khtml-opacity: 1;
        -o-transition: all 0.3s ease 0s;
        -moz-opacity: 1;
        -moz-transition: all 0.3s ease 0s;
        -ms-transition: all 0.3s ease 0s;
        -webkit-opacity: 1;
        -webkit-transition: all 0.3s ease 0s;
    }

    .banner-hover span.price {
        color: #ff9cac;
        float: right;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .video {
        width: 100%;
        height: 100%;
    }

    .video_outer {
        background-image: url(../image/megnor/parallax.jpg);
        background-position: 50% 0;
        background-repeat: repeat-y;
        margin: 5px auto 40px;
        overflow: hidden;
        padding: 0;
        position: relative;
        height: 445px;
    }

    .video_inner.container {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        padding-top: 122px;
        position: relative;
        text-align: left;
        z-index: 1;
    }

    .videotext {
        color: #ffffff;
        font-size: 55px;
        font-weight: 300;
        line-height: 45px;
        margin-bottom: 22px;
        text-align: left;
        text-transform: uppercase;
    }

    .video_text2 {
        color: #ff9cac;
        font-size: 55px;
        font-weight: 700;
        line-height: 45px;
        text-transform: uppercase;
    }

    .video_text3 {
        color: #cfcfcf;
        line-height: 27px;
        margin-top: 20px;
        width: 40%;
        font-size: 16px;
        font-weight: 400;
    }

    .videotext .text_inner {
        font-weight: 700;
        margin: 0 0 0 15px;
    }

    .videosecondtext {
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center;
        text-transform: uppercase;
    }

    .video_outer a {
        background: url(../image/megnor/video_button1.png) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        display: inline-block;
        height: 64px;
        width: 64px;
    }

    .video-bg {
        position: absolute;
        top: 0;
        width: 100%;
    }

    .static_right {
        float: left;
        width: 80%;
    }

    .testimonials-category {
        overflow: hidden;
        position: relative;
        margin: 30px 0 70px 0;
    }

    #testimonial .customNavigation {
        left: 30px;
        position: absolute;
        top: 73%;
    }

    #testimonial .customNavigation a.prev {
        color: #ffffff;
        cursor: pointer;
        left: 10px;
        float: left;
        right: auto;
        background: url("../image/megnor/sprite.png") no-repeat scroll -12px 0px transparent;
        border: medium none;
    }

    #testimonial .customNavigation a:hover.prev {
        background-position: -12px -30px;
    }

    #testimonial .customNavigation a.next {
        color: #ffffff;
        cursor: pointer;
        left: 35px;
        float: left;
        right: auto;
        background: url("../image/megnor/sprite.png") no-repeat scroll -37px 0px transparent;
        border: medium none;
    }

    #testimonial .customNavigation a:hover.next {
        background-position: -37px -30px;
    }

    #footer_aboutus_block > h5 {
        display: none;
    }

    #footer_aboutus_block > ul {
        list-style: outside none none;
        margin: 0;
        padding: 0;
    }

    #footer .column .tm-about-logo > a {
        background: none;
        padding: 0;
    }

    .tm-about-logo {
        margin-bottom: 20px;
    }

    #footer #footer_aboutus_block.col-sm-3.column {
        margin: 0 55px 0 0;
        padding: 0;
        width: 23%;
        line-height: 20px;
    }

    #footer .col-sm-3.column.last > ul {
        list-style: outside none none;
        margin: 0;
        padding: 0;
    }

    #footer .col-sm-3.column ul .address {
        padding-left: 20px;
        background: url(../image/megnor/sprite.png) no-repeat scroll -48px -402px transparent;
    }

    #footer .col-sm-3.column ul .phoneno {
        padding-left: 22px;
        background: url(../image/megnor/sprite.png) no-repeat scroll -46px -314px transparent;
        margin: 6px 0 3px;
    }

    #footer .col-sm-3.column ul .email {
        padding-left: 25px;
        background: url(../image/megnor/sprite.png) no-repeat scroll -46px -360px transparent;
    }

    #footer .col-sm-3.column.last {
        line-height: 23px;
        margin: 0 0 0 23px;
        width: 24.5%;
    }

    #footer .col-sm-3.column .email > a {
        padding: 0;
        background: none;
        text-transform: lowercase;
    }

    .footer_bottom {
        background: none repeat scroll 0 0 #20213d;
        clear: both;
        overflow: hidden;
        padding: 20px 0 17px 0;
    }

    .footer_left {
        float: left;
        margin: 11px 0 7px;
        width: 25%;
    }

    .footer_center {
        float: left;
        text-align: center;
        width: 50%;
    }

    .footer_right {
        float: right;
        margin: 14px 0 6px 0;
        text-align: right;
        width: 25%;
    }

    .footer_bottom .footer_left > h5, .footer_bottom .footer_right > h5 {
        display: none;
    }

    .footer_bottom .payment_block li:first-child a, #footer .payment_block li:first-child a {
        margin: 0 7px 0 0;
    }

    .footer_bottom .payment_block li.visa a, #footer .payment_block li.visa a {
        background: url(../image/megnor/visa.png) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        padding: 11px 16px;
        display: block;
    }

    .footer_bottom .payment_block li.mastro a, #footer .payment_block li.mastro a {
        background: url(../image/megnor/discover.png) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        padding: 11px 16px;
        display: block;
    }

    .footer_bottom .payment_block li.paypal a, #footer .payment_block li.paypal a {
        background: url(../image/megnor/paypal.png) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        padding: 11px 16px;
        display: block;
    }

    .footer_bottom .payment_block li.amex a, #footer .payment_block li.amex a {
        background: url(../image/megnor/amex.png) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        padding: 11px 16px;
        display: block;
    }

    .footer_bottom .payment_block, .social_block {
        margin: 0;
        padding: 0;
        list-style: outside none none;
    }

    .footer_right .social_block li a .fa {
        font-size: 19px;
        font-weight: 300;
    }

    .footer_right .social_block li a {
        color: #b8b7b7;
    }

    .footer_right .social_block li a:hover {
        color: #fff;
    }

    .footer_bottom li {
        display: inline-block;
    }

    .footer_bottom .payment_block li, #footer .payment_block li {
        display: inline-block;
        height: 20px;
        margin-right: 10px;
        width: 33px;
    }

    .footer_bottom_inner.container {
        padding: 0;
    }

    .footer-top {
        background-color: #fe9cab;
        position: relative;
        margin-bottom: 30px;
    }

    footer .footer-top .footer_title1 {
        color: #20213d;
        float: none;
        font-size: 22px;
        font-weight: 900;
        left: auto;
        margin-bottom: 0;
        position: relative;
        top: auto;
        text-transform: uppercase;
    }

    .footer-top .footer_title2 {
        color: #fff;
        font-weight: normal;
        margin-left: 15px;
    }

    .footer-top .Footer_title3 {
        color: #fff;
    }

    .home-about-me.container {
        padding: 20px 0;
    }

    .aboutme-read-more {
        float: right;
        margin: 16px 0;
    }

    .footer-top .aboutme-read-more > a {
        background-color: #20213d;
        padding: 12px 15px;
        text-transform: uppercase;
        font-size: 15px;
        font-weight: 500;
        color: #fff;
    }

    .footer-top .aboutme-read-more > a:hover {
        background-color: #363757;
        transition: all 0.2s ease 0s;
        -moz-transition: all 0.2s ease 0s;
        -o-transition: all 0.2s ease 0s;
        -webkit-transition: all 0.2s ease 0s;
        -ms-transition: all 0.2s ease 0s;
    }

    .tm-about-text {
        float: left;
        line-height: 50px;
    }

    .social_block li {
        cursor: pointer;
        display: inline-block;
        height: 25px;
        margin: 0 10px 0 0;
        padding: 0;
        width: 25px;
    }

    .social_block li:last-child {
        margin: 0;
    }

    #testcms .slider-item .img {
        float: left;
        margin-right: 35px;
    }

    #testcms .slider-item .content-wrapper {
        margin-left: 315px;
        margin-right: 35px;
    }

    #testcms .blog_date {
        color: #ff9cac;
        font-size: 30px;
        font-weight: 700;
        margin: 0px 0 23px;
        padding-top: 3px;
    }

    .blog_date .day_date {
        color: #ff9cac;
        font-size: 30px;
        font-weight: 700;
    }

    .blog_date .day_month {
        color: #ff9cac;
        font-family: "Open Sans", arial;
        font-size: 17px;
        font-weight: bold;
        padding-left: 10px;
        text-transform: uppercase;
    }

    #testcms .title a {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: #434571;
    }

    #testcms .desc {
        color: #9c9c9c;
        font-weight: 400;
        margin-top: 25px;
        text-transform: initial;;
    }

    #testcms .comment {
        font-size: 12px;
        font-style: italic;
        font-weight: 400;
        color: #a5a5a5;
        margin-top: 19px;
    }

    #testcms .customNavigation {
        display: none;
    }

    #testcms .slider-controls.clickable {
        bottom: -80px;
        left: 0;
        margin: 0 auto;
        position: absolute;
        right: 0;
        text-align: center;
    }

    #testcms .slider-pagination {
        position: relative;
        text-align: center;
    }

    #testcms .slider-page {
        display: inline-block;
        margin: 0 2px;
    }

    #testcms .slider-page.active > span, #testcms .slider-page:hover > span {
        background: url(../image/megnor/banner-bullate.png) no-repeat scroll 0px -24px transparent;
    }

    #testcms .img_inner img {
        transition: all 0.5s ease 0s;
        -moz-transition: all 0.5s ease 0s;
        -webkit-transition: all 0.5s ease 0s;
        -o-transition: all 0.5s ease 0s;
        -ms-transition: all 0.5s ease 0s;
        width: 100%;
    }

    #testcms .img_inner {
        overflow: hidden;
        background-color: #363757;
    }

    #testcms .img_inner:hover img {
        opacity: 0.7;
        transform: scale(1.08);
        -moz-opacity: 0.7;
        -moz-transform: scale(1.08);
        -webkit-opacity: 0.7;
        -webkit-transform: scale(1.08);
        -o-transform: scale(1.08);
        -khtml-opacity: 0.7;
        -ms-transform: scale(1.08);
    }

    #testcms .slider-page > span {
        background: url(../image/megnor/banner-bullate.png) no-repeat scroll 0px 0px transparent;
        cursor: pointer;
        display: block;
        font-size: 0;
        height: 15px;
        text-indent: -9999px;
        width: 15px;
    }

    #testcms .slider-wrapper-outer {
        margin-bottom: 73px;
    }

    /*CMS Block End */

    /* footer */
    footer {
        margin-top: 6px;
        padding-top: 0px;
        background-color: #363757;
        border-top: medium none;
        color: #d9d9d9;
        position: relative;
    }

    footer hr {
        border-top: none;
        border-bottom: 1px solid #666;
    }

    #footer .col-sm-3.column {
        width: 18%;
        padding: 0;
        margin: 0 30px;
    }

    footer a {
        color: #d9d9d9;
    }

    #footer .column li a {
        background: none;
        padding-left: 0px;
    }

    footer a:hover, footer a:focus {
        color: #fff;
    }

    footer h5 {
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 14px;
        margin-top: 0;
        text-shadow: none;
        line-height: 13px;
        text-transform: uppercase;
    }

    footer .col-sm-3.column.last > h5 {
        margin-top: 0px;
    }

    #footer .col-sm-3.column .list-unstyled {
        line-height: 23px;
    }

    #footer .footer_left.col-sm-3.column, #footer .footer_right.col-sm-3.column {
        display: none;
    }

    #footer.container {
        overflow: hidden;
        padding: 50px 15px 65px;
    }

    footer p {
        bottom: 33px;
        color: #b8b7b7;
        left: 0;
        margin: 0 auto;
        position: absolute;
        right: 0;
        text-align: center;
        width: 35%;
    }

    /*Content*/

    .common-home .content-top-breadcum {
        display: none;
    }

    .content-top-breadcum {
        background: none repeat scroll 0 0 #f5f5f5;
        box-shadow: 0 2px 2px rgba(0, 0, 0, 0.07) inset;
        -moz-box-shadow: 0 2px 2px rgba(0, 0, 0, 0.07) inset;
        -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, 0.07) inset;
        height: 70px;
        margin-bottom: 30px;
        overflow: hidden;
        width: 100%;
    }

    @media (max-width: 979px) {
        #footer .column ul {
            display: none;
        }

        #footer .column {
            width: 100%;
        }
    }

    /* alert */
    .alert {
        padding: 8px 14px 8px 14px;
    }

    /* breadcrumb */
    .breadcrumb {
        margin: 0 0 20px 0;
        padding: 8px 0;
        border: 1px solid #ddd;
    }

    .breadcrumb i {
        font-size: 15px;
    }

    .breadcrumb > li {
        position: relative;
        white-space: nowrap;
    }

    .breadcrumb > li + li:before {
        content: '';
        padding: 0;
    }

    .breadcrumb > li:after {
    }

    .pagination {
        margin: 0;
    }

    /* buttons */
    .buttons {
        margin: 1em 0;
    }

    .btn-xs {
        font-size: 9px;
    }

    .btn-sm {
        font-size: 10.2px;
    }

    .btn-lg {
        padding: 10px 16px;
        font-size: 15px;
    }

    .btn-group > .btn, .btn-group > .dropdown-menu, .btn-group > .popover {
        font-size: 13px;
    }

    .btn-group > .btn-xs {
        font-size: 9px;
    }

    .btn-group > .btn-sm {
        font-size: 10.2px;
    }

    .btn-group > .btn-lg {
        font-size: 15px;
    }

    .btn-primary {
        color: #fff;
        background-color: #428bca;
        border: 1px solid #357ebd;
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {
        color: #fff;
        background-color: #3276b1;
        border: 1px solid #285e8e;
        background-position: 0 -15px;
    }

    .btn-warning {
        color: #ffffff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #faa732;
        background-image: linear-gradient(to bottom, #fbb450, #f89406);
        background-repeat: repeat-x;
        border-color: #f89406 #f89406 #ad6704;
    }

    .btn-warning:hover, .btn-warning:active, .btn-warning.active, .btn-warning.disabled, .btn-warning[disabled] {
        box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
        -moz-box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
    }

    .btn-danger {
        color: #ffffff;
        background: #d9534f;
    }

    .btn-danger:hover, .btn-danger:active, .btn-danger.active, .btn-danger.disabled, .btn-danger[disabled] {
        box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
        -moz-box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        color: #ffffff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #5bb75b;
        background-image: linear-gradient(to bottom, #62c462, #51a351);
        background-repeat: repeat-x;
        border-color: #51a351 #51a351 #387038;
    }

    .btn-success:hover, .btn-success:active, .btn-success.active, .btn-success.disabled, .btn-success[disabled] {
        box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
        -moz-box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: inset 0 1000px 0 rgba(0, 0, 0, 0.1);
    }

    .btn-info {
        color: #ffffff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #df5c39;
        background-image: linear-gradient(to bottom, #e06342, #dc512c);
        background-repeat: repeat-x;
        border-color: #dc512c #dc512c #a2371a;
    }

    .btn-info:hover, .btn-info:active, .btn-info.active, .btn-info.disabled, .btn-info[disabled] {
        background-image: none;
        background-color: #df5c39;
    }

    .btn-link {
        border-color: rgba(0, 0, 0, 0);
        cursor: pointer;
        color: #23A1D1;
        border-radius: 0;
    }

    .btn-link, .btn-link:active, .btn-link[disabled] {
        background-color: rgba(0, 0, 0, 0);
        background-image: none;
        box-shadow: none;
    }

    .btn-inverse {
        color: #ffffff;
        background: #363636;
        border: none;
    }

    .btn-inverse:hover, .btn-inverse:active, .btn-inverse.active, .btn-inverse.disabled, .btn-inverse[disabled] {
        background-color: #222222;
        background-image: linear-gradient(to bottom, #333333, #111111);
    }

    @media (max-width: 767px) {

    }

    /* list group */

    .box .filterbox {
        border: medium none;
        padding: 5px 0 8px;
        margin: 0px 0 0;
        background: none repeat scroll 0 0 transparent;
    }

    .list-group a {
        border-top: medium none;
        color: #454545;
        padding: 0px;
        font-size: 13px;
        font-weight: 700;
        background: none repeat scroll 0 0 transparent;
    }

    .filterbox .panel-footer {
        text-align: left;
        padding: 10px 0 0;
    }

    .filterbox .list-group-item {
        padding: 8px 0;
    }

    .filterbox .list-group a, .filterbox .list-group a:hover {
        padding: 0;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        font-weight: 700;
        color: #454545;
        text-transform: uppercase;
    }

    .filterbox .list-group-item label {
    }

    .filterbox .list-group-item label:hover {
        color: #c3c3c3;
    }

    .list-group a.active, .list-group a.active:hover, .list-group a:hover {
        color: #444444;
        background: #eeeeee;
        text-shadow: 0 1px 0 #FFF;
    }

    /* carousel */
    .carousel-caption {
        color: #FFFFFF;
        text-shadow: 0 1px 0 #000000;
    }

    .carousel-control .icon-prev:before {
        content: '\f053';
        font-family: FontAwesome;
    }

    .carousel-control .icon-next:before {
        content: '\f054';
        font-family: FontAwesome;
    }

    /* product list */
    .product-thumb {
        border: medium none;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .product-grid .product-thumb {
        margin-bottom: 8px;
    }

    .product-thumb .image {
        text-align: center;
        position: relative;
    }

    .product-thumb .image a {
        display: block;
        margin: 0 0 20px;
        /*background-color:#ff9cac ;*/

    }

    .hometab .product-thumb .image a, .product-grid .product-thumb .image a, .box.related .product-thumb .image a {
        background-color: rgba(255, 156, 172, 1);
    }

    .hometab .product-block:hover .image a, .product-grid .product-block:hover .image a, .box.related .product-block:hover .image a {
        background-color: rgba(255, 156, 172, 0.8);
    }

    .hometab .product-block:hover .image img, .product-grid .product-block:hover .image img, .box.related .product-block:hover .image img {
        opacity: 0.2;
        -moz-opacity: 0.2;
        -webkit-opacity: 0.2;
        -khtml-opacity: 0.2;
        transition: all 0.5s ease 0s;
        -moz-transition: all 0.5s ease 0s;
        -ms-transition: all 0.5s ease 0s;
        -webkit-transition: all 0.5s ease 0s;
        -o-transition: all 0.5s ease 0s;
    }

    /*.product-block:hover .image img {
        opacity: 0.3;
         transition: all 0.5s ease 0s;
        -moz-transition: all 0.5s ease 0s;
        -ms-transition: all 0.5s ease 0s;
        -webkit-transition: all 0.5s ease 0s;
        -o-transition: all 0.5s ease 0s;
    }*/

    .product-list .product-thumb .image > a {
        margin: 0 0 20px;
    }

    .product-grid .product-thumb .image a {
        margin: 0 0 23px;
    }

    .product-thumb .image a:hover {
        opacity: 1;
        -moz-opacity: 1;
        -webkit-opacity: 1;
        -khtml-opacity: 1;
    }

    /*.background-overlay {
        background: none repeat scroll 0 0 #ff9cac;
        height: 100%;
        letter-spacing: 1px;
        opacity: 0.9;
        -moz-opacity: 0.9;
        -webkit-opacity: 0.9;
        -khtml-opacity: 0.9;
        position: absolute;
        right: -100%;
        top: 0;
        transition: all 0.4s ease 0s;
        -moz-transition: all 0.4s ease 0s;
        -ms-transition: all 0.4s ease 0s;
        -webkit-transition: all 0.4s ease 0s;
        -o-transition: all 0.4s ease 0s;
        width: 100%;
        display:none;
    }
    */
    .hometab .product-thumb .price-old, .product-grid .product-thumb .price-old, .related-products .product-thumb .price-old {
        color: #9c9c9c;
        line-height: 15px;
    }

    .product_hover_block {
        left: -100%;
        position: absolute;
        top: 30px;
        transition: all 0.4s ease 0s;
        -moz-transition: all 0.4s ease 0s;
        -ms-transition: all 0.4s ease 0s;
        -webkit-transition: all 0.4s ease 0s;
        -o-transition: all 0.4s ease 0s;
        width: 100%;
    }

    .product-block:hover .product_hover_block {
        left: 0;
    }

    .product-thumb .image img {
        margin-left: auto;
        margin-right: auto;
        height: auto;
        opacity: 1;
        -moz-opacity: 1;
        -webkit-opacity: 1;
        -khtml-opacity: 1;
    }

    .product-grid .product-thumb .image {
        float: none;
    }

    @media (min-width: 767px) {
        .product-list .product-thumb .image {
            float: left;
            padding: 0px;
            margin-right: 45px;
        }
    }

    .product-thumb h4 {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 1px;
    }

    .product-thumb .caption {
        font-size: 13px;
        padding: 0 0 1px;
        text-align: center;
    }

    @media (max-width: 1200px) {
        .product-grid .product-thumb .caption {
            padding: 0 10px;
        }
    }

    @media (max-width: 767px) {
        .product-list .product-thumb .caption {
            min-height: 0;
            margin-left: 0;
            padding: 0 10px;
        }

        .product-grid .product-thumb .caption {
            min-height: 0;
        }
    }

    .product-thumb .rating {
        padding-bottom: 8px;
    }

    .rating .fa-stack {
        font-size: 8px;
    }

    .rating .fa-star {
        color: #fff;
        font-size: 13px;
    }

    .product-list .rating .fa-star, .rating-wrapper .fa-star, #review .fa-star, .product-compare .rating .fa-star {
        color: #9c9c9c;
        font-size: 13px;
    }

    .rating .fa-star + .fa-star, .rating-wrapper .fa-star + .fa-star, #review .fa-star + .fa-star {
        color: #434571;
    }

    h2.price {
        margin: 0;
    }

    .product-thumb .price {
        display: inline-block;
        vertical-align: middle;
        color: #22272a;
        font-size: 15px;
        font-weight: 600;
        padding: 0 0 4px;
        width: 100%;
    }

    #content .action .button_group, .hometab .action .button_group {
        margin: 0;
        padding: 0;
    }

    #content .button_group > li, .hometab .button_group > li {
        padding: 0;
    }

    #content .button_group > li:first-child, .hometab .button_group > li:first-child {
        margin-top: 2px;
    }

    #content .action .button_group .wishlist_button, .hometab .action .button_group .wishlist_button {
        background: url(../image/megnor/PLUSH.png) no-repeat scroll 16px 8px transparent;
        border-bottom: 1px solid #fff;
        border-left: medium none;
        border-right: medium none;
        border-top: medium none;
        padding: 0px 5px 3px 5px;
    }

    #content .action .button_group .compare_button, .hometab .action .button_group .compare_button {
        background: url(../image/megnor/PLUSH.png) no-repeat scroll 16px 11px transparent;
        border: medium none;
        padding: 3px 25px 4px 33px;
    }

    #content .product_hover_block .action .button_group button span, .hometab .product_hover_block .action .button_group button span {
        font-weight: 700;
        text-transform: uppercase;
        color: #fff;
        font-size: 10px;
    }

    .product-thumb .price-new {
        font-weight: 600;
        /*float:left;*/
    }

    .product-thumb .price-old, #column-left .product-thumb .price-old, #column-right .product-thumb .price-old {
        text-decoration: line-through;
        /*float:left;*/
        color: #22272a;
        font-size: 16px;
        font-weight: 400;
        margin-right: 10px;
    }

    #column-left .product-thumb .price-old, #column-right .product-thumb .price-old {
        font-size: 16px;
        color: #9c9c9c;
        float: left;
    }

    .product-thumb .price-tax {
        color: #999;
        display: inline-block;
        font-size: 12px;
        width: 100%;
        display: none;
    }

    .product-thumb .button-group {
        border-top: 1px solid #ddd;
        background-color: #eee;
        overflow: hidden;
    }

    .product-list .product-thumb .button-group {
        border-left: 1px solid #ddd;
    }

    @media (max-width: 768px) {
        .product-list .product-thumb .button-group {
            border-left: none;
        }
    }

    .product-thumb .button-group button, #cart .text-right .addtocart, #cart .text-right .checkout {
        width: 60%;
        border: none;
        display: inline-block;
        float: left;
        background-color: #fff;
        color: #434571;
        padding: 6px 8px;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        border: 1px solid #434571;
    }

    #cart .text-right .addtocart, #cart .text-right .checkout {
        width: auto;
        float: right;
    }

    #cart .dropdown-menu .img-thumbnail {
        width: auto;
        max-width: none;
    }

    #cart .text-right .checkout {
        margin: 0 5px 0 0;
    }

    .product-thumb .button-group button + button {
        width: 20%;
        border-left: 1px solid #ddd;

    }

    .product-thumb .button-group button:hover, #cart .text-right .addtocart:hover, #cart .text-right .checkout:hover {
        color: #fff;
        background-color: #434571;
        text-decoration: none;
        cursor: pointer;
    }

    @media (max-width: 1200px) {
        .product-thumb .button-group button, .product-thumb .button-group button + button {
            width: 33.33%;
        }
    }

    @media (max-width: 767px) {
        .product-thumb .button-group button, .product-thumb .button-group button + button {
            width: 33.33%;
        }
    }

    .thumbnails {
        overflow: auto;
        clear: both;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .thumbnails > li {
    }

    .thumbnails {
    }

    .thumbnails > img {
        width: 100%;
    }

    .image-additional a {
        margin-bottom: 20px;
        padding: 5px;
        display: block;
        border: medium none;
    }

    .image-additional {
    }

    .thumbnails .image-additional {
        float: left;
    }

    .product-info .image {
        border-radius: 3px;
        display: block;
        margin-bottom: 15px;
        text-align: center;
    }

    #content.productpage .product-title {
        margin-top: 0;
        border-bottom: 4px double #e4e3e3;
        padding: 0 0 16px;
        position: relative;
        font-size: 20px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .dark_area {
        border-bottom: 4px solid #8a8989;
        display: none;
        padding: 0 0 16px;
        position: absolute;
        width: 20%;
        left: 0;
    }

    .product-right .description {
        line-height: 23px;
        padding: 10px 0;
    }

    .product-description .description-right {
        padding-left: 15px;
        font-weight: 400;
    }

    .product-description td {
        font-weight: 700;
    }

    .product-right .list-unstyled .old-price {
        font-size: 13px;
        font-weight: 400;
        color: #9c9c9c;
        float: left;
        margin-right: 10px;
    }

    #content .product-right .special-price, #content .product-right .product-price {
        font-weight: 700;
        font-size: 20px;
        color: #22272a;
        margin: 0;
    }

    .product-info .zoomContainer {
        z-index: 9;
    }

    /* It need for ie7 */
    .product-info .additional-carousel {
        position: relative;
        margin-left: 0px;
    }

    .product-info .image-additional {
        clear: both;
        overflow: hidden;
        padding-left: 25px;
        padding-right: 25px;
        width: 425px;
    }

    .product-info .image-additional img {
    }

    .product-info .image-additional a {
        float: none;
        display: block;
    }

    .flexslider .slides img {
        width: inherit;
    }

    .flexslider.carousel .slides img {
        width: auto;
    }

    .slides {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .slides li {
        text-align: center;
    }

    .slides li img {
        text-align: center;
    }

    .flex-direction-nav a:before {
        line-height: 40px;
    }

    /*Megnor www.templatemela.com Start*/

    /* box */
    .box {
        margin-bottom: 12px;
    }

    .box .box-heading {
        background: none repeat scroll 0 0 transparent;
        color: #22272a;
        font-size: 15px;
        font-weight: 700;
        padding: 0 0 16px;
        border-bottom: 4px double #e4e3e3;
    }

    .box-heading > span {
        padding-bottom: 15px;
        border-bottom: 4px solid #8a8989;
        left: auto;
        text-transform: uppercase;
    }

    .box .box-content {
        background: transparent;
        border: medium none;
        margin: 12px 0 0;
        padding: 5px 0 8px;
    }

    .hometab .box-content {
        padding: 0;
    }

    #content .box .box-heading {
        border-bottom: 4px double #e4e3e3;
        background: none repeat scroll 0 0 transparent;
        padding: 0 0 16px;
        font-size: 15px;
        color: #22272a;
    }

    .box-heading.related {
        text-transform: uppercase;
    }

    #content .box .box-content {
        background: none;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        -khtml-border-radius: 0px;
        border-radius: 0px;
        border: none;
        padding: 10px 0;

    }

    #testcms .box-heading {
        margin-bottom: 58px;
        text-align: center;
    }

    #carousel-0 .box-heading {
        text-align: center;
        margin-bottom: 15px;
    }

    #testcms .box-heading span, #carousel-0 .box-heading span {
        padding: 0;
        border-bottom: 1px solid #e9e8e8;
        color: #434571;
        font-size: 17px;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        padding: 7px 0px;
        background: none;
    }

    .box .box-content ul, #content .content ul {
        padding: 0px;
        margin: 0px;
        list-style: none;
    }

    .box .box-content ul li, #content .content ul li, .hometab .box .box-content ul li {
        line-height: 22px;
        padding: 4px 0;
    }

    .hometab .button_group > li, .hometab .box .box-content ul li {
        padding: 0 !important;
    }

    .box .box-content ul li a, #content .content ul li a {
        background: url(../image/megnor/dot.png) no-repeat scroll 2px 9px transparent;
        display: block;
        padding: 0 0 0 13px;
        color: #454545;
        font-weight: 500;
    }

    .box .box-content ul li a + a, .box .box-content ul li a + a:hover {
        background: none;
        padding-left: 0;
    }

    .box .box-content ul li a:hover, #content .content ul li a:hover {
        background: url(../image/megnor/dot.png) no-repeat scroll 2px -16px transparent;
        color: #c3c3c3;
    }

    .box .box-content ul li ul li a, #content .content ul li ul li a {
        background: url(../image/megnor/dot.png) no-repeat scroll 2px -41px transparent;
        display: block;
        padding: 0 0 0 13px;
        color: #454545;
        font-weight: 500;
    }

    .box .box-content ul li ul li a:hover, #content .content ul li ul li a:hover {
        background: url(../image/megnor/dot.png) no-repeat scroll 2px -66px transparent;
        color: #c3c3c3;
    }

    .box .box-content ul ul {
        margin-left: 15px;
    }

    /*Product Tab */
    .tabfeatured_default_width {
        width: 250px;
    }

    .tabbestseller_default_width {
        width: 250px;
    }

    .tablatest_default_width {
        width: 250px;
    }

    .tabspecial_default_width {
        width: 250px;
    }

    .hometab {
        margin-top: 70px;
        position: relative;
    }

    .htabs {
        height: 31px;
        border: medium none;
        margin: 0 0 69px;
        text-align: center;
    }

    .etabs {
        display: inline-block;
        float: none;
        padding: 0;
        margin: 0px;
        padding: 0;
        position: relative;
        text-align: left;
    }

    .htabs .etabs li {
        display: inline-block;
        float: left;
        line-height: 18px;
        list-style: none outside none;
        position: relative;
        text-align: center;
    }

    .htabs a {
        color: #9c9c9c;
        display: block;
        float: left;
        font-size: 17px;
        margin-right: 5px;
        padding: 10px 22px;
        text-align: center;
        font-weight: 700;
        text-transform: uppercase;
        background: none;
        border: 1px solid transparent;
    }

    .htabs a.selected, .htabs a:hover {
        color: #434571;
        border: 1px solid #434571;
    }

    .hometab .tab-content {
        /*position:relative;*/
        padding: 0px;
        z-index: 2;
        overflow: visible;
        margin-bottom: 0px;
        border: medium none;
    }

    .tab-content .tab {
        display: none;
    }

    .hometab .box {
        margin-bottom: 2px;
    }

    .hometab .customNavigation a.prev::before {
        background: url(../image/megnor/line.jpg) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        content: "";
        height: 1px;
        left: -160px;
        margin: auto;
        position: absolute;
        top: 12px;
        width: 141px;
        cursor: default;
    }

    .hometab .customNavigation a.prev {
        right: 28px;
        left: 0;
        margin: 0 auto;
        background: url(../image/megnor/sprite.png) no-repeat scroll -12px 0px transparent;
        border: medium none;
    }

    .hometab .customNavigation a:hover.prev {
        background-position: -12px -30px;
    }

    .hometab .customNavigation a {
        top: -22px;
    }

    .hometab .customNavigation a.next::after {
        background: url(../image/megnor/line.jpg) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
        content: "";
        height: 1px;
        margin: auto;
        position: absolute;
        right: -161px;
        top: 12px;
        width: 141px;
        cursor: default;
    }

    .hometab .customNavigation a.next {
        right: -22px;
        left: 0;
        margin: 0 auto;
        background: url(../image/megnor/sprite.png) no-repeat scroll -37px 0px transparent;
        border: medium none;
    }

    .hometab .customNavigation a:hover.next {
        background-position: -37px -30px;
    }

    /* Product Grid */

    /* Product Grid Start */
    .product-grid-list {
    }

    #content .box-product, .product-grid-list ul, .hometab .box-product {
        list-style-type: none;
        position: relative;
        width: 100%;
        padding: 0px;
        margin: 0px;
        list-style: none;
        overflow: hidden;
    }

    .product-grid .productlist_details, .product-list .product_hover_block, .product-list .caption, .product-list .background-overlay {
        display: none;
    }

    .product-grid-list ul li,
    #content .box-product .product-items, .hometab .box-product .product-items {
        margin-bottom: 14px;
        padding: 0;
        margin: 0;
        position: relative;
        overflow: hidden;
    }

    #content .box-product .product-items, .hometab .box-product .product-items,
    #content .product-carousel .slider-item {
        width: 295px;
        float: left;
        display: inline-block;
    }

    .product-grid li {
        width: auto;
        float: none;
        display: inline-block;
    }

    .ie7 .product-grid li {
        width: 192px !important;
    }

    #content .image-additional .slider-item {
        display: inline-block;
        float: left;
    }

    #content .image-additional .slider-item .product-block {
        margin: 4px 2px;
        background: transparent;
        -moz-border-radius: 0px;
        -webkit-border-radius: 0px;
        border-radius: 0px;
        clear: both;
        overflow: hidden;
    }

    #content .image-additional .slider-item .product-block:hover {
        box-shadow: none !important;
    }

    .product-block-inner {
        position: relative;
    }

    .grid_default_width {
        width: 250px;
    }

    .featured_default_width {
        width: 250px;
    }

    .module_default_width {
        width: 230px;
    }

    .latest_default_width {
        width: 250px;
    }

    .special_default_width {
        width: 250px;
    }

    .related_default_width {
        width: 250px;
    }

    .bestseller_default_width {
        width: 250px;
    }

    .additional_default_width {
        width: 80px;
    }

    .testimonial_default_width {
        width: 200px;
    }

    .testcms_default_width {
        width: 700px;
    }

    .banners-slider-carousel .product-block-inner {
        text-align: center;
    }

    .hometab .box-product.product-carousel {
        padding-top: 0px;
    }

    #content .product-carousel .product-block,
    #content .product-grid-list .product-block,
    #content .box-product .product-block, .hometab .box-product .product-block {
        margin: 48px 15px;
        background: #fff;
        -moz-border-radius: 0px;
        -webkit-border-radius: 0px;
        border-radius: 0px;
        clear: both;
        overflow: hidden;
        border: medium none;
    }

    #content #related-carousel.product-carousel .product-block, #content #related-grid.box-product .product-block {
        margin: 25px 15px;
    }

    #carousel-0 .product-carousel .product-block {
        margin: 25px 5px;
    }

    .product-image-block {
        position: relative;
        max-width: 100%;
    }

    #content .product-carousel .product-block:hover,
    #content .product-grid-list .product-block:hover,
    #content .box-product .product-block:hover, .hometab .box-product .product-block:hover {
        overflow: hidden;
        clear: both;
    }

    .product-block .caption a {
        color: #9c9c9c;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        text-transform: uppercase;
    }

    .product-block:hover .caption a {
        color: #000;
    }

    .product-list .product-block:hover .caption a {
        color: #9c9c9c;
    }

    .cart_button, #button-cart {
        background: none repeat scroll 0 0 transparent;
        border: 1px solid #27b6af;
        color: #434571;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        line-height: 16px;
        padding: 6px 8px;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        white-space: nowrap;
        margin-top: 0px;
        transition: all 0.2s ease 0s;
        -moz-transition: all 0.2s ease 0s;
        -ms-transition: all 0.2s ease 0s;
        -webkit-transition: all 0.2s ease 0s;
        -o-transition: all 0.2s ease 0s;
    }

    #button-filter {
        background: none repeat scroll 0 0 transparent;
        border: 1px solid #434571;
        color: #434571;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        line-height: 16px;
        padding: 6px 8px;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        white-space: nowrap;
        margin-top: 0px;
        transition: all 0.2s ease 0s;
        -moz-transition: all 0.2s ease 0s;
        -ms-transition: all 0.2s ease 0s;
        -webkit-transition: all 0.2s ease 0s;
        -o-transition: all 0.2s ease 0s;
    }

    .productlist_details .list_left .desc {
        clear: both;
        line-height: 1.35;
        margin: 0 0 15px;
        padding: 0;
        font-weight: 500;
    }

    #content .product-list .productlist_details .wishlist_button, #content .product-list .productlist_details .compare_button {
        border-bottom: medium none;
        font-weight: 700;
        text-transform: uppercase;
    }

    #content .product-list .productlist_details .wishlist_button {
        padding: 0 0 7px 12px;
        background: url(../image/megnor/PLUS-black.png) no-repeat scroll 0px 6px transparent;
    }

    #content .product-list .productlist_details .compare_button {
        padding: 0 0 2px 12px;
        background: url(../image/megnor/PLUS-black.png) no-repeat scroll 0px 6px transparent;
    }

    .cart_button, .cart_button:hover, #button-filter:hover, #button-cart:hover {
        background: none repeat scroll 0 0 #27b6af;
        color: #fff;
    }

    .ie7 #content .product-carousel .product-block,
    .ie7 #content .product-grid-list .product-block,
    .ie7 #content .box-product .product-block, .hometab .box-product .product-block {
        border: medium none;
    }

    .ie7 #content .product-carousel .product-block:hover,
    .ie7 #content .product-grid-list .product-block:hover,
    .ie7 #content .box-product .product-block:hover, .hometab .box-product .product-block:hover {
        border: medium none;
    }

    .product-grid .product-thumb .desc, .product-grid .product-thumb .price-tax, .product-carousel .product-thumb .price-tax {
        display: none;
    }

    .product-list .product-details {
        overflow: hidden;
    }

    #content .product-carousel .product-block-inner,
    #content .product-grid-list .product-block-inner,
    #content .box-product .product-block-inner, .hometab .box-product .product-block-inner {
        padding: 0 0;
        text-align: center;
        overflow: hidden;
        position: relative;
        margin: 0;
    }

    .productlist_details .list_right .price {
        margin-top: 0;
        padding: 0 0 7px;
        color: #22272a;
        width: auto;
    }

    .productlist_details .list_right .rating {
        padding-bottom: 23px;
    }

    .banners-slider-carousel {
        padding-top: 50px;
        position: relative;
        width: 100%;
    }

    #carousel-0 .customNavigation a.prev {
        top: 45px;
    }

    #carousel-0 .customNavigation a.next {
        top: 45px;
    }

    #carousel-0 .customNavigation a {
        opacity: 0;
        -moz-opacity: 0;
        -webkit-opacity: 0;
        -khtml-opacity: 0;
    }

    #carousel-0:hover .customNavigation a {
        opacity: 1;
        -moz-opacity: 1;
        -webkit-opacity: 1;
        -khtml-opacity: 1;
        transition: all 0.2s ease 0s;
        -moz-transition: all 0.2s ease 0s;
        -o-transition: all 0.2s ease 0s;
        -webkit-transition: all 0.2s ease 0s;
        -ms-transition: all 0.2s ease 0s;
    }

    .sale {
        background-color: #ff461b;
        color: #fff;
        display: block;
        font-size: 11px;
        font-weight: 700;
        left: 10px;
        letter-spacing: 0;
        line-height: 16px;
        padding: 1px 4px;
        position: absolute;
        text-align: center;
        text-transform: uppercase;
        top: 12px;
    }

    /* box products for Left Column and Right Column */
    #column-left .box-product,
    #column-right .box-product {
        width: 100%;
        overflow: hidden;
    }

    #column-left .box-product > div,
    #column-right .box-product > div {
        display: block;
        vertical-align: top;
        margin-right: 0px;
        margin-bottom: 5px;
        width: 100%;
    }

    #column-left .box .box-content div.product-items:last-child > div, #column-right .box .box-content div.product-items:last-child > div,
    #column-left .box .box-content div.slider-item:last-child > div, #column-right .box .box-content div.slider-item:last-child > div {
        border: 0 none;
    }

    #column-left .product-block .caption a, #column-right .product-block .caption a {
        color: #22272a;
    }

    #column-left .box-product .image,
    #column-right .box-product .image {
        display: block;
        margin-bottom: 0px;
        float: left;
        margin-right: 20px;
        border: medium none;
    }

    #column-left .product-items .product-details {
        width: auto; /* specify width as per your requirement */
        margin-left: 98px;
        margin-right: 0;

    }

    #column-right .product-items .product-details {
        margin-right: 88px;
        margin-left: 0;
    }

    #column-left .product-thumb .caption,
    #column-right .product-thumb .caption {
        padding: 0;
        text-align: left;
    }

    #column-left .box-product .product-thumb h4, #column-right .box-product .product-thumb h4 {
        font-weight: 400;
        font-size: 13px;
        margin: 0 0 3px;
    }

    #column-left .box-product .name,
    #column-right .box-product .name {
        display: block;
    }

    #column-left .box-product .cart,
    #column-right .box-product .cart {
        display: block;
    }

    #column-left .box-product .cart .button,
    #column-right .box-product .cart .button {
        padding: 0;
        background: none;
        box-shadow: none;
        height: auto;
        font-weight: 400;
        border-radius: 0;
        color: #555;
        display: block;
        text-align: left;
    }

    #column-left .box-product .cart .button:hover,
    #column-right .box-product .cart .button:hover {
        text-decoration: underline;
    }

    #column-left .box-product .image img,
    #column-right .box-product .image img {
        opacity: 1;
        transition: none;
    }

    #column-left .box-product .rating,
    #column-right .box-product .rating,
    #column-left .box-product .name,
    #column-right .box-product .name,
    #column-left .box-product .price,
    #column-right .box-product .price,
    #column-left .box-product .cart,
    #column-right .box-product .cart {
        margin-bottom: 16px;
        margin-top: 3px;
        padding: 0;
        color: #22272a;
        font-weight: 700;
    }

    #column-left .box-product .image > a {
        margin: 0;
    }

    #column-left .box .box-content, #column-right .box .box-content,
    #column-left .box .filterbox, #column-right .box .filterbox, #column-left #banner0 {
        margin-bottom: 30px;
    }

    #column-left .box-product .price,
    #column-right .box-product .price {
        font-size: 16px;
    }

    #column-left .box-product .rating,
    #column-right .box-product .rating {
        display: none;
        margin-bottom: 0px;
    }

    #column-left .product-thumb, #column-right .product-thumb {
        border: medium none;
        margin-bottom: 3px;
        padding: 10px 0;
    }

    #column-left .sale, #column-left .price-tax, #column-left .wishlist, #column-left .compare,
    #column-right .sale, #column-right .price-tax, #column-right .wishlist, #column-right .compare {
        display: none !important;
    }

    #column-left .product-thumb .button-group button, #column-right .product-thumb .button-group button {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        padding: 0;
        width: auto;
        line-height: 15px;
        color: #22272a;
        border: medium none;
        font-size: 12px;
    }

    #column-left .product-thumb .button-group, #column-right .product-thumb .button-group {
        background: none;
        border: none;
    }

    .top_button {
        background: url("../image/megnor/top_arrow.png") no-repeat scroll 0 0 transparent;
        height: 36px;
        width: 36px;
        right: 8px;
        bottom: 5px;
        display: none;
        position: fixed;
        z-index: 95;
        font-size: 0;
        margin: 0 4px 4px 0;

    }

    #content ul.list-unstyled {
        border: medium none;
        margin-bottom: 10px;
        overflow: auto;
        padding: 10px 0px;
    }

    #content ul.list-unstyled li {
        line-height: 22px;
        padding: 4px 0;
    }

    .manufacturer-list {
        border: 1px solid #dbdee1;
        margin-bottom: 20px;
        padding: 5px;
    }

    .manufacturer-heading {
        background: none repeat scroll 0 0 #f8f8f8;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 6px;
        padding: 5px 8px;
    }

    .manufacturer-content {
        padding: 8px;
    }

    .manufacturer-list ul {
        float: left;
        list-style: outside none none;
        margin: 0 0 10px;
        padding: 0;
        width: 25%;
    }

    #column-left .flexslider, #column-right .flexslider {
        margin: 0 0 20px;
        box-shadow: 0 0;
        border-radius: 0;
    }

    .productpage .box {
        margin-top: 20px;
        margin-bottom: 0;
    }

    .tab-content {
        border: 1px solid #e4e4e4;
        padding: 25px 25px 20px;
        overflow: hidden;
    }

    .col-sm-4.total_amount {
        margin-top: 70px;
    }

    .product-tag {
        margin: 5px 0;
    }

    #content .aboutus {
        clear: both;
        margin-bottom: 25px;
    }

    .image1 {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll 7px -160px;
        float: left;
        height: 50px;
        width: 70px;
    }

    .image2 {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll 7px -99px;
        float: left;
        height: 50px;
        width: 70px;
    }

    .image3 {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll 7px -48px;
        float: left;
        height: 50px;
        width: 70px;
    }

    .image4 {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll 7px 10px;
        float: left;
        height: 50px;
        width: 70px;
    }

    .aboutus h2 {
        clear: both;
        font-size: 20px;
    }

    .about-content {
        overflow: hidden;
        margin-left: 70px;
    }

    .category_filter #grid-view:hover, .category_filter #grid-view.active {
        background: url(../image/megnor/sprite.png) no-repeat scroll -7px -206px transparent;
    }

    .category_filter #grid-view {
        background: url(../image/megnor/sprite.png) no-repeat scroll -7px -234px transparent;
        border: medium none;
        height: 27px;
        width: 27px;
    }

    .category_filter #list-view {
        background: url(../image/megnor/sprite.png) no-repeat scroll -33px -234px transparent;
        border: medium none;
        height: 27px;
        width: 27px;
    }

    .category_filter #list-view:hover, .category_filter #list-view.active {
        background: url(../image/megnor/sprite.png) no-repeat scroll -33px -206px transparent;
    }

    .category_filter #grid-view:hover .fa, .category_filter #list-view:hover .fa {
        color: #333;
    }

    .category_filter .btn-list-grid {
        float: left;
        width: auto;
        padding: 0;
        margin-top: 5px;
    }

    .compare-total {
        float: left;
        margin: 10px 10px 0;
        font-size: 13px;
        font-weight: 500;
    }

    .pagination-right {
        float: right;
        margin: 0;
        width: auto;
    }

    .category_filter .sort-by {
        float: left;
        margin: 8px 10px 0 0;
        width: auto;
        padding: 0;
    }

    .category_filter .show {
        float: left;
        margin: 8px 10px 0;
        padding: 0;
    }

    .category_filter .sort {
        float: left;
        width: 125px;
        padding: 0;
    }

    .category_filter .limit {
        float: right;
        width: 100px;
        padding: 0;
    }

    .sort-by-wrapper, .show-wrapper {
        float: left;
        padding: 3px 3px 0;
    }

    .category_filter .list.active, .category_filter .grid.active {
        background: #428bca;
    }

    .category_thumb .category_img, .category_thumb .category_description {
        float: left;
        width: 100%;
    }

    .category_list ul {
        padding: 0;
        display: inline-block;
        margin-bottom: 0;
    }

    .refine-search ul {
        padding: 0;
    }

    .refine-search ul li {
        list-style: none;
    }

    .category_list li a {
        color: #211f20;
        display: block;
        padding: 5px 0;
    }

    .category_list li a:hover {
        color: #c3c3c3;
    }

    .category_list li {
        float: left;
        list-style: outside none none;
        margin: 0 10px 5px 0;
    }

    .category_filter, .pagination-wrapper {
        border: medium none;
        display: inline-block;
        margin: 0 0 15px;
        padding: 0px;
        width: 100%;
    }

    .category_filter {
        border-bottom: 1px solid #e4e3e3;
        padding-bottom: 3px;
    }

    .pagination-wrapper {
        border-top: 1px solid #e4e3e3;
        padding-top: 5px;
    }

    .pagination-wrapper .page-link {
        float: right;
        padding: 0;
        width: auto;
        margin: 8px 0 0;
    }

    .pagination-wrapper .page-result {
        float: left;
        padding: 0;
        width: auto;
        margin: 8px 0 0;
    }

    .contact-info .left {
        float: left;
        width: 50%;
    }

    .contact-info .right {
        float: right;
        width: 50%;
    }

    .row.contact-info {
        padding: 0 15px;
    }

    .information-contact .panel-body {
        padding: 30px;
        overflow: auto;
    }

    .contact-info {
        color: #666;
    }

    .contact-info .address-detail strong {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll -6px -235px transparent;
        padding: 5px 0 0 40px;
        height: 28px;
        margin: 10px 0 5px;
        float: left;
        clear: both;
    }

    .contact-info .address-detail, .contact-info .telephone, .contact-info .fax {
        float: left;
        width: 100%;
    }

    .contact-info .telephone strong {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll -5px -314px transparent;
        padding: 5px 0 0 40px;
        margin: 10px 0 5px;
        height: 28px;
        float: left;
        clear: both;
    }

    .contact-info .fax strong {
        background: url("../image/megnor/cms-sprite.png") no-repeat scroll -5px -272px transparent;
        padding: 5px 0 0 40px;
        height: 28px;
        margin: 10px 0 5px;
        float: left;
        clear: both;
    }

    .contact-info address {
        display: inline-block;
        margin: 0 0 0 40px;
        float: left;
        clear: both;
    }

    #spinner {
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 1;
        background: url("../image/megnor/ajax-loader.gif") 50% 50% no-repeat #fff;
    }

    .main-slider {
        position: relative;
    }

    .information-sitemap .sitge-map ul {
        padding: 0 0 0 20px;
    }

    .forget-password {
        margin: 5px 0 0;
    }

    .product-compare .btn-primary {
        margin: 5px 0 5px;
    }

    .productpage .write-review, .productpage .review-count {
        margin: 0 10px 0 0;
    }

    .productpage .rating-wrapper {
        margin: 0px 0 2px 0;
    }

    .checkout-cart .hasCustomSelect {
        width: 95% !important;
    }

    .page-title {
        padding: 0 0 10px;
        border-bottom: 1px solid #eeeeee;
    }

    .page-title, .refine-search, .product-title, .product-option, #content h3, .aboutus > h3, .modal-title, .panel-title {
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        color: #22272a;
    }

    .product-option {
        padding-bottom: 10px;
        border-bottom: 1px solid #e4e3e3;
    }

    .page-title, .account-success h1, .affiliate-success h1, .checkout-success h2, .container h2, .checkout-cart h1 {
        color: #22272a;
        float: left;
        font-size: 15px;
        font-weight: 700;
        position: absolute;
        text-transform: uppercase;
        z-index: 4;
        left: 0;
        top: -73px;
        border-bottom: medium none;
    }

    .col-sm-3.category_list {
        width: 100%;
    }

    .copy-right {
        border-top: 1px solid #444;
        padding: 10px 0;
        text-align: center;
    }

    .col-sm-12.product_bottom {
        margin-bottom: 30px;
    }

    .affiliate-account .btn-primary .list-group-item {
        padding: 0;
        background: none;
        font-weight: 700;
    }

    .affiliate-account .btn-primary .list-group-item:hover, .affiliate-account .btn-primary:hover a {
        color: #fff;
    }

    #content .affiliate-logout {
        border: medium none;
        padding: 10px 0;
    }

    .control-label.qty {
        float: left;
        margin: 6px 10px 0 0;
    }

    #input-quantity {
        float: left;
        height: 30px;
        margin-right: 10px;
        width: auto;
    }

    .product-right .btn.wishlist {
        background: url(../image/megnor/bkg_pipe1.gif) no-repeat scroll right 10px transparent;
        border: medium none;
        padding: 7px 12px 7px 0;
    }

    .productpage .wishlist span {
        background: url(../image/megnor/PLUS-black.png) no-repeat scroll 0px 5px transparent;
        padding: 0 0 0 12px;
    }

    .productpage .compare span {
        background: url(../image/megnor/PLUS-black.png) no-repeat scroll 0px 5px transparent;
        padding: 0 0 0 12px;
    }

    .product-right .btn.compare {
        background: none repeat scroll 0 0 transparent;
        border: medium none;
    }

    .addthis_toolbox.addthis_default_style {
        margin-top: 12px;
    }

    .product-right .btn-group {
        margin-bottom: 8px;
    }

    #products-related .customNavigation a {
        top: -60px;
    }

    #content h3 {
        margin-bottom: 16px;
    }

    .row.site-map {
        border: 1px solid #e4e3e3;
        padding: 15px 0;
        line-height: 22px;
    }

    .modal-content .aboutus {
        clear: both;
        margin-bottom: 25px;
    }

    .shopping-cart .input-group .form-control {
        width: auto;
        margin-right: 2px;
    }

    #input-coupon, #input-voucher {
        margin-right: 2px;
    }

    .shopping-cart .input-group .form-control {
        text-align: center;
        margin-right: 2px;
    }

    .shopping-cart .input-group-btn {
        float: left;
    }

    #collapse-coupon .input-group-btn #button-coupon, #collapse-voucher .input-group-btn #button-voucher {
        margin-left: 2px;
    }

    header .container {
        padding: 0 15px;
    }

    #button-search {
        margin-bottom: 10px;
    }

    .shopping-cart .input-group-btn .btn.btn-danger {
        margin-left: 0px;
    }

    #content .refine-search {
        margin-bottom: 0;
        margin-top: 10px;
    }

    .affiliate-tracking .dropdown-menu a:hover {
        color: #c3c3c3;
    }

    .common-home .alert {
        margin-bottom: 10px;
        margin-top: 10px;
    }

    @media only screen and (min-width: 1024px) and (max-width: 1230px) {
        .header_left, .header_right {
            width: 383px;
        }

        .header_center {
            width: 214px;
        }

        .cms_banner img {
            width: 100%;
        }

        .product-info .image-additional, .category_img .img-thumbnail {
            width: 100%;
        }

        .static_left {
            width: 25%;
        }

        .static_right {
            width: 75%;
        }

        #footer #footer_aboutus_block.col-sm-3.column {
            width: 23%;
            margin: 0 20px 0 0;
        }

        #footer .col-sm-3.column.last {
            width: 25%;
        }

        #footer .col-sm-3.column {
            margin: 0 25px;
        }

        #content .product-carousel .product-block, #content .product-grid-list .product-block, #content .box-product .product-block, .hometab .box-product .product-block {
            margin: 25px;
        }

        #menu > ul > li > div {
            left: -75px;
        }

        #column-left .product-thumb .price-old, #column-right .product-thumb .price-old {
            margin-bottom: 5px;
        }

        #column-left .fa-shopping-cart:before, #column-right .fa-shopping-cart:before {
            display: none;
        }

        #column-left .hidden-xs, #column-left .hidden-sm, #column-left .hidden-md {
            display: block !important;
            text-transform: capitalize;
            font-weight: 400;
            margin-top: -25px;
        }

        .col-sm-4.total_amount {
            margin-top: 88px;
        }

        .product-grid .product_hover_block {
            top: 95px;
        }

        .product-list .product-block .list_left {
            padding-right: 15px;
        }

        .product-list .product-block .list_right {
            padding-left: 15px;
        }

        .product-thumb .price-old {
            margin-right: 10px;
        }

        .contact-info .left {
            width: 40%;
        }

        .contact-info .right {
            float: left;
        }

        .row.site-map {
            margin: 0;
        }

        #collapse-coupon label, #collapse-voucher label {
            width: auto;
        }

        .static_left .category-title {
            height: 240px;
        }

        .col-sm-4.col-sm-offset-8 {
            margin-left: 51.667%;
            width: 48.333%;
        }

        .banners-slider-carousel .product-block-inner img {
            width: 100%;
        }

        .banner-hover {
            max-width: 223px;
        }

        .static_left .category-title h5 {
            padding-right: 15px;
        }

        .image_hover {
            height: 104px;
            width: 454px;
            margin: 13px;
        }

        #testcms .slider-item .img {
            width: 50%;
        }

        #testcms .slider-item .content-wrapper {
            margin-left: 280px;
        }

        .cms_right_banner:hover .image_hover {
            transform: scale(1.042, 1.19);
            -moz-transform: scale(1.042, 1.19);
            -o-transform: scale(1.042, 1.19);
            -webkit-transform: scale(1.042, 1.19);
            -ms-transform: scale(1.042, 1.19);
        }

        #content .product-list .productlist_details .wishlist_button {
            padding: 0 0 7px 12px;
        }

        #content .product-list .productlist_details .compare_button {
            padding: 0 0 2px 12px;
        }

        .product-list .product-thumb .image {
            margin-right: 20px;
        }

        .productlist_details {
            margin-left: 295px;
        }

        .product-search .col-sm-3 {
            width: 32%;
        }

        .video_outer {
            height: 420px;
            margin: 20px 0 35px;
        }

        .owl-controls .owl-buttons div {
            top: 40% !important;
        }

        .footer-top .aboutme-read-more > a {
            font-size: 12px;
            padding: 12px 16px;
        }

        footer .footer-top .footer_title1 {
            font-size: 18px;
        }

        .product-list .product-thumb .price-old {
            margin-bottom: 10px;
        }

        .product-list .product-thumb .price-old {
            margin-bottom: 10px;
        }

        .product-thumb .image img {
            width: 100%;
        }

        .nav-responsive span, .main-menu > ul > li > a {
            padding: 25px 16px;
        }
    }

    @media only screen and (min-width: 980px) and (max-width: 1023px) {
        .header_left, .header_right {
            width: 343px;
        }

        .header_center {
            width: 214px;
        }

        .cms_banner img {
            width: 100%;
        }

        #menu > ul > li > div {
            left: -80px;
        }

        .static_left {
            width: 25%;
        }

        .static_right {
            width: 75%;
        }

        #footer #footer_aboutus_block.col-sm-3.column {
            width: 23%;
            margin: 0 20px 0 0;
        }

        #footer .col-sm-3.column.last {
            width: 26%;
        }

        #footer .col-sm-3.column {
            margin: 0 20px;
        }

        .category_img .img-thumbnail {
            width: 100%;
        }

        .productlist_details {
            margin-left: 0;
        }

        .product-thumb .productlist_details > h4 {
            clear: both;
        }

        .product-list .product-block {
            padding-bottom: 25px;
        }

        .product-list .product-thumb .image a {
            margin-bottom: 0;
        }

        .product-info .image-additional {
            width: 100%;
        }

        .contact-info .left {
            width: 40%;
        }

        .contact-info .right {
            float: left;
        }

        .row.site-map {
            margin: 0;
        }

        #collapse-coupon label, #collapse-voucher label {
            width: auto;
        }

        .static_left .category-title {
            height: 232px;
        }

        .col-sm-4.col-sm-offset-8 {
            margin-left: 51.667%;
            width: 48.333%;
        }

        #column-left .product-thumb .price-old, #column-right .product-thumb .price-old {
            margin-left: 0;
            margin-bottom: 5px;
        }

        .form-horizontal .col-sm-10 {
            width: 82.333%;
        }

        .form-horizontal .col-sm-2 {
            width: 17.667%;
        }

        .banner-hover {
            max-width: 220px;
        }

        .image_hover {
            height: 101px;
            width: 436px;
            margin: 12px;
        }

        #testcms .author {
            font-size: 22px;
        }

        #testcms .slider-item .img {
            width: 50%;
        }

        #testcms .slider-item .content-wrapper {
            margin-left: 270px;
        }

        .product-search .col-sm-3 {
            width: 32%;
        }

        .video_outer {
            height: 420px;
            margin: 20px 0 35px;
        }

        #cart.fixed {
            right: 20px;
        }

        .footer-top .aboutme-read-more > a {
            font-size: 12px;
            padding: 12px 16px;
        }

        footer .footer-top .footer_title1 {
            font-size: 16px;
        }

        .cms_right_banner:hover .image_hover {
            transform: scale(1.045, 1.2);
            -moz-transform: scale(1.045, 1.2);
            -o-transform: scale(1.045, 1.2);
            -webkit-transform: scale(1.045, 1.2);
            -ms-transform: scale(1.045, 1.2);
        }

        .product-thumb .image img {
            width: 100%;
        }

        .product-grid .product_hover_block {
            top: 100px;
        }

        .nav-responsive span, .main-menu > ul > li > a {
            padding: 25px 16px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 980px) {
        .layout-2.left-col #column-left, .layout-2.right-col #column-right {
            width: 25%;
        }

        .layout-2 #content {
            width: 75%;
        }

        #column-left .product-items .product-details, #column-right .product-items .product-details {
            margin: 0 0 0 98px;
        }
    }

    @media (max-width: 979px) {
        #column-left .box-product .image,
        #column-right .box-product .image {
            margin-right: 7px;
            width: 50px;
        }

        .col-md-4.btn-list-grid {
            margin-bottom: 10px;
        }

        .pagination-right {
            clear: both;
            float: left;
            margin: 5px 0;
        }

        .product-compare .table-bordered {
            width: 100%;
            float: left;
            overflow: auto;
            display: inline;
        }

        .dropdown-menu.dropdown-menu-right.myaccount-menu {
            top: 88%;
        }

        #accordion .form-horizontal .control-label {
            width: 100%;
        }

        #res-menu {
            display: block;
        }

        #menu.main-menu, #menu ul {
            display: none;
        }

        .nav-responsive {
            text-align: left;
        }

        .nav-responsive span {
            margin-left: 0;
            padding: 25px 0 23px;
        }

        .cms_banner img {
            width: 100%;
        }

        .header_inner {
            margin: 20px 0 15px 0;
        }

        .header_left {
            width: 100%;
            margin: 0;
            border-left: medium none;
        }

        .header_right_top {
            border-bottom: medium none;
        }

        .header_center {
            float: none;
            margin-top: 40px;
            width: 100%;
        }

        #top-links.pull-right {
            float: right;
            clear: right;
            border-top: medium none;
            padding: 0;
        }

        .header_right {
            width: 100%;
            float: none;
            margin-top: 5px;
        }

        .header_right_bottom {
            border-top: medium none;
            clear: left;
            float: left;
            left: 0;
            padding: 0;
            position: absolute;
            top: 19px;
        }

        .contact_no {
            display: none;
        }

        #cart, .header-cart {
            float: none;
        }

        #cart {
            padding: 0 0 0 11px;
        }

        #column-left .product-items .product-details, #column-right .product-items .product-details {
            margin: 0 0 0 57px;
        }

        #search .input-lg {
            width: 0px;
            border-bottom: 1px solid #e9e9e9;
        }

        #search .input-lg:focus, #search .input-lg:active, #search:hover .input-lg {
            width: 300px;
        }

        #cart > .btn {
            padding: 12px 13px;
            background-position: 0 -278px;
        }

        #cart-total {
            left: 18px;
            top: -8px;
        }

        .static_left {
            width: 33%;
        }

        .static_right {
            width: 67%;
        }

        .static_left .category-title {
            height: 240px;
        }

        #footer_aboutus_block > h5, #footer .footer_left.col-sm-3.column, #footer .footer_right.col-sm-3.column {
            display: block;
        }

        .footer_left, .footer_right {
            display: block;
            width: 100%;
            text-align: center;
        }

        .footer_bottom .payment_block, .social_block {
            text-align: center;
        }

        .footer_left {
            margin: 0 0 7px;
        }

        .footer_bottom {
            padding: 25px 0;
        }

        .footer_bottom {
            padding: 30px 0;
        }

        footer p {
            bottom: 20px;
            width: 100%;
            padding: 0 15px;
        }

        #footer #footer_aboutus_block.col-sm-3.column {
            width: 100%;
            margin: 0;
        }

        #footer .col-sm-3.column, #footer .col-sm-3.column.last {
            width: 100%;
            margin: 0;
        }

        .footer_right {
            text-align: left;
        }

        #footer.container {
            padding: 0 15px 23px;
        }

        #footer .payment_block {
            margin: 0;
            padding: 0;
            list-style: outside none none;
        }

        .category_img .img-thumbnail {
            width: 100%;
        }

        .productlist_details {
            margin-left: 0;
        }

        .product-thumb .productlist_details > h4 {
            clear: both;
        }

        .product-list .product-block {
            padding-bottom: 25px;
        }

        .product-info .image-additional {
            width: 100%;
        }

        .product-right .btn.compare {
            padding: 7px 0px;
        }

        .shopping-cart .input-group-btn, #collapse-coupon .input-group-btn, #collapse-voucher .input-group-btn {
            margin-top: 3px;
            float: left;
        }

        #collapse-coupon label, #collapse-voucher label {
            width: auto;
        }

        .col-sm-4.col-sm-offset-8 {
            margin-left: 51.667%;
            width: 48.333%;
        }

        #column-left .product-thumb .price-old, #column-right .product-thumb .price-old {
            margin-left: 0;
            margin-bottom: 5px;
        }

        .product-list .product-thumb .image a {
            margin-bottom: 0;
        }

        .account-wishlist .text-right .btn.btn-primary, .account-wishlist .text-right .btn.btn-danger {
            margin-bottom: 3px;
        }

        .account-wishlist .fa.fa-times {
            font-size: 15.5px;
        }

        .account-voucher .buttons.clearfix .btn.btn-primary {
            margin-top: 8px;
        }

        .form-horizontal .col-sm-10 {
            width: 77.333%;
        }

        .form-horizontal .col-sm-2 {
            width: 22.667%;
        }

        .contact-info .right {
            width: 100%;
            float: left;
            margin-top: 20px;
        }

        .contact-info .left {
            width: 100%;
        }

        .row.site-map {
            margin: 0;
        }

        footer h5 {
            margin-top: 0;
        }

        .video_outer {
            height: 320px;
            margin: 10px 0 20px
        }

        #footer_aboutus_block .tm-about-description, #footer .col-sm-3.column.last > ul, #footer .payment_block {
            margin-bottom: 10px;
        }

        .banners-slider-carousel .product-block-inner img {
            width: 100%;
        }

        .banner-hover {
            max-width: 100%;
        }

        #testcms .slider-item .img {
            width: 50%;
        }

        .image_hover {
            height: 77px;
            width: 334px;
            margin: 9px;
        }

        .cms_right_banner:hover .image_hover {
            transform: scale(1.058, 1.27);
            -moz-transform: scale(1.058, 1.27);
            -o-transform: scale(1.058, 1.27);
            -webkit-transform: scale(1.058, 1.27);
            -ms-transform: scale(1.058, 1.27);
        }

        #column-left .product-thumb .button-group button, #column-right .product-thumb .button-group button {
            text-align: left;
            letter-spacing: 0px;
        }

        .shopping-cart .input-group .form-control {
            width: 100%;
        }

        .product-search .col-sm-3 {
            width: 40%;
            margin: 5px 0;
        }

        .product-search .col-sm-3.sort {
            width: 50%;
            margin: 0;
        }

        #footer .col-sm-3.column.last > ul, #footer_aboutus_block .tm-about-description {
            padding-right: 30px;
        }

        .video_inner.container {
            padding-top: 90px;
        }

        .videotext {
            font-size: 36px;
            margin-bottom: 12px;
            line-height: 36px;
        }

        .video_text2 {
            font-size: 36px;
            line-height: 36px;
        }

        .video_text3 {
            font-size: 14px;
            margin-top: 12px;
            width: 50%;
        }

        .nav-container.fixed #res-menu {
            width: 95%;
        }

        #cart.fixed {
            right: 25px;
            margin-top: 20px !important;
        }

        #cart.fixed > .btn {
            background-position: 0px 5px;
        }

        #cart.fixed #cart-total {
            left: 17px;
            top: -6px;
        }

        .owl-controls .owl-buttons div {
            top: 40% !important;
        }

        #cart.fixed .dropdown-menu {
            top: 198%;
        }

        .footer-top .aboutme-read-more > a {
            font-size: 12px;
            padding: 12px 16px;
        }

        footer .footer-top .footer_title1 {
            font-size: 16px;
        }

        .tm-about-text {
            line-height: 25px;
        }

        #testcms .slider-item .content-wrapper {
            margin-right: 0px;
        }

        .product_hover_block {
            top: 85px;
        }

        .product-grid .product_hover_block {
            top: 115px;
        }
    }

    @media (max-width: 767px) {
        .layout-2 #content, .layout-3 #content {
            width: 100%;
        }

        .product-info .image-additional {
            width: 100%;
        }

        .product-info .image-additional a {
            padding: 1px;
        }

        .header-logo {
            text-align: center;
        }

        .header-cart {
            width: auto;
            display: inline-block;
            text-align: center;
        }

        .header-logo > div {
            display: inline-block;
        }

        .btn-info {
            margin-bottom: 3px;
        }

        #input-search {
            margin-bottom: 8px;
        }

        #logo .img-responsive {
            margin: 0 auto;
        }

        .col-sm-4.total_amount {
            margin-top: 20px;
        }

        .checkout-cart .btn.btn-danger {
            margin-top: 0px !important;
        }

        .button_class {
            clear: both;
        }

        .show-wrapper {
            clear: both;
            margin: 10px 0 0;
        }

        .category_filter .show {
            float: left;
            margin: 8px 10px 0 0;
        }

        .product-compare .table-bordered {
            float: left;
            width: 100%;
            overflow: auto;
            display: inline;
        }

        .product-info .image, .product-info .additional-carousel {
            margin: 0 auto;
            width: 400px;
        }

        .addthis_toolbox.addthis_default_style {
            margin: 10px 0;
        }

        #content .category_list .filterbox {
            border: medium none;
            margin: 20px 0;
        }

        #content .category_list .filterbox .list-group a {
            border: none;
        }

        nav .container {
            padding: 0 15px;
        }

        header .row, #footer .row {
            margin: 0;
        }

        .product_hover_block {
            top: 65px;
        }

        .static_left {
            width: 100%;
        }

        .static_left .category-title {
            background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
            height: auto;
            margin: 0 0 30px;
        }

        .static_left .category-title h5 {
            padding: 7px 0 0;
            font-size: 17px;
        }

        .static_right {
            width: 100%;
        }

        #testimonial .customNavigation a.prev {
            float: right;
            right: 25px;
            left: auto;
        }

        #testimonial .customNavigation a.next {
            float: right;
            right: 0;
            left: auto;
        }

        #testimonial .customNavigation {
            top: 18%;
            left: auto;
        }

        #column-left, #column-right {
            display: none;
        }

        .product-grid.col-xs-12 {
            width: 50%;
        }

        .background-overlay, .product-thumb .rating, #content .action .button_group, .hometab .action .button_group {
            display: none;
        }

        .product_hover_block {
            position: relative;
            left: auto;
            top: auto;
        }

        .page-title, .account-success h1, .affiliate-success h1, .checkout-success h2, .container h2, .checkout-cart h1 {
            left: 15px;
            top: -103px;
        }

        .breadcrumb {
            left: 15px !important;
            right: auto !important;
        }

        .product-list .product-thumb .image {
            float: left;
        }

        .product-list .product-thumb .rating, #content .product-list .action .button_group {
            display: block;
        }

        .product-right .btn.compare {
            padding: 7px 12px;
        }

        .col-sm-3 .checkbox-inline {
            margin: 8px 0;
        }

        .content-top-breadcum {
            height: 100px;
        }

        .form-horizontal .col-sm-2, .form-horizontal .col-sm-10 {
            width: 100%;
        }

        .cate-block {
            text-align: center;
        }

        .banner-hover {
            margin: 0 auto;
            right: 1px;
            text-align: left;
        }

        .banner-hover span {
            margin: 11px 6px;
        }

        #testimonial .customNavigation a {
            top: -37px;
        }

        .cate-block:hover .banner-hover .description {
            height: 62px;
        }

        .image_hover {
            display: none;
        }

        #testcms .author {
            font-size: 22px;
        }

        #testcms .slider-item .content-wrapper {
            margin-left: 286px;
        }

        .header_right_bottom {
            left: 15px;
        }

        .product-thumb .price {
            color: #22272a;
        }

        #testcms .slider-item .img {
            width: 50%;
            margin-right: 15px;
        }

        .cms_left_banner:hover img {
            transform: scale(1);
            -moz-transform: scale(1);
            -o-transform: scale(1);
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
        }

        .product-list .product-block .list_left {
            width: 100%;
            padding-right: 0;
            border-right: medium none;
        }

        .product-list .product-block .list_right {
            width: 100%;
            padding-left: 0;
        }

        .list_cart_button {
            margin-bottom: 20px;
        }

        .hometab .product-thumb .price-old, .product-grid .product-thumb .price-old, .related-products.product-thumb .price-old {
            color: #22272a;
        }

        #collapse-coupon label, #collapse-voucher label {
            padding: 0;
        }

        .product-search .col-sm-3.sort, .product-search .col-sm-3 {
            width: 100%;
        }

        .video_inner.container {
            padding-top: 80px;
        }

        .video_outer {
            height: 320px;
            margin: 20px 0 25px;
        }

        .video_inner.container {
            padding-left: 30px;
        }

        .nav-container.fixed #res-menu {
            width: 90%;
        }

        .owl-carousel {
            margin: 0 0 10px !important;
        }

        .owl-controls .owl-buttons div {
            top: 35% !important;
        }

        .home-about-me.container {
            padding: 15px;
        }

        .header_right_bottom button span {
            display: none;
            font-size: 0;
        }

        .product-thumb .image img {
            max-width: none;
            width: 100%;
        }

        #collapse-coupon .input-group-btn #button-coupon, #collapse-voucher .input-group-btn #button-voucher {
            margin-left: 0;
        }

        .owl-carousel .owl-buttons div, .owl-carousel .owl-buttons .owl-next, .owl-carousel .owl-buttons .owl-prev {
            opacity: 1 !important;
        }

        #carousel-0 .customNavigation a {
            opacity: 1;
        }

        .hometab .product-block:hover .image img, .product-grid .product-block:hover .image img, .box.related .product-block:hover .image img {
            opacity: 1;
        }

    }

    @media only screen and (max-width: 479px) {
        .product-info .image-additional a {
            float: none;
            text-align: center;
            padding: 1px;
        }

        .table {
            float: left;
            margin-bottom: 20px;
            overflow: scroll;
            width: 100%;
        }

        .product-layout.product-grid {
            width: 100% !important;
        }

        .shopping-cart .input-group .form-control {
            padding: 0;
            text-align: center;
        }

        .account-wishlist .table-bordered {
            float: left;
            width: 100%;
            overflow: auto;
            display: inline;
        }

        #cart .dropdown-menu table {
            display: inline-block;
        }

        .flex-direction-nav a:before {
            font-size: 27px !important;
        }

        .dropdown-menu.pull-right {
            width: 100%;
        }

        .bootstrap-datetimepicker-widget.dropdown-menu.pull-right {
            width: 250px;
        }

        #cart .dropdown-menu li > div {
            min-width: 100%;
        }

        .category_filter .limit {
            float: left;
        }

        .contact-info .left, .contact-info .right {
            width: 100%;
        }

        .product-info .image, .product-info .additional-carousel {
            width: 100%;
        }

        .nav-tabs > li {
            width: 100%;
            text-align: center;
            margin-bottom: -3px !important;
        }

        .nav-tabs > li > a {
            margin: 0 0 2px;
        }

        .productpage .write-review, .productpage .review-count {
            display: inline-block;
            margin: 5px 0 0;
            width: 100%;
        }

        .pagination-wrapper .page-link {
            float: left;
        }

        .pagination-wrapper .page-result {
            float: left;
            clear: both;
            text-align: left;
        }

        .checkout-cart .pull-right {
            clear: both;
            float: left;
            margin: 10px 0;
        }

        #top-links ul li {
            display: none;
        }

        #top-links ul li:first-child {
            background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
            display: block;
            padding-left: 0;
            padding-right: 0;
        }

        #top-links .dropdown-menu.dropdown-menu-right.myaccount-menu > li {
            display: block;
        }

        .dropdown-menu-right {
            right: 0 !important;
            left: auto !important;
        }

        .cms_banner .banner {
            width: 100%;
        }

        .cms_left_banner {
            margin: 0 0 10px 0;
        }

        .cms_right_banner {
            margin-top: 10px;
            margin-left: 0;
        }

        .htabs {
            height: 120px;
        }

        .htabs .etabs li, .htabs a.selected, .htabs a:hover, .htabs a, .etabs {
            width: 100%;
            margin-right: 0;
            margin-bottom: 2px;
        }

        .hometab .customNavigation a {
            top: -20px;
        }

        .htabs li:first-child a, .htabs a {
            padding: 10px 22px;
        }

        #product select {
            width: 93% !important;
        }

        #cart {
            border-left: medium none;
            padding-left: 0;
        }

        #search .input-lg, #search:hover .input-lg, #search .input-lg:focus, #search .input-lg:active {
            width: 230px;
        }

        #search {
            margin-right: 0;
        }

        #search .btn-lg {
            margin-right: 0;
        }

        #search .input-lg {
            position: relative;
            padding: 5px;
            right: auto;
            float: right;
        }

        .header_inner {
            margin: 20px 0 0 0;
        }

        .header-cart {
            position: relative;
            top: 0px;
        }

        #search .input-group-btn {
            width: 1%;
            float: left;
        }

        .nav-tabs > li > a {
            margin-right: 0px !important;
        }

        .footer_bottom {
            padding: 40px 0;
        }

        .account-address .btn.btn-info, .account-address .btn.btn-danger {
            margin-bottom: 3px;
        }

        #collapse-coupon .input-group-btn, #collapse-voucher .input-group-btn {
            clear: both;
            float: left;
            margin-top: 5px;
        }

        .col-sm-4.col-sm-offset-8 {
            margin-left: 0;
            width: 100%;
        }

        #cart, .header-cart {
            float: right;
        }

        .header_right2 {
            float: left;
        }

        .banner-hover {
            max-width: 100%;
        }

        #testcms .slider-item .content-wrapper {
            clear: both;
            float: left;
            margin: 20px 0 0;
        }

        #testcms .slider-item .img {
            margin-right: 0;
            width: 100%;
        }

        #cart .dropdown-menu {
            width: 290px;
        }

        #input-coupon, #input-voucher {
            float: none;
        }

        .hometab .customNavigation a.prev::before, .hometab .customNavigation a.next::after {
            display: none;
        }

        .video_outer {
            height: 240px;
            margin: 15px 0 20px;
        }

        .video_inner.container {
            padding-top: 40px;
        }

        .videotext {
            font-size: 22px;
            line-height: 22px;
            margin-bottom: 10px;
        }

        .video_text2 {
            font-size: 22px;
            line-height: 22px;
        }

        .video_text3 {
            font-size: 14px;
            margin-top: 10px;
            width: 70%;
        }

        #cart.fixed {
            right: 15px;
        }

        .owl-controls .owl-buttons div {
            display: none !important;
        }

        .home-about-me.container {
            text-align: center;
        }

        .aboutme-read-more, .tm-about-text {
            float: none;
        }

        footer p {
            bottom: 5px;
        }

        .hometab {
            margin-top: 20px;
        }

        .htabs {
            margin: 0 0 45px;
        }

        .product-search .sort select.hasCustomSelect {
            width: 86% !important;
        }
    }

    @media only screen and (max-width: 319px) {
        .product-info .product-image .customNavigation {
            width: 196px;
            margin: 0 auto;
            position: relative;
        }

        .product-info .additional-carousel {
            width: 196px;
            margin: 0 auto;
        }

        .header-logo .img-responsive {
            width: 100%;
        }

        .btn-primary {
            margin-bottom: 2px;
        }

        .compare-total {
            clear: both;
            margin: 8px 10px 10px 0;
        }

        #cart .dropdown-menu {
            right: 0px;
            width: 210px;
        }

        #cart .dropdown-menu {
            width: 210px;
        }

        #cart .text-right .addtocart {
            margin: 0 0 5px;
        }

        #cart .text-right .checkout {
            margin: 0;
        }

        #product select {
            width: 88% !important;
        }

        .hometab {
            margin-top: 20px;
        }

        .cms_banner {
            margin-bottom: 20px;
        }

        .static_left .category-title h5 {
            font-size: 14px;
        }

        #testimonial .customNavigation a {
            top: -42px;
        }

        .product-right .btn.compare {
            padding: 7px 0;
        }

        #content .box-heading.related {
            font-size: 12px;
        }

        #footer .col-sm-3.column .email > a {
            word-wrap: break-word;
        }

        .content-top-breadcum {
            height: 125px;
        }

        .page-title, .account-success h1, .affiliate-success h1, .checkout-success h2, .container h2 {
            top: -135px;
        }

        .breadcrumb {
            top: -99px !important;
        }

        .product-thumb .image img {
            width: 100%;
        }

        .account-address .table .text-right {
            vertical-align: middle;
        }

        #search .input-lg, #search:hover .input-lg, #search .input-lg:focus, #search .input-lg:active {
            width: 155px;
        }

        .videotext, .video_text2 {
            font-size: 15px;
            margin-bottom: 15px;
            line-height: 18px;
        }

        .video_text3 {
            font-size: 12px;
            width: 90%;
        }

        #cart.fixed {
            right: 5px;
        }

        span.customSelect {
            overflow: hidden;
        }
    }

    @media only screen and (max-width: 319px) {
    }

    #accordion .panel-title > a {
        color: inherit;
        display: inline-block;
        width: 100%;
    }

    #accordion .panel-title {
        padding: 10px;
    }

    #accordion .panel-heading .fa.fa-caret-down {
        float: right;
    }

    #accordion .col-sm-10 {
        width: 75%;
    }

    #accordion .form-horizontal .control-label {
        text-align: left;
    }

    .account-address .text-right {
        vertical-align: middle;
    }

    #collapse-coupon label, #collapse-voucher label {
        padding-left: 0;
    }

    /* Megnor www.templatemela.com End*/

    @-webkit-keyframes fixedAnim {
        0% {
            top: -40px;
        }
        100% {
            top: 0;
        }
    }

    @-moz-keyframes fixedAnim {
        0% {
            top: -40px;
        }
        100% {
            top: 0;
        }
    }

    @keyframes fixedAnim {
        0% {
            top: -40px;
        }
        100% {
            top: 0;
        }
    }

    /*.select_width{*/
    /*width:120px !important;*/
    /*}*/

    .circle_loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
<style type="text/css">


    #mapper {
        width: 100px;
        height: 100px;
        min-width: 100px;
        min-height: 100px;
        z-index: 1000;
        position: absolute;
        top: 0;
        display: none;

        background: rgba(255, 1, 1, 0.1) none repeat scroll 0 0 / contain;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        cursor: pointer;

    }

    #planetmap div {

        display: block;
        position: absolute;
    }

    #main_panel {
        margin: auto;
        padding: 10px;
        /*width: 1000px;*/
    }

    #url_panel {

    }

    #form_panel {
        float: left;
        background: #eee;
        border: 5px solid #FFF;
        outline: 1px solid #eee;
        left: 100px;
        padding: 5px;
        position: absolute;
        top: 40px;
        width: 310px;
        display: none;
        z-index: 2000;
    }

    #form_panel input, textarea {
        padding: 3px;
        background: #FFF;
        border: 1px solid #CFCFCF;
        color: #000;
    }

    #image_panel {
        /*float:left;*/
        /*width:600px;*/
        position: relative;
        margin: 0 auto;
        text-align: center;
    }

    #image_panel img.save {
        left: 0;
        top: -102px;
        max-width: 600px;
        overflow: hidden;
        padding-top: 5px;
    }

    #image_panel img.delete {
        left: 0;
        top: -102px;
        max-width: 600px;
        overflow: hidden;
        padding-top: 5px;
    }

    #form_panel .label {
        float: left;
        width: 80px;
        padding: 5px;
    }

    #form_panel .field {
        float: left;
        width: 200px;
        padding: 5px;
    }

    #form_panel .row {
        clear: both;
    }

    .tagged_title {

        font-size: 12px;
        font-weight: bold;
        padding: 3px;
        margin-top: 5px;
        background: rgba(255, 1, 1, 0.1) none repeat scroll 0 0 / contain;
        border: 2px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        cursor: pointer;
        width: 100px;
        color: #f5f2d3;
    }

    .tagged_title a {

        color: #f5f2d3;
    }

    #info_panel {
        padding: 10px;
        margin: 20px 0;
        background: #eee;
    }

    .tagged_box {
        width: 100px;
        height: 100px;
        min-width: 100px;
        min-height: 100px;
        z-index: 1000;
        position: absolute;
        top: 0;
        display: none;

        background: rgba(255, 1, 1, 0.1) none repeat scroll 0 0 / contain;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        cursor: pointer;
    }

    input[type='button'] {
        background: none repeat scroll 0 0 #2769C4;
        border: 1px solid #CFCFCF;
        color: #FFFFFF;
        font-weight: bold;
        height: 30px;
        padding: 5px;
    }

    .select_width {
        width: 300px !important;
    }

</style>

<div class="row">
    <div style="display:none" id="loading_div">
        <div class="rowd">
            <div class="loader">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="lading"></div>
            </div>
        </div>
    </div>
    <div class="category_filter" style="margin:0px">
        <div class="pagination-left">
            <div class="sort-by-wrapper">
                <div class="col-md-6 text-left sort" style="width:120px ; margin-left:0px">
                    <div style="font-size:20px; width: 400px; font-weight:bold">Create a tagged photo</div>
                </div>
            </div>

        </div>
        <div class="pagination-right">

            <div class="sort-by-wrapper">
                <div class="col-md-6 text-right sort3" style="margin-left:0px">
                    <button class="cart_button" type="button"
                            style="line-height: 20px; background-color: black; border-color:black" data-toggle="modal"
                            data-target="#resetLook">
                        <span class="">RESET PLAYGROUND</span></button>
                </div>
            </div>
            <div class="sort-by-wrapper">
                <div class="col-md-6 text-right sort3"
                     style=" margin-left:0px;margin-left: 32px;margin-right: -25px;">
                    <button class="cart_button" type="button" style="line-height: 20px;" data-toggle="modal"
                            data-target="#saveDraft">
                        <span class="">SAVE DRAFT</span></button>
                </div>
            </div>

            <div class="sort-by-wrapper">
                <div class="col-md-6 text-right sort3" style=" margin-left:0px;width: 98px;">
                    <button class="cart_button" type="button" style="line-height: 20px;" data-toggle="modal"
                            data-target="#publish">
                        <span class="">PUBLISH</span></button>
                </div>
            </div>

        </div>
    </div>
    <div class="col-sm-12">
        <div class="category_filter" style="margin:0px">
            <div class="pagination-left">

                <div class="sort-by-wrapper">

                    <div class="col-md-3 text-left sort">
                        <select id="category_id" class="selectpicker filter-field" name='category_id'
                                data-live-search="true">
                            <option value="t10">category ...</option>
                            <?php foreach ($catgeories as $key => $value) {
                                $selected = "";
                                if ($key == $category_id) {
                                    $selected = 'selected="selected"';
                                }

                                $count = (int)substr($value, strpos($value, "(") + 1, 1);
                                if ($count > 0) {
                                    ?>
                                    <option data-tokens="<?= $value; ?>" value="<?= $key; ?>"
                                        <?php echo "  " . $selected . " "; ?>
                                            style="background: #5cb85c; color: #fff;"> <?= $value; ?></option>
                                <?php } else {
                                    ?>
                                    <option data-tokens="<?= $value; ?>" value="<?= $key; ?>"
                                        <?php echo "  " . $selected . " "; ?> > <?= $value; ?></option>

                                <?php }
                                ?>

                            <?php } ?>
                        </select>
                    </div>
                </div>


                <div class="sort-by-wrapper">

                    <div class="col-md-3 text-left sort">
                        <?= Html::dropDownList('admin', array($admin) , $users, ["class" =>"selectpicker  filter-field" ,  "data-live-search" =>"true"  , "id" =>"admin"]) ;?>
                    </div>
                </div>

                <div class="sort-by-wrapper">
                    <div class="col-md-3 text-left sort">
                        <select id="brand" class="selectpicker  filter-field" name='brand'
                                data-live-search="true">
                            <?php if ($brand == 't10' || $brand == '0') { ?>
                                <option value="t10" selected="selceted">brand ...</option>
                            <?php } else { ?>
                                <option value="t10">brand ...</option>
                            <?php } ?>

                            <?php foreach ($brands as $key => $value) {
                                $selected = "";
                                if ($key == $brand && ($brand != '0') && ($brand != 't10')) {
                                    $selected = 'selected="selected"';
                                }
                                ?>
                                <option
                                    value="<?= $key; ?>" <?php echo "  " . $selected . " "; ?> > <?= $value; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="sort-by-wrapper">
                    <div class="col-md-3 text-left sort">
                        <select id="condition" class="selectpicker  filter-field" name='condition'
                                data-live-search="true">
                            <?php if ($condition == 't10' || $condition == '0') { ?>
                                <option value="t10" selected="selceted">condition ...</option>
                            <?php } else { ?>
                                <option value="t10">condition ...</option>
                            <?php } ?>
                            <?php foreach ($conditions as $key => $value) {
                                $selected = "";
                                if ($key == $condition && ($condition != '0') && ($condition != 't10')) {
                                    $selected = 'selected="selected"';
                                }
                                ?>
                                <option
                                    value="<?= $key; ?>" <?php echo "  " . $selected . " "; ?> > <?= $value; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>


                <div class="sort-by-wrapper">

                    <div class="col-md-3 text-left sort">
                        <select id="price" class="selectpicker  filter-field" name='price'>
                            <option value="" selected="selected">price ...</option>
                            <option value="1">less than $50</option>
                            <option value="2"> $50-$100</option>
                            <option value="3"> $100-$150</option>
                            <option value="4"> $150-$200</option>
                            <option value="5"> $200-$300</option>
                            <option value="6">$300-$500</option>
                            <option value="7">$500-$700</option>
                            <option value="8">$700-$1000</option>
                            <option value="9">$1000-$1500</option>
                            <option value="10">$1500-$2000</option>
                            <option value="11">$2000-$5000</option>
                            <option value="12">more than $5000</option>
                        </select>
                    </div>
                </div>


                <div class="sort-by-wrapper">

                    <div class="filter-search-input2 col-md-3 text-right sort2">
                        <input type="text" name="name" id="searchName" class="form-control hasCustomSelect"
                               placeholder="Filter products and brands here" value="<?php echo $name; ?>"/>

                    </div>
                </div>

                <div class="sort-by-wrapper" style="float: right">

                    <div class="col-md-3 text-right sort" style="width:100px ; margin-left:0px">
                        <button class="cart_button" id="searchBtn" type="button"
                                style="line-height: 20px;width:100px">
                            <span class="">Filter</span></button>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div id="content" class="col-sm-6" style="border: 4px solid #eee;padding:0px; margin-top:0px">

        <div class="row" id="products">

            <?php $i = 1;
            foreach ($items as $item) { ?>
                <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12"
                     id="<?php echo $item->item_id ?>">
                    <div class="product-block product-thumb transition">
                        <div class="product-block-inner">
                            <div class="product-image-block-inner" id="<?php echo "id" . $item->item_id ?>">
                                <div class="image">
                                    <a href="<?php echo Yii::getAlias('@web') . Image::thumb($item->image, 183, 235); ?>">
                                        <img
                                            src="<?php echo Yii::getAlias('@web') . Image::thumb($item->image, 183, 235); ?>"
                                            alt="<?php echo $item->title ?>" title="<?php echo $item->title ?>"
                                            class="img-responsive"></a>

                                    <div class="background-overlay"></div>


                                    <div class="product_hover_block">

                                        <div class="action">
                                            <ul class="button_group">
                                                <li>
                                                    <button class="wishlist_button" type="button"
                                                            title="Select To Tag"
                                                            onclick="addToPlayground('<?php echo $item->title ?>','<?php echo $item->checkout_link ?>','<?php echo $item->item_id ?>')">
                                                        <span
                                                            class="hidden-xs hidden-sm hidden-md"> Select To Tag</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="compare_button" type="button" title="Get Info"
                                                            data-toggle="modal"
                                                            data-target="#getInfo<?php echo $item->item_id ?>"><span
                                                            class="hidden-xs hidden-sm hidden-md">Get Info</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="caption">
                                <h4><?php echo $item->title; ?></h4>

                                <p class="price">
                                    <?php echo $item->price; ?> <span class="price-tax">Ex Tax: $100.00</span>
                                </p>
                            </div>
                            <div class="productlist_details">
                                <h4>
                                    <a href="<?php echo Yii::getAlias('@web') . Image::thumb($item->image, 183, 235); ?>"><?php echo $item->title;; ?></a>
                                </h4>

                                <div class="list_right">
                                    <p class="price">
                                        <?php echo $item->price; ?> <span class="price-tax">Ex Tax: $100.00</span>
                                    </p>

                                    <div class="action">
                                        <ul class="button_group">
                                            <li>
                                                <button class="wishlist_button" type="button" title="Select To Tag"
                                                        onclick="addToPlayground('<?php echo $item->title ?>','<?php echo $item->checkout_link ?>','<?php echo $item->item_id ?>')">
                                                    <span class="hidden-xs hidden-sm hidden-md">Select To Tag</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="compare_button" type="button" title="Get Info"
                                                        data-toggle="modal"
                                                        data-target="#getInfo<?php echo $item->item_id ?>"><span
                                                        class="hidden-xs hidden-sm hidden-md">Get Info</span></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div id="getInfo<?php echo $item->item_id ?>" class="modal fade getInfo" role="dialog">
                    <div class="modal-dialog modal-md">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo $item->title ?></h4>
                            </div>
                            <div class="modal-body">
                                <p><?php echo $item->brand ?></p>

                                <p><?php echo $item->color ?></p>

                                <p><?php echo $item->conditions ?></p>

                                <p><?php echo $item->price . "$"; ?></p>

                                <p><?php echo $item->description ?></p>

                                <div>
                                    <img
                                        src="<?php echo Yii::getAlias('@web') . Image::thumb($item->image, 275, 350); ?>"
                                        alt="<?php echo $item->title; ?>"
                                        title="<?php echo $item->title; ?>" class="img-responsive">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>


                <?php
                if (($i) % 4 == 0) {
                    echo '<div class="clearfix visible-lg"></div>';
                }
                $i++;
            } ?>

        </div>

    </div>

    <!---->
    <!--    <br><br>-->
    <!--    <h3>Playground</h3><br>-->
    <div id="content" class="playground col-sm-6"
         style="border: 4px solid #eee;padding:0px; margin-top:0px;overflow:hidden;">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'form_id']]) ?>
        <span class="btn-file" style="padding-bottom: 10px; padding-top: 10px">Start by adding photo here</span>

        <div style="margin:0 auto">
            <div style="margin-left:25%;margin-bottom:10px">
                <select id="format" class="selectpicker select_width filter-field" name='format'>
                    <option value="" selected="selected">Format ...</option>
                    <?php foreach ($formats as $f) { ?>
                        <option value="<?php echo $f; ?>"><?php echo $f; ?></option>
                    <?php } ?>
                </select>
            </div>


            <div style="margin-left:25% ;margin-bottom:30px ; display:none" id="dimension">
                <input type="number" name="width" id="width" placeholder="width" style="width:140px ;float:left"
                       class="form-control hasCustomSelect"/>
                <input type="number" name="height" id="height" placeholder="height"
                       style="width:140px;float:left;margin-left:20px" class="form-control hasCustomSelect"/>

                <br/>
            </div>

              <span class="btn btn-success btn-file">
                  <span id="add-your-photo"> ADD YOUR PHOTO</span>
                    <input name="UploadForm[imageFile]" value="" type="hidden" id="uploadform-imagefile2">
                    <input id="uploadform-imagefile" name="UploadForm[imageFile]"
                           onchange='$("form#form_id").submit();' aria-invalid="false" type="file">
              </span>

            <br/>

        </div>
        <div id='main_panel'>

            <div style='margin: auto; width: 550px;'>

                <div id='image_panel'>

                    <img src='' id='imageMap'/>

                    <div id='mapper'>
                        <img src='<?php echo Yii::getAlias('@web') . Image::thumb('/uploads/icons/save.png'); ?>'
                             onclick='openDialog()' class="save"/>
                    </div>

                    <div id="planetmap">
                    </div>

                    <div id='form_panel'>
                        <div class='row'>
                            <div class='label'>Title</div>
                            <div class='field'><input type='text' id='title'/></div>
                        </div>
                        <div class='row'>
                            <div class='label'></div>
                            <div class='field'>
                                <input type='button' value='Add Tag' onclick='addTag()'/>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <?php ActiveForm::end() ?>

        <script type="text/javascript">
            /// showTags();
            setOperation(1);

            function setOperation(enable = 1) {
                if (enable == 1) {

                    $(".container-fluid .row #content #products").css("opacity", "0.1");
                    $(".container-fluid .row #content .category_filter").css("opacity", "0.1");
                    $(".container-fluid .row #content #products").append('<div id="floating_block" class="col-sm-12" style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px;background-color:#ccc ;opacity:0.1"></div>');

                } else if (enable == 0) {
                    $(".container-fluid .row #content #products").css("opacity", "1");
                    $(".container-fluid .row #content .category_filter").css("opacity", "1");
                    $("#floating_block").remove();
                }
            }


            $("#format").change(function () {
                var format = $("#format").val();
                console.log("format = " + format);
                if (format == 'custom(width*height)' || format == 'Custom(width*height)' || format == 'Custom(width*height)') {
                    console.log("show yes");
                    $("#dimension").show();
                }
                else {
                    console.log("hide yes");
                    $("#dimension").hide();
                }
            });


            $("#uploadform-imagefile, #uploadform-imagefile2").click(function () {
                var format = $("#format").val();
                if (format.length == 0 || format == "") {
                    $('.container-fluid').prepend('<div class="alert alert-warning alert-dismissable">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
                        'you should select format before uploading photo ...</div>');
                    return false;
                }
                else {
                    event.preventDefault();
                    return false;
                }
            });

            $("form#form_id").submit(function (event) {
                var formData = new FormData($(this)[0]);
                var url = "<?php echo  Yii::getAlias('@web').DIRECTORY_SEPARATOR.'admin/tagged/a/upload-file'?>";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    //async: false,
                    success: function (file_name) {
                        submited = true;
                        $("#imageMap").attr("src", "<?php echo  Yii::getAlias('@web') ; ?>" + file_name);
                        $("#add-your-photo").text("Update Your Photo");
                        setOperation(0);
                        $(".tagged").remove();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


                event.preventDefault();

                return false;
            });

        </script>

    </div>

</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">

    var isCalling = true;
    var incremnted_offset = $("#products .product-layout").size();
    $("#products").scroll(function () {
        var row_height = 350;
        var n = $("#products .product-layout").size();
        var number_of_rows = 3;
        var count = 6;

        if (n % 6 > 0) {
            number_of_rows = parseInt(n / 6) + 1;
        } else {
            number_of_rows = parseInt(n / 6);
        }
        var scroll_to_height = parseInt($("#products").scrollTop()) * 2;

        console.log("number_of_rows = " + number_of_rows);
        console.log("scroll_to_height = " + scroll_to_height);
        console.log("number_of_rows*row_height = " + number_of_rows * row_height);

        console.log("offset = " + n);
        console.log("incremnted_offset = " + incremnted_offset);
        console.log("isCalling = " + isCalling);
        console.log("============================================================== ");

        if (scroll_to_height * 6 >= parseInt(number_of_rows * row_height) && isCalling && n >= incremnted_offset) {
            incremnted_offset += count;
            var price = $("#price").val();
            var name = $("#searchName").val();
            var category_id = $("#category_id").val();
            var admin = $("#admin").val();
            var brand = $("#brand").val();
            var condition = $("#condition").val();

            $("#loading_div").show();
            console.log(" Ajax Call ");


            $.ajax({
                type: 'POST',
                url: "<?php echo  Yii::getAlias('@web').DIRECTORY_SEPARATOR.'admin/tagged/a/search'?>",
                data: {
                    category_id: category_id,
                    name: name,
                    price: price,
                    admin: admin,
                    brand: brand,
                    condition: condition
                    ,
                    offset: n,
                    count: count
                },
                success: function (result) {
                    if (result != '') {
                        $("#products .visible-lg:last").after(result);
                        setTimeout(function () {
                            $("#loading_div").hide();
                        }, 2000);

                    }
                    else {
                        isCalling = false;
                    }
                },
                async: false
            });

        }
    });

    $("#searchBtn").click(function () {

        var price = $("#price").val();
        var name = $("#searchName").val();
        var category_id = $("#category_id").val();
        var admin = $("#admin").val();
        var brand = $("#brand").val();
        var condition = $("#condition").val();

        $("#loading_div").show();

        $.post("<?php echo  Yii::getAlias('@web').DIRECTORY_SEPARATOR.'admin/tagged/a/create'?>",
            {
                category_id: category_id, name: name, price: price,
                admin: admin, brand: brand, condition: condition
            },
            function (result) {

                $("#products").html(result);
                $("#loading_div").hide();
                isCalling = true;
                incremnted_offset = $("#products .product-layout").size();

            });
    });


    function deleteBlock(id) {
        $("#playground_" + id).parent('.product-block-inner').parent('.product-thumb').parent('.product-layout').remove();
    }


    function addToPlayground(title, checkout_link, item_id) {
        current_item_id = item_id;
        current_title = title;
        current_checkout_link = checkout_link;

        /*$('html, body').animate({
         scrollTop: $(document).height()
         }, 1500);*/

    }


    function saveDraft() {
        var count = getCount();

        if (count > 0) {
            var name = $("#name").val();
            var description = $("#description").val();
            var html_content = $("#main_panel").html();
            $("#draft_html_content").val(html_content);

            var items_id = '';
            $(".tagged a").each(function (index) {
                var id = $(this).attr('id');
                var title = $(this).attr('alt');
                if (title != undefined) {
                    items_id += id + ",";
                }
            });

            items_id = items_id.substring(0, items_id.length - 1);
            $("#draft_items_id").val(items_id);

            if (name.length == 0) {
                $("#error_name").show();
                return false;
            }
            return $('#save_draft_form').submit();
        }
        else {

            $('.container-fluid').prepend('<div class="alert alert-warning alert-dismissable">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
                'you should upload new photo and tagging it before save it ...</div>');

            return false;
        }

    }


    function Publish() {
        var count = getCount();

        if (count > 0) {
            var name = $("#p_name").val();
            var description = $("#p_description").val();
            var html_content = $("#main_panel").html();
            $("#p_draft_html_content").val(html_content);

            var items_id = '';
            $(".tagged a").each(function (index) {
                var id = $(this).attr('id');
                var title = $(this).attr('alt');
                if (title != undefined) {
                    items_id += id + ",";
                }
            });

            items_id = items_id.substring(0, items_id.length - 1);
            $("#items_id").val(items_id);


            if (name.length == 0) {
                $("#p_error_name").show();
                return false;
            }
            return $('#publish_form').submit();
        }
        else {

            $('.container-fluid').prepend('<div class="alert alert-warning alert-dismissable">' +
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning : </strong> ' +
                'you should upload new photo and tagging it before save it ...</div>');

            return false;
        }

    }

    function resetForm() {

        location.href = "<?php echo  Yii::getAlias('@web').DIRECTORY_SEPARATOR.'admin/tagged/a/create'?>";

    }


    function getCount() {
        var count = $("#image_panel #planetmap .tagged").length;
        return count;
    }
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="resetLook" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reset?</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to discard changes and start over?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success" onclick="resetForm()">Yes</button>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="publish" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Publish</h4>
            </div>
            <div class="modal-body">
                <form action="publish" method="post" id="publish_form">
                    <input type="hidden" name='draft_html_content' id='p_draft_html_content'>

                    <input type="hidden" name='items_id' id='items_id'>

                    <div class="form-group">
                        <label for="draft_name" class="form-control-label">Name *:</label>
                        <input type="text" class="form-control" id="p_name" name="name" required="required">
                        <span class="error" id="p_error_name" style="display:none; color:red">Name is required</span>
                    </div>
                    <div class="form-group">
                        <label for="p_description" class="form-control-label">Description:</label>
                        <textarea class="form-control" id="p_description" name="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success" onclick="Publish();">Publish</button>
            </div>
        </div>
    </div>
</div>
</div>


<div class="modal fade" id="saveDraft" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Save</h4>
            </div>
            <div class="modal-body">
                <form action="savedraft" method="post" id="save_draft_form">
                    <input type="hidden" name='draft_html_content' id='draft_html_content'>
                    <input type="hidden" name='items_id' id='draft_items_id'>

                    <div class="form-group">
                        <label for="name" class="form-control-label">Name *:</label>
                        <input type="text" class="form-control" id="name" name="name" required="required">
                        <span class="error" id="error_name" style="display:none; color:red">Name is required</span>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-control-label">Description:</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success" onclick="saveDraft();">Save Draft</button>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $(".sidebar").delay(600).slideToggle('slow');
        $("#slide-left").hide();
        $("#slide-right").show();
    });

</script>