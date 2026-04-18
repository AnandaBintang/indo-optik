<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Public — everyone can browse the product list.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Public — everyone can view a product detail.
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Requires admin or staff access.
     */
    public function create(User $user): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can update the model.
     * Requires admin or staff access.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can delete the model.
     * Requires admin or staff access.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can restore the model.
     * Requires admin or staff access.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only full admins can force-delete products.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }
}
