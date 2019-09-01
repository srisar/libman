<?php


class Book
{
    public $id, $title, $category_id, $subcategory_id, $author_id, $image_url;

    /**
     * Returns a string representation of a Book
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s", $this->title);
    }

    /**
     * Select all the books.
     * @param int $limit
     * @return Book[]
     */
    public static function select_all($limit = 100)
    {
        $db = Database::get_instance();

        $statement = $db->prepare("SELECT * FROM books LIMIT :limit_value");
        $statement->bindParam(":limit_value", $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Book::class);

    }

    /**
     * Select a book by id
     * @param $id
     * @return Book
     */
    public static function select_by_id($id)
    {
        $db = Database::get_instance();

        $statement = $db->prepare("SELECT * FROM books WHERE id=?");
        $statement->execute([$id]);

        return $statement->fetchObject(Book::class);
    }


    /**
     * Insert multiple books at once.
     * @param array $titles
     */
    public static function batch_insert(array $titles)
    {

        try {
            $db = Database::get_instance();

            $db->beginTransaction();

            $statement = $db->prepare("INSERT INTO books(title) VALUE (?)");

            foreach ($titles as $title) {
                $statement->execute([$title]);
            }

            $db->commit();

        } catch (PDOException $exception) {

            $db->rollBack();
            die($exception->getMessage());

        }


    }

    /**
     * Insert a new book into database.
     * @return bool
     */
    public function insert()
    {
        $db = Database::get_instance();

        $statement = $db->prepare("INSERT INTO books(title, category_id, subcategory_id) VALUE (?,?,?)");
        return $statement->execute(
            [$this->title, $this->category_id, $this->subcategory_id]
        );
    }


    /**
     * Returns the last inserted id.
     * @return string
     */
    public static function get_last_insert_id()
    {
        $db = Database::get_instance();
        return $db->lastInsertId();
    }

    /**
     * Update book.
     * @return bool
     */
    public function update()
    {

        $db = Database::get_instance();

        $statement = $db->prepare("UPDATE books SET title=?, subcategory_id=?, category_id=?, image_url=? WHERE id=?");
        return $statement->execute([$this->title, $this->subcategory_id, $this->category_id, $this->image_url, $this->id]);
    }


    /**
     * Get the Book's category.
     * @return Category
     */
    public function get_category()
    {
        return Category::select($this->category_id);
    }

    /**
     * Returns a display name as [Title (category)]
     * @return string
     */
    public function get_display_name()
    {
        return sprintf("%s (%s)", $this->title, $this->get_category());
    }


    /**
     * Get all the books from the given instance.
     * @return BookInstance[]
     */
    public function get_all_book_instances()
    {
        $db = Database::get_instance();
        $statement = $db->prepare("SELECT * FROM book_instances WHERE book_id=?");
        $statement->execute([$this->id]);

        return $statement->fetchAll(PDO::FETCH_CLASS, BookInstance::class);

    }

    /**
     * Get all the books in a given subcategory id.
     * @param $id
     * @return Book[]
     */
    public static function get_all_books_by_subcategory($id)
    {
        $subcategory = Subcategory::get_subcategory_by_id($id);
        return $subcategory->get_all_books();
    }


    public function has_image_url()
    {
        return !empty($this->image_url);
    }

}
