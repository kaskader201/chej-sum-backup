<?php

// Controller pro výpis produktu

class VelkoobchodKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
	
	   	$this->hlavicka = array(
				'titulek' => 'Velkoobchod',
				'klicova_slova' => 'Chejn, velkoobchod,velkoodběr',
				'popis' =>'Chejn velko odběr a velkoobchod',
			);   
			
			$this->pohled = 'velkoobchod';
	
    }
}