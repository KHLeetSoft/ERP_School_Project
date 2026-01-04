<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentLibraryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get library information for the student
        $libraryInfo = $this->getLibraryInfo();
        $currentBooks = $this->getCurrentBooks($student);
        $recentActivity = $this->getRecentActivity($student);
        $libraryStats = $this->getLibraryStats($student);
        $featuredBooks = $this->getFeaturedBooks();
        $newArrivals = $this->getNewArrivals();

        return view('student.library.index', compact(
            'libraryInfo',
            'currentBooks',
            'recentActivity',
            'libraryStats',
            'featuredBooks',
            'newArrivals'
        ));
    }

    public function books(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get search parameters
        $search = $request->get('search', '');
        $category = $request->get('category', 'all');
        $author = $request->get('author', 'all');
        $sortBy = $request->get('sort', 'title');

        // Get books based on search criteria
        $books = $this->getBooks($search, $category, $author, $sortBy);
        $categories = $this->getCategories();
        $authors = $this->getAuthors();

        return view('student.library.books', compact(
            'books',
            'categories',
            'authors',
            'search',
            'category',
            'author',
            'sortBy'
        ));
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, books, journals, ebooks

        // Perform search
        $results = $this->performSearch($query, $type);
        $searchSuggestions = $this->getSearchSuggestions($query);

        return view('student.library.search', compact(
            'results',
            'query',
            'type',
            'searchSuggestions'
        ));
    }

    public function bookDetails($id)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get book details
        $book = $this->getBookDetails($id);
        $relatedBooks = $this->getRelatedBooks($id);
        $reviews = $this->getBookReviews($id);
        $availability = $this->getBookAvailability($id);

        return view('student.library.book-details', compact(
            'book',
            'relatedBooks',
            'reviews',
            'availability'
        ));
    }

    public function borrowBook(Request $request, $id)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $request->validate([
            'borrow_duration' => 'required|in:7,14,21,30',
            'purpose' => 'nullable|string|max:255',
        ]);

        // Check if book is available
        if (!$this->isBookAvailable($id)) {
            return redirect()->back()->with('error', 'Book is not available for borrowing.');
        }

        // Check if student has reached borrowing limit
        if ($this->hasReachedBorrowingLimit($student)) {
            return redirect()->back()->with('error', 'You have reached the maximum borrowing limit.');
        }

        // Create book issue
        $issue = $this->createBookIssue($student, $id, $request->all());

        return redirect()->route('student.library.history')
            ->with('success', 'Book borrowed successfully! Issue ID: ' . $issue['id']);
    }

    public function history()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get borrowing history
        $currentIssues = $this->getCurrentIssues($student);
        $borrowingHistory = $this->getBorrowingHistory($student);
        $overdueBooks = $this->getOverdueBooks($student);
        $historyStats = $this->getHistoryStats($student);

        return view('student.library.history', compact(
            'currentIssues',
            'borrowingHistory',
            'overdueBooks',
            'historyStats'
        ));
    }

    public function renewBook(Request $request, $issueId)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Check if renewal is allowed
        if (!$this->canRenewBook($issueId)) {
            return redirect()->back()->with('error', 'This book cannot be renewed at this time.');
        }

        // Renew the book
        $renewal = $this->renewBookIssue($issueId);

        return redirect()->back()->with('success', 'Book renewed successfully! New due date: ' . $renewal['new_due_date']);
    }

    public function profile()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get library profile information
        $libraryProfile = $this->getLibraryProfile($student);
        $readingPreferences = $this->getReadingPreferences($student);
        $favoriteBooks = $this->getFavoriteBooks($student);
        $readingHistory = $this->getReadingHistory($student);

        return view('student.library.profile', compact(
            'libraryProfile',
            'readingPreferences',
            'favoriteBooks',
            'readingHistory'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $request->validate([
            'favorite_genres' => 'nullable|array',
            'reading_goals' => 'nullable|string|max:500',
            'notifications' => 'nullable|boolean',
            'auto_renewal' => 'nullable|boolean',
        ]);

        // Update library profile
        $this->updateLibraryProfile($student, $request->all());

        return redirect()->route('student.library.profile')
            ->with('success', 'Library profile updated successfully!');
    }

    private function getLibraryInfo()
    {
        // Mock data - replace with actual database queries
        return [
            'library_name' => 'University Central Library',
            'address' => '123 Library Street, Campus Area',
            'contact_phone' => '+1-555-0200',
            'contact_email' => 'library@university.edu',
            'librarian_name' => 'Dr. Emily Watson',
            'librarian_phone' => '+1-555-0201',
            'opening_hours' => 'Mon-Fri: 8:00 AM - 10:00 PM, Sat-Sun: 9:00 AM - 6:00 PM',
            'total_books' => 125000,
            'total_journals' => 5000,
            'total_ebooks' => 25000,
            'seating_capacity' => 500,
        ];
    }

    private function getCurrentBooks($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'BK-001',
                'title' => 'Introduction to Computer Science',
                'author' => 'John Smith',
                'isbn' => '978-0123456789',
                'issue_date' => '2024-03-01',
                'due_date' => '2024-03-15',
                'status' => 'Borrowed',
                'renewals_left' => 2,
            ],
            [
                'id' => 'BK-002',
                'title' => 'Data Structures and Algorithms',
                'author' => 'Jane Doe',
                'isbn' => '978-0123456790',
                'issue_date' => '2024-03-05',
                'due_date' => '2024-03-19',
                'status' => 'Borrowed',
                'renewals_left' => 1,
            ],
        ];
    }

    private function getRecentActivity($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'time' => '02:30 PM',
                'activity' => 'Book returned',
                'details' => 'Introduction to Computer Science',
                'type' => 'return',
            ],
            [
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'time' => '10:15 AM',
                'activity' => 'Book borrowed',
                'details' => 'Data Structures and Algorithms',
                'type' => 'borrow',
            ],
            [
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'time' => '03:45 PM',
                'activity' => 'Book renewed',
                'details' => 'Introduction to Computer Science',
                'type' => 'renewal',
            ],
        ];
    }

    private function getLibraryStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'books_borrowed' => 15,
            'books_returned' => 12,
            'current_borrowed' => 3,
            'overdue_books' => 0,
            'total_reading_time' => '45 hours',
            'favorite_genre' => 'Computer Science',
        ];
    }

    private function getFeaturedBooks()
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'BK-003',
                'title' => 'The Art of Programming',
                'author' => 'Donald Knuth',
                'cover_image' => 'book1.jpg',
                'rating' => 4.8,
                'category' => 'Computer Science',
            ],
            [
                'id' => 'BK-004',
                'title' => 'Clean Code',
                'author' => 'Robert Martin',
                'cover_image' => 'book2.jpg',
                'rating' => 4.6,
                'category' => 'Software Engineering',
            ],
            [
                'id' => 'BK-005',
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'cover_image' => 'book3.jpg',
                'rating' => 4.7,
                'category' => 'Software Engineering',
            ],
        ];
    }

    private function getNewArrivals()
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'BK-006',
                'title' => 'Machine Learning Fundamentals',
                'author' => 'Andrew Ng',
                'cover_image' => 'book4.jpg',
                'category' => 'Artificial Intelligence',
                'arrival_date' => '2024-03-10',
            ],
            [
                'id' => 'BK-007',
                'title' => 'Web Development with React',
                'author' => 'Alex Johnson',
                'cover_image' => 'book5.jpg',
                'category' => 'Web Development',
                'arrival_date' => '2024-03-08',
            ],
        ];
    }

    private function getBooks($search, $category, $author, $sortBy)
    {
        // Mock data - replace with actual database queries
        $books = [
            [
                'id' => 'BK-001',
                'title' => 'Introduction to Computer Science',
                'author' => 'John Smith',
                'isbn' => '978-0123456789',
                'category' => 'Computer Science',
                'publisher' => 'Tech Publishers',
                'year' => 2023,
                'pages' => 450,
                'rating' => 4.5,
                'availability' => 'Available',
                'location' => 'Shelf A-1',
                'cover_image' => 'book1.jpg',
            ],
            [
                'id' => 'BK-002',
                'title' => 'Data Structures and Algorithms',
                'author' => 'Jane Doe',
                'isbn' => '978-0123456790',
                'category' => 'Computer Science',
                'publisher' => 'Algorithm Press',
                'year' => 2022,
                'pages' => 380,
                'rating' => 4.7,
                'availability' => 'Borrowed',
                'location' => 'Shelf A-2',
                'cover_image' => 'book2.jpg',
            ],
            [
                'id' => 'BK-003',
                'title' => 'The Art of Programming',
                'author' => 'Donald Knuth',
                'isbn' => '978-0123456791',
                'category' => 'Computer Science',
                'publisher' => 'Classic Books',
                'year' => 2021,
                'pages' => 600,
                'rating' => 4.8,
                'availability' => 'Available',
                'location' => 'Shelf B-1',
                'cover_image' => 'book3.jpg',
            ],
        ];

        // Apply filters
        if ($search) {
            $books = array_filter($books, function($book) use ($search) {
                return stripos($book['title'], $search) !== false || 
                       stripos($book['author'], $search) !== false;
            });
        }

        if ($category !== 'all') {
            $books = array_filter($books, function($book) use ($category) {
                return $book['category'] === $category;
            });
        }

        if ($author !== 'all') {
            $books = array_filter($books, function($book) use ($author) {
                return $book['author'] === $author;
            });
        }

        // Apply sorting
        switch ($sortBy) {
            case 'title':
                usort($books, function($a, $b) { return strcmp($a['title'], $b['title']); });
                break;
            case 'author':
                usort($books, function($a, $b) { return strcmp($a['author'], $b['author']); });
                break;
            case 'year':
                usort($books, function($a, $b) { return $b['year'] - $a['year']; });
                break;
            case 'rating':
                usort($books, function($a, $b) { return $b['rating'] - $a['rating']; });
                break;
        }

        return $books;
    }

    private function getCategories()
    {
        // Mock data - replace with actual database queries
        return [
            'Computer Science',
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Literature',
            'History',
            'Philosophy',
            'Economics',
            'Psychology',
        ];
    }

    private function getAuthors()
    {
        // Mock data - replace with actual database queries
        return [
            'John Smith',
            'Jane Doe',
            'Donald Knuth',
            'Robert Martin',
            'Andrew Ng',
            'Alex Johnson',
        ];
    }

    private function performSearch($query, $type)
    {
        // Mock data - replace with actual database queries
        return [
            'books' => [
                [
                    'id' => 'BK-001',
                    'title' => 'Introduction to Computer Science',
                    'author' => 'John Smith',
                    'category' => 'Computer Science',
                    'availability' => 'Available',
                    'relevance_score' => 95,
                ],
            ],
            'journals' => [
                [
                    'id' => 'JRN-001',
                    'title' => 'Journal of Computer Science',
                    'author' => 'Various Authors',
                    'category' => 'Computer Science',
                    'availability' => 'Available',
                    'relevance_score' => 88,
                ],
            ],
            'ebooks' => [
                [
                    'id' => 'EBK-001',
                    'title' => 'Digital Computer Science',
                    'author' => 'Tech Authors',
                    'category' => 'Computer Science',
                    'availability' => 'Available',
                    'relevance_score' => 92,
                ],
            ],
        ];
    }

    private function getSearchSuggestions($query)
    {
        // Mock data - replace with actual database queries
        return [
            'Introduction to Computer Science',
            'Computer Science Fundamentals',
            'Advanced Computer Science',
            'Computer Science Principles',
        ];
    }

    private function getBookDetails($id)
    {
        // Mock data - replace with actual database queries
        return [
            'id' => $id,
            'title' => 'Introduction to Computer Science',
            'author' => 'John Smith',
            'isbn' => '978-0123456789',
            'category' => 'Computer Science',
            'publisher' => 'Tech Publishers',
            'year' => 2023,
            'pages' => 450,
            'rating' => 4.5,
            'description' => 'A comprehensive introduction to computer science concepts and programming fundamentals.',
            'availability' => 'Available',
            'location' => 'Shelf A-1',
            'cover_image' => 'book1.jpg',
            'language' => 'English',
            'edition' => '3rd Edition',
            'subjects' => ['Programming', 'Algorithms', 'Data Structures'],
        ];
    }

    private function getRelatedBooks($id)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'BK-002',
                'title' => 'Data Structures and Algorithms',
                'author' => 'Jane Doe',
                'cover_image' => 'book2.jpg',
                'rating' => 4.7,
            ],
            [
                'id' => 'BK-003',
                'title' => 'The Art of Programming',
                'author' => 'Donald Knuth',
                'cover_image' => 'book3.jpg',
                'rating' => 4.8,
            ],
        ];
    }

    private function getBookReviews($id)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 1,
                'user_name' => 'Student A',
                'rating' => 5,
                'comment' => 'Excellent book for beginners!',
                'date' => '2024-03-01',
            ],
            [
                'id' => 2,
                'user_name' => 'Student B',
                'rating' => 4,
                'comment' => 'Good content but could be more detailed.',
                'date' => '2024-02-28',
            ],
        ];
    }

    private function getBookAvailability($id)
    {
        // Mock data - replace with actual database queries
        return [
            'total_copies' => 5,
            'available_copies' => 3,
            'borrowed_copies' => 2,
            'reserved_copies' => 0,
            'estimated_return' => '2024-03-20',
        ];
    }

    private function isBookAvailable($id)
    {
        // Mock implementation - replace with actual database check
        return true;
    }

    private function hasReachedBorrowingLimit($student)
    {
        // Mock implementation - replace with actual database check
        return false;
    }

    private function createBookIssue($student, $bookId, $data)
    {
        // Mock implementation - replace with actual database insert
        return [
            'id' => 'ISS-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'book_id' => $bookId,
            'student_id' => $student->id,
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays($data['borrow_duration'])->format('Y-m-d'),
            'status' => 'Borrowed',
        ];
    }

    private function getCurrentIssues($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'ISS-001',
                'book_title' => 'Introduction to Computer Science',
                'author' => 'John Smith',
                'issue_date' => '2024-03-01',
                'due_date' => '2024-03-15',
                'status' => 'Borrowed',
                'renewals_left' => 2,
            ],
            [
                'id' => 'ISS-002',
                'book_title' => 'Data Structures and Algorithms',
                'author' => 'Jane Doe',
                'issue_date' => '2024-03-05',
                'due_date' => '2024-03-19',
                'status' => 'Borrowed',
                'renewals_left' => 1,
            ],
        ];
    }

    private function getBorrowingHistory($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'ISS-003',
                'book_title' => 'Clean Code',
                'author' => 'Robert Martin',
                'issue_date' => '2024-02-15',
                'return_date' => '2024-03-01',
                'status' => 'Returned',
                'days_borrowed' => 15,
            ],
            [
                'id' => 'ISS-004',
                'book_title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'issue_date' => '2024-02-01',
                'return_date' => '2024-02-15',
                'status' => 'Returned',
                'days_borrowed' => 14,
            ],
        ];
    }

    private function getOverdueBooks($student)
    {
        // Mock data - replace with actual database queries
        return [];
    }

    private function getHistoryStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'total_books_borrowed' => 25,
            'total_books_returned' => 22,
            'current_borrowed' => 3,
            'overdue_books' => 0,
            'average_borrowing_duration' => '12 days',
            'favorite_category' => 'Computer Science',
        ];
    }

    private function canRenewBook($issueId)
    {
        // Mock implementation - replace with actual database check
        return true;
    }

    private function renewBookIssue($issueId)
    {
        // Mock implementation - replace with actual database update
        return [
            'id' => $issueId,
            'new_due_date' => now()->addDays(14)->format('Y-m-d'),
        ];
    }

    private function getLibraryProfile($student)
    {
        // Mock data - replace with actual database queries
        return [
            'member_since' => '2024-01-15',
            'membership_type' => 'Student',
            'borrowing_limit' => 5,
            'current_borrowed' => 3,
            'total_books_borrowed' => 25,
            'favorite_genres' => ['Computer Science', 'Mathematics'],
            'reading_goals' => 'Read 2 books per month',
            'notifications' => true,
            'auto_renewal' => false,
        ];
    }

    private function getReadingPreferences($student)
    {
        // Mock data - replace with actual database queries
        return [
            'preferred_languages' => ['English', 'Spanish'],
            'preferred_formats' => ['Physical Books', 'E-books'],
            'reading_time' => 'Evening',
            'favorite_authors' => ['John Smith', 'Jane Doe'],
        ];
    }

    private function getFavoriteBooks($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'BK-001',
                'title' => 'Introduction to Computer Science',
                'author' => 'John Smith',
                'cover_image' => 'book1.jpg',
                'rating' => 5,
                'added_date' => '2024-02-15',
            ],
            [
                'id' => 'BK-002',
                'title' => 'Data Structures and Algorithms',
                'author' => 'Jane Doe',
                'cover_image' => 'book2.jpg',
                'rating' => 4,
                'added_date' => '2024-02-10',
            ],
        ];
    }

    private function getReadingHistory($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'book_title' => 'Clean Code',
                'author' => 'Robert Martin',
                'read_date' => '2024-03-01',
                'rating' => 5,
                'status' => 'Completed',
            ],
            [
                'book_title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'read_date' => '2024-02-15',
                'rating' => 4,
                'status' => 'Completed',
            ],
        ];
    }

    private function updateLibraryProfile($student, $data)
    {
        // Mock implementation - replace with actual database update
        return true;
    }
}
