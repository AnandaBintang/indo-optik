<?php

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;

class TestimonialPolicy
{
    /**
     * Anyone (including guests) can view the testimonials listing.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Anyone (including guests) can view a single testimonial.
     */
    public function view(?User $user, Testimonial $testimonial): bool
    {
        return true;
    }

    /**
     * Any authenticated user can submit a testimonial.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * A user can update a testimonial if they are the author OR have admin access.
     */
    public function update(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        return $testimonial->user_id !== null && $testimonial->user_id === $user->id;
    }

    /**
     * A user can delete a testimonial if they are the author OR have admin access.
     */
    public function delete(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        return $testimonial->user_id !== null && $testimonial->user_id === $user->id;
    }

    /**
     * Only admins / staff can restore a soft-deleted testimonial.
     */
    public function restore(User $user, Testimonial $testimonial): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Only admins / staff can permanently delete a testimonial.
     */
    public function forceDelete(User $user, Testimonial $testimonial): bool
    {
        return $user->hasAdminAccess();
    }
}
