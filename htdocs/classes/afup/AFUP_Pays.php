<?php

/**
 * Classe de gestion des pays 
 */
class AFUP_Pays
{
    /**
     * Instance de la couche d'abstraction à la base de données
     * @var     object
     * @access  private
     */
    var $_bdd;

    /**
     * Constructeur.
     * 
     * @param  object    $bdd   Instance de la couche d'abstraction à la base de données   
     * @access public
     * @return void
     */
    function AFUP_Pays(&$bdd)
    {
        $this->_bdd = $bdd;   
    }  
    
    /**
     * Renvoit un tableau associatif des pays avec le code ISO comme clé et le nom comme valeur
     * 
     * @access public
     * @return array
     */
    function obtenirPays()
    {
        $requete = 'SELECT id, nom FROM afup_pays ORDER BY nom';
        return $this->_bdd->obtenirAssociatif($requete);    
    }
    
    /**
     * Renvoit le nom du pays à partir du code ISO
     * 
     * @param  string   $id         Identifiant ISO 2a du pays
     * @param  string   $champs     Champs à renvoyer
     * @return string

     */    
    function obtenirNom($id)
    {
        $requete = 'SELECT nom FROM afup_pays WHERE id ='. $this->_bdd->echapper($id);;
        return $this->_bdd->obtenirUn($requete);    
    }    
    
    function obtenirZonesFrancaises()
    {
    		$zonesFrancaises[0] = '--';
    		$zonesFrancaises[1] = '01 - Ile de France';
    		$zonesFrancaises[2] = '02 - Nord Ouest';
    		$zonesFrancaises[3] = '03 - Nord Est';
    		$zonesFrancaises[4] = '04 - Sud Est';
    		$zonesFrancaises[5] = '05 - Sud Ouest';
    		
    		return $zonesFrancaises;
    }
}

?>