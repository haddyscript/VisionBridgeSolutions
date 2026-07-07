<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A permissive fake object used only by the admin email-template preview
 * screen (Admin\EmailTemplateController). It stands in for whatever
 * model(s) a given Mail class normally injects, so every template in
 * resources/views/emails can be rendered with plausible placeholder data
 * without the controller needing to know each template's exact variable
 * shape. Any property access, method call, array access, or foreach loop
 * resolves to something sensible instead of throwing.
 */
class EmailPreviewStub implements \ArrayAccess, \IteratorAggregate, \Countable
{
    protected array $children = [];

    public function __construct(protected string $key = 'value')
    {
    }

    public function __get(string $name)
    {
        return $this->children[$name] ??= $this->resolve($name);
    }

    public function __call(string $name, array $arguments)
    {
        if ($name === 'format') {
            return Carbon::now()->format($arguments[0] ?? 'M d, Y');
        }

        if ($name === 'diffForHumans') {
            return 'in 3 days';
        }

        if (in_array($name, ['toArray', 'all', 'get'], true)) {
            return [];
        }

        return $this->children[$name.'()'] ??= new self($name);
    }

    public function __toString(): string
    {
        return (string) $this->resolve($this->key, forString: true);
    }

    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get((string) $offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }

    public function count(): int
    {
        return 2;
    }

    public function getIterator(): \Generator
    {
        yield new self('item');
        yield new self('item');
    }

    protected function resolve(string $key, bool $forString = false): mixed
    {
        $lower = strtolower($key);

        if (Str::contains($lower, ['url', 'link'])) {
            return '#';
        }

        if (preg_match('/(amount|price|total|fee|rate|percent|progress|count|quantity|qty)/', $lower)) {
            return $forString ? '249.00' : 249.00;
        }

        if (Str::endsWith($lower, ['_at', '_date']) || $lower === 'date') {
            return $forString ? Carbon::now()->format('M d, Y') : Carbon::now();
        }

        if ($lower === 'id' || Str::endsWith($lower, '_id')) {
            return 1;
        }

        if (Str::startsWith($lower, ['is_', 'has_'])) {
            return true;
        }

        $labels = [
            'name' => 'Jordan Smith',
            'email' => 'client@example.com',
            'status' => 'active',
            'title' => 'Sample Title',
            'description' => 'This is a sample description used for previewing this email.',
            'reason' => 'Sample reason provided by the client.',
            'label' => 'Document',
            'category' => 'document',
            'message' => 'This is a sample message body used for previewing this email.',
            'phone' => '(555) 123-4567',
            'version' => 1,
            'body' => 'Sample agreement body text.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (preview)',
        ];

        if (array_key_exists($lower, $labels)) {
            return $labels[$lower];
        }

        return $forString ? 'Sample '.Str::headline($key) : new self($key);
    }
}
