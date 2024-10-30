<?php 
global $wpdb;
global $nslider;

$nslider++;

$res_select = $wpdb->get_row("SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key = '"."{$a['slider_id']}"."'");
$sliderid =[];
$frecce ="";
$indicatori="";
	if ($res_select != '' ) {
		$post_id = $res_select ->post_id;
		$slider_valueDb = $res_select ->meta_value;
		$sliderAA = json_decode($slider_valueDb);
		//$sliderid = explode(';',$sliderAA->idimg);		
		$idimg = lmeasy_control_media_esistenti($sliderAA->idimg, $post_id );
		if($idimg != ''){
			$sliderid = explode (";",$idimg);	
		}
		$frecce = $sliderAA->frecce;
		$indicatori = $sliderAA->indicatori;
		$descrizione = $sliderAA->descrizione;
		$titolo = $sliderAA->titolo;
		$angoli = $sliderAA->angoli;
		$speed = $sliderAA->speed;
		$pausa = $sliderAA->pausa;
		$blocco = $sliderAA->blocco;
	} 

$activeSlide = "active";

$sliderString ='<div id="lmes_carousel_captions-'.$nslider.'" class="carousel ';
if ($frecce != 'si'): 
	$sliderString .='carousel-nofrecce ';
endif;
$sliderString .='slide"';
if ($frecce != 'si'):
	$sliderString .='style="padding-right: 0px; padding-left: 0px;"';
endif;
$sliderString .='data-ride="carousel">';

if ($indicatori == 'si'):
	$sliderString .='<ol class="carousel-indicators">';
	if(sizeof($sliderid) > 0):
		for ($i = 0; $i < sizeof($sliderid); $i++) : 
			$sliderString .='<li data-target="#lmes_carousel_captions-'.$nslider.'" data-slide-to="'.$i.'" class="'.$activeSlide.'"></li>';
			$activeSlide ='';
		endfor;
	endif;
endif;
$sliderString .='</ol><div class="carousel-inner">';
$activeSlide = "active";

if(sizeof($sliderid) > 0):
	foreach($sliderid as $slide):
		$imgAttr = wp_get_attachment( $slide );
		$link = "{$imgAttr['link']}";

		if($link != ''){
			if(stripos($link, 'http')  !== 0  ){
				  $link = 'http://'.$link;
			}	
		}
		 
		$sliderString .='<div class="carousel-item '.$activeSlide.' slider_image '.$angoli.'" style="background-image: url('.wp_get_attachment_url($slide).')"';
		if($link != '') 
			$sliderString .='onClick=window.location=\''.$link.'\'>';
		else $sliderString .='>';

		$sliderString .='<div class="carousel-caption ';
		if (($titolo == 'si' && "{$imgAttr['title']}" != '') || ($descrizione == 'si' && "{$imgAttr['description']}" != '')):
			$sliderString .='carousel-caption-black ';
		endif;
		$sliderString .='d-none d-md-block">';	
		if ($titolo == 'si'):
			$sliderString .='<h5 title="'."{$imgAttr['title']}".'">'."{$imgAttr['title']}".'</h5>';
		endif;
		if ($descrizione == 'si'):
			$sliderString .='<p>'."{$imgAttr['description']}".'</p>';
		endif;
		$sliderString .='</div></div>';		
		$activeSlide ='';
	endforeach;
endif;
$sliderString .='</div>';
if ($frecce == 'si'):
  	$sliderString .='<a class="carousel-control-prev" href="#lmes_carousel_captions-'.$nslider.'" role="button" data-slide="prev">';
    $sliderString .='<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
    $sliderString .='<span class="sr-only">Previous</span></a>';
  	$sliderString .='<a class="carousel-control-next" href="#lmes_carousel_captions-'.$nslider.'" role="button" data-slide="next">';
    $sliderString .='<span class="carousel-control-next-icon" aria-hidden="true"></span>';
    $sliderString .='<span class="sr-only">Next</span></a>';
endif;
$sliderString .='</div>';
?>
<script>	
  interval = <?php echo $speed ?> ;
  <?php if ( $pausa == 'hover'): ?>
  pause = 'hover' ;
<?php else: ?>
	pause = false ;
<?php endif; ?>
  wrap = <?php echo $blocco ?> ;
</script>