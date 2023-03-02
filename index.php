<?php include ("includes/header.php");?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<script src="https://use.fontawesome.com/4ad898f606.js"></script>
<meta name="Keywords" content="<?=$ayar['sayfa_anahtar'];?>"/>
<meta name="Description" content="<?=$ayar['sayfa_aciklama'];?>"/>
<meta author="parstech">
<title>Anasayfa - <?=$ayar['sayfa_baslik'];?></title>
<?php include 'includes/navbar.php';?>
<?php include 'includes/slider.php';?>
<!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
<div class="main-content-area clearfix"> 
  <!-- =-=-=-=-=-=-= Car Inspection End =-=-=-=-=-=-= -->

  <style type="text/css">

    .column li {
      float: left;
      width: 25%;
      padding: 10px;
    }

    /* Style the images inside the grid */
    .column li img {
      opacity: 0.8; 
      cursor: pointer; 
      object-fit: cover;
      height: 100px;
    }

    .column li img:hover {
      opacity: 1;
    }


  </style>
  <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
  <section class="custom-padding gray"> 
    <div class="container">
     <div class="row">
      <!-- Heading Area -->
      <div class="heading-panel">
       <div class="col-xs-12 col-md-7 col-sm-6 left-side">
        <!-- Main Title -->
        <h1><span class="heading-color"> Vitrin İlanları</span></h1>
      </div>

    </div>
    <!-- Heading Area End -->        
    <div class="col-sm-12 col-xs-12 col-md-12">

      <div class="row">
        <!-- Sorting Filters -->

        <div class="grid-style-1">
         <div class="posts-masonry">
          <!-- Listing Ad Grid -->

          <?php

          $suankicat= (new Category())->get($_GET["category"]);
          $subs =  (new Categories)->getSubs($suankicat->id);
          $fullsubs = (new Categories)->getAllSubs($suankicat->id);
          $ww=[];
          if($suankicat)$ww[]= $suankicat->id;


          foreach ($fullsubs as $kat) {
           /** @var Category $kat */
           $ww[]=$kat->id;
         }

         
         if($_POST){ 
           $_SESSION["filtre"]=$_POST;
         }elseif($_GET["category"]){
           $_POST=  $_SESSION["filtre"];
         }
         



         $where=[];
         $filtre=false;
         $whereex=[];
         if(count($ww)>0){
           $where[]= "category in(".implode(",",$ww).")";
           $filtre= true;
         }



         $vites= $_POST["vites"];

         if($vites){
           $filtre= true;

           $in = "";
               $i = 0;// we are using an external counter 
               // because the actual array keys could be dangerous
               foreach ($vites as $item)
               {
                $key = ":arac_vites".$i++;
                $in .= "$key,";
                  $whereex[$key] = $item; // collecting values into a key-value array
                }
               $in = rtrim($in,","); // :id0,:id1,:id2

               $where[]="arac_vites in ($in)";
             }  


             $yil= $_POST["yil1"];
             $yil2= $_POST["yil2"];
             if(is_numeric($yil)){ 
               $filtre= true;
               $where []="arac_yil>=:yil1 and arac_yil<=:yil2 ";
               $whereex["yil1"] =  $yil ;  //implode(",",$_POST["yil"]);
               $whereex["yil2"] =  $yil2 ;   
             }  


             $fiyat1= $_POST["fiyat1"];
             $fiyat2= $_POST["fiyat2"];
             if(is_numeric($fiyat2)){ 
               $filtre= true;
               $where []="arac_fiyat>=:fiyat1 and arac_fiyat<=:fiyat2 ";
               $whereex["fiyat1"] =  $fiyat1 ;  //implode(",",$_POST["yil"]);
               $whereex["fiyat2"] =  $fiyat2 ;   
             }  





             if($filtre)    $filtre= "where  ".implode(" and ",$where);


             $sonucinstaayar["sayfabasi"] = 5;
             $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;   


             $sorgu = $dbh->prepare("SELECT * FROM proje $filtre  where vitrin=1  order by id desc  LIMIT 9 " );
             $sorgu2 = $dbh->prepare("SELECT * FROM proje $filtre");

             $sorgu->execute($whereex);
             $sorgu2->execute($whereex);
             $sorgukayitsayisi= $sorgu2->rowCount();

             while ($sonuc = $sorgu->fetch()) {
              $id = $sonuc['id']; 
              $arac_marka = $sonuc['arac_marka'];
              $arac_model = $sonuc['arac_model'];
              $arac_tip  = $sonuc['arac_tip'];
              $arac_vites = $sonuc['arac_vites'];
              $arac_yil = $sonuc['arac_yil'];
              $arac_baslik = $sonuc['arac_baslik'];
              $arac_aciklama = $sonuc['arac_aciklama'];
              $arac_fiyat = $sonuc['arac_fiyat'];
              $foto = $sonuc['foto'];
              $tarih = $sonuc['tarih'];
              $baslikseo=seo( $sonuc['arac_baslik']); 
              $check = $dbh->query("SELECT * FROM arac_marka WHERE id = '".$arac_marka."' ", PDO::FETCH_ASSOC);
              if ($check->rowCount()) {
               foreach ($check as $check) {
               }
               $kategori = $check["marka_baslik"];
             }
             $checkx = $dbh->query("SELECT * FROM arac_model WHERE id = '".$arac_model."' ", PDO::FETCH_ASSOC);
             if ($checkx->rowCount()) {
               foreach ($checkx as $checkx) {
               }
               $kategorix = $checkx["model_baslik"];
             }  
             $checkxx = $dbh->query("SELECT * FROM arac_tip WHERE id = '".$arac_tip."' ", PDO::FETCH_ASSOC);
             if ($checkxx->rowCount()) {
              foreach ($checkxx as $checkxx) {
              }
              $kategorixx = $checkxx["tip_baslik"];
            }               

            ?>

            <div class="col-md-4 col-sm-4 col-xs-12  ">
             <div class="white category-grid-box-1 ">
               <div class="featured-ribbon">
                 <span>VİTRİN</span>
               </div>
               <!-- Image Box -->
               <div class="image"><a title="İlan No: <?php echo $sonuc['id'];?>" href="ilan-<?=$baslikseo?>-i<?=$sonuc['id'];?>"> <img src="img/<?=$sonuc['foto'];?>" class="img-responsive"></a></div>
               <div class="ad-info-1">
                 <ul>
                  <li><i class="flaticon-fuel-1"></i> <?=$sonuc['arac_yakit'];?></li>
                  <li><i class="flaticon-dashboard"></i> <?=$sonuc['arac_km'];?> km </li>
                  <li><i class="flaticon-calendar-2"></i> <?=$sonuc['arac_yil'];?></li>
                </ul>
              </div>
              <!-- Short Description -->
              <div class="short-description-1 ">
               <h6><center>
                 <?php
                 $k = new Category();$katlar=[];
                 $k->get($sonuc["category"]); 
                 while($k->id!=0){
                   $katlar[]= clone $k;
                   $k=$k->getParent();

                 }


                 $katlar = array_reverse( $katlar);
                 foreach ($katlar as $k=>$kat) {

                   ?>
                   <a href="araclarimiz-<?=seo($kat->name)?>-k<?=$kat->id?>"> <font color="#db1940"><?=$kat->name?></font></a> <? 
                   if(count($katlar)-1 != $k){
                     ?>
                     /
                     <?php
                   }


                 ?>
                       - <a title="İlan No: <?=$sonuc['id'];?>"><?=$sonuc['id'];?></a>
               </center> </h6><br>
               <!-- Location --> 
               <p><center><a title="<?=$sonuc['arac_baslik'];?>" href="ilan-<?=$baslikseo?>-i<?=$id?>"><?=$sonuc['arac_baslik'];?></a></center></p>
               <hr>
               <!-- Ad Meta Stats -->
               <span class="ad-price"><?=kutuphane::paraformat( $sonuc['arac_fiyat'])?> TL</span>
               <button data-target="#ad-preview<?=$sonuc['id'];?>" data-toggle="modal" class="btn btn-danger margin-bottom-10 pull-right" type="button" style="background-color: #000; border-color: #000; padding: 10px;"> <i class="far fa-eye"></i></button>
               <a title="İlan No: <?=$sonuc['id'];?>" class="btn btn-danger margin-bottom-10 pull-right" href="tel:0<?=$ayar['telefon'];?>"><i class="fa fa-phone"></i> Bilgi Al</a>

             </div>
           </div>
           <!-- Product Preview Popup -->
           <div class="quick-view-modal modalopen" id="ad-preview<?=$sonuc['id'];?>" tabindex="-1" role="dialog" aria-hidden="true" style="    overflow: auto;">
             <div class="modal-dialog modal-lg ad-modal">
              <button class="close close-btn popup-cls" aria-label="Close" data-dismiss="modal" type="button"> <i class="fa-times fa"></i> </button>
              <div class="modal-content single-product">
               <div class="diblock">
                <h4> <font color="black"> <b><center>+ <?=$sonuc['arac_baslik'];?></center></b></font></h4>
                <div class="col-lg-7 col-sm-12 col-xs-12"> 
                 <div class="clearfix"></div>
                 
                 <div>
                  <center>
                    <img id="expandedImg<?=$sonuc['id'];?>" src="img/<?=$sonuc['foto'];?>" style="    height: 256px;
                    object-fit: cover;">
                  </center>
                </div>
                <div>
                  <ul class="column" >
                    <li><img alt="" src="img/<?=$sonuc['foto'];?>" id="<?=$sonuc['id'];?>" onclick="myFunction(this);"></li>

                    <?php

                    $sorgux = $dbh->prepare("SELECT * FROM proje_galeri where kategori_sec='".$sonuc["id"]."' order by rand() limit 3");
                    $sorgux->execute(); 
                    while ($sonucx = $sorgux->fetch()) {


                      $foto = $sonucx['foto'];
                      ?>
                      <li><img alt="" src="img/<?=$sonucx['foto'];?>" id="<?=$sonuc['id'];?>" onclick="myFunction(this);"></li>
                    <?php } ?>



                  </ul>
                </div>
              </div>
              <div class=" col-sm-12 col-lg-5 col-xs-12">
               <div class="summary entry-summary">
                <div class="ad-preview-details">
                  <br>
                  <div class="overview-price">
                    <span><?=kutuphane::paraformat( $sonuc['arac_fiyat'])?> TL</span>
                  </div>
                  <div class="clearfix"></div> 
                  <ul class="ad-preview-info col-md-6 col-sm-6">
                    <li>
                     <span>Yakıt Türü:</span>
                     <p><?=$sonuc['arac_yakit'];?></p>
                   </li>
                   <li>
                     <span>Vites Türü:</span>
                     <p><?=$sonuc['arac_vites'];?></p>
                   </li>
                   <li>
                     <span>KM :</span>
                     <p><?=$sonuc['arac_km'];?> KM</p>
                   </li>

                 </ul>
                 <ul class="ad-preview-info col-md-6 col-sm-6">
                  <li>
                   <span>Renk:</span>
                   <p><?=$sonuc['arac_renk'];?></p>
                 </li>
                 <li>
                   <span>Motor Gücü:</span>
                   <p><?=$sonuc['arac_motor'];?> cc</p>
                 </li>
                 <li>
                   <span>Çıkış Tarihi:</span>
                   <p><?=$sonuc['arac_yil'];?></p>
                 </li>

               </ul>
               <button class="btn btn-theme btn-block" type="submit">Contact Dealer</button>
             </div>
           </div>
           <!-- .summary -->
         </div>
       </div>
     </div>
   </div>
 </div>
 <!-- / Product Preview Popup -->     
</div>
<!-- Listing Ad Grid -->


<?php } ?>
</div>
</div>

</div>
<!-- Row End -->



</div>
<!-- Top Dealer Ads  --> 
</div>
</div>
</div>
</section>

<section class="custom-padding">
  <!-- Main Container -->
  <div class="container">
   <!-- Content Box -->
   <!-- Row -->
   <div class="row">
    <!-- Heading Area -->
    <div class="heading-panel">
     <div class="col-xs-12 col-md-7 col-sm-6 left-side">
      <!-- Main Title -->
      <h1><span class="heading-color"> Son Haberler</span></h1>
    </div>

  </div>
  <style type="text/css">
   .sinifim img { width: 400px; height:250px; }
 </style>
 <!-- Middle Content Box -->
 <div class="col-md-12 col-xs-12 col-sm-12">
   <div class="row">
    <div class="posts-masonry" style="position: relative; height: 541.5px;">
     <!-- Blog Post-->
     <?php $habersorgu = $dbh->prepare("SELECT * FROM haber order by id desc limit 3 ");

     $habersorgu->execute();

     while ($habersonuc = $habersorgu->fetch()) {


      $id = $habersonuc['id'];

      $haber_baslik = $habersonuc['haber_baslik']; 

      $haber_aciklama = $habersonuc['haber_aciklama']; 

      $foto = $habersonuc['foto'];

      $tarih = $habersonuc['tarih'];

      $baslikseo=seo( $habersonuc['haber_baslik']);  

      ?>




      <div class="col-md-4 col-sm-6 col-xs-12" style="position: absolute; left: 0px; top: 0px;">
        <div class="blog-post">
         <div class="post-img sinifim" >
          <a href="haber-<?=$baslikseo?>-h<?=$id?>"> <img class="img-responsive" alt="" src="img/<?=$foto?>" > </a>

        </div>

        <h5> <center><a hhref="haber-<?=$baslikseo?>-h<?=$id?>"><?=$haber_baslik?></a></center> </h5>


        <center><a href="haber-<?=$baslikseo?>-h<?=$id?>"><strong>Devamını Oku</strong></a>
        </center>
      </div>
    </div>
  <? }?>

  <!-- Blog Grid -->
</div>
<div class="clearfix"></div>
</div>
</div>
<!-- Middle Content Box End -->
</div>
<!-- Row End -->
</div>
<!-- Main Container End -->
</section>


<!-- =-=-=-=-=-=-= Ads Archieve End =-=-=-=-=-=-= --> 
<style type="text/css">
 .boyutlandirma img {
  max-width: 350px;
  height: 300px;
}
</style>
<section class="custom-padding white over-hidden">
  <!-- Main Container -->
  <div class="container">
   <!-- Row -->
   <div class="row">
    <!-- Heading Area -->
    <div class="heading-panel">
     <div class="col-xs-12 col-md-12 col-sm-12 text-center">
      <!-- Main Title -->
      <h1>Instagram <span class="heading-color"> Son</span> Paylaşımlarımız</h1>
      <!-- Short Description -->
      <p class="heading-text">Instagram paylaşımlarımız</p>
    </div>
  </div>
  <!-- Middle Content Box -->
  <div class="col-md-12 col-xs-12 col-sm-12">
   <div class="row">
    <div class="featured-slider container owl-carousel owl-theme">

     <!-- başlar -->
     <?php
     $sorgu = $dbh->prepare("SELECT * FROM urunler_insta ");
     $sorgu->execute();
     $instaayar = $dbh->prepare("SELECT * FROM instaAyar ");
     $instaayar->execute();
     $sonucinstaayar = $instaayar->fetch();
     $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;   
     $sorgu2 = $dbh->prepare("SELECT * FROM urunler_insta limit ".(($page-1)* $sonucinstaayar["sayfabasi"]).",". $sonucinstaayar["sayfabasi"]        );

     $sorgu2->execute();


     while ($sonuc = $sorgu2->fetch()) {
      $id = $sonuc['id']; 
      $link = $sonuc['link']; 
      $resim = $sonuc['resim'];     
      $tarih = $sonuc['tarih'];    
      $phpdate = strtotime( $tarih );
      $mysqldate = date( 'd-m-Y ', $phpdate );  
      ?>
      <div class="item">
        <div class="grid-style-2">
         <!-- Listing Ad Grid -->
         <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="category-grid-box-1"> 
           <div class="image boyutlandirma">
            <img alt="Carspot" src="img/urunler/<?=$sonuc['resim'];?>" class="img-responsive">
            <div class="ribbon popular"></div>
            <div class="price-tag">
             <div class="price"><span>Instagram Son Verileri</span></div>
           </div>
         </div>
         <div class="short-description-1 clearfix">
          <div class="category-title"> <span><?php echo substr($sonuc['aciklama'],0,120); ?> ...</span> </div>
        </div>
        <div class="ad-info-1">
          <p><i class="flaticon-calendar"></i> &nbsp;<span><?=$mysqldate?></span> </p>
          <ul class="pull-right"> 
           <li> <a href="<?=$sonuc['link'];?>" target="_blank"><i class="flaticon-message"></i></a></li>
         </ul>
       </div>
     </div>
     <!-- Listing Ad Grid -->
   </div>
 </div>
</div>
<!-- biter -->
<?php } ?>


</div>
</div>
</div>
<!-- Middle Content Box End -->
</div>
<!-- Row End -->
</div>
<!-- Main Container End -->
</section>

<?php include 'includes/footer.php';?>

<script>
  function myFunction(imgs) {
    var expandImg = document.getElementById("expandedImg"+imgs.id);
    expandImg.src = imgs.src;
    console.log(imgs.id);
    expandImg.parentElement.style.display = "block";
  }
</script>
<?php } ?>