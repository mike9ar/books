<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    function can_get_all_books()
    {
        $book = Book::factory(4)->create();

        $respone = $this->getJson(route('books.index'));

        $respone->assertJsonFragment([
            'title' => $book[0]->title
        ])->assertJsonFragment([
            'title' => $book[1]->title
        ]);

    }

    /**
     * @test
     */
    function can_get_one_book()
    {
        $book = Book::factory()->create();
        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title
        ]);
    }

       /**
     * @test
     */
    function can_create_books()
    {
        $this->postJson(route('books.store', []))
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store', [
            'title' => 'Mi nuevo libro'
        ]))->assertJsonFragment([
            'title' => 'Mi nuevo libro'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Mi nuevo libro'
        ]);

    }

         /**
     * @test
     */
    function can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Libro editado'
        ])->assertJsonFragment([
            'title' => 'Libro editado'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Libro editado'
        ]);

    }

    /**
     * @test
     */
    function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent(); // 204

        $this->assertDatabaseCount('books', 0);
    }
}
