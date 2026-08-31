<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_role',
        'id_membership',
        'membership_expires_at',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'suspended_until',
        'suspend_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'              => 'hashed',
        'membership_expires_at' => 'datetime',
        'suspended_until'       => 'datetime',
    ];

    public function accountAppeals()
    {
        return $this->hasMany(AccountAppeal::class, 'user_id', 'id_user');
    }

    public function isCurrentlySuspended(): bool
    {
        if ($this->status !== 'blocked') {
            return false;
        }
        if (!$this->suspended_until) {
            return true; // Permanent suspend
        }
        return $this->suspended_until->isFuture();
    }

    public function getSuspendCountdownAttribute(): array
    {
        if (!$this->suspended_until) {
            return [
                'is_permanent' => true,
                'is_expired'   => false,
                'days'         => 0,
                'hours'        => 0,
                'minutes'      => 0,
                'seconds'      => 0,
                'formatted'    => 'Permanen (Sampai diaktifkan kembali oleh Admin)',
            ];
        }

        if ($this->suspended_until->isPast()) {
            return [
                'is_permanent' => false,
                'is_expired'   => true,
                'days'         => 0,
                'hours'        => 0,
                'minutes'      => 0,
                'seconds'      => 0,
                'formatted'    => 'Masa sanksi telah berakhir',
            ];
        }

        $now = now();
        $days = (int) $now->diffInDays($this->suspended_until);
        $hours = (int) $now->diffInHours($this->suspended_until) % 24;
        $minutes = (int) $now->diffInMinutes($this->suspended_until) % 60;
        $seconds = (int) $now->diffInSeconds($this->suspended_until) % 60;

        $parts = [];
        if ($days > 0) $parts[] = $days . ' Hari';
        if ($hours > 0) $parts[] = $hours . ' Jam';
        if ($minutes > 0) $parts[] = $minutes . ' Menit';
        if (empty($parts)) $parts[] = $seconds . ' Detik';

        return [
            'is_permanent'     => false,
            'is_expired'       => false,
            'days'             => $days,
            'hours'            => $hours,
            'minutes'          => $minutes,
            'seconds'          => $seconds,
            'target_timestamp' => $this->suspended_until->timestamp * 1000,
            'formatted'        => implode(' ', $parts) . ' (Hingga ' . $this->suspended_until->translatedFormat('d M Y, H:i') . ' WIB)',
        ];
    }

    /* =========================================================================
     | HELPER MEMBERSHIP & COUNTDOWN
     | ========================================================================= */

    public function isMembershipActive(): bool
    {
        if (!$this->id_membership) {
            return false;
        }
        if (!$this->membership_expires_at) {
            return true;
        }
        return $this->membership_expires_at->isFuture();
    }

    public function getRemainingDaysAttribute(): int
    {
        if (!$this->membership_expires_at) {
            return 0;
        }
        return max(0, (int) now()->diffInDays($this->membership_expires_at, false));
    }

    /**
     * Data countdown presisi (hari, jam, menit, detik & timestamp JS)
     */
    public function getMembershipCountdownAttribute(): array
    {
        if (!$this->membership_expires_at || $this->membership_expires_at->isPast()) {
            return [
                'is_expired'       => true,
                'days'             => 0,
                'hours'            => 0,
                'minutes'          => 0,
                'seconds'          => 0,
                'target_timestamp' => null,
                'formatted_target' => null,
            ];
        }

        return [
            'is_expired'       => false,
            'days'             => (int) now()->diffInDays($this->membership_expires_at),
            'hours'            => (int) now()->diffInHours($this->membership_expires_at) % 24,
            'minutes'          => (int) now()->diffInMinutes($this->membership_expires_at) % 60,
            'seconds'          => (int) now()->diffInSeconds($this->membership_expires_at) % 60,
            'target_timestamp' => $this->membership_expires_at->timestamp * 1000,
            'formatted_target' => $this->membership_expires_at->translatedFormat('d F Y H:i:s'),
        ];
    }

    /**
     * Cek apakah perlu notifikasi/alert peringatan (default: <= 3 hari tersisa)
     */
    public function needsMembershipRenewalWarning(int $thresholdDays = 3): bool
    {
        if (!$this->membership_expires_at || $this->membership_expires_at->isPast()) {
            return false;
        }
        return $this->remainingDays <= $thresholdDays;
    }

    public function getMaxUploadLimit(): int
    {
        if ($this->isMembershipActive() && $this->membership) {
            return (int) ($this->membership->max_upload ?? 5);
        }
        return 5; // Default limit untuk akun gratis/tanpa membership/paket kadaluarsa
    }

    public function canUploadProduct(): bool
    {
        $max = $this->getMaxUploadLimit();
        $current = Product::where('seller_id', $this->id_user)->count();
        return $current < $max;
    }

    public function canUseAds(): bool
    {
        if (!$this->isMembershipActive()) {
            return false;
        }
        $name = strtolower($this->membership?->name ?? '');
        return str_contains($name, 'diamond') || str_contains($name, 'gold') || str_contains($name, 'platinum');
    }

    /* =========================================================================
     | RELASI DATABASE
     | ========================================================================= */

    public function role()
    {
        return $this->belongsTo(
            Role::class,
            'id_role',
            'id_role'
        );
    }

    public function membership()
    {
        return $this->belongsTo(
            Membership::class,
            'id_membership',
            'id_membership'
        );
    }

    public function products()
    {
        return $this->hasMany(
            Product::class,
            'seller_id',
            'id_user'
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'buyer_id',
            'id_user'
        );
    }

    public function carts()
    {
        return $this->hasMany(
            Cart::class,
            'user_id',
            'id_user'
        );
    }

    public function wishlists()
    {
        return $this->hasMany(
            Wishlist::class,
            'user_id',
            'id_user'
        );
    }

    public function reports()
    {
        return $this->hasMany(
            Report::class,
            'user_id',
            'id_user'
        );
    }

    public function verificationsAsVerifier()
    {
        return $this->hasMany(
            Verification::class,
            'verifier_id',
            'id_user'
        );
    }

    public function identityVerification()
    {
        return $this->hasOne(
            IdentityVerification::class,
            'user_id',
            'id_user'
        );
    }

    public function identityVerificationsAsVerifier()
    {
        return $this->hasMany(
            IdentityVerification::class,
            'verifier_id',
            'id_user'
        );
    }

    public function identityVerifications()
    {
        return $this->hasMany(
            IdentityVerification::class,
            'user_id',
            'id_user'
        );
    }
}
