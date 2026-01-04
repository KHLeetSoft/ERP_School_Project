<?php 

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'admin_id',
        'theme_settings',
        'status',
        'logo',
        'qr_code_limit',
        'qr_codes_generated',
        'qr_limit_paid',
        'qr_payment_amount',
        'qr_payment_date'
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'status' => 'boolean',
        'qr_limit_paid' => 'boolean',
        'qr_payment_date' => 'datetime'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the QR limit requests for this school.
     */
    public function qrLimitRequests()
    {
        return $this->hasMany(QrLimitRequest::class);
    }

    /**
     * Get the QR codes for this school.
     */
    public function qrCodes()
    {
        return $this->hasMany(SchoolQrCode::class);
    }

    /**
     * Check if school can generate more QR codes.
     */
    public function canGenerateQrCode()
    {
        return $this->qr_codes_generated < $this->qr_code_limit;
    }

    /**
     * Get remaining QR codes that can be generated.
     */
    public function getRemainingQrCodes()
    {
        return max(0, $this->qr_code_limit - $this->qr_codes_generated);
    }

    /**
     * Check if school needs to pay for additional QR codes.
     */
    public function needsPaymentForQrCodes()
    {
        return $this->qr_codes_generated >= 3 && !$this->qr_limit_paid;
    }
}
