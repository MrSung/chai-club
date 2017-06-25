

/*
 * Author JS 
 */
 
(function($){
  'use strict';

  $('.header__btn').click(function(){
    $(this).toggleClass('is-active');
    $('.header__nav').slideToggle('fast');
  });

  $('.js-modal').click(function(){
    $('.login').fadeIn();
  });
  $('.login__close').click(function(){
    $('.login').fadeOut();
  });

  $('.js-register').click(function(){
    $('.form--login').hide();
    $('.form--register').show();
  });
  $('.js-login').click(function(){
    $('.form--register').hide();
    $('.form--login').show();
  });

  var formInputs = $('.login input');
  formInputs.focus(function() {
    $(this).parent().children('.formLabel').addClass('formTop');
  });
  formInputs.focusout(function() {
    if ($.trim($(this).val()).length == 0){
      $(this).parent().children('.formLabel').removeClass('formTop');
    }
  });

  $.validator.addMethod('alphanumeric', function(value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
  }, 'ユーザー名で使用可能文字は半角英数字です！');
  $('#form-register').validate({
    onfocusout: false,
    rules: {
      username: {
        required: true,
        minlength: 6,
        alphanumeric: true
      },
      password: {
        required: true,
        minlength: 6
      }
    },
    messages: {
      username: {
        required: "ユーザー名を入力してください！",
        minlength: "ユーザー名は6文字以上で入力してください！"
      },
      password: {
        required: "パスワードを入力してください！",
        minlength: "パスワードは6文字以上で入力してください！"
      }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
  $('#form-login').validate({
    onfocusout: false,
    rules: {
      username: "required",
      password: "required"
    },
    messages: {
      username: "ユーザー名を入力してください！",
      password: "パスワードを入力してください！"
    },
    submitHandler: function(form) {
      form.submit();
    }
  });

  $('.productThumb--wrap a').hover(function(){
    $(this).find('.productThumb__desc').prevAll().fadeToggle();
  });
  
  $('.label__close').click(function(){
    $(this).parent().hide();
  });

})(jQuery);