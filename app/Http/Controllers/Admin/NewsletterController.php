<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\NewsletterTemplate;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NewsletterController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
    }

    /**
     * Display the newsletter dashboard
     */
    public function dashboard()
    {
        $schoolId = Auth::user()->school_id;

        // Get comprehensive dashboard statistics
        $totalSubscribers = NewsletterSubscriber::bySchool($schoolId)->active()->count();
        $totalNewsletters = Newsletter::bySchool($schoolId)->count();
        $totalOpens = Newsletter::bySchool($schoolId)->sum('opened_count');
        $totalClicks = Newsletter::bySchool($schoolId)->sum('clicked_count');
        $totalBounces = Newsletter::bySchool($schoolId)->sum('bounced_count');
        $totalUnsubscribed = Newsletter::bySchool($schoolId)->sum('unsubscribed_count');

        // Calculate rates
        $averageOpenRate = $totalNewsletters > 0 ? ($totalOpens / max(1, $totalNewsletters)) * 100 : 0;
        $averageClickRate = $totalOpens > 0 ? ($totalClicks / max(1, $totalOpens)) * 100 : 0;

        // Get subscriber growth data
        $newSubscribersThisMonth = NewsletterSubscriber::bySchool($schoolId)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        // Get recent newsletters
        $recentNewsletters = Newsletter::bySchool($schoolId)
            ->with(['creator', 'template'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get top performing newsletters
        $topPerformingNewsletters = Newsletter::bySchool($schoolId)
            ->where('status', 'sent')
            ->where('sent_count', '>', 0)
            ->orderBy('opened_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($newsletter) {
                $newsletter->engagement_score = $newsletter->opened_count + ($newsletter->clicked_count * 2);
                return $newsletter;
            });

        // Get subscriber growth chart data
        $growthChartLabels = [];
        $growthChartData = [];
        $newSubscribersData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $growthChartLabels[] = $date->format('M d');
            
            $totalOnDate = NewsletterSubscriber::bySchool($schoolId)
                ->where('created_at', '<=', $date->endOfDay())
                ->count();
            $growthChartData[] = $totalOnDate;
            
            $newOnDate = NewsletterSubscriber::bySchool($schoolId)
                ->whereDate('created_at', $date)
                ->count();
            $newSubscribersData[] = $newOnDate;
        }

        // Get engagement chart data
        $engagementData = [
            'opens' => $totalOpens,
            'clicks' => $totalClicks,
            'bounces' => $totalBounces,
            'unsubscribed' => $totalUnsubscribed
        ];

        // Get category performance data
        $categoryLabels = [];
        $categoryOpenRates = [];
        $categoryClickRates = [];
        
        $categories = ['general', 'announcement', 'event', 'update', 'news'];
        foreach ($categories as $category) {
            $categoryNewsletters = Newsletter::bySchool($schoolId)
                ->where('category', $category)
                ->where('status', 'sent')
                ->where('sent_count', '>', 0);
            
            $totalSent = $categoryNewsletters->sum('sent_count');
            $totalOpened = $categoryNewsletters->sum('opened_count');
            $totalClicked = $categoryNewsletters->sum('clicked_count');
            
            $categoryLabels[] = ucfirst($category);
            $categoryOpenRates[] = $totalSent > 0 ? ($totalOpened / $totalSent) * 100 : 0;
            $categoryClickRates[] = $totalOpened > 0 ? ($totalClicked / $totalOpened) * 100 : 0;
        }

        // Get sending time analysis data
        $sendingTimeLabels = ['9 AM', '12 PM', '3 PM', '6 PM', '9 PM'];
        $sendingTimeData = [28.5, 35.2, 42.1, 38.7, 31.9]; // Sample data

        // Get geographic distribution (sample data for now)
        $topCountries = collect([
            (object)['country' => 'United States', 'total' => 450],
            (object)['country' => 'United Kingdom', 'total' => 120],
            (object)['country' => 'Canada', 'total' => 85],
            (object)['country' => 'Australia', 'total' => 65],
            (object)['country' => 'Germany', 'total' => 45]
        ]);

        // Get device and browser stats (sample data for now)
        $deviceStats = collect([
            (object)['device_type' => 'desktop', 'total' => 320],
            (object)['device_type' => 'mobile', 'total' => 280],
            (object)['device_type' => 'tablet', 'total' => 95]
        ]);

        $browserStats = collect([
            (object)['browser' => 'Chrome', 'total' => 280],
            (object)['browser' => 'Safari', 'total' => 180],
            (object)['browser' => 'Firefox', 'total' => 95],
            (object)['browser' => 'Edge', 'total' => 85],
            (object)['browser' => 'Other', 'total' => 55]
        ]);

        // Get weekly activity pattern (sample data for now)
        $weeklyActivity = [
            'Monday' => [12, 15, 18, 22, 25, 28, 30, 32, 35, 38, 40, 42, 45, 48, 50, 52, 55, 58, 60, 62, 65, 68, 70, 72],
            'Tuesday' => [15, 18, 22, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92],
            'Wednesday' => [18, 22, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92, 95],
            'Thursday' => [20, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92, 95, 98],
            'Friday' => [22, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92, 95, 98, 100],
            'Saturday' => [18, 22, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92, 95],
            'Sunday' => [15, 18, 22, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92]
        ];

        $maxHourlyActivity = 100;

        // Calculate change percentages (sample data for now)
        $openRateChange = 5.2;
        $clickRateChange = 3.8;
        $sentNewslettersThisMonth = Newsletter::bySchool($schoolId)
            ->where('status', 'sent')
            ->where('sent_at', '>=', now()->startOfMonth())
            ->count();

        return view('admin.communications.newsletter.dashboard', compact(
            'totalSubscribers',
            'totalNewsletters',
            'totalOpens',
            'totalClicks',
            'totalBounces',
            'totalUnsubscribed',
            'averageOpenRate',
            'averageClickRate',
            'newSubscribersThisMonth',
            'sentNewslettersThisMonth',
            'recentNewsletters',
            'topPerformingNewsletters',
            'growthChartLabels',
            'growthChartData',
            'newSubscribersData',
            'engagementData',
            'categoryLabels',
            'categoryOpenRates',
            'categoryClickRates',
            'sendingTimeLabels',
            'sendingTimeData',
            'topCountries',
            'deviceStats',
            'browserStats',
            'weeklyActivity',
            'maxHourlyActivity',
            'openRateChange',
            'clickRateChange'
        ));
    }

    /**
     * Display a listing of newsletters
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $query = Newsletter::bySchool($schoolId)
            ->with(['creator', 'template'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $newsletters = $query->paginate(15);

        return view('admin.communications.newsletter.index', compact('newsletters'));
    }

    /**
     * Show the form for creating a new newsletter
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        
        $templates = NewsletterTemplate::bySchool($schoolId)->active()->get();
        $categories = ['general', 'announcement', 'event', 'update', 'reminder', 'news'];
        $tags = ['important', 'urgent', 'featured', 'update', 'reminder', 'event'];

        return view('admin.communications.newsletter.create', compact(
            'templates',
            'categories',
            'tags'
        ));
    }

    /**
     * Store a newly created newsletter
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'template_id' => 'nullable|exists:newsletter_templates,id',
            'category' => 'required|in:general,announcement,event,update,reminder,news',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'scheduled_at' => 'nullable|date|after:now',
            'is_draft' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['school_id'] = Auth::user()->school_id;
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
            $data['status'] = $request->is_draft ? 'draft' : 'draft';

            if ($request->scheduled_at) {
                $data['status'] = 'scheduled';
                $data['scheduled_at'] = Carbon::parse($request->scheduled_at);
            }

            $newsletter = Newsletter::create($data);

            return redirect()->route('admin.communications.newsletter.index')
                ->with('success', 'Newsletter created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating newsletter: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating newsletter. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified newsletter
     */
    public function show($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)
            ->with(['creator', 'template', 'subscribers'])
            ->findOrFail($id);

        // Get analytics data
        $analytics = NewsletterAnalytics::where('newsletter_id', $id)
            ->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->get()
            ->pluck('count', 'event_type')
            ->toArray();

        return view('admin.communications.newsletter.show', compact('newsletter', 'analytics'));
    }

    /**
     * Show the form for editing the specified newsletter
     */
    public function edit($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);
        
        $templates = NewsletterTemplate::bySchool($schoolId)->active()->get();
        $categories = ['general', 'announcement', 'event', 'update', 'reminder', 'news'];
        $tags = ['important', 'urgent', 'featured', 'update', 'reminder', 'event'];

        return view('admin.communications.newsletter.edit', compact(
            'newsletter',
            'templates',
            'categories',
            'tags'
        ));
    }

    /**
     * Update the specified newsletter
     */
    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'template_id' => 'nullable|exists:newsletter_templates,id',
            'category' => 'required|in:general,announcement,event,update,reminder,news',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'scheduled_at' => 'nullable|date|after:now',
            'is_draft' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['updated_by'] = Auth::id();

            if ($request->scheduled_at) {
                $data['status'] = 'scheduled';
                $data['scheduled_at'] = Carbon::parse($request->scheduled_at);
            } elseif ($request->is_draft) {
                $data['status'] = 'draft';
                $data['scheduled_at'] = null;
            }

            $newsletter->update($data);

            return redirect()->route('admin.communications.newsletter.index')
                ->with('success', 'Newsletter updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating newsletter: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating newsletter. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified newsletter
     */
    public function destroy($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        try {
            $newsletter->delete();
            return redirect()->route('admin.communications.newsletter.index')
                ->with('success', 'Newsletter deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting newsletter: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting newsletter. Please try again.');
        }
    }

    /**
     * Send newsletter now
     */
    public function sendNow($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        if (!$newsletter->can_be_sent) {
            return redirect()->back()
                ->with('error', 'Newsletter cannot be sent in its current status.');
        }

        try {
            // Update status to sending
            $newsletter->update([
                'status' => 'sending',
                'updated_by' => Auth::id()
            ]);

            // Get active subscribers
            $subscribers = NewsletterSubscriber::bySchool($schoolId)->active()->get();
            
            // Update newsletter counts
            $newsletter->update([
                'total_subscribers' => $subscribers->count(),
                'status' => 'sent',
                'sent_at' => now(),
                'sent_by' => Auth::id()
            ]);

            // TODO: Implement actual email sending logic here
            // This would typically involve a queue job

            return redirect()->back()
                ->with('success', 'Newsletter sent successfully to ' . $subscribers->count() . ' subscribers!');
        } catch (\Exception $e) {
            Log::error('Error sending newsletter: ' . $e->getMessage());
            
            // Reset status on error
            $newsletter->update(['status' => 'draft']);
            
            return redirect()->back()
                ->with('error', 'Error sending newsletter. Please try again.');
        }
    }

    /**
     * Schedule newsletter
     */
    public function schedule($id, Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'scheduled_at' => 'required|date|after:now'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $newsletter->update([
                'status' => 'scheduled',
                'scheduled_at' => Carbon::parse($request->scheduled_at),
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()
                ->with('success', 'Newsletter scheduled successfully!');
        } catch (\Exception $e) {
            Log::error('Error scheduling newsletter: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error scheduling newsletter. Please try again.');
        }
    }

    /**
     * Cancel scheduled newsletter
     */
    public function cancelSchedule($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        if ($newsletter->status !== 'scheduled') {
            return redirect()->back()
                ->with('error', 'Newsletter is not scheduled.');
        }

        try {
            $newsletter->update([
                'status' => 'draft',
                'scheduled_at' => null,
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()
                ->with('success', 'Newsletter schedule cancelled successfully!');
        } catch (\Exception $e) {
            Log::error('Error cancelling newsletter schedule: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error cancelling newsletter schedule. Please try again.');
        }
    }

    /**
     * Duplicate newsletter
     */
    public function duplicate($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        try {
            $newNewsletter = $newsletter->replicate();
            $newNewsletter->title = $newsletter->title . ' (Copy)';
            $newNewsletter->status = 'draft';
            $newNewsletter->scheduled_at = null;
            $newNewsletter->sent_at = null;
            $newNewsletter->total_subscribers = 0;
            $newNewsletter->sent_count = 0;
            $newNewsletter->opened_count = 0;
            $newNewsletter->clicked_count = 0;
            $newNewsletter->bounced_count = 0;
            $newNewsletter->unsubscribed_count = 0;
            $newNewsletter->created_by = Auth::id();
            $newNewsletter->updated_by = Auth::id();
            $newNewsletter->sent_by = null;
            $newNewsletter->save();

            return redirect()->route('admin.communications.newsletter.edit', $newNewsletter->id)
                ->with('success', 'Newsletter duplicated successfully!');
        } catch (\Exception $e) {
            Log::error('Error duplicating newsletter: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error duplicating newsletter. Please try again.');
        }
    }

    /**
     * Preview newsletter
     */
    public function preview($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        return view('admin.communications.newsletter.preview', compact('newsletter'));
    }

    /**
     * Get newsletter statistics
     */
    public function getStatistics($id)
    {
        $schoolId = Auth::user()->school_id;
        $newsletter = Newsletter::bySchool($schoolId)->findOrFail($id);

        $stats = [
            'total_subscribers' => $newsletter->total_subscribers,
            'sent_count' => $newsletter->sent_count,
            'opened_count' => $newsletter->opened_count,
            'clicked_count' => $newsletter->clicked_count,
            'bounced_count' => $newsletter->bounced_count,
            'unsubscribed_count' => $newsletter->unsubscribed_count,
            'open_rate' => $newsletter->open_rate,
            'click_rate' => $newsletter->click_rate,
            'bounce_rate' => $newsletter->bounce_rate,
            'unsubscribe_rate' => $newsletter->unsubscribe_rate
        ];

        return response()->json($stats);
    }

    /**
     * Handle bulk actions for newsletters
     */
    public function bulkAction(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $action = $request->input('action');
        $newsletterIds = $request->input('newsletter_ids', []);

        if (empty($newsletterIds)) {
            return redirect()->back()->with('error', 'Please select newsletters to perform the action.');
        }

        $newsletters = Newsletter::bySchool($schoolId)->whereIn('id', $newsletterIds);

        try {
            switch ($action) {
                case 'delete':
                    $newsletters->delete();
                    $message = 'Selected newsletters deleted successfully!';
                    break;

                case 'send':
                    $draftNewsletters = $newsletters->where('status', 'draft')->get();
                    foreach ($draftNewsletters as $newsletter) {
                        // Update status to sending
                        $newsletter->update([
                            'status' => 'sending',
                            'updated_by' => Auth::id()
                        ]);
                        
                        // TODO: Queue newsletter sending job
                        // dispatch(new SendNewsletterJob($newsletter));
                    }
                    $message = count($draftNewsletters) . ' newsletters queued for sending!';
                    break;

                case 'schedule':
                    $draftNewsletters = $newsletters->where('status', 'draft')->get();
                    $scheduledAt = $request->input('scheduled_at');
                    
                    if (!$scheduledAt) {
                        return redirect()->back()->with('error', 'Please provide a scheduled date and time.');
                    }

                    foreach ($draftNewsletters as $newsletter) {
                        $newsletter->update([
                            'status' => 'scheduled',
                            'scheduled_at' => Carbon::parse($scheduledAt),
                            'updated_by' => Auth::id()
                        ]);
                    }
                    $message = count($draftNewsletters) . ' newsletters scheduled successfully!';
                    break;

                case 'duplicate':
                    $duplicatedCount = 0;
                    foreach ($newsletters->get() as $newsletter) {
                        $newNewsletter = $newsletter->replicate();
                        $newNewsletter->title = $newsletter->title . ' (Copy)';
                        $newNewsletter->status = 'draft';
                        $newNewsletter->scheduled_at = null;
                        $newNewsletter->sent_at = null;
                        $newNewsletter->total_subscribers = 0;
                        $newNewsletter->sent_count = 0;
                        $newNewsletter->opened_count = 0;
                        $newNewsletter->clicked_count = 0;
                        $newNewsletter->bounced_count = 0;
                        $newNewsletter->unsubscribed_count = 0;
                        $newNewsletter->created_by = Auth::id();
                        $newNewsletter->updated_by = Auth::id();
                        $newNewsletter->sent_by = null;
                        $newNewsletter->save();
                        $duplicatedCount++;
                    }
                    $message = $duplicatedCount . ' newsletters duplicated successfully!';
                    break;

                case 'export':
                    // TODO: Implement export functionality
                    $message = 'Export functionality coming soon!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid action specified.');
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error performing bulk action. Please try again.');
        }
    }
}
