<?php
 
namespace Core;

/**
 * Class Validation - class to validate data from POST-requests.
 */
class Validation
{ 
	protected $pk;
    protected $obj;
    protected $rules;
    protected $errors;
    protected $clean_obj;
    
    public function __construct($obj, $rules)
	{
		$this->db = Sql::instance();
        $this->obj = $obj;
        $this->rules = $rules;
        $this->errors = [];
    }

    /**
     * Method checks unique column value for record adding.
     *
     * @param $table
     * @param $column
     * @param $value
     * @return bool
     */
	public function one_add($table, $column, $value)
	{
        $res = $this->db->select("SELECT * FROM {$table} WHERE {$column}=:column",
                                   ['column' => $value]);

        return $res[0] ?? false;
    }

    /**
     * Method checks unique column value for record editing.
     *
     * @param $table
     * @param $column
     * @param $value
     * @param $pk
     * @param $pk_value
     * @return bool
     */
	public function one_edit($table, $column, $value, $pk, $pk_value)
	{
        $res = $this->db->select("SELECT * FROM {$table} WHERE {$column}=:column AND {$pk} != :pk",
                                   [
                                       'column' => $value,
                                       'pk' => $pk_value
								   ]);

        return $res[0] ?? false;
    }

    /**
     * Method executes validation for record adding/editing.
     *
     * @param $action_name
     */
    public function execute($action_name)
    {
        foreach ($this->obj as $k => $v) {
            $value = trim($v);

            // Check field not empty.
            if (in_array($k, $this->rules['not_empty']) && $value == '') {
                $this->errors[] = "Поле $k не может быть пустым.";
            }
            // Check field minimal length.
            elseif (isset($this->rules['min_length'][$k]) && strlen($value) < $this->rules['min_length'][$k]) { //проверка поля на минимальное количество символов.
                $div = ceil($this->rules['min_length'][$k]/2);
                $this->errors[] = "Поле $k не может быть меньше {$this->rules['min_length'][$k]} латинских символов и не меньше $div русских символов.";
            }
            // Check unique value of the field (for record adding).
            elseif ($action_name === 'add' && in_array($k, $this->rules['unique']) && $this->one_add($this->rules['table'], $k, $value) != false) { //проверка поля на уникальность при добавлении в БД нового значения.
				$this->errors[] = "Такое значение поля $k уже существует.";
            }
            // Check unique value of the field (for record editing).
            elseif ($action_name === 'edit' && in_array($k, $this->rules['unique']) && $this->one_edit($this->rules['table'], $k, $value, $this->rules['pk'], $this->obj[$this->rules['pk']]) != false) { //проверка поля на уникальность при редактировании в БД существующего значения.
				$this->errors[] = "Такое значение поля $k уже существует.";
            }
            else {
                // Check HTML-symbols in the field.
                if (!in_array($k, $this->rules['html_allowed'])) {
					$value = htmlspecialchars($value);
                }

                // Get an array of the valid data.
                $this->clean_obj[$k] = $value;
            }
        }
    }

    /**
     * Check validation was successful.
     *
     * @return bool
     */
    public function good()
	{
        return count($this->errors) == 0;
    }

    /**
     * Method returns an array of valid data.
     *
     * @return mixed
     */
    public function cleanObj()
	{
        return $this->clean_obj;
    }

    /**
     * Method returns validation errors.
     *
     * @return array
     */
    public function errors()
	{
        return $this->errors;
    }
}