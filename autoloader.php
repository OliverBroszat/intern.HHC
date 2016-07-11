<?php
/**
 * Created by PhpStorm.
 * User: Oliver Broszat
 * Date: 07.07.2016
 * Time: 15:49
 *
 * Der Autoloader soll dafür sorgen, dass wir beim importieren von Dateien keine Pfade mehr angeben müssen.
 * Um den Autoloader korrekt verwenden zu können muss er zu Beginn mittels require importiert werden.
 * Anschließend kann man ihn aufrufen und ihm den Namen der benötigten Datei übergeben.
 *
 * Bsp:
 *
 * require($pfad);
 * __autoload($dateiname);
 */

function __autoload($class_name)
{
    /**
     * Hier müssen alle Ordner angegeben werden,
     * die nach den benötigten Dateien durchsucht werden sollen.
     */
    $directorys = array(
        'functions/',
        'functions/rechteSystem/',
        'functions/apply/',
        'functions/edit/',
        'functions/html_templates/',
        'functions/suchfunktion/',
        'functions/register/'
    );

    //Jedes Verzeichnis soll überprüft werden
    foreach($directorys as $directory)
    {
        //Überprüft ob die Date im aktuell durchsuchten Verzeichnis vorhanden ist.
        $root = get_template_directory();
        $path = "$root/$directory$class_name" . ".php";

        if(file_exists($path))
        {
            require_once($path);
            return;
        }
    }
}

?>