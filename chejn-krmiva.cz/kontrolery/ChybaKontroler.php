<?php

class ChybaKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
		// Hlavi�ka po�adavku
		header("HTTP/1.0 404 Not Found");
		// Hlavi�ka str�nky
		$this->hlavicka['titulek'] = 'Chyba 404';
		// Nastaven� �ablony
		$this->pohled = 'chyba';
    }
}