<?php

/**
 * Created by PhpStorm.
 * User: Oliver Broszat
 * Date: 07.07.2016
 * Time: 15:09
 * Class MemberClass
 * Dient der Erfassung und dem erleichterten Zugriff auf alle Personenbezogenen Attribute
 * wie Namen, Ressort und die zugeordneten Rollen
 */


class MemberClass
{
   private static $instance;

    private $vorname;
    private $nachname;
    private $rollen;
    private $email;
    private $ressort;
    private $position;
    private $userID;



    /**
     * MemberClass constructor.
     * Ist private da die Klasse das eingeloggte Mitglied repräsentieren soll.
     * Es soll daher nur ein Objekt erstellt werden, mit dem im gesamgten Projekt gearbeitet werden soll.
     *
     * Das Objekt der Klasse kann mittels der getInstance() Funktion aufgerufen und verwendet werden.
     */
    private function __construct()
    {
        echo "\nMail: " .$this->getEmail();
        echo "\nNach: ".$this->getNachname();
        echo "\nPosi: ".$this->getPosition();
        echo "\nRessort: ".$this->getRessort();
        echo "\nUserID: ".$this->getUserID();
        echo "\nVorname: ".$this->getVorname();
    }


    /**
     * Falls noch kein Benutzerobjekt existiert wird eines angelegt und zurückgegeben
     * Alternativ wird das bereits vorhandene Objekt zurückgegeben.
     *
     * @return MemberClass
     */
    public function getInstance(){
        global $instance;

        if($instance == null){
            $instance = new MemberClass();
        }

        return $instance;
    }

    function getUserID(){
        global $wpdb, $userID;

        if($userID == null){
            $email = $this->getEmail();

            $query = "SELECT c.id
                    from contact c
                    join member m on c.id = m.contact
                    join mail ma on ma.contact = c.id
                    where address = '$email'";

           $userID = $wpdb->get_row($query)->id;
        }

        return $userID;
    }

    function getRessort(){
        global $wpdb, $ressort;

        if($ressort == null){
           $userID = $this->getUserID();

            $query = "SELECT r.name as ressort
	        	  from member m
	        	  join ressort r on m.ressort = r.id
	        	  where m.contact = '$userID'";

            $ressort = $wpdb->get_row($query)->ressort;
        }

        return $ressort;
    }

    function getPosition(){
        global $wpdb, $position;

        if($position == null){
            $userID = $this->getUserID();

            $query = "SELECT position
	        	from member m
	        	where contact = '$userID'";
            $position = $wpdb->get_row($query)->position;
        }

        return $position;
    }

    function getEmail(){
        return wp_get_current_user()->user_email;
    }

    function getVorname(){
        return wp_get_current_user()->user_firstname;
    }

    function getNachname(){
        return wp_get_current_user()->user_lastname;
    }
}