<?php

namespace App\Http\Controllers;

use App\Models\CodeDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CodeDraftController extends Controller
{
    /**
     * Get all drafts for current user
     */
    public function index()
    {
        $drafts = CodeDraft::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'drafts' => $drafts
        ]);
    }

    /**
     * Create new draft
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file_name' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:50',
        ]);

        $draft = CodeDraft::create([
            'user_id' => Auth::id(),
            'title' => $request->title ?? 'Untitled',
            'content' => $request->content ?? '',
            'file_name' => $request->file_name ?? 'untitled.txt',
            'language' => $request->language ?? 'plaintext',
            'status' => 'draft',
            'last_saved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'draft' => $draft,
            'message' => 'Draft created successfully'
        ], 201);
    }

    /**
     * Auto-save: update draft content
     * This is the main auto-save endpoint
     */
    public function autoSave(Request $request, $id)
    {
        $draft = CodeDraft::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft not found'
            ], 404);
        }

        $draft->update([
            'content' => $request->content ?? $draft->content,
            'title' => $request->title ?? $draft->title,
            'file_name' => $request->file_name ?? $draft->file_name,
            'language' => $request->language ?? $draft->language,
            'last_saved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'draft' => $draft->fresh(),
            'saved_at' => $draft->last_saved_at->toIsoString(),
            'message' => 'Auto-saved'
        ]);
    }

    /**
     * Get single draft
     */
    public function show($id)
    {
        $draft = CodeDraft::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'draft' => $draft
        ]);
    }

    /**
     * Update draft (manual update)
     */
    public function update(Request $request, $id)
    {
        $draft = CodeDraft::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft not found'
            ], 404);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file_name' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:50',
            'status' => 'nullable|in:draft,saved,published',
        ]);

        $draft->update([
            'title' => $request->title ?? $draft->title,
            'content' => $request->content ?? $draft->content,
            'file_name' => $request->file_name ?? $draft->file_name,
            'language' => $request->language ?? $draft->language,
            'status' => $request->status ?? $draft->status,
            'last_saved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'draft' => $draft->fresh(),
            'message' => 'Draft updated successfully'
        ]);
    }

    /**
     * Delete draft
     */
    public function destroy($id)
    {
        $draft = CodeDraft::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft not found'
            ], 404);
        }

        $draft->delete();

        return response()->json([
            'success' => true,
            'message' => 'Draft deleted successfully'
        ]);
    }
}
