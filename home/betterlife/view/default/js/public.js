/**
 * 解决IE6 Css样式input[type=submit]兼容性的问题
 */
$(function() {
    var inputHover = function() {
        oldBgColor = $(this).css("background-color");
        $(this).css("color","#000");
        $(this).css("background-color","#fff");
        $(this).css("border","1px solid #fff");
    };
    var inputUnHover = function() {
        $(this).css("color","#FFF");
        if (this.type=="submit"){
            $(this).css("background-color","#000");
        }else{
            $(this).css("background-color","gray");
        }
        $(this).css("border","1px solid gray");
    };
    $(".inputNormal,.btnSubmit").focusin(inputHover);
    $(".inputNormal,.btnSubmit").focusout(inputUnHover);
    $(".inputNormal,.btnSubmit").hover(inputHover,inputUnHover);

});
