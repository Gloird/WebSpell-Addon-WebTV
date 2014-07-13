<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2011 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

$_language->read_module('stream');

if(!ispageadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

if( isset($_GET['add']) ){
    
    echo '<h1>Ajouter une chaine de Stream</h1>';
    
    echo '<form method="post" action="?site=stream&add_e=1">';
    //Génération du tableau de formulaire
    echo '<table>';
    
    echo '<tr>
            <td>Titre : </td>
            <td><input type="text" name="titre" value=""/></td>
          </tr>';
    echo '<tr>
            <td>Description : </td>
            <td><textarea name="desc"></textarea></td>
          </tr>';
    echo '<tr>
            <td>ID-Video (pour twitch : nom de la chaine): </td>
            <td><input type="text" name="lien" value=""/></td>
          </tr>';
    echo '<tr>
            <td>Ordre : </td>
            <td><input type="number" name="sort" value="1"/></td>
          </tr>';
    echo '<tr>
            <td>Type : </td>
            <td><select name="type">';
    $stream_type=safe_query("SELECT * FROM ".PREFIX."stream_type ORDER BY id");
    while($st=mysql_fetch_array($stream_type)) {
        
        echo '<option value="'.$st['id'].'">'.$st['name'].'</option>';
        
    }    
    echo '</select></td>
            </tr>';
            
    echo '<tr>
        <td>Image ( images/stream ): </td>
        <td><input type="text" name="image" value="aucun.png"/></td>
      </tr>';    
    echo '</table>';
    // Fin génération du tableau
    
    echo '<input type="submit" name="ok" value="Envoyer"/>';
    
    echo '</form>';
    
    echo '</br>
        </br>
        <a href="?site=stream" ><button>Retour</button></a>
        ';
    
}elseif( isset($_GET['add_e']) ){
    
    safe_query("INSERT INTO ".PREFIX."stream ( name, `desc`, type, lien, image, sort) values( '".$_POST['titre']."', '".$_POST['desc']."', '".$_POST['type']."', '".$_POST['lien']."' , '".$_POST['image']."', '".$_POST['sort']."') ");

    header('Location: ?site=stream');
    echo '</br>
    </br>
    <a href="?site=stream" ><button>Retour</button></a>
    ';

    
}elseif( isset($_GET['save_edit']) ){
    
    safe_query("UPDATE ".PREFIX."stream SET name='".$_POST['name']."', `desc`='".$_POST['desc']."', type='".$_POST['type']."', lien='".$_POST['lien']."' , image='".$_POST['image']."' , sort='".$_POST['sort']."' WHERE id='".$_POST['streamid']."'");
    header('Location: ?site=stream');
    echo '</br>
    </br>
    <a href="?site=stream" ><button>Retour</button></a>
    ';

    
}elseif( isset($_GET['delete']) && isset($_GET['streamid']) ){
    
    safe_query("DELETE FROM ".PREFIX."stream WHERE id='".$_GET['streamid']."'");
    header('Location: ?site=stream');
    
    echo '</br>
    </br>
    <a href="?site=stream" ><button>Retour</button></a>
    ';
    
}elseif( isset($_GET['edit']) && isset($_GET['streamid'])){
    
    $stream=safe_query("SELECT * FROM ".PREFIX."stream where id =".$_GET['streamid']."");
	$num=mysql_num_rows($stream);
    $st=mysql_fetch_array($stream);
    echo '<h1>Ajouter une chaine de Stream</h1>';
    
    echo '<form method="post" action="?site=stream&save_edit=1">';
    //Génération du tableau de formulaire
    echo '<table>';
    
    echo '<tr>
            <td>Titre : </td>
            <td><input type="text" name="name" value="'.$st['name'].'"/></td>
          </tr>';
    echo '<tr>
            <td>Description : </td>
            <td><textarea name="desc">'.$st['desc'].'</textarea></td>
          </tr>';
    echo '<tr>
            <td>ID-Video (pour twitch : nom de la chaine): </td>
            <td><input type="text" name="lien" value="'.$st['lien'].'"/></td>
          </tr>';
    echo '<tr>
            <td>Ordre : </td>
            <td><input type="number" name="sort" value="'.$st['sort'].'"/></td>
          </tr>';
    echo '<tr>
            <td>Type : </td>
            <td><select name="type">';
    $stream_type=safe_query("SELECT * FROM ".PREFIX."stream_type ORDER BY id");
    while($sty=mysql_fetch_array($stream_type)) {
        $default = NULL;
        if($sty['id'] == $st['type']){
            $default = 'selected="selected"';
        }
        echo '<option value="'.$sty['id'].'" '.$default.'>'.$sty['name'].'</option>';
        
    }    
    echo '</select></td>
            </tr>';
    echo '<tr>
        <td>Image ( images/stream ): </td>
        <td><input type="text" name="image" value="'.$st['image'].'"/></td>
        </tr>';   
    echo '</table>';
    // Fin génération du tableau
    
    echo '<input type="hidden" name="streamid" value="'.$st['id'].'" /><input type="submit" name="ok" value="Envoyer"/>';
    
    echo '</form>';
    
    echo '</br>
        </br>
        <a href="?site=stream" ><button>Retour</button></a>
        ';
    
}else{
    
    echo "<h1>Liste des chaines de Stream</h1>";
    
    $stream=safe_query("SELECT * FROM ".PREFIX."stream ORDER BY sort");
	$num=mysql_num_rows($stream);
    if($num){
        
        echo '<table border="0" width="100%" bgcolor="#DDDDDD">';
        echo '<tbody>';
        echo '<tr>
                <td width="5%" class="title"><b>Ordre</b></td>
                <td width="20%" class="title"><b>Nom</b></td>
                <td width="30%" class="title"><b>Description</b></td>
                <td width="10%" class="title"><b>Lien</b></td>
                <td width="15%" class="title"><b>Type</b></td>
                <td width="20%" class="title"><b>Lien</b></td>
                <td width="20%" class="title"><b>Action</b></td>
              </tr>';

        $i=1;
        while($st=mysql_fetch_array($stream)) {
            if($i%2) { $td='td1'; }
            else { $td='td2'; }
            echo '<tr>';
                echo '<td class="'.$td.'">'.$st['sort'].'</td>';
                echo '<td class="'.$td.'">'.$st['name'].'</td>';
                echo '<td class="'.$td.'">'.$st['desc'].'</td>';
                echo '<td class="'.$td.'"><img style="width: 70px;" src="../images/stream/'.$st['image'].'" /></td>';
                    $stream_t=safe_query("SELECT * FROM ".PREFIX."stream_type Where id=".$st['type']);
                    $st_t=mysql_fetch_array($stream_t);
                    echo '<td class="'.$td.'">'.$st_t['name'].'</td>';
                
                echo '<td class="'.$td.'">'.$st['lien'].'</td>';
                echo "<td class='".$td."'><input type='submit' onclick='MM_goToURL(\"parent\",\"admincenter.php?site=stream&edit=1&streamid=".$st['id']."\");return document.MM_returnValue' name='edit' value='".$_language->module['edit']."' />";
                echo "<input type='submit' name='sup' value='".$_language->module['delete']."' onclick='MM_confirm(\"".$_language->module['really_delete']."\",\"admincenter.php?site=stream&delete=1&streamid=".$st['id']."\");return document.MM_returnValue' /></td>";
            echo '</tr>';
            $i++;
        }    
        echo '</tbody>';
        echo '</table>';
        
    }else{
        echo $_language->module['no_stream'];
    }
    
    echo '
        <a href="?site=stream&add=1" ><button>Ajouter</button></a>
        </br>';
    
   
}



?>