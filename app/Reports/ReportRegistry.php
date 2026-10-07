<?php

namespace App\Reports;

/**
 * Registri semua report. File grup di-require eksplisit (multi-class per file).
 */
class ReportRegistry
{
    private static ?array $definitions = null;

    private static function load(): void
    {
        if (self::$definitions !== null) {
            return;
        }

        require_once __DIR__.'/FinancialReports.php';
        require_once __DIR__.'/SavingsReports.php';
        require_once __DIR__.'/LoanReports.php';
        require_once __DIR__.'/MemberShuSyariahRatRatioReports.php';

        $classes = [
            // Financial
            Financial\BalanceSheetReport::class,
            Financial\ProfitLossReport::class,
            Financial\CashFlowReport::class,
            Financial\EquityChangesReport::class,
            Financial\TrialBalanceReport::class,
            Financial\GeneralLedgerReport::class,
            Financial\JournalReport::class,
            Financial\CashPositionReport::class,
            Financial\IncomeExpenseReport::class,
            Financial\ReceivablePayableReport::class,
            Financial\CashForecastReport::class,
            Financial\BudgetActualReport::class,
            Financial\InventoryValuationReport::class,
            // Savings
            Savings\SavingsSummaryReport::class,
            Savings\SavingsGrowthReport::class,
            Savings\SavingsMutationReport::class,
            Savings\DormantMembersReport::class,
            Savings\TopSaversReport::class,
            // Loans
            Loans\LoanPortfolioReport::class,
            Loans\DisbursementReport::class,
            Loans\CollectionReport::class,
            Loans\OverdueReport::class,
            Loans\AgingReport::class,
            Loans\MaturityReport::class,
            Loans\RestructuringReport::class,
            Loans\RiskSummaryReport::class,
            // Members / SHU / Syariah / RAT / Ratios
            Domain\MemberGrowthReport::class,
            Domain\MemberStatementReport::class,
            Domain\ShuReport::class,
            Domain\SyariahPortfolioReport::class,
            Domain\SyariahRevenueReport::class,
            Domain\RatAnnualReport::class,
            Domain\FinancialRatioReport::class,
        ];

        self::$definitions = [];
        foreach ($classes as $class) {
            /** @var ReportDefinition $def */
            $def = new $class;
            self::$definitions[$def->key()] = $def;
        }
    }

    /** @return array<string, ReportDefinition> */
    public static function all(): array
    {
        self::load();

        return self::$definitions;
    }

    public static function find(string $key): ?ReportDefinition
    {
        self::load();

        return self::$definitions[$key] ?? null;
    }

    public static function categories(): array
    {
        return [
            'financial' => __('reports.categories.financial'),
            'savings' => __('reports.categories.savings'),
            'loans' => __('reports.categories.loans'),
            'members' => __('reports.categories.members'),
            'shu' => __('reports.categories.shu'),
            'syariah' => __('reports.categories.syariah'),
            'rat' => __('reports.categories.rat'),
            'ratios' => __('reports.categories.ratios'),
            'custom' => __('reports.categories.custom'),
        ];
    }

    /** @return array<string, ReportDefinition> */
    public static function byCategory(string $category): array
    {
        return array_filter(self::all(), fn ($d) => $d->category() === $category);
    }
}
