// Generated by CoffeeScript 1.6.1
(function(){jQuery(function(e){var t,n;n=e("#cws-links-to-custom-section");t=e("input[type=radio]","#page-links-to");t.filter('input[value="wp"]').prop("checked")&&n.fadeTo(1,0).hide();return t.change(function(){return e(this).val()==="wp"?n.fadeTo("fast",0,function(){return e(this).slideUp()}):n.slideDown("fast",function(){return e(this).fadeTo("fast",1,function(){var t;t=e("#cws-links-to");return t.focus().val(t.val())})})})})}).call(this);