<?php
require_once("include/dbcommon.php");

function fnTEST()
{
$sGETVAR = (@$_GET["test"] <> "") ? $_GET["test"] : 0;
if ($sGETVAR==1)
	{
	echo "<b>SERVER_NAME->  </b>".$_SERVER['SERVER_NAME']."<br>";
	$Link="";
	$SERVER_NAME=$_SERVER['SERVER_NAME'];
	switch ($SERVER_NAME) 
		{
		case "hostname":
			$Link="logout";  
			break;
		case "host":
			$Link="logout";  
			break;

		case "host":
			$Link="https://host/sdp-wrupsliv2wrup/Shibboleth.sso/Logout"; 
			break;
		case "tst-host" :
			$Link="https://host/int-sdp-wrupsliv1wrup/Shibboleth.sso/Logout";
      break;
		case "host" :
			$Link="https://host/int-sdp-wrupsliv1wrup/Shibboleth.sso/Logout";
      break;
		}
				echo "<a href='".$Link."' target='_self'>PROVA ESCI</a>";		
	}
}

function fnCONTROLLASECANCELLAZIONELOGICA_SOGGETTO_ORESETT ($sog_id,$oreset_id)
{
	$sSELECT="SELECT COUNT(*) as somma FROM covidrl_t_fte where sog_id=".$sog_id." and data_cancellazione is null AND id_ente=".$_SESSION['id_ente']." and oreset_id=".$oreset_id;
	$rs = DB::Query($sSELECT);
	$data=$rs->fetchAssoc();
	$num=$data["somma"];
	if ($num>=1) { return 1; } else {return 0;}
	unset ($rs);		
}

function fnCONTROLLASECANCELLAZIONELOGICA_SOGGETTO_ATTIVITA ($sog_id,$attivita_id)
{
	$sSELECT="SELECT COUNT(*) as somma FROM covidrl_t_fte where sog_id=".$sog_id." and data_cancellazione is null AND id_ente=".$_SESSION['id_ente']." and attivita_id=".$attivita_id;
	$rs = DB::Query($sSELECT);
	$data=$rs->fetchAssoc();
	$num=$data["somma"];
	if ($num>=1) { return 1; } else {return 0;}
	unset ($rs);		
}

function fnTEST_INSERIMENTO()
{
$sSELECT="Select data_rilevazione FROM covidrl_t_fte where (id_ente=".$_SESSION['id_ente']." and data_cancellazione is null and fte_stato_inviato=true) order by data_rilevazione DESC"; 
$rs = DB::Query($sSELECT);
$data=$rs->fetchAssoc();
if($data) {$data_rilevazione=$data['data_rilevazione'];} 
unset ($data);
unset ($rs);
$date = date_create($data_rilevazione); 
date_add($date, date_interval_create_from_date_string("7 days")); 
$data_rilevazione=date_format($date, "Y-m-d"); 
$date = date_create($data_rilevazione);
date_add($date, date_interval_create_from_date_string("-7 days")); 
$Dal=date_format($date, "Y-m-d"); 
$date = date_create($data_rilevazione);
date_add($date, date_interval_create_from_date_string("-1 days")); 
$Al=date_format($date, "Y-m-d"); 

$sSELECTFORINSERT="Select * FROM covidrl_t_fte where (id_ente=".$_SESSION['id_ente']." and data_cancellazione is null and fte_stato_inviato='t' and data_rilevazione='".$Dal."')";
$rsSELECTFORINSERT = DB::Query($sSELECTFORINSERT);
while( $dataSELECTFORINSERT = $rsSELECTFORINSERT->fetchAssoc() )
	{
	$DataInserimento=now();
	$fte_percentuale=$dataSELECTFORINSERT['fte_percentuale'];
	$sog_id=$dataSELECTFORINSERT['sog_id'];	
	$profint_id= fnCERCA_PROFILOINTENOID($sog_id);
	$oreset_id=fnCERCA_ORESETID($sog_id,$profint_id);	
	$attivita_id=$dataSELECTFORINSERT['attivita_id'];
	$validita_inizio=$Dal;
	$validita_fine=$Al;
	$utente_operazione=$_SESSION['utente'];
	$id_ente=$_SESSION['id_ente'];
	$sSELECTCERCO="Select COUNT(fte_id) as somma FROM covidrl_t_fte where (attivita_id=".$attivita_id." and sog_id=".$sog_id." and id_ente=".$_SESSION['id_ente']." and data_cancellazione is null and data_rilevazione='".$data_rilevazione."')";
	$rsCERCO = DB::Query($sSELECTCERCO);
	$GIAINSERITO=$rsCERCO->value(0);
	unset($rsCERCO);
	if ($GIAINSERITO==0)
		{
		$sINSERT="INSERT INTO hostname.covidrl_t_fte (fte_percentuale,oreset_id,sog_id,attivita_id,validita_inizio,validita_fine,data_rilevazione,data_visibilita,fte_stato_inviato,data_creazione,data_modifica,data_cancellazione,utente_operazione,id_ente) VALUES($fte_percentuale,$oreset_id,$sog_id,$attivita_id,'".$validita_inizio."','".$validita_fine."','".$data_rilevazione."',NULL,false,'".$DataInserimento."','".$DataInserimento."',NULL,'".$utente_operazione."',$id_ente)";
		$rsINSERT = DB::Query($sINSERT);
		unset($sINSERT);
		}	
	}

} //END TEST

function fnCERCA_ORESETID($sog_id,$profint_id)
{
$sSELECT="SELECT oreset_id FROM covidrl_t_ore_sett where id_ente =".$_SESSION['id_ente']." and profint_id=".$profint_id." and sog_id =".$sog_id."and  data_cancellazione is null and validita_fine is null"; 
$rs = DB::Query($sSELECT);
$data=$rs->fetchAssoc();
if($data) {return $data['oreset_id'];} else {return 0;}
unset ($data);
unset ($rs);
}

function fnTab_PROFILOINTERNO ($Tipo,$ID) 
{
	if ($ID>=1) 
	{
		$sSELECT="SELECT * FROM covidrl_d_prof_interno where profint_id=".$ID." and data_cancellazione is null and validita_fine is null and id_ente =".$_SESSION['id_ente'];
		$rs = DB::Query($sSELECT);
		$data=$rs->fetchAssoc();
		switch ($Tipo) {
	    	case "profint_id": return $data['profint_id'];break;			
			case "profint_codice": return $data['profint_codice'];break;			
			case "profint_descrizione": return $data['profint_descrizione'];break;			
			case "validita_inizio": return $data['validita_inizio'];break;			
			case "validita_fine": return $data['validita_fine'];break;			
			case "data_creazione": return $data['data_creazione'];break;			
			case "data_modifica": return $data['data_modifica'];break;			
			case "data_cancellazione": return $data['data_cancellazione'];break;			
			case "utente_operazione": return $data['utente_operazione'];break;			
			case "id_ente": return $data['id_ente'];break;			
			case "profint_id": return $data['profint_id'];break;			
		}
		unset ($data);
		unset ($rs);
	}
}

function fnWLB($sLabel,$value)
{
echo $sLabel." ".$value."<br>";
}
function fnW($value)
{
echo $value."<br>";
}


function fnCERCA_MARTEDI_PER_RILEVAZIONE2week($ITA)
{
/***********************************************************
CALCOLA DATA X CANCELLAZIONE
************************************************************/
$Anno=date("Y");
$Giorno=date("l");
if ($Giorno=="Monday")
	{
	 $Settimana=date("W")+1;
	} else
	{
	$Settimana=date("W")+2;	
	}
$DATARILEVAZIONE= new DateTime();
$DATARILEVAZIONE->setISODate($Anno, $Settimana,2);
if ($ITA==1) 
{
$DATARILEVAZIONE=$DATARILEVAZIONE->format('d/m/Y');
} else
{
$DATARILEVAZIONE=$DATARILEVAZIONE->format('Y-m-d');
}
return $DATARILEVAZIONE;
}

function fnCERCA_MARTEDI_PER_RILEVAZIONE($ITA)
{
/***********************************************************
CALCOLA DATA X CANCELLAZIONE
************************************************************/
$Anno=date("Y");
$Giorno=date("l");
if ($Giorno=="Monday")
	{
	 $Settimana=date("W");
	} else
	{
	$Settimana=date("W")+1;	
	}
$DATARILEVAZIONE= new DateTime();
$DATARILEVAZIONE->setISODate($Anno, $Settimana,2);
if ($ITA==1) 
{
$DATARILEVAZIONE=$DATARILEVAZIONE->format('d/m/Y');
} else
{
$DATARILEVAZIONE=$DATARILEVAZIONE->format('Y-m-d');
}
return $DATARILEVAZIONE;
}

function fnCERCA_PROFILOINTENOID($sog_id)
{
$sSELECT="SELECT  sog_id, profint_id, validita_fine, id_ente FROM covidrl_r_soggetto_prof_interno where sog_id=".$sog_id." and data_cancellazione is null and validita_fine is null and id_ente =".$_SESSION['id_ente'];
$rs = DB::Query($sSELECT);
$data=$rs->fetchAssoc();
if($data) {return $data['profint_id'];} else {return 0;}
unset ($data);
unset ($rs);
}

function fnRIPRISTINADATABASE()
{
$sUpdate="UPDATE covidrl_t_fte SET fte_stato_inviato=false where (id_ente=".$_SESSION['id_ente']. " AND fte_stato_inviato=false)";
$rs = DB::Query($sUpdate);
unset($rs);
}
function fnTEST_AGGIUNGIDATA ()
{
//$GiornoPrimaDataOggi = date('Y-m-d', mktime(0,0,0,date(m),date(d)-1,date(Y)));
//$data_modifica="2020-06-18 21:22:23";
//$data_modifica=date("Y-m-d", strtotime($data_modifica));
//$now=date("Y-m-d", strtotime(now()));
//if $now
//echo $now."<br>";
//echo $data_modifica."<br>";
/***********************************************************
TEST  AGGIUNGI DATA
************************************************************/
$Anno=date("Y");	
$Settimana=date("W");
$DATARILEVAZIONE= new DateTime();
$DATARILEVAZIONE->setISODate($Anno, $Settimana,2);
$DATARILEVAZIONE=$DATARILEVAZIONE->format('Y-m-d');
echo $DATARILEVAZIONE."<br>";
$Settimana=date("W")+1;
$DATARILEVAZIONE= new DateTime();
$DATARILEVAZIONE->setISODate($Anno, $Settimana,2);
$DATARILEVAZIONE=$DATARILEVAZIONE->format('Y-m-d');
echo $DATARILEVAZIONE."<br>";
}

function fnTEST_PARAMETRIWEB()
{
/***********************************************************
TEST PARAMETRI PAGINA WEB
************************************************************/
$ParametriPaginaWeb= "PARAMETRI: ".$_SERVER["QUERY_STRING"];
echo $ParametriPaginaWeb."<br>";
$pos = strpos($ParametriPaginaWeb, "q=(",0);
echo $pos;
/***********************************************************
SERVER REQUEST
************************************************************/
$indicesServer = array('PHP_SELF',
'argv',
'argc',
'GATEWAY_INTERFACE',
'SERVER_ADDR',
'SERVER_NAME',
'SERVER_SOFTWARE',
'SERVER_PROTOCOL',
'REQUEST_METHOD',
'REQUEST_TIME',
'REQUEST_TIME_FLOAT',
'QUERY_STRING',
'DOCUMENT_ROOT',
'HTTP_ACCEPT',
'HTTP_ACCEPT_CHARSET',
'HTTP_ACCEPT_ENCODING',
'HTTP_ACCEPT_LANGUAGE',
'HTTP_CONNECTION',
'HTTP_HOST',
'HTTP_REFERER',
'HTTP_USER_AGENT',
'HTTPS',
'REMOTE_ADDR',
'REMOTE_HOST',
'REMOTE_PORT',
'REMOTE_USER',
'REDIRECT_REMOTE_USER',
'SCRIPT_FILENAME',
'SERVER_ADMIN',
'SERVER_PORT',
'SERVER_SIGNATURE',
'PATH_TRANSLATED',
'SCRIPT_NAME',
'REQUEST_URI',
'PHP_AUTH_DIGEST',
'PHP_AUTH_USER',
'PHP_AUTH_PW',
'AUTH_TYPE',
'PATH_INFO',
'ORIG_PATH_INFO') ;

echo '<table cellpadding="10">' ;
foreach ($indicesServer as $arg) {
    if (isset($_SERVER[$arg])) {
        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
    }
    else {
        echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
    }
}
echo '</table>' ;
}

function fnTEST_CARICAMENTOMASSIVO ()
{
/***********************************************************
COPIA MASSIVA
************************************************************/
$Anno=date("Y");	
$Settimana=date("W");
$DATARILEVAZIONE= new DateTime();
$DATARILEVAZIONE->setISODate($Anno, $Settimana,2);
$DATARILEVAZIONE=$DATARILEVAZIONE->format('Y-m-d');

$Al = new DateTime();
$Al->setISODate($Anno, $Settimana,1);
$Al=$Al->format('Y-m-d');
	
$Settimana=date("W")-1;
$Dal = new DateTime();
$Dal->setISODate($Anno, $Settimana,2);
$Dal=$Dal->format('Y-m-d');

echo "DATA RILEVAZIONE: ".$DATARILEVAZIONE."<br>";
echo "dal ".$Dal." al ".$Al."<br>";

$sSELECT="Select COUNT(fte_id) as somma FROM covidrl_t_fte where (id_ente=".$_SESSION['id_ente']." and data_cancellazione is null and fte_stato_inviato='f' and data_rilevazione='".$Dal."')";
$rs = DB::Query($sSELECT);
$data=$rs->fetchAssoc();
$CiSonoRecordNonInviatisettimanaPrima=$data['somma'];
unset($data);
unset($rs);
echo "CiSonoRecordNonInviatisettimanaPrima:".$CiSonoRecordNonInviatisettimanaPrima."<br>";

$sSELECTFORINSERT="Select * FROM covidrl_t_fte where (id_ente=".$_SESSION['id_ente']." and data_cancellazione is null and fte_stato_inviato='t' and data_rilevazione='".$Dal."')";
$rsSELECTFORINSERT = DB::Query($sSELECTFORINSERT);
while( $dataSELECTFORINSERT = $rsSELECTFORINSERT->fetchAssoc() )
	{
	$DataInserimento=now();
	$fte_percentuale=$dataSELECTFORINSERT['fte_percentuale'];
	$oreset_id="NULL";
	$sog_id=$dataSELECTFORINSERT['sog_id'];
	$attivita_id=$dataSELECTFORINSERT['attivita_id'];
	$validita_inizio=$Dal;
	$validita_fine=$Al;
	$data_rilevazione=$DATARILEVAZIONE;
	$utente_operazione=$_SESSION['utente'];
	$id_ente=$_SESSION['id_ente'];

	$sSELECTCERCO="Select COUNT(fte_id) as somma FROM covidrl_t_fte where (attivita_id=".$attivita_id." and sog_id=".$sog_id." and id_ente=".$_SESSION['id_ente']." and data_cancellazione is null and data_rilevazione='".$DATARILEVAZIONE."')";
	$rsCERCO = DB::Query($sSELECTCERCO);
	$GIAINSERITO=$rsCERCO->value(0);
	unset($rsCERCO);

	if ($GIAINSERITO>0)
		{
		echo "<b>NON INSERISCO</B> ".	$GIAINSERITO."<br>";	
		} else 
		{
		$sINSERT="INSERT INTO hostname.covidrl_t_fte (fte_percentuale,oreset_id,sog_id,attivita_id,validita_inizio,validita_fine,data_rilevazione,data_visibilita,fte_stato_inviato,data_creazione,data_modifica,data_cancellazione,utente_operazione,id_ente) VALUES($fte_percentuale,$oreset_id,$sog_id,$attivita_id,'".$validita_inizio."','".$validita_fine."','".$data_rilevazione."',NULL,false,'".$DataInserimento."','".$DataInserimento."',NULL,'".$utente_operazione."',$id_ente)";
		echo $sINSERT."<br>";	
		}	
	}
}
?>