<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a paginated listing of users.
     */
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $role = $request->input("role");

        $users = User::query()
            ->when(
                $search,
                fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where("name", "like", "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("phone", "like", "%{$search}%");
                }),
            )
            ->when(
                $role !== null && $role !== "",
                fn($q) => $q->where("role", $role),
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $roles = [User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_USER];

        $roleCounts = [
            "total" => User::count(),
            "admin" => User::where("role", User::ROLE_ADMIN)->count(),
            "staff" => User::where("role", User::ROLE_STAFF)->count(),
            "user" => User::where("role", User::ROLE_USER)->count(),
        ];

        return view(
            "admin.users.index",
            compact("users", "search", "role", "roles", "roleCounts"),
        );
    }

    /**
     * Show the details of a specific user.
     */
    public function show(User $user): View
    {
        return view("admin.users.show", compact("user"));
    }

    /**
     * Update the role of the specified user.
     *
     * Only admins can promote/demote other users.
     * A user cannot change their own role through this endpoint.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        // Prevent the currently authenticated admin from changing their own role
        if ($request->user()->id === $user->id) {
            return redirect()
                ->route("admin.users.index")
                ->with(
                    "error",
                    "Anda tidak dapat mengubah peran akun Anda sendiri.",
                );
        }

        // Only full admins can assign the admin role
        if (
            $request->input("role") === User::ROLE_ADMIN &&
            !$request->user()->isAdmin()
        ) {
            return redirect()
                ->route("admin.users.index")
                ->with(
                    "error",
                    "Hanya admin yang dapat menetapkan peran admin kepada pengguna lain.",
                );
        }

        $validated = $request->validate([
            "role" => [
                "required",
                Rule::in([User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_USER]),
            ],
        ]);

        $oldRole = $user->role;
        $user->update(["role" => $validated["role"]]);

        return redirect()
            ->route("admin.users.index")
            ->with(
                "success",
                "Peran pengguna \"{$user->name}\" berhasil diubah dari {$oldRole} menjadi {$validated["role"]}.",
            );
    }

    /**
     * Remove the specified user from storage.
     *
     * Admins cannot delete their own account or other admin accounts through this action.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($request->user()->id === $user->id) {
            return redirect()
                ->route("admin.users.index")
                ->with(
                    "error",
                    "Anda tidak dapat menghapus akun Anda sendiri.",
                );
        }

        // Only super admins can delete other admin accounts
        if ($user->isAdmin() && !$request->user()->isAdmin()) {
            return redirect()
                ->route("admin.users.index")
                ->with(
                    "error",
                    "Anda tidak memiliki izin untuk menghapus akun admin.",
                );
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route("admin.users.index")
            ->with("success", "Pengguna \"{$name}\" berhasil dihapus.");
    }
}
