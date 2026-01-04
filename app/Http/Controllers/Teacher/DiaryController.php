<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DiaryEntry;

class DiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = DiaryEntry::where('user_id', $user->id)
            ->orderByDesc('is_pinned')
            ->orderByDesc('entry_date')
            ->orderByDesc('created_at');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('mood')) {
            $query->where('mood', $request->get('mood'));
        }

        $entries = $query->paginate(12)->withQueryString();

        return view('teacher.diary.index', compact('entries'));
    }

    public function create()
    {
        return view('teacher.diary.create');
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Diary store method called', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            $data = $request->validate([
                'title' => 'nullable|string|max:255',
                'entry_date' => 'required|date',
                'mood' => 'nullable|string|max:50',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:30',
                'content' => 'required|string',
                'is_pinned' => 'nullable|boolean',
            ]);

            $data['user_id'] = Auth::id();
            $data['is_pinned'] = (bool) ($data['is_pinned'] ?? false);
            if (isset($data['tags'])) {
                $data['tags'] = array_values(array_filter($data['tags']));
            }

            \Log::info('Validated data', $data);

            $entry = DiaryEntry::create($data);

            \Log::info('Diary entry created', ['entry_id' => $entry->id]);

            return redirect()->route('teacher.diary.index')->with('success', 'Diary entry created successfully.');
        } catch (\Exception $e) {
            \Log::error('Diary store error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Failed to save diary entry: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(DiaryEntry $diary)
    {
        $this->authorizeEntry($diary);
        return view('teacher.diary.edit', ['entry' => $diary]);
    }

    public function update(Request $request, DiaryEntry $diary)
    {
        $this->authorizeEntry($diary);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'entry_date' => 'required|date',
            'mood' => 'nullable|string|max:50',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:30',
            'content' => 'required|string',
            'is_pinned' => 'nullable|boolean',
        ]);

        $data['is_pinned'] = (bool) ($data['is_pinned'] ?? false);
        if (isset($data['tags'])) {
            $data['tags'] = array_values(array_filter($data['tags']));
        }

        $diary->update($data);

        return redirect()->route('teacher.diary.index')->with('success', 'Diary entry updated successfully.');
    }

    public function destroy(DiaryEntry $diary)
    {
        $this->authorizeEntry($diary);
        $diary->delete();
        return redirect()->route('teacher.diary.index')->with('success', 'Diary entry deleted successfully.');
    }

    public function togglePin(DiaryEntry $diary)
    {
        $this->authorizeEntry($diary);
        $diary->update(['is_pinned' => ! $diary->is_pinned]);
        return back()->with('success', 'Entry updated.');
    }

    private function authorizeEntry(DiaryEntry $entry): void
    {
        abort_unless($entry->user_id === Auth::id(), 403);
    }
}


