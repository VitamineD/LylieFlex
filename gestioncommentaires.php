<meta charset = "utf-8/">
<?php

/* Fichier PHP: "gestioncommentaires.php"
+------------------------------------------------------------------------------+
| Extension pour une fonction "ajout de commentaires"                          |
| Tedheu 2009, version 1.0 (fr),        mise � jour le 09 novembre 2009        |
| Module script PHP de gestion des commentaires                                |
| fonctionne de paire avec le module JavaScript "commentaires.js"              |
+------------------------------------------------------------------------------+
*/
/*========= Param�tres de personnalisation ===================================*/
// Chaque message a un parametre de validation (index 'v' du tableau $Message)
// avec une valeur de 0 le message est stock� mais ne sera pas affich�
// avec une valeur de 1 le message est stock� et sera affich�
// avec une valeur de -1 le message sera retir� du fichier de stockage
$validmesdef= 1;  // validation par d�faut des messages
//
/*========= Variables (initialisation) =======================================*/
$jeton= NULL;       // normalement communiqu� par variables GET
$sujet= NULL;       // normalement communiqu� par variables GET
$action= NULL;      // normalement communiqu� par variables GET
//---
$entetedef= '|=== Fichier de commentaires, T� 09 novembre 2009 - version 1.0 ===|'."\n";
$nouveau= false;     // message pas nouveau par d�faut
$imesnou= -1;        // indice nouveau message mis � -1
//--- messages => tableau de tableaux
// le visteur envoi un commentaire avec d'autres informations l'ensemble forme un message
$Message= array();
$Message['*']= array(); // * > jeton
$Message['s']= array(); // s > sujet
$Message['v']= array(); // v > validation, -1, 0 ou 1 , 0 > non valid�, -1 > rejet�
$Message['i']= array(); // i > num�ro IP
$Message['d']= array(); // d > date du jour
$Message['h']= array(); // h > heure
$Message['n']= array(); // n > nom
$Message['c']= array(); // c > commentaire
$Message['r']= array(); // r > r�serv� pour usage futur
//--- deux actions possibles: lecture, �criture
$Actionliste= array('lecture','ecriture');
//--- donn�es pour traitement de s�curisation
$tagspermis= '<b><i><u><a><br><small><img>';
$Evenements= array('onAbort','onBlur','onChange','onClick','onDbclick','onDragdrop','onError','onFocus','onKeydown','onKeypress','onKeyup','onLoad','onMouseOver','onMouseOut','onReset','onResize','onSelect','onSubmit','onUnload');
foreach ($Evenements as $evenement){$Clefs[]= '/'.$evenement.'/i';}
$Car_nr= array("\n\r","\r\n","\n","\r");
$Carspe= array('&','�','�','�','�','�','�','�','�','�','�');
array_push($Carspe,'�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');
array_push($Carspe,'�','�','�','�','�','�','�','�','�','�','�','�');
array_push($Carspe,'�','�');
foreach ($Carspe as $car){$Carchgs[]= htmlentities($car);}
array_push($Carspe,'%','^');
array_push($Carchgs,'&#37;','&#94;');
//-----------------------------------------------------------------------------/
/*========= R�cup�ration d'informations ======================================*/
//--- informations g�n�rales
$ipc=$_SERVER['REMOTE_ADDR']; // num�ro IP du visiteur
$jour= date('d.m.Y');
$heure= date('H:i');
//--- variables pass�es par m�thode GET
(!empty($_GET['sujet']))? $sujet=$_GET['sujet']: $sujet='aucun';
(!empty($_GET['action']))? $action=$_GET['action']: $action='rien';
(!empty($_GET['jeton']))? $jeton= $_GET['jeton']: $jeton= '0';
//--- traitements des informations GET entrantes
$sujet= substr(strip_tags($sujet),0,30);
$sujet_r= substr(strip_tags($sujet),0,10);
if (!in_array($action,$Actionliste)) $action='rien';
$jeton= preg_replace('/[^0-9]/','',$jeton); // que des chiffres
$jeton= substr($jeton,0,10);
//-----------------------------------------------------------------------------/
/*========= Validation de la requ�te =========================================*/
$valide= true;


if (!$valide) {
    // arret du script par s�curit� si requ�te non valide
    exit;
}
/*========= Cr�ations de fichiers si inexistants =============================*/
$nomfichier_verrou= 'verrou_'.$sujet_r.'.txt';
$nomfichier_messag= 'messages_'.$sujet_r.'.txt';
if (!file_exists($nomfichier_verrou)) touch($nomfichier_verrou);
if (!file_exists($nomfichier_messag)){
    $pf0=fopen($nomfichier_messag,'w');
    fwrite($pf0,$entetedef);
    fclose($pf0);
}
//-----------------------------------------------------------------------------/
//
/*========= Mode �criture ($action= 'ecriture') ==============================*/
if ($action=='ecriture'){
    $nouveau= true; // on suppose un nouveau message
    //
    //=== variables pass�es par m�thode POST ----------------------------------/
    (!empty($_POST['nom']))? $nom= $_POST['nom']: $nom= 'inconnu';
    (!empty($_POST['message']))? $commentaire= $_POST['message']: $commentaire= '';
    //--- traitements des informations POST entrantes
    $nom= strip_tags($nom);
    $nom= str_replace($Car_nr,'',$nom);
    $nom= htmlentities(substr($nom,0,20));
    $commentaire= strip_tags($commentaire,$tagspermis);
    $commentaire= preg_replace($Clefs,'none',$commentaire);
    $commentaire= str_replace($Carspe,$Carchgs,$commentaire);
    $commentaire= str_replace($Car_nr,'<br>',$commentaire);
    $commentaire= preg_replace('/\\\/','',$commentaire);  // plus d'anti-slash
    //-------------------------------------------------------------------------/
    //=== acc�s au fichier des messages en lecture/�criture -------------------/
    //--- mise en place du verrou -------------------------
    $deb= time(); $tempo= 2; $erreur= '';
    do{
        $verrou= file($nomfichier_verrou);
        if (count($verrou)==0) break;
    }while((time()-$deb)<=$tempo);
    if (count($verrou)!=0){ // (le verrou est rest� bloqu�)
        $erreur.= '-- blocage-verrou --';
        if ((time()-trim($verrou[0]))>=10){
            $erreur.= ' -- deblocage-force --';
            $pfv=fopen($nomfichier_verrou,'w');
            fwrite($pfv,'');
            fclose($pfv);
        }
    }
    else{  // (si pas d'erreur fermeture du verrou)
        $pfv=fopen($nomfichier_verrou,'w');
        fwrite($pfv,time());
        fclose($pfv);
    }
    if ($erreur!='') die('ERREUR: '.$erreur);
    //-----------------------------------------------------
    //--- lecture du fichier de messages ligne par ligne
    $pf1= fopen($nomfichier_messag,'r');
    $n=-1;
    while (!feof($pf1)) $ligne[++$n]=fgets($pf1,1024);
    fclose($pf1);
    $nlgn= $n;
    //-------------------------------------------------------------------------/
    //=== analyse du contenu et traitement ------------------------------------/
    $entete=$ligne[0]; // la premi�re ligne est une ent�te
    $imesnou= 0; // indice nouveau message = 0
    if ($nouveau){
        $Message['*'][0]= $jeton;
        $Message['s'][0]= $sujet;
        $Message['v'][0]= $validmesdef;
        $Message['i'][0]= $ipc;
        $Message['d'][0]= $jour;
        $Message['h'][0]= $heure;
        $Message['n'][0]= $nom;
        $Message['c'][0]= $commentaire;
        $Message['r'][0]= 'RFU';
    }
    //--- traitement ligne par ligne
    $imes= 1;
    $jeton_tst= false; $ipc_tst= false; $heure_tst= false;
    for ($ilgn=1; $ilgn<=$nlgn; $ilgn++){
        $clef= substr($ligne[$ilgn],0,3);
        $contenu= str_replace($Car_nr,'',substr($ligne[$ilgn],3));
        switch($clef){
            case '\\*\\': // jeton
                $Message['*'][$imes]= $contenu;
                ($jeton==$contenu)? $jeton_tst= true: $jeton_tst= false; break;
            case '\\s\\':
                $Message['s'][$imes]= $contenu;
            case '\\v\\':
                $Message['v'][$imes]= $contenu;
            case '\\i\\':
                $Message['i'][$imes]= $contenu;
                ($ipc==$contenu)? $ipc_tst= true: $ipc_tst= false; break;
            case '\\d\\':
                $Message['d'][$imes]= $contenu;
                ($jour==$contenu)? $d_tst= true: $d_tst= false; break;
            case '\\h\\':
                $Message['h'][$imes]= $contenu;
                $mindif= substr($heure,3,4) - substr($contenu,3,4);
                ($mindif<=5)? $heure_tst= true: $heure_tst= false;
                $heudif= substr($heure,0,2) - substr($contenu,0,2);
                if ($heudif!=0) $heure_tst= false;
                break;
            case '\\n\\':
                $Message['n'][$imes]= $contenu; break;
            case '\\c\\':
                $Message['c'][$imes]= $contenu; break;
            case '\\r\\':
                $Message['r'][$imes]= $contenu; break;
            case '\\-\\':
                // fin de message
                if ($nouveau AND $jeton_tst AND $ipc_tst AND $heure_tst){
                    // le m�me que le nouveau
                    $imesnou= $imes; // indice nouveau message
                    $Message['h'][$imes]= $heure;
                    $Message['n'][$imes]= $nom;
                    $Message['c'][$imes]= $commentaire;
                }
                $imes++; // au suivant
                $jeton_tst= false; $ipc_tst= false; $heure_tst= false;
                break;
        }
    }
    $nmes= $imes-1;
    //--- r�duction des messages si le champ $commentaire est vide
    if ($commentaire==''){
        $Message['v'][$imesnou]= -1;
        $nouveau= 0;
    } 
    //-------------------------------------------------------------------------/
    //=== reecriture du fichier de messages -----------------------------------/
    $pf2=fopen($nomfichier_messag,'w');
    fwrite($pf2,$entete);
    ($nouveau AND ($imesnou==0))? $imesdeb=0: $imesdeb=1;
    for ($imes=$imesdeb; $imes<=$nmes; $imes++){
        if ($Message['v'][$imes]== -1) continue; // '-1'> le message est effac�
        fwrite($pf2,'\\*\\'.$Message['*'][$imes]."\n");
        fwrite($pf2,'\\s\\'.$Message['s'][$imes]."\n");
        fwrite($pf2,'\\v\\'.$Message['v'][$imes]."\n");
        fwrite($pf2,'\\i\\'.$Message['i'][$imes]."\n");
        fwrite($pf2,'\\d\\'.$Message['d'][$imes]."\n");
        fwrite($pf2,'\\h\\'.$Message['h'][$imes]."\n");
        fwrite($pf2,'\\n\\'.$Message['n'][$imes]."\n");
        fwrite($pf2,'\\c\\'.$Message['c'][$imes]."\n");
        fwrite($pf2,'\\r\\'.$Message['r'][$imes]."\n");
        fwrite($pf2,'\\-\\----------'."\n");
    }
    fclose($pf2);
    //-------------------------------------------------------------------------/
    //=== lib�ration du verrou ------------------------------------------------/
    $pfv=fopen($nomfichier_verrou,'w');
    fwrite($pfv,'');
    fclose($pfv);
    //-------------------------------------------------------------------------/
}// $action== 'ecriture'
//
/*========= Mode lecture ($action= 'lecture') ================================*/
if ($action=='lecture'){
    $nouveau= false; // pas de nouveau message
    //--- lecture du fichier de messages lignes par lignes
    $pf1= fopen($nomfichier_messag,'r');
    $n=-1;
    while (!feof($pf1)) $ligne[++$n]=fgets($pf1,1024);
    fclose($pf1);
    $nlgn= $n;
    //-------------------------------------------------------------------------/
    //=== analyse du contenu et traitement ------------------------------------/
    $entete=$ligne[0]; // la premi�re ligne est une ent�te
    //--- traitement ligne par ligne
    $imes= 1;
    for ($ilgn=1; $ilgn<=$nlgn; $ilgn++){
        $clef= substr($ligne[$ilgn],0,3);
        $contenu= substr($ligne[$ilgn],3);
        $contenu= str_replace($Car_nr,'',$contenu);
        switch($clef){
            case '\\v\\':
                $Message['v'][$imes]= $contenu; break;
            case '\\d\\':
                $Message['d'][$imes]= $contenu; break;
            case '\\h\\':
                $Message['h'][$imes]= $contenu; break;
            case '\\n\\':
                $Message['n'][$imes]= $contenu; break;
            case '\\c\\':
                $Message['c'][$imes]= $contenu; break;
            case '\\-\\': // fin de message
                $imes++;  // au suivant
                break;
        }
    }
    $nmes= $imes-1;
}// $action== 'lecture'
//-----------------------------------------------------------------------------//

/*========= Affichage ========================================================*/
if ($nouveau){
$imes= $imesnou;
    echo('<hr><fieldset>');
    echo('<legend>Votre nouveau message est enregistr&eacute; : <i>( vous pouvez encore le modifier )</i></legend>');
    echo('&nbsp;Nom: '.$Message['n'][$imes].'<br>');
    echo($Message['c'][$imes]);
    echo('</fieldset>');
}
//
echo('<hr>');
for ($imes=1; $imes<=$nmes; $imes++){
    if ($Message['v'][$imes]== 0) continue; // '0'> le message n'est pas affich�
    if ($imes != $imesnou){
        echo('<b>Nom&nbsp;:</b>&nbsp;'.$Message['n'][$imes].'&nbsp;&nbsp;');
        echo('<b>Date&nbsp:</b>&nbsp;'.$Message['d'][$imes].'&nbsp;'.$Message['h'][$imes].'<br>');
        echo($Message['c'][$imes].'<br>');
        echo('<hr>'."\n");
    }
}
//-----------------------------------------------------------------------------/
?>
