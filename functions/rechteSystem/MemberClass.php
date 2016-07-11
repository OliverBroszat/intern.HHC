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

    private static $vorname;
    private static $nachname;
    private static $rollen;
    private static $email;
    private static $ressort;
    private static $position;
    private static $userID;


    function getUserID(){
        global $wpdb, $userID;

        if($userID == null){
            $email = MemberClass::getEmail();

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
            $userID = MemberClass::getUserID();

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
            $userID = MemberClass::getUserID();

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