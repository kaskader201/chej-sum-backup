<?php


 
class CategoryManager
{

	/**
	 * Zformátuje pole kategorií z databáze rekurzivně do stromu
	 * Kód z http://www.jugbit.com/php/php-recursive-menu-with-1-query/
	 * @param array $items Položky
	 * @param int $parentId Id rodičovské kategorie
	 * @return array Kategorie ve stromové podobě
	 */
	private function formatTree($items, $parentId)
	{
		// Vytvoříme prázdný strom
		$tree = array();
		// Pokusíme se najít položky, které patří do rodičovské kategorie ($parentId)
		foreach ($items as $item)
		{
			if ($item['parent_category_id'] == $parentId)
			{
				// Položku přidáme do nového stromu
				$tree[$item['category_id']] = $item;
				// A rekurzivně přidáme strom podpoložek
				$tree[$item['category_id']]['subcategories'] = $this->formatTree($items, $item['category_id']);
			}
		}
		return $tree; // Vrátíme hotový strom
	}

	/**
	 * Vrátí kategorie produktů v podobě stromu
	 * @return array Kategorie produktů v podobě stromu
	 */
	public function getCategories()
	{
		$categories = Db::queryAll('SELECT * FROM category ORDER BY parent_category_id, order_no');
		return $this->formatTree($categories, null);
	}

	public function renderCategories($categories, $parentUrl = '')
	{
		echo('<ul class="nav nav-pills nav-stacked category-menu">');

		foreach ($categories as $index => $category)
		{
			$url = $parentUrl . '/' . $category['url'];
			if ($category['subcategories'])
			{
				echo('<li><label class="nav-header" data-path="' . $url . '">' . $category['title'] . '</label>');
				self::renderCategories($category['subcategories'], $url, false);
				echo('</li>');
			}
			else
				echo('<li><a href="produkty/index' . $url . '" data-path="' . $url . '">' . $category['title'] . '</a></li>');
		}

		echo('</ul>');
	}
	
	/**
	 * Vrátí ty kategorie, které v sobě již neobsahují další podkategorie (listy stromu kategorií)
	 * @return array Kategorie, které v sobě již neobsahují další podkategorie (listy stromu kategorií)
	 */
	public function getCategoryLeafs()
	{
		return Db::queryAll('
            SELECT category_id, title
            FROM category
            WHERE category_id NOT IN (
                SELECT parent_category_id as category_id
                FROM category
                WHERE parent_category_id IS NOT NULL
            )
        ');
	}

	/**
	 * Vrátí JSON s kategoriemi produktů
	 * @return string JSON s kategoriemi produktů
	 */
	public function getCategoriesJson()
	{
		$categories = Db::queryAll('SELECT * FROM category ORDER BY parent_category_id, order_no');
		return json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	/**
	 * Aktualizuje kategorie produktů
	 * @param array $categories Nové kategorie produktů
	 * @throws UserException
	 */
	public function saveCategories($categories)
	{
		Db::query('TRUNCATE TABLE category'); // Smaže všechny kategorie a resetuje autoincrement, aby byl od 1
		$filtered = array();
		foreach ($categories as $category)
		{
			$filtered = array(
				'category_id' => $category['category_id'],
				'url' => $category['url'],
				'title' => $category['title'],
				'order_no' => $category['order_no'],
				'hidden' => $category['hidden'],
				'parent_category_id' => isset($category['parent_category_id']) ? $category['parent_category_id'] : null,
			);
			Db::insert('category', $filtered);
		}
	}

} 