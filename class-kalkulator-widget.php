<?php
/*
@package kalkulator
*/
class kalkulator_widget extends WP_Widget {
 
    function __construct() {
        parent::__construct(
        
        // Base ID of your widget
        'kalkulator_widget', 
        
        // Widget name will appear in UI
        __('Kalkulator Dostave', ''), 
        
        // Widget description
        array( 'description' => __( 'Shipping Calculator Widget', '' ), ) 
        );
        
    }
	
	function get_user_roles() {
		if( is_user_logged_in() ) {
			$user = wp_get_current_user();
			return ( array ) $user->roles;
		} else {
			return array();
		}
	}
     
    // Creating widget front-end
    public function widget( $args, $instance )
    {

        // Enqueue stylesheets and scripts
        wp_enqueue_script('jquery-legacy');
        wp_enqueue_script('plugin-migrate');
        wp_enqueue_style('kalkulator-stylesheet');
        wp_enqueue_script('jquery-3');
        wp_enqueue_script('compressed-scripts');
        wp_enqueue_script('main-script');
        // $title = apply_filters( 'widget_title', $instance['title'] );
     
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        // This is where you run the code and display the output
        ?>
        <section class="kalkulator clearfix" <?php echo 'oldvalues=' . (in_array('vip', $this->get_user_roles()) ? 'true' : 'false') ?> >
        <div class="wrapa clearfix">
          <div class="flota clearfix">
            <div class="moto izbor unselected tooltip change" id="moto" data-flag="0"
            title="Hitna dostava u Zagrebu i okolici<br>Za pošiljke do 5kg ili dimenzija do 30x20x10 cm<br>Primanje narudžbi pon.-pet. 08-17 sati">
            <img src=<?php echo plugin_dir_url( __FILE__ ) . "assets/img/MOTO1.png"?> alt="">
            <p class="opis">Do 5 kg ili do 30x20x15 cm</p>
          </div>
          <div class="caddy izbor unselected tooltip change" id="caddy" data-flag="1"
          title="Hitna dostava u Zagrebu i okolici<br>Za pošiljke do 600 kg ili dimenzija 160x110x110 cm<br>Primanje narudžbi pon.-pet. 08-17 sati">
          <img src=<?php echo plugin_dir_url( __FILE__ ) . "assets/img/CADDY1.png"?> alt="">
          <p class="opis">Do 600 kg ili do 160x110x110</p>
        </div>
        <div class="kombi izbor unselected change" id="kombi" data-flag="2">
          <img src= <?php echo plugin_dir_url( __FILE__ ) . "assets/img/KOMBI1.png"?> alt="">
          <p class="opis">Do 1400 kg ili do 370x200x150</p>
        </div>
      </div>
      <div class="od-do clearfix">
        <div class="dim-mass">
          <div class="dimenzije">
            <h3>
              DIMENZIJE (cm)
            </h3>
            <input class="dim change" type="text" tabindex="1">
            <span class="crveno">
              x
            </span>
            <input class="dim change" type="text" tabindex="2">
            <span class="crveno">
              x
            </span>
            <input class="dim change" type="text" tabindex="3">
          </div>
          <div class="masa">
            <h3>
              MASA (kg)
            </h3>
            <input id="mass" type="text" name="mass" class="change" tabindex="4">
          </div>
        </div>
        <div class="pol-odr">
          <select data-placeholder="Polazište..." name="" id="od" tabindex="5">
            <option value="">
            </option>
            <optgroup label="1. Zona">
              <option value="0,0,0;0,0,0">
                BORONGAJ
              </option>
              <option value="0,0,0;0,0,0">
                BOROVJE
              </option>
              <option value="0,0,0;0,0,0">
                BUKOVAC DONJI
              </option>
              <option value="0,0,0;0,0,0">
                CENTAR
              </option>
              <option value="0,0,0;0,0,0">
                ČRNOMEREC
              </option>
              <option value="0,0,0;0,0,0">
                CVJETNO NASELJE
              </option>
              <option value="0,0,0;0,0,0">
                DUGAVE
              </option>
              <option value="0,0,0;0,0,0">
                FERENŠČICA
              </option>
              <option value="0,0,0;0,0,0">
                FOLNEGOVIĆEVO
              </option>
              <option value="0,0,0;0,0,0">
                KAJZERICA
              </option>
              <option value="0,0,0;0,0,0">
                KNEŽIJA
              </option>
              <option value="0,0,0;0,0,0">
                KRUGE
              </option>
              <option value="0,0,0;0,0,0">
                KSAVER
              </option>
              <option value="0,0,0;0,0,0">
                KUSTOŠIJA
              </option>
              <option value="0,0,0;0,0,0">
                KVATRIĆ
              </option>
              <option value="0,0,0;0,0,0">
                LANIŠTE
              </option>
              <option value="0,0,0;0,0,0">
                LJUBLJANICA
              </option>
              <option value="0,0,0;0,0,0">
                MAKSIMIR
              </option>
              <option value="0,0,0;0,0,0">
                MALEŠNICA
              </option>
              <option value="0,0,0;0,0,0">
                MEDVEŠČAK
              </option>
              <option value="0,0,0;0,0,0">
                MLINOVI
              </option>
              <option value="0,0,0;0,0,0">
                PEŠČENICA
              </option>
              <option value="0,0,0;0,0,0">
                POLJANICE
              </option>
              <option value="0,0,0;0,0,0">
                PREČKO
              </option>
              <option value="0,0,0;0,0,0">
                RAVNICE
              </option>
              <option value="0,0,0;0,0,0">
                REMETINEC
              </option>
              <option value="0,0,0;0,0,0">
                RETKOVEC
              </option>
              <option value="0,0,0;0,0,0">
                RUDEŠ
              </option>
              <option value="0,0,0;0,0,0">
                SAVICA
              </option>
              <option value="0,0,0;0,0,0">
                SAVSKI GAJ
              </option>
              <option value="0,0,0;0,0,0">
                SIGEČICA
              </option>
              <option value="0,0,0;0,0,0">
                SIGET
              </option>
              <option value="0,0,0;0,0,0">
                SLOBOŠTINA
              </option>
              <option value="0,0,0;0,0,0">
                SOPOT
              </option>
              <option value="0,0,0;0,0,0">
                SREDIŠĆE
              </option>
              <option value="0,0,0;0,0,0">
                SREDNJACI
              </option>
              <option value="0,0,0;0,0,0">
                STENJEVEC
              </option>
              <option value="0,0,0;0,0,0">
                SVETICE
              </option>
              <option value="0,0,0;0,0,0">
                ŠPANSKO
              </option>
              <option value="0,0,0;0,0,0">
                TRAVNO
              </option>
              <option value="0,0,0;0,0,0">
                TREŠNJEVKA
              </option>
              <option value="0,0,0;0,0,0">
                TRNAVA
              </option>
              <option value="0,0,0;0,0,0">
                TRNOVČICA
              </option>
              <option value="0,0,0;0,0,0">
                TRNSKO
              </option>
              <option value="0,0,0;0,0,0">
                TRNJE
              </option>
              <option value="0,0,0;0,0,0">
                TROKUT
              </option>
              <option value="0,0,0;0,0,0">
                UTRINA
              </option>
              <option value="0,0,0;0,0,0">
                VOLOVČICA
              </option>
              <option value="0,0,0;0,0,0">
                VOLTINO
              </option>
              <option value="0,0,0;0,0,0">
                VRBANI
              </option>
              <option value="0,0,0;0,0,0">
                VRBIK
              </option>
              <option value="0,0,0;0,0,0">
                VUKOMEREC
              </option>
              <option value="0,0,0;0,0,0">
                ZAPRUĐE
              </option>
              <option value="0,0,0;0,0,0">
                ZAVRTNICA
              </option>
            </optgroup>
            <optgroup label="2. Zona">
              <option value="0,0,0;7,5,0;J">
                BLATO
              </option>
              <option value="0,0,0;7,5,0;J">
                BOTINEC
              </option>
              <option value="0,0,0;7,5,0;J">
                BUZIN
              </option>
              <option value="0,0,0;7,5,0;I">
                DUBEC
              </option>
              <option value="0,0,0;2,5,0;I">
                DUBRAVA
              </option>
              <option value="0,0,0;7,5,0;Z">
                GAJNICE
              </option>
              <option value="0,0,0;7,5,0;I">
                GORNJI BUKOVAC
              </option>
              <option value="0,0,0;7,5,50;S">
                GRAČANI
              </option>
              <option value="0,0,0;7,5,0;Z">
                JANKOMIR
              </option>
              <option value="0,0,0;7,5,0;I">
                PETRUŠEVEC
              </option>
              <option value="0,0,0;7,10,0;I">
                RESNIK
              </option>
              <option value="0,0,0;7,5,50;I">
                REMETE
              </option>
              <option value="0,0,0;7,5,0;J">
                SVETA KLARA
              </option>
              <option value="0,0,0;7,5,50;S">
                ŠESTINE
              </option>
              <option value="0,0,0;2,5,0;Z">
                VRAPČE
              </option>
              <option value="0,0,0;2,5,0;I">
                ŽITNJAK
              </option>
            </optgroup>
            <optgroup label="3. Zona">
              <option value="5.18,3.55,7.68;50,30,100">
                BREZOVICA
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                ČEHI
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                D. I G. LOMNICA
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                DONJI STUPNIK
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                GORNJI STUPNIK
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                HRAŠĆE
              </option>
              <option value="5.18,3.55,7.68;17,20,50">
                HRVATSKI LESKOVAC
              </option>
              <option value="--,3.55,7.68;--,18,50">
                IKEA/IKEA OUTLET
              </option>
              <option value="5.18,3.55,7.68;7,5,50">
                JAZBINA
              </option>
              <option value="5.18,3.55,7.68;17,20,50">
                JEŽDOVEC
              </option>
              <option value="5.18,3.55,7.68;32,25,50">
                KERESTINEC
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                LUČKO
              </option>
              <option value="5.18,3.55,7.68;25,18,50">
                MALA I VELIKA MLAKA
              </option>
              <option value="5.18,3.55,7.68;50,30,100">
                MARKUŠEVEC
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                MIĆEVEC
              </option>
              <option value="5.18,3.55,7.68;25,18,50">
                MIROŠEVEC
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                ODRA
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                PODSUSED
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                RAKITJE
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                SESVETE
              </option>
              <option value="5.18,3.55,7.68;32,25,50">
                SESVETSKI KRALJEVEC
              </option>
              <option value="5.18,3.55,7.68;25,18,50">
                SVETA NEDJELJA
              </option>
              <option value="5.18,3.55,7.68;12,5,0">
                ŠTEFANOVEC
              </option>
              <option value="5.18,3.55,7.68;32,25,50">
                VELIKA GORICA
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                VELIKO POLJE
              </option>
            </optgroup>
            <optgroup label="4. Zona">
              <option value="--,11.37,7.68;50,80,100">
                DUGO SELO
              </option>
              <option value="--,5.27,7.68;50,50,100">
                NOVO ČIČE
              </option>
              <option value="--,11.37,7.68;50,80,100">
                RUGVICA
              </option>
              <option value="--,5.27,7.68;50,50,100">
                SAMOBOR
              </option>
              <option value="--,5.27,7.68;35,50,100">
                ZAPREŠIĆ
              </option>
            </optgroup>
          </select>
          <select data-placeholder="Odredište..." name="" id="do" tabindex="6">
            <option value="">
            </option>
            <optgroup label="1. Zona">
              <option value="0,0,0;0,0,0">
                BORONGAJ
              </option>
              <option value="0,0,0;0,0,0">
                BOROVJE
              </option>
              <option value="0,0,0;0,0,0">
                BUKOVAC DONJI
              </option>
              <option value="0,0,0;0,0,0">
                CENTAR
              </option>
              <option value="0,0,0;0,0,0">
                ČRNOMEREC
              </option>
              <option value="0,0,0;0,0,0">
                CVJETNO NASELJE
              </option>
              <option value="0,0,0;0,0,0">
                DUGAVE
              </option>
              <option value="0,0,0;0,0,0">
                FERENŠČICA
              </option>
              <option value="0,0,0;0,0,0">
                FOLNEGOVIĆEVO
              </option>
              <option value="0,0,0;0,0,0">
                KAJZERICA
              </option>
              <option value="0,0,0;0,0,0">
                KNEŽIJA
              </option>
              <option value="0,0,0;0,0,0">
                KRUGE
              </option>
              <option value="0,0,0;0,0,0">
                KSAVER
              </option>
              <option value="0,0,0;0,0,0">
                KUSTOŠIJA
              </option>
              <option value="0,0,0;0,0,0">
                KVATRIĆ
              </option>
              <option value="0,0,0;0,0,0">
                LANIŠTE
              </option>
              <option value="0,0,0;0,0,0">
                LJUBLJANICA
              </option>
              <option value="0,0,0;0,0,0">
                MAKSIMIR
              </option>
              <option value="0,0,0;0,0,0">
                MALEŠNICA
              </option>
              <option value="0,0,0;0,0,0">
                MEDVEŠČAK
              </option>
              <option value="0,0,0;0,0,0">
                MLINOVI
              </option>
              <option value="0,0,0;0,0,0">
                PEŠČENICA
              </option>
              <option value="0,0,0;0,0,0">
                POLJANICE
              </option>
              <option value="0,0,0;0,0,0">
                PREČKO
              </option>
              <option value="0,0,0;0,0,0">
                RAVNICE
              </option>
              <option value="0,0,0;0,0,0">
                REMETINEC
              </option>
              <option value="0,0,0;0,0,0">
                RETKOVEC
              </option>
              <option value="0,0,0;0,0,0">
                RUDEŠ
              </option>
              <option value="0,0,0;0,0,0">
                SAVICA
              </option>
              <option value="0,0,0;0,0,0">
                SAVSKI GAJ
              </option>
              <option value="0,0,0;0,0,0">
                SIGEČICA
              </option>
              <option value="0,0,0;0,0,0">
                SIGET
              </option>
              <option value="0,0,0;0,0,0">
                SLOBOŠTINA
              </option>
              <option value="0,0,0;0,0,0">
                SOPOT
              </option>
              <option value="0,0,0;0,0,0">
                SREDIŠĆE
              </option>
              <option value="0,0,0;0,0,0">
                SREDNJACI
              </option>
              <option value="0,0,0;0,0,0">
                STENJEVEC
              </option>
              <option value="0,0,0;0,0,0">
                SVETICE
              </option>
              <option value="0,0,0;0,0,0">
                ŠPANSKO
              </option>
              <option value="0,0,0;0,0,0">
                TRAVNO
              </option>
              <option value="0,0,0;0,0,0">
                TREŠNJEVKA
              </option>
              <option value="0,0,0;0,0,0">
                TRNAVA
              </option>
              <option value="0,0,0;0,0,0">
                TRNOVČICA
              </option>
              <option value="0,0,0;0,0,0">
                TRNSKO
              </option>
              <option value="0,0,0;0,0,0">
                TRNJE
              </option>
              <option value="0,0,0;0,0,0">
                TROKUT
              </option>
              <option value="0,0,0;0,0,0">
                UTRINA
              </option>
              <option value="0,0,0;0,0,0">
                VOLOVČICA
              </option>
              <option value="0,0,0;0,0,0">
                VOLTINO
              </option>
              <option value="0,0,0;0,0,0">
                VRBANI
              </option>
              <option value="0,0,0;0,0,0">
                VRBIK
              </option>
              <option value="0,0,0;0,0,0">
                VUKOMEREC
              </option>
              <option value="0,0,0;0,0,0">
                ZAPRUĐE
              </option>
              <option value="0,0,0;0,0,0">
                ZAVRTNICA
              </option>
            </optgroup>
            <optgroup label="2. Zona">
              <option value="0,0,0;7,5,0;J">
                BLATO
              </option>
              <option value="0,0,0;7,5,0;J">
                BOTINEC
              </option>
              <option value="0,0,0;7,5,0;J">
                BUZIN
              </option>
              <option value="0,0,0;7,5,0;I">
                DUBEC
              </option>
              <option value="0,0,0;2,5,0;I">
                DUBRAVA
              </option>
              <option value="0,0,0;7,5,0;Z">
                GAJNICE
              </option>
              <option value="0,0,0;7,5,0;I">
                GORNJI BUKOVAC
              </option>
              <option value="0,0,0;7,5,50;S">
                GRAČANI
              </option>
              <option value="0,0,0;7,5,0;Z">
                JANKOMIR
              </option>
              <option value="0,0,0;7,5,0;I">
                PETRUŠEVEC
              </option>
              <option value="0,0,0;7,10,0;I">
                RESNIK
              </option>
              <option value="0,0,0;7,5,50;I">
                REMETE
              </option>
              <option value="0,0,0;7,5,0;J">
                SVETA KLARA
              </option>
              <option value="0,0,0;7,5,50;S">
                ŠESTINE
              </option>
              <option value="0,0,0;2,5,0;Z">
                VRAPČE
              </option>
              <option value="0,0,0;2,5,0;I">
                ŽITNJAK
              </option>
            </optgroup>
            <optgroup label="3. Zona">
            <option value="5.18,3.55,7.68;50,30,100">
                BREZOVICA
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                ČEHI
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                D. I G. LOMNICA
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                DONJI STUPNIK
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                GORNJI STUPNIK
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                HRAŠĆE
              </option>
              <option value="5.18,3.55,7.68;17,20,50">
                HRVATSKI LESKOVAC
              </option>
              <option value="--,3.55,7.68;--,18,50">
                IKEA/IKEA OUTLET
              </option>
              <option value="5.18,3.55,7.68;7,5,50">
                JAZBINA
              </option>
              <option value="5.18,3.55,7.68;17,20,50">
                JEŽDOVEC
              </option>
              <option value="5.18,3.55,7.68;32,25,50">
                KERESTINEC
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                LUČKO
              </option>
              <option value="5.18,3.55,7.68;25,18,50">
                MALA I VELIKA MLAKA
              </option>
              <option value="5.18,3.55,7.68;50,30,100">
                MARKUŠEVEC
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                MIĆEVEC
              </option>
              <option value="5.18,3.55,7.68;25,18,50">
                MIROŠEVEC
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                ODRA
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                PODSUSED
              </option>
              <option value="5.18,3.55,7.68;27,20,50">
                RAKITJE
              </option>
              <option value="5.18,3.55,7.68;22,15,50">
                SESVETE
              </option>
              <option value="5.18,3.55,7.68;32,25,50">
                SESVETSKI KRALJEVEC
              </option>
              <option value="5.18,3.55,7.68;25,18,50">
                SVETA NEDJELJA
              </option>
              <option value="5.18,3.55,7.68;12,5,0">
                ŠTEFANOVEC
              </option>
              <option value="5.18,3.55,7.68;32,25,50">
                VELIKA GORICA
              </option>
              <option value="5.18,3.55,7.68;17,10,50">
                VELIKO POLJE
              </option>
            </optgroup>
            <optgroup label="4. Zona">
              <option value="--,11.37,7.68;50,80,100">
                DUGO SELO
              </option>
              <option value="--,5.27,7.68;50,50,100">
                NOVO ČIČE
              </option>
              <option value="--,11.37,7.68;50,80,100">
                RUGVICA
              </option>
              <option value="--,5.27,7.68;50,50,100">
                SAMOBOR
              </option>
              <option value="--,5.27,7.68;35,50,100">
                ZAPREŠIĆ
              </option>
            </optgroup>
          </select>
        </div>
        <div class="rezultat" id="rezultat" tabindex="7">
          <div id="rezvoz" class="visuallyhidden clearfix">
            <div class="item disabled unselected" data-index="0">
              <div class="rez-image">
              </div>
              <p class="rezvoz-opis">
              </p>
            </div>
            <div class="item disabled unselected" data-index="1">
              <div class="rez-image">
              </div>
              <p class="rezvoz-opis">
              </p>
            </div>
            <div class="item selected" data-index="2">
              <div class="rez-image">
              </div>
              <p class="rezvoz-opis">
              </p>
            </div>
            <div class="item disabled unselected" data-index="3">
              <div class="rez-image">
              </div>
              <p class="rezvoz-opis">
              </p>
            </div>
            <div class="item disabled unselected" data-index="4">
              <div class="rez-image">
              </div>
              <p class="rezvoz-opis">
              </p>
            </div>
          </div>
          <div class="kolko selected" id="cijena-1">
            <p class="cijena">
              --
            </p>
            <p class="tarifa">
            </p>
          </div>
          <div class="kolko unselected" id="cijena-2">
            <p class="cijena">
            </p>
            <p class="tarifa">
              Unutar 60 min
            </p>
          </div>
          <div class="ukupno" id="ukupno">
            <h2 class="crveno">UKUPNO</h2>
            <div id="ukupna-cijena"></div>
          </div>
          <div class="kontakt hidden">
            <ul>
              <li>
             <img src=<?php echo plugin_dir_url( __FILE__ ) . "assets/img/phone.png"?>>    +385 91 2008 051
              </li>
              <li>
             <img src=<?php echo plugin_dir_url( __FILE__ ) . "assets/img/phone.png"?>>    +385 99 8484 756
              </li>
            </ul>
          <div class="pdv" > Cijene su izra&#158;ene bez PDV-a.</div>
        </div>
        </div>
        <div id="mali-sim-tam" class="sim-tam kutija hidden"></div>
      </div>
    </div>
  </section>
        <?php
        
    }

    // Creating widget back-end
    public function form($instance)
    {
        echo '<p>It is highly recommended to use this widget through Elementor Page Builder for ease of use and better view</p>';
    }
         
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        return $instance;
    }
} // Class wpb_widget ends here
?>