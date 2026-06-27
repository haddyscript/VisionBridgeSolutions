<?php

namespace Database\Seeders;

use App\Models\ServiceAgreementTemplate;
use Illuminate\Database\Seeder;

class ServiceAgreementTemplateSeeder extends Seeder
{
    /**
     * IMPORTANT: This is placeholder legal text, not a reviewed contract.
     * VisionBridge must have an actual attorney review and replace this
     * body (via the admin Service Agreement editor) before relying on any
     * signature collected against it.
     */
    public function run(): void
    {
        ServiceAgreementTemplate::updateOrCreate(
            ['version' => 1],
            [
                'title' => 'VisionBridge Solutions — Client Service Agreement',
                'is_active' => true,
                'body' => <<<'TEXT'
[PLACEHOLDER — REPLACE BEFORE LAUNCH. This text has not been reviewed by an attorney.]

This Client Service Agreement ("Agreement") is entered into between VisionBridge Solutions
("VisionBridge", "we", "us") and the undersigned client ("Client", "you") for the design,
development, and ongoing care of Client's website.

1. Scope of Work
   VisionBridge will design and develop a custom website for Client based on the information,
   content, and requirements Client provides during onboarding. Any work outside that agreed
   scope may require an additional fee, to be agreed upon in writing before work begins.

2. Payment Terms
   Client agrees to pay fifty percent (50%) of the total project fee prior to the start of
   development, and the remaining fifty percent (50%) upon Client's final approval of the
   completed website, prior to launch.

3. Review Period
   Once a milestone or the completed website is delivered for review, Client has seven (7)
   days to evaluate the work and request revisions within the agreed scope. If Client elects
   not to proceed during this period, VisionBridge will refund the initial payment, less any
   non-refundable payment processing fees and applicable transaction costs already incurred.

4. Ownership
   Upon full payment, Client owns the final website, its content, and associated assets,
   excluding any third-party licensed materials.

5. Maintenance
   Ongoing website maintenance and support are governed separately under VisionBridge's
   Website Care Plan terms, which begin only after the website has launched and Client has
   given final approval, unless otherwise agreed.

6. Acceptance
   By signing below, Client acknowledges having read and agreed to the terms of this Agreement.
TEXT,
            ]
        );
    }
}
