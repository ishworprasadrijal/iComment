<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <style type="text/css">.save,.cancel{display: none;} .edited{display: none;} .upload_icon{display: none;} img{width:100px;padding:3px;}</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>



<?php 
  $comments = (object) array(
    0 => (object) array(
      'id' => 1,
      'avatar'=>'2.png',
      'name'=>'Tika Dahal',
      'comment'=>'This is my first comment on this topic. Mr Rijal developed this awesome commenting plugin. Enjoy it.'
    ),

    1 => (object)  array(
      'id' => 2,
      'avatar'=>'2.png',
      'name'=>'Yam Aryal',
      'comment'=>'This is my second comment on this topic. Mr Rijal developed this awesome commenting plugin. Enjoy it.'
    ),

    2 => (object)  array(
      'id' => 3,
      'avatar'=>'2.png',
      'name'=>'Arbind Thakur',
      'comment'=>'This is my third comment on this topic. Mr Rijal developed this awesome commenting plugin. Enjoy it.'
    ),

    3 => (object) array(
      'id' => 4,
      'avatar'=>'2.png',
      'name'=>'Suman Lamsal',
      'comment'=>'This is my fourth comment on this topic. Mr Rijal developed this awesome commenting plugin. Enjoy it.'
    ),
  );
?>



<div class="container">
  <h2>My Comments</h2>
  <div class="alert alert-success" style="display: none;position: fixed;width: 100%; height: 50px;"></div>
  <div class="clearfix"></div>
  <hr/>

  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
  quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
  consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
  cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
  proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><hr/>
  
  <?php foreach($comments as $key => $comment): ?>
    <div class="media" data-id="<?=$comment->id;?>">
      <div class="media-left">
        <img src="<?=$comment->avatar;?>" class="media-object" style="width:60px">
      </div>
      <div class="media-body">
        <h4 class="media-heading user"><?=$comment->name;?></h4>
        <p class="comment"><?=$comment->comment;?></p>
        <div class="inputs">
          <textarea class="form-control edited"><?=$comment->comment;?></textarea>
          <div class="clearfix"></div>
          <label for="file_<?=$comment->id;?>" class="upload_icon"><span class="glyphicon glyphicon-picture"></span></label><input type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*" multiple="multiple" name="avatar[]" id="file_<?=$comment->id?>" class="image_input" style="display:none;">
        </div>
        <div class="row image_holder"></div>
        <div class="row old_image_holder">
          <?
          $results=glob("uploads/*_".$comment->name."_*",GLOB_BRACE);
          foreach($results as $key => $result){ ?>
            <?php 
              $extension = pathinfo($result, PATHINFO_EXTENSION);
              if(in_array($extension,array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'tiff'))){
                echo '<img src="'.$result.'">';              
              }elseif(in_array($extension,array('doc', 'msword', 'docx', 'txt', 'rtf', 'xls', 'xlsx', 'ppt', 'pptx', 'csv'))){
                echo '<img src="knownfile.png">';
              }elseif(in_array($extension,array('pdf'))){
                echo '<img src="pdf.png">';
              }elseif(!in_array($extension,array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'tiff', 'pdf', 'doc', 'docx', 'txt', 'rtf', 'xls', 'xlsx', 'ppt', 'pptx', 'csv'))){
                echo '<img src="unknown.png">';
              }
              ?>
          <?php }  ?>
            
          </div>
        <div class="clearfix">
          <span class="btn edit btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></span>
          <span class="btn save btn-xs btn-success"><span class="glyphicon glyphicon-floppy-save"></span></span>
          <span class="btn cancel btn-xs btn-info"><span class="glyphicon glyphicon-repeat"></span></span>
          <span class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></span>
        </div>
      </div>
    </div>
    <hr>
  <?php endforeach;?>
</div>

</body>



<script type="text/javascript">
  
  function save(elem, id){
    var comment = $(elem).find('textarea').val();
    var user = $(elem).find('.user').html();
    var files = get_images(elem);
    var url = "server.php";
    $.ajax({
      url : url,
      type : 'post',
      dataType : 'json',
      data :{id:id, comment:comment, user:user, files:files},
      success:function(response){
        $('.alert').html(response.message);
        $(elem).find('.image_holder').html('');
        $(elem).find('.old_image_holder').html(response.files);
        flash();
      }
    })
  }

  function get_images(elem){
    var images = [];
    $(elem).find('.image_holder img').each(function(){console.log($(this).attr('src'));
      images.push($(this).attr('src'));
    })
    return images;
  }
    

  $(document).on('click','.edit',function(e){
    var media = $(this).closest('.media');
    var id = $(media).data('id');
    var comment = $(media).find('.comment').html();
    editmode(media);
  })

  $(document).on('click','.save',function(e){
    var media = $(this).closest('.media');
    var id = $(media).data('id');
    save(media, id);
    savemode(media);
  })

  $(document).on('keypress','textarea',function(e){
    var comment = $(this).val();
    var media = $(this).closest('.media');
    $(media).find('.comment').html(comment);
    var id = $(media).data('id');
  })

  $(document).on('change','textarea',function(e){
    var comment = $(this).val();
    var media = $(this).closest('.media');
    $(media).find('.comment').html(comment);
    var id = $(media).data('id');
  })

  function flash(){
    $('.alert').delay(2000).show();
    $('.alert').fadeOut(2000);
  }

  function savemode(media){
    $(media).find('.comment').show();
    $(media).find('.edited').hide();
    $(media).find('.upload_icon').hide();
    $(media).find('.edit').show();
    $(media).find('.save').hide();
    $(media).find('.cancel').hide();
  }

  function editmode(media){
    $(media).find('.comment').hide();
    $(media).find('.edited').show();
    $(media).find('.upload_icon').show();
    $(media).find('.edit').hide();
    $(media).find('.save').show();
    $(media).find('.cancel').show();
  }

  $(document).on('click','.cancel',function(){
    var media = $(this).closest('.media');
    savemode(media);
  })




   function readURL(input) {
    var media = $(input).closest('.media');
    if (input.files) {
      var filesAmount = input.files.length;
      for (i = 0; i < filesAmount; i++) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $(media).find('.image_holder').append('<img src='+ e.target.result +' style="max-width:100px;padding:2px;"/>');
        }
        reader.readAsDataURL(input.files[i]);
      }
    }
    $(media).find('.image_holder').hide().show(1000);
  }

  $(".image_input").change(function() {
    readURL(this);
  });

</script>
</html>
