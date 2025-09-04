jQuery(function ($) {
  const $btn=$('#cd-ai-generate');
  const $ta=$('#cd_ai_summary');
  const $sp=$('#cd_ai_metabox .spinner');
  if(!$btn.length) return;
  $btn.on('click',function(){
    const postId=$(this).data('post');
    $sp.addClass('is-active');
    $btn.prop('disabled',true).text(CDAI.i18n.generating);
    $.ajax({
      url:CDAI.ajax_url,
      type:'POST',
      dataType:'json',
      data:{action:'cd_generate_ai_summary',nonce:CDAI.nonce,post_id:postId}
    })
    .done(function(res){
      if(res&&res.success&&res.data.summary) $ta.val(res.data.summary);
      else alert(CDAI.i18n.error);
    })
    .fail(function(){alert(CDAI.i18n.error);})
    .always(function(){
      $sp.removeClass('is-active');
      $btn.prop('disabled',false).text('Generate AI Summary');
    });
  });
});
