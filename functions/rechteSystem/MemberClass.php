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

    /**
     * MemberClass constructor.
     * Ist private da die Klasse das eingeloggte Mitglied repräsentieren soll.
     * Es soll daher nur ein Objekt erstellt werden, mit dem im gesamgten Projekt gearbeitet werden soll.
     *
     * Das Objekt der Klasse kann mittels der getInstance() Funktion aufgerufen und verwendet werden.
     */
    private function __construct()
    {
        global $vorname, $nachname, $rollen, $email, $ressort;

        $user = wp_get_current_user();
        $vorname = $user->user_firstname;
        $nachname = $user->user_lastname;
        $email = $user = user_email;
        $ressort = getRessort();
        echo "RESSORT: $ressort";
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

    public function getRessort(){
        global $email;

        $query = "SELECT r.name as ressort
	        	from contact c
	        	  join member m on c.id = m.contact
	        	  join ressort r on m.ressort = r.id
	        	  join mail ma on ma.contact = c.id
	        	where address = $email";

        return $wpdb->get_row($query);
    }
}