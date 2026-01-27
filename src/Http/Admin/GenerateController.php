<?php

declare(strict_types=1);

namespace TentaPress\StaticDeploy\Http\Admin;

use Illuminate\Http\Request;
use TentaPress\StaticDeploy\Services\StaticExporter;

final class GenerateController
{
    public function __invoke(Request $request, StaticExporter $exporter)
    {
        $data = $request->validate([
            'include_public_build' => ['nullable', 'boolean'],
            'include_public_assets' => ['nullable', 'boolean'],
            'include_favicon' => ['nullable', 'boolean'],
            'include_robots' => ['nullable', 'boolean'],
            'compress_html' => ['nullable', 'boolean'],
        ]);

        $result = $exporter->build([
            'include_public_build' => (bool) ($data['include_public_build'] ?? true),
            'include_public_assets' => (bool) ($data['include_public_assets'] ?? true),
            'include_favicon' => (bool) ($data['include_favicon'] ?? true),
            'include_robots' => (bool) ($data['include_robots'] ?? true),
            'compress_html' => (bool) ($data['compress_html'] ?? false),
        ]);

        $message = 'Static build generated. Pages: ' . $result['pages_written'] . ' · Warnings: ' . count($result['warnings']);

        return to_route('tp.static.index')
            ->with('tp_notice_success', $message);
    }
}
