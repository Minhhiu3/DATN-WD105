<?php

namespace App\Console\Commands;

use App\Models\DiscountCode;
use Illuminate\Console\Command;

class DisableExpiredDiscountController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:disable-expired-discount-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
public function handle()
{
    try {
        DiscountCode::where('end_date', '<', now())
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info('Mã giảm giá hết hạn đã bị vô hiệu hóa.');
    } catch (\Exception $e) {
        $this->error('Đã xảy ra lỗi khi vô hiệu hóa mã giảm giá hết hạn: ' . $e->getMessage());
    }
}


}
