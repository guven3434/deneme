<?php
session_start();
if (!isset($_SESSION["Giris"])) {
    header("Location:login.php");
    return;
}	
include("../ayarlar.php");
?> 

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Yönetim Paneli</title>
	<meta name="author" content="İka Oto"/>
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="style/css/bootstrap/bootstrap.min.css" />
	
	<!-- RTL support - for demo only -->

	<!-- 
	If you need RTL support just include here RTL CSS file 
    <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
	And add "rtl" class to <body> element - e.g. <body class="rtl"> 
	-->
	
	<!-- libraries -->
	<link rel="stylesheet" type="text/css" href="style/css/libs/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="style/css/libs/nanoscroller.css" />

	<!-- global styles -->
	<link rel="stylesheet" type="text/css" href="style/css/compiled/theme_styles.css" />

	<!-- this page specific styles -->
    	<link rel="stylesheet" href="style/css/libs/morris.css" type="text/css" />
	<link rel="stylesheet" href="style/css/libs/daterangepicker.css" type="text/css" />
	<link rel="stylesheet" href="style/css/libs/jquery-jvectormap-1.2.2.css" type="text/css" />
	<link rel="stylesheet" href="style/css/libs/weather-icons.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="style/css/libs/dataTables.fixedHeader.css">
	<link rel="stylesheet" type="text/css" href="style/css/libs/dataTables.tableTools.css">
	<link rel="stylesheet" type="text/css" href="style/css/libs/dropzone.css">
    <link rel="stylesheet" type="text/css" href="style/css/libs/magnific-popup.css">
    <link rel="stylesheet" type="text/css" href="style/css/compiled/wizard.css">
	<!-- Favicon -->
	<link type="image/x-icon" href="style/favicon.png" rel="shortcut icon" />
	<link rel="stylesheet" href="style/css/libs/select2.css" type="text/css" />
       <link rel="stylesheet" type="text/css" href="style/css/libs/dropzone.css">
	<!-- google font libraries -->
	<link href='http://fonts.googleapis.com/css?family=Verdana:400,600,700,300' rel='stylesheet' type='text/css'>

	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
    
 	<link rel="stylesheet" type="text/css" href="style/src/jquery.tagsinput.css" />



    
</head>
<body class="boxed-layout fixed-header theme-blue-gradient">
	<div id="theme-wrapper">
		<header class="navbar" id="header-navbar">
			<div class="container">
				<a href="index.php" id="logo" class="navbar-brand">
					<img src="style/img/logo.png" alt="" class="normal-logo logo-white"/>
					<img src="style/img/logo-black.png" alt="" class="normal-logo logo-black"/>
					<img src="style/img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
				</a>
				
				<div class="clearfix">
				<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bars"></span>
				</button>
				
				<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
					<ul class="nav navbar-nav pull-left">
						<li>
							<a class="btn" id="make-small-nav">
								<i class="fa fa-bars"></i>
							</a>
						</li>
						<?php
                        $genel_sorgu = $db->query("SELECT * FROM dilek WHERE okundu = '0' ORDER BY Id DESC", PDO::FETCH_ASSOC);
                        $toplam_kayit = $genel_sorgu->rowCount();
						$genel_sorgu2 = $db->query("SELECT * FROM yorumlar WHERE onay = '0' ORDER BY yId DESC", PDO::FETCH_ASSOC);
                        $toplam_kayit2 = $genel_sorgu2->rowCount();
						$genel_sorgu3 = $db->query("SELECT * FROM ikform ORDER BY Id DESC LIMIT 2", PDO::FETCH_ASSOC);
                        $toplam_kayit3 = $genel_sorgu3->rowCount();
						$topla = $toplam_kayit + $toplam_kayit2 + $toplam_kayit3 + $toplam_kayit4; 
                        echo '
<li class="dropdown hidden-xs">
<a class="btn dropdown-toggle" data-toggle="dropdown">
<i class="fa fa-bell"></i>
<span class="count">' . $topla . '</span>
</a>';
?>                        
<ul class="dropdown-menu notifications-list">
<li class="pointer">
<div class="pointer-inner">
<div class="arrow"></div>
</div>
</li>
<li class="item-header">YENİ GELEN MESAJLAR</li>
<?php
$sql = $db->query("SELECT * FROM yorumlar WHERE onay = '0' ORDER BY yId DESC LIMIT 3");
foreach ($sql as $a) {
echo '
<li class="item">
<a href="index.php?page=yorumgoruntule&id=' . $a["yId"] . '">
<i class="fa fa-comment"></i>
<span class="content">' . $a["adsoyad"] . ' <b>Yorum Gönderdi...</b></span>
<span class="time"><i class="fa fa-clock-o"></i></span>
</a>
</li>
';
}
?>
<?php
$sql = $db->query("SELECT * FROM dilek WHERE okundu = '0' ORDER BY Id DESC LIMIT 3");
foreach ($sql as $a) {
echo '
<li class="item">
<a href="index.php?page=mailgoruntule&id=' . $a["Id"] . '">
<i class="fa fa-envelope-o"></i>
<span class="content">' . $a["adsoyad"] . ' <b>İletişim Formundan Yazdı.</b></span>
<span class="time"><i class="fa fa-clock-o"></i></span>
</a>
</li>
';
}
?>
<?php
$sql = $db->query("SELECT * FROM ikform ORDER BY Id DESC LIMIT 2");
foreach ($sql as $a) {
echo '
<li class="item">
<a href="../upload/cv2331/' . $a["dosya"] . '" target="_blank">
<i class="fa fa-users"></i>
<span class="content">' . $a["baslik"] . ' <b>İş Başvuru Formu Gönderdi.</b></span>
<span class="time"><i class="fa fa-clock-o"></i></span>
</a>
</li>
';
}
?>
</ul>
</li>
					</ul>
				</div>
				
				<div class="nav-no-collapse pull-right" id="header-nav">
					<ul class="nav navbar-nav pull-right">
	       <li class="profile-dropdown">
							<a href="../index.php" target="_blank" class="btn">
								<i class="fa fa-laptop"></i> Mağazaya Git
							</a>
						</li>
						<li class="profile-dropdown">
							<a href="sistem/cikis.php" class="btn">
								<i class="fa fa-power-off"></i> Çıkış 
							</a>
						</li>
               
					</ul>
				</div>
				</div>
			</div>
		</header>
        
		<div id="page-wrapper" class="container">
			<div class="row">
				<div id="nav-col">
					<section id="col-left" class="col-left-nano">
						<div id="col-left-inner" class="col-left-nano-content">
							<div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
								<img alt="" src="style/img/yonetici.png" />
								<div class="user-box">
								
<?php
$sql = $db->query("SELECT * FROM yoneticiler ORDER BY Id ASC", PDO::FETCH_ASSOC);
foreach($sql as $a){ 
 $adsoyad = $a["adsoyad"];
echo '	<span class="name">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"> ' . $a["adsoyad"] . '
<i class="fa fa-angle-down"></i>
</a>
<ul class="dropdown-menu">
<li><a href="index.php?page=yoneticiduzenle&id=' . $a["id"] . '"><i class="fa fa fa-cog"></i>Yönetici Düzenle</a></li>
<li><a href="sistem/cikis.php"><i class="fa fa-power-off"></i>Çıkış Yap</a></li>
</ul>	</span>';	
}
?>
								
									<span class="status">
										<i class="fa fa-circle"></i> Bağlandı
									</span>
								</div>
							</div>
							<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
								<ul class="nav nav-pills nav-stacked">
									<li class="nav-header nav-header-first hidden-sm hidden-xs">
								İÇERİK YÖNETİMİ
									</li>
									<li>
										<a href="index.php">
											<i class="fa fa-home"></i>
											<span>Ana Sayfa</span>
										
										</a>
									</li>
                                    
                                    
<?php
$sql6 = $db->query("SELECT * FROM menu_admin WHERE ust_menu = '0' ORDER BY sira ASC");
foreach($sql6 as $om){
$menuadi = $om["menuadi"];
$icon = $om["icon"];
	if ($om[sec] == $_GET["menusec"]){
					$durum = 'active';	
					} else {
					$durum = "";} 
echo ' 	<li class="'.$durum.' ozel-bg">
                                        <a href="#" class="dropdown-toggle">
											<i class="fa ' . $icon . '"></i>
											<span>' . $menuadi . '</span>
											<i class="fa fa-angle-right drop-icon"></i>
										</a>
<ul class="submenu">'; 
$sql7 = $db->prepare("SELECT * FROM menu_admin WHERE ust_menu = ? ORDER BY Id ASC");
$sql7->execute(array(
$om["Id"]
));
foreach($sql7 as $h){		 
$sayfaurl = $h["link"];
$menuget = $h["sec"];
$menuadi = $h["menuadi"];
$iconlar = $h["icon"];	  
		  echo '<li>
												<a href="index.php?page=' . $sayfaurl . '&menusec=' . $menuget . '">
												<i class="fa ' . $iconlar . '"></i>	' . $menuadi . '
												</a>
											</li>
							';  
		  }
		  echo '
                                 
										</ul>
									</li>'; 
									

}	

	  ?>
 <?php                                   
if ('frm' == $_GET["menusec"]){
$durum19 = 'active';	
} else {
$durum19 = "";} 
echo ' 
<li class="'.$durum19.' ozel-bg">
<a href="#" class="dropdown-toggle">
<i class="fa fa-file"></i>
<span>Gelen Form & Mesajlar</span>
<i class="fa fa-angle-right drop-icon"></i>
</a>
<ul class="submenu">
<li>
<a href="index.php?page=gelenyorumlar&menusec=frm">
<i class="fa fa-list"></i>	Müşteri Yorumları
</a>
</li>
<li>
<a href="index.php?page=isbasvurusu&menusec=frm">
<i class="fa fa-list"></i>	İş Başvuru Formları
</a>
</li>
<li>
<a href="index.php?page=ebulten&menusec=frm">
<i class="fa fa-list"></i>	E-Bülten Kayıtları
</a>
</li>
</ul>
</li>';
?>                          
                                    

									<li class="nav-header hidden-sm hidden-xs">
									SİTE GENEL AYARLAR
									</li>
 <?php                                   
if ('my' == $_GET["menusec"]){
$durum9 = 'active';	
} else {
$durum9 = "";} 
echo ' 
<li class="'.$durum9.' ozel-bg">
<a href="#" class="dropdown-toggle">
<i class="fa fa-desktop"></i>
<span>Site Ayarları</span>
<i class="fa fa-angle-right drop-icon"></i>
</a>
<ul class="submenu">
<li>
<a href="index.php?page=genelayarlar&menusec=my">
<i class="fa fa-cogs"></i>	Genel Ayarlar
</a>
</li>
<li>
<a href="index.php?page=anasayfayonetimi&menusec=my">
<i class="fa fa-cogs"></i>	Ana Sayfa Blok Yönetimi
</a>
</li>
<li>
<a href="index.php?page=menuyonetimi&menusec=my">
<i class="fa fa-cogs"></i>	Menü  Yönetimi
</a>
</li>
</ul>
</li>';
?>
								
										<li class="ozel-bg">
												<a href="index.php?page=mailayarlari">
													<i class="fa fa-envelope-o"></i>	
                                                  <span>  Smtp Mail Ayarları</span>
												</a>
											</li>
										<li class="ozel-bg">
												<a href="index.php?page=iletisimyonetimi&id=1">
													<i class="fa fa-map-marker"></i>
                                                  <span>  İletişim Ayarları</span>
												</a>
											</li>
											<li class="ozel-bg">
												<a href="index.php?page=yoneticiduzenle&id=1">
												<i class="fa fa-user"></i>	
                                               <span> Yönetici Ayarları</span>
												</a>
											</li>
	
								</ul>
							</div>
						</div>
					</section>
					<div id="nav-col-submenu"></div>
				</div>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<div id="content-header" class="clearfix">
										<div class="pull-left">

											<h1>Yönetim Paneli</h1>
                                            
                                            			<ol class="breadcrumb">
										<li><a href="index.php">Ana Sayfa</a></li>
										<li class="active"><span><?php echo $_GET["page"]; ?></span></li>
									</ol>
										</div>

										<div class="pull-right hidden-xs">
											<div class="xs-graph pull-left">
											
                                            
                                                <div class="graph-label">
													 <i class="fa fa-check-square-o"></i> Lisans: <b><?php echo "$_SERVER[SERVER_NAME]"; ?>  </b> 
												</div>
                                                	
													 <div class="graph-label">
													<b> <i class="fa fa-globe"></i> <?php  echo "$_SERVER[SERVER_ADDR]";  ?> </b> 
												</div>
                                                
                                                
												<div class="graph-content spark-orders"></div>
											</div>

										
										</div>
									</div>
								</div>
							</div>

                       <!-- Fonsiyonlar Başlangıç -->
            <!-- Değişiklik Yapmayınız, Web Paketiniz Çalışmayabilir.-->
       
	
             <?php  include("ofisimo.php");?> 
             <!-- Fonksiyonlar Bitiş -->				
					
					<footer id="footer-bar" class="row">
						<p id="footer-copyright" class="col-xs-12">
						Copyright &copy; 2023 - <?php echo date("Y");?> <a href="http://ikaoto.com" target="_blank" />İKA OTO</a> İka Group
						</p>
					</footer>
				</div>
			</div>
		</div>
	</div>
		

	
	<!-- global scripts -->
	
	<script src="style/js/jquery.js"></script>
    <script src="style/js/jquery.min.js"></script>
	<script src="style/js/bootstrap.js"></script>
	<script src="style/js/jquery.nanoscroller.min.js"></script>
	
	<script type="text/javascript" src="style/src/jquery.tagsinput.js"></script>
	<!-- To test using the original jQuery.autocomplete, uncomment the following -->
	<!--
	<script type='text/javascript' src='http://xoxco.com/x/tagsinput/jquery-autocomplete/jquery.autocomplete.min.js'></script>
	<link rel="stylesheet" type="text/css" href="http://xoxco.com/x/tagsinput/jquery-autocomplete/jquery.autocomplete.css" />
	-->
	<script type="text/javascript">

		function onAddTag(tag) {
			alert("Kelime Yazın: " + tag);
		}

		$(function() {

			$('#tags_1').tagsInput({width:'auto'});


// Uncomment this line to see the callback functions in action
//			$('input.tags').tagsInput({onAddTag:onAddTag,onRemoveTag:onRemoveTag,onChange: onChangeTag});

// Uncomment this line to see an input with no interface for adding new tags.
//			$('input.tags').tagsInput({interactive:false});
		});

	</script>   
    
	<!-- this page specific scripts -->
	<script src="style/js/moment.min.js"></script>
    <script src="style/js/jquery.slimscroll.min.js"></script>
	<script src="style/js/jquery.easypiechart.min.js"></script>
	<script src="style/js/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="style/js/jquery-jvectormap-world-merc-en.js"></script>
	<script src="style/js/gdp-data.js"></script>
	<script src="style/js/jquery.sparkline.min.js"></script>
	<script src="style/js/skycons.js"></script>
		<!-- this page specific scripts -->
     	<script src="style/js/wizard.js"></script>   
	<script src="style/js/jquery.dataTables.js"></script>
	<script src="style/js/jquery.dataTables.bootstrap.js"></script>
    	<!-- this page specific scripts -->
	<script src="style/js/jquery.maskedinput.min.js"></script>
	<script src="style/js/bootstrap-datepicker.js"></script>
	<script src="style/js/moment.min.js"></script>
	<script src="style/js/daterangepicker.js"></script>
	<script src="style/js/bootstrap-timepicker.min.js"></script>
	<script src="style/js/select2.min.js"></script>
	<script src="style/js/hogan.js"></script>
	<script src="style/js/typeahead.min.js"></script>
	<script src="style/js/jquery.pwstrength.js"></script>
<!-- this page specific scripts -->
	<script src="style/js/ckeditor/ckeditor.js"></script>
    	<!-- theme scripts -->
	<script src="style/js/dropzone.js"></script>
	<script src="style/js/jquery.magnific-popup.min.js"></script>
    	<!-- this page specific scripts -->
	<script src="style/js/jquery.nestable.js"></script>

	<script src="style/js/scripts.js"></script>
	<script src="style/js/pace.min.js"></script>


<script>
	$(document).ready(function() {
		var table = $('#table-example').dataTable({

			'sDom': 'lTfr<"clearfix">tip',
		
		});
		var table = $('#table-example3').dataTable({

			'sDom': 'lTfr<"clearfix">tip',
		
		});
		var table = $('#table-example2').dataTable({

		'sDom': 'lTfr<"clearfix">tip',
		
		});
		var table = $('#table-example4').dataTable({

			'sDom': 'lTfr<"clearfix">tip',
		
		});
		var table = $('#table-example5').dataTable({

			'sDom': 'lTfr<"clearfix">tip',
		
		});
	});
			$('.chart').easyPieChart({
			easing: 'easeOutBounce',
			onStep: function(from, to, percent) {
				$(this.el).find('.percent').text(Math.round(percent));
			},
			barColor: '#3498db',
			trackColor: '#f2f2f2',
			scaleColor: false,
			lineWidth: 8,
			size: 130,
			animate: 1500
		});
	</script>

	<script>
	$(function($) {
		//tooltip init
		$('#exampleTooltip').tooltip();

		//nice select boxes
		$('#sel2').select2();
		
		$('#sel2Multi').select2({
			placeholder: 'Kategori Seçiniz',
			allowClear: true
		});
	
		//masked inputs
		$("#maskedDate").mask("99/99/9999");
		$("#maskedPhone").mask("(999) 999-9999");
		$("#maskedPhoneExt").mask("(999) 999-9999? x99999");
		$("#maskedTax").mask("99-9999999");
		$("#maskedSsn").mask("999-99-9999");
		
		$("#maskedProductKey").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
		
		$.mask.definitions['~']='[+-]';
		$("#maskedEye").mask("~9.99 ~9.99 999");
	
		//datepicker
		$('#datepickerDate').datepicker({
		  format: 'mm-dd-yyyy'
		});

		$('#datepickerDateComponent').datepicker();
		
		//daterange picker
		$('#datepickerDateRange').daterangepicker();
		
		//timepicker
		$('#timepicker').timepicker({
			minuteStep: 5,
			showSeconds: true,
			showMeridian: false,
			disableFocus: false,
			showWidget: true
		}).focus(function() {
			$(this).next().trigger('click');
		});
		
		//autocomplete simple
		$('#exampleAutocompleteSimple').typeahead({                              
			prefetch: '/style/data/countries.json',
			limit: 10
		});
		
		//autocomplete with templating
		$('#exampleAutocomplete').typeahead({                              
			name: 'twitter-oss',                                                        
			prefetch: '/style/data/repos.json',                                             
			template: [                                                              
				'<p class="repo-language">{{language}}</p>',                              
				'<p class="repo-name">{{name}}</p>',                                      
				'<p class="repo-description">{{description}}</p>'                         
			].join(''),                                                                 
			engine: Hogan                                                               
		});
		
		//password strength meter
		$('#examplePwdMeter').pwstrength({
			label: '.pwdstrength-label'
		});
		
	});
	</script> 
		<script>
	$(function() {
		$(document).ready(function() {
			$('#gallery-photos-lightbox').magnificPopup({
				type: 'image',
				delegate: 'a',
				gallery: {
					enabled: true
			    }
			});
		});
	});
	</script>

	<script>
	$(function () {
		$('#myWizard').wizard();
		
		//masked inputs
		$("#maskedDate").mask("99/99/9999");
		$("#maskedPhone").mask("(999) 999-9999");
		$("#maskedPhoneExt").mask("(999) 999-9999? x99999");
	});
	</script>
    
<script>
$(document).ready(function()
{
    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };
    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);
    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));
    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });
});
</script>

<script>
  $(document).ready(function(){
    $("#load").hide();
    $("#submit").click(function(){
       $("#load").show();

       var dataString = { 
              label : $("#label").val(),
              link : $("#link").val(),
			  href : $("#href").val(),
              id : $("#id").val()
            };

        $.ajax({
            type: "POST",
            url: "sistem/save_menu.php",
            data: dataString,
            dataType: "json",
            cache : false,
            success: function(data){
              if(data.type == 'add'){
                 $("#menu-id").append(data.menu);
              } else if(data.type == 'edit'){
                 $('#label_show'+data.id).html(data.label);
              }
              $('#label').val('');
              $('#link').val('');
			   $('#href').val('');
              $('#id').val('');
              $("#load").hide();
            } ,error: function(xhr, status, error) {
              alert(error);
            },
        });
    });

    $('.dd').on('change', function() {
        $("#load").show();
     
          var dataString = { 
              data : $("#nestable-output").val(),
            };

        $.ajax({
            type: "POST",
            url: "sistem/save.php",
            data: dataString,
            cache : false,
            success: function(data){
              $("#load").hide();
            } ,error: function(xhr, status, error) {
              alert(error);
            },
        });
    });

    $("#save").click(function(){
         $("#load").show();
     
          var dataString = { 
              data : $("#nestable-output").val(),
            };

        $.ajax({
            type: "POST",
            url: "sistem/save.php",
            data: dataString,
            cache : false,
            success: function(data){
              $("#load").hide();
              alert('Başarılı bir şekilde Güncellendi.');
          
            } ,error: function(xhr, status, error) {
              alert(error);
            },
        });
    });

 
    $(document).on("click",".del-button",function() {
        var x = confirm('Alt Menüleri varsa komple silinir! Menü Silinsin mi?');
        var id = $(this).attr('id');
        if(x){
            $("#load").show();
             $.ajax({
                type: "POST",
                url: "sistem/delete.php",
                data: { id : id },
                cache : false,
                success: function(data){
                  $("#load").hide();
                  $("li[data-id='" + id +"']").remove();
                } ,error: function(xhr, status, error) {
                  alert(error);
                },
            });
        }
    });

    $(document).on("click",".edit-button",function() {
        var id = $(this).attr('id');
        var label = $(this).attr('label');
        var link = $(this).attr('link');
        $("#id").val(id);
        $("#label").val(label);
        $("#link").val(link);
    });

    $(document).on("click","#reset",function() {
        $('#label').val('');
        $('#link').val('');
        $('#id').val('');
    });

  });

</script>
<script> 
$('#href').change(function(){

                    var value = $(this).val();

                    if(value=='other'){

                         $('.link').removeClass('hidden');

                         $('.link input').attr('required','required');

                    }else{

                         $('.link').addClass('hidden');

                         $('.link input').removeAttr('required');

                    }

               });
</script>
<script> 
$(document).on("click",".ac",function() {
$('.link').removeClass('hidden');
$('.link input').attr('required','required');
});
</script> 

</body>
</html>
<?php
ob_end_flush();
?>