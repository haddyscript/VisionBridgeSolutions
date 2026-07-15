<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\EmailPreviewStub;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = $this->availableTemplates();
        $selected = $request->query('template');
        $selected = in_array($selected, $templates, true) ? $selected : $templates[0];

        return view('admin.email-templates.index', [
            'templates' => $templates,
            'selected' => $selected,
        ]);
    }

    public function preview(string $template)
    {
        abort_unless(in_array($template, $this->availableTemplates(), true), 404);

        $data = $this->sampleData();

        // Mail classes pass their own arbitrary variable names (not just
        // model keys), so rather than pre-guessing every one, retry on each
        // "Undefined variable" error by stubbing in exactly the variable
        // it's missing, until the template renders or we give up.
        for ($attempt = 0; $attempt < 20; $attempt++) {
            try {
                return response(view('emails.'.$template, $data)->render());
            } catch (\Throwable $e) {
                if (preg_match('/Undefined variable \$(\w+)/', $e->getMessage(), $matches)) {
                    $name = $matches[1];

                    // A raw scalar passed alongside a model (e.g. PaymentFailedMail's
                    // int $amountDue) can't resolve itself the way a model-shaped
                    // EmailPreviewStub property access can — a template doing
                    // arithmetic directly on it (`$amountDue / 100`) needs a real
                    // number here, not a stub object. Mirrors the same $249.00
                    // convention EmailPreviewStub::resolve() uses for "amount"-like
                    // property names, so the preview stays consistent either way.
                    $data[$name] = preg_match('/(amount|price|total|fee|count|quantity|qty)/i', $name)
                        ? 24900
                        : new EmailPreviewStub($name);
                    continue;
                }

                return response(view('admin.email-templates.render-error', [
                    'template' => $template,
                    'message' => $e->getMessage(),
                ])->render());
            }
        }

        return response(view('admin.email-templates.render-error', [
            'template' => $template,
            'message' => 'Too many undefined variables to resolve automatically.',
        ])->render());
    }

    protected function availableTemplates(): array
    {
        $paths = glob(resource_path('views/emails/*.blade.php'));
        sort($paths);

        return array_map(fn ($path) => basename($path, '.blade.php'), $paths);
    }

    protected function sampleData(): array
    {
        $keys = [
            'user', 'project', 'reply', 'upload', 'subscription', 'payment',
            'subscriptionPayment', 'template', 'title', 'resetUrl', 'refundRequest',
            'recommendation', 'milestone', 'consultation', 'satisfactionSurvey',
            'projectRequest', 'contactMessage', 'intakeSubmission', 'signature',
            'announcement', 'carePlan', 'maintenancePlan', 'invoice',
        ];

        return collect($keys)->mapWithKeys(fn ($key) => [$key => new EmailPreviewStub($key)])->all();
    }
}
