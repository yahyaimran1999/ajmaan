<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $status = $user->status;
        $currentRoute = $request->route()->getName() ?? $request->path();

        // Blocked statuses - no access to any routes
        if (in_array($status, [User::STATUS_INACTIVE, User::STATUS_REJECTED, User::STATUS_DRAFT])) {
            $statusMessages = [
                User::STATUS_INACTIVE => 'Your account is inactive. Please contact support.',
                User::STATUS_REJECTED => 'Your account has been rejected. Please contact support.',
                User::STATUS_DRAFT => 'Your account has been deactivated. Please contact support.'
            ];
            
            return response()->json([
                'message' => $statusMessages[$status],
                'status' => $status
            ], 403);
        }

        // Limited access statuses - only profile update routes
        if (in_array($status, [User::STATUS_WAITING, User::STATUS_PENDING_DOCUMENTS])) {
            $allowedRoutes = [
                'me',
                'logout',
                'listing-users.update',
                'upload-file',
                'delete-file'
            ];

            $allowedPaths = [
                'api/me',
                'api/logout',
                'api/listing-users',
                'api/upload-file',
                'api/delete-file'
            ];

            $isAllowed = in_array($currentRoute, $allowedRoutes) || 
                        $this->pathMatches($request->path(), $allowedPaths);

            if (!$isAllowed) {
                $statusMessages = [
                    User::STATUS_WAITING => 'Please complete your profile. Your account is waiting for administrator approval.',
                    User::STATUS_PENDING_DOCUMENTS => 'Please complete your profile and submit required documents.'
                ];
                
                return response()->json([
                    'message' => $statusMessages[$status],
                    'status' => $this->getStatusLabel($status),
                    // 'required_action' => 'update_profile'
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Check if current path matches allowed paths
     */
    private function pathMatches(string $currentPath, array $allowedPaths): bool
    {
        foreach ($allowedPaths as $path) {
            if (str_starts_with($currentPath, $path)) {
                return true;
            }
        }
        return false;
    }

    private function getStatusLabel($status): string
    {
           $statusLabels = [
                'A' => 'Approved',
                'I' => 'Inactive',
                'W' => 'Waiting',
                'R' => 'Rejected',
                'D' => 'Draft',
                'U' => 'Pending Documents'
            ];
            
            return $statusLabels[$status] ?? $status;                
    }
}
