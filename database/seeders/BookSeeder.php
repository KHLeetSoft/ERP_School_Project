<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // Programming & Computer Science
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'genre' => 'Programming',
                'published_year' => 1999,
                'isbn' => '978-0201616224',
                'description' => 'Journey to mastery of software craftsmanship.',
                'stock_quantity' => 10,
                'shelf_location' => 'A1-15',
                'status' => 'available',
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'genre' => 'Programming',
                'published_year' => 2008,
                'isbn' => '978-0132350884',
                'description' => 'Handbook of Agile Software Craftsmanship.',
                'stock_quantity' => 8,
                'shelf_location' => 'A1-16',
                'status' => 'available',
            ],
            [
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen et al.',
                'genre' => 'Computer Science',
                'published_year' => 2009,
                'isbn' => '978-0262033848',
                'description' => 'Comprehensive algorithms textbook.',
                'stock_quantity' => 5,
                'shelf_location' => 'B1-10',
                'status' => 'available',
            ],
            [
                'title' => 'JavaScript: The Good Parts',
                'author' => 'Douglas Crockford',
                'genre' => 'Programming',
                'published_year' => 2008,
                'isbn' => '978-0596517748',
                'description' => 'Essential JavaScript concepts and best practices.',
                'stock_quantity' => 6,
                'shelf_location' => 'A2-05',
                'status' => 'available',
            ],
            
            // Literature & Fiction
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'genre' => 'Fiction',
                'published_year' => 1960,
                'isbn' => '978-0446310789',
                'description' => 'A gripping tale of racial injustice and childhood innocence.',
                'stock_quantity' => 12,
                'shelf_location' => 'C1-20',
                'status' => 'available',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'genre' => 'Fiction',
                'published_year' => 1949,
                'isbn' => '978-0451524935',
                'description' => 'A dystopian social science fiction novel.',
                'stock_quantity' => 9,
                'shelf_location' => 'C1-21',
                'status' => 'available',
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'genre' => 'Fiction',
                'published_year' => 1913,
                'isbn' => '978-0141439518',
                'description' => 'A romantic novel of manners.',
                'stock_quantity' => 7,
                'shelf_location' => 'C2-15',
                'status' => 'available',
            ],
            
            // Science & Mathematics
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'genre' => 'Science',
                'published_year' => 1988,
                'isbn' => '978-0553380163',
                'description' => 'Exploring the universe and its mysteries.',
                'stock_quantity' => 8,
                'shelf_location' => 'D1-10',
                'status' => 'available',
            ],
            [
                'title' => 'The Selfish Gene',
                'author' => 'Richard Dawkins',
                'genre' => 'Science',
                'published_year' => 1976,
                'isbn' => '978-0192860927',
                'description' => 'Revolutionary view of evolution and genetics.',
                'stock_quantity' => 6,
                'shelf_location' => 'D1-11',
                'status' => 'available',
            ],
            [
                'title' => 'Calculus: Early Transcendentals',
                'author' => 'James Stewart',
                'genre' => 'Mathematics',
                'published_year' => 2015,
                'isbn' => '978-1285741550',
                'description' => 'Comprehensive calculus textbook.',
                'stock_quantity' => 15,
                'shelf_location' => 'E1-05',
                'status' => 'available',
            ],
            
            // History & Biography
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'genre' => 'History',
                'published_year' => 2011,
                'isbn' => '978-0062316097',
                'description' => 'The history of our species from the Stone Age to the present.',
                'stock_quantity' => 10,
                'shelf_location' => 'F1-12',
                'status' => 'available',
            ],
            [
                'title' => 'The Diary of a Young Girl',
                'author' => 'Anne Frank',
                'genre' => 'Biography',
                'published_year' => 1947,
                'isbn' => '978-0553577129',
                'description' => 'The diary of Anne Frank during the Holocaust.',
                'stock_quantity' => 8,
                'shelf_location' => 'F2-08',
                'status' => 'available',
            ],
            
            // Business & Economics
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'genre' => 'Psychology',
                'published_year' => 2011,
                'isbn' => '978-0374533557',
                'description' => 'Understanding the two systems of thought.',
                'stock_quantity' => 7,
                'shelf_location' => 'G1-15',
                'status' => 'available',
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'genre' => 'Business',
                'published_year' => 2011,
                'isbn' => '978-0307887894',
                'description' => 'How to build a successful startup.',
                'stock_quantity' => 9,
                'shelf_location' => 'G1-16',
                'status' => 'available',
            ],
        ];

        // If there are schools, seed per school; otherwise, seed without school
        $schoolIds = \App\Models\School::pluck('id');
        if ($schoolIds->isNotEmpty()) {
            foreach ($schoolIds as $schoolId) {
                foreach ($books as $book) {
                    $payload = array_merge($book, ['school_id' => $schoolId]);
                    Book::firstOrCreate(
                        ['isbn' => $payload['isbn'], 'school_id' => $schoolId],
                        $payload
                    );
                }
                Book::factory()->count(20)->create(['school_id' => $schoolId]);
            }
        } else {
            foreach ($books as $book) {
                Book::firstOrCreate(
                    ['isbn' => $book['isbn']],
                    $book
                );
            }
            Book::factory()->count(50)->create();
        }
    }
}


