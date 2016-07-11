<?php

/**
 * Created by PhpStorm.
 * User: Oliver Broszat
 * Date: 07.07.2016
 * Time: 15:12
 *
 * Prüft ob das aktuell eingeloggte Mitglied die entsprechenden Rechte hat den Seiteninhalt zu sehen.
 * Sind die nötigen Rechte nicht vorhanden wird der für das Mitglied angezeigte Seiteninhalt
 * entsprechend verborgen oder der Benutzer wird auf eine andere Seite weitergeleitet.
 */

class PageClass
{
    function __construct()
    {
        $this->isLoggedIn();
    }

    function isLoggedIn(){
        if(!is_user_logged_in()){
            wp_redirect( home_url( '' ) );
        }
    }
}