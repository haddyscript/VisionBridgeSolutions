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

        try {
            $html = view('emails.'.$template, $this->sampleData())->render();
        } catch (\Throwable $e) {
            $html = view('admin.email-templates.render-error', [
                'template' => $template,
                'message' => $e->getMessage(),
            ])->render();
        }

        return response($html);
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
