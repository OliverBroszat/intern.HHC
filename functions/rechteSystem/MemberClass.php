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
   //Vorname de
    private $vorname;
    private $nachname;
    private $rollen;

    function __construct()
    {
        global $vorname, $nachname, $rollen;

        $user = wp_get_current_user();
        $vorname = $user->user_firstname;
        $nachname = $user->user_lastname;
    }
}