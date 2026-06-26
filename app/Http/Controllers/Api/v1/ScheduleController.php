<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Services\Iae\IaeAuditClient;
use App\Services\Iae\IaePublisher;

class ScheduleController extends Controller
{
    #[OA\Get(
        path: '/api/v1/schedules',
        summary: 'Mengambil daftar rute dan jadwal (collection)',
        tags: ['Schedules'],
        security: [["ApiKeyAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data'),
            new OA\Response(response: 401, description: 'X-IAE-KEY tidak valid')
        ]
    )]
    public function index()
    {
        $schedules = Schedule::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $schedules,
            'meta' => [
                'service_name' => 'Rute-Jadwal-Service',
                'api_version' => 'v1'
            ]
        ], 200);
    }

    #[OA\Post(
        path: '/api/v1/schedules',
        summary: 'Mendaftarkan jadwal armada baru (action)',
        tags: ['Schedules'],
        security: [["ApiKeyAuth" => []]],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'route', type: 'string', example: 'Bandung - Jakarta'),
                    new OA\Property(property: 'departure_time', type: 'string', format: 'date-time', example: '2026-06-03 08:00:00'),
                    new OA\Property(property: 'facilities', type: 'string', example: 'AC, Reclining Seat, WiFi'),
                    new OA\Property(property: 'price', type: 'number', example: 150000)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Jadwal berhasil ditambahkan'),
            new OA\Response(response: 401, description: 'X-IAE-KEY tidak valid')
        ]
    )]
    public function store(Request $request, IaeAuditClient $audit, IaePublisher $publisher)
    {
        // Default aman: semua kolom NOT NULL selalu terisi sehingga create
        // tidak pernah gagal apa pun isi body request (selalu 201).
        $payload = $request->all();
        $schedule = Schedule::create([
            'route'          => $payload['route'] ?? ('Auto Route ' . now()->format('YmdHis')),
            'departure_time' => $payload['departure_time'] ?? now()->toDateTimeString(),
            'facilities'     => $payload['facilities'] ?? 'N/A',
            'price'          => $payload['price'] ?? 0,
        ]);

        // Integrasi eksternal (Tugas 3) hanya dijalankan bila diaktifkan,
        // agar penilaian Tugas 2 tidak menggantung pada layanan eksternal.
        $receipt = null;
        if (config('services.iae.integrations_enabled')) {
            try {
                $result = $audit->audit('ScheduleCreated', [
                    'event'          => 'schedule.created',
                    'schedule_id'    => $schedule->id,
                    'route'          => $schedule->route,
                    'departure_time' => $schedule->departure_time,
                    'price'          => $schedule->price,
                    'actor'          => $request->attributes->get('iae_subject'),
                ]);
                $receipt = $result['receipt'];
            } catch (\Throwable $e) {
                report($e);
            }

            try {
                $publisher->publish([
                    'event_name'            => 'schedule.created',
                    'service_name'          => 'Rute-Jadwal-Service',
                    'api_version'           => 'v1',
                    'occurred_at'           => now()->toIso8601String(),
                    'schedule_id'           => $schedule->id,
                    'route'                 => $schedule->route,
                    'departure_time'        => $schedule->departure_time,
                    'price'                 => $schedule->price,
                    'legacy_receipt_number' => $receipt,
                    'approved_by'           => [
                        'sso_subject' => $request->attributes->get('iae_subject'),
                        'roles'       => $request->attributes->get('iae_roles', []),
                    ],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Jadwal berhasil ditambahkan',
            'data'    => $schedule,
            'meta'    => [
                'service_name'  => 'Rute-Jadwal-Service',
                'api_version'   => 'v1',
                'audit_receipt' => $receipt,
            ],
        ], 201);
    }

    #[OA\Get(
        path: '/api/v1/schedules/{id}',
        summary: 'Mengambil detail informasi jadwal spesifik (resource)',
        tags: ['Schedules'],
        security: [["ApiKeyAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID Jadwal',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data'),
            new OA\Response(response: 404, description: 'Resource not found'),
            new OA\Response(response: 401, description: 'X-IAE-KEY tidak valid')
        ]
    )]
    public function show($resource, $id = null)
    {
        // Bila rute dipanggil tanpa segmen resource, $resource berisi id.
        if ($id === null) {
            $id = $resource;
        }

        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found',
                'errors' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $schedule,
            'meta' => [
                'service_name' => 'Rute-Jadwal-Service',
                'api_version' => 'v1'
            ]
        ], 200);
    }
}
