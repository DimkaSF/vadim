<div class="col-12 col-md-4 d-none">
    <img src="{{asset("/img/logo_w.png")}}" alt="Лого" height="100px" />
</div>
<div class="col-12 col-md-6 links flex-row-reverse">
      <a target="_blank" href="https://t.me/79159983117"><i class="fa fa-telegram align-self-center"></i></a>
      <a target="_blank" href="https://wa.me/79159983117"><i class="fa fa-whatsapp align-self-center" data-toggle="modal" data-target="#Modal" aria-hidden="true"></i></a>
      <a href="https://www.facebook.com/zaichikovvadim" target="_blank">
          <i class="fa fa-facebook align-self-center" aria-hidden="true"></i>
      </a>
      <a><i class="fa fa-pinterest-p align-self-center" aria-hidden="true"></i></a>
      <a href="https://vk.com/vadeg76" target="_blank">
          <i class="fa fa-vk align-self-center" aria-hidden="true"></i>
      </a>
      <a href="http://instagram.com/vadim_zaichikov" target="_blank" >
          <i class="fa fa-instagram align-self-center" aria-hidden="true"></i>
      </a>

    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                Номер телефона скопирован в буфер обмена!<br /> Обязательно свяжитесь со мной если возникли вопросы.
            </div>
        </div>
    </div>
</div>
<div class="col-12 col-md-2 phone showtel align-self-center d-none">
    +7 906 526 21 25
</div>

<script type="text/javascript">
    $(".showtel").on("click", function(){
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val("+79065262125").select();
        document.execCommand("copy");
        $temp.remove();
    });
</script>
