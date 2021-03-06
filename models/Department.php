<?php


class Department
{

    /** @var int */
    public $id;
    /** @var string */
    public $department_name;


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->department_name;
    }

    public function getLongDepartmentName()
    {
        return sprintf("%s (%d)", $this->department_name, $this->getMembersCount());
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function selectAll($limit = 100, $offset = 0): array
    {
        $db = Database::getInstance();
        $statement = $db->prepare("SELECT * FROM departments ORDER BY department_name ASC LIMIT :limit_value OFFSET :offset_value");

        $statement->bindValue(':limit_value', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset_value', $offset, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Department::class);

    }

    /**
     * @param $id
     * @return Department
     */
    public static function select($id): Department
    {
        $db = Database::getInstance();
        $statement = $db->prepare("SELECT * FROM departments WHERE id=? LIMIT 1");

        $statement->execute([$id]);

        $result = $statement->fetchObject(Department::class);

        if (empty($result))
            return null;

        return $result;

    }

    /**
     * @param $name
     * @return mixed
     */
    public static function selectByName($name): Department
    {
        $db = Database::getInstance();
        $statement = $db->prepare("SELECT * FROM departments WHERE department_name=? LIMIT 1");

        $statement->execute([$name]);

        $result = $statement->fetchObject(Department::class);

        if (empty($result))
            return null;
        return $result;
    }

    /**
     * @return bool
     */
    public function insert()
    {
        $db = Database::getInstance();
        $statement = $db->prepare("INSERT INTO departments(department_name) VALUE (?)");
        return $statement->execute([$this->department_name]);

    }

    /**
     * @return bool
     */
    public function update()
    {
        $db = Database::getInstance();
        $statement = $db->prepare("UPDATE departments SET department_name=? WHERE id=?");
        return $statement->execute([$this->department_name, $this->id]);
    }


    /**
     * Check if given department name already exist.
     * @param $department_name
     * @return Department|bool
     */
    public static function departmentNameExists($department_name)
    {
        $db = Database::getInstance();
        $statement = $db->prepare("SELECT * FROM departments WHERE department_name=? LIMIT 1");
        $statement->execute([$department_name]);

        return $statement->fetchObject(Department::class);

    }

    /**
     * Deletes a department
     */
    public function delete()
    {

    }

    /**
     * Get all associated members from the department.
     * @param int $limit
     * @param int $offset
     * @return Member[]
     */
    public function getAllMembers($limit = 100, $offset = 0): array
    {
        $db = Database::getInstance();

        $statement = $db->prepare(
            "SELECT * FROM members 
        WHERE department_id=:dept_id 
        ORDER BY member_since ASC 
        LIMIT :limit_value OFFSET :offset_value"
        );

        $statement->bindValue(':dept_id', $this->id, PDO::PARAM_INT);
        $statement->bindValue(':limit_value', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset_value', $offset, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Member::class);
    }

    /**
     * @return Member[]
     */
    public function getAllSudents(): array
    {
        return Member::getByType($this, Member::TYPE_STUDENT);
    }

    /**
     * @return Member[]
     */
    public function getAllTeachers(): array
    {
        return Member::getByType($this, Member::TYPE_TEACHER);
    }

    /**
     *  Get the member count associated with the department
     */
    public function getMembersCount()
    {
        $db = Database::getInstance();
        $statement = $db->prepare("SELECT COUNT(id) as count FROM members WHERE department_id=?");
        $statement->execute([$this->id]);

        $result = $statement->fetchObject();

        if (!empty($result)) {
            return $result->count;
        }

        return 0;
    }

    /**
     * @return int
     */
    public static function getStatsTotalDepartments()
    {
        $db = Database::getInstance();
        $statement = $db->prepare("SELECT COUNT(id) as result FROM departments;");
        $statement->execute();

        $result = $statement->fetchObject(stdClass::class);

        if (!empty($result))
            return $result->result;

        return 0;
    }

}