<?php

class BooksController
{

    public function index(Request $request)
    {


        $categories = Category::select_all();

        View::set_data('categories', $categories);
        View::set_data('title', 'All Books');

        include "views/books/books.view.php";

    }

    public function search(Request $request)
    {
        try {

            $keyword = $request->getParams()->getString('q');

            if (!empty($keyword)) {
                $books = Book::search($keyword);
                $categories = Category::select_all();

                View::set_data('books', $books);
                View::set_data('categories', $categories);
                View::set_data('title', sprintf("Search results for → %s", $keyword));
                View::set_data('keyword', $keyword);
                include "views/books/books_search.view.php";
            } else {
                App::redirect('/books');
            }

        } catch (Exception $ex) {
            AppExceptions::showExceptionView($ex->getMessage());
        }
    }

    public function add(Request $request)
    {

        try {

            $fields = [
                'subcat_id' => $request->getParams()->getInt('subcat_id'),
            ];


            $subcategory = Subcategory::select($fields['subcat_id']);
            $category = $subcategory->get_category();

            View::set_data('category', $category);
            View::set_data('subcategory', $subcategory);
            View::set_data('categories', Category::select_all());


            include "views/books/books_add.view.php";

        } catch (Exception $ex) {
            AppExceptions::showExceptionView($ex->getMessage());
        }

    }

    public function adding(Request $request)
    {

        try {


            $fields = [
                'cat_id' => $request->getParams()->getInt('cat_id'),
                'subcat_id' => $request->getParams()->getInt('subcat_id'),
                'title' => $request->getParams()->getString('title'),
                'author_id' => $request->getParams()->getInt('author_id'),
                'page_count' => $request->getParams()->getInt('page_count'),
                'isbn' => $request->getParams()->getString('isbn'),
                'book_overview' => $request->getParams()->getString('book_overview'),
            ];


            if (empty($fields['author_id'])) {
                App::redirect('/books/add', ['subcat_id' => $fields['subcat_id'], 'error' => '1']);
            }


            $book = new Book();
            $book->title = $fields['title'];
            $book->category_id = $fields['cat_id'];
            $book->subcategory_id = $fields['subcat_id'];
            $book->author_id = $fields['author_id'];
            $book->page_count = $fields['page_count'];
            $book->isbn = $fields['isbn'];
            $book->book_overview = $fields['book_overview'];


            if ($book->insert()) {
                $book_id = Book::get_last_insert_id();
                App::redirect('/books/edit', ['id' => $book_id]);
            }


        } catch (AppExceptions $ex) {
            $ex->showMessage();
        }

    }

    public function edit(Request $request)
    {

        try {

            $id = $request->getParams()->getInt('id');

            $book = Book::select($id);

            $categories = Category::select_all();

            View::set_data('book', $book);
            View::set_data('categories', $categories);

            include "views/books/books_edit.view.php";

        } catch (AppExceptions $exception) {
            $exception->showMessage();
        }
    }


    public function editing(Request $request)
    {


        try {

            $fields = [
                'id' => $request->getParams()->getInt('id'),
                'title' => $request->getParams()->getString('title'),
                'category_id' => $request->getParams()->getInt('category_id'),
                'subcategory_id' => $request->getParams()->getInt('subcategory_id'),
            ];

            $has_image = $request->getFiles()->has('image');

            if ($has_image) {
                // 1. image upload enabled.

                $uploaded_image = new UploadedFile($request->getFiles()->get('image'));

                // check uploaded image is valid before calling the saveFile()

                if (!$uploaded_image->has_error()) {
                    if ($uploaded_image->save_file(BOOK_COVERS_UPLOAD_PATH)) {

                        $img_resize = new ImageProcessor($uploaded_image->get_full_uploaded_file_path());
                        $img_resize->resize_exact(400, 600);
                        $img_resize->save_image($uploaded_image->get_full_uploaded_file_path());

                        $book = Book::select($fields['id']);
                        $book->title = $fields['title'];
                        $book->category_id = $fields['category_id'];
                        $book->subcategory_id = $fields['subcategory_id'];
                        $book->image_url = $uploaded_image->get_uploaded_file_name();


                        if ($book->update()) {
                            App::redirect('/books/edit?id=' . $fields['id']);
                        }

                    }
                }


            } else {
                // 2. image upload disabled. (default state)
                $book = Book::select($fields['id']);
                $book->title = $fields['title'];
                $book->category_id = $fields['category_id'];
                $book->subcategory_id = $fields['subcategory_id'];

                if ($book->update()) {
                    App::redirect('/books/edit?id=' . $fields['id']);
                }
            }

        } catch (Exception $e) {

            $id = $request->getParams()->getInt('id');
            $book = Book::select($id);

            $categories = Category::select_all();

            View::set_data('book', $book);
            View::set_data('categories', $categories);

            View::set_error('error', $e->getMessage());
            include_once "views/books/books_edit.view.php";

        }


    }

    public function view_by_subcategory(Request $request)
    {

        try {
            $field = ['subcat_id' => $request->getParams()->getInt('subcat_id')];

            $books = Book::get_all_books_by_subcategory($field['subcat_id']);

            $categories = Category::select_all();

            $subcat = Subcategory::select($field['subcat_id']);

            $title = sprintf("%s → %s", $subcat->get_category(), $subcat->subcategory_name);

            View::set_data('books', $books);
            View::set_data('categories', $categories);
            View::set_data('title', $title);
            View::set_data('selected_subcategory', $subcat);

            include "views/books/books_subcategory.view.php";
        } catch (Exception $exception) {
            die($exception->getMessage());
        }

    }


}