<?php

namespace App\Services;

use App\Models\ServiceAgreementSignature;
use App\Models\ServiceAgreementTemplate;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

/**
 * Stamps the client's Care Plan selection and signature block onto the
 * uploaded Master Agreement PDF at signing time, so the client walks away
 * with their actual completed agreement instead of a blank template.
 *
 * Coordinates below are hardcoded against the current Master Agreement PDF
 * ("CLIENT WEBSITE DEVELOPMENT & WEBSITE CARE PLAN MASTER AGREEMENT-OFFICIAL
 * 6.30.pdf", pages 132-134) — extracted via `pdftotext -bbox-layout`, not
 * guessed. If the boss uploads a revised version with a different layout or
 * page count, these page numbers and y-coordinates will need updating.
 */
class AgreementPdfFiller
{
    private const PAGE_CARE_PLAN = 132;

    private const PAGE_CLIENT_INFO = 133;

    private const PAGE_CLIENT_SIGNATURE = 134;

    public function fill(ServiceAgreementTemplate $template, ServiceAgreementSignature $signature): string
    {
        $sourcePath = Storage::disk('local')->path($template->pdf_path);

        $pdf = new Fpdi('P', 'pt');
        $pageCount = $pdf->setSourceFile($sourcePath);

        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $templateId = $pdf->importPage($pageNumber);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            match ($pageNumber) {
                self::PAGE_CARE_PLAN => $this->fillCarePlanPage($pdf, $signature),
                self::PAGE_CLIENT_INFO => $this->fillClientInfoPage($pdf, $signature),
                self::PAGE_CLIENT_SIGNATURE => $this->fillClientSignaturePage($pdf, $signature),
                default => null,
            };
        }

        $outputPath = "agreements/{$signature->project_id}/agreement-filled-{$signature->id}.pdf";
        Storage::disk('local')->put($outputPath, $pdf->Output('S'));

        return $outputPath;
    }

    private function fillCarePlanPage(Fpdi $pdf, ServiceAgreementSignature $signature): void
    {
        $plan = $signature->project->carePlanAgreement?->maintenancePlan;

        if (! $plan) {
            return;
        }

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(20, 20, 20);

        $pdf->Text(72.024, 651.5, $plan->name);
        $pdf->Text(85, 702, number_format($plan->price / 100, 2));
    }

    private function fillClientInfoPage(Fpdi $pdf, ServiceAgreementSignature $signature): void
    {
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(20, 20, 20);

        $pdf->Text(72.024, 624.86, $signature->organization_name);
        $pdf->Text(72.024, 674.78, $signature->signer_name);
        $pdf->Text(72.024, 724.696, $signature->title);
    }

    private function fillClientSignaturePage(Fpdi $pdf, ServiceAgreementSignature $signature): void
    {
        $signatureImagePath = Storage::disk('local')->path($signature->signature_image_path);

        if (Storage::disk('local')->exists($signature->signature_image_path)) {
            $pdf->Image($signatureImagePath, 72.024, 105, 110, 33);
        }

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->Text(72.024, 183.3, $signature->signed_at->format('F j, Y'));
    }
}
