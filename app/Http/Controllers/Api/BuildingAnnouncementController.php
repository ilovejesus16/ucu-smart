<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BuildingAnnouncement;
use App\Models\BuildingAnnouncementRead;
use Illuminate\Http\Request;

class BuildingAnnouncementController extends Controller
{
    /**
     * Retrieve announcements and unread count for a client.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'string', 'max:100'],
        ]);

        $clientId = $validated['client_id'];

        $announcements = BuildingAnnouncement::query()
            ->withExists([
                'reads as is_read' => function ($query) use ($clientId) {
                    $query->where('client_id', $clientId);
                },
            ])
            ->latest()
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'building_id' => $announcement->building_id,
                    'building_code' => $announcement->building_code,
                    'building_name' => $announcement->building_name,
                    'title' => $announcement->title,
                    'message' => $announcement->message,
                    'created_at' => $announcement->created_at,
                    'is_read' => (bool) $announcement->is_read,
                ];
            });

        return response()->json([
            'data' => $announcements,
            'unread_count' => $announcements->where('is_read', false)->count(),
        ]);
    }

    /**
     * Mark an announcement as read for this client.
     */
    public function markAsRead(Request $request, BuildingAnnouncement $announcement)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'string', 'max:100'],
        ]);

        BuildingAnnouncementRead::firstOrCreate(
            [
                'building_announcement_id' => $announcement->id,
                'client_id' => $validated['client_id'],
            ],
            [
                'read_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Announcement marked as read.',
        ]);
    }

    /**
     * Mark all announcements as read for this client.
     */
    public function markAllAsRead(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'string', 'max:100'],
        ]);

        $clientId = $validated['client_id'];

        $unreadIds = BuildingAnnouncement::query()
            ->whereDoesntHave('reads', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->pluck('id');

        foreach ($unreadIds as $announcementId) {
            BuildingAnnouncementRead::firstOrCreate(
                [
                    'building_announcement_id' => $announcementId,
                    'client_id' => $clientId,
                ],
                [
                    'read_at' => now(),
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'All announcements marked as read.',
        ]);
    }
}
