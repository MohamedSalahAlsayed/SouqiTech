<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::latest();

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'read', 'replied'])) {
            $query->where('status', $request->status);
        }

        // Search text
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $inquiries,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,read,replied',
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $inquiry->status = $request->status;
        $inquiry->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الرسالة بنجاح. / Inquiry status updated successfully.',
            'data'    => $inquiry,
        ]);
    }

    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الرسالة بنجاح. / Inquiry deleted successfully.',
        ]);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:3',
            'subject' => 'nullable|string|max:255',
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $replyMessage = $request->input('message');
        $subject = $request->input('subject') ?: 'Re: ' . ($inquiry->subject ?: 'Your Inquiry');

        $fromEmail = config('mail.from.address') ?: config('mail.support.address', 'noreply@example.com');
        $fromName = config('mail.from.name') ?: config('app.name');

        try {
            Mail::send([], [], function ($message) use ($inquiry, $replyMessage, $subject, $fromEmail, $fromName) {
                $message->to($inquiry->email, $inquiry->name)
                    ->subject($subject)
                    ->from($fromEmail, $fromName)
                    ->setBody(nl2br(e($replyMessage)), 'text/html');
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال الرد عبر البريد الإلكتروني. / Failed to send email reply.',
                'error' => $e->getMessage(),
            ], 500);
        }

        $inquiry->status = 'replied';
        $inquiry->save();

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرد إلى العميل بنجاح. / Reply sent successfully.',
            'data' => $inquiry,
        ]);
    }

    public function stats()
    {
        $total = Inquiry::count();
        $pending = Inquiry::where('status', 'pending')->count();
        $read = Inquiry::where('status', 'read')->count();
        $replied = Inquiry::where('status', 'replied')->count();

        // Calculate submissions for the last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateObj = now()->subDays($i);
            $date = $dateObj->format('Y-m-d');
            $dayName = $dateObj->format('D'); // e.g. Mon, Tue
            
            $count = Inquiry::whereDate('created_at', $date)->count();
            
            $chartData[] = [
                'date'  => $date,
                'day'   => $dayName,
                'count' => $count,
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'counters' => [
                    'total'   => $total,
                    'pending' => $pending,
                    'read'    => $read,
                    'replied' => $replied,
                ],
                'chart' => $chartData,
            ],
        ]);
    }
}
