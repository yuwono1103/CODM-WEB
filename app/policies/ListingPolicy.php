namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    /**
     * Menentukan apakah user bisa memperbarui (edit) listing.
     */
    public function update(User $user, Listing $listing): bool
    {
        // Hanya pemilik listing yang boleh mengedit
        return $user->id === $listing->user_id;
    }

    /**
     * Menentukan apakah user bisa menghapus listing.
     */
    public function delete(User $user, Listing $listing): bool
    {
        // Pemilik listing ATAU Admin boleh menghapus
        return $user->id === $listing->user_id || $user->role === 'admin';
    }
}