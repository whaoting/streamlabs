<?PHP

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{
    public function indexPage()
    {
        return view('dashboard');
    }

    public function GetDashboardInfo(Request $request)
    {
        $userId = $request->input('id');;

        $subDay = 30;

        $donationRevenue = $this->getDonationRevenue($userId, $subDay);
        $subscriberRevenue = $this->getSubscriberRevenue($userId, $subDay);
        $saleRevenue = $this->getMerchSalesRevenue($userId, $subDay);
        $saleTopResult = $this->getMerchSalesTopResult($userId, $subDay);
        $totalFollowers = $this->getFollowersCount($userId, $subDay);
        $data = $this->getCombinedList($userId, $subDay);

        $binding = [
            'donation_revenue' => !is_null($donationRevenue->total_revenue) ? $donationRevenue->total_revenue : 0,
            'subscriber_revenue' => !is_null($subscriberRevenue->total_revenue) ? $subscriberRevenue->total_revenue : 0,
            'merch_sales' => [
                'revenue' => !is_null($saleRevenue->total_revenue) ? $saleRevenue->total_revenue : 0,
                'top_result' => $saleTopResult,
            ],
            'total_followers' => $totalFollowers,
            'list' => $data,
        ];

        return view('dashboard', $binding);
    }

    // TODO: move to service
    private function getDonationRevenue(int $userId, int $subDays = 30)
    {
        $startDate = Carbon::now()->subDays($subDays);

        return DB::table('donations')
            ->select(DB::raw('SUM(amount) as total_revenue'))
            ->where('target_user_id', '=', $userId)
            ->where('created_at', '>=', $startDate)
            ->first();
    }

    private function getSubscriberRevenue(int $userId, int $subDays = 30)
    {
        $startDate = Carbon::now()->subDays($subDays);

        return DB::table('subscribers')
            ->select(DB::raw('SUM(CASE WHEN tier = 1 THEN 5 WHEN tier = 2 THEN 10 WHEN tier = 3 THEN 15 ELSE 0 END) as total_revenue'))
            ->where('target_user_id', '=', $userId)
            ->where('created_at', '>=', $startDate)
            ->first();
    }

    private function getMerchSalesRevenue(int $userId, int $subDays = 30)
    {
        $startDate = Carbon::now()->subDays($subDays);

        return DB::table('merch_sales')
            ->select(DB::raw('SUM(amount * price) as total_revenue'))
            ->where('user_id', '=', $userId)
            ->where('created_at', '>=', $startDate)
            ->first();
    }

    private function getMerchSalesTopResult(int $userId, int $subDays = 30)
    {
        $startDate = Carbon::now()->subDays($subDays);

        return DB::table('merch_sales')
            ->select('name', DB::raw('SUM(amount) as total_sales'))
            ->where('user_id', '=', $userId)
            ->where('created_at', '>=', $startDate)
            ->groupBy('name')
            ->orderByDesc('total_sales')
            ->limit(3)
            ->get();
    }

    private function getFollowersCount(int $userId, int $subDays = 30)
    {
        $startDate = Carbon::now()->subDays($subDays);

        return DB::table('followers')
            ->where('target_user_id', '=', $userId)
            ->where('created_at', '>=', $startDate)
            ->count();

    }

    private function getCombinedList(int $targetUserId, int $subDays = 30, int $size = 100)
    {
        $startDate = Carbon::now()->subDays($subDays);

        $follower = Follower::select(DB::raw('users.name as name'),
            'followers.user_id',
            'followers.target_user_id',
            DB::raw('followers.created_at as created_at'))
            ->join('users', 'followers.user_id', '=', 'users.id')
            ->where('followers.target_user_id', '=', $targetUserId)
            ->where('followers.created_at', '>=', $startDate)
            ->get();

        $subscriber = Subscriber::select(DB::raw('users.name as name'),
            'subscribers.user_id',
            'subscribers.target_user_id',
            DB::raw('subscribers.created_at as created_at'),
            'subscribers.tier')
            ->join('users', 'subscribers.user_id', '=', 'users.id')
            ->where('subscribers.target_user_id', '=', $targetUserId)
            ->where('subscribers.created_at', '>=', $startDate)
            ->get();

        $donation = Donation::select(DB::raw('users.name as name'),
            'donations.user_id',
            'donations.target_user_id',
            DB::raw('donations.created_at as created_at'),
            DB::raw('donations.amount as donation_amount'),
            'donations.currency')
            ->join('users', 'donations.user_id', '=', 'users.id')
            ->where('donations.target_user_id', '=', $targetUserId)
            ->where('donations.created_at', '>=', $startDate)
            ->get();

        $merchSale = MerchSale::select(DB::raw('users.name as name'),
            'merch_sales.user_id',
            DB::raw('merch_sales.name as merch_name'),
            DB::raw('merch_sales.created_at as created_at'),
            'merch_sales.amount', 'merch_sales.price')
            ->join('users', 'merch_sales.user_id', '=', 'users.id')
            ->where('merch_sales.user_id', '=', $targetUserId)
            ->where('merch_sales.created_at', '>=', $startDate)
            ->get();

        $data = $follower->concat($subscriber)->concat($donation)->concat($merchSale);

        return $data->sortByDesc('created_at')->take($size);
    }
}
