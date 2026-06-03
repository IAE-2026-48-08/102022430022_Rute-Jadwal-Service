<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ScheduleController extends Controller
{
    #[OA\Get(
        path: '/api/v1/schedules',
        summary: 'Mengambil daftar rute dan jadwal',
        tags: ['Schedules'],
        parameters: [
            new OA\Parameter(
                name: 'X-IAE-KEY',
                description: 'NIM Mahasiswa',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data')
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
        summary: 'Mendaftarkan jadwal armada baru',
        tags: ['Schedules'],
        parameters: [
            new OA\Parameter(
                name: 'X-IAE-KEY',
                description: 'NIM Mahasiswa',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['route', 'departure_time', 'facilities', 'price'],
                properties: [
                    new OA\Property(property: 'route', type: 'string', example: 'Bandung - Jakarta'),
                    new OA\Property(property: 'departure_time', type: 'string', format: 'date-time', example: '2026-06-03 08:00:00'),
                    new OA\Property(property: 'facilities', type: 'string', example: 'AC, Reclining Seat, WiFi'),
                    new OA\Property(property: 'price', type: 'number', example: 150000)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Jadwal berhasil ditambahkan')
        ]
    )]
    public function store(Request $request)
    {
        $schedule = Schedule::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $schedule,
            'meta' => [
                'service_name' => 'Rute-Jadwal-Service',
                'api_version' => 'v1'
            ]
        ], 201); 
    }

    #[OA\Get(
        path: '/api/v1/schedules/{id}',
        summary: 'Mengambil detail informasi jadwal spesifik',
        tags: ['Schedules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID Jadwal',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'X-IAE-KEY',
                description: 'NIM Mahasiswa',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data'),
            new OA\Response(response: 404, description: 'Resource not found')
        ]
    )]
    public function show($id)
    {
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