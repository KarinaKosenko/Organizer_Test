<?php
 
namespace M;

use Core\Model;

/**
 * Class Tasks - a model to work with tasks.
 */
class Tasks extends Model
{
    public $per_page;
    public $cur_page;
    protected static $instances = [];
    
    public static function instance($path = 1)
    {
        if (!isset(self::$instances[$path])) {
            self::$instances[$path] = new self($path);
        }
       
        return self::$instances[$path];
    }
	
    protected function __construct($cur_page)
    {
		parent::__construct();
		$this->table = 'tasks';
		$this->pk = 'id_task';
		$this->per_page = 3;
		$this->cur_page = $cur_page;
    }

    /**
     * Method determines validation rules.
     *
     * @return array
     */
    public function validationMap()
    {
		return [
			'table' => 'tasks',
			'pk' => 'id_task',
			'fields' => ['id_task', 'username', 'email', 'text', 'image_link', 'image_original_name', 'status'],
			'not_empty' => ['username', 'e-mail', 'text'],
			'min_length' => [
				'username' => 5,
				'text' => 30
			],
			'unique' => [],
			'html_allowed' => ['image_link']
		];
    }

    /**
     * Method returns sorted data from defined table.
     *
     * @param string $sort_column
     * @param string $sort_order
     * @return array
     */
    public function getData($sort_column = 'status', $sort_order = 'ASC')
    {
        $start = ($this->cur_page - 1) * $this->per_page;

        $data = $this->db
            ->select("SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} ORDER BY {$sort_column} {$sort_order} LIMIT $start, $this->per_page");
        $get_rows = $this->db->select("SELECT FOUND_ROWS()");
        $rows = $get_rows[0]["FOUND_ROWS()"];
        $num_pages = ceil($rows / $this->per_page);

        $obj = compact("data", "rows", "start", "num_pages");
        return $obj;
    }
    
}

