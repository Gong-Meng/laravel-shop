<?php
namespace App\Console\Commands\Cron;

use App\Models\CrowdfundingProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\RefundCrowdfundingOrders;

class FinishCrowdfunding extends Command
{
    protected $signature = 'cron:finish-crowdfunding';

    protected $description = '�����ڳ�';

    public function handle()
    {
        CrowdfundingProduct::query()
            // �ڳ����ʱ�����ڵ�ǰʱ��
            ->where('end_at', '<=', Carbon::now())
            // �ڳ�״̬Ϊ�ڳ���
            ->where('status', CrowdfundingProduct::STATUS_FUNDING)
            ->get()
            ->each(function (CrowdfundingProduct $crowdfunding) {
                // ����ڳ�Ŀ�������ʵ���ڳ���
                if ($crowdfunding->target_amount > $crowdfunding->total_amount) {
                    // �����ڳ�ʧ���߼�
                    $this->crowdfundingFailed($crowdfunding);
                } else {
                    // ��������ڳ�ɹ��߼�
                    $this->crowdfundingSucceed($crowdfunding);
                }
            });
    }

    protected function crowdfundingSucceed(CrowdfundingProduct $crowdfunding)
    {
        // ֻ�轫�ڳ�״̬��Ϊ�ڳ�ɹ�����
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_SUCCESS,
        ]);
    }

    protected function crowdfundingFailed(CrowdfundingProduct $crowdfunding)
    {
        // ���ڳ�״̬��Ϊ�ڳ�ʧ��
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_FAIL,
        ]);
        dispatch(new RefundCrowdfundingOrders($crowdfunding));
    }
}
