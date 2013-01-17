<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class About extends \helper\layout\LayoutBlock {

	/**
	* the data to show on the widget, is as argument.
	*/
	function __construct($data = null){
	
	}
	
	function generate(){
		/*return '<header class="jumbotron subhead" id="overview">
		<h1>Om</h1>
		<p class="lead">Hvem er vi, og hvad kan vi.</p>
	</header>
	<p>kommer senere</p>';*/
        $domain = \config\config::$configs['finance']['settings']['protocol'].'://'.\config\config::$configs['finance']['domains']['static'];
        return '
	<header class="jumbotron subhead" id="overview">
		<h1>Om</h1>
		<p class="lead">Hvem er vi, og hvad kan vi.</p>
	</header>
<div class="row">
	<!--<div class="span2">
		<div style="height:40px;" />
		<div data-spy="affix" data-offset-top="0">
			<ul class="nav nav-list">
				<li><a href="#apps"><i class="icon-chevron-right"></i> Modulært</a></li>
				<li><a href="#tech"><i class="icon-chevron-right"></i> Teknologi</a></li>
				<li><a href="#pricing"><i class="icon-chevron-right"></i> Prisstruktur</a></li>
				<li><a href="#privacy"><i class="icon-chevron-right"></i> Privatliv</a></li>
				<li><a href="#behind"><i class="icon-chevron-right"></i> Kontakt os</a></li>
			</ul>
		</div>
	</div>-->

	<div class="offset2">

		<div class="row" id="apps">
			<div style="height:40px;" />
			<h2>Finansmaskinen <small>.</small></h2>
			<div class="span5">
				Finansmaskinen kører på nuværende tidspunkt i private beta. Dette betyder at du desværre ikke kan få adgang til
                 systemet endnu. Vi tester systemet og udvikler det så hurtigt som muligt, så du snart kan få lov til at benytte
                 dig af det. Indtil da, kan du følge med i udviklingen Facebook og Twitter. Så skal vi nok sige til, når systemet er
                 helt færdigt.
			</div>
			<div class="span5">
				<p></p>
			</div>
		</div>
        <br /><br /><br />
		<div class="row" id="behind">
			<h2>Kontakt os <small></small></h2>
            <div class="span5">
                <p>
                    Du kan også kontakte os på følgende mail adresse eller finde os på de sociale medier:
                </p>
                <p>
                    <a href="mailto:info@finansmaskinen.dk">info@finansmaskinen.dk</a>
                </p>
            </div>

            <div class="span5">
                <a href="http://twitter.com/Finansmaskinen">
                    <img src="http://static.finansmaskinen.dev/templates/finance/images/social/twitter.png" />
                </a>
                <a href="http://www.facebook.com/pages/Finansmaskinendk/138902266186710">
                    <img src="'.$domain.'/templates/finance/images/social/facebook.png" />
                </a>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="span5">
                <h3>Mads Buch <small>CEO</small></h3>
                <img src="'.$domain.'/resources/persons/mThumb.png" style="float:left; margin:5px;" />
                <p style="margin-left:165px;">Mads er datalog og har stor erfaring inde for området. Han kom på ideen bag systemet da han selv startede sit
 første firma AI Consult op. Han var træt af de dyre løsninger der eksisterede på markedet, og ønskede at lave et
 system så enkelt, at alle ville kunne benytte det, uden at skulle sætte sig ind i det, og på denne måde blev
 finansmaskinen skabt.</p>
            </div>
            <div class="span5">
                <h3>Kristine J .Gye <small>Head of communication</small></h3>
                <img src="'.$domain.'/resources/persons/kThumb.png" style="float:left; margin:5px;" />

                <p style="margin-left:165px;">Kristine er uddannet inden for markedsføring og kommunikation. Hun har selv erfaring med iværksætteri, og
 har også haft sit eget firma, KEK Medic siden gymnasiet. Kristine er lidt en social media-nørd og elsker at
 arbejde med inbound og content marketing.</p>
            </div>
        </div>
	</div>
</div>
<div style="height:200px;" />
		';
	}
}


?>
