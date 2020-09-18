<?php

class ChybaKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
		// Hlavièka požadavku
		header("HTTP/1.0 404 Not Found");
		// Hlavièka stránky
		$this->hlavicka['titulek'] = 'Chyba 404';
		// Nastavení šablony
		$this->pohled = 'chyba';
    }
}